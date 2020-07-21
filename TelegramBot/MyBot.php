<?php


namespace TelegramBot;


class MyBot
{
    private $apiToken;
    private $url;
    private $chatID;

    public function __construct($url,$api,$id)
    {
        $this->apiToken = $api;
        $this->url = $url;
        $this->chatID = $id;
    }

    public function getUpdates(){
        $linkForGetUpdates = $this->url.$this->apiToken.'/getUpdates';

        $updateArr = file_get_contents($linkForGetUpdates);
        print_r($updateArr);
    }

    public function sendMessage($text){
        $url = $this->url.$this->apiToken.'/sendMessage?chat_id='.$this->chatID.'&text='.urlencode($text);
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