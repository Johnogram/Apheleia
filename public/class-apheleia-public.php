<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       minns.io
 * @since      1.0.0
 *
 * @package    Apheleia
 * @subpackage Apheleia/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Apheleia
 * @subpackage Apheleia/public
 * @author     John-O-Gram <john@minns.io>
 */
class Apheleia_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Apheleia_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Apheleia_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/apheleia-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Apheleia_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Apheleia_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/apheleia-public.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Start the process
	 * 
	 * @since 	1.0.0
	 */
	public function inline_styles() {
		
		/**
		 * Bail out if child theme is active
		 * 
		 * (Added to roadmap)
		 */
		if ( $this->is_child_theme() )
			return;

		
	}

	/**
	 * Check if child theme is in use
	 * 
	 * @since 	1.0.0
	 */
	private function is_child_theme() {

		return is_child_theme();
	}

	/**
	 * Get the theme stylesheet
	 * 
	 * @since	1.0.0
	 */
	private function get_theme_styles_url() {

	}

	/**
	 * Open and return the contents of stylesheet
	 * 
	 * @since 	1.0.0
	 */
	private function get_theme_styles_content( $file ) {

	}

}
