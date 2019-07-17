<?php
/**
 * Created by PhpStorm.
 * User: Riccardo De Leo
 */
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');


/**
 * Class LapsedInterestReviewAfterSaveLogicHook
 */
class LapsedInterestReviewAfterSaveLogicHook
{
    /**
     * @param  object    $bean
     * @param  string    $event
     * @param  array     $arguments
     * @throws Exception
     */
    public function afterSaveAction($bean, $event, $arguments)
    {
        $today = new \DateTime();
        $lapseDate = new \DateTime($bean->lapse_date);

        if ($today >= $lapseDate) {
            $bean->days_before_lapse = 0;
            $bean->status = 'lapsed';
            $bean->save();
            return;
        }

        $difference = $today->diff($lapseDate);
        $bean->days_before_lapse = $difference->format('%a');
        $bean->status = ((int)$bean->days_before_lapse <= 14) ? 'nearly_lapsed' : 'legitimate';
        $bean->save();
    }

}
