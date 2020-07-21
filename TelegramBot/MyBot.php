<?php


namespace TelegramBot;


class MyBot
{
    private $apiToken = '1297621518:AAHEgBZcv4ieGTPK-XQPSpqX8rXdnlGGb4Q';
    private $Url = 'https://api.telegram.org/bot';


    const __CHAT_ID__ = '705522066';

    public function getUpdates(){
        $linkForGetUpdates = $this->Url.$this->apiToken.'/getUpdates';

        $updateArr = file_get_contents($linkForGetUpdates);
        print_r($updateArr);
    }

    public function sendMessage($text){
        $url = $this->Url.$this->apiToken.'/sendMessage?chat_id='.self::__CHAT_ID__.'&text='.urlencode($text);
        $curl = curl_init($url);

        curl_setopt($curl,CURLOPT_POST, 1);
        curl_setopt($curl,CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl,CURLOPT_HEADER, 0);
        curl_exec($curl);
        curl_close($curl);
    }

}