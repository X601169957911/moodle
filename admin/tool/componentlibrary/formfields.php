<?php

require_once('../../../config.php');
require_once ($CFG->dirroot.'/lib/formslib.php');

class testform extends moodleform {
    public function definition() {
        $mform = $this->_form;

        $r = optional_param('r', false, PARAM_BOOL);
        $h = optional_param('h', false, PARAM_BOOL);
        $m = optional_param('m', false, PARAM_BOOL);

        // Text
        $mform->addElement('text', 'textelement', 'Text');
        $mform->setType('textelement', 'text');
        if ($r) { $mform->addRule('textelement', 'This element is required', 'required', null, 'server'); }
        if ($h && !$m) { $mform->addHelpButton('textelement', 'summary'); }
        $mform->setAdvanced('textelement', true);

        // Text two
        $mform->addElement('text', 'textelementtwo', 'Text element with a long label that can span multiple lines. The next field has no label. ');
        $mform->setType('textelementtwo', 'text');
        if ($r) { $mform->addRule('textelementtwo', 'This element is required', 'required', null, 'server'); }
        if ($h) { $mform->addHelpButton('textelementtwo', 'summary'); }
        $mform->setAdvanced('textelementtwo', true);

        // Text three
        $mform->addElement('text', 'textelementhree', '', '');
        $mform->setType('textelementhree', 'text');
        if ($r && !$m) { $mform->addRule('textelementhree', 'This element is required', 'required', null, 'server'); }
        if ($h && !$m) { $mform->addHelpButton('textelementhree', 'summary'); }
        $mform->setAdvanced('textelementhree', true);

        // Button
        $mform->addElement('button', 'buttonelement', 'Button');
        if ($r) { $mform->addRule('buttonelement', 'This element is required', 'required', null, 'server'); }
        if ($h) { $mform->addHelpButton('buttonelement', 'summary'); }
        $mform->setAdvanced('buttonelement', true);

        // Date
        $mform->addElement('date_selector', 'date', 'Date selector');
        if ($r && !$m) { $mform->addRule('date', 'This element is required', 'required', null, 'server'); }
        if ($h) { $mform->addHelpButton('date', 'summary'); }
        $mform->setAdvanced('date', true);

        // Date time
        $mform->addElement('date_time_selector', 'datetimesel', 'Date time selector');
        if ($r) { $mform->addRule('datetimesel', 'This element is required', 'required', null, 'server'); }
        if ($h && !$m) { $mform->addHelpButton('datetimesel', 'summary'); }
        $mform->setAdvanced('datetimesel', true);

        // Duration
        $mform->addElement('duration', 'timelimit', 'Duration');
        if ($r) { $mform->addRule('timelimit', 'This element is required', 'required', null, 'server'); }
        if ($h) { $mform->addHelpButton('timelimit', 'summary'); }
        $mform->setAdvanced('timelimit', true);

        // Editor
        $mform->addElement('editor', 'editor', 'Editor');
        $mform->setType('editor', PARAM_RAW);
        if ($r) { $mform->addRule('editor', 'This element is required', 'required', null, 'server'); }
        if ($h && !$m) { $mform->addHelpButton('editor', 'summary'); }
        $mform->setAdvanced('editor', true);

        // File
        $mform->addElement('file', 'attachment', 'File');

        // Filepicker
        $mform->addElement('filepicker', 'userfile', 'Filepicker', null, array('maxbytes' => 100, 'accepted_types' => '*'));
        if ($r) { $mform->addRule('userfile', 'This element is required', 'required', null, 'server'); }
        if ($h) { $mform->addHelpButton('userfile', 'summary'); }
        $mform->setAdvanced('userfile', true);

        // HTML
        $mform->addElement('html', '<div class="text-success h2 ">The HTML only formfield</div>');

        // Mod grade
        // $mform->addElement('modgrade', 'modgrade', 'Mod grade', false);

        // Passwords
        $mform->addElement('passwordunmask', 'passwordunmask', 'Passwordunmask');
        if ($r && !$m) { $mform->addRule('passwordunmask', 'This element is required', 'required', null, 'server'); }
        if ($h && !$m) { $mform->addHelpButton('passwordunmask', 'summary'); }
        $mform->setAdvanced('passwordunmask', true);

        // Radio
        $mform->addElement('radio', 'radio', 'Radio', 'Radio label', 'choice_value');
        if ($r) { $mform->addRule('radio', 'This element is required', 'required', null, 'server'); }
        if ($h && !$m) { $mform->addHelpButton('radio', 'summary'); }
        $mform->setAdvanced('radio', true);

        // Checkbox
        $mform->addElement('checkbox', 'checkbox', 'Checkbox', 'Checkbox Text');
        if ($r) { $mform->addRule('checkbox', 'This element is required', 'required', null, 'server'); }
        if ($h) { $mform->addHelpButton('checkbox', 'summary'); }
        $mform->setAdvanced('checkbox', true);

        // Select
        $mform->addElement('select', 'auth', 'Select', ['cow', 'crow', 'dog', 'cat']);
        if ($r && !$m) { $mform->addRule('auth', 'This element is required', 'required', null, 'server'); }
        if ($h) { $mform->addHelpButton('auth', 'summary'); }
        $mform->setAdvanced('auth', true);

        // Yes No
        $mform->addElement('selectyesno', 'selectyesno', 'Selectyesno');
        if ($r && !$m) { $mform->addRule('selectyesno', 'This element is required', 'required', null, 'server'); }
        if ($h) { $mform->addHelpButton('selectyesno', 'summary'); }
        $mform->setAdvanced('selectyesno', true);

        // Static
        $mform->addElement('static', 'static', 'Static', 'static description');

        // Float
        $mform->addElement('float', 'float', 'Floating number');
        if ($r) { $mform->addRule('float', 'This element is required', 'required', null, 'server'); }
        if ($h) { $mform->addHelpButton('float', 'summary'); }
        $mform->setAdvanced('float', true);

        // Textarea
        $mform->addElement('textarea', 'textarea', 'Text area', 'wrap="virtual" rows="20" cols="50"');
        if ($r && !$m) { $mform->addRule('textarea', 'This element is required', 'required', null, 'server'); }
        if ($h && !$m) { $mform->addHelpButton('textarea', 'summary'); }
        $mform->setAdvanced('textarea', true);

        // Recaptcha
        $mform->addElement('recaptcha', 'recaptcha', 'Recaptcha');
        if ($r) { $mform->addRule('recaptcha', 'This element is required', 'required', null, 'server'); }
        if ($h) { $mform->addHelpButton('recaptcha', 'summary'); }

        // Tags
        $mform->addElement('tags', 'tags', 'Tags', array('itemtype' => 'course_modules', 'component' => 'core'));
        if ($r && !$m) { $mform->addRule('tags', 'This element is required', 'required', null, 'server'); }
        if ($h && !$m) { $mform->addHelpButton('tags', 'summary'); }
        $mform->setAdvanced('tags', true);


        // Grading
        // $mform->addElement('grading', 'advancedgrading', get_string('grade').':', array('gradinginstance' => $gradinginstance));

        // Question categories
        // $mform->addElement('questioncategory', 'category', get_string('category', 'question'),
        //     array('contexts'=>$contexts, 'top'=>true, 'currentcat'=>$currentcat, 'nochildrenof'=>$currentcat));

        // Filetypes
        $mform->addElement('filetypes', 'filetypes', 'Allowedfiletypes', ['onlytypes' => ['document', 'image'], 'allowunknown' => true]);
        if ($r && !$m) { $mform->addRule('filetypes', 'This element is required', 'required', null, 'server'); }
        if ($h) { $mform->addHelpButton('filetypes', 'summary'); }
        $mform->setAdvanced('filetypes', true);

        // Advanced checkbox
        $mform->addElement('advcheckbox', 'advcheckbox', 'Advanced checkbox', 'Advanced checkbox name', array('group' => 1), array(0, 1));
        if ($r) { $mform->addRule('advcheckbox', 'This element is required', 'required', null, 'server'); }
        if ($h) { $mform->addHelpButton('advcheckbox', 'summary'); }
        $mform->setAdvanced('advcheckbox', true);

        // Autocomplete
        $searchareas = \core_search\manager::get_search_areas_list(true);
        $areanames = array();
        foreach ($searchareas as $areaid => $searcharea) {
            $areanames[$areaid] = $searcharea->get_visible_name();
        }
        $options = array(
            'multiple' => true,
            'noselectionstring' => get_string('allareas', 'search'),
        );
        $mform->addElement('autocomplete', 'autocomplete', get_string('searcharea', 'search'), $areanames, $options);
        if ($r) { $mform->addRule('autocomplete', 'This element is required', 'required', null, 'server'); }
        if ($h && !$m) { $mform->addHelpButton('autocomplete', 'summary'); }
        $mform->setAdvanced('autocomplete', true);

        // Group
        $radiogrp = [
            $mform->createElement('text', 'rtext', 'Text'),
            $mform->createElement('radio', 'rradio', 'Radio label', 'After one ', 1),
            $mform->createElement('checkbox', 'rchecbox', 'Checkbox label', 'After two ', 2)
        ];
        $mform->setType('rtext', PARAM_RAW);
        $mform->addGroup($radiogrp, 'group', 'Group', ' ', false);
        if ($r) { $mform->addRule('group', 'This element is required', 'required', null, 'server'); }
        if ($h) { $mform->addHelpButton('group', 'summary'); }
        $mform->setAdvanced('group', true);

        $group = $mform->getElement('group');

        // Group of groups.
        $group = [];
        $group[] = $mform->createElement('select', 'profilefield', '', [0 => 'Username', 1 => 'Email']);
        $elements = [];
        $elements[] = $mform->createElement('select', 'operator', null, [0 => 'equal', 1 => 'not equal']);
        $elements[] = $mform->createElement('text', 'value', null);
        $elements[] = $mform->createElement('static', 'desc', 'Just a static text', 'Just a static text');
        $mform->setType('value', PARAM_RAW);
        $group[] = $mform->createElement('group', 'fieldvalue', '', $elements, '', false);
        $mform->addGroup($group, 'fieldsgroup', 'Group containing another group', '', false);
        if ($r) { $mform->addRule('fieldsgroup', 'This element is required', 'required', null, 'server'); }
        if ($h) { $mform->addHelpButton('fieldsgroup', 'summary'); }

        $this->add_action_buttons();
    }

}

$repeatcount = optional_param('test_repeat', 1, PARAM_INT);

$PAGE->set_pagelayout('embedded');
$url = new moodle_url('/admin/tool/componentlibrary/formfields.php');
$help = new moodle_url('/admin/tool/componentlibrary/formfields.php', ['h' => 1]);
$required = new moodle_url('/admin/tool/componentlibrary/formfields.php', ['r' => 1]);
$both = new moodle_url('/admin/tool/componentlibrary/formfields.php', ['h' => 1, 'r' => 1]);
$mixed = new moodle_url('/admin/tool/componentlibrary/formfields.php', ['h' => 1, 'r' => 1, 'm' => 1]);
$PAGE->set_url($url);
$PAGE->set_context(context_system::instance());

$PAGE->set_heading('Moodle form fields');
$PAGE->set_title('Moodle form fields');

$form = new testform($url, array('repeatcount' => $repeatcount));

echo $OUTPUT->header();

echo "<div class='alert alert-warning'>
        Toggle the view of this forum instance<br>
        <a href=" . $url->out() . ">Show default</a> /
        <a href=" . $help->out() . ">Show with help</a> /
        <a href=" . $required->out() . ">Show with required</a> /
        <a href=" . $both->out() . ">Show with both</a> /
        <a href=" . $mixed->out() . ">Show mixed</a>
     </div>";

if ($data = $form->get_data()) {
    echo "<pre>" . print_r($data, true) . "</pre>";
}
$form->display();
echo $OUTPUT->footer();
