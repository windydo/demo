<?php

namespace App\Http\Controllers\Html;

use Illuminate\Routing\Controller ;
use Illuminate\Http\Request;
use View;
use App\Libs\APIMsg;
use App\Libs\Tools;
use App\Libs\Html\Parser;


class DefaultController extends Controller
{

    public function mine()
    {
        $url=request()->input('url');
        $rs_msg=new APIMsg();
        $parser=new Parser();

        $data=$parser->url_mine($url);

        if(!empty($data)){
            return $rs_msg->set(['ret'=>1,'code'=>'api.ok','msg'=>__('api.ok'),'data'=>$data]);
        }
        return $rs_msg->set(['ret'=>0,'code'=>'api.failed','msg'=>__('api.failed')]);
    }
    public function demo()
    {
        $rs_msg=new APIMsg();
        $parser=new Parser();

        $data=$parser->url_mine("https://www.oneone.com.tw/");

        if(!empty($data)){
            return $rs_msg->set(['ret'=>1,'code'=>'api.ok','msg'=>__('api.ok'),'data'=>$data]);
        }
        return $rs_msg->set(['ret'=>0,'code'=>'api.failed','msg'=>__('api.failed')]);
    }
}
