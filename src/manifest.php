<?php

/**
 * Created by PhpStorm.
 * User: Riccardo De Leo
 *
 * GDPR Plugin data to customize manifest.php -> $installdefs array
 */

/**
 * Copy EntryPoint and Schedulers files
 */
$installdefs['copy'][5] = [
    'from' => '<basepath>/entryPoints/UP_GDPR_4_PrivacyPreferencesEntryPointRegistry.php',
    'to' => 'custom/Extension/application/Ext/EntryPointRegistry/UP_GDPR_4_PrivacyPreferencesEntryPointRegistry.php',
];
$installdefs['copy'][6] = [
    'from' => '<basepath>/schedulers/CalculateGdprLapsedInterest.php',
    'to' => 'custom/Extension/modules/Schedulers/Ext/ScheduledTasks/CalculateGdprLapsedInterest.php',
];
$installdefs['copy'][7] = [
    'from' => '<basepath>/schedulers/en_us.calculateGdprLapsedInterest.php',
    'to' => 'custom/Extension/modules/Schedulers/Ext/Language/en_us.calculateGdprLapsedInterest.php',
];
$installdefs['copy'][8] = [
    'from' => '<basepath>/administration/ExtendUpGdprToQuickRepairAndRebuild.php',
    'to' => 'custom/modules/Administration/ExtendUpGdprToQuickRepairAndRebuild.php',
];
$installdefs['copy'][9] = [
    'from' => '<basepath>/administration/view.repair.php',
    'to' => 'custom/modules/Administration/views/view.repair.php',
];

/**
 * UP GDPR 1 Text Field Type
 */
$installdefs['logic_hooks'][] = [
    'module' => 'UP_GDPR_1_TFT',
    'hook' => 'after_save',
    'order' => 100,
    'description' => 'TextFieldTypeGenerateNameAfterSave',
    'file' => 'modules/UP_GDPR_1_TFT/TextFieldTypeAfterSaveLogicHook.php',
    'class' => 'TextFieldTypeAfterSaveLogicHook',
    'function' => 'afterSaveAction'
];


/**
 * UP GDPR 2 Text Field Review logic_hooks
 *
 * Edit this based on the module of the installation you need to deploy
 */

global $moduleList;

foreach ($moduleList as $moduleName) {
    $installdefs['logic_hooks'][] = [
        'module' => $moduleName,
        'hook' => 'after_save',
        'order' => 100,
        'description' => $moduleName . 'UPGDPRTextFieldReviewAfterSave',
        'file' => 'modules/UP_GDPR_2_TFR/TextFieldReviewLogicHook.php',
        'class' => 'TextFieldReviewLogicHook',
        'function' => 'afterSaveAction'
    ];
}

/**
 * UP GDPR 4 Privacy Preferences logic_hook
 */
$installdefs['logic_hooks'][] = [
    'module' => 'Leads',
    'hook' => 'after_save',
    'order' => 100,
    'description' => 'LeadsUPGDPRPrivacyPreferencesAfterSave',
    'file' => 'modules/UP_GDPR_4_PP/PrivacyPreferencesAfterSaveLogicHook.php',
    'class' => 'PrivacyPreferencesAfterSaveLogicHook',
    'function' => 'afterSaveAction'
];

$installdefs['logic_hooks'][] = [
    'module' => 'Contacts',
    'hook' => 'after_save',
    'order' => 100,
    'description' => 'ContactsUPGDPRPrivacyPreferencesAfterSave',
    'file' => 'modules/UP_GDPR_4_PP/PrivacyPreferencesAfterSaveLogicHook.php',
    'class' => 'PrivacyPreferencesAfterSaveLogicHook',
    'function' => 'afterSaveAction'
];

$installdefs['logic_hooks'][] = [
    'module' => 'Accounts',
    'hook' => 'after_save',
    'order' => 100,
    'description' => 'AccountsUPGDPRPrivacyPreferencesAfterSave',
    'file' => 'modules/UP_GDPR_4_PP/PrivacyPreferencesAfterSaveLogicHook.php',
    'class' => 'PrivacyPreferencesAfterSaveLogicHook',
    'function' => 'afterSaveAction'
];

$installdefs['logic_hooks'][] = [
    'module' => 'Leads',
    'hook' => 'after_delete',
    'order' => 100,
    'description' => 'LeadsUPGDPRPrivacyPreferencesAfterDelete',
    'file' => 'modules/UP_GDPR_4_PP/PrivacyPreferencesAfterDeleteLogicHook.php',
    'class' => 'PrivacyPreferencesAfterDeleteLogicHook',
    'function' => 'afterDeleteAction'
];

$installdefs['logic_hooks'][] = [
    'module' => 'Contacts',
    'hook' => 'after_delete',
    'order' => 100,
    'description' => 'ContactsUPGDPRPrivacyPreferencesAfterDelete',
    'file' => 'modules/UP_GDPR_4_PP/PrivacyPreferencesAfterDeleteLogicHook.php',
    'class' => 'PrivacyPreferencesAfterDeleteLogicHook',
    'function' => 'afterDeleteAction'
];

$installdefs['logic_hooks'][] = [
    'module' => 'Accounts',
    'hook' => 'after_delete',
    'order' => 100,
    'description' => 'AccountsUPGDPRPrivacyPreferencesAfterDelete',
    'file' => 'modules/UP_GDPR_4_PP/PrivacyPreferencesAfterDeleteLogicHook.php',
    'class' => 'PrivacyPreferencesAfterDeleteLogicHook',
    'function' => 'afterDeleteAction'
];

/**
 * UP GDPR 5 Lapsed Interest logic_hook
 */
$installdefs['logic_hooks'][] = [
    'module' => 'Opportunities',
    'hook' => 'after_save',
    'order' => 100,
    'description' => 'OpportunityLapsedInterestAfterSave',
    'file' => 'modules/UP_GDPR_5_LIR/OpportunityLapsedInterestAfterSaveLogicHook.php',
    'class' => 'OpportunityLapsedInterestAfterSaveLogicHook',
    'function' => 'afterSaveAction'
];

$installdefs['logic_hooks'][] = [
    'module' => 'UP_GDPR_5_LIR',
    'hook' => 'after_save',
    'order' => 100,
    'description' => 'LapsedInterestReviewAfterSave',
    'file' => 'modules/UP_GDPR_5_LIR/LapsedInterestReviewAfterSaveLogicHook.php',
    'class' => 'LapsedInterestReviewAfterSaveLogicHook',
    'function' => 'afterSaveAction'
];
