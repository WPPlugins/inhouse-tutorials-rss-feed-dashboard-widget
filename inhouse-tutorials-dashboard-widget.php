<?php
/*
Plugin Name: InHouse Tutorials RSS Feed Dashboard Widget
Plugin URI: http://useinhouse.com/for-our-clients/tutorials
Description: Adds a dashboard widget which is a simple RSS feed of a series of helpful tutorials.
Author: In House Graphic Design, Inc.
Version: 0.2.3
Stable: 0.2.3
Author URI: http://useinhouse.com/for-our-clients/tutorials
*/

function dashboard_widget_function() {
     $rss = fetch_feed( "http://useinhouse.com/feed/?post_type=mcm_tutorial" );
  
     if ( is_wp_error($rss) ) {
          if ( is_admin() || current_user_can('manage_options') ) {
               echo '<p>';
               printf(__('<strong>RSS Error</strong>: %s'), $rss->get_error_message());
               echo '</p>';
          }
     return;
}
  
if ( !$rss->get_item_quantity() ) {
     echo '<p>Currently there are no tutorials to display.</p>';
     $rss->__destruct();
     unset($rss);
     return;
}
  
echo "<ul>\n";
  
if ( !isset($items) )
     $items = 10;
  
     foreach ( $rss->get_items(0, $items) as $item ) {
          $publisher = '';
          $site_link = '';
          $link = '';
          $content = '';
          $date = '';
          $link = esc_url( strip_tags( $item->get_link() ) );
          $title = esc_html( $item->get_title() );
          $content = $item->get_content();
          $content = wp_html_excerpt($content, 250) . ' ...';
  
         echo "<li><a class='rsswidget' href='$link'>$title</a>\n<div class='rssSummary'>$content <a class='rsswidgetmore' href='$link'>Read more &raquo;</a></div>\n";
}
  
echo "</ul>\n";
$rss->__destruct();
unset($rss);
}
 
function add_dashboard_widget() {
     wp_add_dashboard_widget('inhouse_tutorials_dashboard_widget', 'InHouse Design – Tutorials', 'dashboard_widget_function');
}

add_filter('wp_feed_cache_transient_lifetime',create_function('$a', 'return 1200;'));
 
add_action('wp_dashboard_setup', 'add_dashboard_widget');

?>
