<?php
function cmdlist($text, $peer_id) {
    $arrayUsers = getArrayUsers($peer_id);
    shuffle($arrayUsers);
    $countUsers = rand(1, count($arrayUsers));
    if ($countUsers != 1) {
        $randUsers = array_rand($arrayUsers, $countUsers);
    } else {
        $randUsers[0] = array_rand($arrayUsers, $countUsers);
    }
    if (empty($text)) {
        $message = "# Список кого-то там:\r\n";
    } else {
        $message = "# Список {$text}:\r\n";
    }
    $number = 1;
    for ($i = 0; $i < $countUsers; $i++) {
        $user_id = $arrayUsers[$randUsers[$i]];
        $user = json_decode(getUserInfo($user_id));
        $message .= "{$number}. {$user->name} {$user->last_name}\r\n";
        $number++;
    }
    $message .= "# Конец списка";
    return sendMessage($message, null, $peer_id);
}