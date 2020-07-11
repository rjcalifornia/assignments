<?php
/**
 * Blog river view.
 */

$item = $vars['item'];
/* @var ElggRiverItem $item */

$object = $item->getObjectEntity();

$excerpt = $object->excerpt ? $object->excerpt : $object->instructions;
$excerpt = strip_tags($excerpt);
$excerpt = elgg_get_excerpt($excerpt);

echo elgg_view('river/assignments-elements/layout', array(
	'item' => $vars['item'],
	'message' => $excerpt,
));
