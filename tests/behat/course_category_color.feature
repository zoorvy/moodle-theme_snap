# This file is part of Moodle - http://moodle.org/
#
# Moodle is free software: you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation, either version 3 of the License, or
# (at your option) any later version.
#
# Moodle is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with Moodle.  If not, see <http://www.gnu.org/licenses/>.
#
# Test the Category color setting for snap.
#
# @package   theme_snap
# @copyright Copyright (c) 2018 Blackboard Inc. (http://www.blackboard.com)
# @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later


@theme @theme_snap
Feature: When the moodle theme is set to Snap, admins can change the color for a given category.

  Background:
    Given I create the following course categories:
      | id | name   | category | idnumber | description |
      |  5 | Cat  5 |     0    |   CAT5   |   Test      |
      | 10 | Cat 10 |   CAT5   |   CAT10  |   Test      |
      | 20 | Cat 20 |   CAT20  |   CAT20  |   Test      |

  @javascript
  Scenario: Go to Snap settings page and put a wrong JSON text in it.
    Given I log in as "admin"
    And I am on site homepage
    And I click on "#admin-menu-trigger" "css_element"
    And I navigate to "Snap" node in "Site administration>Appearance>Themes"
    And I should see "Category color"
    And I click on "Category color" "link"
    And I should see "JSON Text"
    And I set the following fields to these values:
      |  Color palette | #FFAAFF                                           |
      |    JSON Text   | This is more than 10 words. 1 2 3 4 5 6 7 8 9 10. |
    And I click on "Save changes" "button"
    And I wait until the page is ready
    And the following fields do not match these values:
      |    JSON Text   | This is more than 10 words. 1 2 3 4 5 6 7 8 9 10. |

  @javascript
  Scenario: Go to Snap settings page and put a valid JSON text in it.
    Given I log in as "admin"
    And I am on site homepage
    And I click on "#admin-menu-trigger" "css_element"
    And I navigate to "Snap" node in "Site administration>Appearance>Themes"
    And I should see "Category color"
    And I click on "Category color" "link"
    And I should see "JSON Text"
    And I set the field "JSON Text" to "aaa"
    And I set the following fields to these values:
      |  Color palette |      #FFAAFF                  |
      |    JSON Text   | {"5":"#FAAFFF","10":"#FABCF0"} |
    And I click on "Save changes" "button"
    And I wait until the page is ready
    And the following fields match these values:
      |  Color palette |      #FFAAFF     |
      |    JSON Text   | {"5":"#FAAFFF","10":"#FABCF0"} |

  @javascript
  Scenario: Go to Snap settings page and put a valid JSON text in it but with no existing categories.
    Given I log in as "admin"
    And I am on site homepage
    And I click on "#admin-menu-trigger" "css_element"
    And I navigate to "Snap" node in "Site administration>Appearance>Themes"
    And I should see "Category color"
    And I click on "Category color" "link"
    And I should see "JSON Text"
    And I set the field "JSON Text" to "aaa"
    And I set the following fields to these values:
      |  Color palette |      #FFAAFF     |
      |    JSON Text   | {"70":"#FAAFFF"} |
    And I click on "Save changes" "button"
    And I wait until the page is ready
    And the following fields do not match these values:
      |    JSON Text   | {"70":"#FAAFFF"} |

  @javascript
  Scenario: Go to Snap settings page and put a not valid color in the JSON text.
    Given I log in as "admin"
    And I am on site homepage
    And I click on "#admin-menu-trigger" "css_element"
    And I navigate to "Snap" node in "Site administration>Appearance>Themes"
    And I should see "Category color"
    And I click on "Category color" "link"
    And I should see "JSON Text"
    And I set the field "JSON Text" to "aaa"
    And I set the following fields to these values:
      |  Color palette |    #FFAAFF   |
      |    JSON Text   | {"20":"#FA"} |
    And I click on "Save changes" "button"
    And I wait until the page is ready
    And the following fields do not match these values:
      |    JSON Text   | {"20":"#FA"} |

  @javascript
  Scenario: Go to Snap settings page and put a wrong JSON text with duplicated IDs.
    Given I log in as "admin"
      And I am on site homepage
      And I click on "#admin-menu-trigger" "css_element"
      And I navigate to "Snap" node in "Site administration>Appearance>Themes"
      And I should see "Category color"
      And I click on "Category color" "link"
      And I should see "JSON Text"
      And I set the following fields to these values:
        |  Color palette | #FFAAFF                                           |
        |    JSON Text   | {"10":"#FAAFFF", "10":"0DAA00"} |
      And I click on "Save changes" "button"
      And I wait until the page is ready
      And I click on "Category color" "link"
     Then the following fields do not match these values:
        |    JSON Text   | {"10":"#FAAFFF", "10":"0DAA00"} |
