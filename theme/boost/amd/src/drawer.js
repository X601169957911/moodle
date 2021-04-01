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
    medium: 768
};

const pageWrapper = document.querySelector('#page-wrapper');

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

const closeNav = (navRegion, closeBackdrop = true) => {

    classUtil('remove', navRegion, 'show');

    if (isSmall() && closeBackdrop) {
        getBackdrop().then(backdrop => {
            backdrop.hide();
            pageWrapper.style.overflow = 'auto';
        });
    }
};

const showNav = (navRegion) => {

    classUtil('add', navRegion, 'show');

    if (isSmall()) {
        getBackdrop().then(backdrop => {
            backdrop.setZIndex(1020);
            backdrop.show();
            pageWrapper.style.overflow = 'hidden';
            backdrop.getRoot()[0].addEventListener('click', () => {
                closeNav(navRegion);
            });
        });
    }

    const region = navRegion.getAttribute('data-region');
    if (region) {
        navRegion.dispatchEvent(new CustomEvent('show-boost-drawer', { bubbles: true, detail: region }));
    }
};

const isSmall = () => {
    return document.body.clientWidth < Sizes.medium;
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
            closeNav(navRegion);
        } else {
            showNav(navRegion);
        }
    });

    if (closeButton) {
        closeButton.addEventListener('click', () => {
            closeNav(navRegion);
        });
    }

    // Close drawer when another drawer opens.
    document.addEventListener('show-boost-drawer', e => {
        const region = navRegion.getAttribute('data-region');
        if (region !== e.detail) {
            closeNav(navRegion, false);
        }
    });

};
