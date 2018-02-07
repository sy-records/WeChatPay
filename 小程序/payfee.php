<?php
/**
 * @authors ShenYan (52o@qq52o.cn)
 * @date    2018-02-06 11:07:44
 */
include 'WeixinPay.php';
$appid=''; //小程序 appid
$openid= $_POST['id']; //微信用户的openid
$mch_id=''; //微信支付商户支付号
$key=''; //Api 密钥
$out_trade_no = date('YmdHis').rand(100,999);
$total_fee = $_POST['fee'];
if(empty($total_fee)) //押金
{
    $body = "充值押金";
    $total_fee = floatval(99*100);
}
 else {
     $body = "充值余额";
     $total_fee = floatval($total_fee*100);
 }
$weixinpay = new WeixinPay($appid,$openid,$mch_id,$key,$out_trade_no,$body,$total_fee);
$return=$weixinpay->pay();

echo json_encode($return);