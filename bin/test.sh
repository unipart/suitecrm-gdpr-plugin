#!/bin/bash
#########################
# Author: Riccardo De Leo
#
# Description: Test the SuiteCRM GDPR Plugin with behat features.
#              It clone suitecrm-dev-ops repository and copy:
#                   - behat configuration file gdpr.behat.yml;
#                   - behat contexts and suites from features folder;
#                   - plugin installer zip file from latest build.
#              For more information read the README.md file in the main repo folder.
#########################

#########################
# VARIABLES
#########################
TEST_SUITE="ALL"
CLONE_ONLY=0
TEST_ENV_DIR="suitecrm-dev-ops"
TMP_DIR="tmp"
PLUGIN_ZIP_FILE="builds/latest/Unipart-GDPR-Plugin_Installer.zip"
PLUGIN_ZIP_FILE_DESTINATION="volumes/selenium/upload/Unipart-GDPR-Plugin_Installer.zip"
BEHAT_CONF_FILE="gdpr.behat.yml"
TEST_SUITE_FLAG=0
PROJECT_DIR=$( pwd )
OVERRIDE=0

#########################
# FUNCTIONS
#########################
function print_help {
    echo "Unipart -SuiteCRM GDPR Plugin - Build test environment"
    echo "This command clone the suitecrm-dev-ops repository, copy the behat files into it,"
    echo "the installer zip file, than take care of running the required behat test suites."
    echo
    echo "SYNOPSIS"
    echo "     ./test.sh [-acdfhopst]"
    echo
    echo "COMMAND-LINE OPTIONS"
    echo
    echo "       -a --all-tests"
    echo "              Run all tests. It is the default option."
    echo
    echo "       -c --clone-only"
    echo "              Only clone the test environment repository and copy the test files."
    echo
    echo "       -d --destination=DESTINATION_ENV_DIR_NAME"
    echo "              Specify the directory name of the test environment repository."
    echo "              The destination directory will be put into the temporary one."
    echo "              Default destination directory: suitecrm-dev-ops."
    echo
    echo "       -f --file=PLUGIN_INSTALLER_FILE_PATH_NAME"
    echo "              The name and the path from the project directory of the Unipart - SuiteCRM"
    echo "              GDPR Plugin Installer zip file."
    echo "              Default: builds/latest/Unipart-GDPR-Plugin_Installer.zip."
    echo
    echo "       -h --help"
    echo "              Print this help."
    echo
    echo "       -o --override"
    echo "              If exists, delete the test environment and re-create it."
    echo
    echo "       -p --project-dir=PROJECT_DIRECTORY"
    echo "              If you want to execute the script from a different directory than the project"
    echo "              root one, you have to specify the absolute path of the project directory."
    echo
    echo "       -s --suite=BEHAT_SUITE_NAME"
    echo "              Run the specified behat suite. If takes priority over the -a --all-tests option."
    echo
    echo "       -t --temp-dir=TEMPORARY_DIRECTORY"
    echo "              The name of the temporary directory, usually tmp."
    echo
}

function parse_dir {
    DIR=$1

    CHAR_L=$(echo -n ${DIR} | head -c 1)
    CHAR_R=$(echo -n ${DIR} | tail -c -1)

    if [ "${CHAR_L}" == "/" ]; then
        DIR=${DIR#?}
    fi
    if [ "${CHAR_R}" == "/" ]; then
        DIR=${DIR%?}
    fi

    return ${DIR}
}

function parse_test_suite {
    SUITE=$1

    case ${SUITE}} in
        ALL )
        ;;
        GdprPlugin-Install | Install )
            SUITE="GdprPlugin-Install"
        ;;
        GdprPlugin-PrivacyCampaignsAndPreferences | PrivacyCampaignsAndPreferences )
            SUITE="GdprPlugin-PrivacyCampaignsAndPreferences"
        ;;
        GdprPlugin-TextFieldTypesAndReviews | TextFieldTypesAndReviews )
            SUITE="GdprPlugin-TextFieldTypesAndReviews"
        ;;
        GdprPlugin-LapsedInterestDate | LapsedInterestDate )
            SUITE="GdprPlugin-LapsedInterestDate"
        ;;
        * )
            echo
            echo "Test Unipart GDPR Plugin... ERROR: invalid test suite name ${SUITE}"
            echo
            exit 1
    esac

    return ${SUITE}
}


function run_all_tests {
    docker-compose run --rm behat --config gdpr.behat.yml --suite GdprPlugin-Install
    docker-compose run --rm behat --config gdpr.behat.yml --suite GdprPlugin-PrivacyCampaignsAndPreferences
    docker-compose run --rm behat --config gdpr.behat.yml --suite GdprPlugin-TextFieldTypesAndReviews
    docker-compose run --rm behat --config gdpr.behat.yml --suite GdprPlugin-LapsedInterestDate
}

#########################
# MAIN
#########################
while [ "$1" != "" ]; do
    case $1 in
        -a | --all )            shift
                                if [ ${TEST_SUITE_FLAG} == 0 ]; then
                                    TEST_SUITE="ALL"
                                fi
                                ;;
        -c | --clone-only )     CLONE_ONLY=1
                                ;;
        -d | --destination )    shift
                                TEST_ENV_DIR=$(parse_dir $1)
                                ;;
        -f | --file )           shift
                                PLUGIN_ZIP_FILE=$1
                                ;;
        -h | --help )           print_help | less
                                exit 0
                                ;;
        -o | --override )       OVERRIDE=1
                                ;;
        -p | --project-dir )    shift
                                PROJECT_DIR="/$(parse_dir $1)"
                                ;;
        -s | --suite )          shift
                                TEST_SUITE=$(parse_test_suite $1)
                                TEST_SUITE_FLAG=1
                                ;;
        -t | --temp-dir )       shift
                                TMP_DIR=$(parse_dir $1)
                                ;;
        * )                     print_help | less
                                exit 1
    esac
    shift
done

echo "Test Unipart GDPR Plugin... Started"

echo "Test Unipart GDPR Plugin... Cloning docker test environment repository"
if [ -d "${PROJECT_DIR}/${TMP_DIR}/${TEST_ENV_DIR}" ]; then
    if [ ${OVERRIDE} == 0 ]; then
        echo "Test Unipart GDPR Plugin... Docker test environment repository already cloned"
    else
        cd ${PROJECT_DIR}/${TMP_DIR}/${TEST_ENV_DIR}
        docker-compose down
        cd ${PROJECT_DIR}/${TMP_DIR}
        rm -rf cd ${PROJECT_DIR}/${TMP_DIR}/${TEST_ENV_DIR}
        git clone https://github.com/unipart/suitecrm-dev-ops.git ${TEST_ENV_DIR}
        cd ${PROJECT_DIR}

        if [ -d "${PROJECT_DIR}/${TMP_DIR}/${TEST_ENV_DIR}" ]; then
            echo "Test Unipart GDPR Plugin... Test environment repository successfully cloned"
        else
            echo
            echo "Test Unipart GDPR Plugin... ERROR: clone test environment repository did not work"
            echo
            exit 1
        fi
    fi
else
    cd ${PROJECT_DIR}/${TMP_DIR}
    git clone https://github.com/unipart/suitecrm-dev-ops.git ${TEST_ENV_DIR}
    cd ${PROJECT_DIR}

    if [ -d "${PROJECT_DIR}/${TMP_DIR}/${TEST_ENV_DIR}" ]; then
        echo "Test Unipart GDPR Plugin... Test environment repository successfully cloned"
    else
        echo
        echo "Test Unipart GDPR Plugin... ERROR: clone test environment repository did not work"
        echo
        exit 1
    fi
fi

echo "Test Unipart GDPR Plugin... Copying plugin zip file"
if [ -d "${PROJECT_DIR}/${TMP_DIR}/${TEST_ENV_DIR}/${PLUGIN_ZIP_FILE_DESTINATION}" ]; then
    echo "Test Unipart GDPR Plugin... Plugin zip file already copied"
else
    cp ${PROJECT_DIR}/${PLUGIN_ZIP_FILE} ${PROJECT_DIR}/${TMP_DIR}/${TEST_ENV_DIR}/${PLUGIN_ZIP_FILE_DESTINATION}

    if [ -f "${PROJECT_DIR}/${TMP_DIR}/${TEST_ENV_DIR}/${PLUGIN_ZIP_FILE_DESTINATION}" ]; then
        echo "Test Unipart GDPR Plugin... Plugin zip file successfully copied"
    else
        echo
        echo "Test Unipart GDPR Plugin... ERROR: copy plugin zip file did not work"
        echo
        exit 1
    fi
fi

echo "Test Unipart GDPR Plugin... Coping behat config file"
if [ -f "${PROJECT_DIR}/${TMP_DIR}/${TEST_ENV_DIR}/${BEHAT_CONF_FILE}" ]; then
    echo "Test Unipart GDPR Plugin... Behat config file already copied"
else
    cp ${PROJECT_DIR}/${BEHAT_CONF_FILE} ${PROJECT_DIR}/${TMP_DIR}/${TEST_ENV_DIR}/${BEHAT_CONF_FILE}

    if [ -f "${PROJECT_DIR}/${TMP_DIR}/${TEST_ENV_DIR}/${BEHAT_CONF_FILE}" ]; then
        echo "Test Unipart GDPR Plugin... Behat configuration file successfully copied"
    else
        echo
        echo "Test Unipart GDPR Plugin... ERROR: behat configuration file did not copy"
        echo
        exit 1
    fi
fi

echo "Test Unipart GDPR Plugin... Coping behat suites"
if [ -d "${PROJECT_DIR}/${TMP_DIR}${TEST_ENV_DIR}/features/Suites/UnipartGdprPlugin" ]; then
    echo "Test Unipart GDPR Plugin... Behat suites already copied"
else
    cp -r ${PROJECT_DIR}/features/Suites/UnipartGdprPlugin ${PROJECT_DIR}/${TMP_DIR}/${TEST_ENV_DIR}/features/Suites/UnipartGdprPlugin

    if [ -d "${PROJECT_DIR}/${TMP_DIR}/${TEST_ENV_DIR}/features/Suites/UnipartGdprPlugin" ]; then
        echo "Test Unipart GDPR Plugin... Behat suites successfully copied"
    else
        echo
        echo "Test Unipart GDPR Plugin... ERROR: behat suites directory did not copy"
        echo
        exit 1
    fi
fi

echo "Test Unipart GDPR Plugin... Coping behat GdprContext file"
if [ -f "${PROJECT_DIR}/${TMP_DIR}/${TEST_ENV_DIR}/features/bootstrap/GdprContext.php" ]; then
    echo "Test Unipart GDPR Plugin... Behat GdprContext file already copied"
else
    cp ${PROJECT_DIR}/features/bootstrap/GdprContext.php ${PROJECT_DIR}/${TMP_DIR}/${TEST_ENV_DIR}/features/bootstrap/GdprContext.php

    if [ -f "${PROJECT_DIR}/${TMP_DIR}/${TEST_ENV_DIR}/features/bootstrap/GdprContext.php" ]; then
        echo "Test Unipart GDPR Plugin... Behat GdprContext file successfully copied"
    else
        echo
        echo "Test Unipart GDPR Plugin... ERROR: behat GdprContext file did not copy"
        echo
        exit 1
    fi
fi

# NOTE: I assume you want to test locally. If not please refers to the README.md of the suitecrm-dev-ops repo to setup properly those file.
if [ ! -f "${PROJECT_DIR}/${TMP_DIR}/${TEST_ENV_DIR}/docker-compose.yml" ]; then
    cp ${PROJECT_DIR}/${TMP_DIR}/${TEST_ENV_DIR}/docker-compose.template.yml ${PROJECT_DIR}/${TMP_DIR}/${TEST_ENV_DIR}/docker-compose.yml
fi

if [ ! -f "${PROJECT_DIR}/${TMP_DIR}/${TEST_ENV_DIR}/.env" ]; then
    cp ${PROJECT_DIR}/${TMP_DIR}/${TEST_ENV_DIR}/etc/dev/.env.dev.example ${PROJECT_DIR}/${TMP_DIR}/${TEST_ENV_DIR}/.env
fi

if [ ${CLONE_ONLY} == 1 ]; then
    echo "Test Unipart GDPR Plugin... Clone only successfully Completed"
    echo
    exit 0;
fi

cd ${PROJECT_DIR}/${TMP_DIR}/${TEST_ENV_DIR}

if [ ! -f "${PROJECT_DIR}/${TMP_DIR}/${TEST_ENV_DIR}/vendor/bin/behat" ]; then
    docker-compose run --rm composer install
    ./bin/install.sh
fi

if [ "${TEST_SUITE}" == "ALL" ]; then
    run_all_tests
else
    docker-compose run --rm behat --config ${BEHAT_CONF_FILE} --suite ${TEST_SUITE}
fi

cd ${PROJECT_DIR}

echo "Test Unipart GDPR Plugin... Completed"
