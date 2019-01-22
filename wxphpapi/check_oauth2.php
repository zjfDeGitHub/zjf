<?php
//        $db->execute("update wx_card_vip set fx_utype = 1  where 1=1");
$fxvipid=$_REQUEST["fxvipid"];
if($fxvipid<>"" and $_COOKIE['tj_vipid']==""){ //没有注册过， 并且有分享人

	SetCookie("tj_vipid",authcode(strval($fxvipid),'ENCODE'),cotimes,'/'); 
	$_COOKIE['tj_vipid']=authcode(strval($fxvipid),'ENCODE'); 
	
	
}

$oauthopenid=authcode($_COOKIE['oauthopenid']);
$gzinfo=$db->getRow("select * from wx_gongzhong ");
$redirect_uri="";
if($_GET["code"]=="" or $_GET["from"]<>""){
	$redirect_uri= urlencode('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
	$redirect_uri2= $_SERVER['REQUEST_URI'];
}else{
	$redirect_uri= $_GET["redirect_uri"];
	$redirect_uri2= $_SERVER['REQUEST_URI'];
}
//获取用户信息url
$oauthurl=
"https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$gzinfo["APPID"]."&redirect_uri=".$redirect_uri."&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect";
$code=$_GET["code"];

//SetCookie("oauthopenid","",cotimes,'/');  //设置oauthopenid
//die();

if($oauthopenid==""){ //没有oauthopenid
	//获取用户token
	$url="https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$gzinfo["APPID"]."&secret=".$gzinfo["AppSecret"]."&code=".$code."&grant_type=authorization_code";
	$result=http_request_curl($url);
	$result = json_decode($result,true);
	
	
}

$shoulduserinfo="no";
//print_r($result);
$token=$result["access_token"];
if($token=="" and $oauthopenid==""){ //如果获取不到 token
	$shoulduserinfo="no";
	//die("dddddd".$oauthurl);
	if(is_weixin()){
	die("<meta HTTP-EQUIV=refresh Content='0;url=".$oauthurl."'>"); //注释本行去除微信验证
	}
}else if($oauthopenid<>""){ //已有鉴权信息 
	//获取缓存COOKIE信息
	$wxid=str_filter(authcode($_COOKIE['hy_wxid']));
	$hy_id=str_filter(authcode($_COOKIE['hy_id']));
/*	$hy_phone=str_filter(authcode($_COOKIE['hy_phone']));
	$hy_cityid=str_filter(authcode($_COOKIE['hy_cityid']));
	$hy_api_lng=str_filter(authcode($_COOKIE['hy_api_lng']));
	$hy_api_lat=str_filter(authcode($_COOKIE['hy_api_lat']));
	$hy_city=str_filter(authcode($_COOKIE['hy_city']));*/
	if($hy_id){  //会员存在获取缓存COOKIE信息
		$vipinfo=$db->getRow("select * from wx_Card_Vip  where  id=$hy_id ");
		//$vipinfo = $db->getRow("SELECT * FROM wx_card_vip WHERE wxid='".$oauthopenid."'");
		if($vipinfo){
			if($vipinfo["wxid"]<>$oauthopenid){
			 	SetCookie("hy_id",authcode("",'ENCODE'),time()-60,'/');	
				 $_COOKIE['hy_id']=authcode("",'ENCODE'); 
				 die("<meta HTTP-EQUIV=refresh Content='0;url=/index.php'>"); //注释本行去除微信验证
			}
			 SetCookie("hy_wxid",authcode($oauthopenid,'ENCODE'),cotimes,'/');
			 $_COOKIE['hy_wxid']=authcode($oauthopenid,'ENCODE');
			 SetCookie("hy_id",authcode(strval($vipinfo["id"]),'ENCODE'),cotimes,'/');	
			 $_COOKIE['hy_id']=authcode(strval($vipinfo["id"]),'ENCODE'); 
/*			 SetCookie("hy_phone",authcode($vipinfo["phone"],'ENCODE'),cotimes,'/'); 
			 $_COOKIE['hy_phone']=authcode($vipinfo["phone"],'ENCODE');
			 
			 SetCookie("hy_api_lng",authcode($hy_api_lng,'ENCODE'),cotimes,'/'); 
			 $_COOKIE['hy_api_lng']=authcode($hy_api_lng,'ENCODE');
			 
			 SetCookie("hy_api_lat",authcode($hy_api_lat,'ENCODE'),cotimes,'/'); 
			 $_COOKIE['hy_api_lat']=authcode($hy_api_lat,'ENCODE');
			 
			 SetCookie("hy_city",authcode($hy_city,'ENCODE'),cotimes,'/'); 
			 $_COOKIE['hy_city']=authcode($hy_city,'ENCODE');

	
			 SetCookie("hy_cityid",authcode(strval($hy_cityid),'ENCODE'),cotimes,'/'); 
			 $_COOKIE['hy_cityid']=authcode(strval($hy_cityid),'ENCODE'); 	
			 */
			 SetCookie("oauthopenid",authcode($oauthopenid,'ENCODE'),cotimes,'/');  //设置oauthopenid
			 $_COOKIE['oauthopenid']=authcode($oauthopenid,'ENCODE'); 
			 
			 $_SESSION["hy_id"]=$vipinfo["id"];
			 $_SESSION["hy_phone"]=$vipinfo["phone"];
			 $_SESSION["hy_cityid"]=$hy_cityid;		
		}else{ //会员不存在
			//	die("<meta HTTP-EQUIV=refresh Content='0;url=home_bd.php'>");
			 SetCookie("hy_id",authcode("",'ENCODE'),time()-60,'/');	
			 $_COOKIE['hy_id']=authcode("",'ENCODE'); 
			 SetCookie("hy_phone",authcode("",'ENCODE'),time()-60,'/'); 
			 $_COOKIE['hy_phone']=authcode("",'ENCODE');
			 
			 SetCookie("oauthopenid",authcode("",'ENCODE'),time()-60,'/'); 
			 $_COOKIE['oauthopenid']=authcode("",'ENCODE');
			 
			 $_SESSION["hy_id"]="";
			 $_SESSION["hy_phone"]="";
			 die("<meta HTTP-EQUIV=refresh Content='0;url=".$redirect_uri2."'>");
		}

	}else{
			SetCookie("hy_id",authcode("",'ENCODE'),time()-60,'/');	
			 $_COOKIE['hy_id']=authcode("",'ENCODE'); 
			 SetCookie("hy_phone",authcode("",'ENCODE'),time()-60,'/'); 
			 $_COOKIE['hy_phone']=authcode("",'ENCODE');
			 
			 SetCookie("oauthopenid",authcode("",'ENCODE'),time()-60,'/'); 
			 $_COOKIE['oauthopenid']=authcode("",'ENCODE');
			 
			 $_SESSION["hy_id"]="";
			 $_SESSION["hy_phone"]="";
			 die("<meta HTTP-EQUIV=refresh Content='0;url=".$redirect_uri2."'>");
	}
	
}else{ //刚刚获取了鉴权信息
	$shoulduserinfo="yes";
	
	//获取用户信息
	$url="https://api.weixin.qq.com/sns/userinfo?access_token=".$token."&openid=".$result["openid"]."&lang=zh_CN";
	
	
	$resultjson=http_request_curl($url);
	$resultarr = json_decode($resultjson,true);	
	$resultarr=str_replace("'",' ',$resultarr);
	
	$attid=0;
	$endtime=time()+$result["expires_in"]-200;
	$atinfo = $db->getRow("SELECT * FROM wx_AttentionUser WHERE wxid='".$result["openid"]."'");
	SetCookie("oauthopenid",authcode($result["openid"],'ENCODE'),cotimes,'/');  //设置oauthopenid
	$oauthopenid=$result["openid"];
	$wxid=$oauthopenid;
	if($atinfo){
	   $mebers= array(
		'access_token'=> $result["access_token"],
		'access_token_endtime'=> $endtime,
		'refresh_token'=> $result["refresh_token"],
		'userinfo'=> addslashesDeepObj($resultjson)
		);	
		
		//if($atinfo["userinfo"]==""){
			//$mebers["userinfo"]=$resultjson;
		//}	
		$db->query("update wx_card_vip set head_img='".$resultarr["headimgurl"]."',uname='".$resultarr["nickname"]."' WHERE   wxid='$wxid'");
				
 	    $db->update('wx_AttentionUser',$mebers,"id=".$atinfo["id"]);
		
	}else{
		
		
	   $mebers= array(									           
		'addtime'=>time(),	
		'uid'=> $uid,
		'wxid'=> $result["openid"],
		'access_token'=> $result["access_token"],
		'access_token_endtime'=> time()+$result["expires_in"]-200,
		'refresh_token'=> $result["refresh_token"],
		'userinfo'=> $resultjson,
		'status'=> 0,
		'u_from'=> 2 
		);	
		
		$tj_vipid="0";
		if($_COOKIE['tj_vipid']<>"" ){ //有分享人
			$tj_vipid=str_filter(authcode($_COOKIE['tj_vipid'])); 
			$fx_utype = $db->getOne("select fx_utype from wx_card_vip where id=$tj_vipid");
			if($fx_utype==2){
				$mebers['tj_vip']=$tj_vipid;
			}else{
				$tj_vipid=0;
				if($fxvipid){
					$fx_utype1 = $db->getOne("select fx_utype from wx_card_vip where id=$fxvipid");
					if($fx_utype1==2){
						$tj_vipid=$fxvipid;
						$mebers['tj_vip']=$tj_vipid;
					}else{
						$tj_vipid=0;
					}
				}
			}	
	   }
		
		$gzaccess_token=getAccess_token_new();
		$pdgzurl="https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$gzaccess_token."&openid=".$result["openid"]."&lang=zh_CN";
		$gzresult=http_request_curl($pdgzurl);
		//echo $pdgzurl.$result;
		//die();
		$gzresult = json_decode($gzresult,true);
		$isgz=false;
		if($gzresult["errcode"]==""){
			if($gzresult["subscribe"]==0){
				$isgz=false;
			}else{
				$isgz=true;
			}
		}else{
			$isgz=false;
		}
		if($isgz){  //已关注
			$mebers["status"]=1;
		}
		
		
 		$attid=$db->insert('wx_AttentionUser',$mebers);
	}

	$vipinfo = $db->getRow("SELECT * FROM wx_card_vip WHERE  wxid='".$oauthopenid."'");
	if($vipinfo){ //会员存在
		 SetCookie("hy_wxid",authcode($oauthopenid,'ENCODE'),cotimes,'/');
		 $_COOKIE['hy_wxid']=authcode($oauthopenid,'ENCODE');
		 SetCookie("hy_id",authcode(strval($vipinfo["id"]),'ENCODE'),cotimes,'/');	
		 $_COOKIE['hy_id']=authcode(strval($vipinfo["id"]),'ENCODE'); 
		 SetCookie("oauthopenid",authcode($oauthopenid,'ENCODE'),cotimes,'/');  //设置oauthopenid
		 $_COOKIE['oauthopenid']=authcode($oauthopenid,'ENCODE'); 
		 $_SESSION["hy_id"]=$vipinfo["id"];
		 $_SESSION["hy_phone"]=$vipinfo["phone"];
		 $_SESSION["hy_cityid"]=$hy_cityid;		
	}else{ //会员未注册
	 
		$arrayreg = array(
		'uname' => $resultarr["nickname"],
		'head_img' => $resultarr["headimgurl"],
		'pwd'  =>md5("123456"),	
		'addtime' => time(),
		'uid' =>$uid,
		'wxid' => $oauthopenid
		);
		 $zhuceintegral=$db->getOne("select gz_get_integral from wx_websit where uid={$uid}"); 
		 $arrayreg["payIntegral"]=$zhuceintegral;
		 $grid=getFistGradeIdByUid($uid); //活动等级
		 if($grid){
			 $arrayreg['GradeId']=$grid["id"];
		 }
		// die($oauthopenid.$url);
		/*if($_COOKIE['tj_vipid']<>""){ //有分享人
			$tj_vipid=str_filter(authcode($_COOKIE['tj_vipid'])); 
			if($tj_vipid){ //有分享人
				 $utype=$db->getOne("select utype from wx_card_vip where id=$tj_vipid"); 
				 if($utype==1){ //普通会员
					$arrayreg['tj_vip']=$tj_vipid;
				 }else if($utype==2){ //业务员
					$arrayreg['tj_ywy']=$tj_vipid;
				 }
			}
	   }*/
	   
	 	if($tj_vipid<>"0"){ //有分享人
			$arrayreg['tj_vip']=$tj_vipid;
	   }
	   
	   
/*		$ywy_keys=$db->getOne("select ywy_keys from wx_attentionuser where wxid='$wxid'"); 
		if($ywy_keys<>""){ //属于扫业务员的二维码进来的客户
			$arrayreg['tj_ywy']=$ywy_keys;
		}*/
	   
		   
		$vipcardid=$db->insert('wx_card_vip',$arrayreg);
		//////推荐人+1///////

		if($vipcardid){ //注册成功
		
			if($arrayreg["tj_vip"]<>""){
				$url=APIHOST."dovip.php?act=responseKeFuText_tuijian&vipid=".$vipcardid;
				http_request_curl($url,""); //修改会上级会员推荐数
				//$sjvipinfo = $db->getRow("SELECT * FROM wx_card_vip WHERE  id=".$arrayreg["tj_vip"]);
				//responseKeFuText($sjvipinfo["wxid"],"恭喜，您成功推荐 ".$arrayreg["uname"]." 成为您的会员！<a href='".APIHOST."/allhy/my_tuijian_vip.php'>查看详情>></a>");
			}
		
			//updVipsubordinate($vipcardid);
			SetCookie("hy_id",authcode(strval($vipcardid),'ENCODE'),cotimes,'/');
			SetCookie("hy_phone",authcode(strval($arrayreg["phone"]),'ENCODE'),cotimes,'/');
			SetCookie("hy_wxid",authcode($arrayreg["wxid"],'ENCODE'),cotimes,'/');
			
			//vipGetCoupon_gz($uid,$vipcardid); //领取优惠券
			die("<meta HTTP-EQUIV=refresh Content='0;url=".$redirect_uri2."'>");
		}
		   
			 
	}
		
}


if($vipinfo){ //会员存在

	$phpself= get_url();
	$str = end(explode("/",$phpself)); //当前访问的文件名
	if($str==""){
		$phpself=$phpself."index.php";
	}
	
	if($vipinfo["fx_utype"]==2){ //会员存在

		if(!strstr($phpself,'fxvipid=')){ //判断是否包含fxvipid=
			//$wzurl=$infos->addr."&wxid=".$object->FromUserName;
			if(strstr($phpself,'?')){ //判断是否包含
				$phpself=$phpself."&fxvipid=".$vipinfo["id"];
			}else{
				$phpself=$phpself."?fxvipid=".$vipinfo["id"];
			}
		}else{ //如果包含分享信息
			$keyword = str_replace('&fxvipid='.$fxvipid,'',$phpself);//将全角－替换成半角-
			if(strstr($phpself,'?')){ //判断是否包含
				$phpself=$phpself."&fxvipid=".$vipinfo["id"];
			}else{
				$phpself=$phpself."?fxvipid=".$vipinfo["id"];
			}
		}
	}
	

}


?>
