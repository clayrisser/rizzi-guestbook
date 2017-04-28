<?php

/**
 * Provide a public-facing header for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       http://example.com
 * @since      4.0.0
 *
 * @package    Rizzi_Guestbook
 * @subpackage Rizzi_Guestbook/public/partials
 */

?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div class="rizzi-guestbook-header">
  <?php if (isset($_GET["sign"])) { ?>
    Show Guestbook | <a href="<?php echo remove_query_arg('sign', $_SERVER['REQUEST_URI']) ?>">Sign Guestbook</a>
  <?php } else { ?>
    <a href="<?php echo add_query_arg(array('sign' => '1'), $_SERVER['REQUEST_URI']) ?>">Show Guestbook</a> | Sign Guestbook
  <?php } ?>
</div>
