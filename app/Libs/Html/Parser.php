<?php
namespace App\Libs\Html;
use voku\helper\HtmlDomParser;
use Spatie\Browsershot\Browsershot;
use Illuminate\Support\Arr;
//通用API回應格式
class Parser
{

  public function __construct() {

  }

  public function url_mine($url='') {

    try {
        $html = HtmlDomParser::file_get_html($url);
    } catch (\Exception $e) {
        return null;
    }


    $url_md5=md5($url);

    $mine['title']=$html->findOne('title');
    $mine['meta']=$html->find('meta');
    $mine['link']=$html->find('link');
    $mine['body']=$html->findOne('body');
    $meta_data=null;
    if(!empty($mine['meta'])){
        foreach ($mine['meta'] as $meta) {
            if ($meta->hasAttribute('content')) {
                $meta_data[$meta->getAttribute('name')][] = $meta->getAttribute('content');
            }
            //fb
            if ($meta->hasAttribute('property')) {
                $meta_data[$meta->getAttribute('property')][] = $meta->getAttribute('content');
            }
        }
    }

    $screenshot_path=storage_path("screenshot/{$url_md5}.jpg");
    try {
        $screenshot=Browsershot::url($url)->save($screenshot_path);
    } catch (\Exception $e) {
        $screenshot=null;
    }

    $base_mine=[
        "url_uuid" => $url_md5, //@for db
        "url" => $url,
        'title' => empty($mine['title']) ? '' : $mine['title']->text,
        'image' => empty($meta_data['og:image']) ? '' : Arr::first($meta_data['og:image']),
        'description' => empty($meta_data['description']) ? '' : Arr::first($meta_data['description']),
        'parser_date' => date("YmdH0000"),//@for db index
        'created_at' => date("Y-m-d H:i:s"),
        'screenshot' => $screenshot_path,
        'body' => empty($mine['body']) ? '' : $mine['body']->text,//@for db or storage (dig again)

    ];







    return $base_mine;
  }

  public function url_digger($uurl_uuid='') {
  }


}
