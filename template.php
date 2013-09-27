<?php

/**
 * @file
 * enrique Theme template.php
 *
 * This is WHERE the magic happens!
 *
*/



function fuze_preprocess(&$vars, $hook) {
  if ($hook == "html") {

    // Add the font stylesheet to the HTML
    drupal_add_css(
      'https://fonts.googleapis.com/css?family=Open+Sans:300italic,300,600', 
      array('type' => 'external')
    );

    /* CSS Classes to Remove
     * ---------------------
    */
    // Remove html class
    $classes_to_remove_from_body = array(
      'html',
      'logged-in',
      'not-logged-in',
      'two-sidebars',
      'one-sidebar',
      'one-sidebar sidebar-first',
      'one-sidebar sidebar-second',
      'no-sidebars',
      'toolbar',
      'toolbar-drawer',
      'page-node',
      'page-node-1'
    );

    // remove body path classes
    $path_all = drupal_get_path_alias($_GET['q']);
    array_push($classes_to_remove_from_body, drupal_html_class('path-' . $path_all));
    array_push($classes_to_remove_from_body, drupal_html_class('page-' . $path_all));
    
    // remove body path first class
    $path = explode('/', $_SERVER['REQUEST_URI']);
    if($path['0']){
      array_push($classes_to_remove_from_body, drupal_html_class('pathone-' . $path['0']));
    }

    // remove all the bad, bad classes
    $vars['classes_array'] = array_values(array_diff($vars['classes_array'],$classes_to_remove_from_body));

    // Little Utility Variable
    $vars['theme_path'] = base_path() . drupal_get_path('theme', 'enrique');;

  }
  elseif ($hook == "page") {

    /* Grid Setup
     * ----------
    */
    
    // shortcut var
    $page = $vars['page'];

    // Total number of columns
    $g_total_cols = 12;

    // main row widths
    $g_sidebar_first_width = 3;
    $g_sidebar_second_width = 3;

    // calculations 
    $grid_main_count = $g_total_cols;
    if (!empty($page['sidebar_first'])) {
      $grid_main_count -= $g_sidebar_first_width;
    }
    if (!empty($page['sidebar_second'])) {
      $grid_main_count -= $g_sidebar_second_width;
    }

    // set widths
    $grid['sidebar_first_class'] = "g" . $g_sidebar_first_width;
    $grid['sidebar_second_class'] = "g" . $g_sidebar_second_width;
    $grid['main_content_class'] = "g" . $grid_main_count;

    $vars['grid'] = $grid;
  }
  elseif ($hook == "region") {
    
    // add code

  }
  elseif ($hook == "block") {
    
    // add code
    
  }
  elseif ($hook == "node") {

    // add code
    
  }
  elseif ($hook == "field") {
    
    // add code
    
  }

}

/* Views Tweaks
 * ------------
 * These get rid of a lot of crazy classes. Maybe added back later.
*/
function fuze_preprocess_views_view(&$vars) {
  // remove view holder classes
  $classes_to_remove_from_view = array(
    'view',
    'view-' . $vars['name'],
    'view-id-' . $vars['name'],
    'view-display-id-' . $vars['display_id']
  );
  
  // remove all the bad, bad classes
  $vars['classes_array'] = array_values(array_diff($vars['classes_array'],$classes_to_remove_from_view));

}
function fuze_preprocess_views_view_unformatted(&$vars) {
  foreach ($vars['rows'] as $id => $row) {
    // patterns to preg-replace; order most-specific to least specific to avoid messy replaces
    $classes_to_remove_from_row = array(
      "/views-row-last/",
      "/views-row-first/",
      "/views-row-odd/",
      "/views-row-even/",
      "/views-row-([0-9]+)/",
      "/views-row/"
    );
    foreach ($classes_to_remove_from_row as $pattern) {
      $vars['classes_array'][$id] = preg_replace($pattern, '', $vars['classes_array'][$id]);
    }
    // remove any extra space that may be left in there.
    $vars['classes_array'][$id] = trim($vars['classes_array'][$id]);
  }
}


/* CSS Removal
 * ---------
*/

function fuze_css_alter(&$css) {
  // remove various stylesheets
  unset($css[drupal_get_path('module', 'system') . '/system.admin.css']);
  unset($css[drupal_get_path('module', 'system') . '/system.admin-rtl.css']);
  //unset($css[drupal_get_path('module', 'system') . '/system.base.css']);
  //unset($css[drupal_get_path('module', 'system') . '/system.base-rtl.css']);
  unset($css[drupal_get_path('module', 'system') . '/system.menus.css']);
  unset($css[drupal_get_path('module', 'system') . '/system.menus-rtl.css']);
  unset($css[drupal_get_path('module', 'system') . '/system.messages.css']);
  unset($css[drupal_get_path('module', 'system') . '/system.theme.css']);
  unset($css[drupal_get_path('module', 'system') . '/system.theme-rtl.css']);
  unset($css[drupal_get_path('module', 'user') . '/user.css']);
  //unset($css[drupal_get_path('module', 'system') . '/system.maintainance.css']);
}

/* Menu Clean Modification
 * -------------
*/

// clean up some classes on menus
function fuze_menu_link(array $variables) {
  
  // custom stuff to remove extra classes
  $classes_to_remove_from_links = array();
  array_push($classes_to_remove_from_links, "leaf");
  array_push($classes_to_remove_from_links, "collapsed");
  array_push($classes_to_remove_from_links, "expanded");
  array_push($classes_to_remove_from_links, "expandable");

  // remove the classes
  $variables['element']['#attributes']['class'] = array_diff($variables['element']['#attributes']['class'], $classes_to_remove_from_links);

  // remove class attribute altogether if not needed for cleaner markup
  if (empty($variables['element']['#attributes']['class'])) {
    unset($variables['element']['#attributes']['class']);
  }

  return theme_menu_link($variables);
}