<?php
require_once dirname(__FILE__).'/yandex_config.php';

function saveVoiceMessage($message, $speaker, $emotion) {
	$nameFile = md5($message).'.mp3';
	if (!(fopen(dirname(__FILE__)."/../otherfiles/voice/{$nameFile}", 'r'))) {
		$params = http_build_query(array(
			'format' => 'mp3',
			'lang' => 'ru-RU',
			'speaker' => $speaker,
			'key' => YANDEX_TOKEN,
			'emotion' => $emotion,
			'text' => $message
		));
		$ctx = stream_context_create(array("http"=>array("method"=>"GET","header"=>"Referer: \r\n")));
		$soundfile = file_get_contents("https://tts.voicetech.yandex.net/generate?{$params}", false, $ctx);
		$file = file_put_contents(dirname(__FILE__)."/../otherfiles/voice/{$nameFile}", $soundfile);
		if (!($file)) return false;
	}
	return $nameFile;
}
