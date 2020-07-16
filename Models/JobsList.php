<?php

namespace Models;
include 'simple_html_dom.php';

class JobsList
{
    protected $vacancyLinks = [];
    protected $vacancyContent = [];
    protected $allVacancy = [];

    protected function getLinks()
    {
        $html = file_get_html('https://jobs.dou.ua/vacancies/?city=%D0%94%D0%BD%D0%B5%D0%BF%D1%80&category=PHP&exp=0-1');
        if($html->innertext!='' and count($html->find('a[class=vt]')))
        {
           foreach ($html->find('a[class=vt]') as $item){
               $this->vacancyLinks[] = $item->href;
           }
        }

        $html->clear();
        unset($html);
    }

    public function getContentFromLinks()
    {
        $this->getLinks();
        foreach ($this->vacancyLinks as $link){
            $vacancyHTML = file_get_html($link);
            if($vacancyHTML->innertext!='' and count($vacancyHTML->find('h1[class=g-h2], div[class=sh-info] span[class=place], h3[class=g-h3], div[class=text]')))
            {
                foreach ($vacancyHTML->find('h1[class=g-h2], div[class=date], div[class=sh-info] span[class=place], h3[class=g-h3], div[class=text b-typo vacancy-section] p') as $item){
                    $this->vacancyContent[] = $item->innertext;
                }
            }
            $this->vacancyContent[] = $link;
            $this->allVacancy[] = $this->vacancyContent;
            unset($this->vacancyContent);
            $vacancyHTML->clear();
            unset($vacancyHTML);
        }
        print_r($this->allVacancy);
    }
}