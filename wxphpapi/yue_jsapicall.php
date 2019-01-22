<?php
/**
 * JS_API支付demo
 * ====================================================
 * 在微信浏览器里面打开H5网页中执行JS调起支付。接口输入输出数据格式为JSON。
 * 成功调起支付需要三个步骤：
 * 步骤1：网页授权获取用户openid
 * 步骤2：使用统一支付接口，获取prepay_id
 * 步骤3：使用jsapi调起支付
 */
require("../checkindex.php");
$shopinclude_path="/shop/template/sys/temp_shop/";
$act = isset($_REQUEST['act']) ? str_filter($_REQUEST['act']) : 'info';
$uid=41;
$yhjid=$_REQUEST['yhjid'];

if($act=="paymoneys"){ //生成订单
    //订单名称
    $subject = "余额充值";
    //付款金额
    $total_fee =str_filter($_POST['paymoneys']);

    //$total_fee =0.01;
    //付款方式
    $paytypes =str_filter(intval($_POST['paytypes']));
    if($total_fee==0){
        jsAlert('抱歉，充值金额不能为空',0,'','window.history.go(-1);');
    }
    //    充值记录
    $logarr = array(
        'vipid'=>$vipinfo['id'],
        'changeTime'=>time(),
        'svalue'=>$total_fee,
        'status'=>2,
        'type'=>1,
        'yhjid'=>$yhjid
    );
    $logaddid=$db->insert(recharge_log,$logarr);
    //如果使用优惠劵
    if (!empty($yhjid)){
        $zsmoney = $db->getOne("select zsmoney from wx_CouponInfo where id = ".$yhjid);
        $total_fee = $total_fee - $zsmoney;
    }
    $articlearr = array(
        'uid'  => $uid,
        'paymoney'=> $total_fee,
        'addtime'=> time(),
        'titles'=> $subject,
        'status'=> 1,
        'paytypes'=> 6,  //微支付
        'vipid'=>$vipinfo["id"]
    );
    $addids=$db->insert('wx_pay_orders',$articlearr);


    if($addids){
        $czinfo=$db->getRow("select * from wx_CZ_favorable where uid={$uid} ");
        $mebers= array(
            'types'  => 7,
            'addTime'=>time(),
            'stepDate'=> $vipinfo["id"]."|",
            'uid'=> $uid,
            'xftype'=> 1,
            'cardid'=>$vipinfo["id"],
            'wxid'=> $vipinfo["wxid"]
        );

        if($czinfo){
            if($czinfo["payMoney"]<=$total_fee){ //充值金额大于等于送的金额


                /*				$out= $total_fee/$czinfo["payMoney"];
                                $out=floor($out);
                                $dmoneys=$total_fee+$out*$czinfo["faMoney"];
                                $mebers['stepDate']=$mebers['stepDate']."+".$dmoneys."|";*/


                $dmoneys=$total_fee+$czinfo["faMoney"];
                $mebers['stepDate']=$mebers['stepDate']."+".$dmoneys."|";

            }else{
                $mebers['stepDate']=$mebers['stepDate']."+".$total_fee."|";
            }
        }else{
            $mebers['stepDate']=$mebers['stepDate']."+".$total_fee."|";
        }
        //$addid_wxop=$db->insert('wx_operateInfo',$mebers);
        $addid_wxop=$db->insert('wx_operateInfo',$mebers);
        if($addid_wxop){

            $operateId=creatorderNumber_order($addid_wxop); //操作串号

            if($operateId){
                $out_trade_no=$operateId;
                //请求号
                $req_id = $out_trade_no;
                $orderoperateId="WX".$operateId;
                $db->update('wx_operateInfo',array('operateId'=> $operateId),"uid=$uid and id='$addid_wxop' and status=1");
                $db->update('recharge_log',array('orderId'=> $orderoperateId)," vipid = ".$vipinfo['id']." and id = $logaddid");
                $db->update('wx_pay_orders',array('orderId'=> $orderoperateId),"uid=$uid and id='$addids'");

                die("<meta HTTP-EQUIV=refresh Content='0;url=/weixinpaynew/yue_jsapicall.php?showwxpaytitle=1&id=".$addids."'>");
            }

        }
    }else{
        Common::jump("抱歉，系统出错，请重新提交订单","score.php?act=Gradeupinfo");
    }

}

//if(empty($_GET["id"])) C::jump('抱歉订单出错，请重试','/allhy/pay.php');
if(empty($_GET["id"])) C::jump('抱歉订单出错，请重试','/skin/charge.php');

$sqls="SELECT * FROM wx_pay_orders WHERE id='".$_GET["id"]."'  and uid=$uid ";

$orderinfo=$db->getRow($sqls);
//if(!$orderinfo) C::jump('抱歉,订单号不存在','/hykjsc/personcenter.php?xuanze=4');
if(!$orderinfo) C::jump('抱歉,订单号不存在','/skin/charge.php');
if($act=="quxiao"&&$orderinfo["status"]<>2){ //取消订单
    if($db->update('wx_pay_orders',array('status'=>4),"uid=$uid and id=".$_GET["id"]." and status=1 and vipid=".$vipinfo["id"])){
        die("<meta HTTP-EQUIV=refresh Content='0;url=/skin/vip.php'>");
//        jsAlert("取消成功！！！",'/skin/charge.php');
    }
}

include_once("WxPayPubHelper/yue_WxPayPubHelper.php");

//使用jsapi接口
$jsApi = new JsApi_pub();

//=========步骤1：网页授权获取用户openid============
//通过code获得openid
if (!isset($_GET['code']))
{
    //触发微信返回code码
    $url = $jsApi->createOauthUrlForCode(JS_API_CALL_URL."?id=".$_GET["id"]."&showwxpaytitle=1");
    //$url = $jsApi->createOauthUrlForCode(JS_API_CALL_URL."?id=".$_GET["id"]);
    //die($url);
    Header("Location: $url");
}else
{
    //die($code);
    //获取code码，以获取openid
    $code = $_GET['code'];
    $jsApi->setCode($code);
    $openid = $jsApi->getOpenId();
}
//=========步骤2：使用统一支付接口，获取prepay_id============
//使用统一支付接口
$unifiedOrder = new UnifiedOrder_pub();
//设置统一支付接口参数
//设置必填参数
//appid已填,商户无需重复填写
//mch_id已填,商户无需重复填写
//noncestr已填,商户无需重复填写
//spbill_create_ip已填,商户无需重复填写
//sign已填,商户无需重复填写
$unifiedOrder->setParameter("openid","$openid");//商品描述
$unifiedOrder->setParameter("body","余额充值");//商品描述
//自定义订单号，此处仅作举例
$timeStamp = time();
$out_trade_no = time(); //商户订单号
$out_trade_no = APPID."$timeStamp";
$unifiedOrder->setParameter("out_trade_no",$orderinfo["orderId"]);//商户订单号
$unifiedOrder->setParameter("total_fee",($orderinfo["paymoney"])*100);//总金额
//$unifiedOrder->setParameter("device_info",$logaddid);//log ID
$unifiedOrder->setParameter("notify_url",NOTIFY_URL);//通知地址
$unifiedOrder->setParameter("trade_type","JSAPI");//交易类型
//非必填参数，商户可根据实际情况选填
//$unifiedOrder->setParameter("sub_mch_id","XXXX");//子商户号
//$unifiedOrder->setParameter("device_info",$logaddid);//设备号
//$unifiedOrder->setParameter("attach","XXXX");//附加数据
//$unifiedOrder->setParameter("time_start","XXXX");//交易起始时间
//$unifiedOrder->setParameter("time_expire","XXXX");//交易结束时间
//$unifiedOrder->setParameter("goods_tag","XXXX");//商品标记
//$unifiedOrder->setParameter("openid","XXXX");//用户标识
//$unifiedOrder->setParameter("product_id","XXXX");//商品ID

$prepay_id = $unifiedOrder->getPrepayId();

//echo "prepay_id:".$prepay_id;

//=========步骤3：使用jsapi调起支付============
$jsApi->setPrepayId($prepay_id);

$jsApiParameters = $jsApi->getParameters();

//die($jsApiParameters);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta content="yes" name="apple-mobile-web-app-capable">
    <meta content="black" name="apple-mobile-web-app-status-bar-style">
    <meta content="telephone=no" name="format-detection">
    <title>余额充值</title>
    <link href="<?php echo $shopinclude_path."/"; ?>css.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="/mobilemall/style/c3e09ac36d.css">
    <link rel="stylesheet" href="/mobilemall/style/86fe49ca90.css">
</head>
<script type="text/javascript">

    //调用微信JS api 支付
    function jsApiCall()
    {
        WeixinJSBridge.invoke(
            'getBrandWCPayRequest',
            <?php echo $jsApiParameters; ?>,
            function(res){
                WeixinJSBridge.log(res.err_msg);
                //alert(res.err_code+res.err_desc+res.err_msg);
                if(res.err_msg="ok"){
                    location='/skin/vip.php';
<!--                --><?php
//                    //记录充值信息
//                    $logarr2 = array(
//                        'status'=>1,
//                    );
//                    $vipid = $vipinfo['id'];
//                    $db->update('recharge_log',$logarr2,"vipid = $vipid and id='$logaddid'");
//                    //增加用户余额和积分
//                    $oldm = $db->getOne("select moneys from wx_card_vip where id =".$vipinfo['id']);
//                    $oldP = $db->getOne("select payIntegral from wx_card_vip where id =".$vipinfo['id']);
//                    $payIntegral = array(
//                        'payIntegral'=> $oldP+$total_fee,
//                        'moneys'=> $total_fee+$oldm
//                    );
//                    $db->update('wx_card_vip',$payIntegral,"id= ".$vipinfo['id']);
//                    $db->update('wx_pay_orders',array('status'=> 2),"uid=$uid and id='$addids'");
//
//                    echo "alert(充值成功);window.href='vip.php'";
//                    ?>
                }else{
//                    alert(res.err_code+res.err_desc+res.err_msg);
                }
            }
        );
    }

    function callpay()
    {
        if (typeof WeixinJSBridge == "undefined"){
            if( document.addEventListener ){
                document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
            }else if (document.attachEvent){
                document.attachEvent('WeixinJSBridgeReady', jsApiCall);
                document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
            }
        }else{
            jsApiCall();
        }
    }
    callpay();
</script>

<!--<body>-->
<!--<header  id="header" class="header clearfix">-->
<!--    <div class="fl"><a href="javascript:history.go(-1);"><img src="--><?php ///*echo $shopinclude_path."/"; */?><!--order_fan2.png" /></a></div>-->
<!--    <span class="headtit" style="color:#A77746 ">订单详情</span>-->
<!--    <div class="fr"><a href="/mobilemall/personcenter.php"><img src="--><?php ///*echo $shopinclude_path."/"; */?><!--order_shouye.png" /></a></div>-->
<!--</header>-->
<!--<header id="header" class="u-header clearfix">-->
<!--    <div class="u-hd-left f-left">-->
<!--        <a href="javascript:history.go(-1);" class="J_backToPrev"><span class="u-icon i-hd-back"></span></a>-->
<!--    </div>-->
<!--    <span class="u-hd-tit">余额充值</span>-->
<!--    <div class="u-hd-right f-right">-->
<!--        <a href="/hykjsc/personcenter.php?xuanze=4" mars_sead="nav_home_btn"><span class="u-icon i-hd-home"></span></a>-->
<!--    </div>-->
<!--</header>-->
<!--<div class="space10"></div>-->
<!--<div class="userdiv">-->
<!--    <div class="orderlisttit">订单号：--><?php //echo  $orderinfo["orderId"];?><!--</div>-->
<!--    <div class="orderdeatilbox clearfix">-->
<!--        <div class="o_d_info">-->
<!--            <p>支付方式：<em>--><?php //echo getPayTypesByType($orderinfo["paytypes"]); ?><!--</em></p>-->
<!--            <p>下单时间：<em>--><?php //echo date('Y-m-d H:i:s',$orderinfo['addtime']);?><!--</em></p>-->
<!--            <p><span class="tit_h">订单应付金额：</span><span class="arial font16" style="color: red">¥--><?php //echo $orderinfo["paymoney"]; ?><!--</span></p>-->
<!---->
<!--        </div>-->
<!--    </div>-->
<!--</div>-->
<!---->
<?php //if($orderinfo["status"]=="1"){?>
<!--    <div class="alignc"><a href="?act=quxiao&id=--><?php //echo $orderinfo["id"];?><!--" onclick="return confirm('确认取消该订单吗');" class="btn_white_order canclorder">取消订单</a></div>-->
<!--    <div class="space30"></div>-->
<!--    <div class="space30"></div>-->
<!--    <div class="space30"></div>-->
<?php //} ?>
<?php //if($orderinfo["paytypes"]=="6"&& $orderinfo["status"]=="1"){ ?>
<!--    <div class="bottomdiv clearfix">-->
<!--        <div class="inner clearfix"><a href="#" onClick="callpay()" class="btn_red bblock" mars_sead="orderlist_detail_pay_btn">立即支付</a></div>-->
<!--    </div>-->
<?php //} ?>
<!---->
<!---->
<!--</body>-->
</html>

