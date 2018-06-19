<?php
require_once dirname(__FILE__)."/../core/pixabay_config.php";
function picture($peer_id) {
	$category = array('fashion', 'nature', 'backgrounds', 'science', 'education', 'people', 'feelings', 'religion', 'health', 'places', 'animals', 'industry', 'food', 'computer', 'sports', 'transportation', 'travel', 'buildings', 'business', 'music' );
	$image_type = array("all", "photo", "illustration", "vector" );
	$colors = array("grayscale", "transparent", "red", "orange", "yellow", "green", "turquoise", "blue", "lilac", "pink", "white", "gray", "black", "brown" );
	$data = json_decode(file_get_contents('https://pixabay.com/api/?key=' . PIXABAY_TOKEN . '&per_page=20&image_type=' . $image_type[rand(0, count($image_type))] . '&category=' . $category[rand(0, count($category))] . '&colors=' . $colors[rand(0, count($colors))]));
	do {
		$picture = $data->hits[rand(0, 19)];
		$url_image = $picture->largeImageURL;
		preg_match('/(\w+-\d+_\d+.jpg$)/', $picture->previewURL, $matches);
		$name_image = $matches[1];
	} while ($name_image == NULL)
	$attachment = uploadPhoto($url_image, $name_image, $peer_id)
	return sendMessage(null, $attachment, $peer_id);
}