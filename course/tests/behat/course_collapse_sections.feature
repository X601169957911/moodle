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
      | enablecompletion | 1         |
    And the following "activities" exist:
      | activity | name         | intro                        | course | idnumber | section | completion |
      | assign   | Assignment 1 | Test assignment description1 | C1     | assign1  | 1       | 1          |
      | assign   | Assignment 2 | Test assignment description2 | C1     | assign2  | 2       | 1          |
      | assign   | Assignment 3 | Test assignment description3 | C1     | assign3  | 3       | 1          |
      | book     | Book 2       | Test book description2       | C1     | book2    | 2       | 1          |
      | book     | Book 3       | Test book description3       | C1     | book3    | 3       | 1          |
      | choice   | Choice 3     | Test choice description3     | C1     | choice3  | 3       | 1          |
    And the following "course enrolments" exist:
      | user     | course | role           |
      | student1 | C1     | student        |
      | teacher1 | C1     | editingteacher |
    And I am on the "Choice 3" "choice activity editing" page logged in as teacher1
    And I expand all fieldsets
    And I click on "Add restriction..." "button"
    And I click on "Activity completion" "button" in the "Add restriction..." "dialogue"
    And I click on "Displayed greyed-out if user does not meet this condition • Click to hide" "link"
    And I set the field "Activity or resource" to "Previous activity with completion"
    And I press "Save and return to course"
    And I log out

  @javascript
  Scenario: Expand/collapse sections for Topics format.
    Given I log in as "student1"
    And I am on "Course 1" course homepage
    And "[data-toggle=collapse]" "css_element" should exist in the "region-main" "region"
    And I should see "Topic 1" in the "region-main" "region"
    And I should see "Topic 2" in the "region-main" "region"
    And I should see "Topic 3" in the "region-main" "region"
    And I should see "Assignment 1" in the "region-main" "region"
    And I should see "Assignment 2" in the "region-main" "region"
    And I should see "Assignment 3" in the "region-main" "region"
    And I should see "Book 2" in the "region-main" "region"
    And I should see "Book 3" in the "region-main" "region"
    And I should not see "Choice 3" in the "region-main" "region"
    When I click on "#collapssesection3" "css_element"
    And I should not see "Assignment 3" in the "region-main" "region"
    And I should see "Assignment 1" in the "region-main" "region"
    And I should see "Assignment 2" in the "region-main" "region"
    And I should see "Book 2" in the "region-main" "region"
    And I should not see "Book 3" in the "region-main" "region"
    And I should not see "Choice 3" in the "region-main" "region"
    And I click on "#collapssesection1" "css_element"
    And I click on "#collapssesection2" "css_element"
    Then I should not see "Assignment 1" in the "region-main" "region"
    And I should not see "Assignment 2" in the "region-main" "region"
    And I should not see "Assignment 3" in the "region-main" "region"
    And I should not see "Book 2" in the "region-main" "region"
    And I should not see "Book 3" in the "region-main" "region"
    And I should not see "Choice 3" in the "region-main" "region"
    And I click on "#collapssesection1" "css_element"
    And I click on "#collapssesection2" "css_element"
    And I click on "#collapssesection3" "css_element"
    And I should see "Assignment 1" in the "region-main" "region"
    And I should see "Assignment 2" in the "region-main" "region"
    And I should see "Assignment 3" in the "region-main" "region"
    And I should see "Book 2" in the "region-main" "region"
    And I should see "Book 3" in the "region-main" "region"
    And I should not see "Choice 3" in the "region-main" "region"

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
    And I should see "Assignment 1" in the "region-main" "region"
    And I should see "Assignment 2" in the "region-main" "region"
    And I should see "Assignment 3" in the "region-main" "region"
    And I should see "Book 2" in the "region-main" "region"
    And I should see "Book 3" in the "region-main" "region"
    And I should see "Choice 3" in the "region-main" "region"
    And I should see "Not available unless: The activity Book 3 is marked complete (hidden otherwise)" in the "region-main" "region"
    When I click on "#collapssesection3" "css_element"
    And I should not see "Assignment 3" in the "region-main" "region"
    And I should see "Assignment 1" in the "region-main" "region"
    And I should see "Assignment 2" in the "region-main" "region"
    And I should see "Book 2" in the "region-main" "region"
    And I should not see "Book 3" in the "region-main" "region"
    And I should not see "Choice 3" in the "region-main" "region"
    And I click on "#collapssesection1" "css_element"
    And I click on "#collapssesection2" "css_element"
    Then I should not see "Assignment 1" in the "region-main" "region"
    And I should not see "Assignment 2" in the "region-main" "region"
    And I should not see "Assignment 3" in the "region-main" "region"
    And I should not see "Book 2" in the "region-main" "region"
    And I should not see "Book 3" in the "region-main" "region"
    And I should not see "Choice 3" in the "region-main" "region"
    And I click on "#collapssesection1" "css_element"
    And I click on "#collapssesection2" "css_element"
    And I click on "#collapssesection3" "css_element"
    And I should see "Assignment 1" in the "region-main" "region"
    And I should see "Assignment 2" in the "region-main" "region"
    And I should see "Assignment 3" in the "region-main" "region"
    And I should see "Book 2" in the "region-main" "region"
    And I should see "Book 3" in the "region-main" "region"
    And I should see "Choice 3" in the "region-main" "region"

  @javascript
  Scenario: Users can see one section per page for Topics format
    Given I log in as "teacher1"
    And I am on "Course 1" course homepage
    When I navigate to "Edit settings" in current page administration
    And I expand all fieldsets
    And I set the following fields to these values:
      | Course layout | Show one section per page |
    And I press "Save and display"
    And "[data-toggle=collapse]" "css_element" should not exist in the "region-main" "region"
    And I follow "Topic 2"
    And I should see "Assignment 2" in the "region-main" "region"
    Then "Topic 1" "section" should not exist
    And "Topic 3" "section" should not exist

  @javascript
  Scenario: Users can see one section per page for Weeks format
    Given I log in as "teacher1"
    And I am on "Course 1" course homepage
    When I navigate to "Edit settings" in current page administration
    And I expand all fieldsets
    And I set the following fields to these values:
      | Format      | Weekly format     |
      | Course layout | Show one section per page |
    And I press "Save and display"
    And "[data-toggle=collapse]" "css_element" should not exist in the "region-main" "region"
    And I follow "8 May - 14 May"
    And I should see "Assignment 2" in the "region-main" "region"
    Then "1 May - 7 May" "section" should not exist
    And "15 May - 21 May" "section" should not exist
