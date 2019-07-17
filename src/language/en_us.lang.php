<?php
/**
 * Created by PhpStorm.
 * User: Riccardo De Leo
 */
global $moduleList;
global $beanList;

/**
 * Note: It is not possible to just flip the $beanList
 */
$app_list_strings['gdpr_1_tft_bean_type_list'] = array_reduce(
    $moduleList,
    function ($carry, $moduleName) use ($beanList) {
        if (empty($moduleName) || !isset($beanList[$moduleName]) || empty($beanList[$moduleName])) {
            return $carry;
        }

        $carry[$beanList[$moduleName]] = $moduleName;
        return $carry;
    },
    []
);

$app_strings["LBL_GDPR_PP_PAGE_HEADER_META_DESCRIPTION"] = "Unipart - SuiteCRM GDPR Plugin";
$app_strings["LBL_GDPR_PP_PAGE_HEADER_META_AUTHOR"] = "Riccardo De Leo";
$app_strings["LBL_GDPR_PP_PAGE_HEADER_META_TITLE"] = "Unipart GDPR";
$app_strings["LBL_GDPR_PP_PAGE_BODY_TITLE"] = "Privacy Preferences Page";
$app_strings["LBL_GDPR_PP_PAGE_BODY_DESCRIPTION"] = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed ac ipsum neque. In hac habitasse platea dictumst. Etiam a mauris vitae metus luctus consequat. Sed lobortis faucibus ligula vitae pretium. Donec vitae velit est. Praesent nec dictum lectus, sodales cursus lectus. Praesent sem ex, feugiat eget mauris dictum, elementum convallis purus. Suspendisse potenti. Pellentesque et pharetra metus, et pulvinar nunc. Duis commodo venenatis dui ultrices ullamcorper. Nullam blandit aliquam enim, id pellentesque felis fringilla et.";
$app_strings["LBL_GDPR_PP_PAGE_BODY_FORM_SUBMIT_BUTTON"] = "Send GDPR Preferences &raquo;";
$app_strings["LBL_GDPR_PP_PAGE_FOOTER_TEXT"] = "© 2019 Unipart Digital Team. All rights reserved.";
$app_strings["LBL_GDPR_PP_PAGE_ERROR_EMPTY_UUID"] = "Invalid privacy preferences id, please contact our operation support.";
$app_strings["LBL_GDPR_PP_PAGE_ERROR_UNEXPECTED"] = "Unexpected internal server error, please contact our operation support.";
$app_strings["LBL_GDPR_PP_PAGE_ERROR_INVALID_UUID"] = "Invalid privacy preference unique id, please contact our operation support.";
$app_strings["LBL_GDPR_PP_PAGE_ERROR_SECURITY_MISMATCH"] = "Update privacy preferences error: security id does not match, please contact our operation support.";
$app_strings["LBL_GDPR_PP_PAGE_SUCCESS_UPDATE"] = "Privacy Preferences successfully updated.";