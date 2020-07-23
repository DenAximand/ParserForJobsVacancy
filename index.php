<?php

include 'autoloader.php';

use TelegramBot\MyBot;

$ini = parse_ini_file('config.ini',true);

$Bot = new MyBot($ini['PARSER']['par_url_dou'], $ini['PARSER']['par_url_work'], $ini['BOT']['bot_url'], $ini['BOT']['bot_api_token'], $ini['BOT']['bot_chat_id']);

while(true){
    $Bot->run();
    sleep(30);
    set_time_limit(120);
}

