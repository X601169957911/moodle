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
 * Toggling the visibility of the secondary navigation on mobile.
 *
 * @package    theme_boost
 * @copyright  2021 Bas Brands
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
import {classUtil} from 'core/utils';
import ModalBackdrop from 'core/modal_backdrop';
import Templates from 'core/templates';
import Notification from 'core/notification';

let backdropPromise = null;
/**
 * Simple object of sizes to respond to so we can easily modify as required.
 */
const Sizes = {
    medium: 991,
    large: 1200
};

const pageWrapper = document.getElementById('page-wrapper');
const page = document.getElementById('page');

const getBackdrop = () => {
    if (!backdropPromise) {
        // Would look to see if there is a way to migrate this to renderForPromise to remove the .then().
        // Interesting idea to use some of the modal factory. Unsure if it is a good idea or
        // if it should be more of a generic helper.
        backdropPromise = Templates.render('core/modal_backdrop', {})
            .then(html => {
                return new ModalBackdrop(html);
            })
            .fail(Notification.exception);
    }
    return backdropPromise;
};

const closeNav = (navRegion, toggleButton, closeBackdrop = true) => {
    const preference = toggleButton.getAttribute('data-preference');
    const state = toggleButton.getAttribute('data-state');
    classUtil('remove', navRegion, 'show');

    if (state) {
        classUtil('remove', page, state);
    }
    if (!isMedium() && preference) {
        M.util.set_user_preference(preference, 'false');
    }

    if (isMedium() && closeBackdrop) {
        getBackdrop().then(backdrop => {
            backdrop.hide();
            pageWrapper.style.overflow = 'auto';
        });
    }
};

const showNav = (navRegion, toggleButton) => {
    const preference = toggleButton.getAttribute('data-preference');
    const state = toggleButton.getAttribute('data-state');
    classUtil('add', navRegion, 'show');

    if (!isMedium() && preference) {
        M.util.set_user_preference(preference, 'true');
    }
    if (state) {
        classUtil('add', page, state);
    }

    if (isMedium()) {
        getBackdrop().then(backdrop => {
            backdrop.setZIndex(1020);
            backdrop.show();
            pageWrapper.style.overflow = 'hidden';
            backdrop.getRoot()[0].addEventListener('click', () => {
                closeNav(navRegion);
            });
        });
    }

    const trigger = navRegion.getAttribute('data-trigger');
    if (trigger) {
        navRegion.dispatchEvent(new CustomEvent('show-boost-drawer', { bubbles: true, detail: trigger }));
    }
};

const isMedium = () => {
    return document.body.clientWidth <= Sizes.medium;
};

const isLarge = () => {
    return document.body.clientWidth <= Sizes.large;
};

export const region = (region, toggle) => {
    const navRegion = document.querySelector(region);
    const toggleButton = document.querySelector(toggle);

    if (!navRegion) {
        return '';
    }

    const closeButton = navRegion.querySelector('[data-action="closedrawer"]');

    toggleButton.addEventListener('click', () => {
        if (classUtil('has', navRegion, 'show')) {
            closeNav(navRegion, toggleButton);
        } else {
            showNav(navRegion, toggleButton);
        }
    });

    if (closeButton) {
        closeButton.addEventListener('click', () => {
            closeNav(navRegion, toggleButton);
        });
    }
    if (toggleButton.getAttribute('data-closeonresize')) {
        window.addEventListener('resize', () => {
            closeNav(navRegion, toggleButton);
        });
    }

    // Close drawer when another drawer opens.
    document.addEventListener('show-boost-drawer', e => {
        const trigger = navRegion.getAttribute('data-trigger');
        if (trigger != e.detail && isLarge()) {
            closeNav(navRegion, toggleButton, false);
        }
    });

};
