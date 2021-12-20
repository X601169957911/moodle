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
 *
 * @module core_form/collapsesections
 * @copyright 2021 Bas Brands
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @since 4.0
 */

import $ from 'jquery';
import Pending from 'core/pending';

const SELECTORS = {
    FORMCONTAINER: '.fcontainer',
};

/**
 * Initialises the form section collapse / expand action.
 *
 */
export const init = () => {
    const pendingPromise = new Pending('core_form/collapsesections');
    const collapsemenu = document.getElementById('collapsesections');
    collapsemenu.addEventListener('click', () => {
        let action = 'hide';
        if (collapsemenu.classList.contains('collapsed')) {
            action = 'show';
        }

        document.querySelectorAll(SELECTORS.FORMCONTAINER).forEach((collapsecontainer) => {
            $(collapsecontainer).collapse(action);
        });
    });
    $(SELECTORS.FORMCONTAINER).on('hidden.bs.collapse', () => {
        let allcollapsed = true;
        $(SELECTORS.FORMCONTAINER).each((_, collapsecontainer) => {
            if (collapsecontainer.classList.contains('show')) {
                allcollapsed = false;
            }
        });
        if (allcollapsed) {
            collapsemenu.classList.add('collapsed');
        }
    });
    $(SELECTORS.FORMCONTAINER).on('shown.bs.collapse', () => {
        var allexpanded = true;
        $(SELECTORS.FORMCONTAINER).each((_, collapsecontainer) => {
            if (!collapsecontainer.classList.contains('show')) {
                allexpanded = false;
            }
        });

        if (allexpanded) {
            collapsemenu.classList.remove('collapsed');
        }
    });
    pendingPromise.resolve();
};
