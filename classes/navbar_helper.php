<?php
/**
 * Navbar helper class for cuadrodemando dashboard
 *
 * @package    local_cuadrodemando
 * @author     Thorvaldur Konradsson
 * @version    1.0.0
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_cuadrodemando;

defined('MOODLE_INTERNAL') || die();

class navbar_helper {

    /**
     * Generate the dashboard navbar with language selector
     * 
     * @param string $active_page Current active page (home, courses, users, geo)
     * @param string $custom_title Optional custom title to override the default page title
     * @return string HTML for the navbar
     */
    public static function render_navbar($active_page = 'home', $custom_title = null) {
        global $CFG;
        
        $html = '';
        
        // Start navbar wrapper
        $html .= \html_writer::start_div('dashboard-nav mb-4');
        $html .= \html_writer::start_tag('nav', ['class' => 'navbar navbar-expand-lg navbar-light bg-light']);
        $html .= \html_writer::start_div('container-fluid');

        // Dashboard header/brand
        if ($custom_title !== null) {
            // Use custom title if provided
            $display_title = $custom_title;
        } else {
            // Use default page title
            $page_name = get_string($active_page, 'local_cuadrodemando');
            $display_title = get_string('pagetitle', 'local_cuadrodemando', $page_name);
        }
        
        $html .= \html_writer::tag('span', 
            $display_title, 
            ['class' => 'navbar-brand h3 mb-0']
        );

        // Navigation links
        $html .= \html_writer::start_div('navbar-nav me-auto');
        
        // Define navigation items using index.php?page=...
        $nav_items = [
            'home' => [
                'url' => new \moodle_url('/local/cuadrodemando/index.php'),
                'text' => get_string('home', 'local_cuadrodemando')
            ],
            'courses' => [
                'url' => new \moodle_url('/local/cuadrodemando/index.php', ['page' => 'courses']),
                'text' => get_string('courses', 'local_cuadrodemando')
            ],
            'users' => [
                'url' => new \moodle_url('/local/cuadrodemando/index.php', ['page' => 'users']),
                'text' => get_string('users', 'local_cuadrodemando')
            ],
            'geo' => [
                'url' => new \moodle_url('/local/cuadrodemando/index.php', ['page' => 'geo']),
                'text' => get_string('geo', 'local_cuadrodemando')
            ]
        ];

        foreach ($nav_items as $key => $item) {
            $classes = ['nav-link'];
            if ($key === $active_page) {
                $classes[] = 'active';
            }
            
            $html .= \html_writer::start_div('nav-item');
            $html .= \html_writer::link(
                $item['url'],
                $item['text'],
                ['class' => implode(' ', $classes)]
            );
            $html .= \html_writer::end_div();
        }
        
        $html .= \html_writer::end_div(); // navbar-nav

        // Language selector
        $html .= self::render_language_selector();

        $html .= \html_writer::end_div(); // container-fluid
        $html .= \html_writer::end_tag('nav');
        $html .= \html_writer::end_div(); // dashboard-nav

        return $html;
    }

    /**
     * Generate the language selector dropdown
     * 
     * @return string HTML for language selector
     */
    private static function render_language_selector() {
        $html = '';
        
        $html .= \html_writer::start_div('d-flex align-items-center');
        $html .= \html_writer::tag('label', 
            get_string('language_selector', 'local_cuadrodemando'), 
            ['for' => 'language-select', 'class' => 'form-label me-2 mb-0']
        );

        $languages = [
            'en' => get_string('lang_english', 'local_cuadrodemando'),
            'es' => get_string('lang_spanish', 'local_cuadrodemando'),
            'is' => get_string('lang_icelandic', 'local_cuadrodemando'),
            'ca' => get_string('lang_catalan', 'local_cuadrodemando')
        ];

        $current_lang = current_language();
        if (!$current_lang) {
            $current_lang = ''; // No language selected, show "Select language"
        }

        $select_options = \html_writer::tag(
            'option',
            get_string('selectlanguage', 'local_cuadrodemando'),
            ['value' => '', 'disabled' => 'disabled', 'selected' => ($current_lang === '')]
        );

        foreach ($languages as $lang_code => $lang_name) {
            $selected = ($lang_code === $current_lang) ? 'selected' : '';
            $select_options .= \html_writer::tag('option', $lang_name, [
                'value' => $lang_code, 
                'selected' => $selected
            ]);
        }

        $html .= \html_writer::tag('select', $select_options, [
            'id' => 'language-select',
            'class' => 'form-select',
            'onchange' => 'changeDashboardLanguage(this.value)'
        ]);
        
        $html .= \html_writer::end_div(); // d-flex

        return $html;
    }

    /**
     * Generate the breadcrumb navigation
     * 
     * @param string $current_page Current active page (home, courses, users, geo)
     * @param array $additional_items Additional breadcrumb items
     * @return string HTML for the breadcrumbs
     */
    public static function render_breadcrumbs($current_page = 'home', $additional_items = []) {
        global $CFG;
        
        $html = '';
        $html .= \html_writer::start_tag('ol', ['class' => 'breadcrumb float-sm-right']);
        
        // Home breadcrumb (always present unless we're on home)
        if ($current_page !== 'home') {
            $html .= \html_writer::start_tag('li', ['class' => 'breadcrumb-item']);
            $html .= \html_writer::link(
                $CFG->wwwroot . '/local/cuadrodemando/', 
                get_string('home', 'local_cuadrodemando')
            );
            $html .= \html_writer::end_tag('li');
        }
        
        // Additional breadcrumb items
        foreach ($additional_items as $item) {
            $html .= \html_writer::start_tag('li', ['class' => 'breadcrumb-item']);
            $html .= \html_writer::link($item['url'], $item['text']);
            $html .= \html_writer::end_tag('li');
        }
        
        // Current page (active)
        $html .= \html_writer::start_tag('li', ['class' => 'breadcrumb-item active']);
        $html .= get_string($current_page, 'local_cuadrodemando');
        $html .= \html_writer::end_tag('li');
        
        $html .= \html_writer::end_tag('ol');
        
        return $html;
    }
}