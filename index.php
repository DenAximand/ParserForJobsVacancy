<?php

include 'autoloader.php';

use Models\JobsList;
use TelegramBot\MyBot;

$allVacancies = [];

$Parsing = new JobsList;
$Parsing->sendMessageContentForBot();

$Bot = new MyBot;
foreach ($allVacancies as $item){
    $text = $item;
    $Bot->sendMessage($text);
}

