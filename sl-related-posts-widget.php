<?php

/*----------------------------------------------------------------
 * Widget implementation
 *----------------------------------------------------------------*/

class SL_Related_Posts_Widget extends WP_Widget
{
  // Widget construction
  function SL_Related_Posts_Widget()
  {
    $options=array(
      'classname'=>'widget_related_posts_sl',
      'description'=>__('Sidebar widget to list related posts', 'Steffen Liersch'));

    $this->WP_Widget('sl_related_posts', __('SLRelatedPosts', 'Steffen Liersch'), $options);
  }

  // Widget rendering
  function widget($args, $instance)
  {
    $items=sl_get_related_posts($instance['limit']);
    if($items)
    {
      echo "\n\n<!-- SLRelatedPosts Plug-In by Steffen Liersch -->\n";
      echo $args['before_widget']."\n";
      $title=$instance['title'];
      if($title)
        echo $args['before_title'].$title.$args['after_title'];
      echo "<ul>\n".$items."</ul>\n";
      echo $args['after_widget'];
      echo "\n<!-- SLRelatedPosts Plug-In by Steffen Liersch -->\n\n";
    }
  }

  // Widget settings update
  function update($new_instance, $old_instance)
  {
    $instance=$old_instance;
    $instance['title']=trim(strip_tags(stripslashes($new_instance['title'])));
    $instance['limit']=trim(strip_tags(stripslashes($new_instance['limit'])));
    return $instance;
  }

  // Widget setup form
  function form($instance)
  {
    // Set default values
    $instance=wp_parse_args((array)$instance,
      array('title'=>'Related Posts', 'limit'=>'5'));

    $option='title';
    $value=htmlspecialchars($instance[$option]);
    $id=$this->get_field_id($option);
    echo '<label for="'.$id.'">'.__('Title: ', 'Steffen Liersch');
    echo '<input id="'.$id.'" name="'.$this->get_field_name($option);
    echo '" type="text" size="20" value="'.$value.'" /></label><br />';

    $option='limit';
    $value=htmlspecialchars($instance[$option]);
    $id=$this->get_field_id($option);
    echo '<label for="'.$id.'">'.__('Limit: ', 'Steffen Liersch');
    echo '<select id="'.$id.'" name="'.$this->get_field_name($option).'" size="1">';
    for($i=2; $i<=10; $i++)
      self::write_select_option($i, $i, $value);
    echo '</select>';
    echo '</label><br /><br />';

    ?>
    <div style="text-align: center">
      <small>If you like this plug-in, you can leave a donation to support maintenance and development.</small>
    </div>
    <br />

    <div style="text-align: center">
      <a style="outline: none;" href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&amp;hosted_button_id=9566236"><img
        title="Leave a donation to support maintenance and development"
        alt="PayPal - The safer, easier way to pay online!"
        src="https://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif"
        width="147" height="47" border="0"
      /></a>
    </div>
    <br />

    <div style="text-align: center">
      <small>Copyright &copy; 2010 Steffen Liersch<br />
      <a href="http://www.steffen-liersch.de/">www.steffen-liersch.de</a></small>
    </div>
    <?php

    @readfile('http://www.steffen-liersch.de/advertisement/?mode=div&type=short');
  }

  static function write_select_option($display, $value, $selected)
  {
    $s=$value==$selected ?  ' selected="selected"' : '';
    echo '<option value="'.$value.'"'.$s.'>'.$display.'</option>';
  }
}

if(function_exists('add_action'))
  add_action('widgets_init', create_function('', 'register_widget("SL_Related_Posts_Widget");'));

?>