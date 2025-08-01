<?php
/**
 * 404 error page for the Dashboard plugin
 *
 * @package    local_cuadrodemando
 * @author     Thorvaldur Konradsson
 * @version    1.0.0
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

echo html_writer::start_div('alert alert-warning text-center');
echo html_writer::tag('h3', get_string('pagenotfound', 'local_cuadrodemando'), array('class' => 'mb-3'));
echo html_writer::tag('p', get_string('pagenotfound_desc', 'local_cuadrodemando'));
echo html_writer::tag('a', get_string('returntodashboard', 'local_cuadrodemando'), array(
    'href' => new moodle_url('/local/cuadrodemando/index.php'),
    'class' => 'btn btn-primary mt-3'
));
echo html_writer::end_div();
