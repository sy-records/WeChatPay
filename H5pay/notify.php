<?php
$postXml = $GLOBALS["HTTP_RAW_POST_DATA"]; //接收微信参数 
// $postXml = file_get_contents("php://input"); 
// 接收不到参数可以使用 file_get_contents("php://input"); PHP 高版本中$GLOBALS 好像已经被废弃了
if (empty($postXml)) {
    return false;
}

//将 xml 格式转换成数组
function xmlToArray($xml) {

    //禁止引用外部 xml 实体 
    libxml_disable_entity_loader(true);

    $xmlstring = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);

    $val = json_decode(json_encode($xmlstring), true);

    return $val;
}

$attr = xmlToArray($postXml);
//file_put_contents('./log.txt',$attr); //打印数据
$total_fee = $attr['total_fee']; //总金额
$open_id = $attr['openid'];
$out_trade_no = $attr['out_trade_no'];
$time = $attr['time_end'];
