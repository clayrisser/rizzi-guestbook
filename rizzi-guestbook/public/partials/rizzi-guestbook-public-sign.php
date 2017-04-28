<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       http://example.com
 * @since      4.0.0
 *
 * @package    Rizzi_Guestbook
 * @subpackage Rizzi_Guestbook/public/partials
 */

  $commenter = wp_get_current_commenter();
  $user = wp_get_current_user();
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div class="rizzi-guestbook-sign">
  <form action="<?php echo get_option("siteurl") ?>/wp-comments-post.php" method="POST" id="commentform">
    <?php if ($user->ID) { ?>
      <input type="text" name="author" id="author" value="<?php echo $user->display_name ?>" disabled="disabled" />
    <?php } else { ?>
      <input type="text" name="author" id="author" placeholder="Your Name" value="<?php echo $commenter['comment_author'] ?>" />
    <?php } ?>

    <?php if ($user->ID) { ?>
      <input type="text" name="email" id="email" value="<?php echo $user->user_email ?>" disabled="disabled" />
    <?php } else { ?>
      <input type="text" name="email" id="email" placeholder="email@example.com" value="<?php echo $commenter['comment_author_email'] ?>" />
    <?php } ?>

    <textarea name="comment" id="comment"></textarea>

    <input name="submit" type="submit" id="submit" value="<?php _e('Submit', 'rizzi-guestbook') ?>" />
    <input type="hidden" name="comment_post_ID" value="<?php echo $GLOBALS['id']?>" />
    <input type="hidden" name="redirect_to" value="<?php echo htmlspecialchars(get_permalink()) ?>" />
  </form>
</div>
