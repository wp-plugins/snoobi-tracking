<?php
/*
Plugin Name: Snoobi Tracking
Author: Jussi Ruokom&auml;ki
Version: 0.1
Author URI: http://jussi.ruokomaki.fi/
Description: Track your visitors with Snoobi web analytics. <a href="options-general.php?page=blog-introduction.php">Add the name of your Snoobi account to get started.</a> 
*/

$snoobi_account_data = 0;
add_option('snoobi_account', $snoobi_account_data);
$snoobi_account = get_option('snoobi_account');


function get_snoobi_tracking_code() {
  global $snoobi_account;

  $str = "";
  
  $str .= '<!-- BEGIN Snoobi v1.4 -->' . "\n";
  $str .= '<script type="text/javascript" src="' . ($_SERVER["HTTPS"] == "on" ? 'https' : 'http' ). '://eu1.snoobi.com/snoop.php?tili=' . $snoobi_account . '">' . "\n";
  $str .= '</script>' . "\n";
  $str .= '<!-- END Snoobi v1.4 -->' . "\n";

  return $str;
} // ends get_snoobi_tracking_code


function print_snoobi_tracking_code() { 
  global $snoobi_account;

  if ($snoobi_account) {
    echo get_snoobi_tracking_code();
  } 
} // ends bintro_print


function bintro_add_options_page() {
  add_options_page('Snoobi Tracking', 'Snoobi Tracking', 8, basename(__FILE__), 'snoobi_tracking_options_page');
}

function snoobi_tracking_options_page() { ?>

<div class="wrap">

  <h2><?= __('Snoobi Tracking') . _e('Settings') ?></h2>

  <form method="post" action="options.php">

    <?php wp_nonce_field('update-options'); ?>

    <p class="submit">
      <input type="submit" name="Submit" value="<?php _e('Save Changes') ?>" />
    </p>

    <table border="0" style="vertical-align: top;" cellspacing="10">
      <tr>
       <td style="vertical-align: top;"><strong><?= __('Snoobi ID:') ?></strong></td>
       <td style="vertical-align: top;">
         <input type="text" name="snoobi_account" value="<?= get_option('snoobi_account') ?>" /> 
       </td>
       <td style="vertical-align: top;"><span style="color: #999"><?= __("/"example_com/"") ?></span></td>
      </tr>
	</table>
	
    <input type="hidden" name="action" value="update" />

    <input type="hidden" name="page_options" value="snoobi_account" />

    <p class="submit">
      <input type="submit" name="Submit" value="<?php _e('Save Changes') ?>" />
    </p>

  </form>

</div>

<?php
}


add_action('wp_footer', 'bintro_print');

add_action('admin_menu', 'snoobi_tracking_options_page');
