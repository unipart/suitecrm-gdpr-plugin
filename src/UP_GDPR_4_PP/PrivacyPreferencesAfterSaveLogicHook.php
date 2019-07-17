<?php
/**
 * Created by PhpStorm.
 * User: Riccardo De Leo
 */
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');


/**
 * Class PrivacyPreferencesAfterSaveLogicHook
 */
class PrivacyPreferencesAfterSaveLogicHook
{
    /**
     * @var object
     */
    protected $ppBean;

    /**
     * @param  object $bean
     * @param  string $event
     * @param  array  $arguments
     * @throws Exception
     */
    public function afterSaveAction($bean, $event, $arguments)
    {
        $this->ppBean = BeanFactory::newBean('UP_GDPR_4_PP');
        $targetBeanType = get_class($bean);

        $ppBeanList = $this->getPpListWhereTargetTypeAndTargetId(
            $targetBeanType, $bean->id
        );

        if (count($ppBeanList['list']) <= 0) {
            return;
        }

        $dateTime = new \DateTime();

        $this->ppBean->name = $bean->name;
        $this->ppBean->target_id = $bean->id;
        $this->ppBean->target_type = $targetBeanType;
        $this->ppBean->uuid = $this->ppBean->generateUuid();
        $this->ppBean->url = $this->ppBean->getUrl();
        $this->ppBean->privacy_preferences .= "=================================\n";
        $this->ppBean->privacy_preferences .= "Privacy Preferences generated on the: " . $dateTime->format('Y-m-d H:i:s')  . "\n";

        if ($targetBeanType == 'Contact') {
            $this->ppBean->load_relationship('up_gdpr_4_pp_contacts');
            $this->ppBean->up_gdpr_4_pp_contacts->add($bean);
        }

        if ($targetBeanType == 'Lead') {
            $this->ppBean->load_relationship('up_gdpr_4_pp_leads');
            $this->ppBean->up_gdpr_4_pp_leads->add($bean);
        }

        if ($targetBeanType == 'Account') {
            $this->ppBean->load_relationship('up_gdpr_4_pp_accounts');
            $this->ppBean->up_gdpr_4_pp_accounts->add($bean);
        }

        $this->ppBean->save();
//        exit('<pre>' . var_export($bean->id, true) . '</pre>');
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