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
 * Controls the drawer.
 *
 * @module     core/edit_switch
 * @copyright  2021 Bas Brands <bas@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

import Ajax from 'core/ajax';

/**
 * Change the Edit mode.
 *
 * @param {Object} args The request arguments
 * @return {Promise} Resolved with an array file the stored file url.
 */
const changeEditmode = args => {
    const request = {
        methodname: 'core_change_editmode',
        args: args
    };

    const promise = Ajax.call([request])[0]
        .fail(Notification.exception);

    return promise;
};

/**
 * Toggle the edit switch
 *
 * @param {Object} editSwitch DOMElement edit switch.
 */
const toggleEditSwitch = editSwitch => {
    if (editSwitch.checked) {
        editSwitch.setAttribute('aria-checked', true);
    } else {
        editSwitch.setAttribute('aria-checked', false);
    }

    window.location = editSwitch.dataset.pageurl;
};

/**
 * Add the eventlistener for the editswitch.
 */
export const init = () => {
    const editSwitch = document.getElementById('editingswitch');
    editSwitch.addEventListener('change', function() {
        changeEditmode(
            {
                setmode: editSwitch.checked,
                context: editSwitch.dataset.context
            }
        ).then(result => {
            if (result.success) {
                toggleEditSwitch(editSwitch);
                editSwitch.dispatchEvent(new CustomEvent('change-edit-mode',
                    {bubbles: true, setmode: editSwitch.checked}));
            } else {
                editSwitch.checked = false;
            }
            return null;
        }).catch(Notification.exception);
    });
};
