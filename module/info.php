<?php
function info($peer_id) {
	$diff = 0;
	$arrayCommand = array(
		array('/captcha <code>', 'Вводит капчу для tts'),
		array('/list', 'Выводит список кого-то там'),
		array('/list <text>', 'Выводит список <text>'),
		array('/info', 'Показывает справку'),
		array('Бот/Вика/Виктория, <text>?', 'Спросить бота и получить ответ'),
		array('/roll', 'Выбрасывает случайного человека из окна'),
		array('/roll <text>', 'Выбирает случайного человека и присваивает ему <text>'),
		array('/tts <text>', 'Воспроизводит <text> (Яндекс)'),
		array('/weather', 'Показывает погоду в Омске'),
	);
	for ($i = 1; $i <= count($arrayCommand); $i++) {
		if ((int)(count($arrayCommand) / $i) <= 10) {
			$num_command = (int)((count($arrayCommand) / $i) + 1);
			$num_message = $i;
			break;
		}
	}
	for ($i = 0; $i < $num_message; $i++) {
		$n = $i + 1;
		$message = "Список команд #{$n}:\r\n";
		for ($j = 0 + $diff; $j < $num_command + $diff; $j++) {
			if ($arrayCommand[$j][0] != null)
				$message .= "{$arrayCommand[$j][0]} — {$arrayCommand[$j][1]}\r\n";
			else break;
		}
		$res = sendMessage($message, null, $peer_id);
		$diff += $num_command;
	}
	return $res;
}