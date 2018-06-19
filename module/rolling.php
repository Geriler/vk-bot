<?php
function roll($text, $peer_id) {
    $arrayUsers = getArrayUsers($peer_id);
    $numUser = (count($arrayUsers) - 1);
    $user_id = $arrayUsers[rand(0, $numUser)];
    $user = json_decode(getUserInfo($user_id));
    if (empty($text)) {
        $res = sendMessage("{$user->name} {$user->last_name} выпал из окна", null, $peer_id);
    } else {
        $res = sendMessage("{$user->name} {$user->last_name} {$text}", null, $peer_id);
    }
    return $res;
}
