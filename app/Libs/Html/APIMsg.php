<?php
namespace App\Libs\Html;
use voku\helper\HtmlDomParser;
//通用API回應格式
class Parser
{

  public function __construct() {

  }

  public function url_mine($url='') {
    $html = HtmlDomParser::file_get_html($url);
    $mine['title']=$html->find('title');
    $mine['meta']=$html->find('meta');

    print_r($mine);




  }


}
