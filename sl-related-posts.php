<?php
/*
Plugin Name: SLRelatedPosts
Plugin URI: http://www.steffen-liersch.de/wordpress/
Description: This plug-in installs a sidebar widget and an optional content filter to list related posts.
Version: 1.0
Author: Steffen Liersch
Author URI: http://www.steffen-liersch.de/
*/

/*----------------------------------------------------------------
 * Related posts enumeration
 *----------------------------------------------------------------*/

function sl_get_related_posts($limit)
{
  if(!is_single())
    return '';

  $params='category=';
  foreach(get_the_category() as $c)
    $params.=','.$c->cat_ID;

  global $post;
  $params.='&exclude='.$post->ID;
  $params.='&numberposts='.$limit;
  $params.='&orderby=date';

  $items='';
  foreach(get_posts($params) as $p)
  {
    $href=get_permalink($p->ID);
    $title=get_the_title($p->ID);
    $items.='<li><a href="'.$href.'" title="'.$title.'">'.$title."</a></li>\n";
  }
  return $items;
}

/*----------------------------------------------------------------
 * Content filter
 *----------------------------------------------------------------*/

function sl_related_posts_filter($content)
{
  if(is_single() && get_option('sl_related_posts_in_content_enabled', 'yes')=='yes')
  {
    $limit=get_option('sl_related_posts_in_content_limit', '5');
    $items=sl_get_related_posts($limit);
    if(strlen($items)>0)
    {
      $s="\n\n<!-- SLRelatedPosts Plug-In by Steffen Liersch -->\n";
      $h=trim(get_option('sl_related_posts_in_content_headline', 'Related Posts'));
      if(strlen($h)>0)
        $s.="<h2>".$h."</h2>\n";
      $s.="<ul>\n";
      $s.=$items;
      $s.="</ul>\n";
      $s.="<!-- SLRelatedPosts Plug-In by Steffen Liersch -->\n\n";
      $content.=$s;
    }
  }
  return $content;
}

add_filter('the_content' , 'sl_related_posts_filter' );

/*----------------------------------------------------------------
 * Content filter settings
 *----------------------------------------------------------------*/

if(is_admin())
  include_once('sl-related-posts-settings.php');

/*----------------------------------------------------------------
 * Sidebar widget
 *----------------------------------------------------------------*/

include_once('sl-related-posts-widget.php');

?>