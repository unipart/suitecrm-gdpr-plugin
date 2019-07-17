#!/bin/bash
#########################
# Author: Riccardo De Leo
#
# Description: Build the SuiteCRM GDPR Plugin.
#              it takes the skeleton built with module builder and add all bespoke code into the src/ folder.
#########################

#########################
# VARIABLES
#########################
TMP_BUILD_DIR="tmp/builds/"
DESTINATION_DIR_NAME="$(date +"%F_%H%M%S")/"
ORIGIN_FILE="./builds/latest/Unipart-GDPR-Plugin_Origin.zip"
OVERRIDE=0

#########################
# FUNCTIONS
#########################
function print_help {
    echo "Unipart -SuiteCRM GDPR Plugin builder"
    echo "This command takes the zip file generated from SuiteCRM Module Builder,"
    echo "unzip it into the tmp/ folder and adds the src/ bespoke files to it."
    echo
    echo "SYNOPSIS"
    echo "     ./build.sh [-dfho]"
    echo
    echo "COMMAND-LINE OPTIONS"
    echo
    echo "       -d --destination=DIR_NAME"
    echo "              Specify the directory name of the build's destination folder"
    echo "              The directory will be created within the directory tmp/"
    echo "              If not specified it will use the date time with the following format: Y-m-d_H-m-s"
    echo
    echo "       -f --file=PLUGIN_ORIGIN_FILE_PATH_NAME"
    echo "              The name and the path of the zip file generated from SuiteCRM Module Builder"
    echo "              Default name: ./builds/latests/Unipart-GDPR-Plugin_Origin.zip"
    echo
    echo "       -h --help"
    echo "              Print this help"
    echo
    echo "       -o --override"
    echo "              Delete the destination directory and override with the new installation files"
    echo
}

#########################
# MAIN
#########################
while [ "$1" != "" ]; do
    case $1 in
        -d | --destination )    shift
                                DESTINATION_DIR=$1
                                ;;
        -f | --file )           shift
                                ORIGIN_FILE=$1
                                ;;
        -h | --help )           print_help | less
                                exit 0
                                ;;
        -o | --override )       OVERRIDE=1
                                ;;
        * )                     print_help | less
                                exit 1
    esac
    shift
done

echo "Build Unipart GDPR Plugin... Started"

TARGET_DIR="${TMP_BUILD_DIR}${DESTINATION_DIR_NAME}"

if [ -d "${TARGET_DIR}" ]; then
    if [ "${OVERRIDE}" == "1" ]; then
        rm -rf ${TARGET_DIR}
    else
        echo
        echo "Build Unipart GDPR Plugin... Stopped because destination directory already exists and override parameter is not set"
        echo
        exit 0
    fi
fi

mkdir ${TARGET_DIR}

if [ -d "${TARGET_DIR}" ]; then
    echo "Build Unipart GDPR Plugin... Created target directory"
else
    echo
    echo "Build Unipart GDPR Plugin... ERROR creating target directory"
    echo
    exit 1
fi

unzip ${ORIGIN_FILE} -d ${TARGET_DIR}

if [ -f "${TARGET_DIR}manifest.php" ]; then
    echo "Build Unipart GDPR Plugin... Unzipped plugin installer"
else
    echo
    echo "Build Unipart GDPR Plugin... ERROR unzipping plugin installer"
    echo
    rm -rf ${TARGET_DIR}
    exit 1
fi

tail -n +2 src/manifest.php >> ${TARGET_DIR}manifest.php

echo "Build Unipart GDPR Plugin... Patched manifest file"

mkdir ${TARGET_DIR}administration/

if [ -d "${TARGET_DIR}administration/" ]; then
    echo "Build Unipart GDPR Plugin... Created administration directory"
else
    echo
    echo "Build Unipart GDPR Plugin... ERROR creating administration directory"
    echo
    exit 1
fi

cp -r src/administration/* ${TARGET_DIR}administration/

if [ -f "${TARGET_DIR}administration/ExtendUpGdprToQuickRepairAndRebuild.php" ] && [ -f "${TARGET_DIR}administration/view.repair.php" ]; then
    echo "Build Unipart GDPR Plugin... Copied administration files"
else
    echo
    echo "Build Unipart GDPR Plugin... ERROR coping administration files"
    echo
    exit 1
fi

mkdir ${TARGET_DIR}entryPoints/

if [ -d "${TARGET_DIR}entryPoints/" ]; then
    echo "Build Unipart GDPR Plugin... Created entryPoints directory"
else
    echo
    echo "Build Unipart GDPR Plugin... ERROR creating entryPoints directory"
    echo
    exit 1
fi

cp -r src/entryPoints/* ${TARGET_DIR}entryPoints/

if [ -f "${TARGET_DIR}entryPoints/UP_GDPR_4_PrivacyPreferencesEntryPointRegistry.php" ]; then
    echo "Build Unipart GDPR Plugin... Copied entryPoints files"
else
    echo
    echo "Build Unipart GDPR Plugin... ERROR coping entryPoints files"
    echo
    exit 1
fi

mkdir ${TARGET_DIR}entryPoints/

if [ -d "${TARGET_DIR}entryPoints/" ]; then
    echo "Build Unipart GDPR Plugin... Created entryPoints directory"
else
    echo
    echo "Build Unipart GDPR Plugin... ERROR creating entryPoints directory"
    echo
    exit 1
fi

cp -r src/entryPoints/* ${TARGET_DIR}entryPoints/

if [ -f "${TARGET_DIR}entryPoints/UP_GDPR_4_PrivacyPreferencesEntryPointRegistry.php" ]; then
    echo "Build Unipart GDPR Plugin... Copied entryPoints files"
else
    echo
    echo "Build Unipart GDPR Plugin... ERROR coping entryPoints files"
    echo
    exit 1
fi

tail -n +2 src/language/en_us.lang.php >> ${TARGET_DIR}SugarModules/language/application/en_us.lang.php

if [ -f "${TARGET_DIR}SugarModules/language/application/en_us.lang.php" ]; then
    echo "Build Unipart GDPR Plugin... Copied language file"
else
    echo
    echo "Build Unipart GDPR Plugin... ERROR coping language file"
    echo
    exit 1
fi

mkdir ${TARGET_DIR}schedulers/

if [ -d "${TARGET_DIR}schedulers/" ]; then
    echo "Build Unipart GDPR Plugin... Created schedulers directory"
else
    echo
    echo "Build Unipart GDPR Plugin... ERROR creating schedulers directory"
    echo
    exit 1
fi

cp -r src/schedulers/* ${TARGET_DIR}schedulers/

if [ -f "${TARGET_DIR}schedulers/CalculateGdprLapsedInterest.php" ] && [ -f "${TARGET_DIR}schedulers/en_us.calculateGdprLapsedInterest.php" ]; then
    echo "Build Unipart GDPR Plugin... Copied schedulers files"
else
    echo
    echo "Build Unipart GDPR Plugin... ERROR coping schedulers files"
    echo
    exit 1
fi

mkdir ${TARGET_DIR}scripts/

if [ -d "${TARGET_DIR}scripts/" ]; then
    echo "Build Unipart GDPR Plugin... Created scripts directory"
else
    echo
    echo "Build Unipart GDPR Plugin... ERROR creating scripts directory"
    echo
    exit 1
fi

cp -r src/scripts/* ${TARGET_DIR}scripts/

if [ -f "${TARGET_DIR}scripts/post_install.php" ]; then
    echo "Build Unipart GDPR Plugin... Copied scripts files"
else
    echo
    echo "Build Unipart GDPR Plugin... ERROR coping scripts files"
    echo
    exit 1
fi

cp src/UP_GDPR_1_TFT/TextFieldTypeAfterSaveLogicHook.php ${TARGET_DIR}SugarModules/modules/UP_GDPR_1_TFT/TextFieldTypeAfterSaveLogicHook.php

if [ -f "${TARGET_DIR}SugarModules/modules/UP_GDPR_1_TFT/TextFieldTypeAfterSaveLogicHook.php" ]; then
    echo "Build Unipart GDPR Plugin... Copied UP_GDPR_1_TFT - TextFieldTypeAfterSaveLogicHook.php"
else
    echo
    echo "Build Unipart GDPR Plugin... ERROR coping UP_GDPR_1_TFT - TextFieldTypeAfterSaveLogicHook.php"
    echo
    exit 1
fi


cp src/UP_GDPR_2_TFR/TextFieldReviewLogicHook.php ${TARGET_DIR}SugarModules/modules/UP_GDPR_2_TFR/TextFieldReviewLogicHook.php

if [ -f "${TARGET_DIR}SugarModules/modules/UP_GDPR_2_TFR/TextFieldReviewLogicHook.php" ]; then
    echo "Build Unipart GDPR Plugin... Copied UP_GDPR_2_TFR - TextFieldReviewLogicHook.php"
else
    echo
    echo "Build Unipart GDPR Plugin... ERROR coping UP_GDPR_2_TFR - TextFieldReviewLogicHook.php"
    echo
    exit 1
fi

cp src/UP_GDPR_4_PP/PrivacyPreferencesAfterSaveLogicHook.php ${TARGET_DIR}SugarModules/modules/UP_GDPR_4_PP/PrivacyPreferencesAfterSaveLogicHook.php

if [ -f "${TARGET_DIR}SugarModules/modules/UP_GDPR_4_PP/PrivacyPreferencesAfterSaveLogicHook.php" ]; then
    echo "Build Unipart GDPR Plugin... Copied UP_GDPR_4_PP - PrivacyPreferencesAfterSaveLogicHook.php"
else
    echo
    echo "Build Unipart GDPR Plugin... ERROR coping UP_GDPR_4_PP - PrivacyPreferencesAfterSaveLogicHook.php"
    echo
    exit 1
fi

cp src/UP_GDPR_4_PP/PrivacyPreferencesAfterDeleteLogicHook.php ${TARGET_DIR}SugarModules/modules/UP_GDPR_4_PP/PrivacyPreferencesAfterDeleteLogicHook.php

if [ -f "${TARGET_DIR}SugarModules/modules/UP_GDPR_4_PP/PrivacyPreferencesAfterDeleteLogicHook.php" ]; then
    echo "Build Unipart GDPR Plugin... Copied UP_GDPR_4_PP - PrivacyPreferencesAfterDeleteLogicHook.php"
else
    echo
    echo "Build Unipart GDPR Plugin... ERROR coping UP_GDPR_4_PP - PrivacyPreferencesAfterDeleteLogicHook.php"
    echo
    exit 1
fi

cp src/UP_GDPR_4_PP/PrivacyPreferencesEntryPoint.php ${TARGET_DIR}SugarModules/modules/UP_GDPR_4_PP/PrivacyPreferencesEntryPoint.php

if [ -f "${TARGET_DIR}SugarModules/modules/UP_GDPR_4_PP/PrivacyPreferencesEntryPoint.php" ]; then
    echo "Build Unipart GDPR Plugin... Copied UP_GDPR_4_PP - PrivacyPreferencesEntryPoint.php"
else
    echo
    echo "Build Unipart GDPR Plugin... ERROR coping UP_GDPR_4_PP - PrivacyPreferencesEntryPoint.php"
    echo
    exit 1
fi

cp src/UP_GDPR_4_PP/privacyPreferencesPageConfig.json ${TARGET_DIR}SugarModules/modules/UP_GDPR_4_PP/privacyPreferencesPageConfig.json

if [ -f "${TARGET_DIR}SugarModules/modules/UP_GDPR_4_PP/privacyPreferencesPageConfig.json" ]; then
    echo "Build Unipart GDPR Plugin... Copied UP_GDPR_4_PP - privacyPreferencesPageConfig.json"
else
    echo
    echo "Build Unipart GDPR Plugin... ERROR coping UP_GDPR_4_PP - privacyPreferencesPageConfig.json"
    echo
    exit 1
fi

head -n -1  ${TARGET_DIR}SugarModules/modules/UP_GDPR_4_PP/UP_GDPR_4_PP.php > ${TARGET_DIR}SugarModules/modules/UP_GDPR_4_PP/UP_GDPR_4_PP.bkp
rm -rf ${TARGET_DIR}SugarModules/modules/UP_GDPR_4_PP/UP_GDPR_4_PP.php
mv ${TARGET_DIR}SugarModules/modules/UP_GDPR_4_PP/UP_GDPR_4_PP.bkp ${TARGET_DIR}SugarModules/modules/UP_GDPR_4_PP/UP_GDPR_4_PP.php
tail -n +5  src/UP_GDPR_4_PP/UP_GDPR_4_PP.php >> ${TARGET_DIR}SugarModules/modules/UP_GDPR_4_PP/UP_GDPR_4_PP.php
echo "Build Unipart GDPR Plugin... Added code to UP_GDPR_4_PP - UP_GDPR_4_PP.php"

cp src/UP_GDPR_5_LIR/LapsedInterestReviewAfterSaveLogicHook.php ${TARGET_DIR}SugarModules/modules/UP_GDPR_5_LIR/LapsedInterestReviewAfterSaveLogicHook.php

if [ -f "${TARGET_DIR}SugarModules/modules/UP_GDPR_5_LIR/LapsedInterestReviewAfterSaveLogicHook.php" ]; then
    echo "Build Unipart GDPR Plugin... Copied UP_GDPR_5_LIR - LapsedInterestReviewAfterSaveLogicHook.php"
else
    echo
    echo "Build Unipart GDPR Plugin... ERROR coping UP_GDPR_5_LIR - LapsedInterestReviewAfterSaveLogicHook.php"
    echo
    exit 1
fi

cp src/UP_GDPR_5_LIR/OpportunityLapsedInterestAfterSaveLogicHook.php ${TARGET_DIR}SugarModules/modules/UP_GDPR_5_LIR/OpportunityLapsedInterestAfterSaveLogicHook.php

if [ -f "${TARGET_DIR}SugarModules/modules/UP_GDPR_5_LIR/OpportunityLapsedInterestAfterSaveLogicHook.php" ]; then
    echo "Build Unipart GDPR Plugin... Copied UP_GDPR_5_LIR - OpportunityLapsedInterestAfterSaveLogicHook.php"
else
    echo
    echo "Build Unipart GDPR Plugin... ERROR coping UP_GDPR_5_LIR - OpportunityLapsedInterestAfterSaveLogicHook.php"
    echo
    exit 1
fi
