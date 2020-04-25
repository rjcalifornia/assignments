<?php

// register an initializer
elgg_register_event_handler('init', 'system', 'assignments_init');

function assignments_init() {
    
    elgg_register_library('elgg:assignments', __DIR__ . '/lib/assignments.php');
    // register the save action
    elgg_register_action("assignments/save", __DIR__ . "/actions/assignments/save.php");

    // register the page handler
    elgg_register_page_handler('assignments', 'assignments_page_handler');

    // register a hook handler to override urls
    elgg_register_plugin_hook_handler('entity:url', 'object', 'assignments_set_url');
    
    // Register for search.
	elgg_register_entity_type('object', 'assignments');

	// Add group option
	add_group_tool_option('assignments', elgg_echo('assignments:enableassignments'), true);
	elgg_extend_view('groups/tool_latest', 'assignments/group_module');

	// add a blog widget
	elgg_register_widget_type('assignments', elgg_echo('assignments'), elgg_echo('assignments:widget:description'));
        
        elgg_register_plugin_hook_handler('view', 'river/object/comment/create', 'disable_river_actions');
        

}
  
function disable_river_actions($hook, $type, $return, $params) {
    return false;
}
/**
 * Dispatches blog pages.
 * URLs take the form of
 *  All blogs:       blog/all
 *  User's blogs:    blog/owner/<username>
 *  Friends' blog:   blog/friends/<username>
 *  User's archives: blog/archives/<username>/<time_start>/<time_stop>
 *  Blog post:       blog/view/<guid>/<title>
 *  New post:        blog/add/<guid>
 *  Edit post:       blog/edit/<guid>/<revision>
 *  Preview post:    blog/preview/<guid>
 *  Group blog:      blog/group/<guid>/all
 *
 * Title is ignored
 *
 * @todo no archives for all blogs or friends
 *
 * @param array $page
 * @return bool
 */
function assignments_page_handler($page) {

	elgg_load_library('elgg:assignments');

	// push all blogs breadcrumb
	elgg_push_breadcrumb(elgg_echo('assignments:assignment'), 'assignments/all');

	$page_type = elgg_extract(0, $page, 'all');
	$resource_vars = [
		'page_type' => $page_type,
	];

	switch ($page_type) {
		case 'owner':
			$resource_vars['username'] = elgg_extract(1, $page);
			
			echo elgg_view_resource('assignments/owner', $resource_vars);
			break;
		case 'friends':
			$resource_vars['username'] = elgg_extract(1, $page);
			
			echo elgg_view_resource('assignments/friends', $resource_vars);
			break;
		case 'archive':
			$resource_vars['username'] = elgg_extract(1, $page);
			$resource_vars['lower'] = elgg_extract(2, $page);
			$resource_vars['upper'] = elgg_extract(3, $page);
			
			echo elgg_view_resource('assignments/archive', $resource_vars);
			break;
		case 'view':
			$resource_vars['guid'] = elgg_extract(1, $page);
			
			echo elgg_view_resource('assignments/view', $resource_vars);
			break;
		case 'add':
			$resource_vars['guid'] = elgg_extract(1, $page);
			
			echo elgg_view_resource('assignments/add', $resource_vars);
			break;
		case 'edit':
			$resource_vars['guid'] = elgg_extract(1, $page);
			$resource_vars['revision'] = elgg_extract(2, $page);
			
			echo elgg_view_resource('assignments/edit', $resource_vars);
			break;
		case 'group':
			$resource_vars['group_guid'] = elgg_extract(1, $page);
			$resource_vars['subpage'] = elgg_extract(2, $page);
			$resource_vars['lower'] = elgg_extract(3, $page);
			$resource_vars['upper'] = elgg_extract(4, $page);
			
			echo elgg_view_resource('assignments/group', $resource_vars);
			break;
		case 'all':
			echo elgg_view_resource('assignments/all', $resource_vars);
			break;
		default:
			return false;
	}

	return true;
}

function assignments_set_url($hook, $type, $url, $params) {
	$entity = $params['entity'];
	if (elgg_instanceof($entity, 'object', 'assignments')) {
		$friendly_title = elgg_get_friendly_title($entity->title);
		return "assignments/view/{$entity->guid}/$friendly_title";
	}
}

