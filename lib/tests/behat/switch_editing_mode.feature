@core @javascript
Feature: Turn editing mode on
  Users should be able to turn editing mode on and off

  Background:
    Given the following "courses" exist:
      | fullname | shortname |
      | Course 1 | C1        |
    And the following "users" exist:
      | username | firstname | lastname | email |
      | teacher1 | Teacher | 1 | teacher1@example.com |
      | student1 | Student | 1 | student1@example.com |
    And the following "course enrolments" exist:
      | user | course | role |
      | teacher1 | C1 | editingteacher |
      | student1 | C1 | student |

  Scenario: Turn editing mode on by behat step on Course
    And I log in as "teacher1"
    And I am on "Course 1" course homepage
    And I turn editing mode on
    And I should see "Add an activity or resource"
    And I turn editing mode off
    Then I should not see "Add an activity or resource"

  Scenario: Turn editing mode on by behat step on Gradebook
    And I log in as "teacher1"
    And I am on "Course 1" course homepage
    And I navigate to "View > Grader report" in the course gradebook
    And I turn editing mode on
    And I should see "Add a block"
    And I turn editing mode off
    Then I should not see "Add a block"

  Scenario: Turn editing mode on by behat step on Homepage
    Given I log in as "admin"
    And I am on site homepage
    And I turn editing mode on
    And I should see "Add an activity or resource"
    And I turn editing mode off
    Then I should not see "Add an activity or resource"

  Scenario: Switch editing Course
    And I log in as "teacher1"
    And I am on "Course 1" course homepage
    And I switch editing mode on
    And I should see "Add an activity or resource"
    And I switch editing mode off
    Then I should not see "Add an activity or resource"

  Scenario: Switch editing Homepage
    Given I log in as "admin"
    And I am on site homepage
    And I switch editing mode on
    And I should see "Add an activity or resource"
    And I switch editing mode off
    Then I should not see "Add an activity or resource"

  Scenario: Switch editing Default profile
    Given I log in as "admin"
    And I navigate to "Appearance > Default profile page" in site administration
    And I switch editing mode on
    And I should see "Add a block"
    And I switch editing mode off
    Then I should not see "Add a block"

  Scenario: Switch editing Profile
    Given I log in as "admin"
    And I follow "View profile"
    And I switch editing mode on
    And I should see "Add a block"
    And I switch editing mode off
    Then I should not see "Add a block"

  Scenario: Switch editing Default dashboard
    Given I log in as "admin"
    And I navigate to "Appearance > Default Dashboard page" in site administration
    And I switch editing mode on
    And I should see "Add a block"
    And I switch editing mode off
    Then I should not see "Add a block"

  Scenario: Switch editing Dashboard
    And I log in as "teacher1"
    And I switch editing mode on
    And I should see "Add a block"
    Then I switch editing mode off
    Then I should not see "Add a block"
