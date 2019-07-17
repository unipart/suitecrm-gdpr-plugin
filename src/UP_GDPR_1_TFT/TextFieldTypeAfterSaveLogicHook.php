<?php
/**
 * Created by PhpStorm.
 * User: Riccardo De Leo
 */
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');


/**
 * Class TextFieldReviewLogicHook
 */
class TextFieldTypeAfterSaveLogicHook
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
        if ($bean->name == $bean->bean_type . "_". $bean->field_name) {
            return;
        }

        $bean->name = $bean->bean_type . "_". $bean->field_name;
        $bean->save();

        return;
    }

}
