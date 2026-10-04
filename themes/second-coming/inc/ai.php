<?php
/**
 * Owner Generate field on Pages (same idea as Simple Role Based Pricing).
 * Uses wp_ai_client_prompt() when a connector exists. Theme works without it.
 *
 * @package Second_Coming
 */

defined('ABSPATH') || exit;

/**
 * True when a prompt client exists (connector may still be missing).
 *
 * @return bool
 */
function second_coming_ai_is_available() {
    if (function_exists('wp_supports_ai') && wp_supports_ai()) {
        return true;
    }
    return function_exists('wp_ai_client_prompt');
}

/**
 * Enqueue the Generate box on the Pages list and page editor.
 *
 * @param string $hook_suffix Current admin screen.
 */
function second_coming_ai_admin_assets($hook_suffix) {
    if (!current_user_can('edit_pages')) {
        return;
    }

    $screen = function_exists('get_current_screen') ? get_current_screen() : null;
    $ok     = in_array($hook_suffix, array('edit.php', 'post.php', 'post-new.php'), true);
    if (!$ok || !$screen || $screen->post_type !== 'page') {
        return;
    }

    $version = wp_get_theme()->get('Version');

    wp_enqueue_style(
        'second-coming-admin-ai',
        get_theme_file_uri('assets/css/admin-ai.css'),
        array(),
        $version
    );
    wp_enqueue_script(
        'second-coming-admin-ai',
        get_theme_file_uri('js/admin-ai.js'),
        array(),
        $version,
        true
    );
    wp_localize_script(
        'second-coming-admin-ai',
        'secondComingAi',
        array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce'   => wp_create_nonce('second_coming_ai_generate'),
            'i18n'    => array(
                'working'   => __('Generating…', 'second-coming'),
                'empty'     => __('Describe the page first.', 'second-coming'),
                'generic'   => __('Could not generate the page.', 'second-coming'),
            ),
        )
    );
}
add_action('admin_enqueue_scripts', 'second_coming_ai_admin_assets');

/**
 * Generate box on Pages list.
 */
function second_coming_ai_pages_notice() {
    $screen = get_current_screen();
    if (!$screen || $screen->id !== 'edit-page') {
        return;
    }
    if (!current_user_can('edit_pages')) {
        return;
    }

    second_coming_ai_render_box();
}
add_action('admin_notices', 'second_coming_ai_pages_notice');

/**
 * Same box in the page editor.
 */
function second_coming_ai_add_metabox() {
    add_meta_box(
        'second-coming-ai-generate',
        __('Generate with Second Coming', 'second-coming'),
        'second_coming_ai_render_box',
        'page',
        'side',
        'high'
    );
}
add_action('add_meta_boxes', 'second_coming_ai_add_metabox');

/**
 * Markup: native admin, no neon.
 */
function second_coming_ai_render_box() {
    static $printed = false;
    if ($printed) {
        return;
    }
    $printed = true;

    $available = second_coming_ai_is_available();
    $count     = count(second_coming_get_pattern_catalog());
    $screen    = function_exists('get_current_screen') ? get_current_screen() : null;
    $is_list   = $screen && $screen->id === 'edit-page';
    $classes   = $is_list ? 'sc-ai-box notice notice-info' : 'sc-ai-box';
    ?>
    <div class="<?php echo esc_attr($classes); ?>" id="sc-ai-box">
        <p class="sc-ai-box__intro">
            <?php
            echo esc_html(
                sprintf(
                    /* translators: %d: number of registered patterns */
                    __('Describe a page. Second Coming assembles a draft from its %d patterns — no generic Gutenberg.', 'second-coming'),
                    $count
                )
            );
            ?>
        </p>
        <?php if (!$available) : ?>
            <p>
                <?php
                echo esc_html__('Go to Settings → Connectors: install the AI plugin from the notice, add a connector key, then Settings → AI → Enable AI. The rest of the theme works without it.', 'second-coming');
                ?>
            </p>
        <?php else : ?>
            <p>
                <label for="sc-ai-prompt" class="screen-reader-text"><?php esc_html_e('Page description', 'second-coming'); ?></label>
                <textarea id="sc-ai-prompt" class="large-text" rows="3" placeholder="<?php echo esc_attr__('A confidential-data page, Matrix office: hero, file grid, access warning, then a jack-in CTA.', 'second-coming'); ?>"></textarea>
            </p>
            <p>
                <button type="button" class="button button-primary" id="sc-ai-generate">
                    <?php esc_html_e('Generate draft', 'second-coming'); ?>
                </button>
                <span class="sc-ai-box__status" id="sc-ai-status" aria-live="polite"></span>
            </p>
        <?php endif; ?>
    </div>
    <?php
}

add_action('wp_ajax_second_coming_ai_generate', 'second_coming_ajax_ai_generate');

/**
 * Phrase → JSON → create-page (draft).
 */
function second_coming_ajax_ai_generate() {
    check_ajax_referer('second_coming_ai_generate', 'nonce');

    if (!current_user_can('edit_pages')) {
        wp_send_json_error(array(
            'message' => __('Permission denied.', 'second-coming'),
        ));
    }

    $prompt = isset($_POST['prompt']) ? sanitize_textarea_field(wp_unslash($_POST['prompt'])) : '';
    if ($prompt === '') {
        wp_send_json_error(array(
            'message' => __('Describe the page first.', 'second-coming'),
        ));
    }

    $payload = second_coming_ai_extract_page_from_prompt($prompt);
    if (is_wp_error($payload)) {
        wp_send_json_error(array(
            'message' => $payload->get_error_message(),
        ));
    }

    $saved = second_coming_ability_create_page($payload);
    if (is_wp_error($saved)) {
        wp_send_json_error(array(
            'message' => $saved->get_error_message(),
        ));
    }

    wp_send_json_success($saved);
}

/**
 * Ask the connector for title + pattern slugs + copy tokens.
 *
 * @param string $prompt Owner sentence.
 * @return array|WP_Error
 */
function second_coming_ai_extract_page_from_prompt($prompt) {
    if (!function_exists('wp_ai_client_prompt')) {
        return new WP_Error(
            'no_connector',
            __('Go to Settings → Connectors, install the AI plugin from the notice, and add a connector key.', 'second-coming')
        );
    }

    $catalog = second_coming_get_pattern_catalog();
    $lines   = array();
    foreach ($catalog as $item) {
        $lines[] = $item['slug'] . ' — ' . $item['title'] . ': ' . $item['description'];
    }

    $tokens = implode(', ', array_keys(second_coming_token_defaults()));

    $full  = "You assemble a WordPress page for the Second Coming theme (Matrix / cyberpunk / terminal).\n";
    $full .= "Use ONLY these pattern slugs, 3 to 7 of them, in display order:\n";
    $full .= implode("\n", $lines) . "\n";
    $full .= "Prefer second-coming/hero-terminal first when a hero fits. Prefer second-coming/cta-jack-in last when a CTA fits.\n";
    $full .= "Do not invent slugs. Do not output HTML or CSS.\n";
    $full .= 'Optional copy keys (omit any you do not need): ' . $tokens . "\n";
    $full .= "User request:\n" . $prompt . "\n\n";
    $full .= "Return ONLY a JSON object, no markdown, no backticks:\n";
    $full .= '{"title":"Page title","slug":"optional-slug","patterns":["second-coming/hero-terminal"],"copy":{"sc_headline":"...","sc_lede":"..."}}';

    $raw = wp_ai_client_prompt($full)->generate_text();

    if (is_wp_error($raw)) {
        $code = $raw->get_error_code();
        if (in_array($code, array('no_provider', 'provider_not_configured', 'unsupported'), true)) {
            return new WP_Error(
                'no_connector',
                __('Go to Settings → Connectors, install the AI plugin from the notice, and add a connector key.', 'second-coming')
            );
        }
        return new WP_Error(
            'ai_failed',
            sprintf(
                /* translators: %s: provider error */
                __('AI request failed: %s', 'second-coming'),
                $raw->get_error_message()
            )
        );
    }

    return second_coming_ai_parse_page_json($raw);
}

/**
 * Normalize model output to create-page input.
 *
 * @param string $raw Model text.
 * @return array|WP_Error
 */
function second_coming_ai_parse_page_json($raw) {
    $raw = trim((string) $raw);
    $raw = preg_replace('/^```(?:json)?\s*/i', '', $raw);
    $raw = preg_replace('/\s*```$/', '', $raw);

    $data = json_decode($raw, true);
    if (!is_array($data) && preg_match('/\{.*\}/s', $raw, $match)) {
        $data = json_decode($match[0], true);
    }

    if (is_array($data) && isset($data[0]) && is_array($data[0])) {
        $data = $data[0];
    }

    if (is_array($data)) {
        foreach (array('page', 'result', 'data') as $wrap) {
            if (isset($data[$wrap]) && is_array($data[$wrap]) && isset($data[$wrap]['title'])) {
                $data = $data[$wrap];
                break;
            }
        }
    }

    if (!is_array($data) || empty($data['title']) || empty($data['patterns']) || !is_array($data['patterns'])) {
        return new WP_Error(
            'invalid_json',
            __('The AI returned an unexpected response. Please try again.', 'second-coming')
        );
    }

    if (isset($data['copy']) && is_string($data['copy'])) {
        $decoded = json_decode($data['copy'], true);
        $data['copy'] = is_array($decoded) ? $decoded : array();
    }
    if (!isset($data['copy']) || !is_array($data['copy'])) {
        $data['copy'] = array();
    }

    $data['status'] = 'draft';

    return $data;
}
