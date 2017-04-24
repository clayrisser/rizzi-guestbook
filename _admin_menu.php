<?php

//Set default options.
add_option($opt_vgb_items_per_pg, 10);
add_option($opt_vgb_max_upload_siz, 50);
add_option($opt_vgb_no_anon_signers, false);
add_option($opt_vgb_show_browsers, true);
add_option($opt_vgb_show_flags, true);
add_option($opt_vgb_style, "Default");
add_option($opt_vgb_show_cred_link, false);
add_option($opt_vgb_digg_pagination, false);

/*
 * Tell WP about the Admin page
 */
add_action('admin_menu', 'vgb_add_admin_page', 99);
function vgb_add_admin_page()
{ 
    add_options_page('Rizzi Guestbook Pro Options', 'Rizzi Guestbook Pro', 'administrator', "rizzi-guestbook", 'vgb_admin_page');
}

/**
  * Link to Settings on Plugins page 
  */
add_filter('plugin_action_links', 'vgb_add_plugin_links', 10, 2);
function vgb_add_plugin_links($links, $file)
{
    if( dirname(plugin_basename( __FILE__ )) == dirname($file) )
        $links[] = '<a href="options-general.php?page=' . "rizzi-guestbook" .'">' . __('Settings',WPVGB_DOMAIN) . '</a>';
    return $links;
}


/*
 * Output the Admin page
 */
function vgb_admin_page()
{
    global $vgb_name, $vgb_homepage, $vgb_version;
    global $opt_vgb_page, $opt_vgb_style, $opt_vgb_reverse, $opt_vgb_allow_upload, $opt_rgb_homepage_field, $opt_rgb_captcha_key, $opt_rgb_use_captcha, $rgb_hide_value_section, $opt_rgb_sign_page, $opt_rgb_show_page, $opt_rgb_show_powered_by;
    global $opt_vgb_items_per_pg, $opt_vgb_max_upload_siz;
	global $opt_vgb_no_anon_signers;
    global $opt_vgb_show_browsers, $opt_vgb_show_flags, $opt_vgb_show_cred_link;
    global $opt_vgb_hidesponsor, $opt_vgb_digg_pagination;
    ?>
    <div class="wrap">
      <?php
      if( isset($_POST['opts_updated']) )
      {
          update_option( $opt_vgb_page, $_POST[$opt_vgb_page] );
          update_option( $opt_vgb_style, $_POST[$opt_vgb_style] );
          update_option( $opt_vgb_items_per_pg, $_POST[$opt_vgb_items_per_pg] );
          update_option( $opt_vgb_reverse, $_POST[$opt_vgb_reverse] );
          update_option( $opt_rgb_homepage_field, $_POST[$opt_rgb_homepage_field] );
          update_option( $opt_rgb_show_powered_by, $_POST[$opt_rgb_show_powered_by] );
          update_option( $opt_rgb_sign_page, $_POST[$opt_rgb_sign_page] );
          update_option( $opt_rgb_show_page, $_POST[$opt_rgb_show_page] );
          update_option( $rgb_hide_value_section, $_POST[$rgb_hide_value_section] );
          update_option( $opt_rgb_use_captcha, $_POST[$opt_rgb_use_captcha] );
          update_option( $opt_rgb_captcha_key, $_POST[$opt_rgb_captcha_key] );
          update_option( $opt_vgb_allow_upload, $_POST[$opt_vgb_allow_upload] );
          update_option( $opt_vgb_max_upload_siz, $_POST[$opt_vgb_max_upload_siz] );
		  update_option( $opt_vgb_no_anon_signers, $_POST[$opt_vgb_no_anon_signers] );
          update_option( $opt_vgb_show_browsers, $_POST[$opt_vgb_show_browsers] );
          update_option( $opt_vgb_show_flags, $_POST[$opt_vgb_show_flags] );
          update_option( $opt_vgb_show_cred_link, $_POST[$opt_vgb_show_cred_link] );
		  update_option( $opt_vgb_digg_pagination, $_POST[$opt_vgb_digg_pagination] );
          ?><div class="updated"><p><strong><?php _e('Options saved.', WPVGB_DOMAIN ); ?></strong></p></div><?php
      }
      if( isset($_REQUEST[$opt_vgb_hidesponsor]) )
          update_option($opt_vgb_hidesponsor, $_REQUEST[$opt_vgb_hidesponsor]);
      ?>
      <h2 style="clear: none">
         <?php _e('Rizzi Guestbook Pro Options', WPVGB_DOMAIN) ?>
         <?php if( get_option($opt_vgb_page) ): ?>
         <?php endif;?>
      </h2>
<hr /> 
    <center><?php
 $image = "//".$_SERVER['HTTP_HOST']."/wp-content/plugins/rizzi-guestbook-pro/img/rizzi-guestbook.png";
 echo "<a href='http://software.jamrizzi.com/store/products/rizzi-guestbook/' target='_blank'><img src='".$image."' width='100%'></a>";
 ?><br />
    <h2><a href="edit-comments.php?p=<?php echo get_option($opt_vgb_page)?>"><?php _e('Manage Entries', WPVGB_DOMAIN) ?> &raquo;</a></h2></center>
      <?php
           _e('
<hr />
<h2>Instructions:</h2>
<h4>To add a guestbook to your website, create a new page, select it in the first combo box below, and click <u>Save Settings</u>.', WPVGB_DOMAIN) ?><br /><br />
        Click <a href='http://support.jamrizzi.com' target='_blank'>HERE</a> to get official support from JamRizzi Technologies.</h4>
      <hr />
      
      <h2><?php _e('Main Settings', WPVGB_DOMAIN) ?>:</h2>
      <form name="formOptions" method="post" action="">

        <h3><?php _e('Guestbook Page', WPVGB_DOMAIN) ?><span style="word-spacing:9px;">:</span>
        <select style="width:150px;" name="<?php echo $opt_vgb_page?>">
          <?php
            $pages = get_pages(array('post_status'=>'publish,private'));  
            $vgb_page = get_option($opt_vgb_page);
            echo '<option value="0" selected>&lt;None&gt;</option>';
            foreach($pages as $page)
               echo '<option value="'.$page->ID.'"'. ($page->ID==$vgb_page?' selected':'').'>'.$page->post_title.'</option>'."\n";
          ?>
        </select>
          <br /><br />
        <input type="checkbox" name="<?php echo $opt_vgb_no_anon_signers?>" value="1" <?php echo get_option($opt_vgb_no_anon_signers)?'checked="checked"':''?> /> <?php _e('Only Allow Registered Users',WPVGB_DOMAIN)?><br /><br />
        <input type="checkbox" name="<?php echo $opt_rgb_homepage_field?>" value="1" <?php echo get_option($opt_rgb_homepage_field)?'checked="checked"':''?> /> <?php _e('Hide Homepage Field', WPVGB_DOMAIN)?>
        </h3>
        <hr />

        <h2><?php _e('Display Settings', WPVGB_DOMAIN)?>:</h2>
        <h3> <?php _e('Entries per Page: ', WPVGB_DOMAIN)?><input type="text" size="3" name="<?php echo $opt_vgb_items_per_pg?>" value="<?php echo get_option($opt_vgb_items_per_pg) ?>" /><br /><br />
        <?php _e('Date Stamp', WPVGB_DOMAIN) ?><span style="word-spacing:3px">:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
        <select style="width:53px;" name="<?php echo $opt_vgb_style?>">
          <?php
             $stylesDir = opendir(dirname(__FILE__) . "/styles");
             while ($file = readdir($stylesDir))
             {
                if( ($fileEnding = strpos($file, '.css'))===FALSE ) continue;
                $styleName = substr($file, 0, $fileEnding);
                echo '<option value="'.$styleName.'"'. ($styleName==get_option($opt_vgb_style)?' selected':'').'>'.$styleName.'</option>'."\n";
             }
             closedir($stylesDir);

          ?></h3>
        </select>
          <h4>
            A = Month DD, YYYY<br />
            B = MM-DD-YY<br />
            C = MM/DD/YY<br />
            D = Month DD, YYYY - MM:HH<br />
            E = MM-DD-YY - MM:HH<br />
            F = Month DD, YYYY - MM:HH<br />
            G = None</h4>
            
        <h3><input type="checkbox" name="<?php echo $opt_vgb_reverse?>" value="1" <?php echo get_option($opt_vgb_reverse)?'checked="checked"':''?> /> <?php _e('List Oldest to Newest', WPVGB_DOMAIN)?><br /><br />
        <input type="checkbox" name="<?php echo $opt_rgb_show_powered_by?>" value="1" <?php echo get_option($opt_rgb_show_powered_by)?'checked="checked"':''?> /> <?php _e('Hide <i>Powered By JamRizzi Technologies</i>', WPVGB_DOMAIN)?><br /><br />
        <input type="checkbox" name="<?php echo $rgb_hide_value_section?>" value="1" <?php echo get_option($rgb_hide_value_section)?'checked="checked"':''?> /> <?php _e('Just Show Message Box', WPVGB_DOMAIN)?>
        </h3>
        <h4>*Make sure <u>Only Allow Registered Users</u> is enabled when you <u>Just Show Message Box</u>.</h4>

        <hr />
        <h2>Page Names</h2>
        <h3><?php _e('Show Page', WPVGB_DOMAIN)?>:&nbsp;&nbsp;<input type="text" name="<?php echo $opt_rgb_show_page?>" value="<?php echo get_option($opt_rgb_show_page)?>" style="width:200px" />
        <h3><?php _e('Sign Page', WPVGB_DOMAIN)?>:&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="<?php echo $opt_rgb_sign_page?>" value="<?php echo get_option($opt_rgb_sign_page)?>" style="width:200px" /><br />
        <h4>*The default names, Show Guestbook and Sign Guestbook, will be displayed when these fields are empty.</h4>

        <hr />
        <h2>reCaptcha</h2>
        <h3><?php _e('Site Key', WPVGB_DOMAIN)?>:&nbsp;&nbsp;<input type="text" name="<?php echo $opt_rgb_captcha_key?>" value="<?php echo get_option($opt_rgb_captcha_key)?>" style="width:375px" /><br /><br />
        <input type="checkbox" name="<?php echo $opt_rgb_use_captcha?>" value="1" <?php echo get_option($opt_rgb_use_captcha)?'checked="checked"':''?> /> <?php _e('Enable reCaptcha', WPVGB_DOMAIN)?></h3>
        <h4>*Make sure this is disabled when you do not have a valid key. &nbsp;To get a Google reCaptcha Key, click <a href="http://www.google.com/recaptcha/intro/index.html" target="_blank">HERE</a>.</h4>

        <hr />
        <h3><input type="checkbox" name="<?php echo $opt_vgb_digg_pagination?>" value="1" <?php echo get_option($opt_vgb_digg_pagination)?'checked="checked"':''?> /> <?php _e('Use Digg-style pagination', WPVGB_DOMAIN)?><br /></h3>
        <h4>It is strongly recommended that you do not use Digg-style pagination because it is buggy. &nbsp;This option is only here to let users turn it off if it was already enabled.</h4>
        <input type="hidden" name="opts_updated" value="1" />
        <span class="submit"><input type="submit" name="Submit" value="<?php _e('Save Settings',WPVGB_DOMAIN)?>" /></div>
      </form>
    <br />    
    <hr />  

This plugin was created by JamRizzi Technologies. &nbsp;For more information, visit <a href="http://jamrizzi.com" target="_blank">jamrizzi.com</a>.


    <?php
}
?>