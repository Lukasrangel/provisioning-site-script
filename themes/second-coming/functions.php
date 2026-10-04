<?php
/**
 * Second Coming — Matrix-style Full Site Editing theme.
 *
 * @package Second_Coming
 */

function second_coming_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('wp-block-styles');
    add_theme_support('align-wide');
    add_theme_support('editor-styles');
    add_editor_style('style.css');
}
add_action('after_setup_theme', 'second_coming_setup');

function second_coming_enqueue_assets() {
    $version = wp_get_theme()->get('Version');

    wp_enqueue_style('second-coming-style', get_stylesheet_uri(), array(), $version);
    wp_enqueue_script('secondcoming-nav', get_stylesheet_directory_uri() . '/js/nav.js', array('jquery'), $version, true);
    wp_enqueue_script('secondcoming-matrix-rain', get_stylesheet_directory_uri() . '/js/matrix-rain.js', array(), $version, true);
    wp_enqueue_script('second-coming-pill-toggle', get_stylesheet_directory_uri() . '/js/pill-toggle.js', array(), $version, true);
    
    // Les chaînes source en ANGLAIS
    wp_localize_script('second-coming-pill-toggle', 'matrixL10n', array(
        'takeBlue' => __('TAKE THE BLUE PILL', 'second-coming'),
        'takeRed'  => __('TAKE THE RED PILL', 'second-coming'),
        'speed'    => __('SPEED FLUX', 'second-coming'),
        'style'    => __('CHARACTER SET', 'second-coming'),
        'reset'    => __('FACTORY RESET', 'second-coming'),
        'reboot'   => __('Reboot system?', 'second-coming')
    ));
}
add_action('wp_enqueue_scripts', 'second_coming_enqueue_assets', 999);

function second_coming_register_menus() {
    register_nav_menus(array(
        'primary-menu' => __('Primary Menu', 'second-coming'),
        'footer-menu'  => __('Footer Menu', 'second-coming')
    ));
}
add_action('init', 'second_coming_register_menus');

// Filtre pour le Copyright automatique via la classe CSS
add_filter('render_block', function($block_content, $parsed_block) {
    if (!empty($parsed_block['attrs']['className']) && strpos($parsed_block['attrs']['className'], 'footer__copyright') !== false) {
        return '<p class="footer__copyright">© ' . date('Y') . ' ' . get_bloginfo('name') . '</p>';
    }
    return $block_content;
}, 10, 2);

require get_theme_file_path('inc/patterns.php');
require get_theme_file_path('inc/abilities.php');
require get_theme_file_path('inc/ai.php');
