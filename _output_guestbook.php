<?php
//Include the comment-upload handler plugin
//ECU Code require_once('easy-comment-uploads/main.php');
//Include the diggstyle pagination
require_once('_diggstyle.php');


/**
  * This is the only function you need to generate a WP-Viper Guestbook - simply
  * "echo vgb_GetGuestbook()" in any of your templates.
  * 
  * Note: Templates that use this guestbook should NOT call comments_template().
  * Note: Requires the_post() to've been called already, i.e. we're in The Loop.
  * Note: See the $defaults array for available options.
  */
function vgb_GetGuestbook( $opts=array() )
{
    $defaults = array(
        'entriesPerPg' => 10,       //Number if entries to show per page
        'reverseOrder' => false,    //Reverse the order of entries (oldest first)
        'allowUploads' => false,    //Allow users to upload images
        'maxImgSizKb'  => 50,       //Max uploadable image size (if allowUploads is set)
        'showBrowsers' => true,     //Show browser/OS icons in guestbook entries  
        'showFlags'    => true,     //Show national flags in guestbook entries (REQUIRES OZH IP2NATION)
        'hideCred'     => false,    //Omit "Powered by WP-ViperGB" (please don't though :))
        'showCredLink' => false,    //Include a link to the project page in the "Powered by WP-ViperGP" (would be appreciated :))
        'disallowAnon' => false,	//Don't allow anonymous signatures (aka only logged-in users can sign)
        'diggPagination'=>false		//Use Digg-style pagination (rather than this plugin's original style)
         );       
    $opts = wp_parse_args( $opts, $defaults );

    if( vgb_is_listing_pg() )   return vgb_get_listing_pg($opts);
    else                        return vgb_get_sign_pg($opts);
}


/********************************************************************************/
/******************************IMPLEMENTATION************************************/
/********************************************************************************/

//PHP Arguments
define('VB_SIGN_PG_ARG', 'sign');       //"Sign" page (vs "Listing" page)
define('VB_PAGED_ARG', 'cpage');        //Paged Comments pagenumber


/**
  * Return true if this is the LISTING page, false if it's the SIGN page
  */
function vgb_is_listing_pg()
{
    return !isset($_REQUEST[VB_SIGN_PG_ARG]);
}


/**
  * Return the URL to the plugin directory, with trailing slash
  */
function vgb_get_data_url()
{
    return plugins_url(dirname(plugin_basename(__FILE__))) . '/';
}


/**
  * Return the current page number (in listing view)
  */
function vgb_get_current_page_num()
{
    return max(get_query_var(VB_PAGED_ARG), 1);
}


/**
  * Get the header: Show Guestbook | Sign Guestbook, and *maybe* paged nav links
  */

function vgb_get_header( $itemTotal, $entriesPerPg, $diggPagination )
{
    global $wpdb;
    $rgb_sign_page_name = $wpdb->get_row("SELECT * FROM $wpdb->options WHERE option_name = 'rgb_sign_page'", ARRAY_A);
    $rgb_sign_page_value = $rgb_sign_page_name['option_value'];

    global $wpdb;
    $rgb_show_page_name = $wpdb->get_row("SELECT * FROM $wpdb->options WHERE option_name = 'rgb_show_page'", ARRAY_A);
    $rgb_show_page_value = $rgb_show_page_name['option_value'];

    if ($rgb_show_page_value == NULL || $rgb_show_page_value == "") {
        $rgb_show_page = "Show Guestbook";
    } else {
        $rgb_show_page = $rgb_show_page_value;
    }

    if ($rgb_sign_page_value == NULL || $rgb_sign_page_value == "") {
        $rgb_sign_page = "Sign Guestbook";
    } else {
        $rgb_sign_page = $rgb_sign_page_value;
    }

    //Comment
    global $vgb_name, $vgb_version;
    $retVal = "<!-- $vgb_name v$vgb_version -->\n";
        
    //Show Guestbook | Sign Guestbook
    $isListingPg = vgb_is_listing_pg();
    $retVal .= '<div id="gbHeader">';
    $retVal .= '<div id="gbNavLinks">';
    if( !$isListingPg ) $retVal .= "<a href=\"".get_permalink()."\" target='_self'>";
    $retVal .= __($rgb_show_page, WPVGB_DOMAIN);
    if( !$isListingPg ) $retVal .= "</a>";
    $retVal .= " | ";
    if( $isListingPg ) $retVal .= "<a href=\"".htmlspecialchars(add_query_arg(VB_SIGN_PG_ARG, 1))."\" target='_self'>";
    $retVal .= __($rgb_sign_page, WPVGB_DOMAIN);
    if( $isListingPg ) $retVal .= "</a>";
    $retVal .= "</div>";

//For Digg-style pagination
	if($diggPagination == 1)
	{
    	$retVal .= '<div id="gbPageLinks">';
    	if ($isListingPg)
	        $retVal .= $itemTotal . ' ' . __('entries',WPVGB_DOMAIN);
	    $retVal .= "</div>";
	    $retVal .= "</div>";
    }	
	
    //Paged/paginated nav links
    if($isListingPg && $itemTotal > $entriesPerPg)
    {
        $curPage = vgb_get_current_page_num();
        $maxPages = ceil($itemTotal/$entriesPerPg);
        if($diggPagination == 0) $retVal .= '<div id="gbPageLinks">' . __('Page',WPVGB_DOMAIN) . ': ';
        if( $maxPages > 1 )
        {
        	//Original-style paged nav links
        	if( $diggPagination == 0 )
			{
            	for( $i = 1; $i <= $maxPages; $i++ )
            	{
                	if( $curPage == $i || (!$curPage && $i==1) ) $retVal .= "(" . $i . ") ";
                	else                                         $retVal .= "<a href=\"".htmlspecialchars(add_query_arg(VB_PAGED_ARG, $i))."\">$i</a> ";
            	}
			}
			//Digg-style paginated nav links
			else
			{
				//Digg-Style Pagination
            	$retVal .= '<div style="text-align:center">';
            	$retVal .= getPaginationString($curPage, $itemTotal, $entriesPerPg, 1, get_permalink(), '?cpage=');
            	$retVal .= '</div>';
			}
        }
        if($diggPagination == 0) $retVal .= "</div>";
    }
    if($diggPagination == 0) $retVal .= "</div>";
    return $retVal;
}



/*************************************************************************/
/************************Output the LISTINGS PAGE*************************/
/*************************************************************************/
function vgb_get_listing_pg($opts)
{
    //Capture output
    ob_start();
    
    //First, get the comments and make sure we have some
    global $comment, $post;
    $comments = get_comments( array('post_id' => $post->ID, 'order' => ($opts['reverseOrder']?'ASC':'DESC') ) );
    $commentTotal = count($comments);
    
    //Output the header
    echo vgb_get_header($commentTotal, $opts['entriesPerPg'], $opts['diggPagination']);
    
    //Check for "no entries"
    if( $commentTotal == 0 ) {
        echo '<div id="gbNoEntriesWrap">
             <hr>' . __('No entries yet', WPVGB_DOMAIN) . '. &nbsp;Be the first to sign the Guestbook.<hr></div>';
        global $wpdb;
        $rgb_show_powered_by_name = $wpdb->get_row("SELECT * FROM $wpdb->options WHERE option_name = 'rgb_show_powered_by'", ARRAY_A);
        $rgb_show_powered_by_value = $rgb_show_powered_by_name['option_value'];
        if( $rgb_show_powered_by_value == NULL ) {
          echo "Powered by <b><a href='http://software.jamrizzi.com/store/products/rizzi-guestbook/' target='_blank'>Rizzi Guestbook</a></b>.";
        }
    }
    else {
    
    //Take a SLICE of the comments array corresponding to the current page
    $curPage = vgb_get_current_page_num();
    $comments = array_slice($comments, ($curPage-1)*$opts['entriesPerPg'], $opts['entriesPerPg']);
    $commentCounter = $commentTotal - ($curPage-1)*$opts['entriesPerPg'];
   
    //And output each comment!
    ?>
<hr>
    <div id="gbEntriesWrap">
    <?php foreach( $comments as $comment ): ?>

<b><?php echo $comment->comment_author?></b>&nbsp;<?php edit_comment_link('<i><small>Edit</small></i>', '');?>

<?php
       if( $comment->comment_approved == 1 ) comment_text();
       else                                  echo __('<br />This entry is awaiting moderation.<br /><br />',WPVGB_DOMAIN);
       ?>

<i>
<div class="rg_time1">
<?php echo get_comment_date(__('F j, Y',WPVGB_DOMAIN))?>
</div>

<div class="rg_time2">
<?php echo get_comment_date(__('m-j-y',WPVGB_DOMAIN))?>
</div>

<div class="rg_time3">
<?php echo get_comment_date(__('m/j/y',WPVGB_DOMAIN))?>
</div>

<div class="rg_time4">
<?php echo get_comment_date(__('F j, Y',WPVGB_DOMAIN))?>
<?php echo '&nbsp;-&nbsp;'?>
<?php echo get_comment_time(__('g:i a',WPVGB_DOMAIN))?>
</div>

<div class="rg_time5">
<?php echo get_comment_date(__('m-j-y',WPVGB_DOMAIN))?>
<?php echo '&nbsp;-&nbsp;'?>
<?php echo get_comment_time(__('g:i a',WPVGB_DOMAIN))?>
</div>

<div class="rg_time6">
<?php echo get_comment_date(__('m/j/y',WPVGB_DOMAIN))?>
<?php echo '&nbsp;-&nbsp;'?>
<?php echo get_comment_time(__('g:i a',WPVGB_DOMAIN))?>
</div>
</i>

<hr>

    <?php endforeach; ?>

<?php
    global $wpdb;
    $rgb_show_powered_by_name = $wpdb->get_row("SELECT * FROM $wpdb->options WHERE option_name = 'rgb_show_powered_by'", ARRAY_A);
    $rgb_show_powered_by_value = $rgb_show_powered_by_name['option_value'];
    if( $rgb_show_powered_by_value == NULL ) {
      echo "Powered by <b><a href='http://software.jamrizzi.com/store/products/rizzi-guestbook/' target='_blank'>Rizzi Guestbook</a></b>.";
    }
?>

<?php
    
    //if( $commentTotal == 0 ):
    }
    
    //Stop capturing output and return
    $output_string=ob_get_contents();
    ob_end_clean();
    return $output_string;
}


/*************************************************************************/
/********************Output the SIGN GUESTBOOK page***********************/
/*************************************************************************/
function vgb_get_sign_pg($opts)
{
    //Get the captcha key
    global $wpdb;
    $rgb_captcha_key_name = $wpdb->get_row("SELECT * FROM $wpdb->options WHERE option_name = 'rgb_captcha_key'", ARRAY_A);
    $rgb_captcha_key_value = $rgb_captcha_key_name['option_value'];

    //Check captcha settings
    global $wpdb;
    $rgb_use_captcha_name = $wpdb->get_row("SELECT * FROM $wpdb->options WHERE option_name = 'rgb_use_captcha'", ARRAY_A);
    $rgb_use_captcha_value = $rgb_use_captcha_name['option_value'];


    //Get the current user (if logged in)
    $user = wp_get_current_user();
    if ( empty( $user->display_name ) ) $user->display_name=$user->user_login;
        
    //If not, we'll try to use info from the cookie to pre-fill in the fields
    $commenter = wp_get_current_commenter();
    
    //Capture output
    ob_start();
    
    //Output the header
    echo vgb_get_header(0, $opts['entriesPerPg'], $opts['diggPagination']);
    
    //And output the page!
   ?>


   <div id="gbSignWrap" class="page-nav">


<?php
    
    $captcha = $_POST["g-recaptcha-response"];

    if (($captcha == NULL || $captcha == "") && $rgb_use_captcha_value == 1) {
echo @"<br />
   <div id='gbSignWrap' class='page-nav'>
    <script src='https://www.google.com/recaptcha/api.js' async defer></script>
    <form action='?sign=1' method='post'>
      <div class='g-recaptcha' data-sitekey='". $rgb_captcha_key_value .@"'></div>
      <br/>
      <input type='submit' value='Continue'>
    </form><br />";
    global $wpdb;
    $rgb_show_powered_by_name = $wpdb->get_row("SELECT * FROM $wpdb->options WHERE option_name = 'rgb_show_powered_by'", ARRAY_A);
    $rgb_show_powered_by_value = $rgb_show_powered_by_name['option_value'];
    if( $rgb_show_powered_by_value == NULL ) {
      echo "Powered by <b><a href='http://software.jamrizzi.com/store/products/rizzi-guestbook/' target='_blank'>Rizzi Guestbook</a></b>.";
    }
    } else {

?>

<form action="<?php echo get_option("siteurl")?>/wp-comments-post.php" method="POST" id="commentform">

<hr>
     <?php if( $opts['disallowAnon'] && !$user->ID ) : 
     	_e('Sorry, but only registered users are allowed to sign this guestbook.<br />Please create a user account, or login to sign.',WPVGB_DOMAIN);
	else: ?>
	
     <!-- Name/Email/Homepage section -->
    <div class="name-email-homepage">
        <?php _e('*Name', WPVGB_DOMAIN);
 
  ?>:&nbsp;&nbsp;&nbsp;

        <?php if($user->ID):?> <input type="text" name="author" id="author" value="<?php echo $user->display_name?>" disabled="disabled" size="30" maxlength="40" />
        <?php else:         ?> <input type="text" name="author" id="author" value="<?php echo $commenter['comment_author']?>" size="30" maxlength="40" />
        <?php endif; ?>
        <?php if(!$opts['disallowAnon']) _e('', WPVGB_DOMAIN); ?><br /><br />

        <?php _e('*Email', WPVGB_DOMAIN)?>:&nbsp;&nbsp;&nbsp;

        <?php if($user->ID):?> <input type="text" name="email" id="email" value="<?php echo $user->user_email?>" disabled="disabled" size="30" maxlength="40" />
        <?php else:         ?> <input type="text" name="email" id="email" value="<?php echo $commenter['comment_author_email']?>" size="30" maxlength="40" />
        <?php endif; ?>
        <?php if(!$opts['disallowAnon']) _e('', WPVGB_DOMAIN); ?><br />

        <div id="rg-homepage">
        <br />
        <?php _e('Homepage', WPVGB_DOMAIN)?>:&nbsp;&nbsp;&nbsp;

        <?php if($user->ID):?> <input type="text" name="url" id="url" value="<?php echo $user->user_url?>" disabled="disabled" size="30" />
        <?php else:         ?> <input type="text" name="url" id="url" value="<?php echo esc_url($commenter['comment_author_url'])?>" size="30" />
        <?php endif; ?>
        <?php if(!$opts['disallowAnon']) _e('', WPVGB_DOMAIN); ?>
        </div>

           <?php
           remove_action('comment_form', 'show_subscription_checkbox');
           remove_action('comment_form', 'subscribe_reloaded_show');
           remove_action('comment_form', 'jfb_show_comment_button');
           global $post;
           do_action('comment_form', $post->ID);
           ?>

        <?php if( $user->ID && !$opts['disallowAnon'] ) echo __("If you'd like to customize these values, please ", WPVGB_DOMAIN) . "<b><a href=\"". wp_logout_url( $_SERVER['REQUEST_URI'] ) . "\">" . __("Logout", WPVGB_DOMAIN) . "</a></b>.<br /><br />"; ?>
     <hr />
        </div>
        <!-- End Name/Email section -->
     
     <!-- Text section -->
     <div id="gbSignText">
       <?php _e('*Message', WPVGB_DOMAIN)?>:<br />
       <textarea name="comment" id="comment" rows="12" cols="45" style="width: 100%"></textarea><br /><br />
       <input name="submit" type="submit" id="submit" value="<?php _e('Submit', WPVGB_DOMAIN)?>" />
       <input type="hidden" name="comment_post_ID" value="<?php echo $GLOBALS['id']?>" />
       <input type='hidden' name='redirect_to' value='<?php echo htmlspecialchars(get_permalink()) ?>' />
     </div><br />
     <!-- EndText area section -->

<?php
    global $wpdb;
    $rgb_show_powered_by_name = $wpdb->get_row("SELECT * FROM $wpdb->options WHERE option_name = 'rgb_show_powered_by'", ARRAY_A);
    $rgb_show_powered_by_value = $rgb_show_powered_by_name['option_value'];
    if( $rgb_show_powered_by_value == NULL ) {
      echo "Powered by <b><a href='http://software.jamrizzi.com/store/products/rizzi-guestbook/' target='_blank'>Rizzi Guestbook</a></b>.";
    }
?>

     <?php endif; ?>
    </form>

<?php

    }

    if( $opts['allowUploads'] ):
      ?>
      <!-- Image Upload section: -->  
      <div id="gbSignUpload">  
        <?php
        /*ECU Code
           update_option('ecu_max_file_size', $opts['maxImgSizKb']);
           update_option('ecu_images_only', true);
           $msg = sprintf(__("Add photo (max %dkb)", WPVGB_DOMAIN), $opts['maxImgSizKb']) . ":";
           ecu_upload_form_core($msg);
           ecu_upload_form_preview();
		*/ 
        ?>
      </div>       
      <!-- End Image Upload section -->
    <?php endif;?>  
   </div>
   <?php
   
   //Stop capturing output and return
   $output_string=ob_get_contents();
   ob_end_clean();
   return $output_string;
}

?>