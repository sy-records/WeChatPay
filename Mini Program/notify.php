<?php
/**
 * @authors ShenYan (52o@qq52o.cn)
 * @date    2018-02-06 11:10:09
 */
$postXml = $GLOBALS["HTTP_RAW_POST_DATA"]; //接收微信参数 
// 接受不到参数可以使用 file_get_contents("php://input"); PHP 高版本中$GLOBALS 好像已经被废弃了
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

$total_fee = $attr['total_fee'];
$open_id = $attr['openid'];
$out_trade_no = $attr['out_trade_no'];
$time = $attr['time_end'];

echo exit('<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>'); 
//在微信的异步通知后，也需要给微信服务器，返回一个信息，只不过微信的所有数据格式都是 xml 的，所以我们在返回一个数据给微信即可。
