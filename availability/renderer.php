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

/**
 * Renderer for availability display.
 *
 * @package core_availability
 * @copyright 2014 The Open University
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Renderer for availability display.
 *
 * @package core_availability
 * @copyright 2014 The Open University
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class core_availability_renderer extends plugin_renderer_base {
    /**
     * Renders HTML for the result of two or more availability restriction
     * messages being combined in a list.
     *
     * The supplied messages should already take account of the 'not' option,
     * e.g. an example message could be 'User profile field Department must
     * not be set to Maths'.
     *
     * This function will not be called unless there are at least two messages.
     *
     * @param core_availability_multiple_messages $renderable Multiple messages
     * @return string Combined HTML
     */

    /** @var int counts number of conditions */
    public $count = 0;

    /** @var bool If these conditions have parent conditions, ie. are part of a subset  */
    public $hasparent = false;

    public function render_core_availability_multiple_messages(
            core_availability_multiple_messages $renderable) {
        // Make the list.
        $template = (object)[];
        // Get initial message.
        $template->header = get_string('list_' . ($renderable->root ? 'root_' : '') .
                ($renderable->andoperator ? 'and' : 'or') . ($renderable->treehidden ? '_hidden' : ''),
                'availability');
        $template->items = [];
        $template->parent = $this->count;

        $maxvisible = 4;

        if (!$this->hasparent) {
            $template->parentcontainer = uniqid();
            $this->hasparent = true;
        }

        foreach ($renderable->items as $item) {
            $message = (object)[];

            if (is_string($item)) {
                $this->count++;
                $message->title = $item;
                if ($this->count == $maxvisible) {
                    $message->abbreviate = true;
                }
                if ($this->count > $maxvisible) {
                    $message->class = 'd-none';
                }
            } else {
                if ($this->count == $maxvisible) {
                    $message->class = 'd-none';
                }
                $message->title = $this->render($item);
            }

            $template->items[] = $message;

            if (is_string($item) && $this->count == $maxvisible) {
                // Insert a more link time.
                $morelink = (object)[];
                $morelink->showmorelink = true;
                $template->items[] = $morelink;
            }
        }

        $html = $this->render_from_template('core_availability/availability_multiple_messages', $template);
        // Removing newlines for unit test in tree_test.php.
        return str_replace(array("\r", "\n"), '', $html);
    }
}
