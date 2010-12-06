<?php

/*----------------------------------------------------------------
 * Plug-in settings
 *----------------------------------------------------------------*/

class SL_Related_Posts_Settings
{
  static $menu_title='SL::RelatedPosts';
  static $page_title='SLRelatedPostsPlugIn Settings';
  static $unique_handle='sl_related_posts_settings';
  static $capability='administrator';

  static function initialize()
  {
    if(function_exists('add_action'))
    {
      add_action('admin_head', 'SL_Related_Posts_Settings::add_styles');
      add_action('admin_menu', 'SL_Related_Posts_Settings::add_menu_item');
    }

    // Arguments: tag, function, priority (default is 10), accepted_args (default is 1)
    if(function_exists('add_filter'))
      add_filter('plugin_action_links', 'SL_Related_Posts_Settings::update_plugin_links', 10, 2);
  }

  static function add_styles()
  {
    $s=WP_PLUGIN_URL.'/'.dirname(plugin_basename(__FILE__)).'/sl-related-posts-settings.css';
    echo '<link rel="stylesheet" href="'.$s.'" type="text/css" media="all" />'."\n";
  }

  static function add_menu_item()
  {
    // Arguments: page_title, menu_title, capability, handle/file, function
    add_options_page(
      self::$page_title,
      self::$menu_title,
      self::$capability,
      self::$unique_handle,
      'SL_Related_Posts_Settings::execute');
  }

  static function update_plugin_links($links, $file)
  {
    static $plugin;
    if(!$plugin)
    {
      $plugin=plugin_basename(__FILE__);
      $plugin=dirname($plugin).'/sl-related-posts.php';
    }
    if($file==$plugin)
    {
      $title=__('Settings');
      $settings='<a href="options-general.php?page='.self::$unique_handle.'">'.$title.'</a>';
      array_unshift($links, $settings); // At first
    }
    return $links;
  }

  static function execute()
  {
    self::copyright();

    if(!current_user_can('manage_options')) wp_die();

    if($_POST['action']=='update')
    {
      $option='sl_related_posts_in_content_enabled';
      $value=trim($_POST[$option]);
      $value=stripslashes($value);
      $value=$value=='yes' ? 'yes' : 'no';
      update_option($option, $value);

      $option='sl_related_posts_in_content_headline';
      $value=trim($_POST[$option]);
      $value=stripslashes($value);
      update_option($option, $value);

      $option='sl_related_posts_in_content_limit';
      $value=trim($_POST[$option]);
      $value=stripslashes($value);
      update_option($option, $value);

      echo '<div id="message" class="updated fade"><p><strong>'.__('Options saved.', 'Steffen Liersch').'</strong></p></div>';
    }
    ?>
    <div class="wrap sl_plugin_edit">
      <h2><?php _e('SLRelatedPosts Plug-In Settings', 'Steffen Liersch'); ?></h2>
      <form method="post" action="options-general.php?page=<?php echo self::$unique_handle; ?>">
        <?php wp_nonce_field(self::$unique_handle); ?>
        <p class="submit"><input type="submit" name="Submit" value="<?php _e('Save Changes', 'Steffen Liersch'); ?>" /></p>
        <table class="form-table">
          <tr valign="top">
            <th scope="row"><?php _e('Related posts inside the content area', 'Steffen Liersch'); ?></th>
            <td>
              <fieldset>
                <?php
                $option='sl_related_posts_in_content_enabled';
                $value=get_option($option, 'yes');
                echo '<label for="'.$option.'">';
                $s=$value=='yes' ? ' checked="checked"' : '';
                echo '<input id="'.$option.'" name="'.$option.'" type="checkbox" value="yes"'.$s.' /> ';
                echo __('Enable related post list at the end of each post', 'Steffen Liersch').'</label>';
                echo '<br />';
                echo '<span class="description">';
                echo __('This option installs, if enabled, a content filter that adds a list of related posts to the end of each post.', 'Steffen Liersch');
                echo '</span>';

                echo '<br />';
                echo '<br />';

                $option='sl_related_posts_in_content_headline';
                $value=htmlspecialchars(get_option($option, 'Related Posts'));
                echo '<label for="'.$option.'"> '.__('Headline: ', 'Steffen Liersch').'</label><br />';
                echo '<input id="'.$option.'" name="'.$option.'" type="text" size="40" value="'.$value.'" /> ';
                echo '<br /><span class="description">'.__('This text is used as headline for the list of related posts.', 'Steffen Liersch').'</span>';

                echo '<br />';
                echo '<br />';

                $option='sl_related_posts_in_content_limit';
                $value=get_option($option, '5');
                echo '<label for="'.$option.'">'.__('Maximum item count: ', 'Steffen Liersch').'</label>';
                echo '<select id="'.$option.'" name="'.$option.'" size="1">';
                for($i=2; $i<=10; $i++)
                  self::write_select_option($i, $i, $value);
                echo '</select>';
                echo '<br />';
                echo '<span class="description">';
                echo __('This value specifies the maximum number of related posts.', 'Steffen Liersch');
                echo '</span>';
                ?>
              </fieldset>
            </td>
          </tr>
        </table>
        <p class="submit"><input type="submit" name="Submit" value="<?php _e('Save Changes', 'Steffen Liersch'); ?>" /></p>
        <input type="hidden" name="action" value="update" />
      </form>
    </div>
    <?php
  }

  static function write_select_option($display, $value, $selected)
  {
    $s=$value==$selected ?  ' selected="selected"' : '';
    echo '<option value="'.$value.'"'.$s.'>'.$display.'</option>';
  }

  static function copyright()
  {
    ?>
    <div class="wrap">
      <br style="clear:both" />
        <div class="sl_plugin_info">
        <h3>Plug-In Information</h3>
        <p>
          Copyright &copy; 2010 Dipl.-Ing. (BA) Steffen Liersch<br />
          <a href="http://www.steffen-liersch.de/">www.steffen-liersch.de</a>
        </p>
        <p>If you like this plug-in, you can leave a donation to support maintenance and development.</p>
        <a style="outline: none;" href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&amp;hosted_button_id=9566236"><img
          title="Leave a donation to support maintenance and development"
          alt="PayPal - The safer, easier way to pay online!"
          src="https://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif"
          width="147" height="47" border="0"
        /></a>
      </div>
    </div>
    <?php
  }
}

// Perform plug-in initialization
SL_Related_Posts_Settings::initialize();

?>