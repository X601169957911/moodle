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
import Clipboard from 'tool_componentlibrary/clipboard';
import {get_string as getString} from 'core/str';
import selectors from 'tool_componentlibrary/selectors';

/**
 * Initialise the clipboard button on all reusable code.
 */
export const init = async() => {
    const copied = await getString('copied', 'tool_componentlibrary');
    const copy = await getString('copy', 'tool_componentlibrary');
    const copytoclipboard = await getString('copytoclipboard', 'tool_componentlibrary');

    const btnHtml = `<div class="bd-clipboard">
        <button type="button" class="btn-clipboard" title="${copytoclipboard}">${copy}</button>
        </div>`;

    document.querySelectorAll(selectors.clipboardcontent)
        .forEach((element) => {
            element.insertAdjacentHTML('beforebegin', btnHtml);
        });

    document.querySelectorAll(selectors.clipboardbutton)
        .forEach((btn) => {
            const tooltipBtn = new Tooltip(btn);

            btn.addEventListener('mouseleave', () => {
                // Explicitly hide tooltip, since after clicking it remains
                // focused (as it's a button), so tooltip would otherwise
                // remain visible until focus is moved away
                tooltipBtn.hide();
            });
        });

    const clClipboard = new Clipboard(selectors.clipboardbutton, {
        target: (trigger) => {
            return trigger.parentNode.nextElementSibling;
        }
    });

    clClipboard.on('success', (e) => {
        const tooltipBtn = new Tooltip(e.trigger);
        e.trigger.setAttribute('data-original-title', copied);
        tooltipBtn.show();
        setTimeout(() => {
            tooltipBtn.dispose();
        }, 3000);

        e.trigger.setAttribute('data-original-title', copytoclipboard);
        e.clearSelection();
    });
};
