<?php
namespace App\Libs;

use Illuminate\Http\Request;
use App\Libs\Member\Member;
use File;
use Storage;

class LogsEv
{
    static public function add($log_name,$campaign='',$input=[]){
        $ndate=date("Ymd");
        $ntime=date("H00");
        $log_group=empty($log_name) ? 'ETC' : $log_name;
        $campaign=empty($campaign) ? 'None' : $campaign;
        $file_name="/Log/{$ndate}/{$log_name}_{$ntime}.log";

        if(!is_array($input)){
            $arr=['text'=>$input];
            $input=$arr;
        }

        $text=date("Y-m-d H:i:s")."\t#{$campaign}#\t".json_encode($input, JSON_UNESCAPED_UNICODE);


        Storage::disk('log')->append($file_name,$text);
    }

}
