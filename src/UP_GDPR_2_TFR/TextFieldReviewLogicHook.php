<?php
/**
 * Created by PhpStorm.
 * User: Riccardo De Leo
 */
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');


/**
 * Class TextFieldReviewLogicHook
 */
class TextFieldReviewLogicHook
{
    /**
     * @var object
     */
    protected $tftBean;

    /**
     * @var object
     */
    protected $tfrBean;

    /**
     * @param object $bean
     * @param string $event
     * @param array  $arguments
     */
    public function afterSaveAction($bean, $event, $arguments)
    {
        $this->tftBean = BeanFactory::newBean('UP_GDPR_1_TFT');
        $this->tfrBean = BeanFactory::newBean('UP_GDPR_2_TFR');

        $targetBeanType = get_class($bean);

        $tftBeanList = $this->getTFTListWhereTargetBeanTypeAndEnabled(
            $targetBeanType
        );

        if (count($tftBeanList['list']) <= 0) {
            return;
        }

        foreach ($tftBeanList['list'] as $tftBean) {
            $targetField = $tftBean->field_name;
            $targetData = $bean->$targetField;

            if (empty($targetData)) {
                return;
            }

            $tfrBeanList = $this->getTFRListWhereTargetIdAndName($bean->id, $tftBean->name);

            if (count($tftBeanList['list']) > 0) {
                if ($this->duplicateCheck($tfrBeanList, $targetData) == true) {
                    return;
                }
            }

            $newReview = BeanFactory::newBean('UP_GDPR_2_TFR');
            $newReview->name = $targetBeanType . "_" . $targetField;
            $newReview->description = $targetData;
            $newReview->target_id = $bean->id;
            $newReview->save();
            global $current_user;
            $newReview->up_gdpr_2_tfr_users->add($current_user);
            $newReview->up_gdpr_2_tfr_up_gdpr_1_tft->add($tftBean);
            $newReview->save();
        }
    }

    /**
     * @param  string $targetBeanType
     * @return array
     */
    protected function getTFTListWhereTargetBeanTypeAndEnabled(string $targetBeanType) : array
    {
        return $this->tftBean->get_list(
            $order_by = "",
            $where = "up_gdpr_1_tft.bean_type = '" . $targetBeanType . "' AND up_gdpr_1_tft.enabled = '1'",
            $row_offset = 0,
            $limit=-1,
            $max=-1,
            $show_deleted = 0
        );
    }

    /**
     * @param  string $targetId
     * @param  string $name
     * @return array
     */
    protected function getTFRListWhereTargetIdAndName(string $targetId, string $name) : array
    {
        return $this->tfrBean->get_list(
            $order_by = "date_entered DESC",
            $where = "up_gdpr_2_tfr.target_id = '" . $targetId . "' AND up_gdpr_2_tfr.name = '" . $name . "'",
            $row_offset = 0,
            $limit=1,
            $max=-1,
            $show_deleted = 0
        );
    }

    /**
     * @param array  $tfrBeanList
     * @param string $targetDescription
     * @return bool
     */
    protected function duplicateCheck(array $tfrBeanList, string $targetDescription) : bool
    {
        return array_reduce(
            $tfrBeanList['list'],
            function ($carry, $tfrBean) use ($targetDescription) {
                $carry = ($carry == true)
                    ? $carry
                    : ($targetDescription == $tfrBean->description);
                return $carry;
            },
            false
        );
    }

}
