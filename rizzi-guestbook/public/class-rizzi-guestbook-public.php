<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      4.0.0
 *
 * @package    Rizzi_Guestbook
 * @subpackage Rizzi_Guestbook/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the rizzi guestbook, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Rizzi_Guestbook
 * @subpackage Rizzi_Guestbook/public
 * @author     Your Name <email@example.com>
 */
class Rizzi_Guestbook_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    4.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    4.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    4.0.0
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
	 * @since    4.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Rizzi_Guestbook_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Rizzi_Guestbook_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/rizzi-guestbook-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    4.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Rizzi_Guestbook_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Rizzi_Guestbook_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/rizzi-guestbook-public.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Replace guestbook page contents with the guestbook.
	 *
	 * @since    4.0.0
	 */
	public function replace_content( $content ) {
    global $comment, $post;
    if ($post->ID != get_option('rizzi_guestbook_guestbook_page')) return $content;
    include_once 'partials/rizzi-guestbook-public-header.php';
    if (isset($_GET["sign"])) {
      include_once 'partials/rizzi-guestbook-public-sign.php';
    } else {
      include_once 'partials/rizzi-guestbook-public-display.php';
    }
	}

	/**
	 * Suppress the regular comments by replacing it with a blank file.
	 *
	 * @since    4.0.0
	 */
  function suppress_comments( $file ) {
    global $post;
    if ($post->ID != get_option('rizzi_guestbook_guestbook_page')) return $file;
    return dirname(__FILE__) . '/index.php';
  }

  function add_param($name, $value) {
    $params = $_GET;
    unset($params[$name]);
    $params[$name] = $value;
    return basename( $_SERVER['PHP_SELF'] ) . '?' .http_build_query( $params );
  }
}
