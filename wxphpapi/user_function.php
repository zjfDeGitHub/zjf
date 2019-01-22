<?php
/*新 */
//vipAutoLogin($_REQUEST["wxid"],$_REQUEST["key"],$_REQUEST["ti"],$urlinfo['uid']); //会员自动登陆
//距离
function getDistance($lat1, $lng1, $lat2, $lng2){
    $earthRadius = 6367000; //approximate radius of earth in meters
    $lat1 = ($lat1 * pi() ) / 180;
    $lng1 = ($lng1 * pi() ) / 180;
    $lat2 = ($lat2 * pi() ) / 180;
    $lng2 = ($lng2 * pi() ) / 180;
    $calcLongitude = $lng2 - $lng1;
    $calcLatitude = $lat2 - $lat1;
    $stepOne = pow(sin($calcLatitude / 2), 2) + cos($lat1) * cos($lat2) * pow(sin($calcLongitude / 2), 2);
    $stepTwo = 2 * asin(min(1, sqrt($stepOne)));
    $calculatedDistance = $earthRadius * $stepTwo;
    $calculatedDistance = $calculatedDistance/1000;
    return round($calculatedDistance);
}
function checkVipCookie($uid,$grid,$needor=0)
{
if(isset($_COOKIE['wx_cardid_'.$uid]) and isset($_COOKIE['wx_phone_'.$uid]) and isset($_COOKIE['wx_wxid_'.$uid]) and isset($_COOKIE['GradeId_'.$uid])){
			$wx_cardid=str_filter(authcode($_COOKIE['wx_cardid_'.$uid]));
			$wx_phone=str_filter(authcode($_COOKIE['wx_phone_'.$uid]));
			$wx_wxid=str_filter(authcode($_COOKIE['wx_wxid_'.$uid]));
			$wx_GradeId=str_filter(authcode($_COOKIE['GradeId_'.$uid]));
			
			$vipGradeOrders=getGradeOrderById($uid,$wx_GradeId); //等级排序
			if($vipGradeOrders>=$needor){
					$info = array(
						'loingstatus'  =>1,
						'wx_cardid'  =>$wx_cardid,
						'wx_phone'=>$wx_phone,	
						'wx_wxid'=>$wx_wxid,
						'wx_GradeId'=>$wx_GradeId
				    );
				   return $info;
			}else{
				//echo "ddd".$vipGradeOrders.":".$needor;
				  $info = array(
					 'loingstatus'=>2
				   );	
				 return $info;	
			}
		
	}else{
		 $info = array(
			'loingstatus'=>-1
		 );	
		 return $info;		
	}	 		
}


//获取焦点图列表
function get_wx_shop_pcfocus_limit($cid=0,$limit=0,$uid=41){
	global $db;
	$newslist=array();
	$sql="select * from wx_shop_pcfocus where  cid=$cid and uid=$uid order by orderList asc";
	if($limit>0){
		$sql=$sql." limit $limit";
	}
	//echo $sql;
	$resultcat=$db->execute($sql);
	while($row=$db->fetch_array($resultcat)){
		$newslist[]=$row;
	}
	return $newslist;
}
//查询用户信息
function get_wx_users_id(){
    //$db = dblink();
    //die($id);
    global $db;
    $data="";
    $sql="select * from wx_users";
    $q=$db->execute($sql);
    while($r=$db->fetch_array($q)){
        $data[]=$r;
    }

    return $data;
}


//根据用户wx_company_cat ID查询栏目名称
function getLeftKeyword($srt,$lens){
	if(strlen($srt)>$lens*2){
		return subString(stripslashes(strip_tags($srt)),$lens).".."; 
	}else{
		return $srt;
	}
}


# 获取uid获取产品信息
function get_wx_integral_shop_goods_limit($uid){
	global $db;
	//$db = dblink();and
	global $doc;
	$sql="select *  from wx_integral_shop_goods where indexshow=1 and uid=".$uid;
	$q=$db->execute($sql);
	while($r=$db->fetch_array($q)){
		$data[]=$r;
	}

	return $data;
}

# 获取默认收货地址列表
function get_Vip_Addr_Listone($vipid){
	//$db = dblink();
	global $db;
	$data="";
	$sql="select *  from wx_shop_vip_addr where vipid=".$vipid." and isdefualt = 1  ORDER BY isdefualt desc, id desc";
	$q=$db->execute($sql);
	while($r=$db->fetch_array($q)){
		$data[]=$r;
	}

	return $data;
}

# 根据id获取产品信息
function get_jifenGoodInfoById($uid,$id){
	global $db;
	$ms=$db->getRow("select * from wx_integral_shop_goods where   uid=$uid and id=$id");
	return $ms;
}

//根据pid展示分类列表
function get_wx_shop_cat_jpal($pid=0){
    //$db = dblink();
    global $db;
    $data="";
    $sql="select * from wx_shop_cat where pid=$pid";
    $sql = $sql." ORDER BY orderList asc";
    $q=$db->execute($sql);
    while($r=$db->fetch_array($q)){
        $data[]=$r;
    }
    return $data;
}


//首页推荐的分类信息
function get_wx_shop_cat_list(){
	global $db;
	$data="";
	$sql="select * from wx_shop_cat where pid=0 and is_index=1 ";
	$sql = $sql." ORDER BY orderList asc";
	$q=$db->execute($sql);
	while($r=$db->fetch_array($q)){
		$data[]=$r;
	}
	return $data;
}


//根据pid展示分类列表
function get_wx_shop_cat_jpal2($pid){
    //$db = dblink();
    global $db;
    $data="";
    $sql="select * from wx_shop_cat where pid=$pid";
    $sql = $sql." ORDER BY orderList asc";
    $q=$db->execute($sql);
    while($r=$db->fetch_array($q)){
        $data[]=$r;
    }

    return $data;
}
//根据pid展示分类列表
function get_wx_shop_area_jpal2($pid){
    //$db = dblink();
    global $db;
    $data="";
    $sql="select * from wx_shop_area where pid=$pid";
    $sql = $sql." ORDER BY orderList asc";
    $q=$db->execute($sql);
    while($r=$db->fetch_array($q)){
        $data[]=$r;
    }

    return $data;
}
//根据品牌展示分类列表
function get_wx_brand_jpal2($brand){
    //$db = dblink();
    global $db;
    $data="";
    $sql="select * from wx_brand_list where pid=$brand and state = 1 order by orderList asc";
    $q=$db->execute($sql);
    while($r=$db->fetch_array($q)){
        $data[]=$r;
    }

    return $data;
}
//根据pid展示分区列表
function get_wx_shop_area($pid=0){
    //$db = dblink();
    global $db;
    $data="";
    $sql="select * from wx_shop_area where pid=$pid";
    $sql = $sql." ORDER BY orderList asc";
    $q=$db->execute($sql);
    while($r=$db->fetch_array($q)){
        $data[]=$r;
    }

    return $data;
}


//根据用户wx_company_cat ID查询栏目名称
function getCatNameById($id,$wid){
	global $db;
	///echo "select wphone from websit where uid='$uids'";
	$catName=$db->getOne("select catName from wx_company_cat where id=$id and wid=$wid");
	return $catName;
}
//查询栏目是否需要高等级才能查看
function getCatIsVipById($cid,$wid){
	global $db,$catName;
	///echo "select wphone from websit where uid='$uids'";
	$hyzs=$db->getRow("select hyzs,wxgc.levelTitle  from wx_company_cat as cu inner join wx_memberGradeConfig as wxgc on cu.hyzs=wxgc.id where cu.id=$cid and cu.wid=$wid and cu.is_member=1 and cu.hyzs!=0");
	
	
	return $hyzs;
}


//根据手机号码查询用户最低的会员等级
function getGradeOrderById($uid,$gid){ 
	//$materialinfo=$db->getRow("select * from wx_material where uid=".uid." and id=".$rs1["mid"]);	
   	global $db;
	$GradeOrders=$db->getOne("select GradeOrder  from wx_memberGradeConfig where uid=".$uid." and id =$gid");
	return $GradeOrders;
}


//根据用户wx_company_cat ID查询栏目分享信息
function getCatGradeNameById($id,$wid){
	global $db,$catName;
	///echo "select wphone from websit where uid='$uids'";
	$catName=$db->getOne("select levelTitle from wx_memberGradeConfig where id=$id and uid=$wid");
	return $catName;
}


//根据用户wx_company_cat ID查询栏目分享信息
function getCatIfoById($id,$wid){
	global $db,$catName;
	///echo "select wphone from websit where uid='$uids'";
	$catName=$db->getRow("select catName,share_imgs,share_title from wx_company_cat where id=$id and wid=$wid");
	return $catName;
}



//根据等级id 查询享受的折扣
function getDiscountById($id,$uid){
	global $db;
	///echo "select wphone from websit where uid='$uids'";
	$Discount=$db->getOne("select Discount from wx_memberGradeConfig where id=$id and uid=$uid");
	return $Discount;
}


# 获取焦点图列表
function get_model_Focus_Img_List($wid){
	global $db;
	global $doc;
	$data="";
	$sql="select *  from wx_company_singlepage where wid=".$wid." and state=0 ORDER BY orderList desc,id desc";
	$q=$db->execute($sql);
	while($r=$db->fetch_array($q)){	
		$data[]=$r;
	}
	
	return $data;
}

function get_money_limit($vipid){
    global $db;
    $data="";
    $sql="select *  from wx_shop_order where vipid=".$vipid;
    $q=$db->execute($sql);
    while($r=$db->fetch_array($q)){
        $data[]=$r;
    }
    return $data;
}
# 获取第一张焦点图
function get_model_Focus_Img_Top1($wid){
	global $db;
	$sql="select beijingimg from wx_websit where id=".$wid;
	$bjimg=$db->getOne($sql);
	if(!$bjimg){
		$sql2="select img from wx_company_singlepage where wid=".$wid." and state=0 ORDER BY orderList desc,id desc limit 1";
		$bjimg=$db->getOne($sql2);
	}
	return $bjimg;
	
}

# 获取第一张焦点图
function get_model_Focus_Img_one($wid){
	global $db;
	$sql="select beijingimg from wx_websit where id=".$wid;
	$bjimg=$db->getOne($sql);
	if(!$bjimg){
		$sql2="select img_url from wx_company_singlepage where wid=".$wid." and state=0 ORDER BY orderList desc,id desc limit 1";
		$bjimg=$db->getOne($sql2);
	}
	return $bjimg;
	
}


# 获取背景图，
function get_beijing_Img($wid){
	global $db;
	$sql="select beijingimg from wx_websit where id=".$wid;
	$bjimg=$db->getOne($sql);
	
	return $bjimg;
	
}

/*//获取网站用户id
function getuid(){
 $url=getDomain();  //企业域名
 $urlarr=explode(".",$url);
	if(!stristr($urlarr[0],"v")){ //连接里不包含V
			$uid =0;									        
	
	 }else{
		 $aryName1=explode('v',$urlarr[0]); //获取企业i
		 $url=$aryName1[1];  //获取到的用户名
		 $uid = str_filter($url);
	
	 }
	 return $uid;
}*/

# 获取产品分类列表[多级]
function get_type_multi($wxid,$level=1){
	
	global $doc;
	global $db;
	$sql="select * from wx_company_cat where wid=".$wxid." and pid=0 ORDER BY orderList desc";
	$q=$db->execute($sql);
	$datas=array();
	$i=0;
	while($r=$db->fetch_array($q)){	
		$type1=array();
		$type1['id']=$r['id'];
		$type1['pid']=$r['pid'];
		$type1["catName"]=$r["catName"];
		$type1["orderList"]=$r["orderList"];
		$type1["is_last"]=$r["is_last"];
/*		$isHaveChi=$db->getOne("select count(id) from wx_company_news where wid=".$wxid." and companyCat=".$r['id']);	
		if($isHaveChi){
		 $type1["is_last"]=1;
		}
		$islast=$db->getRow("select is_last,mod_url  from wx_system_column where id=".$r["scid"]);	
		$type1["syis_last"]=$islast["is_last"];
		$type1["topage"]=$islast["mod_url"];$type1["ctype"]=$r["ctype"];*/
		
			$sctype=$db->getRow("select types,mod_url from wx_system_column where id=".$r["scid"]);	
			$type1["sctype"]=$sctype["types"];
			//$type1["topage"]=$sctype["mod_url"];
		
			 if($type1['sctype']==1 ){//图文列表 
				  $tohref="list.php?cid=".$type1['id'];
			 }else if($type1['sctype']==2){// 分类栏目
				  $tohref="list1.php?catid=".$type1['id'];
			 }else if($type1['sctype']==3){// 单页面
				 $tohref="show.php?act=lm&id=".$type1['id'];
			 }else if($type1['sctype']==4&&$sctype["mod_url"]<>""){// 链接栏目如会员中心
				$tohref=$sctype['mod_url']."?catid=".$type1['id'];;
			 }else if($new['sctype']==5&&$new["modurl"]<>""){// 自定义链接
				$tohref=$new['modurl'];
			 } 
			$type1["topage"]=$tohref;

		if($level>=2){
			$tempArr = getTypeLevelByPid($r['id'],$wxid,$level);
			if($tempArr!=NULL && count($tempArr)>0){
				$type1["type"] = $tempArr;
			}else{
			$type1["type"] = "";
			}
		}
		$datas[$i]=$type1;
		$i++;
	}
	return $datas;
}

//获取产品子集分类
function getTypeLevelByPid($pid,$wxid,$level){
	global $db;
	$sql="select * from wx_company_cat where wid=".$wxid." and pid=".$pid." ORDER BY orderList desc";
	$q=$db->execute($sql);
	$datas=array();
	$i=0;
	while($r=$db->fetch_array($q)){
		$type1=array();
		$type1['id']=$r['id'];
		$type1['pid']=$r['pid'];
		$type1["catName"]=$r["catName"];
		$type1["orderList"]=$r["orderList"];
		$type1["is_last"]=$r["is_last"];
		$type1["ctype"]=$r["ctype"];
	
			$sctype=$db->getRow("select types,mod_url from wx_system_column where id=".$r["scid"]);	
			$type1["sctype"]=$sctype["types"];
			//$type1["topage"]=$sctype["mod_url"];
	
			 if($type1['sctype']==1 ){//图文列表 
				  $tohref="list.php?cid=".$type1['id'];
			 }else if($type1['sctype']==2){// 分类栏目
				  $tohref="list1.php?catid=".$type1['id'];
			 }else if($type1['sctype']==3){// 单页面
				 $tohref="show.php?act=lm&id=".$type1['id'];
			 }else if($type1['sctype']==4&&$sctype["mod_url"]<>""){// 链接栏目如会员中心
				$tohref=$sctype['mod_url']."?catid=".$type1['id'];;
			 }else if($new['sctype']==5&&$new["modurl"]<>""){// 自定义链接
				$tohref=$new['modurl'];
			 } 
			$type1["topage"]=$tohref;
	
		
		if($level==2){
			$tempArr = getTypeLevelByPid($r['id'],$wxid,$level);
			if($tempArr!=NULL && count($tempArr)>0)
				$type1["type"] = $tempArr;
		}
		$datas[$i]=$type1;
		$i++;
	}
	return $datas;
}

# 获取所有栏目信息
function get_companyCatlist($wid,$pid=0){
	global $db;
if($pid==0){
	$catid=str_filter($_GET["catid"]);
	$sql = "SELECT * FROM wx_company_cat WHERE wid =".$wid." and pid=0 ORDER BY orderList desc "; 

}else{
	$sql = "SELECT * FROM wx_company_cat WHERE wid =".$wid." and pid=".$pid."  ORDER BY orderList desc "; 
}
$qs=$db->execute($sql);
while($new=$db->fetch_array($qs)){
	
	//echo $new["catName"];
	$sctype=$db->getRow("select types,mod_url from wx_system_column where id=".$new["scid"]);	
	$new["sctype"]=$sctype["types"];
	
	 if($new['sctype']==1 ){//图文列表 
		  $tohref="list.php?cid=".$new['id'];
	 }else if($new['sctype']==2){// 分类栏目
		  $tohref="list1.php?catid=".$new['id'];
	 }else if($new['sctype']==3){// 单页面
		 $tohref="show.php?act=lm&id=".$new['id'];
	 }else if($new['sctype']==4&&$sctype["mod_url"]<>""){// 链接栏目如会员中心
		$tohref=$sctype['mod_url']."?catid=".$new['id'];
	 }else if($new['sctype']==5&&$new["modurl"]<>""){// 自定义链接
		$tohref=$new['modurl'];
	 } 
	$new["topage"]=$tohref;
	
	$navinfo[]=$new;
	}
	return $navinfo;   
}



# 根据id获取栏目信息
function get_companyCatById($wid,$id){
	global $db;
	$ms=$db->getRow("select * from wx_company_cat where wid=$wid and id=$id");
	return $ms;   
}

# 根据id获取栏目信息
function get_PontoCatById($wid,$id){
	global $db;
	$sql="select * from wx_photo_card_cat where uid=$wid and id=$id";
	//echo $sql;
	$ms=$db->getRow($sql);
	return $ms;   
}



# 根据id获取新闻信息
function get_companyNewsById($wid,$id){
	global $db,$r;
	$ms=$db->getRow("select * from wx_company_news where wid=$wid and id=$id");
	return $ms;   
}

# 根据id获取素材信息
function get_materialById($uid,$id){
	global $db;
	$ms=$db->getRow("select * from wx_material where id={$id}");
	return $ms;   
}



/*旧 */

//$host=getDomain();  //企业域名
//$aryName=explode('.',$host);
//$aryName1=explode('v',$aryName[0]); //获取企业id
//
//if(!is_numeric($aryName1[1])){
//	new_404(); 
//}
//
//
//$uid=$db->getOne("select id from users where id=$aryName1[1] and status=2");
//
//
////用户名不存在
//if(!$uid){  new_404(); }
//define ("$uids",$uid);
//
//$uphone=$db->getOne("select wphone from websit where uid=$uid");
function new_404(){
	header("Status: 404 Not Found");
	header("HTTP/1.1 404 Not Found");
	//exit;
	die("<h1>您的服务已到期,续费请联系<a href='tel:400-011-3669' style='color: #0088cd;'>400-011-3669</a></h1>");
}


function hitcount($uids){
	global $db,$uphone;
	///echo "select wphone from websit where uid='$uids'";
	$uphone=$db->getOne("select wphone from websit where uid=$uids");
	return $uphone;
}



# 获取产品分类列表
function get_pro_type($uids){
	global $db;
	//$db = dblink();
	global $doc;
	$sql="select *  from company_cat where uid=".$uids." and pid=0 ORDER BY orderList asc,id desc";
	$q=$db->execute($sql);
	while($r=$db->fetch_array($q)){	
		$data[]=$r;
	}
	
	return $data;
}

# 获取新闻数据
function get_product_show($uid,$pid){
	global $db,$r;
	$r=$db->getRow("select * from company_news where uid=".$uid." and companyCat=".$pid." and is_trash=0 order by paixu desc,id desc limit 1 ");
	return $r;   
}


# 根据id获取新闻数据
function get_NewsById_show($uid,$id){
	global $db,$r;
	//echo "select * from company_news where uid=".$uid."   and id=".$id;
	$r=$db->getRow("select * from company_news where uid=".$uid."   and id=".$id );
	return $r;   
}

# 根据id获取产品数据
function get_ProductById_show($uid,$id){
	global $db,$r;
	$r=$db->getRow("select * from  product where uid=".$uid."  and is_delete=0 and id=".$id );
	return $r;   
}

# 根据id获取栏目名称
function get_CatById_show($uid,$id){
	global $db,$r;
	$ms=$db->getOne("select catName  from company_cat where uid=".$uid."  and id=".$id);
	return $ms;   
}


# 根据id获取栏目信息
function get_CatInfoById($uid,$id){
	global $db,$r;
	$r=$db->getRow("select * from company_cat where uid=".$uid."  and id=".$id);
	return $r;   
}

# 根据页面获取导航信息
function get_NavInfoById($uid,$pages){
	global $db,$r;
	$navigid = $db->getOne("select navig from  websit where uid=".$uid);
	if($navigid==2){ //自定义导航
		$sql = "SELECT nav_imgs as tupian_fenxiang,fx_title as title,fx_title as contents FROM mod_navig WHERE uid = ".$uid." and mod_url='".$pages."'"; 
	}else{				
		$sql = "SELECT nav_imgs as tupian_fenxiang,fx_title as title,fx_title as contents FROM  default_navig  WHERE 1=1  and mod_url='".$pages."'"; 
	}
	//echo $sql;
	$info=$db->getRow($sql);	
	
	return $info;   
}


# 根据页面获取导航名称
function get_NavTextById($uid,$pages){
	global $db,$r;
	$navigid = $db->getOne("select navig from  websit where uid=".$uid);
	if($navigid==2){ //自定义导航
		$sql = "SELECT mod_text FROM mod_navig WHERE uid = ".$uid." and mod_url='".$pages."'"; 
	}else{				
		$sql = "SELECT mod_text FROM  default_navig  WHERE 1=1  and mod_url='".$pages."'"; 
	}
	//echo $sql;
	$info=$db->getOne($sql);	
	
	return $info;   
}
	
		//$sql = "SELECT * FROM wx_company_news WHERE companyCat =".$id." and  wid=".$data["id"]." ORDER BY orderList desc "; 

# 获取新闻列表
function get_newlist($wid,$pid,$size,$pages){
	global $db;
	//$db = dblink();
	global $get_newlist;

	if($pid==0){
		$qry="select * from wx_company_news where wid=".$wid." order by orderList desc,id desc";
	}else{
		$qry="select * from wx_company_news where wid=".$wid." and companyCat=".$pid." order by orderList desc,id desc";
	}
	//echo $qry;
	$db->pagesize=$size;
	$q=$db->execute($qry);
	while($new=$db->fetch_array($q)){
		//echo "ddd"."<br/>";
		$get_newlist['data'][]=$new;
	}
	
	$pages="list.php?cid=".$pid;
	$get_newlist['maxpage']=$db->maxpage;
	if($pid!=0){
		$get_newlist['fenye']=$db->pageindex_mobile($pages."&page=%");
	}else{
		$get_newlist['fenye']=$db->pageindex_mobile($pages."&page=%");
	}
	 return $get_newlist;
}



function getAllyPhotoCatList($pid=0,$uid){
	global $db;
	//$db = dblink();
		$sql2="select * from wx_photo_card_cat where uid=$uid  and pid=".$pid."  order by orderList desc,id desc";
		$resultcat=$db->execute($sql2);
		while($row=$db->fetch_array($resultcat)){
			//echo $row["id"];
		//$havepro=$db->$rowgetOne("select count(id) from wx_visiting_card_cat where uid=$userid and pid=".$row["id"]);
		/*if($havepro){
			echo "<option  disabled='disabled' value='".$row['id']."'>".$row['cattitle']."</option>";
		}else{*/
			$row["topage"]="photoAlbum.php?pid=".$row["id"];
			$get_photolist[]=$row;
		//}
		}
	 return $get_photolist;
}

# 获取相册列表
function get_photolist($uid,$size,$catid=0,$pid=''){
	global $db;
	//$db = dblink();
	
	
	if($pid<>""){
		
		$isHaveChi=$db->getOne("select count(id) from wx_photo_card_cat where uid=".$uid." and pid=".$pid);	
		if($isHaveChi){
			$r["is_last"]=1;
		}
		$qry="select * from wx_photo_album where uid=".$uid." and pid=".$pid;
		
		//echo $qry
	}else{
		$qry="select * from wx_photo_album where uid=".$uid;
	}
	
	if($_REQUEST["keyword"]){
	
	$qry=$qry." and title like '%".$_REQUEST["keyword"]."%'";
	}
	
	$qry=$qry." order by orderList desc,id desc";
	
	
	//echo $qry;
	$db->pagesize=$size;
	$q=$db->execute($qry);
	while($new=$db->fetch_array($q)){
	//echo "ddd"."<br/>";
		$get_photolist['data'][]=$new;
	}
	
	if($pid==0){$pid=-1;} //-1查询所有相册
	$pages="photoAlbum.php?catid=".$catid."&pid=".$pid;
	
	$get_photolist['maxpage']=$db->maxpage;
	$get_photolist['fenye']=$db->pageindex_mobile($pages."&page=%");

	 return $get_photolist;
}


# 获取分享达人列表
function get_fenxiangdarenlist($uid,$size){
	global $db;
	//$db = dblink();
	global $wxid;
	$qry="select * from wx_fenxiangdaren where uid=".$uid." and isuse=1 order by id desc";
	//echo $qry;
	$db->pagesize=$size;
	$q=$db->execute($qry);
	while($new=$db->fetch_array($q)){
	//echo "ddd"."<br/>";
		$get_photolist['data'][]=$new;
	}
	
	$pages="fenxiangdarenlist.php";
	
	$get_photolist['maxpage']=$db->maxpage;
	$get_photolist['fenye']=$db->pageindex_mobile($pages."?wxid=".$wxid."&page=%");

	 return $get_photolist;
}

//根据用户wx_company_cat ID查询相册信息
function getPhotoInfoById($id,$uid){
	global $db;
	$sql="select * from wx_photo_album where id=$id and uid=$uid";
	$photoinfo=$db->getRow($sql);
	return $photoinfo;
}


# 获取360全景图列表
function get_view360list($uid,$size){
	global $db;
	//$db = dblink();
	$qry="select * from wx_view360 where uid=".$uid." order by orderList desc,id desc";
	//echo $qry;
	$db->pagesize=$size;
	$q=$db->execute($qry);
	while($new=$db->fetch_array($q)){
	//echo "ddd"."<br/>";
		$get_photolist['data'][]=$new;
	}
	
	$pages="view360List.php";
	
	$get_photolist['maxpage']=$db->maxpage;
	$get_photolist['fenye']=$db->pageindex_mobile($pages."?page=%");

	 return $get_photolist;
}


//根据用户wx_company_cat ID查询360全景图信息
function getview360InfoById($id,$uid){
	global $db;
	$sql="select * from wx_view360 where id=$id and uid=$uid";
	$photoinfo=$db->getRow($sql);
	return $photoinfo;
}

# 获取专家列表
function get_expertlist($uid,$size,$pages=""){
	global $db;
	$qry="select * from wx_expert where uid=".$uid." order by orderList desc,id desc";
	//echo $qry;
	$db->pagesize=$size;
	$q=$db->execute($qry);
	while($new=$db->fetch_array($q)){
	//echo "ddd"."<br/>";
		$get_photolist['data'][]=$new;
	}
	
	$get_photolist['maxpage']=$db->maxpage;
	$get_photolist['fenye']=$db->pageindex_mobile($pages."?page=%");

	 return $get_photolist;
}

//根据用户wx_company_cat ID查询360全景图信息
function getExpertInfoById($id,$uid){
	global $db;
	$sql="select * from wx_expert where id=$id and uid=$uid";
	$photoinfo=$db->getRow($sql);
	return $photoinfo;
}


# 获取cp列表
function get_Produectlist($uid,$pid,$size=1,$className='m_page'){
	global $db;
	//$db = dblink();
	global $get_newlist;

	if($pid==0){
		$qry="select * from product where uid=".$uid."  and is_delete=0 order by paixu desc,id desc";
	}else{
		$qry="select * from product where uid=".$uid." and companyCat=".$pid." and is_delete=0 order by paixu desc,id desc";
	}
	//echo $qry;
	$db->pagesize=$size;
	$q=$db->execute($qry);
	while($new=$db->fetch_array($q)){
		//echo "ddd"."<br/>";
		$get_newlist['data'][]=$new;
	}
	
	
	if($pid!=0){
		$get_newlist['fenye']=$db->pageindex_mobile($pages."?page=%",$className);
	}else{
		$get_newlist['fenye']=$db->pageindex_mobile($pages."?page=%",$className);
	}
	 return $get_newlist;
}


//自定义页面
function get_diy($id,$user_id){
	global $db,$cid;	
	$sql="select * from company_singlepage where uid=".$user_id."  and  id='".$id."'";
	$data=$db->getRow($sql);
	return $data;
}



//自定义页面
function get_WebsiteInfo($uid){
	global $db,$cid;	
	$wzsql="SELECT id,uid,wtitles,wkeywords,wIntroduction,wphone,wtupian_fenxiang FROM websit WHERE uid=$uid"; //登陆成功后查询网站信息
	$data=$db->getRow($wzsql);
	return $data;
}



//根据网址获取用户名和当前页面名称
function getUrlInfo(){
 $url=getDomain();  //企业域名
 $urlarr=explode(".",$url);
 
 if(!stristr($urlarr[0],"v")){ //连接里不包含V
	$urlinfo = array(
				'uid'  => '0',										        
				'pages' => '',				
	);

 }else{
	 $aryName1=explode('v',$urlarr[0]); //获取企业i
	 $url=$aryName1[1];  //获取到的用户名
	 
	 $phpself =$_SERVER['PHP_SELF'];
	 $str = end(explode("/",$phpself)); //当前访问的文件名
	
	$urlinfo = array(
				'uid'  => empty($url) ? '0' : trim($url),										        
				'pages' => empty($str) ? 'index.php' : trim($str),				
	);

 }

 return $urlinfo;
}

//
function get_now_page(){
	 $phpself =$_SERVER['PHP_SELF'];
	 $str = end(explode("/",$phpself)); //当前访问的文件名
	 return $str;
}




function showNavsOnindex($wid,$catid){
	global $db;
	//$db = dblink();
	if($catid<>"" && $catid<>0){
		$catid=str_filter($_GET["catid"]);
		$sql = "SELECT * FROM wx_company_cat WHERE wid =".$wid." and pid=".$catid." ORDER BY orderList desc "; 
	
	}else{
		$sql = "SELECT * FROM wx_company_cat WHERE wid =".$wid." and pid=0  ORDER BY orderList desc "; 
	}
	
	
	$qs=$db->execute($sql);
	
	while($r=$db->fetch_array($qs)){
		
		$isHaveChi=$db->getOne("select count(*) from wx_company_cat where wid=".$wid." and pid=".$r['id']);	
		if($isHaveChi){
			$r["is_last"]=1;
		}
		
		$islast=$db->getRow("select is_last,mod_url  from wx_system_column where id=".$r["scid"]);	
		$r["syis_last"]=$islast["is_last"];
		$r["topage"]=$islast["mod_url"];
		//$navinfo[]=$new;
		
		?>
           <li>
        
            <?php if($r['syis_last']==0 && $r['topage']!=""){?>
                 <a href="<?php echo $r['topage'];?>?uid=<?php echo $uid;?>#mp.weixin.qq.com">
            <?php }else{?>
                <?php if($r['ctype']==2){ //自定义栏目 ?>
                <a href="show.php?act=lm&id=<?php echo $r['id'];?>#mp.weixin.qq.com">
                <?php }else if($r['is_last']==1){?>
                 <a href="list1.php?catid=<?php echo $r['id'];?>#mp.weixin.qq.com">
                <?php }else{ ?>
                <a href="list.php?cid=<?php echo $r['id'];?>#mp.weixin.qq.com">
                <?php } ?>
                
            <?php }?>
             <span><img src="<?php echo "/UpLoad/".$r['img']; ?>"></span> <?php echo $r['catName'];?>    
             </a>
             <div class="c"></div>
         </li>  

		<?php
	}
	
}




//获取导航列表
function get_navings($uid){
global $db;
	//$db = dblink();
$navinfo=array();
$navigid=$db->getOne("select navig from  websit where uid=".$uid); //查询所属导航类型
if($navigid==2){ //自定义导航
	$sql = "SELECT * FROM mod_navig WHERE uid = '".$uid."'  ORDER BY mod_sorting "; 
}else{				
	$sql = "SELECT * FROM  default_navig  WHERE 1=1  ORDER BY mod_sorting "; 
}
$qs=$db->execute($sql);


while($new=$db->fetch_array($qs)){
	//echo $new["mod_text"];
	$navinfo[]=$new;
}

return $navinfo;
}



//获取用户下一步需要操作跳转的页面
function getstatusPage($status){
	$data="";
	switch($pages){
		case"1":
			$data="";	
			break;
		case"2":				
			$data="";
			break;
		case'3':
			$data="";
			break;
		case'4':
			$data="";
			break;
		
		default:
			$data="";
			break;
	}
	return $data;
}


//根据微信号查询会员信息
function getVipInfoByPhones($wxid,$uid){ 
	//$materialinfo=$db->getRow("select * from wx_material where uid=".uid." and id=".$rs1["mid"]);	
   	global $db;
	$vipinfo=$db->getRow("select id,phone,cardId,payIntegral,moneys,addTime,wxid,pwd,GradeId,qiandaojf,bzcontents,uname from wx_Card_Vip  where uid=$uid and wxid='$wxid' ");
	
	
	return $vipinfo;
}

function getvipinfo($wxid,$uid){
    //$materialinfo=$db->getRow("select * from wx_material where uid=".uid." and id=".$rs1["mid"]);
    global $db;
    $vipinfo=$db->getRow("select * from wx_Card_Vip  where uid = $uid and wxid = '$wxid' ");
    return $vipinfo;
}

//根据手机号码查询会员信息
function getVipInfoByPhonesAndWxid($wxid,$uid){ 
	//$materialinfo=$db->getRow("select * from wx_material where uid=".uid." and id=".$rs1["mid"]);	
   	global $db;
	$vipinfo=$db->getRow("select wc.*,mgc.levelTitle from  wx_Card_Vip as wc left join wx_memberGradeConfig as mgc on wc.GradeId=mgc.id where wc.wxid='".$wxid."' and wc.uid=".$uid);
	return $vipinfo;
}

//根据手机号码查询会员信息
function getlevelTitle_by_id($id){ 
	//$materialinfo=$db->getRow("select * from wx_material where uid=".uid." and id=".$rs1["mid"]);	
   	global $db;
	$levelTitle=$db->getOne("select levelTitle from  wx_memberGradeConfig where id=".$id);
	return $levelTitle;
}

/**以下是微商城兑换函数**/
# 获取产品列表
function get_Integral_Goodlist($pid,$size,$pages,$keyword=""){
	global $db;
	//$db = dblink();
	if($pid==0){
		$qry="select * from wx_integral_shop_goods where 1=1";
	}else{
		$qry="select * from wx_integral_shop_goods where  1=1 and pid=".$pid;
	}
	if($keyword<>""){
		$qry=$qry."  and shopname like '%$keyword%'";
	}
	$qry=$qry."  order by orderlist desc,id desc";
	
	//echo $qry;
	$db->pagesize=$size;
	$q=$db->execute($qry);
	while($new=$db->fetch_array($q)){
		//echo "ddd"."<br/>";
		$get_newlist['data'][]=$new;
	}
	
	//$pages="shop_goodlist.php?cid=".$pid;
	$get_newlist['counts']=$db->count;
	$get_newlist['maxpage']=$db->maxpage;
	if($pid!=0){
		$get_newlist['fenye']=$db->pageindex_shop($pages."&page=%");
	}else{
		$get_newlist['fenye']=$db->pageindex_shop($pages."&page=%");
	}
	 return $get_newlist;
}

# 根据id获取产品信息
function get_Integral_GoodInfoById($uid,$id){
	global $db;
	$ms=$db->getRow("select * from wx_integral_shop_goods where uid=$uid and id=$id");
	return $ms;   
}




/**以下是微商城函数**/

# 获取焦点图列表
function get_Shop_Focus_Img_List($uid){
	//$db = dblink();
	global $doc,$db;
	$data="";
	$sql="select *  from wx_shop_singlepage where uid=".$uid." and state=0 ORDER BY orderList desc,id desc";
	$q=$db->execute($sql);
	while($r=$db->fetch_array($q)){	
		
		if($r["imgtype"]==2){
			$r["img_url"]="subject_show.php?id=".$r["id"];
		}
		$data[]=$r;
	}
	
	return $data;
}

# 获取栏目列表
function get_Shop_Cat_List($uid,$cums){
	global $db;
	//$db = dblink();
	$data="";
	$sql="select * from wx_shop_cat where uid=".$uid." and is_show=1 and pid=0 ORDER BY orderList desc,id desc";
	$q=$db->execute($sql);
	$i=0;
	while($r=$db->fetch_array($q)){	
	$haveccats=$db->getOne("select count(id) from wx_shop_cat where uid=$uid and is_show=1 and pid=".$r['id']);
		if($haveccats){ //有子类
	?> 
  		  <li><a href="shop_catlist.php?cid=<?php echo $r['id']; ?>"><img src="<?php echo $r['img']; ?>"><span class="title"><?php echo $r['cattitle']; ?></span></a></li>
	<?php }else{ ?>
         <li><a href="shop_goodlist.php?cid=<?php echo $r['id']; ?>"><img src="<?php echo $r['img']; ?>"><span class="title"><?php echo $r['cattitle']; ?></span></a></li>
	<?php } ?>
       
	<?php
	$i=$i+1;
	}
/*	if($i>=$cums){
		$yus=$i%$cums;
	}else{
		$yus=$cums%$i;
	}
	
	//echo $i.":".$yus;
	
	if($yus<>0){ //菜单不够
		$yus=$cums-$yus;
		$sqls="select *  from wx_shop_goods where uid=".$uid."  ORDER BY orderlist desc,id desc limit $yus";
		$qs=$db->execute($sqls);
		while($rs=$db->fetch_array($qs)){	
		?>
                  <li><a href="shop_goodshow.php?cid=<?php echo $rs['pid']; ?>&id=<?php echo $rs['id']; ?>"><img src="<?php echo $rs['img']; ?>"><span class="title"><?php echo $rs['shopname']; ?></span></a></li>
		<?php
		}
	}	 //菜单不够结束*/
	
	
}


# 获取栏目列表
function get_Shop_Cat_ListArry($pid=0,$top=0){
	global $uid;
	global $db;
	//$db = dblink();
	$data="";
	if($top){
		$sql="select * from wx_shop_cat where uid=".$uid."  and is_show=1 and pid=$pid ORDER BY orderList asc,id desc limit 0,$top";
	}else{
		$sql="select * from wx_shop_cat where uid=".$uid."  and is_show=1 and pid=$pid ORDER BY orderList asc,id desc";
	}
	
	//echo $sql;
	$q=$db->execute($sql);
	$i=0;
	while($r=$db->fetch_array($q)){	
		//$haveccats=$db->getOne("select count(id) from wx_shop_cat where uid=$uid and pid=".$r['id']);
		//$r["haveccats"]=$haveccats;
		//if($haveccats){ //有
		if($r["have_child"]==1){ //有
			$r['tohref']="cat_list.php?cid=".$r['id'];
		}else{
			$r['tohref']="good_list.php?cid=".$r['id'];
		}
		
		$data[]=$r;
	}
	return $data;
	
}


# 获取前2栏目列表
function get_Shop_Cat_ListArryLimit2($slimit=0,$elimit=2){
	global $uid;
	global $db;
	//$db = dblink();
	$data="";
	$sql="select * from wx_shop_cat where uid=".$uid." and is_show=1  and pid=0 ORDER BY orderList desc,id desc limit $slimit,$elimit";
	//echo $sql;
	$q=$db->execute($sql);
	$i=0;
	while($r=$db->fetch_array($q)){	
		$haveccats=$db->getOne("select count(id) from wx_shop_cat where uid=$uid and is_show=1 and pid=".$r['id']);
		$r["haveccats"]=$haveccats;
		if($haveccats){ //有
			$r['tohref']="shop_catlist.php?cid=".$r['id'];
		}else{
			$r['tohref']="shop_goodlist.php?cid=".$r['id'];
		}
		$data[]=$r;
	}
	return $data;
	
}



# 获取产品列表
function get_GoodlistByPids($uid,$pid,$sid=0,$eid=1){
	global $db;
	//$db = dblink();
	$qry="select * from wx_shop_goods where status=2 and uid=".$uid." and pid=".$pid;
	$qry=$qry."  order by orderlist desc,id desc limit $sid,$eid";
	//echo $qry;
	$q=$db->execute($qry);
	while($new=$db->fetch_array($q)){	
		$get_newlist[]=$new;
	}
	 return $get_newlist;
}


//获取用户下一步需要操作跳转的页面
/**
$styles 显示样式
**/
function getShopListByMod($uid,$styles,$mod,$top){
	global $db;
	$sql="select *  from wx_shop_goods where  status=2 and uid=".$uid." and scid=$mod ORDER BY orderlist desc,id desc limit $top";
	
	$q=$db->execute($sql);
	$i=1;
	while($rs=$db->fetch_array($q)){	
		$hrefs="shop_goodshow.php?cid=".$rs['pid']."&id=".$rs['id']; 
		switch($styles){
			case"1": //模块一 推荐商品四列
				?>
                  <li><a href="<?php echo $hrefs; ?>"><img src="<?php echo $rs['img']; ?>"><span class="title"><?php echo $rs['shopname']; ?></span></a></li>
				<?php
				break;
			case'2': //模块二,推荐商品3张图片的第一张
				if($i==1){			
				?>
                  <p>
                     <a href="<?php echo $hrefs; ?>"><img src="<?php echo $rs['img']; ?>" class="half-img">
                       <span class="jg_in"><span class="jg_in_left"><?php echo $rs['shopname']; ?></span><span class="jg_in_right"><?php echo $rs['fixed_price']; ?></span></span>
                       </a>
                  </p>
                <?php } 
				break;
			case"3": //模块二,推荐商品3张图片的后两张
				if($i<>1){			
				?>
                 <p>
                     <a href="<?php echo $hrefs; ?>"><img src="<?php echo $rs['img']; ?>" class="half-img">
                     <span class="jg_in1"><span class="jg_in_right"><?php echo $rs['fixed_price']; ?></span></span>
                     </a>
                  </p>
				<?php
				}	
				break;
			
			case'4': //模块三推荐商品1行2张图片(大图)
				?>
                 <li><a href="<?php echo $hrefs; ?>"><img src="<?php echo $rs['img']; ?>"><span class="jg_in1"><span class="jg_in_right"><?php echo $rs['fixed_price']; ?></span></span></a></li>

				<?php
				break;
		  case'5': //模块四 六个列表
				?>
                 
                 <li> <a href="<?php echo $hrefs; ?>"> <img src="<?php echo $rs['img']; ?>">
                    <div>
                      <p class="p1"><?php echo $rs['shopname']; ?></p>
                      <p class="p2">￥<?php echo $rs['fixed_price']; ?></p>
                    </div>
                    </a>
                    <div class="c"></div>
                 </li>
				<?php
				break;
		  case'6': //模块二,推荐商品3张图片的第一张
				if($i==1){			
				?>
                  <p>
                     <a href="<?php echo $hrefs; ?>"><img src="<?php echo $rs['img']; ?>" class="half-img">
                       <span class="jg_in"><span class="jg_in_left"><?php echo $rs['shopname']; ?></span><span class="jg_in_right"><?php echo $rs['fixed_price']; ?></span></span>
                       </a>
                  </p>
                <?php } 
				break;
			case"7": //模块二,推荐商品3张图片的后两张
				if($i<>1){			
				?>
                 <p>
                     <a href="<?php echo $hrefs; ?>"><img src="<?php echo $rs['img']; ?>" class="half-img">
                     <span class="jg_in1"><span class="jg_in_right"><?php echo $rs['fixed_price']; ?></span></span>
                     </a>
                  </p>
				<?php
				}	
				break;	
			default:
				$data="";
				break;
		}//switch结束
		$i=$i+1;
	} //循环结束
}


# 获取产品列表
function get_Goodlist($uid,$pid,$size,$pages,$keyword=""){
	global $db;
	//$db = dblink();
	if($pid==0){
		$qry="select * from wx_shop_goods where  status=2 and  uid=".$uid;
	}else{
		$qry="select * from wx_shop_goods where  status=2 and  uid=".$uid." and pid=".$pid;
	}
	if($keyword<>""){
		$qry=$qry."  and shopname like '%$keyword%'";
	}
	$qry=$qry."  order by orderlist desc,id desc";
	
	//echo $qry;
	$db->pagesize=$size;
	$q=$db->execute($qry);
	while($new=$db->fetch_array($q)){
		//echo "ddd"."<br/>";
		$get_newlist['data'][]=$new;
	}
	
	//$pages="shop_goodlist.php?cid=".$pid;
	$get_newlist['counts']=$db->count;
	$get_newlist['maxpage']=$db->maxpage;
	if($pid!=0){
		$get_newlist['fenye']=$db->pageindex_shop($pages."&page=%");
	}else{
		$get_newlist['fenye']=$db->pageindex_shop($pages."&page=%");
	}
	 return $get_newlist;
}


#
function get_Aoya_Goodlist($uid,$limit=7){
	global $db;
	//$db = dblink();
	$qry="select * from wx_shop_goods where  status=2 and  uid=".$uid;
	$qry=$qry."  order by orderlist desc,id desc limit $limit";
	//echo $qry;
	$q=$db->execute($qry);
	while($new=$db->fetch_array($q)){	
		$get_newlist[]=$new;
	}
	 return $get_newlist;
}

function get_hots_Goodlist($limit=999){
    global $db;
    //$db = dblink();
    $qry="select * from wx_shop_goods where status=2 and is_hot=1";
    $qry=$qry."  order by orderlist asc,id desc limit $limit";
    //echo $qry;
    $q=$db->execute($qry);
    while($new=$db->fetch_array($q)){
        $get_newlist[]=$new;
    }
    return $get_newlist;
}
function get_sales_Goodlist($limit=999){
    global $db;
    //$db = dblink();
    $qry="select * from wx_shop_goods where status=2";
    $qry=$qry."  order by sales desc limit $limit";
    //echo $qry;
    $q=$db->execute($qry);
    while($new=$db->fetch_array($q)){
        $get_newlist[]=$new;
    }
    return $get_newlist;
}
function get_time_Goodlist($limit=999){
    global $db;
    //$db = dblink();
    $time = time();
    $qry="select * from wx_shop_goods where status=2 and week>$time";
    $qry=$qry."  order by orderlist asc,id desc limit $limit";
    //echo $qry;
    $q=$db->execute($qry);
    while($new=$db->fetch_array($q)){
        $get_newlist[]=$new;
    }
    return $get_newlist;
}
# 获取傲亚生活产品列表
function get_index_Goodlist($pid,$limit=7){
	global $db;
	//$db = dblink();
	$cidlist=get_all_wx_shop_cat_config_id($pid);
	$qry="select * from wx_shop_goods where  status=2 and istop=1 and (pid in(".$cidlist."))";
//	$qry="select * from wx_shop_goods where   pid=".$pid;
	$qry=$qry."  order by orderlist desc,id desc limit $limit";
	//echo $qry;
	$q=$db->execute($qry);
	while($new=$db->fetch_array($q)){
		$get_newlist[]=$new;
	}
	return $get_newlist;
}

# 获取返佣金额
function get_fanyongjine_limit($orderid){
    global $db;
    //$db = dblink();
    $qry="select * from wx_incentive_score where  orid=$orderid";
    //echo $qry;
    $q=$db->execute($qry);
    while($new=$db->fetch_array($q)){
        $get_newlist[]=$new;
    }
    return $get_newlist;
}

# 获取热销产品列表
function get_Hot_Goodlist($uid,$limit=0){
	//$db = dblink();
	global $db;
	$qry="select * from wx_shop_goods where  status=2 and  uid=".$uid;
	$qry=$qry."  order by sales desc,orderlist desc,id desc limit $limit";
	//echo $qry;
	$q=$db->execute($qry);
	while($new=$db->fetch_array($q)){	
		$get_newlist[]=$new;
	}
	 return $get_newlist;
}




# 根据id获取产品信息
function get_GoodInfoById($uid,$id){
	global $db;
	$ms=$db->getRow("select * from wx_shop_goods where  status=2 and  uid=$uid and id=$id");
	return $ms;   
}

# 根据id获取产品规格信息
function getStandardInfoById($uid,$id){
	global $db;
	$ms=$db->getRow("select * from wx_shop_standard_info where uid=$uid and id=$id");
	return $ms;   
}

# 根据id获取栏目信息
function getShopCatIfoById($id,$uid){
	global $db;
	///echo "select wphone from websit where uid='$uids'";
	$catinfo=$db->getRow("select * from wx_shop_cat where id=$id and is_show=1 and uid=$uid");
	return $catinfo;
}

# 根据pid获取子栏目列表
function getShopCatListById($pid,$uid){
	global $db;
	//$db = dblink();
	$data="";
	$sql="select *  from wx_shop_cat where uid=".$uid." and is_show=1 and pid=$pid ORDER BY orderList desc,id desc";
	$q=$db->execute($sql);
	$i=1;
	while($rs=$db->fetch_array($q)){
		$haveccat=$db->getOne("select count(id) from wx_shop_cat where uid=$uid and is_show=1 and pid=".$rs["id"]);
		if($haveccat){
			$rs['tohref']="shop_catlist.php?cid=".$rs['id'];	
		}else{
			$rs['tohref']="shop_goodlist.php?cid=".$rs['id'];	
		}
		
		
		$data[]=$rs;
	}
	return $data;
}

# 获取栏目列表
function get_Shop_StandardListArry($goodid){
	global $db;
	//$db = dblink();
	global $uid;
	$data="";
	$sql="select * from wx_shop_standard_info where uid=".$uid."  and goodid=$goodid ORDER BY id asc";
	//echo $sql;
	$q=$db->execute($sql);
	$i=0;
	while($r=$db->fetch_array($q)){	
	//echo $r["sname1"];
		$data[]=$r;
	}
	return $data;
	
}

//生成唯一订单号:商城
function creatorderNumber_orders($id)
{
	//echo time()."<br/>";
/*	$fists=substr(time(),-3);
	$nexts=10126725789+$id;
	$ornumbers=$fists.$nexts;
	$sums=0;
	//echo $fists."<br/>";
	//echo $ornumbers."<br/>";
	for ($i = 1; $i <= strlen($ornumbers); $i++) {
		$rest=substr($ornumbers, $i-1,1);
		$sums=$sums+$rest;
		//echo "i:".$i."sums:".$sums."rest:".$rest."<br/>";
	}
	//echo $sums."<br/>";
	$lastnum=substr($sums,-1);*/
	$ornumbers=time().$id.mt_rand(100,999);
	return $ornumbers;
}

//生成唯一订单号：积分兑换
function creatorderNumber_integral_orders($id)
{
	//echo time()."<br/>";
	$fists=substr(time(),-3);
	$nexts=101267257892+$id;
	$ornumbers=$fists.$nexts;
	$sums=0;
	//echo $fists."<br/>";
	//echo $ornumbers."<br/>";
	for ($i = 1; $i <= strlen($ornumbers); $i++) {
		$rest=substr($ornumbers, $i-1,1);
		$sums=$sums+$rest;
		//echo "i:".$i."sums:".$sums."rest:".$rest."<br/>";
	}
	//echo $sums."<br/>";
	$lastnum=substr($sums,-1);
	$ornumbers=$ornumbers.$lastnum;
	
	return $ornumbers;
}


# 获取收货地址列表
function get_Vip_Addr_List($vipid){
	//$db = dblink();
	global $db;
	$data="";
	$sql="select * from wx_shop_vip_addr where vipid=".$vipid." and is_del = 1 ORDER BY isdefualt desc, id desc";
	$q=$db->execute($sql);
	while($r=$db->fetch_array($q)){	
		$data[]=$r;
	}
	
	return $data;
}



# 获取名片信息列表
function get_Vip_card_info_List($vipid){
	//$db = dblink();
	global $db;
	$data="";
	$sql="select *  from wx_shop_vip_card_info where vipid=".$vipid." ORDER BY isdefualt desc, id desc";
	$q=$db->execute($sql);
	while($r=$db->fetch_array($q)){	
		$data[]=$r;
	}
	
	return $data;
}

# 获取支付方式列表
function get_PayTypes_List($uid,$istimexz=0){
	global $db;
	//$db = dblink();
	if($istimexz==0){
		$sql="select *  from wx_pay_type where uid=".$uid." and isdisable=1 and paytype<>4 ORDER BY orderlist desc, id desc";
	}else{
		$sql="select *  from wx_pay_type where uid=".$uid." and isdisable=1 and paytype not in(4,2,5) ORDER BY orderlist desc, id desc";
	}
	
	$q=$db->execute($sql);
	while($r=$db->fetch_array($q)){	
	?>
     <option value="<?php echo $r["paytype"] ?>"><?php echo $r["payname"] ?></option>
	<?php	
	}
}

# 根据类型获取订单列表
function get_Order_ListByType($vipid,$uid,$status=0,$paytypes=0,$pages='shop_order_list.php?type=more'){
	//$db = dblink();
	global $db;
	$data="";
	if($status==-1){
			//$sql="select * from wx_shop_order where vipid=$vipid and uid=$uid and status in(0,1,2,3) ORDER BY id desc";
			$sql="select * from wx_shop_order where vipid=$vipid and uid=$uid and paytypes!=$paytypes and status!=4  and is_del=1 ORDER BY id desc";
	}else{
		$sql="select * from wx_shop_order where vipid=$vipid and uid=$uid and status=$status and paytypes!=$paytypes and is_del=1 ORDER BY id desc";
	}
	$db->pagesize=10;
	$q=$db->execute($sql);
	while($r=$db->fetch_array($q)){	
		$data['data'][]=$r;
	}
	
	$data['counts']=$db->count;
	$data['maxpage']=$db->maxpage;
	if($pid!=0){
		$data['fenye']=$db->pageindex_mobile($pages."&page=%");
	}else{
		$data['fenye']=$db->pageindex_mobile($pages."&page=%");
	}
	
	return $data;
}

//根据订单id查询订单信息
function getOrderInfoByids($orid,$uid,$vipid){
	//$materialinfo=$db->getRow("select * from wx_material where uid=".uid." and id=".$rs1["mid"]);	
   	global $db;
	$orinfo=$db->getRow("select * from wx_shop_order where uid='".$uid."' and vipid=$vipid and id=".$orid);
	return $orinfo;
}

function getOrderInfoByids4($orid,$cuid,$vipid){
    //$materialinfo=$db->getRow("select * from wx_material where uid=".uid." and id=".$rs1["mid"]);
    global $db;
    $orinfo=$db->getRow("select * from wx_shop_order where cuid='".$cuid."' and vipid=$vipid and id=".$orid);
    return $orinfo;
}

function getOrderInfoByids3($orid,$vipid){
    //$materialinfo=$db->getRow("select * from wx_material where uid=".uid." and id=".$rs1["mid"]);
    global $db;
    $orinfo=$db->getRow("select * from wx_shop_order where id='".$orid."' and vipid=$vipid ");
    return $orinfo;
}


function getOrderInfoByids2($orid,$cuid,$vipid){
    //$materialinfo=$db->getRow("select * from wx_material where uid=".uid." and id=".$rs1["mid"]);
    global $db;
    $orinfo=$db->getRow("select * from wx_shop_order2 where cuid='".$cuid."' and vipid=$vipid and id=".$orid);
    return $orinfo;
}

function getStatusInfoByids($orderid){
    global $db;
    $sql="select *  from wx_shop_order_good where order_id=$orderid ORDER BY id asc";
    $q=$db->execute($sql);
    while($r=$db->fetch_array($q)){
        $data[]=$r;
    }

    return $data;
}

function getGoodInfoByGoodid($orid,$uid,$vipid){
    //$materialinfo=$db->getRow("select * from wx_material where uid=".uid." and id=".$rs1["mid"]);
    global $db;
    $orinfo=$db->getRow("select * from wx_shop_order_good where uid='".$uid."' and vipid=$vipid and id=".$orid);
    return $orinfo;
}

//根据订单状态查询订单数量
function getCountOrderByVipid($where,$uid,$vipid){ 
	//$materialinfo=$db->getRow("select * from wx_material where uid=".uid." and id=".$rs1["mid"]);	
   	global $db;
	$orinfo=$db->getOne("select count(id) from wx_shop_order where uid='".$uid."' and vipid=$vipid ".$where);
	return $orinfo;
}

//查询会员是否有订单
function getCountOrderCountByVipid($uid,$vipid){ 
	//$materialinfo=$db->getRow("select * from wx_material where uid=".uid." and id=".$rs1["mid"]);	
   	global $db;
	$orinfo=$db->getOne("select count(id) from wx_shop_order where uid='".$uid."' and vipid=$vipid and status<>4");
	return $orinfo;
}


//根据查询默认收货地址id
function getDefaultAddrId($vipid){ 
	//$materialinfo=$db->getRow("select * from wx_material where uid=".uid." and id=".$rs1["mid"]);	
   	global $db;
	$orinfo=$db->getOne("select id from wx_shop_vip_addr where  vipid=$vipid and isdefualt=1");
	return $orinfo;
}

//根据查询默认名片信息id
function getDefaultcard_info_Id($vipid){ 
	//$materialinfo=$db->getRow("select * from wx_material where uid=".uid." and id=".$rs1["mid"]);	
   	global $db;
	$orinfo=$db->getOne("select id from wx_shop_vip_card_info where  vipid=$vipid and isdefualt=1");
	return $orinfo;
}




# 获取符合要求的优惠券列表
function get_Coupon_Lists($vipid,$uid,$mon=0){
	global $db;
	//$db = dblink();
	$data="";
	$sql="select wx_CouponLog.*,wx_CouponInfo.useRestrictions,wx_CouponInfo.title,wx_CouponInfo.statustime,wx_CouponInfo.endtime from  wx_CouponLog,wx_CouponInfo where  wx_CouponLog.CId=wx_CouponInfo.id and wx_CouponLog.uid=".$uid." and wx_CouponLog.CVipId=".$vipid." and wx_CouponLog.status=1  and wx_CouponInfo.statustime<=".time()." and wx_CouponInfo.endtime>=".time()." order by id desc";
	$q=$db->execute($sql);
	//if($db->count)
	while($r=$db->fetch_array($q)){	
		if($r["useRestrictions"]>$mon){
	?>
    <option value="<?php echo $r["id"]; ?>" <?php if($r["useRestrictions"]>$mon){ ?> disabled="disabled" <?php } ?>><?php echo $r["title"]."(".$r["moneys"]."元) (满".$r["useRestrictions"]."元使用)"; ?></option>
    <?php	
		}else{
	?>
    <option value="<?php echo $r["id"]; ?>" data-price="<?php echo $r["moneys"] ?>"><?php echo $r["title"]."(".$r["moneys"]."元)"; ?></option>
    <?php	
		}
	?>
    	 
	<?php 
	}
}

//根据优惠券id查询优惠券面值
function getCouponLogMoneyById($uid,$vipid,$yhqid){ 
	//$materialinfo=$db->getRow("select * from wx_material where uid=".uid." and id=".$rs1["mid"]);	
   	global $db;
	$sqls="select wx_CouponLog.moneys from  wx_CouponLog,wx_CouponInfo where  wx_CouponLog.CId=wx_CouponInfo.id and wx_CouponLog.uid=".$uid." and wx_CouponLog.CVipId=".$vipid."  and wx_CouponInfo.statustime<=".time()." and wx_CouponInfo.endtime>=".time()." and wx_CouponLog.id=".$yhqid;
	$yhqmoneys=$db->getOne($sqls);
	return $yhqmoneys;
}


//根据运费模板id和购买数量，查询运费总金额
function getYunFeiMoneyById($uid,$mbid=0,$nums){ 
   	global $db;
	$summoney=0;

	//$yunfeimb=$db->getOne("select yunfei_mb from wx_shop_goods where uid=$userid  and id=$shopid");
	if($mbid){ //设置了模板
		$yunfeimbinfo=$db->getRow("select * from wx_shop_yunfei_mb where uid=$uid  and id=$mbid");
		if($yunfeimbinfo){
			if($nums>$yunfeimbinfo["nJian"]){ //大于首N件
				
				$summoney=$yunfeimbinfo["nMoney"]; 
				$sxnums=$nums-$yunfeimbinfo["nJian"];//首N件后还剩的件数
				if($sxnums>$yunfeimbinfo["mJian"]){ //大于续M件
			
					$ges=$sxnums/$yunfeimbinfo["mJian"]; //有几倍的M
					$yushu=$sxnums%$yunfeimbinfo["mJian"];//有几倍的M余数
					if($yushu>0){ //有余数
						$ges=$ges+1;
					
						$summoney=$summoney+$ges*$yunfeimbinfo["mMoney"];
					}else{
						$summoney=$summoney+$ges*$yunfeimbinfo["mMoney"];
					}
					
					
				}else{
					$summoney=$summoney+$yunfeimbinfo["mMoney"];//直接等于首费加续费
				}
			}else{ 
				$summoney=$yunfeimbinfo["nMoney"]; //直接等于首费
			}
		}
	}else{ //不需要运费
		$summoney=0;
	}
	return $summoney;
}


function getYuYueFrom($yuyue_info){ 
 	global $vipinfo;
	?>
	 <tr>
        <td align="right">您的姓名：</td>
        <td><input name="uname" id="uname" type="text" value="<?php echo $vipinfo["uname"]; ?>" placeholder="请输入您的姓名" class="px" /></td>
      </tr>
      <tr>
        <td width="80px;" align="right">手机号码：</td>
        <td><input name="uphone" id="uphone" type="text" value="<?php echo $vipinfo["phone"]; ?>" placeholder="请输入手机号码" class="px" /></td>
      </tr>
	<?php
  $infos=json_decode(html_entity_decode($yuyue_info),true);
	$j=0;
	if($infos){
	foreach($infos as $zdyinfo) //循环开始
	{
	
	 if($zdyinfo['textname']<>""){ ?>
	 <tr >
	  <td align="right" valign="top"><?php echo $zdyinfo['textname'];?>：</td>
	  <td>
	  <?php 
		if($zdyinfo['texttype']==1){ //单行文字
	  ?>
	  <input name="textvalue<?php echo $j?>" id="textvalue<?php echo $j?>" type="text" placeholder="<?php echo $zdyinfo['textvalue'];?>" class="px" />
	  <?php 	
		}else  if($zdyinfo['texttype']==2){ //日期选择
	  ?>
	   <input name="textvalue<?php echo $j?>" id="statustime" type="text" readonly="readonly" value="" class="px m_input_out" onFocus="this.className='px m_input_on';this.onmouseout='';WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM-dd'});" onBlur="this.className='px m_input_off';this.onmouseout=function(){this.className='px m_input_out'};" onMouseMove="this.className='px m_input_move'" onMouseOut="this.className='px m_input_out'" placeholder="<?php echo $zdyinfo['textvalue'];?>" />
	  <?php 	
		}else  if($zdyinfo['texttype']==3){ //下拉框
		$xlkinfo=explode('|',$zdyinfo['textvalue']);
	  ?>
	  <select id="textvalue<?php echo $j?>" name="textvalue<?php echo $j?>" class="text_select">
	  <?php  for ($i=0;$i<count($xlkinfo);$i++){ //循环开始
	  ?>
	  <option value="<?php echo $xlkinfo[$i] ?>"><?php echo $xlkinfo[$i] ?></option>
	  <?php } ?>
	  </select>

	  <?php 	
		}else  if($zdyinfo['texttype']==4){ //多行文字
	  ?>
			<textarea name="textvalue<?php echo $j?>" id="textvalue<?php echo $j?>"  placeholder="<?php echo $zdyinfo['textvalue'];?>"  rows="4" class="px"></textarea>

	   <?php 	
		}else  if($zdyinfo['texttype']==5){ //手机号码
	  ?>
			 <input name="textvalue<?php echo $j?>" id="zdy<?php echo $i?>"  type="tel" placeholder="<?php echo $zdyinfo['textvalue'];?>" class="px" />

 	  <?php 	
		}else  if($zdyinfo['texttype']==6){ //单选按钮
          $xlkinfo=explode('|',$zdyinfo['textvalue']);
			for ($i=0;$i<count($xlkinfo);$i++){ //循环开始
		  ?>
		
		  <label><input name="textvalue<?php echo $j?>" class="radioItem" id="textvalue<?php echo $j?>" type="radio" value="<?php echo $xlkinfo[$i] ?>" ><?php echo $xlkinfo[$i] ?></label>&nbsp;<br/>
	 <?php } // 循环结束
	 	
	   }else if($zdyinfo['texttype']==7){ //复选框
           $xlkinfo=explode('|',$zdyinfo['textvalue']);
		 // echo $vipvalueinfo[$j]["textvalue"][0];
		  for ($i=0;$i<count($xlkinfo);$i++){ //循环开始
		  ?>
           <label><input type="checkbox" name="textvalue<?php echo $j?>[]" id="textvalue<?php echo $j?>"  value="<?php echo $xlkinfo[$i] ?>" /><?php echo $xlkinfo[$i] ?></label><br/>
           <?php
		    //if(($i+1)%2==0){ echo "<br/>";} 
	      } // 循环结束
	 	
	   }
	  ?>
	  </td>
	</tr>
	<?php 
		}
		$j=$j+1;
	  } 
	}
	
}


//根据权限名称判断是否有权限
function selectIsHaveProByName($uid,$proname){
	 global $db;
	 
	 $nowurlid=$db->getOne("select id from wx_user_privs where priv_name='".$proname."'");	
	 if($nowurlid>0){ //权限存在
		$userspriv=$db->getOne("select priv from wx_users where id=$uid");	//查询用户权限
	 	if($userspriv<>""){ //没有设置权限
			$arrayss=explode(",",$userspriv);
			if(in_array($nowurlid, $arrayss)){
				return true;
			}else{
				return false;	
			}
		}else{
			return false;	
		}
		 
	 }else{
	 	return false;
	 }
	 
}

//根据手机号码查询会员信息
function getVipInfoByPhone($wxid,$uid){ 
	//$materialinfo=$db->getRow("select * from wx_material where uid=".uid." and id=".$rs1["mid"]);	
   	global $db;
	$vipinfo=$db->getRow("select cv.id,cv.phone,cv.cardId,cv.payIntegral,cv.moneys,cv.addTime,cv.wxid,cv.pwd,cv.GradeId,cv.uname,cv.addr,cv.QQ,cv.uEmail,cv.endtime  from wx_Card_Vip as cv  left join wx_AttentionUser as au on cv.wxid=au.wxid  where cv.uid=$uid and cv.wxid='$wxid' and au.status !=2");
	//echo "select cv.id,cv.phone,cv.cardId,cv.payIntegral,cv.moneys,cv.addTime,cv.wxid,cv.pwd,cv.GradeId  from wx_Card_Vip as cv  left join wx_AttentionUser as au on cv.wxid=au.wxid  where cv.uid=$uid and cv.wxid='$wxid' and au.status !=2";
	
	return $vipinfo;
}



/**微餐饮开始**/
function get_cy_cat_List($uid){
	global $db;
	//$db = dblink();
	$data="";
	$sql="select *  from wx_cy_cat where uid=".$uid."  ORDER BY orderList desc,id desc";
	$q=$db->execute($sql);
	while($r=$db->fetch_array($q)){	
		$data[]=$r;
	}
	
	return $data;
}

function get_wx_shop_cat_limit($uid,$pid){
    global $db;
    //$db = dblink();
    $data="";
    $sql="select * from wx_shop_cat where uid = $uid and pid = $pid order by orderList asc";
    $q=$db->execute($sql);
    while($r=$db->fetch_array($q)){
        $data[]=$r;
    }

    return $data;
}

function get_wx_shop_cat_limitone($uid,$pid){
    global $db;
    //$db = dblink();
    $data="";
    $sql="select * from wx_shop_cat where uid = $uid and pid = $pid and is_show = 1 order by orderList asc";
    $q=$db->execute($sql);
    while($r=$db->fetch_array($q)){
        $data[]=$r;
    }

    return $data;
}

function get_wx_shop_goods_limit($id){
    global $db;
    //$db = dblink();
    $data="";
    $sql="select * from wx_shop_goods where id = $id order by orderlist asc";
    $q=$db->execute($sql);
    while($r=$db->fetch_array($q)){
        $data[]=$r;
    }

    return $data;
}

function get_wx_shop_goods_limitone($pid){
    global $db;
    //$db = dblink();
    $data="";
    $sql="select * from wx_shop_goods where pid = $pid";
    $q=$db->execute($sql);
    while($r=$db->fetch_array($q)){
        $data[]=$r;
    }

    return $data;
}

function get_wx_shop_goods_limittwo($pid,$limit,$limit1){
    global $db;
    //$db = dblink();
    $data="";
    $sql="select * from wx_shop_goods where pid = $pid limit $limit,$limit1";
    $q=$db->execute($sql);
    while($r=$db->fetch_array($q)){
        $data[]=$r;
    }

    return $data;
}

function get_wx_company_cat_limit($pid){
    global $db;
    //$db = dblink();
    $data="";
    $sql="select * from wx_company_cat where pid = $pid";
    $q=$db->execute($sql);
    while($r=$db->fetch_array($q)){
        $data[]=$r;
    }

    return $data;
}

function get_wx_company_news_limit($companyCat){
    global $db;
    //$db = dblink();
    $data="";
    $sql="select * from wx_company_news where companyCat = $companyCat order by orderlist asc";
    $q=$db->execute($sql);
    while($r=$db->fetch_array($q)){
        $data[]=$r;
    }

    return $data;
}

function get_cy_cp_List($uid){
	global $db;
	//$db = dblink();
	$data="";
	$sql="select *  from wx_cy_cp where uid=".$uid." and is_del=0 and is_sj=1  ORDER BY istop asc,orderList desc,id desc";
	$q=$db->execute($sql);
	while($r=$db->fetch_array($q)){	
		$data[]=$r;
	}
	
	return $data;
}


function get_cy_cp_ids($uid){
	//$db = dblink();
	global $db;
	$data="";
	$sql="select id from wx_cy_cp where uid=".$uid." and is_del=0 and is_sj=1  ORDER BY istop asc,orderList desc,id desc";
	$q=$db->execute($sql);
	while($r=$db->fetch_array($q)){	
		$data[]=$r;
	}
	
	return $data;
}


# 获取收货地址列表
function get_vip_cy_addr_list($vipid){
    //$db = dblink();
    global $db;
    $data="";
    $sql="select *  from wx_cy_addr where vipid=".$vipid." ORDER BY isdefualt desc, id desc";
    $q=$db->execute($sql);
    while($r=$db->fetch_array($q)){
        $data[]=$r;
    }

    return $data;
}
# 获取品牌分类
function get_brand_cat_list(){
    //$db = dblink();
    global $db;
    $data="";
    $sql="select *  from wx_brand_cat order by orderlist asc";
    $q=$db->execute($sql);
    while($r=$db->fetch_array($q)){
        $data[]=$r;
    }

    return $data;
}
# 获取限时抢购商品
function get_time_goods_list($i){
    //$db = dblink();
    global $db;
    $time = strtotime(date("Y-m-d",time()))+86400*$i;
    $data="";
    $sql="select *  from wx_shop_goods where is_time=".$time." ORDER BY orderList desc, id desc";
    $q=$db->execute($sql);
    while($r=$db->fetch_array($q)){
        $data[]=$r;
    }

    return $data;
}

//根据查询默认收货地址id
function getDefaultCyAddrId($uid,$vipid){ 
	//$materialinfo=$db->getRow("select * from wx_material where uid=".uid." and id=".$rs1["mid"]);	
   	global $db;
	$orinfo=$db->getOne("select id from wx_cy_addr where uid='".$uid."' and vipid=$vipid and isdefualt=1");
	return $orinfo;
}

//生成唯一订单号:微餐饮
function creat_cy_orderNumber_orders($id)
{
	//echo time()."<br/>";
	$fists=substr(time(),-3);
	$nexts=11249326436+$id;
	$ornumbers=$fists.$nexts;
	$sums=0;
	//echo $fists."<br/>";
	//echo $ornumbers."<br/>";
	for ($i = 1; $i <= strlen($ornumbers); $i++) {
		$rest=substr($ornumbers, $i-1,1);
		$sums=$sums+$rest;
		//echo "i:".$i."sums:".$sums."rest:".$rest."<br/>";
	}
	//echo $sums."<br/>";
	$lastnum=substr($sums,-1);
	$ornumbers=$ornumbers.$lastnum;
	
	return $ornumbers;
}


# 根据类型获取订单列表
function get_cy_Order_List($vipid,$uid,$pages='orderlist.php'){
	//$db = dblink();
	global $db;
	$data="";
	$sql="select * from wx_cy_order where vipid=$vipid and uid=$uid and paytypes<>0   ORDER BY id desc";
	
	$db->pagesize=10;
	$q=$db->execute($sql);
	while($r=$db->fetch_array($q)){	
		$data['data'][]=$r;
	}
	
	$data['counts']=$db->count;
	$data['maxpage']=$db->maxpage;
	if($pid!=0){
		$data['fenye']=$db->pageindex_mobile($pages."&page=%");
	}else{
		$data['fenye']=$db->pageindex_mobile($pages."&page=%");
	}
	
	return $data;
}

/**微餐饮结束**/


//根据手机号码查询用户最低的会员等级
function getFistGradeIdByUid($uid){ 
	//$materialinfo=$db->getRow("select * from wx_material where uid=".uid." and id=".$rs1["mid"]);	
   	global $db;
	$isHaveVip=$db->getRow("select id,Discount,levelTime,is_levelTime  from wx_memberGradeConfig where uid=".$uid." order by GradeOrder asc");
	return $isHaveVip;
}

//根据id查询新闻
function getNewsDeail($nid){
	global $db;
	$isHavenews=$db->getRow("select *  from wx_company_news where id=".$nid);
	return $isHavenews;
}

//用户办卡领取默认优惠劵：不判断优惠券起始结束时间
function vipGetCoupon_gz($uid,$vipid){
   	global $db;
	$rnum=mt_rand(1,10000);
	$nows=time();
	$CouponInfo=$db->getRow("select moneys,id,maxCounts,sumdays from wx_CouponInfo where uid=".$uid." and statustime<=$nows and endtime>=$nows and status=2 and types=1");

	if($CouponInfo){ //如果有启用的优惠券
		$ishaveCouponLog=$db->getOne("SELECT id FROM wx_CouponLog WHERE uid= '$uid' and CId=".$CouponInfo["id"]." and CVipId=".$vipid);
		
				$countcl=$db->getOne("SELECT count(id) FROM wx_CouponLog WHERE uid='$uid' and CId=".$CouponInfo['id']); //已领取数
				$mon=0;
				if($CouponInfo["maxCounts"]>=$countcl){
					$minfos=json_decode($CouponInfo['moneys']);
					foreach($minfos as $cos) //循环开始
					{
						if($rnum>=$cos->mins&&$rnum<=$cos->maxs){
							
							if($cos->nums!=0){
								$countcls=$db->getOne("SELECT count(id) FROM wx_CouponLog WHERE uid='$uid' and CId=".$CouponInfo['id']." and moneys=".$cos->moneys); //已领取数
								if($countcls>=$cos->nums){
									return vipGetCoupon_gz($uid,$vipid); //
								}
							}
							
							
							
							$mon=$cos->moneys;
							
						}else{
						
						}
					}
					
					$crid=0;
					while(!$crid)
					{
					  $keys=9999999999-$uid;
					  $ukey=$db->getOne("select keyss from wx_CouponInfo where id=".$CouponInfo["id"]);
					  $sums=0;
					  
					  
					  for ($i = 1; $i <= strlen($keys.$ukey); $i++) {
						$rest=substr($keys.$ukey, $i-1,1);
						$sums=$sums+$rest;
						//echo "i:".$i."sums:".$sums."rest:".$rest."<br/>";
					  }
					   //$lastnum=substr($sums,-1);
						$lastnum=$sums%7;
					   $carids=$ukey.$lastnum;
						$keys=$keys.$carids;
					  
					

					  
						$mebers= array(
							'CId'  => $CouponInfo["id"],										           
							'addtime'=> time(),	
							'uid'=> $uid,
							'CVipId'=> $vipid,
							'moneys'=> $mon,
							'keyss'=> $keys,
							'CNumber'=> $carids,
						);	
					   if($CouponInfo["sumdays"]!=0){
						   $today=strtotime("today");
						   $mebers["endtime"]=$today+86400*intval($CouponInfo["sumdays"]);
					   }
					   
					   $crid=$db->autoExecute('wx_CouponLog',$mebers,$mode='insert',$where='');
					   
					  // $cardId=creatorderNumber(3,$uid,$crid); //生成操作串号
					}
					
				  if($crid){
					 $db->execute("update wx_CouponInfo set keyss=keyss+1  where id=".$CouponInfo["id"]." and uid=".$uid);
					  $mebers["id"]=$crid;
					  return $mebers;
					  
				  }else{
					  return  "";
				  }
				
				  
			 }else{
					return  "";
			}
		
	}else{//没有启用的优惠券
			return  "";
	}


}

//根据下级人数及佣金金额判断用户所属服务商
function getDistributor($sbdNumer,$commission){
	
	if($sbdNumer>=27000 && $commission>=250000)	
	{
		return "省级服务商";
	}else if($sbdNumer>=900 && $commission>=55000)	
	{
		return "市级服务商";
	}else if($sbdNumer>=30 && $commission>=5500)	
	{
		return "县级服务商";
	}
}





/*每人分配金额
 *Parameter 总金额
 *
 **/
function dominatingamount(){
	//$db = dblink();
	global $db;
	$shopifo=$db->getRow("select * from wx_shop_config"); //商户信息
	$totalMoney=$shopifo["totalsales"];
	$jxs_tc1=0;
	$jxs_tc2=0;
	$jxs_tc3=0;
	 //省级服务商总数
	$sjcount=$db->getOne("select count(id) from wx_card_vip where fx_utype=2 and GradeId=13"); //省级服务商总数

	//市级服务商
	$shijcount=$db->getOne("select count(id) from wx_card_vip where fx_utype=2 and GradeId=12"); //市级服务商总数
	
	//县级服务商
	$xjcount=$db->getOne("select count(id) from wx_card_vip where fx_utype=2 and GradeId=11"); //县级服务商总数
	if($sjcount){
		$jxs_tc1=$totalMoney*($shopifo["jxs_tc1"]/100)/$sjcount;//省级服务商每人所得佣金
	}
	if($shijcount){
		$jxs_tc2=$totalMoney*($shopifo["jxs_tc2"]/100)/$shijcount;//市级服务商每人所得佣金
	}
	if($xjcount){
		$jxs_tc3=$totalMoney*($shopifo["jxs_tc3"]/100)/$xjcount;//县级服务商每人所得佣金
	}
	$sql2="select *  from wx_card_vip where fx_utype=2 and totalcommission>=".$shopifo["amount3"]." and  subordinate>=".$shopifo["sbdNumer3"];//所有县级以上服务商
	$q2=$db->execute($sql2);
	while($r2=$db->fetch_array($q2)){
		$gradeOrder=$db->getOne("select GradeOrder from wx_membergradeconfig where id=".$r2["GradeId"]); //商户信息
		//echo $jxs_tc2."";
		//所有县级服务商获得提成
		if($gradeOrder==1){
			$db->execute("update wx_card_vip set totalcommission=totalcommission+$jxs_tc3,commission=commission+$jxs_tc3 where id=".$r2["id"]);
		}else if($gradeOrder==2){//市级服务商
			$db->execute("update wx_card_vip set totalcommission=totalcommission+$jxs_tc2,commission=commission+$jxs_tc2 where id=".$r2["id"]);
			
		}else if($gradeOrder==3){//省级服务商
			$db->execute("update wx_card_vip set totalcommission=totalcommission+$jxs_tc1,commission=commission+$jxs_tc1 where id=".$r2["id"]);
		}
		
	}
	
	
	/*$sql="select *  from wx_card_vip where where fx_utype=2 and totalcommission>=".$shopifo["amount1"]."and subordinate>".$shopifo["sbdNumer1"];//所有省级服务商总数
	$q=$db->execute($sql);
	while($r=$db->fetch_array($q)){	
		//所有省级服务商获得提成
		$db->execute("update wx_card_vip set totalcommission=totalcommission+$jxs_tc1,commission=commission+$jxs_tc1 where id=".$r["id"]);
	}
	
	//市级服务商
	$shijcount=$db->getOne("select count(id) form wx_card_vip where fx_utype=2 and totalcommission>=".$shopifo["amount2"]."and totalcommission<".$shopifo["amount1"]."  and  subordinate>".$shopifo["sbdNumer2"]." and subordinate <".$shopifo["sbdNumer1"]); //市级服务商总数
	
	$sql1="select *  from wx_card_vip where where fx_utype=2 and totalcommission>=".$shopifo["amount2"]."and totalcommission<".$shopifo["amount1"]."  and  subordinate>".$shopifo["sbdNumer2"]." and subordinate <".$shopifo["sbdNumer1"];//所有市级服务商总数
	$q1=$db->execute($sql1);
	while($shi=$db->fetch_array($q1)){	
		//所有市级服务商获得提成
		$db->execute("update wx_card_vip set totalcommission=totalcommission+$jxs_tc2,commission=commission+$jxs_tc2 where id=".$shi["id"]);
	}
	
	//县级服务商
	$xjcount=$db->getOne("select count(id) form wx_card_vip where fx_utype=2 and totalcommission>=".$shopifo["amount3"]."and totalcommission<".$shopifo["amount2"]."  and  subordinate>".$shopifo["sbdNumer3"]." and subordinate <".$shopifo["sbdNumer2"]); //县级服务商总数
	
	$sql2="select *  from wx_card_vip where where fx_utype=2 and totalcommission>=".$shopifo["amount3"]."and totalcommission<".$shopifo["amount2"]."  and  subordinate>".$shopifo["sbdNumer3"]." and subordinate <".$shopifo["sbdNumer2"];//所有县级服务商总数
	$q2=$db->execute($sql2);
	while($r2=$db->fetch_array($q2)){	
		//所有县级服务商获得提成
		$db->execute("update wx_card_vip set totalcommission=totalcommission+$jxs_tc3,commission=commission+$jxs_tc3 where id=".$r2["id"]);
	}*/
}
//是否显示
function goods_is_show($goodId,$vipid){
    global $db;
    $vip_addr_id = $db->getOne("select id from wx_shop_vip_addr where vipid = $vipid and isdefualt = 1");
    $goodinfo=$db->getRow("select * from wx_shop_goods where id = $goodId");
    $stOrder = "select * from wx_shop_order where good_id like $goodId and status !=0 GROUP BY vip_addr_id";
    $resultcat=$db->execute($stOrder);
    while($row=$db->fetch_array($resultcat)){
        $newslist[]=$row;
    }
    $z = 0;
    foreach($newslist as $stOrder_result){
        if($z==0){
            $douhao='';
        }else{
            $douhao = ',';
        }
        $addrid = $addrid.$douhao.$stOrder_result['vip_addr_id'];
        $z++;
    }
    $addrList = "select id,vipid,location_x,location_y from wx_shop_vip_addr where id in ($addrid) and is_protect = 1";
    $addrresult=$db->execute($addrList);
    while($row=$db->fetch_array($addrresult)){
        $addrresultList[]=$row;
    }
    $dis_protect = $db->getOne("select dis_protect from wx_shop_config where id = 25");//保护距离

    $sj1 = $db->getRow("select location_y,location_x,vipid from wx_shop_vip_addr where id = $vip_addr_id");
    for($n=0;$n<count($addrresultList);$n++){
        $sj2 = $db->getRow("select location_y,location_x,vipid from wx_shop_vip_addr where id = ".$addrresultList[$n]['id']);
        $dis = getDistance($sj1['location_y'],$sj1['location_x'],$sj2['location_y'],$sj2['location_x']);
        if($dis>$dis_protect){
            $status = '';
        }else{
            if($sj1['vipid']==$sj2['vipid']){

            }else{
                $status = 'continue';
            }
        }
    }
    return $status;
}
function area_array($area){
    //$db = dblink();
    global $db;
    $data="";
    $sql="select pid,id from wx_shop_area where pid = $area";
    $q=$db->execute($sql);
    while($r=$db->fetch_array($q)){
        if(!$data){
            $data = $r['id'];
        }else{
            $data = $data.','.$r['id'];
        }
    }
    return $data;
}

function NewsListAll($pid,$limit=0){
	global $db;
	$data="";
	$sql="select * from wx_company_news where companyCat = $pid ";
	if($limit>0){
		$sql=$sql." limit $limit";
	}
	$q=$db->execute($sql);
	while($r=$db->fetch_array($q)){
		$data[]=$r;
	}
	return $data;
}
function shop_car_bus_group($vipid){
    global $db;
    $data="";
    $sql="select cuid,id from shop_car where vipid = ".$vipid." and mode = 'cart' group by cuid";
    $q=$db->execute($sql);
    while($r=$db->fetch_array($q)){
        $data[]=$r;
    }
    return $data;
}
function shop_car_bus_list($cuid,$vipid,$checked=0){
    global $db;
    $data="";
    $sql="select * from shop_car where cuid = ".$cuid." and vipid = ".$vipid." and mode = 'cart'";
    if($checked){
        $sql = $sql.' and checked = '.$checked;
    }
    $q=$db->execute($sql);
    while($r=$db->fetch_array($q)){
        $data[]=$r;
    }
    return $data;
}
function shop_car_bus_checked($cuid,$vipid){
    global $db;
    $sql="select count(*) from (select checked from shop_car where cuid = $cuid and vipid = ".$vipid." group by checked) as a";
    $count = $db->getOne($sql);
    $countC2 = $db->getOne("select count(id) from shop_car where cuid = $cuid and vipid = $vipid and checked = 2");
    if($count!=2 && $countC2==0){
        $result = 1;
    }else{
        $result = 2;
    }
    return $result;
}
function get_orderInfo($serial_number,$type){
    global $db;
    $data="";
    $sql="select * from wx_shop_order where $type = '".$serial_number."'";
    $q=$db->execute($sql);
    if($type=='serial_number_disposable'){
        while($r=$db->fetch_array($q)){
            $data[]=get_orderChildInfo($r['serial_number']);
        }
    }else{
        while($r=$db->fetch_array($q)){
            $data[]=$r;
        }
    }
    return $data;
}
function get_orderChildInfo($serial_number){
    global $db;
    $data="";
    $sql="select * from wx_shop_order_child where serial_number_main = '".$serial_number."'";
    $q=$db->execute($sql);

        while($r=$db->fetch_array($q)){
            $data=$r;

    }
    return $data;
}
function empty_shop_car($vipid){
    global $db;
    $db->query("delete from shop_car where vipid = $vipid and checked = 1");
}
function operation_recharge_type($vipid,$good_id,$time,$num){
    global $db;
    $recharge_type = $db->getRow("select * from recharge_good where good_id = $good_id");
    $goodInfo = $db->getRow("select * from wx_shop_goods where id = $good_id");
    $addInfo = array(
            'uid'=>$goodInfo['uid'],
            'cuid'=>$goodInfo['cuid'],
            'type_id'=>$recharge_type['type_id'],
            'vipid'=>$vipid,
            'svalue'=>$recharge_type['svalue']*$num,
            'changeTime'=>$time,
    );
    $existence = $db->getRow("select * from recharge_log where vipid = $vipid and type_id = ".$recharge_type['type_id']);
    if($existence){
        $addInfo['svalue'] = $addInfo['svalue']+$existence['svalue'];
        $db->update("recharge_log",$addInfo," id = ".$existence['id']);
    }else{
        $db->insert("recharge_log",$addInfo);
    }
}
function getInfo2($ida,$num){
//            $GLOBALS['num2'] =$GLOBALS['num2'] + 6
//            $GLOBALS['$first'] = 2;
    global $db;
    $numTotal = ceil($db->getOne("select count(id) from wx_pay_orders where vipid = $ida and status = 2"));
    $numN = $num*6;
//            echo $numN.' '.$numTotal;
//            if($numN>=$numTotal){
//                die();
//            }
    $jifenInfo = $db->getAll("select * from wx_pay_orders where vipid = ".$ida." and status = 2 order by id desc ");
    if (!empty($jifenInfo)){
        $html= "";
        foreach ($jifenInfo as $k) {
            $html .="<div class=\"navset cleaxfix\">"."<div class=\"col-xs-8 full\">";
            $html .="<p>充值".$k['paymoney']."元</p><span>".date('Y-m-d h:i:s',$k['addtime'])."</span>";
            $html .="<span>获得积分 ".$k['paymoney']."</span>"."</div><div class=\"col-xs-4 add\">+".intval($k['paymoney'])."积分</div></div>";
        }
    return $html;
    }else{
        return( "<div>暂无积分记录哦</div>");
    }
}
function getInfo5($ida,$num){
//            $GLOBALS['num2'] =$GLOBALS['num2'] + 6
//            $GLOBALS['$first'] = 2;
    global $db;
    $numTotal = ceil($db->getOne("select count(id) from wx_pay_orders where vipid = $ida "));
    $numN = $num*6;
//            echo $numN.' '.$numTotal;
//            if($numN>=$numTotal){
//                die();
//            }
    $jifenInfo = $db->getAll("select * from wx_pay_orders where vipid = ".$ida." order by id desc");
    if (!empty($jifenInfo)){
        $html= "";
        foreach ($jifenInfo as $k) {
            if ($k['paytypes'] == 3){
                $paytypes = "支付宝";
            }elseif ($k['paytypes'] == 4){
                $paytypes = "支付宝";
            }elseif ($k['paytypes'] == 6){
                $paytypes = "微支付";
            }elseif ($k['paytypes'] == 2){
                $paytypes = "货到付款";
            }else{
                $paytypes = "暂无";
            }
            if ($k['status'] == 1){
                $status = "未提交";
            }elseif ($k['status'] == 2){
                $status = "支付成功";
            }elseif ($k['status'] == 3){
                $status = "支付失败";
            }else{
                $status= "充值失败";
            }
            $html .="<div class=\"navset cleaxfix\">"."<div class=\"col-xs-8 full\">";
            $html .="<p>".$status."</p><span>".date('Y-m-d H:i:s',$k['addtime'])."</span>";
            $html .="<span>支付方式 ".$paytypes."</span><span>实际支付金额</span>"."</div><div class=\"col-xs-4 add\">".intval($k['paymoney'])."元</div></div>";
        }
        return $html;
    }else{
        return( "");
    }
}
function getInfo3($ida,$num){
//            $GLOBALS['num2'] =$GLOBALS['num2'] + 6
//            $GLOBALS['$first'] = 2;
    global $db;
    $numTotal = ceil($db->getOne("select count(id) from recharge_log where vipid = $ida "));
    $numN = $num*6;
//            echo $numN.' '.$numTotal;
//            if($numN>=$numTotal){
//                die();
//            }
    $jifenInfo = $db->getAll("select * from recharge_log where vipid = ".$ida." order by id desc");
    if (!empty($jifenInfo)){
        $html= "";
        foreach ($jifenInfo as $k) {
            if ($k['paytypes'] == 3){
                $paytypes = "支付宝";
            }elseif ($k['paytypes'] == 4){
                $paytypes = "支付宝";
            }elseif ($k['paytypes'] == 6){
                $paytypes = "微支付";
            }elseif ($k['paytypes'] == 2){
                $paytypes = "货到付款";
            }else{
                if ($k['isvip'] == 1 ){
                    $paytypes = "管理员操作";
                }else{
                    $paytypes = "充值";
                }
            }
            if ($k['status'] == 1){
                $status = "操作成功";
            }elseif ($k['status'] == 2){
                $status = "操作失败";
            }else{
                $status= "充值失败";
            }
            if ($k['type'] == 1){
                $jia = "+";
            }else{
                $jia = "-";
            }
            $html .="<div class=\"navset cleaxfix\">"."<div class=\"col-xs-8 full\">";
            $html .="<p>".$status."</p><span>".date('Y-m-d h:i:s',$k['changeTime'])."</span>";
            $html .="<span>".$paytypes."</span>"."</div><div class=\"col-xs-4 add\">".$jia.intval($k['svalue'])."元</div></div>";
        }
        return $html;
    }else{
        return( "<div>暂无记录</div>");
    }
}
function getInfo4($ids){
    global  $db;
    $jifenInfo = $db->getAll("select * from recharge_log where isvip =1 and sid = $ids order by id desc");
    if (!empty($jifenInfo)){

        $html= "";
        foreach ($jifenInfo as $k) {
            $uname  = $db->getOne("select uname from wx_card_vip where id = ".$k['vipid']);
            if ($k['status']==1){
                $types = "操作成功";
            }elseif ($k['status'] == 2){
                $types ="操作失败";
            }
            if ($k['type'] == 1){
                $jia = "+";
            }elseif ($k['type'] == 2){
                $jia = "-";
            }
            $html .="<div class=\"navset cleaxfix\">"."<div class=\"col-xs-8 full\">";
            $html .="<p>".$types."</p><span>".date('Y-m-d h:i:s',$k['changeTime'])."</span><div class=\"col - xs -  full\"><span>扣款会员： ".$uname."</span></div>";
            $html .="</div><div class=\"col-xs-4 add\">".$jia.intval($k['svalue'])."元</div>";
            $html .="</div>";
        }
        return $html;
    }else{
        return( "<div>暂无记录</div>");
    }
}
?>
