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
 * This component is used to control the expand/collapse all feature for course sections .
 *
 * @module     core_courseformat/local/content/section/collapseall
 * @copyright  2021 Bas Brands <bas@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

import $ from 'jquery';

const collapseall = () => {
    const collapsemenu = document.getElementById('collapsesections');
    collapsemenu.addEventListener('click', () => {
        let action = 'hide';
        if (collapsemenu.classList.contains('collapsed')) {
            action = 'show';
        }

        document.querySelectorAll('.course-content-item-content').forEach(collapsecontainer => {
            $(collapsecontainer).collapse(action);
        });
    });

    $('.course-content-item-content').on('hidden.bs.collapse', () => {
        let allcollapsed = true;
        $('.course-content-item-content').each((_, collapsecontainer) => {
            if (collapsecontainer.classList.contains('show')) {
                allcollapsed = false;
            }
        });
        if (allcollapsed) {
            collapsemenu.classList.add('collapsed');
        }
    });

    $('.course-content-item-content').on('shown.bs.collapse', () => {
        let allexpanded = true;
        $('.course-content-item-content').each((_, collapsecontainer) => {
            if (!collapsecontainer.classList.contains('show')) {
                allexpanded = false;
            }
        });

        if (allexpanded) {
            collapsemenu.classList.remove('collapsed');
        }
    });
};

collapseall();
