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
        return '';
    }

    public function editing_button() {
        global $PAGE;
        $url = $PAGE->url;
        $url->param('sesskey', sesskey());
        if ($PAGE->user_is_editing()) {
            $url->param('edit', 'off');
            $edit = 'off';
            $adminedit = 0;
            $editstring = get_string('turneditingoff');
            $checked = 'checked';
        } else {
            $url->param('edit', 'on');
            $edit = 'on';
            $adminedit = 1;
            $editstring = get_string('turneditingon');
            $checked = '';
        }
        $url->param('sesskey', sesskey());
        $url->param('adminedit', $adminedit);
        $navlink = '<a class="nav-link" href='.$url->out().'><i class="fa fa-pencil"></i></a>';
        if ($PAGE->user_allowed_editing()) {
            return $navlink;
        }
    }
    /**
     * Renders the "breadcrumb" for all pages in boost.
     *
     * @return string the HTML for the navbar.
     */
    public function navbar(): string {
        $newnav = new \theme_boost\boostnavbar($this->page->navbar);
        return $this->render_from_template('core/navbar', $newnav);
    }

    public function courseindex(): ?string {
        global $DB, $PAGE;
        $selected = optional_param(
                'section',
                null,
                PARAM_INT
        );
        $format = course_get_format($PAGE->course);
        $course = $format->get_course();
        if (!$format->uses_sections()) {
            return null;
        }

        $sections = $format->get_sections();

        if (empty($sections)) {
            return null;
        }

        $context = \context_course::instance($course->id);

        $modinfo = get_fast_modinfo($course);

        $template = (object)[];

        $completioninfo = new \completion_info($course);

        if ($completioninfo->is_enabled()) {
            $template->completionon = 'completion';
        }

        $completionok = [
                COMPLETION_COMPLETE,
                COMPLETION_COMPLETE_PASS
        ];

        $thiscontext = \context::instance_by_id($PAGE->context->id);

        $inactivity = false;
        $myactivityid = 0;

        if ($thiscontext->get_level_name() == get_string('activitymodule')) {
            // Uh-oh we are in a activity.
            $inactivity = true;
            if ($cm = $DB->get_record_sql(
                    "SELECT cm.*, md.name AS modname
                                           FROM {course_modules} cm
                                           JOIN {modules} md ON md.id = cm.module
                                           WHERE cm.id = ?",
                    [$thiscontext->instanceid]
            )) {
                $myactivityid = $cm->id;
            }
        }

        $template->inactivity = $inactivity;

        if (count($sections) > 1) {
            $template->hasprevnext = true;
            $template->hasnext = true;
            $template->hasprev = true;
        }

        $courseurl = new moodle_url(
                '/course/view.php',
                ['id' => $course->id]
        );

        $template->courseurl = $courseurl->out();

        $sectionnums = [];
        foreach ($sections as $section) {
            $sectionnums[] = $section->section;
        }
        foreach ($sections as $section) {
            $i = $section->section;
            if (!$section->uservisible) {
                continue;
            }

            if (!empty($section->name)) {
                $title = format_string(
                        $section->name,
                        true,
                        ['context' => $context]
                );
            } else {
                $summary = file_rewrite_pluginfile_urls(
                        $section->summary,
                        'pluginfile.php',
                        $context->id,
                        'course',
                        'section',
                        $section->id
                );
                $summary = format_text(
                        $summary,
                        $section->summaryformat,
                        [
                                'para' => false,
                                'context' => $context
                        ]
                );
                $title = $format->get_section_name($section);
            }
        // "sections": [
        //     {
        //         "name": "General",
        //         "number": 1,
        //         "url": "#",
        //         "isactive": 1,
        //         "modules": [
        //             {
        //                 "name": "Glossary of characters",
        //                 "url": "#",
        //                 "visibility": 1,
        //                 "isactive": 0
        //             },
        //             {
        //                 "name": "World Cinema forum",
        //                 "url": "#",
        //                 "visibility": 1,
        //                 "isactive": 0
        //             },
        //             {
        //                 "name": "Announcements",
        //                 "url": "#",
        //                 "visibility": 1,
        //                 "isactive": 1
        //             }
        //         ]
        //     },
            $thissection = (object)[];
            $thissection->number = $i;
            $thissection->name = $title;
            $thissection->url = $format->get_view_url($section);
            $thissection->isactive = false;

            if ($i == $selected && !$inactivity) {
                $thissection->isactive = true;
            }

            $thissection->modules = [];
            if (!empty($modinfo->sections[$i])) {
                foreach ($modinfo->sections[$i] as $modnumber) {
                    $module = $modinfo->cms[$modnumber];
                    if ($module->modname == 'label') {
                        continue;
                    }
                    if (!$module->uservisible || !$module->visible || !$module->visibleoncoursepage) {
                        continue;
                    }
                    $thismod = (object)[];

                    if ($inactivity) {
                        if ($myactivityid == $module->id) {
                            $thissection->isactive = true;
                            $thismod->isactive = true;
                        } else {
                            $thismod->isactive = false;
                        }
                    } else {
                        $thismod->isactive = false;
                    }

                    $thismod->name = format_string(
                            $module->name,
                            true,
                            ['context' => $context]
                    );

                    $thismod->url = $module->url;
                    if ($module->modname == 'label') {
                        $thismod->url = '';
                        $thismod->label = 'true';
                    }
                    $hascompletion = $completioninfo->is_enabled($module);
                    if ($hascompletion) {
                        $thismod->completeclass = 'incomplete';
                    }
                    $completiondata = $completioninfo->get_data(
                            $module,
                            true
                    );
                    if (in_array(
                            $completiondata->completionstate,
                            $completionok
                    )) {
                        $thismod->completeclass = 'completed';
                    }
                    $thissection->modules[] = $thismod;
                }
                $thissection->hasmodules = (count($thissection->modules) > 0);
                $template->sections[] = $thissection;
            }
        }
        return $this->render_from_template('core_course/courseindex', $template);
    }
}
