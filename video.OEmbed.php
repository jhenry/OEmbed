<?php

$this->view->options->disableView = true;

$urlParts = parse_url($_GET['url']);

// Check for 'live' in the url and set up livestream embed
if (strpos($urlParts['path'], 'live') !== false) {

  // Get the stream id from the params
  $query = parse_str($urlParts['query'], $params);
  $id = (isset($query['id'])) ? $query['id'] : false;

  if ($id) {
    if (is_numeric($id)) {

      // Generic class, since this is not an application-owned video
      $video = new stdClass();
      $video->type = "video";
      $video->version = "1.0";
      $video->author_name = $urlParts['host'];
      $video->author_url = "https://" . $urlParts['host'];
      $video->html = "<iframe src='" . BASE_URL . "/live/embed.php?id=$id' width='640' height='480' frameborder='0' allowfullscreen></iframe>";
      $video->width = '480';
      $video->height = '360';
    }
  } else {
    App::Throw404();
  }
}
else {
  // Get the video id from the url path since this is not a live stream
  $videoId = basename($urlParts['path']);

  // Determine how to search based on id pattern
  $idColumnName = (is_numeric($videoId)) ? 'video_id' : 'private_url';

  $videoMapper = new VideoMapper();
  
  $video = $videoMapper->getVideoByCustom(array($idColumnName => $videoId, 'status' => 'approved'));

	if (!$video) {
		App::Throw404();
	}
    $videoUrl = ($video->private) ? $video->privateUrl : $video->videoId;
	$userMapper = new UserMapper();
	$owner = $userMapper->getUserByCustom(array('username' => $video->username, 'status' => 'Active'));
	$video->type="video";
	$video->version="1.0";
	$video->author_name=$owner->firstName.' '.$owner->lastName;
	$video->author_url=$owner->website;
	//$video->thumbnail_url=getVideoThumbnail($video);
  $video->html="<iframe src='" . BASE_URL . "/embed/$videoUrl/' width='400' height='250' frameborder='0' allowfullscreen></iframe>";
	$video->width='400';
	$video->height='250';

}

$apiResponse = new ApiResponse();
$apiResponse = $video;
header('Content-Type: application/json');
echo json_encode($apiResponse);
