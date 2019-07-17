<?php
/**
 * Created by PhpStorm.
 * User: Riccardo De Leo
 */
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once('custom/modules/Administration/ExtendUpGdprToQuickRepairAndRebuild.php');


/**
 * Class ViewRepair
 */
class ViewRepair extends SugarView
{
    /**
     * @see SugarView::display()
     */
    public function display()
    {
        $randc = new ExtendUpGdprToQuickRepairAndRebuild();
        $randc->repairAndClearAll(array('clearAll'),array(translate('LBL_ALL_MODULES')), false,true);

        echo <<<EOHTML
<br /><br /><a href="index.php?module=Administration&action=index">{$GLOBALS['mod_strings']['LBL_DIAGNOSTIC_DELETE_RETURN']}</a>
EOHTML;
    }
}
