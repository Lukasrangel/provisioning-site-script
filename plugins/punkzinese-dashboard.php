<?php
/**
 * Plugin Name: Punkzinese Dashboard
 * Description: Exibe o dashboard remoto como página principal do painel administrativo.
 * Version: 2.0.0
 * Author: Punkzinese
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Registra a página do dashboard no menu administrativo.
 */
add_action('admin_menu', 'punkzinese_register_dashboard_page');

function punkzinese_register_dashboard_page() {
    add_menu_page(
        'Punkzinese Dashboard',       // Título da página
        'Punkzinese',                 // Nome no menu
        'read',                       // Capacidade necessária
        'punkzinese-dashboard',        // Slug
        'punkzinese_render_dashboard', // Callback
        'dashicons-admin-site-alt3',   // Ícone
        2                             // Posição no menu
    );
}

/**
 * Renderiza o dashboard remoto em tela inteira.
 */
function punkzinese_render_dashboard() {
    $url = 'https://punkzinese.org/';

    ?>
    <div class="wrap punkzinese-dashboard-wrap">
        <iframe
            src="<?php echo esc_url($url); ?>"
            title="Dashboard Punkzinese"
            loading="eager"
            allowfullscreen
        ></iframe>
    </div>
    <?php
}

/**
 * Estilos exclusivos da página do dashboard.
 */
add_action('admin_head-toplevel_page_punkzinese-dashboard', 'punkzinese_dashboard_styles');

function punkzinese_dashboard_styles() {
    ?>
    <style>
        #wpcontent {
            padding-left: 0;
        }

        #wpbody-content {
            padding-bottom: 0;
        }

        .punkzinese-dashboard-wrap {
            margin: 0;
            padding: 0;
        }

        .punkzinese-dashboard-wrap iframe {
            display: block;
            width: 100%;
            height: calc(100vh - 32px);
            min-height: 600px;
            border: 0;
            background: #fff;
        }
    </style>
    <?php
}

/**
 * Redireciona o Dashboard padrão para a página do Punkzinese.
 */
add_action('load-index.php', 'punkzinese_redirect_default_dashboard');

function punkzinese_redirect_default_dashboard() {
    if (!current_user_can('read')) {
        return;
    }

    wp_safe_redirect(
        admin_url('admin.php?page=punkzinese-dashboard')
    );

    exit;
}

?>
