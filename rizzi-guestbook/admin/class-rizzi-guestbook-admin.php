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
    // guestbook_page
    add_settings_field(
      $this->option_name . '_guestbook_page',
      __( 'Guestbook Page', 'rizzi-guestbook' ),
      array( $this, $this->option_name . '_guestbook_page_cb' ),
      $this->plugin_name,
      $this->option_name . '_general',
      array( 'label_for' => $this->option_name . '_guestbook_page' )
    );
    register_setting(
      $this->plugin_name,
      $this->option_name . '_guestbook_page',
      array( $this, $this->option_name . '_sanitize_guestbook_page' )
    );
    // sign_guestbook_title
    add_settings_field(
      $this->option_name . '_sign_guestbook_title',
      __( 'Sign Guestbook Title', 'rizzi-guestbook' ),
      array( $this, $this->option_name . '_sign_guestbook_title_cb' ),
      $this->plugin_name,
      $this->option_name . '_general',
      array( 'label_for' => $this->option_name . '_sign_guestbook_title' )
    );
    register_setting(
      $this->plugin_name,
      $this->option_name . '_sign_guestbook_title',
      array( $this, $this->option_name . '_sanitize_sign_guestbook_title' )
    );
    // show_guestbook_title
    add_settings_field(
      $this->option_name . '_show_guestbook_title',
      __( 'Show Guestbook Title', 'rizzi-guestbook' ),
      array( $this, $this->option_name . '_show_guestbook_title_cb' ),
      $this->plugin_name,
      $this->option_name . '_general',
      array( 'label_for' => $this->option_name . '_show_guestbook_title' )
    );
    register_setting(
      $this->plugin_name,
      $this->option_name . '_show_guestbook_title',
      array( $this, $this->option_name . '_sanitize_show_guestbook_title' )
    );
    // only_registered
    add_settings_field(
      $this->option_name . '_only_registered',
      __( 'Only Registered', 'rizzi-guestbook' ),
      array( $this, $this->option_name . '_only_registered_cb' ),
      $this->plugin_name,
      $this->option_name . '_general',
      array( 'label_for' => $this->option_name . '_only_registered' )
    );
    register_setting(
      $this->plugin_name,
      $this->option_name . '_only_registered',
      array( $this, $this->option_name . '_sanitize_only_registered' )
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
   * Render the selection field for guestbook page setting
   *
   * @since  4.0.0
   */
  public function rizzi_guestbook_guestbook_page_cb() {
    $option_name = $this->option_name . '_guestbook_page';
    $guestbook_page = get_option( $option_name );
    $pages = get_pages();
    ?>
      <fieldset>
        <label>
          <select id="<?php echo $option_name; ?>"
                  name="<?php echo $option_name; ?>">
            <?php foreach($pages as $page) { ?>
              <option value="<?php echo $page->ID; ?>"
                      <?php echo $guestbook_page == $page->ID ? 'selected' : ''; ?>>
                <?php echo $page->post_title; ?>
              </option>
            <?php } ?>
          </select>
        </label>
      </fieldset>
    <?php
  }

  /**
   * Render the sign_guestbook_title for sign guestbook title setting
   *
   * @since  4.0.0
   */
  public function rizzi_guestbook_sign_guestbook_title_cb() {
    $option_name = $this->option_name . '_sign_guestbook_title';
    $sign_guestbook_title = get_option( $option_name );
    ?>
      <fieldset>
        <label>
          <input id="<?php echo $option_name; ?>"
                 name="<?php echo $option_name; ?>"
                 type="text"
                 value="<?php echo $sign_guestbook_title; ?>" />
        </label>
      </fieldset>
    <?php
  }

  /**
   * Render the show_guestbook_title for show guestbook title setting
   *
   * @since  4.0.0
   */
  public function rizzi_guestbook_show_guestbook_title_cb() {
    $option_name = $this->option_name . '_show_guestbook_title';
    $show_guestbook_title= get_option( $option_name );
    ?>
      <fieldset>
        <label>
          <input id="<?php echo $option_name; ?>"
                 name="<?php echo $option_name; ?>"
                 type="text"
                 value="<?php echo $show_guestbook_title; ?>" />
        </label>
      </fieldset>
    <?php
  }

  /**
   * Render the only_registered for show guestbook title setting
   *
   * @since  4.0.0
   */
  public function rizzi_guestbook_only_registered_cb() {
    $option_name = $this->option_name . '_only_registered';
    $only_registered= get_option( $option_name );
    ?>
      <fieldset>
        <label>
          <input id="<?php echo $option_name; ?>"
                 name="<?php echo $option_name; ?>"
                 type="checkbox"
                 value="1"
                 <?php echo $only_registered ? 'checked' : ''; ?> />
        </label>
      </fieldset>
    <?php
  }

  /**
   * Sanitize the guestbook_page_value before being saved to database
   *
   * @param  string $guestbook_page $_POST value
   * @since  4.0.0
   * @return string           Sanitized value
   */
  public function rizzi_guestbook_sanitize_guestbook_page( $guestbook_page ) {
    return $guestbook_page;
  }

  /**
   * Sanitize the sign_guestbook_title value before being saved to database
   *
   * @param  string $sign_guestbook_title $_POST value
   * @since  4.0.0
   * @return string           Sanitized value
   */
  public function rizzi_guestbook_sanitize_sign_guestbook_title( $sign_guestbook_title ) {
    return $sign_guestbook_title;
  }

  /**
   * Sanitize the show_guestbook_title value before being saved to database
   *
   * @param  string $show_guestbook_title $_POST value
   * @since  4.0.0
   * @return string           Sanitized value
   */
  public function rizzi_guestbook_sanitize_show_guestbook_title( $show_guestbook_title ) {
    return $show_guestbook_title;
  }

  /**
   * Sanitize the only_registered value before being saved to database
   *
   * @param  string $only_registered $_POST value
   * @since  4.0.0
   * @return string           Sanitized value
   */
  public function rizzi_guestbook_sanitize_only_registered( $only_registered ) {
    return $only_registered;
  }
}
