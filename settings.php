<?php
/**
 * Plugin settings for the Dashboard plugin
 *
 * @package    local_cuadrodemando
 * @author     Thorvaldur Konradsson
 * @version    1.0.0
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

if ($hassiteconfig) {
    $settings = new admin_settingpage('local_cuadrodemando', get_string('pluginname', 'local_cuadrodemando'));

    // Add setting for enabling charts
    $settings->add(new admin_setting_configcheckbox(
        'local_cuadrodemando/enablecharts',
        get_string('enablecharts', 'local_cuadrodemando'),
        get_string('enablecharts_desc', 'local_cuadrodemando'),
        1
    ));

    // Add setting for refresh interval
    $settings->add(new admin_setting_configtext(
        'local_cuadrodemando/refreshinterval',
        get_string('refreshinterval', 'local_cuadrodemando'),
        get_string('refreshinterval_desc', 'local_cuadrodemando'),
        '5',
        PARAM_INT
    ));

    $ADMIN->add('localplugins', $settings);
}
