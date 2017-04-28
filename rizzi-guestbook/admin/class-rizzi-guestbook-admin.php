<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      4.0.0
 *
 * @package    Rizzi_Guestbook
 * @subpackage Rizzi_Guestbook/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the rizzi guestbook, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Rizzi_Guestbook
 * @subpackage Rizzi_Guestbook/admin
 * @author     Your Name <email@example.com>
 */
class Rizzi_Guestbook_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    4.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

  /**
   * The options name to be used in this plugin
   *
   * @since  4.0.0
   * @access private
   * @var  string $option_name Option name of this plugin
   */
  private $option_name = 'rizzi_guestbook';

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/rizzi-guestbook-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/rizzi-guestbook-admin.js', array( 'jquery' ), $this->version, false );

	}

  /**
   * Add an options page under the Settings submenu
   *
   * @since  4.0.0
   */
  public function add_options_page() {
    $this->options_page = add_options_page(
      __( 'Rizzi Guestbook Settings', 'rizzi-guestbook' ),
      __( 'Rizzi Guestbook', 'rizzi-guestbook' ),
      'manage_options',
      $this->plugin_name,
      array( $this, 'display_options_page' )
    );
  }

  /**
   * Render the options page for plugin
   *
   * @since  4.0.0
   */
  public function display_options_page() {
    include_once 'partials/rizzi-guestbook-admin-display.php';
  }

  /**
   * Register all related settings of this plugin
   *
   * @since  4.0.0
   */
  public function register_settings() {
    add_settings_section(
      $this->option_name . '_general',
      __( 'General', 'rizzi-guestbook' ),
      array( $this, $this->option_name . '_general_cb' ),
      $this->plugin_name
    );
    add_settings_field(
      $this->option_name . '_fav_text_editor',
      __( 'Favorite Text Editor', 'rizzi-guestbook'),
      array( $this, $this->option_name . '_fav_text_editor_cb' ),
      $this->plugin_name,
      $this->option_name . '_general',
      array( 'label_for' => $this->option_name . '_fav_text_editor' )
    );
    register_setting(
      $this->plugin_name,
      $this->option_name . '_fav_text_editor',
      array( $this, $this->option_name . '_sanitize_fav_text_editor' )
    );
  }

  /**
   * Render the text for the general section
   *
   * @since  4.0.0
   */
  public function rizzi_guestbook_general_cb() {
    echo '<p>' . __( 'Please change the settings accordingly.', 'rizzi-guestbook' ) . '</p>';
  }

  /**
   * Render the radio input field for fav text editor option
   *
   * @since  4.0.0
   */
  public function rizzi_guestbook_fav_text_editor_cb() {
    $option_name = $this->option_name . '_fav_text_editor';
    $fav_text_editor = get_option( $option_name );
    ?>
      <fieldset>
        <label>
          <input type="radio" id="<?php echo $option_name; ?>" name="<?php echo $option_name; ?>" value="vim" <?php checked($fav_text_editor, 'vim'); ?>>
          <?php _e( 'Vim', 'rizzi-guestbook' ); ?>
        </label>
        <br />
        <label>
          <input type="radio" name="<?php echo $option_name; ?>" value="emacs" <?php checked($fav_text_editor, 'emacs'); ?>>
          <?php _e( 'Emacs', 'rizzi-guestbook' ); ?>
        </label>
      </fieldset>
    <?php
  }

  /**
   * Sanitize the fav text editor value before being saved to database
   *
   * @param  string $fav_text_editor $_POST value
   * @since  4.0.0
   * @return string           Sanitized value
   */
  public function rizzi_guestbook_sanitize_fav_text_editor( $fav_text_editor ) {
    if ( in_array( $fav_text_editor, array( 'vim', 'emacs' ), true ) ) {
      return $fav_text_editor;
    }
  }
}
