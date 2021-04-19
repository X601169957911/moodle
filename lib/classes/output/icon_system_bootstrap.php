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
 * Contains class \core\output\icon_system
 *
 * @package    core
 * @category   output
 * @copyright  2021 Bas Brands
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace core\output;

use renderer_base;
use pix_icon;

defined('MOODLE_INTERNAL') || die();

/**
 * Class for the Bootstrap icon system.
 *
 *
 * @package    core
 * @category   output
 * @copyright  2021 Bas Brands
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class icon_system_bootstrap extends icon_system_font {

    /**
     * @var array $map Cached map of moodle icon names to font awesome icon names.
     */
    private $map = [];

    public function get_core_icon_map() {
        return [
            'core:docs' => 'info-circle',
            'core:help' => 'question-circle text-info',
            'core:req' => 'exclamation-circle text-danger',
            'core:a/add_file' => 'file',
            'core:a/create_folder' => 'file', // missing in bootstrap
            'core:a/download_all' => 'download',
            'core:a/help' => 'question-circle text-info',
            'core:a/logout' => 'door-open',
            'core:a/refresh' => 'arrow-clockwise',
            'core:a/search' => 'search',
            'core:a/setting' => 'gear',
            'core:a/view_icon_active' => 'app', // missing
            'core:a/view_list_active' => 'list',
            'core:a/view_tree_active' => 'file', //missing
            'core:b/bookmark-new' => 'bookmark',
            'core:b/document-edit' => 'pencil',
            'core:b/document-new' => 'file',
            'core:b/document-properties' => 'info',
            'core:b/edit-copy' => 'folder', //missing files
            'core:b/edit-delete' => 'trash',
            'core:e/abbr' => '',
            'core:e/absolute' => 'fullscreen-exit',
            'core:e/accessibility_checker' => 'universal-access', // really missing
            'core:e/acronym' => '',
            'core:e/advance_hr' => 'arrow-right',
            'core:e/align_center' => 'text-center',
            'core:e/align_left' => 'text-left',
            'core:e/align_right' => 'text-right',
            'core:e/anchor' => 'link',
            'core:e/backward' => 'back',
            'core:e/bold' => 'type-bold',
            'core:e/bullet_list' => 'list-ul',
            'core:e/cancel' => 'x',
            'core:e/cancel_solid_circle' => 'x-circle',
            'core:e/cell_props' => 'info',
            'core:e/cite' => 'blockquote-right',
            'core:e/cleanup_messy_code' => 'eraser',
            'core:e/clear_formatting' => 'cursor-text',
            'core:e/copy' => 'clipboard',
            'core:e/cut' => 'scissors',
            'core:e/decrease_indent' => 'text-indent-right',
            'core:e/delete_col' => 'dash',
            'core:e/delete_row' => 'dash',
            'core:e/delete' => 'dash',
            'core:e/delete_table' => 'dash',
            'core:e/document_properties' => 'info',
            'core:e/emoticons' => 'emoji-smile',
            'core:e/find_replace' => 'search',
            'core:e/file-text' => 'card-text',
            'core:e/forward' => 'arrow-right',
            'core:e/fullpage' => 'fullscreen',
            'core:e/fullscreen' => 'fullscreen',
            'core:e/help' => 'question-circle',
            'core:e/increase_indent' => 'text-indent-left',
            'core:e/insert_col_after' => 'layout-three-columns',
            'core:e/insert_col_before' => 'layout-three-columns',
            'core:e/insert_date' => 'calendar',
            'core:e/insert_edit_image' => 'image',
            'core:e/insert_edit_link' => 'link',
            'core:e/insert_edit_video' => 'camera-video',
            'core:e/insert_file' => 'file',
            'core:e/insert_horizontal_ruler' => 'rulers',
            'core:e/insert_nonbreaking_space' => 'app',
            'core:e/insert_page_break' => 'file-break',
            'core:e/insert_row_after' => 'plus',
            'core:e/insert_row_before' => 'plus',
            'core:e/insert' => 'plus',
            'core:e/insert_time' => 'clock',
            'core:e/italic' => 'type-italic',
            'core:e/justify' => 'align-justify',
            'core:e/layers_over' => 'box-arrow-up',
            'core:e/layers' => 'window-restore',
            'core:e/layers_under' => 'level-down',
            'core:e/left_to_right' => 'chevron-right',
            'core:e/manage_files' => 'folder', //missing files
            'core:e/math' => 'calculator',
            'core:e/merge_cells' => 'compress',
            'core:e/new_document' => 'file',
            'core:e/numbered_list' => 'list-ol',
            'core:e/page_break' => 'level-down',
            'core:e/paste' => 'clipboard',
            'core:e/paste_text' => 'clipboard',
            'core:e/paste_word' => 'clipboard',
            'core:e/prevent_autolink' => 'exclamation',
            'core:e/preview' => 'search',
            'core:e/print' => 'print',
            'core:e/question' => 'question',
            'core:e/redo' => 'repeat',
            'core:e/remove_link' => 'link-45deg',
            'core:e/remove_page_break' => 'remove',
            'core:e/resize' => 'expand',
            'core:e/restore_draft' => 'back',
            'core:e/restore_last_draft' => 'back',
            'core:e/right_to_left' => 'chevron-left',
            'core:e/row_props' => 'info',
            'core:e/save' => 'floppy',
            'core:e/screenreader_helper' => 'braille',
            'core:e/search' => 'search',
            'core:e/select_all' => 'arrows-h',
            'core:e/show_invisible_characters' => 'eye-slash',
            'core:e/source_code' => 'code',
            'core:e/special_character' => 'pencil-square',
            'core:e/spellcheck' => 'check',
            'core:e/split_cells' => 'layout-three-columns',
            'core:e/strikethrough' => 'strikethrough',
            'core:e/styleparagraph' => 'file-font',
            'core:e/subscript' => 'subscript',
            'core:e/superscript' => 'superscript',
            'core:e/table_props' => 'table',
            'core:e/table' => 'table',
            'core:e/template' => 'sticky-note',
            'core:e/text_color_picker' => 'brush',
            'core:e/text_color' => 'brush',
            'core:e/text_highlight_picker' => 'lightbulb',
            'core:e/text_highlight' => 'lightbulb',
            'core:e/tick' => 'check',
            'core:e/toggle_blockquote' => 'quote-left',
            'core:e/underline' => 'underline',
            'core:e/undo' => 'back',
            'core:e/visual_aid' => 'universal-access',
            'core:e/visual_blocks' => 'audio-description',
            'theme:fp/add_file' => 'file',
            'theme:fp/alias' => 'share',
            'theme:fp/alias_sm' => 'share',
            'theme:fp/check' => 'check',
            'theme:fp/create_folder' => 'file', //missing
            'theme:fp/cross' => 'remove',
            'theme:fp/download_all' => 'download',
            'theme:fp/help' => 'question-circle',
            'theme:fp/link' => 'link',
            'theme:fp/link_sm' => 'link',
            'theme:fp/logout' => 'sign-out',
            'theme:fp/path_folder' => 'file', //missing
            'theme:fp/path_folder_rtl' => 'file', //missing
            'theme:fp/refresh' => 'refresh',
            'theme:fp/search' => 'search',
            'theme:fp/setting' => 'gear',
            'theme:fp/view_icon_active' => 'th',
            'theme:fp/view_list_active' => 'list',
            'theme:fp/view_tree_active' => 'file', //missing
            'core:i/addblock' => 'plus-square',
            'core:i/assignroles' => 'user-plus',
            'core:i/backup' => 'file-zip',
            'core:i/badge' => 'shield',
            'core:i/breadcrumbdivider' => 'angle-right',
            'core:i/bullhorn' => 'bullhorn',
            'core:i/calc' => 'calculator',
            'core:i/calendar' => 'calendar',
            'core:i/calendareventdescription' => 'align-left',
            'core:i/calendareventtime' => 'clock',
            'core:i/caution' => 'exclamation text-warning',
            'core:i/checked' => 'check',
            'core:i/checkedcircle' => 'check-circle',
            'core:i/checkpermissions' => 'unlock-alt',
            'core:i/cohort' => 'people',
            'core:i/competencies' => 'check-square',
            'core:i/completion_self' => 'person',
            'core:i/contentbank' => 'brush',
            'core:i/dashboard' => 'speedometer2',
            'core:i/lock' => 'lock',
            'core:i/categoryevent' => 'cubes',
            'core:i/course' => 'journal-richtext',
            'core:i/courseevent' => 'journal-richtext',
            'core:i/customfield' => 'hand-o-right',
            'core:i/db' => 'database',
            'core:i/delete' => 'trash',
            'core:i/down' => 'arrow-down',
            'core:i/dragdrop' => 'arrows',
            'core:i/duration' => 'clock',
            'core:i/emojicategoryactivities' => 'futbol',
            'core:i/emojicategoryanimalsnature' => 'leaf',
            'core:i/emojicategoryflags' => 'flag',
            'core:i/emojicategoryfooddrink' => 'cutlery',
            'core:i/emojicategoryobjects' => 'lightbulb',
            'core:i/emojicategorypeoplebody' => 'male',
            'core:i/emojicategoryrecent' => 'clock',
            'core:i/emojicategorysmileysemotion' => 'smile',
            'core:i/emojicategorysymbols' => 'heart',
            'core:i/emojicategorytravelplaces' => 'plane',
            'core:i/edit' => 'pencil',
            'core:i/email' => 'envelope',
            'core:i/empty' => 'fw',
            'core:i/enrolmentsuspended' => 'pause',
            'core:i/enrolusers' => 'user-plus',
            'core:i/expired' => 'exclamation text-warning',
            'core:i/export' => 'download',
            'core:i/files' => 'file',
            'core:i/filter' => 'filter',
            'core:i/flagged' => 'flag',
            'core:i/folder' => 'file', //missing
            'core:i/grade_correct' => 'check text-success',
            'core:i/grade_incorrect' => 'remove text-danger',
            'core:i/grade_partiallycorrect' => 'check-square',
            'core:i/grades' => 'table',
            'core:i/grading' => 'magic',
            'core:i/gradingnotifications' => 'bell',
            'core:i/groupevent' => 'group',
            'core:i/groupn' => 'person',
            'core:i/group' => 'people',
            'core:i/groups' => 'user-circle',
            'core:i/groupv' => 'user-circle',
            'core:i/home' => 'house',
            'core:i/hide' => 'eye',
            'core:i/hierarchylock' => 'lock',
            'core:i/import' => 'box-arrow-up',
            'core:i/incorrect' => 'exclamation',
            'core:i/info' => 'info',
            'core:i/invalid' => 'times text-danger',
            'core:i/item' => 'circle',
            'core:i/loading' => 'circle-o-notch fa-spin',
            'core:i/loading_small' => 'circle-o-notch fa-spin',
            'core:i/location' => 'map-marker',
            'core:i/lock' => 'lock',
            'core:i/log' => 'list-alt',
            'core:i/mahara_host' => 'id-badge',
            'core:i/manual_item' => 'square',
            'core:i/marked' => 'circle',
            'core:i/marker' => 'circle',
            'core:i/mean' => 'calculator',
            'core:i/menu' => 'three-dots-vertical',
            'core:i/menubars' => 'list bi-2x',
            'core:i/messagecontentaudio' => 'headphones',
            'core:i/messagecontentimage' => 'image',
            'core:i/messagecontentvideo' => 'film',
            'core:i/messagecontentmultimediageneral' => 'camera-video',
            'core:i/mnethost' => 'external-link',
            'core:i/moodle_host' => 'journal-richtext',
            'core:i/moremenu' => 'ellipsis-h',
            'core:i/move_2d' => 'arrows',
            'core:i/muted' => 'microphone-slash',
            'core:i/navigationitem' => 'fw',
            'core:i/ne_red_mark' => 'remove',
            'core:i/new' => 'bolt',
            'core:i/news' => 'newspaper',
            'core:i/next' => 'chevron-right',
            'core:i/nosubcat' => 'plus-square',
            'core:i/notifications' => 'bell',
            'core:i/open' => 'folder-open',
            'core:i/otherevent' => 'calendar',
            'core:i/outcomes' => 'tasks',
            'core:i/payment' => 'money',
            'core:i/permissionlock' => 'lock',
            'core:i/permissions' => 'pencil-square',
            'core:i/persona_sign_in_black' => 'male',
            'core:i/portfolio' => 'id-badge',
            'core:i/preview' => 'search',
            'core:i/previous' => 'chevron-left',
            'core:i/privatefiles' => 'file',
            'core:i/progressbar' => 'spinner fa-spin',
            'core:i/publish' => 'share',
            'core:i/questions' => 'question',
            'core:i/reload' => 'refresh',
            'core:i/report' => 'area-chart',
            'core:i/repository' => 'hdd',
            'core:i/restore' => 'box-arrow-up',
            'core:i/return' => 'arrow-left',
            'core:i/risk_config' => 'exclamation text-muted',
            'core:i/risk_managetrust' => 'exclamation-triangle text-warning',
            'core:i/risk_personal' => 'exclamation-circle text-info',
            'core:i/risk_spam' => 'exclamation text-primary',
            'core:i/risk_xss' => 'exclamation-triangle text-danger',
            'core:i/role' => 'user-md',
            'core:i/rss' => 'rss',
            'core:i/rsssitelogo' => 'journal-richtext',
            'core:i/scales' => 'balance-scale',
            'core:i/scheduled' => 'calendar-check',
            'core:i/search' => 'search',
            'core:i/section' => 'file', //missing
            'core:i/sendmessage' => 'paper-plane',
            'core:i/settings' => 'gear',
            'core:i/show' => 'eye-slash',
            'core:i/siteevent' => 'globe',
            'core:i/star' => 'star',
            'core:i/star-o' => 'star',
            'core:i/star-rating' => 'star',
            'core:i/stats' => 'line-chart',
            'core:i/switch' => 'exchange',
            'core:i/switchrole' => 'person-bounding-box',
            'core:i/trash' => 'trash',
            'core:i/twoway' => 'arrows-h',
            'core:i/unchecked' => 'square',
            'core:i/uncheckedcircle' => 'circle',
            'core:i/unflagged' => 'flag',
            'core:i/unlock' => 'unlock',
            'core:i/up' => 'arrow-up',
            'core:i/upload' => 'upload',
            'core:i/userevent' => 'person',
            'core:i/user' => 'person',
            'core:i/users' => 'people',
            'core:i/valid' => 'check text-success',
            'core:i/warning' => 'exclamation text-warning',
            'core:i/window_close' => 'window-close',
            'core:i/withsubcat' => 'plus-square',
            'core:m/USD' => 'usd',
            'core:t/addcontact' => 'address-card',
            'core:t/add' => 'plus',
            'core:t/approve' => 'thumbs-up',
            'core:t/assignroles' => 'user-circle',
            'core:t/award' => 'trophy',
            'core:t/backpack' => 'shopping-bag',
            'core:t/backup' => 'arrow-circle-down',
            'core:t/block' => 'ban',
            'core:t/block_to_dock_rtl' => 'chevron-right',
            'core:t/block_to_dock' => 'chevron-left',
            'core:t/calc_off' => 'calculator', // TODO: Change to better icon once we have stacked icon support or more icons.
            'core:t/calc' => 'calculator',
            'core:t/check' => 'check',
            'core:t/cohort' => 'people',
            'core:t/collapsed_empty_rtl' => 'caret-square-o-left',
            'core:t/collapsed_empty' => 'caret-square-o-right',
            'core:t/collapsed_rtl' => 'caret-left',
            'core:t/collapsed' => 'caret-right',
            'core:t/collapsedcaret' => 'caret-right',
            'core:t/contextmenu' => 'gear',
            'core:t/copy' => 'copy',
            'core:t/delete' => 'trash',
            'core:t/dockclose' => 'window-close',
            'core:t/dock_to_block_rtl' => 'chevron-right',
            'core:t/dock_to_block' => 'chevron-left',
            'core:t/download' => 'download',
            'core:t/down' => 'arrow-down',
            'core:t/downlong' => 'long-arrow-down',
            'core:t/dropdown' => 'gear',
            'core:t/editinline' => 'pencil',
            'core:t/edit_menu' => 'gear',
            'core:t/editstring' => 'pencil',
            'core:t/edit' => 'gear',
            'core:t/emailno' => 'ban',
            'core:t/email' => 'envelope',
            'core:t/emptystar' => 'star',
            'core:t/enrolusers' => 'user-plus',
            'core:t/expanded' => 'caret-down',
            'core:t/go' => 'play',
            'core:t/grades' => 'table',
            'core:t/groupn' => 'person',
            'core:t/groups' => 'user-circle',
            'core:t/groupv' => 'user-circle',
            'core:t/hide' => 'eye',
            'core:t/left' => 'arrow-left',
            'core:t/less' => 'caret-up',
            'core:t/locked' => 'lock',
            'core:t/lock' => 'unlock',
            'core:t/locktime' => 'lock',
            'core:t/markasread' => 'check',
            'core:t/messages' => 'chat',
            'core:t/message' => 'chat',
            'core:t/more' => 'caret-down',
            'core:t/move' => 'arrows-v',
            'core:t/online' => 'circle',
            'core:t/passwordunmask-edit' => 'pencil',
            'core:t/passwordunmask-reveal' => 'eye',
            'core:t/portfolioadd' => 'plus',
            'core:t/preferences' => 'wrench',
            'core:t/preview' => 'search',
            'core:t/print' => 'print',
            'core:t/removecontact' => 'user-times',
            'core:t/reload' => 'refresh',
            'core:t/reset' => 'repeat',
            'core:t/restore' => 'arrow-circle-up',
            'core:t/right' => 'arrow-right',
            'core:t/sendmessage' => 'paper-plane',
            'core:t/show' => 'eye-slash',
            'core:t/sort_by' => 'sort-amount-asc',
            'core:t/sort_asc' => 'sort-asc',
            'core:t/sort_desc' => 'sort-desc',
            'core:t/sort' => 'sort',
            'core:t/stop' => 'stop',
            'core:t/switch_minus' => 'dash',
            'core:t/switch_plus' => 'plus',
            'core:t/switch_whole' => 'square',
            'core:t/tags' => 'tags',
            'core:t/unblock' => 'commenting',
            'core:t/unlocked' => 'unlock-alt',
            'core:t/unlock' => 'lock',
            'core:t/up' => 'arrow-up',
            'core:t/uplong' => 'long-arrow-up',
            'core:t/user' => 'person',
            'core:t/viewdetails' => 'list',
        ];
    }

    /**
     * Overridable function to get a mapping of all icons.
     * Default is to do no mapping.
     */
    public function get_icon_name_map() {
        if ($this->map === []) {
            $cache = \cache::make('core', 'bootstrapiconmapping');

            // Create different mapping keys for different icon system classes, there may be several different
            // themes on the same site.
            $mapkey = 'mapping_'.preg_replace('/[^a-zA-Z0-9_]/', '_', get_class($this));
            $this->map = $cache->get($mapkey);

            if (empty($this->map)) {
                $this->map = $this->get_core_icon_map();
                $callback = 'get_bootstrap_icon_map';

                if ($pluginsfunction = get_plugins_with_function($callback)) {
                    foreach ($pluginsfunction as $plugintype => $plugins) {
                        foreach ($plugins as $pluginfunction) {
                            $pluginmap = $pluginfunction();
                            $this->map += $pluginmap;
                        }
                    }
                }
                $cache->set($mapkey, $this->map);
            }

        }
        return $this->map;
    }


    public function get_amd_name() {
        return 'core/icon_system_bootstrap';
    }

    public function render_pix_icon(renderer_base $output, pix_icon $icon) {
        $subtype = 'pix_icon_bootstrap';
        $subpix = new $subtype($icon);

        $data = $subpix->export_for_template($output);

        if (!$subpix->is_mapped()) {
            $data['unmappedIcon'] = $icon->export_for_template($output);
        }
        if (isset($icon->attributes['aria-hidden'])) {
            $data['aria-hidden'] = $icon->attributes['aria-hidden'];
        }
        return $output->render_from_template('core/pix_icon_bootstrap', $data);
    }

}
