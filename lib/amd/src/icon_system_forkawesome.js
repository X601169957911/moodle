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
 * Competency rule points module.
 *
 * @package    core
 * @copyright  2017 Damyon Wiese
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

import IconSystem from 'core/icon_system';
import $ from 'jquery';
import Ajax from 'core/ajax';
import Mustache from 'core/mustache';
import LocalStorage from 'core/localstorage';
import Url from 'core/url';

let staticMap = null;
let fetchMap = null;

/**
 * IconSystemForkawesome
 */
const IconSystemForkawesome = function() {
    IconSystem.apply(this, arguments);
};
IconSystemForkawesome.prototype = Object.create(IconSystem.prototype);

/**
 * Prefetch resources so later calls to renderIcon can be resolved synchronously.
 *
 * @method init
 * @return {Promise}
 */
IconSystemForkawesome.prototype.init = function() {
    if (staticMap) {
        return $.when(this);
    }

    let map = LocalStorage.get('core/iconmap-forkawesome');
    if (map) {
        map = JSON.parse(map);
    }

    if (map) {
        staticMap = map;
        return $.when(this);
    }

    if (fetchMap === null) {
        fetchMap = Ajax.call([{
            methodname: 'core_output_load_forkawesome_icon_map',
            args: []
        }], true, false, false, 0, M.cfg.themerev)[0];
    }

    return fetchMap.then(function(map) {
        staticMap = {};
        $.each(map, function(index, value) {
            staticMap[value.component + '/' + value.pix] = value.to;
        });
        LocalStorage.set('core/iconmap-forkawesome', JSON.stringify(staticMap));
        return this;
    }.bind(this));
};

/**
 * Render an icon.
 *
 * @param {String} key
 * @param {String} component
 * @param {String} title
 * @param {String} template
 * @return {String}
 * @method renderIcon
 */
IconSystemForkawesome.prototype.renderIcon = function(key, component, title, template) {
    const mappedIcon = staticMap[component + '/' + key];
    let unmappedIcon = false;
    if (typeof mappedIcon === "undefined") {
        const url = Url.imageUrl(key, component);

        unmappedIcon = {
            attributes: [
                {name: 'src', value: url},
                {name: 'alt', value: title},
                {name: 'title', value: title}
            ]
        };
    }

    const context = {
        key: mappedIcon,
        title: title,
        alt: title,
        unmappedIcon: unmappedIcon
    };

    if (typeof title === "undefined" || title === '') {
        context['aria-hidden'] = true;
    }

    const result = Mustache.render(template, context);
    return result.trim();
};

/**
 * Get the name of the template to pre-cache for this icon system.
 *
 * @return {String}
 * @method getTemplateName
 */
IconSystemForkawesome.prototype.getTemplateName = function() {
    return 'core/pix_icon_forkawesome';
};

/** @alias module:core/icon_system_forkawesome */
export default IconSystemForkawesome;
