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

defined('MOODLE_INTERNAL') || die();

global $CFG;

require_once($CFG->libdir . '/form/filepicker.php');

/**
 * Single image type form element.
 *
 * Creates a form element that allows changing a default
 * image to a user selected image, and allows resizing + cropping the image.
 *
 * @package   core_form
 * @category  form
 * @copyright 2021 Bas Brands <bas@moodle.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class MoodleQuickForm_singleimage extends MoodleQuickForm_filepicker {

     /** @var array $_options single image default options. */
    protected $_options = [
        'currentimage' => '',
        'defaultimage' => '',
        'rounded' => false,
        'component' => 'user',
        'contextid' => '',
        'filearea' => 'draft'
    ];

    /**
     * Constructor
     *
     * @param string $elementname (optional) name of the singleimagefield
     * @param string $elementlabel (optional) singleimage label
     * @param array $attributes (optional) Either a typical HTML attribute string
     *              or an associative array
     * @param array $options set of options to initalize singleimage
     */
    public function __construct($elementname=null, $elementlabel=null, $attributes=null, $options=null) {
        parent::__construct($elementname, $elementlabel, $attributes, $options);
        $this->_type = 'singleimage';
    }

    /**
     * Returns HTML for filepicker form element.
     *
     * @return string
     */
    public function toHtml(): string {
        global $OUTPUT;

        $id     = $this->_attributes['id'];
        $elname = $this->_attributes['name'];

        if ($this->_flagFrozen) {
            return $this->getFrozenHtml();
        }
        if (!$draftitemid = (int)$this->getValue()) {
            // No existing area info provided - let's use fresh new draft area.
            $draftitemid = file_get_unused_draft_itemid();
            $this->setValue($draftitemid);
        }

        $data = (object) [
            'currentimage' => $this->_options['currentimage'],
            'defaultimage' => $this->_options['defaultimage'],
            'size' => 200,
            'rounded' => $this->_options['rounded'],
            'component' => $this->_options['component'],
            'contextid' => $this->_options['contextid'],
            'filearea' => $this->_options['filearea'],
            'draftitemid' => $draftitemid,
            'formelements' => ['name' => $elname, 'id' => $id, 'value' => $draftitemid]
        ];
        $editable = new \core\output\image_editable($data);
        return $OUTPUT->render($editable);
    }

    /**
     * export uploaded file
     *
     * @param array $submitvalues values submitted.
     * @param bool $assoc specifies if returned array is associative
     * @return array
     */
    public function exportValue(&$submitvalues, $assoc = false): ?array {

        $draftitemid = $this->_findValue($submitvalues);
        if (null === $draftitemid) {
            $draftitemid = $this->getValue();
        }

        return $this->_prepareValue($draftitemid, true);
    }

    /**
     * Check that the file has the allowed type.
     *
     * @param int $value Draft item id with the uploaded files.
     * @return string|null Validation error message or null.
     */
    public function validateSubmitValue($value): ?string {

        $draftfiles = file_get_drafarea_files($value);

        if (empty($draftfiles)) {
            // No file uploaded, nothing to check here.
            return 'no draftfiles';
        }

        return null;
    }

}
