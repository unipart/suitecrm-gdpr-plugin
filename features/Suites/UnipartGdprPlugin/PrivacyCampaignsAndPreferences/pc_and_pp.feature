Feature: GDPR 3- Privacy Campaigns and GDPR 4 - Privacy Preferences
  In order to test privacy campaigns and privacy preferences features
  As a SuiteCRM admin
  I need to perform a fresh installation

  Scenario: In order to test Privacy Preferences I successfully create the privacy campaign Operation
    Given I am on "/"
    And I hover over the element "#grouptab_5"
    And I wait till "30000" or to see "GDPR - 3 - Privacy Campaigns"
    And I click on "a" with text "GDPR - 3 - Privacy Campaigns"
    And I wait till "30000" or to see "Create GDPR - 3 - Privacy Campaigns"
    And I click on "div" with text "Create GDPR - 3 - Privacy Campaigns"
    And I wait till "30000" or to see "Privacy Campaign Title:*"
    And I select "success" from "color"
    And I check "enabled"
    And I fill in the following:
      | name          | Operation Name          |
      | text          | Operation text          |
      | description   | Operation Description   |
      | modal_title   | Operation Modal Title   |
      | modal_content | Operation Modal Content |
    And I check "show_modal"
    And I scroll on top
    When I press "SAVE"
    Then I wait till "30000" or to see "OPERATION NAME"

  Scenario: In order to test Privacy Preferences I successfully create the privacy campaign Marketing
    Given I am on "/"
    And I hover over the element "#grouptab_5"
    And I wait till "30000" or to see "GDPR - 3 - Privacy Campaigns"
    And I click on "a" with text "GDPR - 3 - Privacy Campaigns"
    And I wait till "30000" or to see "Create GDPR - 3 - Privacy Campaigns"
    And I click on "div" with text "Create GDPR - 3 - Privacy Campaigns"
    And I wait till "30000" or to see "Privacy Campaign Title:*"
    And I select "secondary" from "color"
    And I check "enabled"
    And I fill in the following:
      | name          | Marketing Name          |
      | text          | Marketing text          |
      | description   | Marketing Description   |
      | modal_title   | Marketing Modal Title   |
      | modal_content | Marketing Modal Content |
    And I scroll on top
    When I press "SAVE"
    Then I wait till "30000" or to see "MARKETING NAME"

  Scenario: In order to test Privacy Preferences I successfully create the privacy campaign 3rd party marketing
    Given I am on "/"
    Then I hover over the element "#grouptab_5"
    And I wait till "30000" or to see "GDPR - 3 - Privacy Campaigns"
    And I click on "a" with text "GDPR - 3 - Privacy Campaigns"
    And I wait till "30000" or to see "Create GDPR - 3 - Privacy Campaigns"
    And I click on "div" with text "Create GDPR - 3 - Privacy Campaigns"
    And I wait till "30000" or to see "Privacy Campaign Title:*"
    And I check "enabled"
    And I fill in the following:
      | name          | 3rd party marketing name          |
      | text          | 3rd party marketing text          |
      | description   | 3rd party marketing Description   |
      | modal_title   | 3rd party marketing Modal Title   |
      | modal_content | 3rd party marketing Modal Content |
    And I scroll on top
    Then I press "SAVE"
    When I wait till "30000" or to see "3RD PARTY MARKETING NAME"

  Scenario: I successfully update the privacy preferences of my test contact for the first time
  through the external privacy preferences page
    Given I am on "/"
    And I hover over the element "#grouptab_5"
    And I wait till "30000" or to see "GDPR - 4 - Privacy Preferences"
    And I click on "a" with text "GDPR - 4 - Privacy Preferences"
    And I wait till "30000" or to see "Mr. contact_name contact_last_name"
    And I click on "a" with contains text "Mr. contact_name contact_last_name"
    And I wait till "30000" or to see "Relationships Panel"
    And I visit privacy preference page
    And I wait till "30000" or to see "Privacy Preferences Page"
    And I click on "b" with contains text "3rd party marketing name"
    And I scroll on Bottom
    When I click on "button" with contains text "Send GDPR Preferences »"
    And I scroll on top
    Then I wait till "30000" or to see "CONGRATULATION: Privacy Preferences successfully updated."
    And I am on "/index.php"
    And I hover over the element "#grouptab_5"
    And I wait till "30000" or to see "GDPR - 4 - Privacy Preferences"
    And I click on "a" with text "GDPR - 4 - Privacy Preferences"
    And I wait till "30000" or to see "Mr. contact_name contact_last_name"
    And I click on "a" with contains text "Mr. contact_name contact_last_name"
    And I wait till "30000" or to see "Relationships Panel"
    And I should see text "3rd party marketing name" in element "#privacy_preferences"

  Scenario: I successfully update the privacy preferences of my test contact for the second time
  through the external privacy preferences page
    Given I am on "/"
    And I hover over the element "#grouptab_5"
    And I wait till "30000" or to see "GDPR - 4 - Privacy Preferences"
    And I click on "a" with text "GDPR - 4 - Privacy Preferences"
    And I wait till "30000" or to see "Mr. contact_name contact_last_name"
    And I click on "a" with contains text "Mr. contact_name contact_last_name"
    And I wait till "30000" or to see "Relationships Panel"
    And I visit privacy preference page
    And I wait till "30000" or to see "Privacy Preferences Page"
    And I click on "b" with contains text "3rd party marketing name"
    And I scroll on Bottom
    When I click on "button" with contains text "Send GDPR Preferences »"
    And I scroll on top
    Then I wait till "30000" or to see "CONGRATULATION: Privacy Preferences successfully updated."
    And I am on "/index.php"
    And I hover over the element "#grouptab_5"
    And I wait till "30000" or to see "GDPR - 4 - Privacy Preferences"
    And I click on "a" with text "GDPR - 4 - Privacy Preferences"
    And I wait till "30000" or to see "Mr. contact_name contact_last_name"
    And I click on "a" with contains text "Mr. contact_name contact_last_name"
    And I wait till "30000" or to see "Relationships Panel"
    And I should not see text "3rd party marketing name" in element "#privacy_preferences"

  Scenario: I successfully update the privacy preferences of my test lead for the first time
  through the external privacy preferences page
    Given I am on "/"
    And I hover over the element "#grouptab_5"
    And I wait till "30000" or to see "GDPR - 4 - Privacy Preferences"
    And I click on "a" with text "GDPR - 4 - Privacy Preferences"
    And I wait till "30000" or to see "Miss lead_name lead_last_name"
    And I click on "a" with contains text "Miss lead_name lead_last_name"
    And I wait till "30000" or to see "Relationships Panel"
    And I visit privacy preference page
    And I wait till "30000" or to see "Privacy Preferences Page"
    And I click on "b" with contains text "Marketing Name"
    And I scroll on Bottom
    When I click on "button" with contains text "Send GDPR Preferences »"
    And I scroll on top
    Then I wait till "30000" or to see "CONGRATULATION: Privacy Preferences successfully updated."
    And I am on "/index.php"
    And I hover over the element "#grouptab_5"
    And I wait till "30000" or to see "GDPR - 4 - Privacy Preferences"
    And I click on "a" with text "GDPR - 4 - Privacy Preferences"
    And I wait till "30000" or to see "Miss lead_name lead_last_name"
    And I click on "a" with contains text "Miss lead_name lead_last_name"
    And I wait till "30000" or to see "Relationships Panel"
    And I should see text "Marketing Name" in element "#privacy_preferences"

  Scenario: I successfully update the privacy preferences of my test lead for the second time
  through the external privacy preferences page
    Given I am on "/"
    And I hover over the element "#grouptab_5"
    And I wait till "30000" or to see "GDPR - 4 - Privacy Preferences"
    And I click on "a" with text "GDPR - 4 - Privacy Preferences"
    And I wait till "30000" or to see "Miss lead_name lead_last_name"
    And I click on "a" with contains text "Miss lead_name lead_last_name"
    And I wait till "30000" or to see "Relationships Panel"
    And I visit privacy preference page
    And I wait till "30000" or to see "Privacy Preferences Page"
    And I click on "b" with contains text "Marketing Name"
    And I scroll on Bottom
    When I click on "button" with contains text "Send GDPR Preferences »"
    And I scroll on top
    Then I wait till "30000" or to see "CONGRATULATION: Privacy Preferences successfully updated."
    And I am on "/index.php"
    And I hover over the element "#grouptab_5"
    And I wait till "30000" or to see "GDPR - 4 - Privacy Preferences"
    And I click on "a" with text "GDPR - 4 - Privacy Preferences"
    And I wait till "30000" or to see "Miss lead_name lead_last_name"
    And I click on "a" with contains text "Miss lead_name lead_last_name"
    And I wait till "30000" or to see "Relationships Panel"
    And I should not see text "Marketing Name" in element "#privacy_preferences"

  Scenario: I successfully update the privacy preferences of my test account for the first time
  through the external privacy preferences page
    Given I am on "/"
    And I hover over the element "#grouptab_5"
    And I wait till "30000" or to see "GDPR - 4 - Privacy Preferences"
    And I click on "a" with text "GDPR - 4 - Privacy Preferences"
    And I wait till "30000" or to see "account_name"
    And I click on "a" with contains text "account_name"
    And I wait till "30000" or to see "Relationships Panel"
    And I visit privacy preference page
    And I wait till "30000" or to see "Privacy Preferences Page"
    And I click on "b" with contains text "Operation Name"
    And I scroll on Bottom
    When I click on "button" with contains text "Send GDPR Preferences »"
    And I scroll on top
    Then I wait till "30000" or to see "CONGRATULATION: Privacy Preferences successfully updated."
    And I am on "/index.php"
    And I hover over the element "#grouptab_5"
    And I wait till "30000" or to see "GDPR - 4 - Privacy Preferences"
    And I click on "a" with text "GDPR - 4 - Privacy Preferences"
    And I wait till "30000" or to see "account_name"
    And I click on "a" with contains text "account_name"
    And I wait till "30000" or to see "Relationships Panel"
    And I should see text "Operation Name" in element "#privacy_preferences"

  Scenario: I successfully update the privacy preferences of my test account for the second time
  through the external privacy preferences page
    Given I am on "/"
    And I hover over the element "#grouptab_5"
    And I wait till "30000" or to see "GDPR - 4 - Privacy Preferences"
    And I click on "a" with text "GDPR - 4 - Privacy Preferences"
    And I wait till "30000" or to see "account_name"
    And I click on "a" with contains text "account_name"
    And I wait till "30000" or to see "Relationships Panel"
    And I visit privacy preference page
    And I wait till "30000" or to see "Privacy Preferences Page"
    And I click on "b" with contains text "Operation Name"
    And I scroll on Bottom
    When I click on "button" with contains text "Send GDPR Preferences »"
    And I scroll on top
    Then I wait till "30000" or to see "CONGRATULATION: Privacy Preferences successfully updated."
    And I am on "/index.php"
    And I hover over the element "#grouptab_5"
    And I wait till "30000" or to see "GDPR - 4 - Privacy Preferences"
    And I click on "a" with text "GDPR - 4 - Privacy Preferences"
    And I wait till "30000" or to see "account_name"
    And I click on "a" with contains text "account_name"
    And I wait till "30000" or to see "Relationships Panel"
    And I should not see text "Operation Name" in element "#privacy_preferences"