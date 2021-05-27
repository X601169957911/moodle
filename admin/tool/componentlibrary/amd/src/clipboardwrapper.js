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
 * Interface to using clipboard.js
 *
 * @module     tool_componentlibrary/clipboardwrapper
 * @package    tool_componentlibrary
 * @copyright  2021 Bas Brands <bas@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

import Tooltip from 'theme_boost/bootstrap/tooltip';
import $ from 'jquery';
import Clipboard from 'tool_componentlibrary/clipboard';
import {get_strings as getStrings} from 'core/str';
import selectors from 'tool_componentlibrary/selectors';
import Templates from 'core/templates';

/**
 * Initialise the clipboard button on all reusable code.
 */
export const clipboardWrapper = async() => {
    const strings = await getStrings([
        {
            key: 'copied',
            component: 'tool_componentlibrary'
        },
        {
            key: 'copytoclipboard',
            component: 'tool_componentlibrary'
        },
    ]);

    const {html, js} = await Templates.renderForPromise('tool_componentlibrary/clipboardbutton', {});
    document.querySelectorAll(selectors.clipboardcontent)
        .forEach(element => {
            Templates.prependNodeContents(element, html, js);
        });

    const clClipboard = new Clipboard(selectors.clipboardbutton, {
        target: (trigger) => {
            return trigger.parentNode.nextElementSibling;
        }
    });

    clClipboard.on('success', e => {
        // Hide the original tooltip
        $(e.trigger).tooltip('dispose');

        // Show an new tooltip with the Copied string.
        const tooltipBtn = new Tooltip(e.trigger);
        e.trigger.setAttribute('data-original-title', strings[0]);
        tooltipBtn.show();
        setTimeout(() => {
            tooltipBtn.dispose();
        }, 3000);
        e.clearSelection();
        e.trigger.setAttribute('data-original-title', strings[1]);
    });
};
