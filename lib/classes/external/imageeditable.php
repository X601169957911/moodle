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
 * A web service to load the mapping of moodle pix names to fontawesome icon names.
 *
 * @package    core
 * @category   external
 * @copyright  2021 Bas Brands <bas@sonsbeekmedia.nl>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace core\external;

use external_api;
use external_function_parameters;
use external_single_structure;
use external_value;

/**
 * Web service to load store images from the imageeditable component.
 *
 * @package    core
 * @category   external
 * @copyright  2021 Bas Brands <bas@sonsbeekmedia.nl>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class imageeditable extends external_api {

    /**
     * Description of the parameters suitable for the `update_image` function.
     *
     * @return external_function_parameters
     */
    public static function update_image_parameters() {
        $parameters = [
            'params' => new \external_single_structure([
                'imagedata' => new \external_value(PARAM_TEXT, 'Image data', VALUE_REQUIRED),
                'imagefilename' => new \external_value(PARAM_TEXT, 'Image filename', VALUE_REQUIRED),
                'cropped' => new \external_value(PARAM_INT, 'Cropped version', VALUE_OPTIONAL),
                'delete' => new \external_value(PARAM_INT, 'Delete image', VALUE_OPTIONAL),
                'component' => new \external_value(PARAM_RAW, 'Component', VALUE_REQUIRED),
                'filearea' => new \external_value(PARAM_RAW, 'File area', VALUE_REQUIRED),
                'contextid' => new \external_value(PARAM_INT, 'Contextid', VALUE_REQUIRED),
                'draftitemid' => new \external_value(PARAM_INT, 'Draftitemid', VALUE_OPTIONAL),
            ], 'Params wrapper - just here to accommodate optional values', VALUE_REQUIRED)
        ];
        return new \external_function_parameters($parameters);
    }

    /**
     * Save the image and return any warnings and the new image url
     *
     * @param   string $params parameters for saving the image
     * @return  array the save image return values
     */
    public static function update_image($params) {
        global $CFG, $DB, $PAGE, $USER;

        $params = self::validate_parameters(self::update_image_parameters(), ['params' => $params])['params'];

        $component = $params['component'];
        $filearea = $params['filearea'];
        $contextid = $params['contextid'];
        $delete = isset($params['delete']) ? 1 : 0;
        $draftitemid = $params['draftitemid'];
        $filename = $params['imagefilename'];

        $context = \context::instance_by_id($contextid);
        self::validate_context($context);
        $params['context'] = $context;
        $binary = base64_decode($params['imagedata']);

        $success = false;
        $fileurl = false;
        $warning = false;

        if ($delete) {
            $fs = get_file_storage();
            $personalcontext = \context_user::instance($USER->id);
            $fs->delete_area_files($personalcontext->id, 'user', 'draft', $draftitemid);
            $success = true;
        } else if ($draftitemid > 0) {
            $fs = get_file_storage();
            $personalcontext = \context_user::instance($USER->id);
            $fs->delete_area_files($personalcontext->id, 'user', 'draft', $draftitemid);
            // Temporarely store the image.
            $draftfile = array(
                'contextid' => $personalcontext->id,
                'component' => 'user',
                'filearea' => 'draft',
                'itemid' => $draftitemid,
                'filepath' => '/',
                'filename' => $filename,
            );
            $tempfile = $fs->create_file_from_string($draftfile, $binary);
            $url = \moodle_url::make_draftfile_url(
                $draftitemid,
                '/',
                $tempfile->get_filename()
            );
            $fileurl = $url->out();
            $success = true;
        } else {
            $classname = "\\$component\\imageeditable\\handler";
            if (class_exists($classname)) {
                if ($delete) {
                    $warning = $classname::delete($contextid, $filearea);
                } else {
                    $warning = $classname::store($contextid, $filearea, $filename, $binary);
                }
                $success = true;
            } else {
                throw new coding_exception("$classname not found");
            }
        }

        return ['success' => $success, 'fileurl' => $fileurl, 'warning' => $warning];
    }

    /**
     * Description of the return value for the `update_image` function.
     *
     * @return external_description
     */
    public static function update_image_returns() {
        $keys = [
            'success' => new \external_value(PARAM_BOOL, 'Was the cover image successfully changed', VALUE_REQUIRED),
            'warning' => new \external_value(PARAM_TEXT, 'Warning', VALUE_OPTIONAL),
            'fileurl' => new \external_value(PARAM_TEXT, 'New file url', VALUE_REQUIRED)
        ];

        return new \external_single_structure($keys, 'coverimage');
    }
}
