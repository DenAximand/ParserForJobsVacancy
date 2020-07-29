<?php

namespace TelegramBot;

use Models\JobsList;

include_once './Logging/LogController.php';

class MyBot extends JobsList
{
    private $apiToken;
    private $url;
    private $chatID;
    private $msgID = 0;

    public function __construct($urlDou, $urlWork, $url,$api,$id)
    {
        parent::__construct($urlDou, $urlWork);
        $this->apiToken = $api;
        $this->url = $url;
        $this->chatID = $id;
    }

    public function getUpdates()
    {
        try{
            $data = file_get_contents($this->url . $this->apiToken . "/getUpdates");
            $json = json_decode($data);
            if(!$json){
                throw new \Exception('Missing data from getUpdates');
            }
            return end($json->result);
        } catch (\Exception $e){
            $text = $e->getMessage();
            createRecordInLogFile($text);
            die();
        }
    }

    public function run()
    {
        $lastMessageArr = $this->getUpdates();
        $textLastMsg = $lastMessageArr->message->text;
        if($textLastMsg == 'giveJobs' and $this->msgID != $lastMessageArr->message->message_id){
            $this->msgID = $lastMessageArr->message->message_id;
            parent::pushVacanciesTextToSharedArr();
            foreach ($this->allVacancies as $item){
                $text = $item;
                $this->sendMessage($text);
            }
        }
        unset($this->allVacancies);
    }

    private function sendMessage($message)
    {
        try{
            $data = array('chat_id' => $this->chatID, 'text' => $message);
            $options = array(
                'http' => array(
                    'method' => 'POST',
                    'content' => json_encode($data),
                    'header' =>  "Content-Type: application/json\r\n" .
                        "Accept: application/json\r\n"));
            $context = stream_context_create($options);
            $result = file_get_contents($this->url .$this->apiToken. "/sendMessage", 0, $context);
            if(!$message){
                throw new \Exception('No Text in var $text');
            } elseif (!$data['chat_id']){
                throw new \Exception('No data on var $chatID');
            }
            return json_decode($result);
        } catch (\Exception $e){
            $text = $e->getMessage();
            createRecordInLogFile($text);
            die();
        }
    }

}