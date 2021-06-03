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

namespace theme_boost\output\core;
defined('MOODLE_INTERNAL') || die();

use moodle_url;
use stdClass;
use html_writer;
use core_course\external\course_summary_exporter;

require_once($CFG->dirroot . '/course/renderer.php');

/**
 *
 * Overridden Core Course Renderer for the Boost
 *
 * @package    theme_clear
 * @copyright  2021 Bas Brands <bas@sonsbeekmedia.nl>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 */

class course_renderer extends \core_course_renderer {

    /**
     * Returns HTML to print tree of course categories (with number of courses) for the frontpage
     *
     * @return string
     */
    public function frontpage_categories_list() {
        global $CFG;
        // TODO MDL-10965 improve.
        $tree = \core_course_category::top();
        if (!$tree->get_children_count()) {
            return '';
        }
        $chelper = new \coursecat_helper();
        $chelper->set_subcat_depth($CFG->maxcategorydepth)->
                set_show_courses(self::COURSECAT_SHOW_COURSES_COUNT)->
                set_categories_display_options(array(
                    'limit' => $CFG->coursesperpage,
                    'viewmoreurl' => new moodle_url('/course/index.php',
                            array('browse' => 'categories', 'page' => 1))
                ))->
                set_attributes(array('class' => 'frontpage-category-names'));
        return $this->coursecat_boost($chelper, $tree);
    }

    /**
     * Returns HTML to display a tree of subcategories and courses in the given category
     *
     * @param coursecat_helper $chelper various display options
     * @param core_course_category $coursecat top category (this category's name and description will NOT be added to the tree)
     * @return string
     */
    protected function coursecat_boost(\coursecat_helper $chelper, $coursecat) {

        $categorycontent = $this->boost_coursecat_category_content($chelper, $coursecat, 0);
        if (empty($categorycontent)) {
            return '';
        }

        if ($coursecat->get_children_count()) {
        }

        return $categorycontent;
    }

    /**
     * Returns HTML to display the subcategories and courses in the given category
     *
     * This method is re-used by AJAX to expand content of not loaded category
     *
     * @param coursecat_helper $chelper various display options
     * @param core_course_category $coursecat
     * @param int $depth depth of the category in the current tree
     * @return string
     */
    protected function boost_coursecat_category_content(\coursecat_helper $chelper, $coursecat, $depth) {
        return $this->boost_coursecat_subcategories($chelper, $coursecat, $depth);
    }

    /**
     * Renders the list of subcategories in a category
     *
     * @param coursecat_helper $chelper various display options
     * @param core_course_category $coursecat
     * @param int $depth depth of the category in the current tree
     * @return string
     */
    protected function boost_coursecat_subcategories(\coursecat_helper $chelper, $coursecat, $depth) {
        global $CFG;
        $subcategories = array();
        if (!$chelper->get_categories_display_option('nodisplay')) {
            $subcategories = $coursecat->get_children($chelper->get_categories_display_options());
        }
        $totalcount = $coursecat->get_children_count();
        if (!$totalcount) {
            // Note that we call core_course_category::get_children_count() AFTER core_course_category::get_children()
            // to avoid extra DB requests.
            // Categories count is cached during children categories retrieval.
            return '';
        }

        // prepare content of paging bar or more link if it is needed
        $paginationurl = $chelper->get_categories_display_option('paginationurl');
        $paginationallowall = $chelper->get_categories_display_option('paginationallowall');
        if ($totalcount > count($subcategories)) {
            if ($paginationurl) {
                // the option 'paginationurl was specified, display pagingbar
                $perpage = $chelper->get_categories_display_option('limit', $CFG->coursesperpage);
                $page = $chelper->get_categories_display_option('offset') / $perpage;
                $pagingbar = $this->paging_bar($totalcount, $page, $perpage,
                        $paginationurl->out(false, array('perpage' => $perpage)));
                if ($paginationallowall) {
                    $pagingbar .= html_writer::tag('div', html_writer::link($paginationurl->out(false, array('perpage' => 'all')),
                            get_string('showall', '', $totalcount)), array('class' => 'paging paging-showall'));
                }
            } else if ($viewmoreurl = $chelper->get_categories_display_option('viewmoreurl')) {
                // the option 'viewmoreurl' was specified, display more link (if it is link to category view page, add category id)
                if ($viewmoreurl->compare(new moodle_url('/course/index.php'), URL_MATCH_BASE)) {
                    $viewmoreurl->param('categoryid', $coursecat->id);
                }
                $viewmoretext = $chelper->get_categories_display_option('viewmoretext', new lang_string('viewmore'));
                $morelink = html_writer::tag('div', html_writer::link($viewmoreurl, $viewmoretext),
                        array('class' => 'paging paging-morelink'));
            }
        } else if (($totalcount > $CFG->coursesperpage) && $paginationurl && $paginationallowall) {
            // there are more than one page of results and we are in 'view all' mode, suggest to go back to paginated view mode
            $pagingbar = html_writer::tag('div', html_writer::link($paginationurl->out(false, array('perpage' => $CFG->coursesperpage)),
                get_string('showperpage', '', $CFG->coursesperpage)), array('class' => 'paging paging-showperpage'));
        }

        // display list of subcategories
        $content = html_writer::start_tag('div', array('class' => 'subcategories'));

        if (!empty($pagingbar)) {
            $content .= $pagingbar;
        }

        $template = (object)[];
        foreach ($subcategories as $subcategory) {
            $template->categories[] = $this->boost_coursecat_category($subcategory);
        }
        $content .= $this->render_from_template('theme_boost/categorycards', $template);

        if (!empty($pagingbar)) {
            $content .= $pagingbar;
        }
        if (!empty($morelink)) {
            $content .= $morelink;
        }

        $content .= html_writer::end_tag('div');
        return $content;
    }

    /**
     * Returns HTML to display a course category as a part of a tree
     *
     * This is an internal function, to display a particular category and all its contents
     * use {@link core_course_renderer::course_category()}
     *
     * @param coursecat_helper $chelper various display options
     * @param core_course_category $coursecat
     * @param int $depth depth of this category in the current tree
     * @return string
     */
    protected function boost_coursecat_category($coursecat) {
        // open category tag
        $template = (object)[];

        // category name
        $template->categoryname = $coursecat->get_formatted_name();
        $template->viewurl = new moodle_url('/course/index.php', ['categoryid' => $coursecat->id, 'redirect' => 0]);
        $template->coursescount = $coursecat->get_courses_count();
        $template->categoryimage = $this->get_generated_image_for_id($coursecat->id);

        return $template;
    }

    /**
     * Returns HTML to print list of available courses for the frontpage
     *
     * @return string
     */
    public function frontpage_available_courses() {
        global $CFG;

        $chelper = new \coursecat_helper();
        $chelper->set_show_courses(self::COURSECAT_SHOW_COURSES_EXPANDED)->
                set_courses_display_options(array(
                    'recursive' => true,
                    'limit' => $CFG->frontpagecourselimit,
                    'viewmoreurl' => new moodle_url('/course/index.php'),
                    'viewmoretext' => new \lang_string('fulllistofcourses')));

        $chelper->set_attributes(array('class' => 'frontpage-course-list-all'));
        $courses = \core_course_category::get(0)->get_courses($chelper->get_courses_display_options());
        $totalcount = \core_course_category::get(0)->get_courses_count($chelper->get_courses_display_options());
        if (!$totalcount && !$this->page->user_is_editing() && has_capability('moodle/course:create', context_system::instance())) {
            // Print link to create a new course, for the 1st available category.
            return $this->add_new_course_button();
        }
        return $this->coursecat_course_cards($courses);
    }

    protected function coursecat_course_cards($courses) {
        global $CFG, $OUTPUT, $PAGE;

        $template = new stdClass;
        $template->courses = [];
        foreach ($courses as $course) {
            $course = get_course($course->id);
            $context = \context_course::instance($course->id);
            // Make sure we delete the cache image introduced in MDL-66667
            \cache::make('core', 'course_image')->delete($course->id);
            $exporter = new course_summary_exporter($course, ['context' => $context]);
            $coursecard = $exporter->export($OUTPUT);

            if ($PAGE->user_is_editing()) {
                $data = (object) [
                    'currentimage' => $coursecard->courseimage,
                    'defaultimage' => '',
                    'size' => false,
                    'rounded' => false,
                    'component' => 'core_course',
                    'contextid' => \context_course::instance($course->id)->id,
                    'filearea' => 'overviewfiles',
                    'draftitemid' => false,
                    'formelements' => [],
                ];
                $editable = new \core\output\image_editable($data);
                $coursecard->editimage = $OUTPUT->render($editable);
            }

            $template->courses[] = $coursecard;
        }

        return $this->render_from_template('theme_boost/coursecards', $template);
    }

}
