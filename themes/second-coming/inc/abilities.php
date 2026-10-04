<?php
/**
 * Abilities: list patterns, create a page from those patterns.
 *
 * No-ops on WordPress without the Abilities API.
 *
 * @package Second_Coming
 */

defined('ABSPATH') || exit;

add_action('wp_abilities_api_categories_init', 'second_coming_register_ability_category');
add_action('wp_abilities_api_init', 'second_coming_register_abilities');

/**
 * Register the ability category.
 */
function second_coming_register_ability_category() {
    if (!function_exists('wp_register_ability_category')) {
        return;
    }

    wp_register_ability_category(
        'second-coming',
        array(
            'label'       => __('Second Coming pages', 'second-coming'),
            'description' => __('Assemble WordPress pages from Second Coming block patterns.', 'second-coming'),
        )
    );
}

/**
 * Register list + create abilities.
 */
function second_coming_register_abilities() {
    if (!function_exists('wp_register_ability')) {
        return;
    }

    wp_register_ability(
        'second-coming/list-patterns',
        array(
            'label'               => __('List Second Coming patterns', 'second-coming'),
            'description'         => __('Return the live catalog of Second Coming section patterns (slug, title, description). Call this before create-page so you only use real slugs.', 'second-coming'),
            'category'            => 'second-coming',
            'output_schema'       => array(
                'type'       => 'object',
                'properties' => array(
                    'patterns' => array(
                        'type'  => 'array',
                        'items' => array(
                            'type'       => 'object',
                            'properties' => array(
                                'slug'        => array('type' => 'string'),
                                'title'       => array('type' => 'string'),
                                'description' => array('type' => 'string'),
                            ),
                        ),
                    ),
                ),
            ),
            'execute_callback'    => 'second_coming_ability_list_patterns',
            'permission_callback' => 'second_coming_ability_permission',
            'meta'                => array(
                'show_in_rest' => true,
                'mcp'          => array(
                    'public' => true,
                ),
                'annotations'  => array(
                    'readonly'    => true,
                    'destructive' => false,
                    'idempotent'  => true,
                ),
            ),
        )
    );

    wp_register_ability(
        'second-coming/create-page',
        array(
            'label'               => __('Create page from Second Coming patterns', 'second-coming'),
            'description'         => __('Create a WordPress page (draft by default) by concatenating allowlisted Second Coming pattern slugs in order. Example: title=Confidential data, patterns=[second-coming/hero-terminal, second-coming/classified-grid, second-coming/cta-jack-in]. Optional copy object fills {{sc_headline}} and other tokens. Unknown slugs are dropped. Does not invent HTML.', 'second-coming'),
            'category'            => 'second-coming',
            'input_schema'        => array(
                'type'       => 'object',
                'properties' => array(
                    'title'    => array(
                        'type'        => 'string',
                        'description' => __('Page title (required).', 'second-coming'),
                    ),
                    'slug'     => array(
                        'type'        => 'string',
                        'description' => __('Optional post_name.', 'second-coming'),
                    ),
                    'patterns' => array(
                        'type'        => 'array',
                        'items'       => array(
                            'type' => 'string',
                        ),
                        'description' => __('Ordered pattern slugs. Prefix second-coming/ optional. 3–7 recommended. Always prefer hero-terminal first and cta-jack-in last when they fit.', 'second-coming'),
                    ),
                    'copy'     => array(
                        'type'        => 'object',
                        'description' => __('Optional token map: sc_headline, sc_lede, sc_card1_title, … See theme token defaults.', 'second-coming'),
                    ),
                    'status'   => array(
                        'type'        => 'string',
                        'enum'        => array('draft', 'publish'),
                        'default'     => 'draft',
                        'description' => __('draft (default) or publish (requires publish_pages).', 'second-coming'),
                    ),
                ),
                'required'   => array('title', 'patterns'),
            ),
            'output_schema'       => array(
                'type'       => 'object',
                'properties' => array(
                    'id'       => array('type' => 'integer'),
                    'title'    => array('type' => 'string'),
                    'status'   => array('type' => 'string'),
                    'edit_url' => array('type' => 'string'),
                    'patterns' => array(
                        'type'  => 'array',
                        'items' => array('type' => 'string'),
                    ),
                    'message'  => array('type' => 'string'),
                ),
            ),
            'execute_callback'    => 'second_coming_ability_create_page',
            'permission_callback' => 'second_coming_ability_permission',
            'meta'                => array(
                'show_in_rest' => true,
                'mcp'          => array(
                    'public' => true,
                ),
                'annotations'  => array(
                    'readonly'    => false,
                    'destructive' => false,
                    'idempotent'  => false,
                    'instructions' => __('Assemble from existing Second Coming patterns only. Do not output raw HTML. Prefer 3–7 sections, hero first, CTA last.', 'second-coming'),
                ),
            ),
        )
    );
}

/**
 * Editors and above.
 *
 * @return bool
 */
function second_coming_ability_permission() {
    return current_user_can('edit_pages');
}

/**
 * Execute: catalog.
 *
 * @param mixed $input Unused.
 * @return array
 */
function second_coming_ability_list_patterns($input = null) {
    unset($input);
    return array(
        'patterns' => second_coming_get_pattern_catalog(),
    );
}

/**
 * Persist a page from pattern slugs + tokens. Shared with the Generate field.
 *
 * @param array $input Ability-shaped input.
 * @return array|WP_Error
 */
function second_coming_ability_create_page($input) {
    $input = is_array($input) ? $input : array();

    $title = isset($input['title']) ? sanitize_text_field((string) $input['title']) : '';
    if ($title === '') {
        return new WP_Error('invalid_title', __('A page title is required.', 'second-coming'));
    }

    $slugs   = isset($input['patterns']) ? $input['patterns'] : array();
    $copy    = isset($input['copy']) && is_array($input['copy']) ? $input['copy'] : array();
    $content = second_coming_assemble_page_content($slugs, $copy);
    if (is_wp_error($content)) {
        return $content;
    }

    $status = isset($input['status']) ? sanitize_key((string) $input['status']) : 'draft';
    if ($status === 'publish' && !current_user_can('publish_pages')) {
        $status = 'draft';
    }
    if ($status !== 'publish') {
        $status = 'draft';
    }

    $postarr = array(
        'post_type'    => 'page',
        'post_status'  => $status,
        'post_title'   => $title,
        'post_content' => $content,
    );

    if (!empty($input['slug'])) {
        $postarr['post_name'] = sanitize_title((string) $input['slug']);
    }

    $post_id = wp_insert_post($postarr, true);
    if (is_wp_error($post_id)) {
        return $post_id;
    }

    $used = second_coming_allowlist_pattern_slugs($slugs);

    return array(
        'id'       => (int) $post_id,
        'title'    => $title,
        'status'   => $status,
        'edit_url' => get_edit_post_link($post_id, 'raw'),
        'patterns' => $used,
        'message'  => $status === 'publish'
            ? sprintf(
                /* translators: %s: page title */
                __('Page published: %s', 'second-coming'),
                $title
            )
            : sprintf(
                /* translators: %s: page title */
                __('Draft page created: %s', 'second-coming'),
                $title
            ),
    );
}
