<?php
/**
 * Created by PhpStorm.
 * User: Riccardo De Leo
 */
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once('modules/Administration/QuickRepairAndRebuild.php');


/**
 * Class ExtendUpGdprToQuickRepairAndRebuild
 */
class ExtendUpGdprToQuickRepairAndRebuild extends RepairAndClear
{
    /**
     * @param $selected_actions
     * @param $modules
     * @param bool $autoexecute
     * @param bool $show_output
     */
    public function repairAndClearAll($selected_actions, $modules, $autoexecute=false, $show_output=true)
    {
        parent::repairAndClearAll($selected_actions, $modules, $autoexecute, $show_output);

        $this->repairPrivacyPreferences();
        $this->repairLapsedInterestReview();
    }

    /**
     * Repair Privacy Preferences relationships
     * this method is required to fix after unipart-gdpr-plugin installation
     */
    public function repairPrivacyPreferences()
    {
        $ppBean = BeanFactory::getBean('UP_GDPR_4_PP');

        $repairList = $ppBean->get_list(
            $order_by = "",
            $where = "",
            $row_offset = 0,
            $limit=-1,
            $max=-1,
            $show_deleted = 0
        );

        foreach ($repairList['list'] as $privacyPreference) {
            $privacyPreference->url = $privacyPreference->getUrl();
            $privacyPreference->save();

            try {
                if ($privacyPreference->target_type == 'Contact') {
                    $privacyPreference->load_relationship('up_gdpr_4_pp_contacts');
                    $privacyPreference->up_gdpr_4_pp_contacts->add($privacyPreference->target_id);
                }

                if ($privacyPreference->target_type == 'Lead') {
                    $privacyPreference->load_relationship('up_gdpr_4_pp_leads');
                    $privacyPreference->up_gdpr_4_pp_leads->add($privacyPreference->target_id);
                }

                if ($privacyPreference->target_type == 'Account') {
                    $privacyPreference->load_relationship('up_gdpr_4_pp_accounts');
                    $privacyPreference->up_gdpr_4_pp_accounts->add($privacyPreference->target_id);
                }

                $privacyPreference->save();
            } catch (\Throwable $e) {
                $GLOBALS['log']->error(
                    'ExtendUpGdprToQuickRepairAndRebuild::repairPrivacyPreferences() error: ' . $e->getMessage()
                );
            }
        }

        echo "<p>Privacy Preferences ... repaired</p>";

        return;
    }

    /**
     * Repair Lapsed Interest Review relationships
     * this method is required to fix after unipart-gdpr-plugin installation
     */
    public function repairLapsedInterestReview()
    {
        $lirBean = BeanFactory::getBean('UP_GDPR_5_LIR');

        $repairList = $lirBean->get_list(
            $order_by = "",
            $where = "",
            $row_offset = 0,
            $limit=-1,
            $max=-1,
            $show_deleted = 0
        );

        foreach ($repairList['list'] as $lirFocus) {
            try {
                $today = new \DateTime();
                $lapseDate = new \DateTime($lirFocus->lapse_date);

                if ($today >= $lapseDate) {
                    $lirFocus->days_before_lapse = 0;
                    $lirFocus->status = 'lapsed';
                    $lirFocus->save();
                    $lirFocus->load_relationship('up_gdpr_5_lir_opportunities');
                    $lirFocus->up_gdpr_5_lir_opportunities->add($lirFocus->target_id);
                    $lirFocus->save();
                    continue;
                }

                $difference = $today->diff($lapseDate);
                $lirFocus->days_before_lapse = $difference->format('%a');
                $lirFocus->status = ($lirFocus->days_before_lapse <= 14) ? 'nearly_lapsed' : 'legitimate';
                $lirFocus->save();
                $lirFocus->load_relationship('up_gdpr_5_lir_opportunities');
                $lirFocus->up_gdpr_5_lir_opportunities->add($lirFocus->target_id);
                $lirFocus->save();
            } catch (\Throwable $e) {
                $GLOBALS['log']->error(
                    'ExtendUpGdprToQuickRepairAndRebuild::repairLapsedInterestReview() error: ' . $e->getMessage()
                );
            }
        }

        echo "<p>Lapsed Interest Review ... repaired</p>";

        return;
    }

}
