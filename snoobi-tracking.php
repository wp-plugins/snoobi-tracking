<?php
/*
Plugin Name: Snoobi Tracking
Plugin URI: http://wordpress.org/extend/plugins/snoobi-tracking/
Author: Jussi Ruokom&auml;ki
Version: 1.0.1
Author URI: http://jussi.ruokomaki.fi/
Description: Track your visitors with Snoobi web analytics. <a href="options-general.php?page=blog-introduction.php">Add the name of your Snoobi account to get started.</a> 
*/

$snoobi_account_id_data = '';
add_option('snoobi_account_id', $snoobi_account_id_data);
$snoobi_account_id = get_option('snoobi_account_id');

$snoobi_admin_tracking_data = 0;
add_option('snoobi_admin_tracking', $snoobi_admin_tracking_data);
$snoobi_admin_tracking = get_option('snoobi_admin_tracking');


function get_snoobi_tracking_code() {
  global $snoobi_account_id;

  $str = "";
  
  $str .= '<!-- BEGIN Snoobi v1.4 -->' . "\n";
  $str .= '<script type="text/javascript" src="' . ($_SERVER["HTTPS"] == "on" ? 'https' : 'http' ). '://eu1.snoobi.com/snoop.php?tili=' . $snoobi_account_id . '">' . "\n";
  $str .= '</script>' . "\n";
  $str .= '<!-- END Snoobi v1.4 -->' . "\n";

  return $str;
} // ends get_snoobi_tracking_code


function print_snoobi_tracking_code() { 
  global $snoobi_account_id, $snoobi_admin_tracking;

  if ($snoobi_account_id && // account id is set
      (!current_user_can('edit_users') || $snoobi_admin_tracking) && // is not admin or admin tracking is set
	  !is_preview()) { // is not a preview
    echo get_snoobi_tracking_code();
  } 
} // ends print_snoobi_tracking_code


function snoobi_tracking_add_options_page() {
  add_options_page('Snoobi Tracking', 'Snoobi Tracking', 8, basename(__FILE__), 'snoobi_tracking_options_page');
}

function snoobi_tracking_options_page() { ?>

<div class="wrap">

  <h2><?= _e('Settings') . ': ' . __('Snoobi Tracking') ?></h2>

  <form method="post" action="options.php">

    <?php wp_nonce_field('update-options'); ?>

    <!--<p class="submit">
      <input type="submit" name="Submit" value="<?php _e('Save Changes') ?>" />
    </p>-->

    <table border="0" style="vertical-align: top;" cellspacing="10">
      <tr>
       <td style="vertical-align: top;"><strong><?= __('Snoobi ID:') ?></strong></td>
       <td style="vertical-align: top;">
         <input type="text" name="snoobi_account_id" value="<?= get_option('snoobi_account_id') ?>" /> 
       </td>
       <!--<td style="vertical-align: top;"><span style="color: #999"><?= __("'example_com'") ?></span></td>-->
      </tr>
      <tr>
       <td style="vertical-align: top;"><?= __('Track administrators too:') ?></td>
       <td style="vertical-align: top;">
         <input type="radio" name="snoobi_admin_tracking" value="1" <?= get_option('snoobi_admin_tracking') == '1' ? 'checked="checked"' : ''  ?> /> <?= __('Yes') ?><br />
         <input type="radio" name="snoobi_admin_tracking" value="0" <?= get_option('snoobi_admin_tracking') == '0' ? 'checked="checked"' : ''  ?> /> <?= __('No') ?><br />
       </td>
       <!--<td style="vertical-align: top;"><span style="color: #999"></span></td>-->
      </tr>
	</table>
	
    <input type="hidden" name="action" value="update" />

    <input type="hidden" name="page_options" value="snoobi_account_id,snoobi_admin_tracking" />

    <p class="submit">
      <input type="submit" name="Submit" value="<?php _e('Save Changes') ?>" />
    </p>

  </form>

</div>

<?php
} // ends snoobi_tracking_options_page


add_action('wp_footer', 'print_snoobi_tracking_code');

add_action('admin_menu', 'snoobi_tracking_add_options_page');
