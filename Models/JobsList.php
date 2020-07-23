<?php

namespace Models;
include 'simple_html_dom.php';

class JobsList
{
    private $urlForDou;
    private $urlForWork;
    private $vacancyLinks = [];
    private $vacancyContent = [];
    protected $allVacancies = [];

    public function __construct($urlDou, $urlWork)
    {
        $this->urlForDou = $urlDou;
        $this->urlForWork = $urlWork;

    }

    private function getLinksFromDou()
    {
        $html = file_get_html($this->urlForDou);
        if($html->innertext!='' and count($html->find('a[class=vt]')))
        {
           foreach ($html->find('a[class=vt]') as $item){
               $this->vacancyLinks[] = $item->href;
           }
        }
        $html->clear();
        unset($html);
    }

    private function getLinksFromWork()
    {
        $html = file_get_html($this->urlForWork);
        if($html->innertext!='' and count($html->find('div[class=card card-hover card-visited wordwrap job-link] h2 a')))
        {
            foreach ($html->find('div[class=card card-hover card-visited wordwrap job-link] h2 a') as $item){
                $this->vacancyLinks[] = $item->href;
            }
        }

        $html->clear();
        unset($html);
    }

    private function getContentFromLinksDou()
    {
        $this->getLinksFromDou();
        foreach ($this->vacancyLinks as $link){
            $vacancyHTML = file_get_html($link);
            if($vacancyHTML->innertext!='' and count($vacancyHTML->find('h1[class=g-h2], div[class=sh-info] span[class=place], h3[class=g-h3], div[class=text]')))
            {
                foreach ($vacancyHTML->find('h1[class=g-h2], div[class=date], div[class=sh-info] span[class=place], h3[class=g-h3], div[class=text b-typo vacancy-section] p') as $item){
                    $this->vacancyContent[] = strip_tags($item->innertext).'\n';
                }
            }
            $this->vacancyContent[] = $link;
            $this->allVacancies[] = stripcslashes(implode($this->vacancyContent));
            unset($this->vacancyContent);
            $vacancyHTML->clear();
            unset($vacancyHTML);
            unset($this->vacancyLinks);
        }
    }

    private function getContentFromLinksWork()
    {
        $this->getLinksFromWork();
        foreach ($this->vacancyLinks as $link){
            $vacancyHTML = file_get_html('https://www.work.ua'.$link);
            if($vacancyHTML->innertext!='' and count($vacancyHTML->find('p[class=cut-bottom-print] span[class=text-muted]')))
            {
                foreach ($vacancyHTML->find('p[class=cut-bottom-print] span[class=text-muted], h1[id=h1-name], p[class=text-indent add-top-sm], h2 span, div[id=job-description] p, div[id=job-description] ul li') as $item){
                    $this->vacancyContent[] = strip_tags($item->innertext).'\n';
                }
            }
            $this->vacancyContent[] = 'https://www.work.ua'.$link;
            $this->allVacancies[] = str_replace('&nbsp;',' ',stripcslashes(implode($this->vacancyContent)));
            unset($this->vacancyContent);
            $vacancyHTML->clear();
            unset($vacancyHTML);
            unset($this->vacancyLinks);
        }
    }

    protected function pushVacanciesTextToSharedArr()
    {
        $this->getContentFromLinksDou();
        $this->getContentFromLinksWork();
    }

}