<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

namespace core\navigation\output;

/**
 * Primary navigation renderable test
 *
 * @package     core
 * @category    navigation
 * @copyright   2020 onwards Peter Dias
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class primary_test extends \advanced_testcase {
    /**
     * Basic setup to make sure the nav objects gets generated without any issues.
     */
    public function setUp(): void {
        global $PAGE;
        $this->resetAfterTest();
        $pagecourse = $this->getDataGenerator()->create_course();
        $assign = $this->getDataGenerator()->create_module('assign', ['course' => $pagecourse->id]);
        $cm = get_coursemodule_from_id('assign', $assign->cmid);
        $contextrecord = \context_module::instance($cm->id);
        $pageurl = new \moodle_url('/mod/assign/view.php', ['id' => $cm->instance]);
        $PAGE->set_cm($cm);
        $PAGE->set_url($pageurl);
        $PAGE->set_course($pagecourse);
        $PAGE->set_context($contextrecord);
    }

    /**
     * Test the primary export to confirm we are getting the nodes
     *
     * @dataProvider test_primary_export_provider
     * @param bool $withcustom Setup with custom menu
     * @param bool $withlang Setup with langs
     * @param array $expecteditems An array of nodes expected with content in them.
     */
    public function test_primary_export(bool $withcustom, bool $withlang, array $expecteditems) {
        global $PAGE, $CFG;
        if ($withcustom) {
            $CFG->custommenuitems = "Course search|/course/search.php
                Google|https://google.com.au/
                Netflix|https://netflix.com/au";
        }

        // Mimic multiple langs installed. To trigger responses 'get_list_of_translations'.
        // Note: The text/title of the nodes generated will be 'English(fr), English(de)' but we don't care about this.
        // We are testing whether the nodes gets generated when the lang menu is available.
        if ($withlang) {
            mkdir("$CFG->dataroot/lang/de", 0777, true);
            mkdir("$CFG->dataroot/lang/fr", 0777, true);
        }

        $primary = new primary();
        $renderer = $PAGE->get_renderer('core');
        $data = $primary->export_for_template($renderer);
        foreach ($data as $menutype => $value) {
            if ($value) {
                $this->assertTrue(in_array($menutype, $expecteditems));
            }
        }

        // If we have asked to include custom menu the it should return us 3 nodes as per the setting.
        if ($withcustom) {
            $this->assertEquals(3, count($data['custom']));
        }

        // If we have asked to include custom menu the it should return us 3 nodes,
        // as per the original setup.
        if ($withlang) {
            $this->assertEquals(3, count($data['lang']));
        }
    }

    /**
     * Provider for the test_primary_export function.
     *
     * @return array
     */
    public function test_primary_export_provider(): array {
        return [
            "Export the menu data with custom and lang menu" => [
                true, true, ['primary', 'custom', 'lang']
            ],
            "Export the menu data with custom menu" => [
                true, false, ['primary', 'custom']
            ],
            "Export the menu data with lang menu" => [
                false, true, ['primary', 'lang']
            ],
            "Export the menu data without the custom and lang menu" => [
                false, false, ['primary']
            ],
        ];
    }
}