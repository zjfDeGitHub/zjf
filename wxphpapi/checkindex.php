<?php 
require("config.inc.php");
require('user_function.php');
require('check_oauth2.php');
$hy_id=str_filter(authcode($_COOKIE['hy_id']));
if(!$vipinfo){
//	$vipinfo = $db->getRow("SELECT * FROM wx_card_vip where id=1");
}
if($hy_id){  //会员存在获取缓存COOKIE信息
	$vipinfo=$db->getRow("select * from wx_Card_Vip  where  id=$hy_id ");

}
$hy_id=$vipinfo["id"];
$vote_id=intval(str_filter($_REQUEST['vote_id']));
if($vote_id){
	$voteinfo=$db->getRow("select * from wx_vote where id=".$vote_id);
	$vote_id = $voteinfo["id"];
}
$shopdata=$db->getRow("select * from wx_shop_config where uid=".$uid);
?>
