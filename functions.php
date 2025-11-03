<?php

if (!defined('TEAMTH_VERSION')) {
    define('TEAMTH_VERSION', '1.0.0');
}

/**
 * Basic
 */
add_action('after_setup_theme', function () {

    load_theme_textdomain('teamth', get_template_directory() . '/languages');

    add_theme_support('post-thumbnails');

    add_theme_support('html5', ['search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script']);
});

/**
 Tailwind
 */
add_action('wp_enqueue_scripts', function () {
    wp_enqueue_script(
        'teamth-tailwind',
        'https://cdn.tailwindcss.com',
        [],
        null,
        false
    );

    // Konfigurace: světlý UI + výrazná žlutá (brand)
    wp_add_inline_script(
        'teamth-tailwind',
        'window.tailwind = window.tailwind || {};
         window.tailwind.config = {
           darkMode: false,
           theme: {
             extend: {
               colors: {
                 brand: {
                   DEFAULT: "#FACC15",   // žlutá 400
                   dark: "#EAB308"       // žlutá 500
                 }
               },
               boxShadow: {
                 card: "0 8px 24px rgba(2, 6, 23, 0.06)"
               },
               borderRadius: {
                 xl: "0.75rem",
                 "2xl": "1rem"
               }
             }
           }
         };',
        'before'
    );

    wp_enqueue_style('teamth-style', get_stylesheet_uri(), [], TEAMTH_VERSION);
});


/**
 * CPT
 */
add_action('init', function () {
    $labels = [
        'name'               => __('Team Members', 'teamth'),
        'singular_name'      => __('Team Member', 'teamth'),
        'add_new'            => __('Add New', 'teamth'),
        'add_new_item'       => __('Add New Team Member', 'teamth'),
        'edit_item'          => __('Edit Team Member', 'teamth'),
        'new_item'           => __('New Team Member', 'teamth'),
        'view_item'          => __('View Team Member', 'teamth'),
        'search_items'       => __('Search Team Members', 'teamth'),
        'not_found'          => __('No team members found', 'teamth'),
        'not_found_in_trash' => __('No team members found in Trash', 'teamth'),
        'all_items'          => __('Team Members', 'teamth'),
        'menu_name'          => __('Team Members', 'teamth'),
    ];

    register_post_type('team_member', [
        'labels'             => $labels,
        'public'             => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'menu_position'      => 20,
        'menu_icon'          => 'dashicons-groups',
        'supports'           => ['title', 'thumbnail'],
        'has_archive'  => false,
        'rewrite'      => ['slug' => 'team', 'with_front' => false],
        'show_in_rest'       => true,
    ]);
});

/**
 * ACF Local JSON
 */
add_filter('acf/settings/save_json', function ($path) {
    return get_template_directory() . '/acf-json';
});
add_filter('acf/settings/load_json', function ($paths) {
    $paths[] = get_template_directory() . '/acf-json';
    return $paths;
});

/**
 * Registrace ACF bloků
 */
add_action('acf/init', function () {
    if (!function_exists('acf_register_block_type')) {
        return;
    }

    acf_register_block_type([
        'name'            => 'team-member-detail',
        'title'           => __('Team Member Detail', 'teamth'),
        'description'     => __('Display a single team member profile.', 'teamth'),
        'category'        => 'widgets',
        'icon'            => 'id-alt',
        'supports'        => ['align' => false, 'jsx' => false],
        'render_template' => get_template_directory() . '/template-parts/blocks/team-member-detail.php',
        'enqueue_assets'  => function () {
        },
        'mode'            => 'preview',
        'keywords'        => ['team', 'member', 'profile', 'detail'],
    ]);

    acf_register_block_type([
        'name'            => 'team-member-grid',
        'title'           => __('Team Member Grid', 'teamth'),
        'description'     => __('Display multiple team members in a grid.', 'teamth'),
        'category'        => 'widgets',
        'icon'            => 'screenoptions',
        'supports'        => ['align' => false, 'jsx' => false],
        'render_template' => get_template_directory() . '/template-parts/blocks/team-member-grid.php',
        'enqueue_assets'  => function () {
        },
        'mode'            => 'preview',
        'keywords'        => ['team', 'members', 'grid'],
    ]);
});


add_action('after_setup_theme', function () {
    $inc = get_template_directory() . '/inc';
    if (is_dir($inc)) {
        $helpers = $inc . '/helpers.php';
        if (file_exists($helpers)) {
            require_once $helpers;
        }
    }
});
