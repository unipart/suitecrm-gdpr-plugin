Feature: Install
  In order to use Unipart - SuiteCRM GDPR Plugin
  As a SuiteCRM admin
  I need to be able to perform a successfully fresh installation of the plugin

  Scenario: In order to perform future tests around installation and privacy preferences module,
  I successfully create a new Contact
    Given I am on "/index.php?module=Contacts&action=EditView&return_module=Contacts&return_action=DetailView"
    And I wait till "30000" or to see "Last Name"
    And I select "Mr." from "salutation"
    And I fill in the following:
      | last_name               | contact_last_name       |
      | phone_work              | +441234567890           |
      | phone_mobile            | +440987654321           |
      | title                   | Sir                     |
      | department              | HR                      |
      | Contacts0emailAddress0  | test.contanct@email.lan |
      | first_name              | contact_name            |
    When I press "SAVE"
    Then I wait till "30000" or to see "contact_name"
    And I should see "ACTIVITIES"
    And I should see "HISTORY"

  Scenario: In order to perform future tests around installation and privacy preferences module,
  I successfully create a new Lead
    Given I am on "/index.php?module=Leads&action=EditView&return_module=Leads&return_action=DetailView"
    And I wait till "30000" or to see "Last Name"
    And I select "Miss" from "salutation"
    And I fill in the following:
      | last_name             | lead_last_name      |
      | phone_work            | +391234567890       |
      | phone_mobile          | +390987654321       |
      | title                 | Miss                |
      | department            | BTG                 |
      | Leads0emailAddress0   | test.lead@email.lan |
      | first_name            | lead_name           |
    When I press "SAVE"
    Then I wait till "30000" or to see "lead_name"
    And I should see "ACTIVITIES"
    And I should see "HISTORY"

  Scenario: In order to perform future tests around installation and lapsed interest review module,
  I successfully create a new Account
    Given I am on "/index.php?module=Accounts&action=EditView&return_module=Accounts&return_action=DetailView"
    And I wait till "30000" or to see "Name"
    And I should see "Billing Address"
    And I fill in the following:
      | Accounts0emailAddress0  | test.account@email.lan  |
      | name                    | account_name            |
    When I press "SAVE"
    Then I wait till "30000" or to see "account_name"
    And I should see "ACTIVITIES"
    And I should see "HISTORY"

  Scenario: In order to perform future tests around installation and lapsed interest review module,
  I successfully create a new Opportunity
    Given I am on "/index.php?module=Opportunities&action=EditView&return_module=Opportunities&return_action=DetailView"
    And I wait till "30000" or to see "Supercharged by SuiteCRM"
    And I should see "Expected Close Date:"
    And I wait milliseconds "500"
    And I fill in the following:
      | account_name  | account_name      |
      | date_closed   | 06/25/2019        |
      | amount        | 100               |
      | name          | opportunity_name  |
    And I wait milliseconds "500"
    When I press "SAVE"
    Then I wait till "30000" or to see "opportunity_name"
    And I should see "Assigned to:"
    And I should see "ACTIVITIES"
    And I should see "HISTORY"

  @_file_upload
  Scenario: I successfully upload the Unipart - SuiteCRM GDPR Plugin zip file
    Given I am on "/index.php?module=Administration&action=index"
    And I click on id "module_loader"
    And I wait till "30000" or to see "MODULE LOADER"
    And I attach the file "/upload/Unipart-GDPR-Plugin_Installer.zip" to "upgrade_zip"
    When I press "Upload"
    Then I wait till "30000" or to see "Unipart Digital - SuiteCRM GDPR Plugin"

  Scenario: I install successfully the latest version of the Unipart - SuiteCRM GDPR Plugin
    Given I am on "/index.php?module=Administration&action=index"
    And I click on id "module_loader"
    And I wait till "30000" or to see "MODULE LOADER"
    And I click install on Unipart plugin row
    And I wait till "30000" or to see "Ready To Install"
    And I click on id "radio_license_agreement_accept"
    When I press "Commit"
    And I wait till "30000" or to see "Post installation migration script... Successfully executed"
    Then I should see "Post installation migration script... Started"
    And I should see "Text Field Type migration: UP_GDPR_4_PP_description Successfully created"
    And I should see "STEP 1 - Text Field Type Migration... Successfully completed"
    And I should see "STEP 2 - Privacy Preferences Migration... Started"
    And I should see "Privacy Preferences migration: - Mr. contact_name contact_last_name Successfully created"
    And I should see "Privacy Preferences migration: - Miss lead_name lead_last_name Successfully created"
    And I should see "Privacy Preferences migration: - account_name Successfully created"
    And I should see "STEP 2 - Privacy Preferences Migration... Successfully completed"
    And I should see "STEP 3 - Lapsed Interest Review Migration... Started"
    And I should see "STEP 3 - Lapsed Interest Review Migration... Successfully completed"

  Scenario: I successfully execute the post installation Quick Repair and Rebuild process
    Given I am on "/index.php?module=Administration&action=index"
    And I click on id "repair"
    And I wait till "30000" or to see "Quick Repair and Rebuild"
    When I click on "a" with text "Quick Repair and Rebuild"
    Then I wait till "30000" or to see "Privacy Preferences ... repaired"
    And I should see "Lapsed Interest Review ... repaired"

  Scenario: Per each contact, lead and account I created before install,
  I expect to have a UP GDPR 4 Privacy Preferences Entity
    Given I am on "/"
    And I hover over the element "#grouptab_5"
    And I wait till "30000" or to see "GDPR - 4 - Privacy Preferences"
    When I click on "a" with text "GDPR - 4 - Privacy Preferences"
    Then I wait till "30000" or to see "GDPR - 4 - PRIVACY PREFERENCES"
    And I should see "Mr. contact_name contact_last_name"
    And I should see "Miss lead_name lead_last_name"
    And I should see "account_name"

  Scenario: I expect to have a generated privacy preferences field for the contact I created before install
    Given I am on "/"
    And I hover over the element "#grouptab_5"
    And I wait till "30000" or to see "GDPR - 4 - Privacy Preferences"
    And I click on "a" with text "GDPR - 4 - Privacy Preferences"
    And I wait till "30000" or to see "GDPR - 4 - PRIVACY PREFERENCES"
    When I click on "a" with contains text "Mr. contact_name contact_last_name"
    Then I wait till "30000" or to see "Privacy Preferences generated by GDPR migration on the"

  Scenario: I expect to have a generated privacy preferences field for the lead I created before install
    Given I am on "/"
    And I hover over the element "#grouptab_5"
    And I wait till "30000" or to see "GDPR - 4 - Privacy Preferences"
    And I click on "a" with text "GDPR - 4 - Privacy Preferences"
    And I wait till "30000" or to see "GDPR - 4 - PRIVACY PREFERENCES"
    When I click on "a" with contains text "Miss lead_name lead_last_name"
    Then I wait till "30000" or to see "Privacy Preferences generated by GDPR migration on the"

  Scenario: I expect to have a generated privacy preferences field for the account I created before install
    Given I am on "/"
    And I hover over the element "#grouptab_5"
    And I wait till "30000" or to see "GDPR - 4 - Privacy Preferences"
    And I click on "a" with text "GDPR - 4 - Privacy Preferences"
    And I wait till "30000" or to see "GDPR - 4 - PRIVACY PREFERENCES"
    When I click on "a" with contains text "account_name"
    Then I wait till "30000" or to see "Privacy Preferences generated by GDPR migration on the"

  Scenario: I expect to have a generated Lapsed Interest Review for the opportunity I created before install
    Given I am on "/"
    And I hover over the element "#grouptab_5"
    And I wait till "30000" or to see "GDPR - 5 - Lapsed Interest Reviews"
    And I click on "a" with text "GDPR - 5 - Lapsed Interest Reviews"
    And I wait till "30000" or to see "opportunity_name"
    And I should see text "opportunity_name Legitimate 179" in element "div.list-view-rounded-corners > table > tbody > tr:first-child"
    When I click on "a" with contains text "opportunity_name"
    Then I wait till "30000" or to see "Relationships Panel"
    And I should see "opportunity_name" in element "#name"
    And I should see "Legitimate" in element "[field='status']"
    And I should see "179" in element "#days_before_lapse"
