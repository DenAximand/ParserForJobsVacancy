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

    public function getUpdates()
    {
        $data = file_get_contents($this->url . "/getUpdates");
        $json = json_decode($data);
//        print_r($json->result);
        return end($json->result);
    }

    public function sendMessage($message)
    {
        $data = array('chat_id' => $this->chatID, 'text' => $message);
        $options = array(
            'http' => array(
                'method' => 'POST',
                'content' => json_encode($data),
                'header' =>  "Content-Type: application/json\r\n" .
                    "Accept: application/json\r\n"));
        $context = stream_context_create($options);
        $result = file_get_contents($this->url .$this->apiToken. "/sendMessage", 0, $context);
        return json_decode($result);
    }

}