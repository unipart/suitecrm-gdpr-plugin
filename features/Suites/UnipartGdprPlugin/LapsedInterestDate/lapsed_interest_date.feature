Feature: Lapsed Interest Review
  In order to simplify the review of the validity of the interest on the opportunity
  As an admin
  I can use the fifth module of the Unipart GDPR plugin

  Scenario: I change the lapse date of the lapsed interest review of my test opportunity
    to 16 day before the lapse and I expect to have a Legitimate interest
    Given I am on "/"
    And I hover over the element "#grouptab_5"
    And I wait till "30000" or to see "GDPR - 5 - Lapsed Interest Reviews"
    And I click on "a" with text "GDPR - 5 - Lapsed Interest Reviews"
    And I wait till "30000" or to see "Opportunity Name"
    And I click on "a" with contains text "opportunity_name"
    And I wait till "30000" or to see "Relationships panel"
    And I click on id "tab-actions"
    And I click on id "edit_button"
    When I add "16" days to date
    And I press "SAVE"
    Then I wait till "30000" or to see "Authors Panel"
    And I should see "Legitimate" in element "[field='status']"
    And I should see "15" in element "#days_before_lapse"

  Scenario: I change the lapse date of the lapsed interest review of my test opportunity
  to 15 day before the lapse and I expect to have a Nearly Lapsed interest
    Given I am on "/"
    And I hover over the element "#grouptab_5"
    And I wait till "30000" or to see "GDPR - 5 - Lapsed Interest Reviews"
    And I click on "a" with text "GDPR - 5 - Lapsed Interest Reviews"
    And I wait till "30000" or to see "Opportunity Name"
    And I click on "a" with contains text "opportunity_name"
    And I wait till "30000" or to see "Relationships panel"
    And I click on id "tab-actions"
    And I click on id "edit_button"
    When I add "15" days to date
    And I press "SAVE"
    Then I wait till "30000" or to see "Authors Panel"
    And I should see "Nearly Lapsed" in element "[field='status']"
    And I should see "14" in element "#days_before_lapse"

  Scenario: I change the lapse date of the lapsed interest review of my test opportunity
  to 16 day after the lapse and I expect to have a Lapsed interest
    Given I am on "/"
    And I hover over the element "#grouptab_5"
    And I wait till "30000" or to see "GDPR - 5 - Lapsed Interest Reviews"
    And I click on "a" with text "GDPR - 5 - Lapsed Interest Reviews"
    And I wait till "30000" or to see "Opportunity Name"
    And I click on "a" with contains text "opportunity_name"
    And I wait till "30000" or to see "Relationships panel"
    And I click on id "tab-actions"
    And I click on id "edit_button"
    When I subtract "7" days to date
    And I press "SAVE"
    Then I wait till "30000" or to see "Authors Panel"
    And I should see "Lapsed" in element "[field='status']"
    And I should see "0" in element "#days_before_lapse"