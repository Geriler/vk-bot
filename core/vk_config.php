<?php
define('VK_VERSION', '5.69'); //Версия VK API
define('VK_API_METHOD', 'https://api.vk.com/method/');
define('VK_TOKEN', 'Paste your token here'); //Токен для VK API

// ID конференций, в которую подключен бот, замените своими значениями в формате "2000000000 + номер конференции"
// В данный момент бот подключен в конференции нмоер 2 и 7
define('PEER_ID', array(
    '2000000002',
    '2000000007',
));
