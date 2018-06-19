<?php
function weather($peer_id) {
    $data = json_decode(file_get_contents("http://api.wunderground.com/api/47e38818810ce9cb/conditions/q/RU/Omsk.json"));
    $temp_city = $data->current_observation->temperature_string;
    $feel_city = $data->current_observation->feelslike_string;
    $name_city = $data->current_observation->display_location->full;
    $message = "{$name_city}\r\nТемпература: {$temp_city}\r\nОщущается: {$feel_city}";
    return sendMessage($message, null, $peer_id);
}
