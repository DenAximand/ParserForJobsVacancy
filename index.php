<?php

include 'autoloader.php';

use Models\JobsList;
use TelegramBot\MyBot;

$ini = parse_ini_file('config.ini',true);

$allVacancies = [];
//
$Parsing = new JobsList($ini['PARSER']['par_url_dou'], $ini['PARSER']['par_url_work']);
$Parsing->sendMessageContentForBot();
//
$Bot = new MyBot($ini['BOT']['bot_url'], $ini['BOT']['bot_api_token'], $ini['BOT']['bot_chat_id']);
//$Bot->getUpdates();
foreach ($allVacancies as $item){
    $text = $item;
    $Bot->sendMessage($text);
}
