<?php

namespace Models;
include 'simple_html_dom.php';
include_once './Logging/LogController.php';

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
        try{
            $html = file_get_html($this->urlForDou);
            if($html->innertext!='' and count($html->find('a[class=vt]')))
            {
                foreach ($html->find('a[class=vt]') as $item){
                    $this->vacancyLinks[] = $item->href;
                }
            }
            if(!$html){
                throw new \Exception('Filed to get data from the site: jobs.dou.ua');
            }
            $html->clear();
            unset($html);
        } catch (\Exception $e){
            $text = $e->getMessage();
            createRecordInLogFile($text);
        }
    }

    private function getLinksFromWork()
    {
        try{
            $html = file_get_html($this->urlForWork);
            if($html->innertext!='' and count($html->find('div[class=card card-hover card-visited wordwrap job-link] h2 a')))
            {
                foreach ($html->find('div[class=card card-hover card-visited wordwrap job-link] h2 a') as $item){
                    $this->vacancyLinks[] = $item->href;
                }
            }
            if (!$html){
                throw new \Exception('Filed to get data from the site: work.ua');
            }
            $html->clear();
            unset($html);
        } catch (\Exception $e){
            $text = $e->getMessage();
            createRecordInLogFile($text);
        }
    }

    private function getContentFromLinksDou()
    {
        try{
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
                if(!$vacancyHTML){
                    throw new \Exception('Filed to get data from the link: '.$link);
                }
                unset($this->vacancyContent);
                $vacancyHTML->clear();
                unset($vacancyHTML);
                unset($this->vacancyLinks);
            }
        } catch (\Exception $e){
            $text = $e->getMessage();
            createRecordInLogFile($text);
        }
    }

    private function getContentFromLinksWork()
    {
        try{
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
                if(!$vacancyHTML){
                    throw new \Exception('Filed to get data from the link: '.'https://www.work.ua'.$link);
                }
                unset($this->vacancyContent);
                $vacancyHTML->clear();
                unset($vacancyHTML);
                unset($this->vacancyLinks);
            }
        } catch (\Exception $e){
            $text = $e->getMessage();
            createRecordInLogFile($text);
        }
    }

    protected function pushVacanciesTextToSharedArr()
    {
        $this->getContentFromLinksDou();
        $this->getContentFromLinksWork();
    }

}