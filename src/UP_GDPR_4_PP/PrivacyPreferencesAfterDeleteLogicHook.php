<?php
/**
 * Created by PhpStorm.
 * User: Riccardo De Leo
 */
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');


/**
 * Class PrivacyPreferencesAfterDeleteLogicHook
 */
class PrivacyPreferencesAfterDeleteLogicHook
{
    /**
     * @var object
     */
    protected $ppBean;

    /**
     * @param object $bean
     * @param string $event
     * @param array  $arguments
     */
    public function afterDeleteAction($bean, $event, $arguments)
    {
        $this->ppBean = BeanFactory::newBean('UP_GDPR_4_PP');
        $targetBeanType = get_class($bean);

        $ppBeanList = $this->getPpListWhereTargetTypeAndTargetId(
            $targetBeanType, $bean->id
        );

        if (count($ppBeanList['list']) <= 0) {
            return;
        }

        foreach ($ppBeanList as $ppBean) {
            $ppBean->mark_deleted($ppBean->id);
            $ppBean->save();
        }
    }

    /**
     * @param  string $targetBeanType
     * @param  string $targetId
     * @return array
     */
    protected function getPpListWhereTargetTypeAndTargetId(string $targetBeanType, string $targetId) : array
    {
        return $this->ppBean->get_list(
            $order_by = "",
            $where = "up_gdpr_4_pp.target_type = '" . $targetBeanType . "' AND up_gdpr_4_pp.target_id = '" . $targetId . "'",
            $row_offset = 0,
            $limit=-1,
            $max=-1,
            $show_deleted = 0
        );
    }

}