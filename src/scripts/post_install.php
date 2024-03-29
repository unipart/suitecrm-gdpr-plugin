<?php
/**
 * Created by PhpStorm.
 * User: Riccardo De Leo
 */

/**
 * Post Installation migration Step 1
 *
 * This function generates TFT records in order to monitor all the text fields
 * of all the modules installed into the SuiteCRM instance
 */
function generateTextFieldTypes() {
    global $moduleList;
    global $beanList;

    foreach ($moduleList as $moduleName) {
        $beanName = $beanList[$moduleName];

        $beanFocus = BeanFactory::newBean($beanName);

        foreach ($beanFocus->field_defs as $fieldDef) {
            if ($fieldDef['type'] != 'text') {
                continue;
            }

            $tftBean = BeanFactory::newBean('UP_GDPR_1_TFT');

            $fftList = $tftBean->get_list(
                $order_by = "",
                $where = "up_gdpr_1_tft.name = '" . $beanName . "_" . $fieldDef['name'] . "'",
                $row_offset = 0,
                $limit=-1,
                $max=-1,
                $show_deleted = 0
            );

            if (count($fftList['list']) < 0) {
                echo '<p>Text Field Type migration: <i>' . $beanName . '_' . $fieldDef['name'] .
                    '</i> <b">Already exits</b></p>';
                continue;
            }

            $fftList = null;
            unset($fftList);

            $tftBean->name = $beanName . '_' . $fieldDef['name'];
            $tftBean->description = 'Created during Text Field Type module installation';
            $tftBean->bean_type = $beanName;
            $tftBean->field_name = $fieldDef['name'];
            $tftBean->enabled = 1;
            $tftBean->save();
            $tftBean = null;
            unset($tftBean);
            echo '<p>Text Field Type migration: <i>' . $beanName . '_' . $fieldDef['name'] .
                '</i> <b style="color: green;">Successfully created</b><p>';
        }
    }
}

/**
 * Post Installation migration Step 2
 *
 * Generate empty privacy preferences per each of the entries of the modules
 * defined in the variable $ppmToBeMigrated
 *
 * @param  array $ppmToBeMigrated = [
 *                  [
 *                      'module' => Module name,
 *                      'bean' => Bean name
 *                  ],
 *              ];
 * @throws Exception
 */
function generatePrivacyPreferences(array $ppmToBeMigrated)
{
    foreach ($ppmToBeMigrated as $toMigrate) {
        $migrationBean = BeanFactory::newBean($toMigrate['module']);

        $migrationList = $migrationBean->get_list(
            $order_by = "",
            $where = "",
            $row_offset = 0,
            $limit=-1,
            $max=-1,
            $show_deleted = 0
        );

        foreach ($migrationList['list'] as $resultBean) {
            $ppBean = BeanFactory::newBean('UP_GDPR_4_PP');

            $targetList = $ppBean->get_list(
                $order_by = "",
                $where = "up_gdpr_4_pp.target_type = '" . $toMigrate['bean'] .
                    "' AND up_gdpr_4_pp.target_id = ' . $resultBean->id . '",
                $row_offset = 0,
                $limit=-1,
                $max=-1,
                $show_deleted = 0
            );

            if (count($targetList['list']) > 0) {
                echo '<p>Privacy Preferences migration: nothing to generate for module <b>' .
                    $ppmToBeMigrated['module'] . '</b></p>';
                continue;
            }

            $dateTime = new \DateTime();

            $ppBean->name = $resultBean->name;
            $ppBean->target_id = $resultBean->id;
            $ppBean->target_type = $toMigrate['bean'];
            $ppBean->uuid = $ppBean->generateUuid();
            $ppBean->url = $ppBean->getUrl();
            $ppBean->privacy_preferences = "Privacy Preferences generated by GDPR migration on the: " . $dateTime->format('Y-m-d H:i:s')  . "\n";
            $ppBean->save();

            $ppBean = null;
            unset($ppBean);
            $targetList = null;
            unset($targetList);

            echo '<p>Privacy Preferences migration: ' . $ppmToBeMigrated['module'] . ' - ' . $resultBean->name .
                ' <b style="color: green;">Successfully created</b></p>';
        }

        $migrationBean = null;
        unset($migrationBean);
        $migrationList = null;
        unset($migrationList);
    }
}

/**
 * Post Installation migration Step 3
 *
 * Lapsed Interest Reviewer migration
 *
 * Per each opportunity, it generates a lapsed interest review that force to
 * review the legal interest of the opportunity 6 months after this migration
 */
function generateLapsedInterestReviews()
{
    $oBean = BeanFactory::newBean('Opportunities');

    $migrationList = $oBean->get_list(
        $order_by = "",
        $where = "",
        $row_offset = 0,
        $limit=-1,
        $max=-1,
        $show_deleted = 0
    );

    if (count($migrationList['list']) <= 0) {
        echo '<p>Lapsed Interest Review Migration: There are no opportunities in this SuiteCRMS instance</p>';
        return;
    }

    $date = new \DateTime();
    $date->add(new DateInterval('P180D'));

    foreach ($migrationList['list'] as $resultBean) {
        $lirBean = BeanFactory::newBean('UP_GDPR_5_LIR');

        $targetList = $lirBean->get_list(
            $order_by = "",
            $where = "up_gdpr_5_lir.target_id = ' . $resultBean->id . '",
            $row_offset = 0,
            $limit=-1,
            $max=-1,
            $show_deleted = 0
        );

        if (count($targetList['list']) > 0) {
            continue;
        }

        $lirBean->name = $resultBean->name;
        $lirBean->target_id = $resultBean->id;
        $lirBean->lapse_date = $date->format('Y-m-d');
        $lirBean->days_before_lapse = 179;
        $lirBean->status = 'legitimate';
        $lirBean->save();

        echo '<p>Lapsed Interest Review Migration: ' . $lirBean->name .
            ' <b style="color: green;">Successfully created</b></p>';

        $lirBean = null;
        unset($lirBean);
        $targetList = null;
        unset($targetList);
    }

    $oBean = null;
    unset($oBean);
    $migrationList = null;
    unset($migrationList);

}

/**
 * Main post_install script
 */
function post_install() {
    /**
     * UP_GDPR_4_PP - Privacy Preferences: Modules to be migrated
     *
     * Note: You can edit this list adding more type of modules that contains customer data that needs
     *       to be linked with privacy preferences module
     *
     * @var array
     */
    $ppmToBeMigrated = [
        [
            'module' => 'Contacts',
            'bean' => 'Contact'
        ], [
            'module' => 'Leads',
            'bean' => 'Lead'
        ], [
            'module' => 'Accounts',
            'bean' => 'Account'
        ]
    ];

    echo '<p><h1">Unipart - SuiteCRM GDPR Plugin</h1></p>';
    echo '<p><h2>Post installation migration script... <b>Started</b></h2></p>';
    $successFlag = true;

    try {
        echo '<p><h2>STEP 1 - Text Field Type Migration... <b>Started</b></h2></p>';
        generateTextFieldTypes();
        echo '<p><h2>STEP 1 - Text Field Type Migration... <b style="color: green;">Successfully completed</b></h2></p>';
    } catch (\Throwable $e) {
        echo '<p><h2>STEP 1 - Text Field Type Migration... <b style="color: darkred;">Error</b></h2></p>';
        echo '<p>' . $e->getMessage() . '</p>';
        echo '<p>' . $e->getTraceAsString() . '</p>';
        $successFlag = false;
    }

    try {
        echo '<p><h2>STEP 2 - Privacy Preferences Migration... <b>Started</b></h2></p>';
        generatePrivacyPreferences($ppmToBeMigrated);
        echo '<p><h2>STEP 2 - Privacy Preferences Migration... ' .
            '<b style="color: green;">Successfully completed</b></h2></p>';
    } catch (\Throwable $e) {
        echo '<p><h2>STEP 2 - Privacy Preferences Migration... <b style="color: darkred;">Error</b></h2></p>';
        echo '<p>' . $e->getMessage() . '</p>';
        echo '<p>' . $e->getTraceAsString() . '</p>';
        $successFlag = false;
    }

    try {
        echo '<p><h2>STEP 3 - Lapsed Interest Review Migration... <b>Started</b></h2></p>';
        generateLapsedInterestReviews();
        echo '<p><h2>STEP 3 - Lapsed Interest Review Migration... ' .
            '<b style="color: green;">Successfully completed</b></h2></p>';
    } catch (\Throwable $e) {
        echo '<p><h2>STEP 3 - Lapsed Interest Review Migration... <b style="color: darkred;">Error</b></h2></p>';
        echo '<p>' . $e->getMessage() . '</p>';
        echo '<p>' . $e->getTraceAsString() . '</p>';
        $successFlag = false;
    }

    $result = $successFlag
        ? '<p><h2>Post installation migration script... <b style="color: green;">Successfully executed</b></h2></p>'
        : '<p><h2>Post installation migration script... <b style="color: darkred;">Executed with errors</b></h2></p>';

    echo $result;
}
