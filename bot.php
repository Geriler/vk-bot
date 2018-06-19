<?php
set_time_limit(0);

if ($argv[1] == "debug") {
	error_reporting('E_ALL');
	echo "debug on\n";
}
else error_reporting('E_ERROR');

//region Require
require_once dirname(__FILE__).'/core/vk_api.php';
require_once dirname(__FILE__).'/core/yandex_api.php';

require_once dirname(__FILE__).'/module/cmdlist.php';
require_once dirname(__FILE__).'/module/info.php';
require_once dirname(__FILE__).'/module/picture.php';
require_once dirname(__FILE__).'/module/qanda.php';
require_once dirname(__FILE__).'/module/rolling.php';
require_once dirname(__FILE__).'/module/tts.php';
require_once dirname(__FILE__).'/module/weather.php';
//endregion

$longPoll = json_decode(getLongPollServer());

while (true) {
    $data = json_decode(file_get_contents("https://{$longPoll->server}?act=a_check&key={$longPoll->key}&ts={$longPoll->ts}&wait=25&mode=32&lp_version=2"));
    if ($data->failed == '2' || $data->failed == '3') {
        $longPoll = json_decode(getLongPollServer());
    }
    if ($longPoll->ts != $data->ts) {
        $pts = $data->pts;
        $longPoll->ts = $data->ts;
        $updates = $data->updates;

        foreach ($updates as $update) {
            if ($update[0] == 4) {
                foreach (PEER_ID as $id) {
                    if ($update[3] == $id) {
                        if (preg_match('/^\/tts\s+(.+)/s', $update[6], $matches)) {
                            $res = tts($matches[1], $id);
                            var_dump($res);
                        }
                        if (preg_match('/^\/captcha\s+(.+)/s', $update[6], $matches)) {
                            $res = captcha($res, $matches[1], $id);
                            var_dump($res);
                        }
						if (preg_match('/^\/weather$/s', $update[6], $matches)) {
							$res = weather($id);
							var_dump($res);
						}
						if (preg_match('/^\/roll\s*(.*)$/s', $update[6], $matches)) {
							$res = roll($matches[1], $id);
							var_dump($res);
						}
						if (preg_match('/^\/list\s*(.*)$/s', $update[6], $matches)) {
							$res = cmdlist($matches[1], $id);
							var_dump($res);
						}
						if (preg_match('/^(Бот|Вика|Виктория).+\?$/s', $update[6], $matches)) {
							$res = questionandanswer($id, $update[1]);
							var_dump($res);
						}
						if (preg_match('/^\/info$/s', $update[6], $matches)) {
							$res = info($id);
							var_dump($res);
						}
						if (preg_match('/^\/pic$/s', $update[6], $matches)) {
							$res = picture($id);
							var_dump($res);
						}
                    }
                }
            }
        }
    }
}
