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
		 * (ADDED TO ROADMAP)
		 */
		if ( $this->is_child_theme() )
			return;

		/**
		 * Get stylesheets content
		 * 
		 * Bail out if stylesheets aren't set
		 */
		if ( !$this->get_theme_styles_url() )
			return;

		foreach ( $this->get_theme_styles_url() as $key => $file ) {
			$stylesheets[$key] = $this->get_stylesheet_content( $file );
		}

		/**
		 * Output the stylesheet contents
		 */
		foreach ( $stylesheets as $stylesheet ) {
			echo $this->format_inline_css( $stylesheet );
		}

	}

	/**
	 * Check if child theme is in use
	 * 
	 * @since 	1.0.0
	 * @access 	private
	 * @return	boolean		If child theme is in use
	 */
	private function is_child_theme() {
		return is_child_theme();
	}

	/**
	 * Get the theme stylesheet
	 * 
	 * (WILL BECOME ALL ENQUEUE STYLESHEETS)
	 * 
	 * @since	1.0.0
	 * @access 	private
	 * @return	string		$stylesheet_url		The stylesheets URL
	 */
	private function get_theme_styles_url() {

		$stylesheet_url['theme'] = get_stylesheet_uri();
		// return array for future proofing
		return $stylesheet_url;

	}

	/**
	 * Dequeue the stylesheets
	 * 
	 * (HARDCODED FOR NOW)
	 * 
	 * @since	1.0.0
	 */
	public function dequeue_stylesheets() {

		wp_dequeue_style( 'twentyseventeen-style' );
		wp_deregister_style( 'twentyseventeen-style' );
		wp_dequeue_style( 'twentyseventeen-ie8' );
		wp_deregister_style( 'twentyseventeen-ie8' );

	}

	/**
	 * Open and return the contents of a stylesheet
	 * 
	 * @since 	1.0.0
	 * @access 	private
	 * @param 	string		$file	The URL of a stylesheet
	 * @return 	string 		$file_contents 	The contents of the stylesheet
	 */
	private function get_stylesheet_content( $file ) {

		$file_contents = file_get_contents( $file );

		/**
		 * Strip comments
		 * Strip excess whitespace
		 * Non-destructive CSS formatting
		 * 
		 * Credit: https://www.progclub.org/blog/2012/01/10/compressing-css-in-php-no-comments-or-whitespace/
		 */
		$replace = array(
			"#/\*.*?\*/#s" 		=> "",  // Strip C style comments.
			"#\s\s+#"      		=> " ", // Strip excess whitespace.
			"/[\r|\n|\t|\r\n]/"	=> "", // Strip new lines, tabs, etc...
		);
		$search = array_keys($replace);
		$file_contents = preg_replace($search, $replace, $file_contents);
		
		$replace = array(
			": "  => ":",
			"; "  => ";",
			" {"  => "{",
			" }"  => "}",
			", "  => ",",
			"{ "  => "{",
			";}"  => "}", // Strip optional semicolons.
			",\n" => ",", // Don't wrap multiple selectors.
			"\n}" => "}", // Don't wrap closing braces.
		);
		$search = array_keys($replace);
		$file_contents = str_replace($search, $replace, $file_contents);

		return $file_contents;

	}

	/**
	 * Return the inline CSS for the page head
	 * 
	 * @since	1.0.0
	 * @access 	private
	 * @param 	string		$css			The raw CSS to be inlined
	 * @return 	string 		$inline_css 	The inline HTML to be output
	 */
	private function format_inline_css( $css ) {

		$inline_css = '<style type="text/css">';
		$inline_css .= $css;
		$inline_css .= '</style>';

		return $inline_css;

	}

}
