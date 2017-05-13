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

$comments = get_comments( array('post_id' => $post->ID, 'order' => 'ASC' ) );
if ( count($comments) <= 0 ) echo __('No entries yet. Be the first to sign the Guestbook', 'rizzi-guestbook');
$edit_name = __('Edit', 'rizzi-guestbook');
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div class="rizzi-guestbook-display">
  <?php foreach($comments as $comment) { ?>
    <?php if ($comment->comment_author_uri) { ?>
      <div>
        <span class="author">
          <a href="<?php echo $comment->comment_author_uri ?>">
            <?php echo $comment->comment_author ?>
          </a>
        </span>
        <span class="edit">
          <?php echo edit_comment_link($edit_name) ?>
        </span>
      </div>
    <?php } else { ?>
      <div>
        <span class="author">
          <?php echo $comment->comment_author ?>
        </span>
        <span class="edit">
          <?php echo edit_comment_link($edit_name) ?>
        </span>
      </div>
    <?php } ?>
    <div>
      <?php if ($comment->comment_approved) { ?>
        <?php echo $comment->comment_content ?>
      <?php } else { ?>
        This entry is awaiting moderation.
      <?php } ?>
    </div>
    <div class="date">
      <?php echo get_comment_date('m/j/y') ?>
    </div>
    <hr />
  <?php } ?>
</div>
