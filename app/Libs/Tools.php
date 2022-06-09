<?php
namespace App\Libs;

use App\Models\Table\TableSerializedModel;



class Tools //extends Repository
{

    static $CONF_PHONE_REGION=[
        '886' => '台灣',
        '86' => '中國',
        '852' => '香港',
        '853' => '澳門',
    ];




    public static function getRequst(){
        $json_string=file_get_contents('php://input','r');
        $receive=json_decode($json_string,JSON_UNESCAPED_UNICODE);
        $request=!empty($receive) ? $receive : array();

        if(!empty($_REQUEST['request'])){
            $receive = json_decode($_REQUEST['request'],JSON_UNESCAPED_UNICODE);
            $request=!empty($receive) ? $receive : array();
        }
        if(empty($request)){
            $request=!empty($_REQUEST) ? $_REQUEST : array();
        }
        if(!empty($request)){
            foreach($request as $key => $val){
                if(is_array($val)){
                    $request[$key]=$val;
                }
                else{
                    $request[$key]=trim($val);
                }
            }
        }
        return $request;
    }

    static public function filterData(&$ary,$ary_keys){
        if(!is_array($ary_keys)){ $ary_keys=explode(",",$ary_keys);}
        if(!empty($ary_keys)){
            foreach($ary_keys as $key){unset($ary[$key]);}
        }
        return $ary;
    }


    static public function grantSerialByCode($code = "",$serial_cnt = 12)
    {
        $str = $code.self::grantSerial($serial_cnt);
        return $str;
    }

    static public function grantSerial($len = 12)
    {
        $str = "";
        for ($i = 0; $i < $len; $i++)
        {
            //每4-5碼產生2個數字碼
            if ($i == 0) {
                $chr = rand(65,90);
            }//A-Z
            else if ($i%3 == 0) {
                $chr = rand(48,57);
            }//0-9
            else if ($i%4 == 0) {
                $chr = rand(48,57);
            }//0-9
            else{$chr = rand(65,90);
            }//A-Z
            //chr = 1、0、I、O 重新產生
            if ($chr == 73 || $chr == 79 || $chr == 48 || $chr == 49)
            {
                while(1)
                {
                    $chr = rand(65,90);
                    if($chr == 73 || $chr == 79 || $chr == 48 || $chr == 49){
                    }
                    else{break;
                    }
                }
            }
            $str.= chr($chr);
        }
        return $str;
    }
    static public function grantInsertID($target_slot='member',$add_value=1)
    {
        $table=new TableSerializedModel();

        $num=$table->insertId($target_slot,$add_value);

        return $num;
    }

    static public function toPairs($arr)
    {
        if(empty($arr)){return $arr;}
        if(!is_array($arr)){return $arr;}
        foreach($arr as $data){
            $data1=array_values($data);
            $arr1[$data1[0]]=$data1[1];

        }
        return $arr1;
    }
    static public function toHashArray($arr,$unique_key)
    {
        $arr1=null;
        if(empty($arr)){return null;}
        if(!is_array($arr)){return null;}
        foreach($arr as $data){
            if(empty($data[$unique_key])){return null;}
            $arr1[$data[$unique_key]]=$data;
        }
        return $arr1;
    }

    static public function setSelect($obj_id,$obj_data,$option_id="",$css="",$js='')
    {
        $option_id=empty($option_id) ? "" : $option_id;
        if($obj_data)
        {
            $txt_list = '';
            foreach($obj_data as $obj_option => $obj_name)
            {
                $str1="";

                if(is_array($option_id))
                {
                    if(in_array($obj_option,$option_id)){$str1="selected";}
                }else
                {
                    if($option_id==$obj_option){$str1="selected";}
                }

                $txt_list.="<option value=\"{$obj_option}\" {$str1}>{$obj_name}</option>\n";
            }
            $txt="<select name=\"{$obj_id}\" id=\"{$obj_id}\" {$css} {$js} >\n{$txt_list}</select>\n";
        }
        return $txt;
    }
    static public function setSelectAll($obj_id,$obj_data,$option_id="",$css="")
    {
        if($option_id===0){
            $option_id="zero";
        }
        $obj_data1['all']="All";

        if(!empty($obj_data))
        {
            foreach($obj_data as $obj_option => $obj_name){
                if($obj_option===0){
                    $obj_data1["zero"]=$obj_name;
                }
                else{
                    $obj_data1[$obj_option]=$obj_name;
                }
            }
        }
        $obj_data=$obj_data1;

        if($obj_data)
        {
            $txt_list = '';
            foreach($obj_data as $obj_option => $obj_name)
            {
                $str1="";
                if($option_id==$obj_option){$str1="selected";}
                $txt_list.="<option value=\"{$obj_option}\" {$str1}>{$obj_name}</option>\n";
            }
            $txt="<select name=\"{$obj_id}\" id=\"{$obj_id}\" {$css}>\n{$txt_list}</select>\n";
        }
        return $txt;
    }

    public  static function getQRCode($url,$widthHeight ='150',$EC_level='L',$margin='0') {
        $url = urlencode($url);
        $qrcode_url="http://chart.apis.google.com/chart?chs={$widthHeight}x{$widthHeight}&cht=qr&chld={$EC_level}|{$margin}&chl={$url}";
        return $qrcode_url;
    }


    public static function cutPage($max_cnt,&$page=1,$a_page_show=10,$query="")
    {
        $queryAry=array();
        $prev_max=0;
        $next_max=0;
        $add_url="";
        if($page<0){$page=1;}
        if($query)
        {
            foreach($query as $queryKey => $queryData)
            {
                $add_url.=empty($add_url) ? "?{$queryKey}={$queryData}" :  "&{$queryKey}={$queryData}";
            }
            $url=$add_url;
        }
        else
        {
            $url="";
        }
        if($page<=0){
            $page=1;
        }
        $base_row=10;
        $base_row_max=$base_row*$a_page_show;
        $max_end_page=floor($max_cnt/$base_row_max)*$base_row+1;
        $page_str['row_split_pages']=ceil($max_cnt/$a_page_show);
        $page_str['row_query_str']=$url;

        ///上一頁
        if($page-1<=0){
            $prev_page=1;
        }
        else{$prev_page=$page-1;
        }
        $fix_url=str_replace("[{page}]",$prev_page,$url);
        if($page>1) //顯示控制
        {
            $page_str['row_prev_page']=$fix_url;
        }

        ///下一頁
        if($page>=$page_str['row_split_pages']){
            $next_page=$page_str['row_split_pages'];
        }
        else{$next_page=$page+1;
        }
        $fix_url=str_replace("[{page}]",$next_page,$url);
        if($page<$page_str['row_split_pages']) //顯示控制
        {
            $page_str['row_next_page']=$fix_url;
        }
        $row_beg_i=$page;
        $row_end_i=$page;
        $tmp_k=0;
        $tmp=$row_beg_i;
        for($i=1;$i<=4;$i++)
        {
            $tmp--;
            if($tmp<1){
                $tmp=1;$prev_max=1;$tmp_k++;
            }
            $row_beg_i=$tmp;
        }
        $after=5;
        if($prev_max==1){
            $after+=$tmp_k;
        }
        $tmp_k=0;
        $tmp=$row_end_i;
        for($i=1;$i<=$after;$i++)
        {
            $tmp++;
            if($tmp>$page_str['row_split_pages']){
                $tmp=$page_str['row_split_pages'];$next_max=1;$tmp_k++;
            }
            $row_end_i=$tmp;
        }
        if($next_max=1)
        {
            $tmp=$row_beg_i;
            for($i=1;$i<=$tmp_k;$i++)
            {
                $tmp--;
                if($tmp<1){
                    $tmp=1;
                }
                $row_beg_i=$tmp;
            }
        }

        /////中間固定秀處理
        if($page_str['row_split_pages']>0)
        {
            for($i=$row_beg_i;$i<=$row_end_i;$i++)
            {
                $page_class="";
                $fix_url=str_replace("[{page}]",$i,$url);
                if($page==$i){
                    $page_str['row_body'][$i]['mark']="high_line";
                }
                $page_str['row_body'][$i]['val']=$i;
                $page_str['row_body'][$i]['url']=$fix_url;

                //if(($i*$a_page_show)>=$max_cnt){break;}
            }
        }



        return $page_str;
    }

    ///
    // $page=safe_int($_REQUEST["page"]);
    // $sqlstr="select count(*) as cnt from back.member where 1=1 {$sqladd} ";
    // //(ps:$sqlstr can give a number)
    // $aPageShow=10;
    // $query=array(	//search query
    //	"page" =>"[{page}]", //*must write this format,you can change array key for request it
    //	"gender" => $gender,
    //	"status" => $status,
    //	"birthday" => $birthday,
    //	"birthday_sdate" => $birthday_sdate,
    //	"birthday_edate" => $birthday_edate,
    // );
    //
    // $pageHtml=cutBackEndPageHtml($sqlstr,$page,$aPageShow,$query);
    // $offsetPage=($page-1)*$aPageShow;
    // //(ps:$page 在 cutBackEndPageHtml 處理後不會小於1,所以$offsetPage可以直接減1計算 )
    public static function cutPageHtml($maxCnt,&$nowPage,$aPageShow=10,$query="")
    {
        if($nowPage<=0){
            $nowPage=1;
        }
        $pageInfo=self::cutPage($maxCnt,$nowPage,$aPageShow,$query);

        $html="<div class=\"bb_page_bar\" > ";
        $html.="<ul class=\"pagination bb_page\">";
        if(!empty($pageInfo['row_prev_page']))
        {
            $html.="<li class=\"page-item prev\"><a class=\"page-link\" href=\"{$pageInfo['row_prev_page']}\">上一頁</a></li>";
        }
        if(!empty($pageInfo['row_body'])){
            foreach($pageInfo['row_body'] as $rowData)
            {
                $html.="<li class=\"page-item ".(empty($rowData['mark']) ? "" : "active")."\"><a class=\"page-link\" href=\"{$rowData['url']}\">{$rowData['val']}</a></li>";
            }
        }
        if($pageInfo['row_split_pages']>0)
        {
            $html.="<li class=\"page-item\" ><a class=\"page-link\">共{$pageInfo['row_split_pages']}頁</a></li>";
        }

        if(!empty($pageInfo['row_next_page']))
        {
            $html.="<li  class=\"page-item next\"><a class=\"page-link\" href=\"{$pageInfo['row_next_page']}\">下一頁</a></li>";
        }
        $html.="</ul>";
        $html.="</div>";




        return $html;
    }

	public static function getIp()
    {
        foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key){
            if (array_key_exists($key, $_SERVER) === true){
                foreach (explode(',', $_SERVER[$key]) as $ip){
                    $ip = trim($ip); // just to be safe
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false){
                        return $ip;
                    }
                }
            }
        }
    }

	public static function buildParam($paramArray) {
		return base64_encode(http_build_query($paramArray));
	}

	public static function parseParam($p) {
		parse_str(base64_decode($p), $params);
		return $params;
	}

}

