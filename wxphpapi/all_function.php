<?

function fanyong_qidao_order($orid){
		global $db;
	$orderinfo=$db->getRow("select * from wx_shop_order where id=".$orid);
	if($orderinfo){ //订单存在开始
		$vipinfo=$db->getRow("select tj_vip,id,GradeId from wx_Card_Vip where id=".$orderinfo["vipid"]);
		/**返佣开始**/
		if($vipinfo["tj_vip"]){ //有推荐人开始
		   $p_vipinfo=$db->getRow("SELECT * FROM wx_card_vip where id=".$vipinfo["tj_vip"]); //推荐人信息
		   if($p_vipinfo["fx_utype"]==2 or $p_vipinfo['fx_utype']==1){ //推荐人是vip会员开始
		   		$shop_config=$db->getRow("SELECT * FROM wx_shop_config"); //推荐人信息
		   		$sumtcmoney=$orderinfo["nowsumprice"]; //分佣金额
               //*$shop_config["tc_bfb"]/100,2    提成百分比
				//查询上面有几级会员开始
				$dowhile = false; //默认不循环
				$djnum=1; //分销级数
				$dj_tj_vip=$p_vipinfo["tj_vip"]; //推荐人id
				if($dj_tj_vip){
					$dowhile = true; //有推荐人就得循环
				}
				while ($dowhile) //循环开始
				{
					 if($dj_tj_vip<>0){
						$dj_vipinfo=$db->getRow("SELECT tj_vip,id  FROM wx_card_vip where fx_utype=2 and id=".$dj_tj_vip); //查询
					 } //设置了返佣结束
					 $dj_tj_vip=$dj_vipinfo["tj_vip"];
					 if($dj_tj_vip==0 or $djnum>=1){ //没有推荐人了
						$dowhile = false; //退出循环
					 }
				}

				//查询上面有几级会员结束
				$p_vip=$vipinfo["tj_vip"]; //子推荐 二级会员id
				for ($i=1; $i<=$djnum; $i++) { //循环分销
					if($p_vip<>0){
						$p_vipinfo=$db->getRow("SELECT * FROM wx_card_vip where id=".$p_vip); //查询
						$havefy=0;
						$havefy=$db->getOne("SELECT count(id) FROM wx_incentive_score where orid=".$orderinfo["id"]." and odrtype=1 and types=".$i); //查询该类型返佣是否已返
						if($shop_config["tj".$djnum."_tc".$i]<>0 and $p_vip and $p_vipinfo["fx_utype"]==2 and $havefy==0  and $p_vipinfo["utype"]==1){ //设置了返佣
							//die("fffffffff".$orcount);
							$tc_moneys=$sumtcmoney*($shop_config["tj".$djnum."_tc".$i]/100);//返佣金额
							//$db->execute("update wx_card_vip set moneys=moneys+$tc_moneys,totalcommission=totalcommission+$tc_moneys  where id=".$p_vip);
							/**添加奖励记录**/
							$incentive_score= array(
								'vipid'=> $p_vip,
								'orid'=> $orderinfo["id"],
								'good_kzid'=>0,
								'serial_number'=> $orderinfo["serial_number"],
                                'fy_serial_number'=> $orderinfo["serial_number"],
								'ordermoneys'=>$sumtcmoney,
								'tc_bfb'=> $shop_config["tj".$djnum."_tc".$i],
								'tc_moneys'=>$tc_moneys,
								'status'=>1,
								'odrtype'=>1,
								'addtime'=>time(),
								'cg_time'=>time(),
								'types'=> $i
							 );
							$db->insert('wx_incentive_score',$incentive_score);
							/**添加奖励记录**/
							$url= APIHOST."allhy/incentive_score.php?act=moneyupinfo";
							/***发送模版信息开始*/
							//订单支付成功通知
							$tid="OPENTM400590844";
							$twxid=$p_vipinfo["wxid"];
							$template_message=$db->getRow("select * from wx_template_message where t_id='$tid' ");
							if($template_message["t_status"]==2 and $twxid<>""){ //已启用
								//$total_fee=$incentive_score["tc_moneys"];
								$arraydata=array(
									'first'  => array('value'  => "您好，您的佣金已存入账户",'color'  => $template_message["t_font_colour"]),
									'keyword1'  => array('value'  =>$tc_moneys."元",'color'  => $template_message["t_font_colour"]),
									//'keyword2'  => array('value'  =>strip_tags(getincentive_info_types($incentive_score["types"],$incentive_score["odrtype"])),'color'  => $template_message["t_font_colour"]),
									'keyword2'  => array('value'  =>date('Y-m-d H:i',time()),'color'  => $template_message["t_font_colour"]),
									'remark'  => array('value'  => "点击此通知查看详细。",'color'  => $template_message["t_font_colour"])
								);
								$arraypost=array(
									'touser'  => $twxid,
									'template_id'  => $template_message["template_id"],
									'url'  => $url,
									"topcolor"=>$template_message["t_head_colour"],
									'data'  => $arraydata
								);

								$jsons=JSON($arraypost);
								$result=send_template_message($jsons);
								//print_r($result);

							}
							/***发送模版信息结束**/
                            $sumfanyong=0;
                            $sumfanyong+=$tc_moneys;

                            $tc_moneys=$sumtcmoney*($shop_config["tc_bfb"]/100);//返佣金额
                            /**添加奖励记录**/
                            ;

                            //查询上上级 一级会员
                            $p_to_vip=$db->getRow("select tj_vip,id,GradeId from wx_Card_Vip where id=".$p_vip);
                            $incentive_score= array(
                                'vipid'=> $p_to_vip['tj_vip'],
                                'orid'=> $orderinfo["id"],
                                'good_kzid'=>0,
                                'serial_number'=> $orderinfo["serial_number"],
                                'fy_serial_number'=> 'fy'.$orderinfo["serial_number"],
                                'ordermoneys'=>$sumtcmoney,
                                'tc_bfb'=> $shop_config["tc_bfb"],
                                'tc_moneys'=>$tc_moneys,
                                'status'=>1,
                                'odrtype'=>1,
                                'addtime'=>time(),
                                'cg_time'=>time(),
                                'types'=> $i
                            );
                            $db->insert('wx_incentive_score',$incentive_score);
                            /**添加奖励记录**/
                            $url= APIHOST."allhy/incentive_score.php?act=moneyupinfo";
                            /***发送模版信息开始*/
                            //订单支付成功通知
                            $tid="OPENTM400590844";
                            $idaa = $vipinfo['id'];
                            $twxid=$db->getOne("select wxid from wx_card_vip where id = $idaa");
                            echo $twxid;
                            $template_message=$db->getRow("select * from wx_template_message where t_id='$tid' ");
                            if($template_message["t_status"]==2 and $twxid<>""){ //已启用

                                $arraydata=array(
                                    'first'  => array('value'  => "您好，您的佣金已存入账户",'color'  => $template_message["t_font_colour"]),
                                    'keyword1'  => array('value'  =>$tc_moneys."元",'color'  => $template_message["t_font_colour"]),
                                    //'keyword2'  => array('value'  =>strip_tags(getincentive_info_types($incentive_score["types"],$incentive_score["odrtype"])),'color'  => $template_message["t_font_colour"]),
                                    'keyword2'  => array('value'  =>date('Y-m-d H:i',time()),'color'  => $template_message["t_font_colour"]),
                                    'remark'  => array('value'  => "点击此通知查看详细。",'color'  => $template_message["t_font_colour"])
                                );
                                $arraypost=array(
                                    'touser'  => $twxid,
                                    'template_id'  => $template_message["template_id"],
                                    'url'  => $url,
                                    "topcolor"=>$template_message["t_head_colour"],
                                    'data'  => $arraydata
                                );

                                $jsons=JSON($arraypost);
                                $result=send_template_message($jsons);
                                print_r($result);

                            }
                            /***发送模版信息结束**/
                            $sumfanyong+=$tc_moneys;
                            $p_vip=$p_vipinfo["tj_vip"];
                            //print_r($result);

						} //设置了返佣结束


					}

				} //循环分销结束

				$db->execute("update wx_shop_order set fy_sum_money=$sumfanyong  where id=".$orderinfo["id"]);  //修改订单状态
		  } //推荐人是vip会员结束
		}//有推荐人结束
else{
    $vipinfo=$db->getRow("select tj_vip,id,GradeId from wx_Card_Vip where id=".$orderinfo["vipid"]);
    $shop_config=$db->getRow("SELECT * FROM wx_shop_config"); //推荐人信息
    $sumtcmoney=$orderinfo["nowsumprice"]; //分佣金额
    $i = 0;
    $sumfanyong=0;

        $tc_moneys=$sumtcmoney*($shop_config["tc_bfb"]/100);//返佣金额
    /**添加奖励记录**/
    $incentive_score= array(
        'vipid'=> $vipinfo['id'],
        'orid'=> $orderinfo["id"],
        'good_kzid'=>0,
        'serial_number'=> $orderinfo["serial_number"],
        'fy_serial_number'=> $orderinfo["serial_number"],
        'ordermoneys'=>$sumtcmoney,
        'tc_bfb'=> $shop_config["tc_bfb"],
        'tc_moneys'=>$tc_moneys,
        'status'=>1,
        'odrtype'=>1,
        'addtime'=>time(),
        'cg_time'=>time(),
        'types'=> $i
    );
    $db->insert('wx_incentive_score',$incentive_score);
    /**添加奖励记录**/
    $url= APIHOST."/allhy/incentive_score.php?act=moneyupinfo";
    /***发送模版信息开始*/
    //订单支付成功通知
    $tid="OPENTM400590844";
    $idaa = $vipinfo['id'];
    $twxid=$db->getOne("select wxid from wx_card_vip where id = $idaa");
    echo $twxid;
    $template_message=$db->getRow("select * from wx_template_message where t_id='$tid' ");
    if($template_message["t_status"]==2 and $twxid<>""){ //已启用

        $arraydata=array(
            'first'  => array('value'  => "您好，您的佣金已存入账户",'color'  => $template_message["t_font_colour"]),
            'keyword1'  => array('value'  =>$tc_moneys."元",'color'  => $template_message["t_font_colour"]),
            //'keyword2'  => array('value'  =>strip_tags(getincentive_info_types($incentive_score["types"],$incentive_score["odrtype"])),'color'  => $template_message["t_font_colour"]),
            'keyword2'  => array('value'  =>date('Y-m-d H:i',time()),'color'  => $template_message["t_font_colour"]),
            'remark'  => array('value'  => "点击此通知查看详细。",'color'  => $template_message["t_font_colour"])
        );
        $arraypost=array(
            'touser'  => $twxid,
            'template_id'  => $template_message["template_id"],
            'url'  => $url,
            "topcolor"=>$template_message["t_head_colour"],
            'data'  => $arraydata
        );

        $jsons=JSON($arraypost);
        $result=send_template_message($jsons);
        print_r($result);

    }
    $sumfanyong+=$tc_moneys;

    /***发送模版信息结束**/

}
		/**返佣结束**/


	}//订单存在结束
}


//店铺服务积分返佣函数
/**
*$orid 订单号
*$ordergoodinfo 商品快照信息
*$goodinfo 商品信息
**/
function fanyong_fuwushang($orid){
	global $db;
	$orderinfo=$db->getRow("select * from wx_shop_order where id=".$orid);
	$shopordergoods=$db->getRow("select sum(fw_xs_price) as sumfw_xs_price from wx_shop_order_good where order_id=".$orid); //查询订单快照信息
	$paymoneys=$shopordergoods["sumfw_xs_price"]; //总佣金
	$tc_moneys=$paymoneys;//返佣金额
	if($orderinfo["fws_id"]){
			/**添加奖励记录**/
			$incentive_score= array(
				'vipid'=>$orderinfo["fws_id"],
				'orid'=>$orderinfo["id"],
				'serial_number'=>$orderinfo["serial_number"],
				'ordermoneys'=>$paymoneys,
				'tc_bfb'=>100,
				'tc_moneys'=>$tc_moneys,
				'status'=>1,
				'addtime'=>time(),
				//'cg_time'=>time(),
				'types'=>31,
				'odrtype'=>3,

			 );

			//返佣应为立即为待入账结束
			$havefy=$db->getOne("SELECT count(id) FROM wx_incentive_score where orid=".$incentive_score["orid"]." and types=".$incentive_score["types"]." and odrtype=".$incentive_score["odrtype"]); //查询该类型返佣是否已返
			if($havefy==0){
				$db->insert('wx_incentive_score',$incentive_score);

				$sjinfo=$db->getRow("select * from wx_users where id=".$orderinfo["fws_id"]);
				$url= APIHOST."allhy/incentive_score.php?act=moneyupinfo";
				/***发送模版信息开始*/
				//订单支付成功通知
				$tid="OPENTM207422813";
				$twxid=$sjinfo["ad_wxid"];
				$template_message=$db->getRow("select * from wx_template_message where t_id='$tid' ");
				if($template_message["t_status"]==2 and $twxid<>""){ //已启用
					$total_fee=$incentive_score["tc_moneys"];
					$arraydata=array(
						'first'  => array('value'  => "您新增了待入账收入",'color'  => $template_message["t_font_colour"]),
						'keyword1'  => array('value'  =>$tc_moneys."元",'color'  => $template_message["t_font_colour"]),
						'keyword2'  => array('value'  =>strip_tags(getincentive_info_types($incentive_score["types"],$incentive_score["odrtype"])),'color'  => $template_message["t_font_colour"]),
						'keyword3'  => array('value'  =>date('Y-m-d H:i',time()),'color'  => $template_message["t_font_colour"]),
						'remark'  => array('value'  => "点击此通知查看详细，用户确认收货七天之后佣金将自动到您账户余额哦。",'color'  => $template_message["t_font_colour"])
					);

						$twxidlist=@explode(',',$twxid);
						for ($i=0;$i<count($twxidlist);$i++){
							$arraypost=array(
								'touser'  =>$twxidlist[$i],
								'template_id'  => $template_message["template_id"],
								'url'  => "",
								"topcolor"=>$template_message["t_head_colour"],
								'data'  => $arraydata
							);
							$jsons=JSON($arraypost);
							$result=send_template_message($jsons);
					   }

				}
				/***发送模版信息结束**/

			}
			/**添加奖励记录**/
	}else{
	//公司沉淀
		//update_cdmoney($orderinfo["id"],$orderinfo["serial_number"],$paymoneys,100,$tc_moneys,41,$ordergoodinfo);
	}

	//标记店铺已返佣
	$db->execute("update wx_shop_order set is_dpxs=2  where id=".$orderinfo["id"]);
}




//成为会员后有推荐人的情况下所有上级推荐人推荐总数加1
function updVipsubordinate($vipid){
/*	global $db;
	$p_vipinfo=$db->getRow("SELECT * FROM wx_card_vip where fx_utype=2 and id=".$vipid); //被推荐人信息
	$p_vip=$p_vipinfo["tj_vip"]; //推荐人id
	for ($i=1; $i<=3; $i++) { //循环分销
		if($p_vip<>0){
			$p_vipinfo=$db->getRow("SELECT * FROM wx_card_vip where fx_utype=2 and id=".$p_vip); //查询
			$db->execute("update wx_card_vip set subordinate=subordinate+1  where id=".$p_vip);
			upgradeByvipid($p_vip); //等级达到条件自动升级
		} //设置了返佣结束
		$p_vip=$p_vipinfo["tj_vip"];
	 } //推荐人是vip会员结束*/
}



//等级达到条件自动升级
function upgradeByvipid($vipid){
	//$db = dblink();
/*	global $db;
	$shopifo=$db->getRow("select * from wx_shop_config"); //商户信息
	$userifo=$db->getRow("select * from wx_card_vip where fx_utype=2 and id=".$vipid); //用户信息
	if($userifo){
		if($userifo["subordinate"]>=$shopifo["sbdNumer1"] && $userifo["totalcommission"]>=$shopifo["amount1"]){//省级服务商
			  $db->execute("update wx_card_vip set GradeId=13  where id=".$vipid);
			  return;
		  }else if($userifo["subordinate"]>=$shopifo["sbdNumer2"] && $userifo["totalcommission"]>=$shopifo["amount2"]){//市级服务商
			  $db->execute("update wx_card_vip set GradeId=12  where id=".$vipid);
			  return;
		  }else if($userifo["subordinate"]>=$shopifo["sbdNumer3"] && $userifo["totalcommission"]>=$shopifo["amount3"]){//市级服务商
			  $db->execute("update wx_card_vip set GradeId=11  where id=".$vipid);
			  return;
		  }
	}
	*/

}
?>
