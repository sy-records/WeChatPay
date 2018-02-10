<?php
$money= 1;//充值金额
$userip = get_client_ip();          //获得用户设备IP 自己网上百度去
$appid  = "";                  //应用APPID
$mch_id = "";                  //微信支付商户号
$key    = "";                 //微信商户API密钥

$out_trade_no = date('YmdHis').rand(1000,9999);//平台内部订单号
$nonce_str = createNoncestr();//随机字符串
$body = "H5充值";//内容
$total_fee = $money; //金额
$spbill_create_ip = $userip; //IP
$notify_url = "http://XXXXXX.com/wxpay/notify.php"; //回调地址
$trade_type = 'MWEB';//交易类型 具体看API 里面有详细介绍
$scene_info ='{"h5_info":{"type":"Wap","wap_url":"http://XXXXXX.com","wap_name":"支付"}}';//场景信息 必要参数

$signA ="appid=$appid&attach=$out_trade_no&body=$body&mch_id=$mch_id&nonce_str=$nonce_str&notify_url=$notify_url&out_trade_no=$out_trade_no&scene_info=$scene_info&spbill_create_ip=$spbill_create_ip&total_fee=$total_fee&trade_type=$trade_type";
$strSignTmp = $signA."&key=$key"; //拼接字符串  注意顺序微信有个测试网址 顺序按照他的来 直接点下面的校正测试 包括下面XML  是否正确

$sign = strtoupper(MD5($strSignTmp)); // MD5 后转换成大写

$post_data = "<xml>
                   <appid>$appid</appid>
                   <mch_id>$mch_id</mch_id>
                   <body>$body</body>
                   <out_trade_no>$out_trade_no</out_trade_no>
                   <total_fee>$total_fee</total_fee>
                   <spbill_create_ip>$spbill_create_ip</spbill_create_ip>
                   <notify_url>$notify_url</notify_url>
                   <trade_type>$trade_type</trade_type>
                   <scene_info>$scene_info</scene_info>
                   <attach>$out_trade_no</attach>
                   <nonce_str>$nonce_str</nonce_str>
                   <sign>$sign</sign>
               </xml>";//拼接成XML 格式

$url = "https://api.mch.weixin.qq.com/pay/unifiedorder";//微信传参地址
$dataxml = postXmlCurl($post_data,$url); //后台POST微信传参地址  同时取得微信返回的参数    POST 方法我写下面了
$objectxml = (array)simplexml_load_string($dataxml, 'SimpleXMLElement', LIBXML_NOCDATA); //将微信返回的XML 转换成数组
function createNoncestr( $length = 32 ){
    $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
    $str ="";
    for ( $i = 0; $i < $length; $i++ )  {
        $str.= substr($chars, mt_rand(0, strlen($chars)-1), 1);
    }
    return $str;
}
function postXmlCurl($xml,$url,$second = 30){
    $ch = curl_init();
    //设置超时
    curl_setopt($ch, CURLOPT_TIMEOUT, $second);
    curl_setopt($ch,CURLOPT_URL, $url);
    curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
    curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,FALSE);
    //设置header
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    //要求结果为字符串且输出到屏幕上
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    //post提交方式
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
    //运行curl
    $data = curl_exec($ch);
    //返回结果
    if($data){
        curl_close($ch);
        return $data;
    }else{
        $error = curl_errno($ch);
        curl_close($ch);
        echo "curl出错，错误码:$error"."<br>";
    }
}
function get_client_ip($type = 0) {
    $type       =  $type ? 1 : 0;
    $ip         =   'unknown';

    if ($ip !== 'unknown') return $ip[$type];
    if($_SERVER['HTTP_X_REAL_IP']){//nginx 代理模式下，获取客户端真实IP
        $ip=$_SERVER['HTTP_X_REAL_IP'];
    }elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {//客户端的ip
        $ip     =   $_SERVER['HTTP_CLIENT_IP'];
    }elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {//浏览当前页面的用户计算机的网关
        $arr    =   explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        $pos    =   array_search('unknown',$arr);
        if(false !== $pos) unset($arr[$pos]);
        $ip     =   trim($arr[0]);
    }elseif (isset($_SERVER['REMOTE_ADDR'])) {
        $ip     =   $_SERVER['REMOTE_ADDR'];//浏览当前页面的用户计算机的ip地址
    }else{
        $ip=$_SERVER['REMOTE_ADDR'];
    }

    // IP地址合法验证
    $long = sprintf("%u",ip2long($ip));
    $ip   = $long ? array($ip, $long) : array('0.0.0.0', 0);

    return $ip[$type];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>微信支付</title>
    <style type="text/css">
        body{
            font-family: "Microsoft YaHei";
        }
        .pay-box{
            position: absolute;
            top: 50%;
            margin-top: -516px;
            left: 50%;
            margin-left: -320px;
        }
        .ico{
            width: 240px;
            height: 240px;
            border-radius: 120px;
            background: #3FB837;
            color: #fff;
            display: inline-block;
            font-size: 160px;
            line-height: 240px;
        }
        .txt{
            font-size: 42px;
            padding-top: 30px;
            color: #333;
        }
        .val{
            font-size: 80px;
            font-weight: bold;
        }
        .pay{
            width: 640px;
            height: 100px;
            margin-top: 100px;
            padding: 20px;
            border-radius: 10px;
            font-size:42px;
            color: #fff;
            background: #07BF05;
            border: 0px;
            text-align: center;
        }
        a{
            color: #fff;
            background: transparent !important;
        }
    </style>
</head>
<body>
    <div class="pay-box" style="text-align: center;">
        <div class="ico">
            ￥
        </div>
        <div class="txt">
            支付金额
        </div>
        <div class="val">
            ￥<span><?php echo $total_fee/100 ?></span>
        </div>
        <a class="pay" href="<?php echo $objectxml['mweb_url'] ?>"><button class="pay">确认支付</button></a>
    </div>
</body>
</html>