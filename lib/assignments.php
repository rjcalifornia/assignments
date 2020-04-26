<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
function get_assignments_content_list($container_guid = NULL) {

	$return = array();
        $page_owner = elgg_get_page_owner_entity()->owner_guid;

	$return['filter_context'] = $container_guid ? 'mine' : 'all';

	$options = array(
		'type' => 'object',
		'subtype' => 'assignments',
		'full_view' => false,
		'no_results' => elgg_echo('assignments:none'),
		'preload_owners' => true,
		'distinct' => false,
	);

	$current_user = elgg_get_logged_in_user_entity();

	if ($container_guid) {
		// access check for closed groups
		elgg_group_gatekeeper();

		$container = get_entity($container_guid);
		if ($container instanceof ElggGroup) {
		$options['container_guid'] = $container_guid;
		} else {
			$options['owner_guid'] = $container_guid;
		}
		$return['title'] = elgg_echo('assignments:title:user_assignments', array($container->name));

		$crumbs_title = $container->name;
		elgg_push_breadcrumb($crumbs_title);

		if ($current_user && ($container_guid == $current_user->guid)) {
			$return['filter_context'] = 'mine';
		} else if (elgg_instanceof($container, 'group')) {
			$return['filter'] = false;
		} else {
			// do not show button or select a tab when viewing someone else's posts
			$return['filter_context'] = 'none';
		}
	} else {
		$options['preload_containers'] = true;
		$return['filter_context'] = 'all';
		$return['title'] = elgg_echo('assignments:title:all_assignments');
		elgg_pop_breadcrumb();
		elgg_push_breadcrumb(elgg_echo('assignments:list'));
	}

        if($page_owner == $current_user->guid)
{
	elgg_register_title_button('assignments', 'add', 'object', 'assignments');
}

	$return['content'] = elgg_list_entities($options);

	return $return;
}


function elgg_view_responses($entity, $add_comment = true, array $vars = array()) {
	if (!($entity instanceof \ElggEntity)) {
		return false;
	}

	$vars['entity'] = $entity;
	$vars['show_add_form'] = $add_comment;
        
        $logged_guid = elgg_get_logged_in_user_entity()->guid;
        $comments = elgg_get_entities(array(
                            'type' => 'object',
                            'subtype' => 'comment',
                            'container_guid' => $entity->guid,
                            'owner_guid' => $logged_guid,
                         ));
        
	$vars['class'] = elgg_extract('class', $vars, "{$entity->getSubtype()}-comments");

	$output = elgg_trigger_plugin_hook('comments', $entity->getType(), $vars, false);
	if ($output !== false) {
		return $output;
	} 
        
        if(!$comments){
		return elgg_view('page/elements/comments', $vars);
	}
        
        if($comments && $logged_guid != $entity->owner_guid)
        {
             return elgg_view('resources/assignments/elements/received_assignments');
        }
        
        if($logged_guid == $entity->owner_guid){
		return elgg_view('page/elements/comments', $vars);
	}
}


function get_all_responses($attr, $guid, $owner_guid)
{
    
    $logged_user = elgg_get_logged_in_user_entity()->guid;
    
    if($logged_user == $owner_guid)
    {
    $content = elgg_list_entities(array(
	'type' => 'object',
	'subtype' => 'comment',
	'container_guid' => $guid,
	'reverse_order_by' => true,
	'full_view' => true,
	//'limit' => $limit,
	'preload_owners' => true,
	'distinct' => false,
	'url_fragment' => $attr['id'],
));

    return $content;
    }
}


?>
