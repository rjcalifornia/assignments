<?php
/**
 * View for blog objects
 *
 * @package Blog
 */

$full = elgg_extract('full_view', $vars, FALSE);
$blog = elgg_extract('entity', $vars, FALSE);

if (!$blog) {
	return TRUE;
}

$owner = $blog->getOwnerEntity();
$categories = elgg_view('output/categories', $vars);
$excerpt = $blog->excerpt;
if (!$excerpt) {
	$excerpt = elgg_get_excerpt($blog->description);
}

$owner_icon = elgg_view_entity_icon($owner, 'tiny');

$vars['owner_url'] = "homework/owner/$owner->username";
$by_line = elgg_view('page/elements/by_line', $vars);

// The "on" status changes for comments, so best to check for !Off
if ($blog->comments_on != 'Off') {
	$comments_count = $blog->countComments();
	//only display if there are commments
	if ($comments_count != 0) {
		$text = elgg_echo("comments") . " ($comments_count)";
		$comments_link = elgg_view('output/url', array(
			'href' => $blog->getURL() . '#comments',
			'text' => $text,
			'is_trusted' => true,
		));
	} else {
		$comments_link = '';
	}
} else {
	$comments_link = '';
}

$subtitle = "$by_line $comments_link $categories";

$metadata = '';
if (!elgg_in_context('widgets')) {
	// only show entity menu outside of widgets
	$metadata = elgg_view_menu('entity', array(
		'entity' => $vars['entity'],
		'handler' => 'homework',
		'sort_by' => 'priority',
		'class' => 'elgg-menu-hz',
	));
}

if ($full) {
    
    $featured = elgg_get_entities(array(
	'type' => 'object',
	'subtype' => 'file',
        'category' => 'featured',
        'owner_guid' => $blog->guid,
	//'full_view' => false,
        'limit' => 1,
	'no_results' => elgg_echo("file:none"),
	'preload_owners' => true,
	'preload_containers' => true,
	'distinct' => false,
));
    
    foreach ($featured as $f) {
                 $file = get_entity($f->guid);

                 // $image_url = $file->getIconURL('large');
               //   $image_url = elgg_format_url($image_url);
                  $download_url = elgg_get_download_url($file);
             
                  
$current_featured = <<<___HTML
<center>
    <div>
       
            <img src="$download_url" class="img-fluid">
       
    </div>
</center>
___HTML;
                          
                 
                  
//echo $download_url;
                 }
$body =  elgg_view('output/longtext', array(
		'value' => $current_featured,
		'class' => 'blog-post',));



	$body.= elgg_view('output/longtext', array(
		'value' => $blog->description,
		'class' => 'blog-post',
	));



	$params = array(
		'entity' => $blog,
		'title' => false,
		'metadata' => $metadata,
		'subtitle' => $subtitle,
	);
	$params = $params + $vars;
	$summary = elgg_view('object/elements/summary', $params);

	echo elgg_view('object/elements/full', array(
		'entity' => $blog,
		'summary' => $summary,
		'icon' => $owner_icon,
                'test' => $test,
		'body' => $body,
	));

} else {
	// brief view

	$params = array(
		'entity' => $blog,
		'metadata' => $metadata,
		'subtitle' => $subtitle,
		'content' => $excerpt,
		'icon' => $owner_icon,
	);
	$params = $params + $vars;
	echo elgg_view('object/elements/summary', $params);

}
