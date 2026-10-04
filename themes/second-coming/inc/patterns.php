<?php
/**
 * Pattern catalog and token defaults for Second Coming.
 *
 * @package Second_Coming
 */

defined('ABSPATH') || exit;

/**
 * Register the pattern category used by files in /patterns.
 */
function second_coming_register_pattern_category() {
    register_block_pattern_category(
        'second-coming',
        array(
            'label'       => __('Second Coming', 'second-coming'),
            'description' => __('Matrix / terminal sections used to assemble pages.', 'second-coming'),
        )
    );
}
add_action('init', 'second_coming_register_pattern_category', 5);

/**
 * Default copy so a page assembled without AI still looks like the theme.
 *
 * @return array<string, string>
 */
function second_coming_token_defaults() {
    return array(
        'sc_kicker'         => __('ACCESS NODE // CLEARANCE PENDING', 'second-coming'),
        'sc_headline'       => __('Follow the white rabbit', 'second-coming'),
        'sc_lede'           => __('A terminal briefing. Neon on black. Nothing beige.', 'second-coming'),
        'sc_status'         => __('UPLINK OK  ·  TRACE: OFF  ·  MODE: BLUE PILL', 'second-coming'),
        'sc_section_title'  => __('Classified index', 'second-coming'),
        'sc_stack_title'    => __('Stack', 'second-coming'),
        'sc_card1_title'    => __('Construct', 'second-coming'),
        'sc_card1_body'     => __('The room you load first. Hero, rain, the lie that looks like a lobby.', 'second-coming'),
        'sc_card2_title'    => __('Operator', 'second-coming'),
        'sc_card2_body'     => __('Who watches the trace. Sidebar logs, not a blog widget.', 'second-coming'),
        'sc_card3_title'    => __('Hard line', 'second-coming'),
        'sc_card3_body'     => __('The way out. One CTA, no carousel, no lorem.', 'second-coming'),
        'sc_terminal'       => "Zion uplink established.\nPacket 7.1 · no beige found.\nAwaiting operator.",
        'sc_protocol_title' => __('Protocol', 'second-coming'),
        'sc_step1'          => __('Jack in. Describe the page in one sentence.', 'second-coming'),
        'sc_step2'          => __('The site picks Second Coming sections only.', 'second-coming'),
        'sc_step3'          => __('Open the draft. Edit copy. Publish when it is yours.', 'second-coming'),
        'sc_cta_title'      => __('Jack in', 'second-coming'),
        'sc_cta_body'       => __('One action. No newsletter popup.', 'second-coming'),
        'sc_cta_label'      => __('Enter', 'second-coming'),
        'sc_cta_url'        => '#',
        'sc_denied_title'   => __('Access denied', 'second-coming'),
        'sc_denied_body'    => __('This node is restricted. Ask the operator.', 'second-coming'),
    );
}

/**
 * Live catalog of this theme's patterns (slug, title, description, keywords).
 *
 * @return array<int, array<string, mixed>>
 */
function second_coming_get_pattern_catalog() {
    if (!class_exists('WP_Block_Patterns_Registry')) {
        return array();
    }

    $catalog = array();
    $all     = WP_Block_Patterns_Registry::get_instance()->get_all_registered();

    foreach ($all as $pattern) {
        if (empty($pattern['name']) || strpos($pattern['name'], 'second-coming/') !== 0) {
            continue;
        }

        $catalog[] = array(
            'slug'        => $pattern['name'],
            'title'       => isset($pattern['title']) ? (string) $pattern['title'] : $pattern['name'],
            'description' => isset($pattern['description']) ? (string) $pattern['description'] : '',
            'keywords'    => isset($pattern['keywords']) && is_array($pattern['keywords']) ? $pattern['keywords'] : array(),
        );
    }

    return $catalog;
}

/**
 * Registered slugs only (allowlist).
 *
 * @return string[]
 */
function second_coming_registered_pattern_slugs() {
    return wp_list_pluck(second_coming_get_pattern_catalog(), 'slug');
}

/**
 * Markup for one registered pattern, or empty string.
 *
 * @param string $slug Pattern name (second-coming/…).
 * @return string
 */
function second_coming_get_pattern_content($slug) {
    if (!class_exists('WP_Block_Patterns_Registry')) {
        return '';
    }

    $registry = WP_Block_Patterns_Registry::get_instance();
    if (!$registry->is_registered($slug)) {
        return '';
    }

    $pattern = $registry->get_registered($slug);
    return isset($pattern['content']) ? (string) $pattern['content'] : '';
}

/**
 * Keep only slugs this theme actually registered.
 *
 * @param array $slugs Raw slugs from the model or an ability call.
 * @return string[]
 */
function second_coming_allowlist_pattern_slugs($slugs) {
    $clean     = array();
    $allowed   = second_coming_registered_pattern_slugs();
    $seen      = array();

    foreach ((array) $slugs as $slug) {
        $slug = sanitize_text_field((string) $slug);
        $slug = str_replace('_', '-', $slug);
        if ($slug !== '' && strpos($slug, '/') === false) {
            $slug = 'second-coming/' . $slug;
        }
        if (!preg_match('/^second-coming\/[a-z0-9-]+$/', $slug)) {
            continue;
        }
        if (!in_array($slug, $allowed, true) || isset($seen[$slug])) {
            continue;
        }
        $seen[$slug] = true;
        $clean[]     = $slug;
    }

    return $clean;
}

/**
 * Replace {{token}} placeholders. Unknown keys ignored. Values kses'd.
 *
 * @param string $markup Pattern HTML.
 * @param array  $copy   Token => string.
 * @return string
 */
function second_coming_apply_tokens($markup, $copy) {
    $defaults = second_coming_token_defaults();
    $merged   = $defaults;

    foreach ((array) $copy as $key => $value) {
        $key = sanitize_key((string) $key);
        if ($key === '' || !array_key_exists($key, $defaults)) {
            continue;
        }
        if ($key === 'sc_cta_url') {
            $merged[$key] = esc_url((string) $value);
            if ($merged[$key] === '') {
                $merged[$key] = '#';
            }
            continue;
        }
        $merged[$key] = wp_kses_post((string) $value);
    }

    foreach ($merged as $key => $value) {
        $markup = str_replace('{{' . $key . '}}', $value, $markup);
    }

    return $markup;
}

/**
 * Concatenate allowlisted patterns and fill tokens.
 *
 * @param string[] $slugs Pattern slugs.
 * @param array    $copy  Optional token map.
 * @return string|WP_Error
 */
function second_coming_assemble_page_content($slugs, $copy = array()) {
    $slugs = second_coming_allowlist_pattern_slugs($slugs);
    if (empty($slugs)) {
        return new WP_Error(
            'no_patterns',
            __('No valid Second Coming patterns were selected.', 'second-coming')
        );
    }

    $chunks = array();
    foreach ($slugs as $slug) {
        $html = second_coming_get_pattern_content($slug);
        if ($html === '') {
            continue;
        }
        $chunks[] = $html;
    }

    if (empty($chunks)) {
        return new WP_Error(
            'empty_markup',
            __('The selected patterns had no markup.', 'second-coming')
        );
    }

    return second_coming_apply_tokens(implode("\n\n", $chunks), $copy);
}
