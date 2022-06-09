<?php
namespace App\Libs;

//通用API回應格式
class APIMsg
{
  private $msg;
  public function __construct() {

    $this->msg=[
                 'ret' => 0,
                 'data' => null,
                 'code' => '',
                 'msg' => __('error.default'),
                 'msg_params' => null,
                 'err_msg' => [],
               ];

    return $this->msg;
  }

  /*
  @desc 回傳當前保存的訊息紀錄
  @param null
  @return array APIMsg
  */
  public function get() {
    return $this->msg;
  }

  /*
  @desc 設置回傳訊息紀錄
  @param array $setMsg
  @return array APIMsg
  */
  public function set($setMsg=[]) {

    if(!empty($setMsg)) {
      $this->msg=array_merge($this->msg,$setMsg);
    }
    return $this->get();
  }


}
