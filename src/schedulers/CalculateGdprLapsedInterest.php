<?php
/**
 * Created by PhpStorm.
 * User: Riccardo De Leo
 */
$job_strings[] = 'calculateGdprLapsedInterest';


/**
 * Return true if it runs successfully, false in case of failure.
 *
 * @return bool
 * @throws Exception
 */
function calculateGdprLapsedInterest()
{

    $lirBean = BeanFactory::newBean('UP_GDPR_5_LIR');

    $lirList = $lirBean->get_list(
        $order_by = "",
        $where = "",
        $row_offset = 0,
        $limit=-1,
        $max=-1,
        $show_deleted = 0
    );

    foreach ($lirList['list'] as $lirFocus) {
        try {
            $today = new \DateTime();
            $lapseDate = new \DateTime($lirFocus->lapse_date);

            if ($today >= $lapseDate) {
                $lirFocus->days_before_lapse = 0;
                $lirFocus->status = 'lapsed';
                $lirFocus->save();
                continue;
            }

            $difference = $today->diff($lapseDate);
            $lirFocus->days_before_lapse = $difference->format('%a');
            $lirFocus->status = ((int)$lirFocus->days_before_lapse <= 14) ? 'nearly_lapsed' : 'legitimate';
            $lirFocus->save();
        } catch(\Throwable $e) {
            $GLOBALS['log']->error(
                'calculateGdprLapsedInterest error: ' . $e->getMessage()
            );

            $GLOBALS['log']->error(
                'calculateGdprLapsedInterest error: ' . $e->getTraceAsString()
            );
        }
    }

    return true;
}
