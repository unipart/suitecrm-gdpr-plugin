<?php
/**
 * Created by PhpStorm.
 * User: Riccardo De Leo
 */
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');


/**
 * Class OpportunityLapsedInterestAfterSaveLogicHook
 */
class OpportunityLapsedInterestAfterSaveLogicHook
{
    /**
     * @param  object    $bean
     * @param  string    $event
     * @param  array     $arguments
     * @throws Exception
     */
    public function afterSaveAction($bean, $event, $arguments)
    {
        $lirBean = BeanFactory::newBean('UP_GDPR_5_LIR');

        $lirBeanList = $lirBean->get_list(
            $order_by = "",
            $where = "up_gdpr_5_lir.target_id = '" . $bean->id . "'",
            $row_offset = 0,
            $limit=-1,
            $max=-1,
            $show_deleted = 0
        );

        if (count($lirBeanList['list']) > 0) {
            return;
        }

        $date = new \DateTime();
        $date->add(new DateInterval('P180D'));

        $lirBean->name = $bean->name;
        $lirBean->target_id = $bean->id;
        $lirBean->lapse_date = $date->format('Y-m-d');
        $lirBean->days_before_lapse = $lirBean->lapsed_interest_period;
        $lirBean->status = 'legitimate';
        $lirBean->save();
        $lirBean->up_gdpr_5_lir_opportunities->add($bean);
        $lirBean->save();
    }

}
