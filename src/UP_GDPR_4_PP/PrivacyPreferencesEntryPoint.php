<?php
/**
 * Created by PhpStorm.
 * User: Riccardo De Leo
 */
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');


/**
 * Class PrivacyPreferencesPageGenerator
 *
 * This class will be used at the end of the file to generate the page
 */
class PrivacyPreferencesPageGenerator
{
    /**
     * @var string
     */
    protected $siteUrl;

    /**
     * @var string
     */
    protected $logo;

    /**
     * @var string
     */
    protected $favicon;

    /**
     * @var array
     */
    protected $ppConfig = [];

    /**
     * @var string
     */
    protected $uuid;

    /**
     * @var bool
     */
    protected $isUpdate = false;

    /**
     * @var string
     */
    protected $form = '';

    /**
     * @var array
     */
    protected $acceptedCampaigns = [];

    /**
     * @var object
     */
    protected $ppBean;

    /**
     * @var array
     */
    protected $appStrings = [];

    /**
     * @var array = [
     *      'time()' => [
     *          'DATE' => 'yyyy-mm-dd_hh-ii-ss',
     *          'REMOTE_ADDR' => '',
     *          'HTTP_X_FORWARDED_FOR' => '',
     *          'HTTP_USER_AGENT' => '',
     *          'acceptedCampaigns' => [
     *              [
     *                  'id' => 'privacy campaign id'.
     *                  'name' => 'privacy campaign name',
     *                  'text' => 'the exact wording of the accepted privacy campaign'
     *              ],
     *          ]
     *      ],
     * ]
     */
    protected $preferencesArray = [];

    /**
     * @var array
     */
    protected $pcList = [];

    /**
     * @var array
     */
    protected $modals = [];

    /**
     * @var array
     */
    protected $errorMessages = [];

    /**
     * @var array
     */
    protected $successMessages = [];

    /**
     * PrivacyPreferencesPageGenerator constructor.
     */
    public function __construct()
    {
        global $sugar_config;

        $xdebugRemoteEnable = getenv('XDEBUG_REMOTE_ENABLE');

        $this->siteUrl = ($xdebugRemoteEnable == 1) ? '' : $sugar_config['site_url'];

        $themeObject = SugarThemeRegistry::current();
        $this->logo = $themeObject->getImageURL('company_logo.png',false);
        $this->favicon = $themeObject->getImageURL('sugar_icon.ico',false);

        $this->ppConfig = json_decode(
            file_get_contents(__DIR__ . '/privacyPreferencesPageConfig.json'),
            true
        );

        global $app_strings;
        $this->appStrings = $app_strings;
    }

    /**
     * Main method, return the html page
     *
     * @return string
     */
    public function getPage() : string
    {
        try {
            $this->isUpdate = false;
            $this->modals = [];
            $this->errorMessages = [];
            $this->acceptedCampaigns = [];
            $this->ppBean = null;
            $this->successMessages = [];
            $this->form = '';

            if ($this->parseRequest() == false) {
                return $this->returnPage();
            }

            if ($this->loadPpBean() == false) {
                return $this->returnPage();
            }

            $this->loadAllPrivacyCampaigns();

            if ($this->isUpdate == true) {
                if ($this->updatePreferences() == false) {
                    return $this->returnPage();
                }
            }

            return $this->returnPage();
        } catch (\Throwable $e) {
            $GLOBALS['log']->error("PrivacyPreferencesEntryPoint::getPage() Error Message: " . $e->getMessage());
            $GLOBALS['log']->error("PrivacyPreferencesEntryPoint::getPage() Trace: " . $e->getTraceAsString());
            return "<h1>Unexpected Error, please contact our operation support.</h1>";
        }
    }

    /**
     * @return bool
     */
    protected function parseRequest()
    {
        if (!isset($_GET['uuid']) || empty($_GET['uuid'])) {
            $this->errorMessages[] = $this->appStrings["LBL_GDPR_PP_PAGE_ERROR_EMPTY_UUID"];
            return false;
        }
        $this->uuid = $_GET['uuid'];

        if (!isset($_POST['pageHash']) || empty($_POST['pageHash'])) {
            return true;
        }

        $this->isUpdate = true;

        if (isset($_POST['acceptedCampaigns']) &&
            is_array($_POST['acceptedCampaigns']) &&
            !empty($_POST['acceptedCampaigns'])
        ) {
            $this->acceptedCampaigns = $_POST['acceptedCampaigns'];
        }

        return true;
    }

    /**
     * @return bool
     */
    protected function loadPpBean() : bool
    {
        $ppBean = BeanFactory::newBean('UP_GDPR_4_PP');

        $ppList = $ppBean->get_list(
            $order_by = "date_entered DESC",
            $where = "up_gdpr_4_pp.uuid = '" . $this->uuid . "'",
            $row_offset = 0,
            $limit=10,
            $max=-1,
            $show_deleted = 0
        );

        if (!is_array($ppList)) {
            $this->errorMessages[] = $this->appStrings["LBL_GDPR_PP_PAGE_ERROR_UNEXPECTED"];
            $GLOBALS['log']->error("ppList is not an array, invalid uuid " . $this->uuid );
            return false;
        }

        $rowCount = count($ppList['list']);
        if ($rowCount == 0) {
            $this->errorMessages[] = $this->appStrings["LBL_GDPR_PP_PAGE_ERROR_INVALID_UUID"];
            $GLOBALS['log']->error("ppList is empty, invalid uuid " . $this->uuid );
            return false;
        }

        if ($rowCount > 1) {
            $this->errorMessages[] = $this->appStrings["LBL_GDPR_PP_PAGE_ERROR_UNEXPECTED"];
            $GLOBALS['log']->error("ppList has more than one result, invalid uuid " . $this->uuid );
            return false;
        }

        foreach ($ppList['list'] as $foundPpBean) {
            $this->ppBean = $foundPpBean;
            $this->ppBean->load_relationship('up_gdpr_4_pp_up_gdpr_3_pc');
        }

        return true;
    }

    /**
     *
     */
    protected function loadAllPrivacyCampaigns()
    {
        $pcBean = BeanFactory::newBean('UP_GDPR_3_PC');

        $this->pcList = $pcBean->get_list(
            $order_by = "date_entered DESC",
            $where = "up_gdpr_3_pc.enabled = '1'",
            $row_offset = 0,
            $limit=-1,
            $max=-1,
            $show_deleted = 0
        );
    }

    /**
     * @return string
     */
    protected function returnPage()
    {
        return $this->getHeader() .
            $this->getErrorMessages() .
            $this->getSuccessMessages() .
            $this->getBody() .
            $this->getModals() .
            $this->getFooter();
    }

    /**
     * @return string
     */
    protected function getHeader()
    {
        $header = '<!doctype html>
            <html lang="en">
                <head>
                    <meta charset="utf-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
                    <meta name="description" content="' . $this->appStrings["LBL_GDPR_PP_PAGE_HEADER_META_DESCRIPTION"] . '">
                    <meta name="author" content="' . $this->appStrings["LBL_GDPR_PP_PAGE_HEADER_META_AUTHOR"] . '">
                    <link rel="icon" href="' . $this->siteUrl . '/' . $this->logo . '">
                
                    <title>' . $this->appStrings["LBL_GDPR_PP_PAGE_HEADER_META_TITLE"] . '</title>';

        foreach ($this->ppConfig['styles'] as $style) {
            $header .= '
                    ' . $style . '
            ';
        }

        $header .= '
                    <style>
                        /* Show it\'s not fixed to the top */
                        body {
                          color: #474848;
                        }
                        
                        .cont {
                            min-height:100%;
                            position:relative;
                        }
                        
                        .pb-95 {
                            padding-bottom: 95px;
                        }
                        
                        html, body {
                            height: 100%;
                            margin: 0;
                        }
                        
                        p {
                          font-weight: 100;
                          font-family: Arial,Times New Roman,serif;
                        }
                        
                        .red-line {
                          border-top: 6px solid #e41b13;
                          margin-top: 0;
                        }
                        
                        .blue-line {
                          border-bottom: 6px solid #014289;
                          margin-bottom: 2px!important;
                        }
                        
                        .p-logo {
                          padding: 20px 110px 20px;
                        }
                        
                        .btn-primary {
                          color: #fff;
                          background-color: #014289;
                        }
                        
                        .btn-primary:hover {
                          color: #fff;
                          background-color: #3061b1;
                        }
                        
                        .footer {
                          width: 100%;
                          color: white;
                          background-color: #014289;
                          border-top: 6px solid #e41b13;
                          padding-bottom: 30px;
                          padding-top: 30px;
                          position: absolute;
                          bottom: 0;
                        }
                        
                        .check-col>label, p {
                          color: #474848!important;
                          font-family: inherit!important;
                          font-weight: 500!important;
                          line-height: 1.2!important;
                          font-size: 1rem!important;
                        }
                        
                        .ml-0 {
                          margin-left: 0px;
                        }
                        
                        .lab-mar {
                          padding-left: 25px;
                        }
                        
                        .info-ico {
                          position: absolute;
                          top: 10px;
                          right: 10px;
                          cursor: pointer;
                        }
                    </style>
                </head>
                <body>
                    <div class="cont">
                    
                    <nav class="navbar navbar-expand-md bg-light blue-line">
                        <a class="navbar-brand p-logo" href="#"><img src="' . $this->siteUrl . '/' . $this->logo . '"></a>
                    </nav>
                    
                    <hr class="red-line">
                    
                    <main role="main" class="container pb-95">
        ';

        return $header;
    }

    /**
     * @return string
     */
    protected function getErrorMessages()
    {
        return array_reduce(
            $this->errorMessages,
            function ($carry, $errorMessage) {
                $carry .= '
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>ERROR:</strong> ' . $errorMessage . '
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                ';
                return $carry;
            },
            ''
        );
    }

    /**
     * @return string
     */
    protected function getSuccessMessages()
    {
        return array_reduce(
            $this->successMessages,
            function ($carry, $successMessage) {
                $carry .= '
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>CONGRATULATION:</strong> ' . $successMessage . '
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                ';
                return $carry;
            },
            ''
        );
    }

    /**
     * @return string
     */
    protected function getBody()
    {
        return '
                        <div class="jumbotron">
                            <h3>' . $this->appStrings["LBL_GDPR_PP_PAGE_BODY_TITLE"] . '</h3>
                            <p class="">
                                ' . $this->appStrings["LBL_GDPR_PP_PAGE_BODY_DESCRIPTION"] . '
                            </p>
                
                            <br><hr><br>
                            
                            ' . $this->getForm() . '
                                
                        </div>
                    </main>
                    <footer class="footer text-center">
                        <h6>' . $this->appStrings["LBL_GDPR_PP_PAGE_FOOTER_TEXT"] . '</h6>
                    </footer>
            ';
    }

    /**
     * @return string
     */
    protected function getForm() : string
    {
        if (count($this->pcList['list']) <= 0) {
            return '';
        }

        $form = '<form action="' . $this->siteUrl . '/index.php?entryPoint=PrivacyPreferencesEntryPoint&uuid=' . $this->uuid . '" method="POST">';

        foreach ($this->pcList['list'] as $pcBean) {
            $pcIds = $this->ppBean->up_gdpr_4_pp_up_gdpr_3_pc->get();
            $checked = in_array($pcBean->id, $pcIds) ? 'checked' : '';

            $form .= '
                <div class="form-group form-check check-col alert alert-' . $pcBean->color . '">
                    <input type="checkbox" ' . $checked . ' class="form-check-input ml-0" name="acceptedCampaigns[]" value="' . $pcBean->id . '" id="acceptedCampaign_' . $pcBean->id . '">
                    <label class="form-check-label lab-mar" for="acceptedCampaign_' . $pcBean->id . '">
                        <b>' . $pcBean->name . '</b>
                    </label>
                    <p>';

            if ($pcBean->show_modal == '1') {
                $form .= '
                        <i class="info-ico float-right far fa-question-circle" data-toggle="modal" data-target="#modalCampaign_' . $pcBean->id . '"></i>
                ';

                $this->generateModalArrayFromBean($pcBean);
            }

            $form .= '
                        ' . $pcBean->text . '
                    </p>
                </div>
            ';
        }

        $form .= '
                <input type="hidden" name="pageHash" value="' . $this->getPageHash() . '">
                <button type="submit" class="btn btn-md btn-primary float-right" href="#" role="button">' . $this->appStrings["LBL_GDPR_PP_PAGE_BODY_FORM_SUBMIT_BUTTON"]. '</button>
            </form>
        ';

        return $form;
    }

    /**
     * @param object $pcBean
     */
    protected function generateModalArrayFromBean($pcBean)
    {
        $this->modals[] = [
            'id' => $pcBean->id,
            'title' => $pcBean->modal_title,
            'content' => $pcBean->modal_content
        ];
    }


    /**
     * @return string
     */
    protected function getModals()
    {
        return array_reduce(
            $this->modals,
            function ($carry, $modalData) {
                $carry .= '
            <div class="modal fade" id="modalCampaign_' . $modalData['id'] . '" tabindex="-1" role="dialog" aria-labelledby="modalCampaignLabel_' . $modalData['id'] . '" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalCampaignLabel_' . $modalData['id'] . '">' . $modalData['title'] . '</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            ' . $modalData['content'] . '
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
                ';
                return $carry;
            },
            ''
        );
    }

    /**
     * @return string
     */
    protected function getFooter()
    {
        $footer = '
                    </div>';

        foreach ($this->ppConfig['scripts'] as $script) {
            $footer .= '
                    ' . $script . '
            ';
        }
        $footer .= '
                </body>
            </html>';

        return $footer;
    }

    /**
     * @return bool
     * @throws Exception
     */
    protected function updatePreferences()
    {
        if ($this->verifyPageHash($_POST['pageHash']) == false) {
            $this->errorMessages[] = $this->appStrings["LBL_GDPR_PP_PAGE_ERROR_SECURITY_MISMATCH"];
            return false;
        }

        $pcIds = $this->ppBean->up_gdpr_4_pp_up_gdpr_3_pc->get();

        if (empty($this->acceptedCampaigns) && empty($pcIds)) {
            return true;
        }

        $areEquals = array_diff($pcIds, $this->acceptedCampaigns) === array_diff($this->acceptedCampaigns, $pcIds);

        if ($areEquals) {
            return true;
        }

        foreach ($pcIds as $pcId) {
            $pcBean = BeanFactory::getBean('UP_GDPR_3_PC', $pcId);
            $this->ppBean->up_gdpr_4_pp_up_gdpr_3_pc->delete($this->ppBean->id, $pcBean);
            $this->ppBean->save();
            $pcBean = null;
            unset($pcBean);
        }

        $preferences = [];

        foreach ($this->acceptedCampaigns as $acceptedCampaignId) {
            $pcBean = BeanFactory::getBean('UP_GDPR_3_PC', $acceptedCampaignId);
            $this->ppBean->up_gdpr_4_pp_up_gdpr_3_pc->add($pcBean);
            $this->ppBean->save();
            $preferences[] = [
                'id' => $pcBean->id,
                'name' => $pcBean->name,
                'text' => $pcBean->name
            ];
            $pcBean = null;
            unset($pcBean);
        }

        $dateTime = new \DateTime();
        $httpXForwardedFor = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : '';
        $httpUserAgent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
        $oldPreferences = $this->ppBean->privacy_preferences;

        $this->ppBean->privacy_preferences = "Preferences changed on: " .
            $dateTime->format('Y-m-d H:i:s')  . "\n";
        $this->ppBean->privacy_preferences .= "REMOTE_ADDR: " . $_SERVER['REMOTE_ADDR'] . "\n";
        $this->ppBean->privacy_preferences .= "HTTP_X_FORWARDED_FOR: " . $httpXForwardedFor . "\n";
        $this->ppBean->privacy_preferences .= "HTTP_USER_AGENT: " . $httpUserAgent . "\n";
        $this->ppBean->privacy_preferences .= "Accepted Campaigns: " .
            json_encode($preferences, JSON_PRETTY_PRINT) . "\n";

        $this->ppBean->save();

        // create an array to audit changes in the calls module's audit table
        $auditEntry = array();
        $auditEntry['field_name'] = 'privacy_preferences';
        $auditEntry['data_type'] = 'text';
        $auditEntry['before'] = $oldPreferences;
        $auditEntry['after'] = $this->ppBean->privacy_preferences;
        // save audit entry
        $this->ppBean->db->save_audit_records($this->ppBean, $auditEntry);

        $this->successMessages[] = $this->appStrings["LBL_GDPR_PP_PAGE_SUCCESS_UPDATE"];

        $this->loadPpBean();

        return true;
    }

    /**
     * @param  string $pageHash
     * @return bool
     */
    protected function verifyPageHash($pageHash) : bool
    {
        return password_verify($this->ppConfig['passPageSalt'] . $this->ppBean->target_id, $pageHash);
    }

    /**
     * @return bool|string
     */
    protected function getPageHash()
    {
        return password_hash(
            $this->ppConfig['passPageSalt'] . $this->ppBean->target_id,
            PASSWORD_DEFAULT
        );
    }

}

$pageGenerator = new PrivacyPreferencesPageGenerator();

echo $pageGenerator->getPage();
