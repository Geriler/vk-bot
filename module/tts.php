<?php
function tts($text, $peer_id) {
	$arrayEmotion = array('good', 'neutral', 'evil');
	$indexEmotion = rand(0, 2);
	$file = saveVoiceMessage($text, 'jane', $arrayEmotion[$indexEmotion]);
	$attachment = uploadVoiceMessage($file, $peer_id);
	if (!(is_array($attachment))) return sendMessage(null, $attachment, $peer_id);
	else return $attachment;
}