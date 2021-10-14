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
 * Interface to the Lunr search engines.
 *
 * @module     core_availability/availability_more
 * @copyright  2021 Bas Brands <bas@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

export const init = () => {
    const body = document.querySelector('body');

    if (!body.dataset.showmoreactive) {
        document.addEventListener('click', event => {
            const triggerElement = event.target.closest('.showmore');
            if (triggerElement === null) {
                return;
            }
            const container = triggerElement.closest('[data-region="availability-multiple"]');
            container.querySelectorAll('.d-none').forEach(function(node) {
                node.classList.remove('d-none');
            });
            container.querySelectorAll('.d-block').forEach(function(node) {
                node.classList.remove('d-block');
                node.classList.add('d-none');
            });
            event.preventDefault();
        });
        body.dataset.showmoreactive = 1;
    }
};
