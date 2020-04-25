<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$site_url = elgg_get_site_url();

elgg_gatekeeper();
$form_vars = array('enctype' => 'multipart/form-data');
$vars = array();
	$vars['id'] = 'blog-post-edit';
	$vars['class'] = 'elgg-form-alt';
        $vars['enctype'] = 'multipart/form-data';
        

$title = elgg_echo('assignments:title');

$content = elgg_view_title($title);

// add the form to the main column
$content .= elgg_view_form("assignments/save", $form_vars, $vars, $body_vars);

// optionally, add the content for the sidebar
$sidebar = "";

// layout the page
$body = elgg_view_layout('one_sidebar', array(
   'content' => $content,
   'sidebar' => $sidebar
));

// draw the page, including the HTML wrapper and basic page layout
echo elgg_view_page($title, $body);

//echo $content;
?>
<!-- Compiled and minified CSS -->
