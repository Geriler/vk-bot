<?php
require_once dirname(__FILE__).'/vk_config.php';

function uploadVoiceMessage($nameFile, $peer_id) {
    $data = json_decode(file_get_contents(VK_API_METHOD . 'docs.getMessagesUploadServer?type=audio_message&peer_id=' . $peer_id . '&access_token=' . VK_TOKEN . '&v=' . VK_VERSION));
    $upload_url = $data->response->upload_url;
    $post_data = array('file' => new CURLFile(dirname(__FILE__) . "/../otherfiles/voice/{$nameFile}"));
    $result = download($upload_url, $post_data);
    $file = $result->file;
    $url = VK_API_METHOD . "docs.save?file={$file}&access_token=" . VK_TOKEN . "&v=" . VK_VERSION;
    $data_doc = json_decode(file_get_contents($url));
    $owner_id = $data_doc->response[0]->owner_id;
    $media_id = $data_doc->response[0]->id;
    if ($data_doc->response[0]->title == $nameFile)
        return "doc{$owner_id}_{$media_id}";
    else {
        $new_url = "{$url}&captcha_sid={$data_doc->error->captcha_sid}&captcha_key=";
        $url_image = $data_doc->error->captcha_img;
        $img = uploadPhoto($url_image, 'captcha.jpg', $peer_id);
        sendMessage("!!!КАПЧА!!!\r\n{$url_image}", $img, $peer_id);
        $data = array(
            'url' => $new_url,
            'name' => $nameFile
        );
        return $data;
    }
}

function uploadPhoto($url_image, $name_image, $peer_id) {
    $data = json_decode(file_get_contents(VK_API_METHOD . 'photos.getMessagesUploadServer?peer_id='. $peer_id .'&access_token='. VK_TOKEN .'&v='. VK_VERSION));
    $upload_url = $data->response->upload_url;
    file_put_contents(dirname(__FILE__)."/../otherfiles/images/{$name_image}", file_get_contents($url_image));
    sleep(1);
    $post_data = array('photo' => new CURLFile(dirname(__FILE__)."/../otherfiles/images/{$name_image}"));

    $result = download($upload_url, $post_data);

    $hash = $result->hash;
    $server = $result->server;
    $photo = $result->photo;

    $data_photo = json_decode(file_get_contents(VK_API_METHOD . "photos.saveMessagesPhoto?server={$server}&hash={$hash}&photo={$photo}&access_token=". VK_TOKEN ."&v=". VK_VERSION));
    $owner_id = $data_photo->response[0]->owner_id;
    $pic_id = $data_photo->response[0]->id;
    return "photo{$owner_id}_{$pic_id}";
}


function captcha($data, $code, $peer_id) {
	$data_doc = json_decode(file_get_contents($data['url'].$code));
	$owner_id = $data_doc->response[0]->owner_id;
	$media_id = $data_doc->response[0]->id;
	if ($data_doc->response[0]->title == $data['name']) {
		sendMessage('Капча введена', "doc{$owner_id}_{$media_id}", $peer_id);
		return 1;
	}
	else {
		sendMessage('Ошибка, капча не введена', null, $peer_id);
		return 0;
	}
}

function sendMessage($message, $attachment, $peer_id) {
	$params = http_build_query(array(
		'message' => $message,
		'attachment' => $attachment,
		'peer_id' => $peer_id,
		'access_token' => VK_TOKEN,
		'v' => VK_VERSION
	));
	return file_get_contents(VK_API_METHOD . "messages.send?{$params}");
}

function getLongPollServer() {
    $data = json_decode(file_get_contents(VK_API_METHOD . "messages.getLongPollServer?access_token=" . VK_TOKEN . "&v=" . VK_VERSION));
    $key = $data->response->key;
    $ts = $data->response->ts;
    $server = $data->response->server;
    $longPoll = json_encode(array(
        'server' => $server,
        'key' => $key,
        'ts' => $ts
    ));
    return $longPoll;
}

function download($upload_url, $post_data) {
    $ch = curl_init($upload_url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: multipart/form-data; charset=UTF-8'));
    $result = json_decode(curl_exec($ch));
    curl_close($ch);
    return $result;
}

function getUserInfo ($user_id) {
    $user_info = json_decode(file_get_contents(VK_API_METHOD . "users.get?user_ids={$user_id}&v=" . VK_VERSION . "&access_token=" . VK_TOKEN));
    $user_name = $user_info->response[0]->first_name;
    $user_lname = $user_info->response[0]->last_name;
    $user = json_encode(array(
        'name' => $user_name,
        'last_name' => $user_lname
    ));
    return $user;
}

function getArrayUsers($peer_id) {
    $chat_id = $peer_id - 2000000000;
    $dataChat = json_decode(file_get_contents(VK_API_METHOD . "messages.getChat?chat_id={$chat_id}&v=" . VK_VERSION . "&access_token=" . VK_TOKEN));
    $arrayUsers = $dataChat->response->users;
    return $arrayUsers;
}

function getUserIdByMessage($message_id) {
    $userid = json_decode(file_get_contents(VK_API_METHOD . "messages.getById?message_ids={$message_id}&v=" . VK_VERSION . "&access_token=" . VK_TOKEN));
    $uid = $userid->response->items[0]->user_id;
    return $uid;
}
