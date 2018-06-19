<?php
function questionandanswer($peer_id, $message_id) {
    $user_id = getUserIdByMessage($message_id);
    $user = json_decode(getUserInfo($user_id));
    $answer = rand(1, 2);
    $message = ($answer == 1) ? "Да, {$user->name}." : "Нет, {$user->name}.";
    return sendMessage($message, null, $peer_id);
}