<?php


namespace TelegramBot;


use Models\JobsList;

class MyBot extends JobsList
{
    private $apiToken;
    private $url;
    private $chatID;
    private $msgDate = 0;

    public function __construct($urlDou, $urlWork, $url,$api,$id)
    {
        parent::__construct($urlDou, $urlWork);
        $this->apiToken = $api;
        $this->url = $url;
        $this->chatID = $id;
    }

    private function getUpdates()
    {
        $data = file_get_contents($this->url . $this->apiToken . "/getUpdates");
        $json = json_decode($data);
        return end($json->result);
    }

    public function run()
    {
        $lastMessageArr = $this->getUpdates();
        $textLastMsg = $lastMessageArr->message->text;
        if($textLastMsg == 'giveJobs' and $this->msgDate != $lastMessageArr->message->date){
            $this->msgDate = $lastMessageArr->message->date;
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