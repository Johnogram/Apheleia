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
	 * Storage for retrieved stylesheets/scripts
	 * 
	 * @since	1.0.0
	 * @access 	private
	 * @var		array		$data 		Enqueued stylesheets/scripts
	 */
	private $data = array();

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
	public function inline_scripts() {
		
		/**
		 * Bail out if child theme is active
		 * 
		 * (ADDED TO ROADMAP)
		 */
		if ( $this->is_child_theme() )
			return;

		$scripts = array();

		/**
		 * Bail out if user is logged in
		 */
		if ( $this->is_user_logged_in() )
			return;

		/**
		 * Is this header or footer?
		 * 
		 * Begin process of inlining CSS and dequeue external files
		 */
		if ( ! isset( $this->data['footer'] ) ) { // Header
			$scripts = $this->get_scripts( $this->data['header'] );
		} else { // Footer
			$scripts = $this->get_scripts( $this->data['footer'] );
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
	 * Check if user is logged in
	 * 
	 * @since 	1.0.0
	 * @access 	private
	 * @return 	boolean		If user is logged in
	 */
	private function is_user_logged_in() {
		return is_user_logged_in();
	}
 
	/**
	 * Get the scripts enqueued on page load
	 * 
	 * Inspiration for methods taken from Query Monitor plugin
	 * https://en-gb.wordpress.org/plugins/query-monitor/
	 * 
	 * @since	1.0.0
	 */
	public function get_enqueued_header_scripts() {

		global $wp_scripts, $wp_styles;

		/**
		 * Raw data from page load (wp_head at 999)
		 */
		$this->data['raw']['scripts'] = $wp_scripts;
		$this->data['raw']['styles']  = $wp_styles;

		$this->data['header']['styles'] = $wp_styles->done;
		$this->data['header']['scripts'] = $wp_scripts->done;
		

	}

	/**
	 * Get the scripts enqueued to page footer
	 * 
	 * Inspiration for methods taken from Query Monitor plugin
	 * https://en-gb.wordpress.org/plugins/query-monitor/
	 * 
	 * @since	1.0.0
	 */
	public function get_enqueued_footer_scripts() {

		global $wp_scripts, $wp_styles;

		if ( empty( $this->data['header'] ) ) {
			return;
		}

		/**
		 * Overwrite raw data from page footer (wp_print_footer_scripts at 999)
		 */
		$this->data['raw']['scripts'] = $wp_scripts;
		$this->data['raw']['styles']  = $wp_styles;

		$this->data['footer']['scripts'] = array_diff( $wp_scripts->done, $this->data['header']['scripts'] );
		$this->data['footer']['styles']  = array_diff( $wp_styles->done, $this->data['header']['styles'] );

	}

	/**
	 * Get the scripts
	 * 
	 * @since	1.0.0
	 * @access 	private
	 * @param 	array		$scripts		The array of enqueued scripts
	 */
	private function get_scripts( $scripts ) {

		// TO DO: CHECK FOR BROKEN AND MISSING DEPENDANCIES

		/**
		 * Bail out if no data
		 */
		if ( ! isset( $this->data['raw'] ) ) {
			return;
		}

		/**
		 *  Get all styles
		 */
		foreach ( $scripts['styles'] as $handle ) {

			if ( $handle == 'twentyseventeen-ie8' )
				return;

			$style_url = $raw_data = $this->data['raw']['styles']->registered[$handle]->src;

			// To Do: Add in hook/action to allow prevention of this
			
			/**
			 * Get stylesheet content
			 */
			$style_content = $this->get_stylesheet_content( $style_url );

			/**
			 * Inline the content
			 */
			$style_inline = $this->format_inline_css( $handle, $style_content );
			echo $style_inline;

			/**
			 * Dequeue the external stylesheet
			 */
			$this->dequeue_stylesheets( $handle );

		}

	}

	/**
	 * Dequeue the stylesheets
	 * 
	 * @since	1.0.0
	 * @param 	string		$handle		The handle of the stylesheet
	 */
	public function dequeue_stylesheets( $handle ) {

		wp_dequeue_style( $handle );
		wp_deregister_style( $handle );

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
			"/[\r|\n|\t|\r\n]/"	=> "",  // Strip new lines, tabs, etc...
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
	 * @param	string		$key 			The name of the stylesheet
	 * @param 	string		$css			The raw CSS to be inlined
	 * @return 	string 		$inline_css 	The inline HTML to be output
	 */
	private function format_inline_css( $handle, $css ) {

		$inline_css  = "<!-- Apheleia: $handle -->\r\n";
		$inline_css .= "<style type=\"text/css\">\r\n";
		$inline_css .= $css;
		$inline_css .= "</style>\r\n";
		$inline_css .= "<!--/Apheleia: $handle -->\r\n";

		return $inline_css;

	}

}
