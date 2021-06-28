@core @core_course
Feature: Collapse course sections
  In order to quickly access the course structure
  As a user
  I need to collapse/extend sections for Topics/Weeks formats.

  Background:
    Given the following "users" exist:
      | username | firstname | lastname | email                |
      | teacher1 | Teacher   | 1        | teacher1@example.com |
      | student1 | Student   | 1        | student1@example.com |
    And the following "course" exists:
      | fullname         | Course 1  |
      | shortname        | C1        |
      | category         | 0         |
      | enablecompletion | 1         |
      | numsections      | 4         |
      | startdate        | 957139200 |
    And the following "activities" exist:
      | activity | name              | intro                       | course | idnumber | section |
      | assign   | Activity sample 1 | Test assignment description | C1     | sample1  | 1       |
      | book     | Activity sample 2 | Test book description       | C1     | sample2  | 2       |
      | choice   | Activity sample 3 | Test choice description     | C1     | sample3  | 3       |
    And the following "course enrolments" exist:
      | user     | course | role           |
      | student1 | C1     | student        |
      | teacher1 | C1     | editingteacher |

  @javascript
  Scenario: Expand/collapse sections for Topics format.
    Given I log in as "teacher1"
    And I am on "Course 1" course homepage
    And I should see "Topic 1" in the "region-main" "region"
    And I should see "Topic 2" in the "region-main" "region"
    And I should see "Topic 3" in the "region-main" "region"
    And I should see "Activity sample 1" in the "region-main" "region"
    And I should see "Activity sample 2" in the "region-main" "region"
    And I should see "Activity sample 3" in the "region-main" "region"
    When I click on "#collapssesection1" "css_element"
    And I click on "#collapssesection2" "css_element"
    And I click on "#collapssesection3" "css_element"
    Then I should not see "Activity sample 1" in the "region-main" "region"
    And I should not see "Activity sample 2" in the "region-main" "region"
    And I should not see "Activity sample 3" in the "region-main" "region"
    And I click on "#collapssesection1" "css_element"
    And I click on "#collapssesection2" "css_element"
    And I click on "#collapssesection3" "css_element"
    And I should see "Activity sample 1" in the "region-main" "region"
    And I should see "Activity sample 2" in the "region-main" "region"
    And I should see "Activity sample 3" in the "region-main" "region"

  @javascript
  Scenario: Expand/collapse sections for Weeks format.
    Given I log in as "teacher1"
    And I am on "Course 1" course homepage
    When I navigate to "Edit settings" in current page administration
    And I expand all fieldsets
    And I set the following fields to these values:
      | Format      | Weekly format     |
    And I press "Save and display"
    And I should see "1 May - 7 May" in the "region-main" "region"
    And I should see "8 May - 14 May" in the "region-main" "region"
    And I should see "15 May - 21 May" in the "region-main" "region"
    And I should see "Activity sample 1" in the "region-main" "region"
    And I should see "Activity sample 2" in the "region-main" "region"
    And I should see "Activity sample 3" in the "region-main" "region"
    When I click on "#collapssesection1" "css_element"
    And I click on "#collapssesection2" "css_element"
    And I click on "#collapssesection3" "css_element"
    Then I should not see "Activity sample 1" in the "region-main" "region"
    And I should not see "Activity sample 2" in the "region-main" "region"
    And I should not see "Activity sample 3" in the "region-main" "region"
    And I click on "#collapssesection1" "css_element"
    And I click on "#collapssesection2" "css_element"
    And I click on "#collapssesection3" "css_element"
    And I should see "Activity sample 1" in the "region-main" "region"
    And I should see "Activity sample 2" in the "region-main" "region"
    And I should see "Activity sample 3" in the "region-main" "region"

  @javascript
  Scenario: Users can see one section per page for Topics format
    Given I log in as "admin"
    And I am on "Course 1" course homepage
    When I navigate to "Edit settings" in current page administration
    And I expand all fieldsets
    And I set the following fields to these values:
      | Course layout | Show one section per page |
    And I press "Save and display"
    And I follow "Topic 2"
    And I should see "Activity sample 2" in the "region-main" "region"
    Then "Topic 1" "section" should not exist
    And "Topic 3" "section" should not exist

  @javascript
  Scenario: Users can see one section per page for Weeks format
    Given I log in as "admin"
    And I am on "Course 1" course homepage
    When I navigate to "Edit settings" in current page administration
    And I expand all fieldsets
    And I set the following fields to these values:
      | Format      | Weekly format     |
      | Course layout | Show one section per page |
    And I press "Save and display"
    And I follow "8 May - 14 May"
    And I should see "Activity sample 2" in the "region-main" "region"
    Then "1 May - 7 May" "section" should not exist
    And "15 May - 21 May" "section" should not exist
