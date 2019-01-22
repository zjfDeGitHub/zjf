<?php
require("../config.inc.php");
require('../user_function.php');
require('../all_function.php');
/**
 * 通用通知接口demo
 * ====================================================
 * 支付完成后，微信会把相关支付和用户信息发送到商户设定的通知URL，
 * 商户接收回调信息后，根据需要设定相应的处理流程。
 * 
 * 这里举例使用log文件形式记录回调信息。
*/
	include_once("WxPayPubHelper/yue_WxPayPubHelper.php");

    //使用通用通知接口
	$notify = new Notify_pub();

	//存储微信的回调
	$xml = $GLOBALS['HTTP_RAW_POST_DATA'];	
	$notify->saveData($xml);
	
	//验证签名，并回应微信。
	//对后台通知交互时，如果微信收到商户的应答不是成功或超时，微信认为通知失败，
	//微信会通过一定的策略（如30分钟共8次）定期重新发起通知，
	//尽可能提高通知的成功率，但微信不保证通知最终能成功。
	if($notify->checkSign() == FALSE){
		$notify->setReturnParameter("return_code","FAIL");//返回状态码
		$notify->setReturnParameter("return_msg","签名失败");//返回信息
	}else{
		//$notify->setReturnParameter("return_code","SUCCESS");//设置返回码
	}
	//	$returnXml = $notify->returnXml();
	//echo $returnXml;
	
	//==商户根据实际情况设置相应的处理流程，此处仅作举例=======
	



//接收微信后台发送过来的消息，该消息数据结构为XML，不是php默认的识别数据类型
 $requests=$notify->data; //获取返回参数
 $postinfo = array(
	'requestInfo'=> json_encode($requests), //postData 数据 
	'out_trade_no'=> $requests["out_trade_no"], //订单号 
	'sign'=> $requests["sign"], //sign 
	'uid'=> $uid, //uid 
	'log_type'=> 3, //微支付 
	'postData'=> $xml, //postData 数据 
	'addtime'=> time(), //addtime 
	'addip'=> realIp() //addip
);



	if($notify->checkSign() == TRUE)//充值
	{
		$sqls="SELECT * FROM wx_pay_orders WHERE orderId='".$requests['out_trade_no']."'";
		$orderinfo = $db->getRow($sqls); //查询交易时间
		if($orderinfo){ //订单号存在
			if ($notify->data["return_code"] == "FAIL") {
				//此处应该更新一下订单状态，商户自行增删操作
				//$log_->log_result($log_name,"【通信出错】:\n".$xml."\n");
				$addorid=$db->insert('wx_shop_return_log',$postinfo);
				$db->update('wx_shop_order',array('status'=>3), "orderId='".$requests['out_trade_no']."'");

				$notify->setReturnParameter("return_code","FAIL");//设置返回码
				$returnXml = $notify->returnXml();
				echo $returnXml;
				
			}elseif($notify->data["result_code"] == "FAIL"){
				//此处应该更新一下订单状态，商户自行增删操作
				//$log_->log_result($log_name,"【业务出错】:\n".$xml."\n");
				$addorid=$db->insert('wx_shop_return_log',$postinfo);
				$db->update('wx_shop_order',array('status'=>3,'pay_moneyss'=>$requests['total_fee']/100), "serial_number='".$requests['out_trade_no']);
				$notify->setReturnParameter("return_code","FAIL");//设置返回码
				$returnXml = $notify->returnXml();
				echo $returnXml;
			}else{
				//此处应该更新一下订单状态，商户自行增删操作
				//$log_->log_result($log_name,"【支付成功】:\n".$xml."\n");
                $total_fee=$requests["total_fee"]/100;
                $vipinfo=$db->getRow("select * from wx_Card_Vip where id=".$orderinfo['vipid']);
                //记录充值信息
                $logarr2 = array(
                    'status'=>1,
                );
                $vipid = $vipinfo['id'];
                $db->update('recharge_log',$logarr2,"orderId='".$requests['out_trade_no']."'");
                //赠送金额
                $yhmoneys = 0;
                $yhid = $db->getOne("select yhjid from recharge_log where orderId = '".$requests['out_trade_no']."'");
                if (!empty($yhid)){
                    $yhmoneys = $db->getOne("select zsmoney from wx_couponinfo where id = ".$yhid);
                }
                //增加用户余额和积分
                $oldm = $db->getOne("select moneys from wx_card_vip where id =".$vipinfo['id']);
                $oldP = $db->getOne("select payIntegral from wx_card_vip where id =".$vipinfo['id']);
                $payIntegral = array(
                    'payIntegral'=> $oldP+$total_fee,
                    'moneys'=> $total_fee+$oldm+$yhmoneys
                );
                $db->update('wx_card_vip',$payIntegral,"id= ".$vipinfo['id']);
                $db->update('wx_pay_orders',array('status'=> 2),"orderId='".$requests['out_trade_no']."'");

				if($orderinfo["status"]<>2){ //支付状态已为成功
					$addorid=$db->insert('wx_shop_return_log',$postinfo);
					
					
					$nowpaynomey=$requests['total_fee']/100;
					
					//添加余额
					$out_trade_no = str_replace('WX','',$requests['out_trade_no']);//将全角－替换成半角-
					
					$opinfoid=$db->getRow("select * from wx_operateInfo where operateId='$out_trade_no' and status=1 and types=7"); 
					if($opinfoid){
						$db->update('wx_operateInfo',array('status'=> 2,'stepDate'=>$opinfoid["stepDate"].$nowpaynomey."|")," operateId='$out_trade_no' and id=".$opinfoid["id"]);//修改状态
						
						$aryName1=explode('|',$opinfoid["stepDate"]);
						$phone=$aryName1[0];  //获取到的电话号码
						//$domoney11=intval($aryName1[1]);
						//$domoney=intval($aryName1[2]);
						$aryName2=explode('+',$aryName1[1]);
						$domoney=floatval($aryName2[1]);
						//$sql="update wx_Card_Vip set moneys=moneys+".($requests['total_fee']/100)." where id=".$orderinfo['vipid'];
						$vipinfo=$db->getRow("select * from wx_Card_Vip where id=".$orderinfo['vipid']);
//						$sql="update wx_Card_Vip set moneys=moneys+".$domoney." where id=".$vipinfo['id'];
//						$db->query($sql);
//						$db->update('wx_pay_orders',array('status'=>2,'paymoney'=>($requests['total_fee']/100+$yhmoneys)), "orderId='".$requests['out_trade_no']."'");
						
						if($vipinfo["GradeId"]==1){
							//$db->query("update wx_Card_Vip set GradeId=2 where id=".$vipinfo['id']);
						}
						if($vipinfo["fx_utype"]<2){
							//$db->query("update wx_Card_Vip set fx_utype=2 where id=".$vipinfo['id']);
						}
						
					}
					
					//添加余额结束
					/*$vipinfo=$db->getRow("select * from wx_Card_Vip where id='".$orderinfo['vipid']."'");
					if($vipinfo["GradeId"]){  //有会员等级
						$paymoneys=$requests['total_fee']/100;
						$Gradeinfo=$db->getRow("select * from wx_membergradeconfig where id='".$vipinfo["GradeId"]."'"); //当前等级信息
					//	$nextGrade=$db->getRow("select * from wx_membergradeconfig where  GradeOrder>".$Gradeinfo["GradeOrder"]." order by GradeOrder asc limit 1 "); //下一个等级信息	
						
						$paynextGrade=$db->getRow("select * from wx_membergradeconfig where  needmoneys<=".$paymoneys." order by needmoneys desc limit 1 "); //比充值金额小的下一个等级信息	
						
						if($Gradeinfo["GradeOrder"]<$paynextGrade["GradeOrder"]){ //当前等级小于充值金额的下一等级
							$sql2="update wx_Card_Vip set GradeId=".$paynextGrade["id"]." where id=".$vipinfo['id'];
							$db->query($sql2);
						}
			
						
					}*/
					
					
					$notify->setReturnParameter("return_code","SUCCESS");//设置返回码
					$returnXml = $notify->returnXml();
					
					
					
		/***发送模版信息开始*/
		//订单支付成功通知

                    $tid="OPENTM201752540";
                    $vipinfo=$db->getRow("select * from wx_Card_Vip  where  id= ".$orderinfo["vipid"]);
                    $twxid=$vipinfo["wxid"];
                    $template_message=$db->getRow("select * from wx_template_message where t_id='$tid' ");
                    if($template_message["t_status"]==2 and $twxid<>""){ //已启用
//            $total_fee=$requests["total_fee"]/100;
                        $total_fee = $requests["total_fee"]/100+$yhmoneys;
                        $arraydata=array(
                            'first'  => array('value'  => "您好，您已经充值成功。",'color'  => $template_message["t_font_colour"]),
                            'accountType'  => array('value'  => "订单号",'color'  => $template_message["t_font_colour"]),
                            'account'  => array('value'  => $requests['out_trade_no'],'color'  => $template_message["t_font_colour"]),
                            'amount'  => array('value'  => $total_fee."(优惠:$yhmoneys 元)",'color'  => $template_message["t_font_colour"]),
                            'result'  => array('value'  => "充值成功",'color'  => $template_message["t_font_colour"]),
//                'keyword4'  => array('value'  => date('Y-m-d H:i',time()),'color'  => $template_message["t_font_colour"]),
                            'remark'  => array('value'  => "感谢您对我们的信任，牛扒制造将为您提供更优质的服务。",'color'  => $template_message["t_font_colour"])
                        );
                        $arraypost=array(
                            'touser'  => $twxid,
                            'template_id'  => $template_message["template_id"],
//				'url'  => APIHOST."mobilemall/personcenter.php",
                            "topcolor"=>$template_message["t_head_colour"],
                            'data'  => $arraydata
                        );

                        $jsons=JSON($arraypost);
                        $result=send_template_message($jsons);


//    	$vipinfo=$db->getRow("select * from wx_Card_Vip  where  id= ".$orderinfo["vipid"]);
//
//		$tid="OPENTM201752540";
//		$twxid=$vipinfo["wxid"];
//		$template_message=$db->getRow("select * from wx_template_message where t_id='$tid' ");
//		if($template_message["t_status"]==2 and $twxid<>""){ //已启用
//	     	$total_fee=$requests["total_fee"]/100;
//			$arraydata=array(
//				'first'  => array('value'  => "您好，您已经充值成功。",'color'  => $template_message["t_font_colour"]),
//				'keyword1'  => array('value'  => "余额充值",'color'  => $template_message["t_font_colour"]),
//				'keyword2'  => array('value'  => $requests['out_trade_no'],'color'  => $template_message["t_font_colour"]),
//				'keyword3'  => array('value'  => $total_fee."元",'color'  => $template_message["t_font_colour"]),
//                'keyword4'  => array('value'  => date('Y-m-d H:i',time()),'color'  => $template_message["t_font_colour"]),
//				'remark'  => array('value'  => "感谢您对我们的信任，我们将为您提供更优质的服务。",'color'  => $template_message["t_font_colour"])
//			);
//			$arraypost=array(
//				'touser'  => $twxid,
//				'template_id'  => $template_message["template_id"],
////				'url'  => APIHOST."mobilemall/personcenter.php",
//				"topcolor"=>$template_message["t_head_colour"],
//				'data'  => $arraydata
//			);
//
//			$jsons=JSON($arraypost);
//			$result=send_template_message($jsons);
			// print_r($result);
		
		}
		/***发送模版信息结束**/

					
		/***发送模版信息开始付款成功通知*/
		/*$spinfo=$db->getRow("select * from wx_shop_config");
		$tid="OPENTM203079635"; 
		$twxid=$spinfo["ad_wxid"];
		$template_message=$db->getRow("select * from wx_template_message where t_id='$tid' ");
		if($template_message["t_status"]==2 and $twxid<>""){ //已启用
	     	$total_fee=$requests["total_fee"]/100;
			$arraydata=array(
				'first'  => array('value'  => "您好，您有新付款订单",'color'  => $template_message["t_font_colour"]),	
				'keyword1'  => array('value'  => $orderinfo["vipid"],'color'  => $template_message["t_font_colour"]),
				'keyword2'  => array('value'  => $orderinfo["serial_number"],'color'  => $template_message["t_font_colour"]),
				'keyword3'  => array('value'  => date('Y-m-d H:i:s',time()),'color'  => $template_message["t_font_colour"]),
				'remark'  => array('value'  => "请尽快确认并安排发货",'color'  => $template_message["t_font_colour"])
			);
			$arraypost=array(
				'touser'  => $twxid,
				'template_id'  => $template_message["template_id"],		
				'url'  => "",	
				"topcolor"=>$template_message["t_head_colour"],
				'data'  => $arraydata
			);
		
			$jsons=JSON($arraypost);
			$result=send_template_message($jsons);
			// print_r($result);
		
		}*/
		/***发送模版信息开始付款成功通知结束**/
					
					
					
					echo $returnXml;
				}else{
					$addorid=$db->insert('wx_shop_return_log',$postinfo);
					$notify->setReturnParameter("return_code","SUCCESS");//设置返回码
					$returnXml = $notify->returnXml();
					echo $returnXml;
				}
			}
			
			
			
		}else{ //订单号不存在
			$addorid=$db->insert('wx_shop_return_log',$postinfo);
			
			$notify->setReturnParameter("return_code","FAIL");//设置返回码
			$returnXml = $notify->returnXml();
			echo $returnXml;
		} //订单号不存在 结束
		
	
		//商户自行增加处理流程,
		//例如：更新订单状态
		//例如：数据库操作
		//例如：推送支付完成信息
	}else{ //签名失败
		$returnXml = $notify->returnXml();
		echo $returnXml;
	}
?>
