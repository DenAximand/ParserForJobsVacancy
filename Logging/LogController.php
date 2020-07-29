<?php

function createRecordInLogFile($text){
    $log = date('Y-m-d H:i:s ') . $text;
    file_put_contents('ErrorLog.log', $log . PHP_EOL, FILE_APPEND);
}
