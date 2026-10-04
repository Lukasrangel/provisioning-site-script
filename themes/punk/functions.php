<?php
/**
 * Punk functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Punk
 * @since Punk 1.0
 */


if ( ! function_exists( 'punk_support' ) ) :

	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * @since Punk 1.0
	 *
	 * @return void
	 */
	function punk_support() {

		// Enqueue editor styles.
		add_editor_style( 'style.css' );

		// Make theme available for translation.
		load_theme_textdomain( 'punk' );
	}

endif;

add_action( 'after_setup_theme', 'punk_support' );

if ( ! function_exists( 'punk_styles' ) ) :

	/**
	 * Enqueue styles.
	 *
	 * @since Punk 1.0
	 *
	 * @return void
	 */
	function punk_styles() {

		// Register theme stylesheet.
		wp_register_style(
			'punk-style',
			get_stylesheet_directory_uri() . '/style.css',
			array(),
			wp_get_theme()->get( 'Version' )
		);

		// Enqueue theme stylesheet.
		wp_enqueue_style( 'punk-style' );

	}

endif;

add_action( 'wp_enqueue_scripts', 'punk_styles' );
