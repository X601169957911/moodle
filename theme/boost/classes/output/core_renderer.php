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

namespace theme_boost\output;

use moodle_url;
use core_course\external\course_summary_exporter;

defined('MOODLE_INTERNAL') || die;

/**
 * Renderers to align Moodle's HTML with that expected by Bootstrap
 *
 * @package    theme_boost
 * @copyright  2012 Bas Brands, www.basbrands.nl
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class core_renderer extends \core_renderer {

    public function edit_button(moodle_url $url) {
        return null;
    }

    public function edit_switch() {
        if (!isloggedin() || isguestuser()) {
            return;
        }
        if ($this->page->user_allowed_editing()) {
            $url = clone($this->page->url);
            if ($this->page->course->id == 1) {
                $url = new moodle_url('/course/view.php', ['id' => 1]);
            }
            $temp = (object)[];
            $temp->sesskey = sesskey();
            if ($this->page->user_is_editing()) {
                $temp->string = get_string('turneditingoff', 'theme_boost');
                $url->param('adminedit', 'off');
                $url->param('edit', 'off');
                $temp->edit = 'off';
                $temp->adminedit = 0;
                $temp->checked = 'checked';
            } else {
                $temp->string = get_string('turneditingon', 'theme_boost');
                $url->param('adminedit', 'on');
                $url->param('edit', 'on');
                $temp->edit = 'on';
                $temp->adminedit = 1;
                $temp->checked = '';
            }
            $temp->url = $url;
        }
        return $this->render_from_template('theme_boost/editswitch', $temp);
    }

    /**
     * Wrapper for header elements.
     *
     * @return string HTML to display the main header.
     */
    public function full_header() {
        global $DB, $USER;

        $thiscontext = \context::instance_by_id($this->page->context->id);
        $pageurl = parse_url($this->page->url, PHP_URL_PATH);

        if ($this->page->include_region_main_settings_in_header_actions() &&
                !$this->page->blocks->is_block_present('settings')) {
            // Only include the region main settings if the page has requested it and it doesn't already have
            // the settings block on it. The region main settings are included in the settings block and
            // duplicating the content causes behat failures.
            $this->page->add_header_action(html_writer::div(
                $this->region_main_settings_menu(),
                'd-print-none',
                ['id' => 'region-main-settings-menu']
            ));
        }

        $header = (object)[];

        // The default variables from core that contruct the page header.
        $header->settingsmenu = $this->context_header_settings_menu();
        $header->contextheader = $this->context_header();
        $header->hasnavbar = empty($this->page->layout_options['nonavbar']);
        $header->navbar = $this->navbar();
        $header->pageheadingbutton = $this->page_heading_button();
        $header->courseheader = $this->course_header();

        // The context header within the page header
        $contextheader = (object)[];
        $contextheader->navbar = $this->navbar();

        if ($thiscontext->contextlevel == CONTEXT_USER) {
            $user = $DB->get_record('user', array('id' => $thiscontext->instanceid));

            $course = $this->page->course;

            if (user_can_view_profile($user, $course)) {

                $userpicture = new \user_picture($user);
                $userpicture->size = 400;
                $contextheader->large = true;
                $contextheader->pagename = fullname($user);
                $contextheader->icon = $userpicture->get_url($this->page, $this);
            }

            if ($USER->id == $user->id) {
                $data = (object) [
                    'currentimage' => $contextheader->icon,
                    'defaultimage' => '',
                    'size' => 120,
                    'rounded' => true,
                    'component' => 'core_user',
                    'contextid' => \context_user::instance($user->id)->id,
                    'filearea' => 'icon',
                    'draftitemid' => false,
                    'formelements' => [],
                ];
                $editable = new \core\output\image_editable($data);
                $contextheader->editimage = $this->render($editable);
            }
        }

        // Course Module
        if ($thiscontext->contextlevel == CONTEXT_MODULE) {
            global $DB;
            $header->activitycontext = true;
            if ($cm = $DB->get_record_sql(
                    "SELECT cm.*, md.name AS modname
                                           FROM {course_modules} cm
                                           JOIN {modules} md ON md.id = cm.module
                                           WHERE cm.id = ?",
                    [$thiscontext->instanceid]
            )) {
                $format = course_get_format($this->page->course);
                $course = $format->get_course(); // Needed to have numsections property available.
                $modinfo = get_fast_modinfo($course);
                $header->modname = $cm->modname;
                $module = $modinfo->cms[$cm->id];
                $courseurl = new \moodle_url(
                    '/course/view.php',
                    ['id' => $course->id]
                );

                $contextheader->pagename = $module->get_formatted_name();
                $contextheader->icon = $module->get_icon_url();
            }
        }

        // Course category
        if ($thiscontext->contextlevel == CONTEXT_COURSECAT) {
            $contextheader->pagename = $this->page->heading;
            $contextheader->bgicon = $this->get_generated_image_for_id($thiscontext->instanceid);
        }

        // Site home
        if ($thiscontext->contextlevel == CONTEXT_COURSE && $this->page->course->id == 1) {
            $contextheader->pagename = $this->page->heading;
            $contextheader->large = true;
            $contextheader->icon = $this->get_logo_url();
            $contextheader->navbar = false;

        // Altered page header when visiting a course.
        } else if ($thiscontext->contextlevel == CONTEXT_COURSE) {

            // Retreive the information about this course to show in the page header
            $header->coursecontext = true;
            $course = $this->page->course;

            $exporter = new course_summary_exporter($course, ['context' => $thiscontext]);
            $courseexport = $exporter->export($this);

            // Generate the variables for the context header.
            $contextheader->pagename = $courseexport->fullname;
            $contextheader->bgicon = $courseexport->courseimage;

        }
        $header->contextheader = $this->render_from_template('theme_boost/contextheader', $contextheader);
        return $this->render_from_template('core/full_header', $header);
    }

}
