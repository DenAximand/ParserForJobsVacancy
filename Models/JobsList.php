<?php

namespace Models;
include 'simple_html_dom.php';

class JobsList
{
    protected $vacancyLinks = [];

//    protected function getSearchCode()
//    {
//        $html = file_get_html('https://jobs.dou.ua/vacancies/?city=%D0%94%D0%BD%D0%B5%D0%BF%D1%80&category=PHP&exp=0-1');
//
//    }

    public function go()
    {
        $html = file_get_html('https://jobs.dou.ua/vacancies/?city=%D0%94%D0%BD%D0%B5%D0%BF%D1%80&category=PHP&exp=0-1');
        for($i=0;$i<=100;$i++)
        {
            $elementArr = $html->find('a[class=vt]',$i)->href;
//            $type=gettype($elementArr);
//            print_r($type);
            if(array_key_exists($i,$html)){
                break;
            }
            $this->vacancyLinks[]=$elementArr;
        }
        print_r($this->vacancyLinks);
    }
}