    <?php
    date_default_timezone_set(PRC);
    error_reporting(0);
    session_start();
    header("Cache-control: private");
    header("Content-Type: text/html; charset=utf-8");
    ini_set('display_errors', false);
    ini_set('html_errors', false);
    date_default_timezone_set('Asia/Shanghai');
    define ('ROOT_PATH', str_replace('config.inc.php', '',__FILE__));
    define ("db_name","niupazhizao");
    define ("db_username","niupazhizao");
    define ("db_password","niupazhizaoadmin20185456");
    define ("db_host","106.14.45.31");
    define ("cotimes",time()+315360000); //设置Cookie过期时间
    define ("FAHUOTIMES",864000); //订单发货后几天后自动确认,10天
    define ("AUTOQUXIAO",86400*5); //取消未付款订单,5天
    define ("hykey","f01076ce65ce1c928d17a1d16e3ae5e4");
    define ("BAI_DU_API_KEY","0E6f7c8cbd27a31c1fd0ebd7a08af780"); //百度地图key
    define ("UC_KEY","0193e2e8db1369c41b7f80c69927a77c");
    define ("JSZCURL","");
    define ("CONNAME","©");
    define ("IS_SHOW_JSZC","1"); //1 显示，0.不显示
    define ("JSZCNAME","");
    define ("APIHOST","http://www.npzz.topswr.com/");
    define ("APIHOSTS","www.npzz.topswr.com");
    define ("ZDYCOUNTS","4");//微预约自定义字段数量
    define ("KEFU","");//客服电话
    define ("ZHANZHANG","");//客服电话
    define ("sms_http","http://api.sms.cn/mtutf8/");
    define ("sms_uid","zhouwu");
    define ("sms_pwd","zzww920920");
    require_once('send.php'); //短信发送
    require_once('include/mysql.class.php');
    require_once('include/common.class.php');
    require_once('include/common.php');
    define ("image_path","");
    $timeconfig=array('10:00','11:00','12:00','13:00','14:00','15:00','16:00','17:00','18:00','19:00','20:00','21:00'); //化妆预约时间
    $uid=41;
    $userid=41;
    function dblink($p=0)
    {
        $objDB=new DBClass();
        $objDB->connect(db_name,db_username,db_password,'utf8',db_host,$p);
        return $objDB;
    }
    function dblink_file($p=0)
    {
        $objDB=new DBClass();
        $objDB->connect('weixinfile',db_username,db_password,'utf8',db_host,$p);
        return $objDB;
    }
    //end function
    $db = dblink();
    function UpdateCookie($outtimess,$wid='',$userids='',$uemails='',$ustatus='')
    {
        if($wid!=''&&$ustatus!=''){
            SetCookie("wid",$wid);
            SetCookie("userids",$userids);
            SetCookie("uemails",$uemails);
            SetCookie("ustatus",$ustatus);
            SetCookie("outtimess",$outtimess);
        }else if($wid==''&&$ustatus!=''){
            SetCookie("ustatus",$ustatus);
        }else{
            SetCookie("outtimess",$outtimess);
        }
    }

    //判断微信内置浏览器
    function is_weixin(){
        if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false ) {
            return true;
        }
        return false;
    }

    function getDistance_map($lat_a, $lng_a, $lat_b, $lng_b) {
        //R是地球半径（米）
        $R = 6366000;
        $pk = doubleval(180 / 3.14169);
        $a1 = doubleval($lat_a / $pk);
        $a2 = doubleval($lng_a / $pk);
        $b1 = doubleval($lat_b / $pk);
        $b2 = doubleval($lng_b / $pk);
        $t1 = doubleval(cos($a1) * cos($a2) * cos($b1) * cos($b2));
        $t2 = doubleval(cos($a1) * sin($a2) * cos($b1) * sin($b2));
        $t3 = doubleval(sin($a1) * sin($b1));
        $tt = doubleval(acos($t1 + $t2 + $t3));
        return round($R * $tt);
    }


    /**
     * Curl
     * 使用方法
     * $post_string = "app=request&version=beta";
     * request_by_curl('http://facebook.cn/restServer.php',$post_string);
     */
    function request_note_by_curl($remote_server, $post_string='')
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $remote_server);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, "aoyakeji");
        $result = curl_exec($ch);
    //	curl_exec($ch);
        $result = curl_multi_getcontent($ch);
        curl_close($ch);
        return $result;
    }
    /**
     * 短息发送
     * CORPID：用户名
     * CPPW：密码
     * PHONE 电话如 13397085211 或 13397085213,13397085245
     * CONTENT 内容
     * $post_string = "app=request&version=beta";
     * request_by_curl('http://facebook.cn/restServer.php',$post_string);
     */
    function sendNote($CORPID,$CPPW,$PHONE,$CONTENT)
    {
        $res_str=request_note_by_curl("http://16sms.ganqi.net/send.asp","CORPID=aoyakeji&CPPW=69210f290e498cfb8d541be987b5e4ce&PHONE=".$PHONE."&CONTENT=".$CONTENT);
        return $res_str;
    }
    function checkCookie()
    {
    //die(authcode($_COOKIE['uemails']));
        if($_COOKIE['ustatus']!="" && $_COOKIE['uemails']!="" && $_COOKIE['ustatus']!="" && $_COOKIE['dietimes']!="" ){
            $userid=str_filter(authcode($_COOKIE['userids']));
            $nowuserids=str_filter(authcode($_COOKIE['nowuserids']));
            $uemails=str_filter(authcode($_COOKIE['uemails']));
            $ustatus=str_filter(authcode($_COOKIE['ustatus']));
            $dietimes=str_filter(authcode($_COOKIE['dietimes']));
            $wid=str_filter(authcode($_COOKIE['wid']));
            $userspriv=str_filter(authcode($_COOKIE['userspriv']));
            $userscatpriv=str_filter(authcode($_COOKIE['userscatpriv']));
            if($ustatus!=2){
                SetCookie("userids","",time()-3600);
                SetCookie("nowuserids","",time()-3600);
                SetCookie("uemails","",time()-3600);
                SetCookie("ustatus","",time()-3600);
                SetCookie("wid","",time()-3600);
                SetCookie("dietimes","",time()-3600);
                SetCookie("userspriv","",time()-3600);
                SetCookie("userscatpriv","",time()-3600);
                $info = array(
                        'loingstatus'=>-1
                );
                return $info;
            }
    //		$outimes=strval(time()+10);
    //		setcookie("dietimes",authcode($outimes,'ENCODE'));
            //$dietimes=str_filter(authcode($_COOKIE['dietimes']));
            $info = array(
                    'loingstatus'  =>1,
                    'userids'  =>$userid,
                    'nowuserids'  =>$nowuserids,
                    'uemails'=>$uemails,
                    'ustatus'=>$ustatus,
                    'wid'=>$wid,
                    'userspriv'=>$userspriv,
                    'userscatpriv'=>$userscatpriv,
                    'dietimes'=>$dietimes
            );
    //echo $dietimes.":".time();
            if($dietimes>time()){
                //if($outimes>time()){
                $outimes=strval(time()+2400);

                setcookie("dietimes",authcode($outimes,'ENCODE'),time()+8640000,"/");
                $_COOKIE['dietimes']=authcode($outimes,'ENCODE');
                return $info;
            }else{
                // die("sssssssssss".$dietimes);
                SetCookie("userids","",time()-3600);
                SetCookie("nowuserids","",time()-3600);
                SetCookie("uemails","",time()-3600);
                SetCookie("ustatus","",time()-3600);
                SetCookie("wid","",time()-3600);
                SetCookie("dietimes","",time()-3600);
                SetCookie("userspriv","",time()-3600);
                SetCookie("userscatpriv","",time()-3600);
                $info= array(
                        'loingstatus'=>-1
                );
                return $info;
            }
        }else{
            //die("ddddddd".$dietimes);
            SetCookie("userids","",time()-3600);
            SetCookie("nowuserids","",time()-3600);
            SetCookie("uemails","",time()-3600);
            SetCookie("ustatus","",time()-3600);
            SetCookie("wid","",time()-3600);
            SetCookie("dietimes","",time()-3600);
            SetCookie("userspriv","",time()-3600);
            SetCookie("userscatpriv","",time()-3600);
            $info = array(
                    'loingstatus'=>-1
            );
            return $info;
        }
    }

    function checkCookie_fws()
    {
        //die(authcode($_COOKIE['uemails']));
        if($_COOKIE['nowuserids']!="" && $_COOKIE['uemails']!="" && $_COOKIE['ustatus']!="" && $_COOKIE['dietimes']!="" ){
            $nowuserids=str_filter(authcode($_COOKIE['nowuserids']));
            $uemails=str_filter(authcode($_COOKIE['uemails']));
            $ustatus=str_filter(authcode($_COOKIE['ustatus']));
            $dietimes=str_filter(authcode($_COOKIE['dietimes']));
            $wid=str_filter(authcode($_COOKIE['wid']));
            $userspriv=str_filter(authcode($_COOKIE['userspriv']));
            $userscatpriv=str_filter(authcode($_COOKIE['userscatpriv']));
//            if($ustatus!=2){
//                SetCookie("nowuserids","",time()-3600);
//                SetCookie("uemails","",time()-3600);
//                SetCookie("ustatus","",time()-3600);
//                SetCookie("wid","",time()-3600);
//                SetCookie("dietimes","",time()-3600);
//                SetCookie("userspriv","",time()-3600);
//                SetCookie("userscatpriv","",time()-3600);
//                $info = array(
//                    'loingstatus'=>-1
//                );
//                return $info;
//            }
            //		$outimes=strval(time()+10);
            //		setcookie("dietimes",authcode($outimes,'ENCODE'));
            //$dietimes=str_filter(authcode($_COOKIE['dietimes']));
            $info = array(
                'loingstatus'  =>1,
                'userids'  =>$userids,
                'nowuserids'  =>$nowuserids,
                'uemails'=>$uemails,
                'ustatus'=>$ustatus,
                'wid'=>$wid,
                'userspriv'=>$userspriv,
                'userscatpriv'=>$userscatpriv,
                'dietimes'=>$dietimes
            );
            //echo $dietimes.":".time();
            if($dietimes>time()){
                //if($outimes>time()){
                $outimes=strval(time()+2400);
                setcookie("dietimes",authcode($outimes,'ENCODE'),time()+8640000,"/");
                $_COOKIE['dietimes']=authcode($outimes,'ENCODE');
                return $info;
            }else{
                // die("sssssssssss".$dietimes);
                SetCookie("nowuserids","",time()-3600);
                SetCookie("uemails","",time()-3600);
                SetCookie("ustatus","",time()-3600);
                SetCookie("wid","",time()-3600);
                SetCookie("dietimes","",time()-3600);
                SetCookie("userspriv","",time()-3600);
                SetCookie("userscatpriv","",time()-3600);
                $info= array(
                    'loingstatus'=>-1
                );
                return $info;
            }
        }else{
            //die("ddddddd".$dietimes);
            SetCookie("nowuserids","",time()-3600);
            SetCookie("uemails","",time()-3600);
            SetCookie("ustatus","",time()-3600);
            SetCookie("wid","",time()-3600);
            SetCookie("dietimes","",time()-3600);
            SetCookie("userspriv","",time()-3600);
            SetCookie("userscatpriv","",time()-3600);
            $info = array(
                'loingstatus'=>-1
            );
            return $info;
        }
    }
    
    function checkCookieOnIndex()
    {
    //die(authcode($_COOKIE['uemails']));
        if($_COOKIE['ustatus']!="" && $_COOKIE['uemails']!="" && $_COOKIE['ustatus']!="" && $_COOKIE['dietimes']!="" ){
            $userid=str_filter(authcode($_COOKIE['userids']));
            $uemails=str_filter(authcode($_COOKIE['uemails']));
            $ustatus=str_filter(authcode($_COOKIE['ustatus']));
            $wid=str_filter(authcode($_COOKIE['wid']));
            $dietimes=str_filter(authcode($_COOKIE['dietimes']));
            $userscatpriv=str_filter(authcode($_COOKIE['userscatpriv']));
    //		$outimes=strval(time()+10);
    // 		setcookie("dietimes",authcode($outimes,'ENCODE'));
            //$dietimes=str_filter(authcode($_COOKIE['dietimes']));
            $info = array(
                    'loingstatus'  =>1,
                    'userids'  =>$userid,
                    'uemails'=>$uemails,
                    'ustatus'=>$ustatus,
                    'wid'=>$wid,
                    'userspriv'=>$userspriv,
                    'userscatpriv'=>$userscatpriv,
                    'dietimes'=>$dietimes
            );
            // if($outimes>time()){
            if($dietimes>time()){
                $outimes=strval(time()+2400);
                //setcookie("dietimes",authcode($outimes,'ENCODE'));
                setcookie("dietimes",authcode($outimes,'ENCODE'),time()+8640000,"/");
                return $info;
            }else{
                SetCookie("userids","",time()-3600);
                SetCookie("uemails","",time()-3600);
                SetCookie("ustatus","",time()-3600);
                SetCookie("wid","",time()-3600);
                SetCookie("dietimes","",time()-3600);
                SetCookie("userspriv","",time()-3600);
                SetCookie("userscatpriv","",time()-3600);
                $info= array(
                        'loingstatus'=>-1
                );
                return $info;
            }
        }else{
            SetCookie("userids","",time()-3600);
            SetCookie("uemails","",time()-3600);
            SetCookie("ustatus","",time()-3600);
            SetCookie("wid","",time()-3600);
            SetCookie("dietimes","",time()-3600);
            SetCookie("userspriv","",time()-3600);
            SetCookie("userscatpriv","",time()-3600);
            $info = array(
                    'loingstatus'=>-1
            );

            return $info;
        }
    }
    /*if (session_is_registered('cc_lasttime')){
       $cc_lasttime = $_SESSION['cc_lasttime'];
       $cc_times = $_SESSION['cc_times'] + 1;
       $_SESSION['cc_times'] = $cc_times;
    }else{
       $cc_lasttime = $TIME;
       $cc_times = 1;
       $_SESSION['cc_times'] = $cc_times;
       $_SESSION['cc_lasttime'] = $cc_lasttime;
    }

    if(($TIME - $cc_lasttime)<5){
       if ($cc_times>=10){
           die('请不要重复刷新');
           exit;
     }
    }else{
       $_SESSION['cc_lasttime'] = $TIME;
       $_SESSION['cc_times'] = 0;
    }*/

    //$H_B = new DBData();
    //$H_B->db=$db;
    //设置cookie，防止刷新


    ///过滤，防注入
    //int_filter(array('id','page','cat_id','user_id'));
    //
    //function int_filter($arr){
    //	foreach($arr as $v){
    //		if(isset($_REQUEST[$v]))
    //			$_REQUEST[$v]=int_val($_REQUEST[$v]);
    //		if(isset($_POST[$v]))
    //			$_POST[$v]=int_val($_POST[$v]);
    //		if(isset($_GET[$v]))
    //			$_GET[$v]=int_val($_GET[$v]);
    //	}
    //}
    function request_by_curl($remote_server, $post_string='')
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $remote_server);
        curl_setopt($ch,CURLOPT_CUSTOMREQUEST, "POST");
        // curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, "JXHB");
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }
    function dead($str='',$url=''){
        if(!$url)
            $url=isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:'';
        if(!$str)
            $str='对不起，参数异常！';

        $ss='<p align="center">'.$str.'</p>
            <p align="center">正在跳转至来源页...</p>
            <script type="text/javascript">setTimeOut(\'window.history.go(-1);\',3000);</script>';
        if($url){
            echo $ss;
        }else{
            die($ss);
            $url=$url?$url:'/';
        }


        die('<p align="center">如果不能正常跳转，请点击 <a href="'.$url.'">跳转</a> ！</p>
        <meta HTTP-EQUIV=refresh Content="3;url='.$url.'">
        <script type="text/javascript">setTimeOut(\'window.location.href="'.$url.'";\',3000);</script>');
    }
    //验证后台用户是否登录
    /*function checkUserIsLogin_new(){
        if(isset($_COOKIE['userids']) and isset($_COOKIE['uemails']) and isset($_COOKIE['ustatus']) and isset($_COOKIE['wid'])){
            $userid=str_filter(rc4($_COOKIE['userids']));
            $uemails=str_filter(rc4($_COOKIE['uemails']));
            $ustatus=str_filter(rc4($_COOKIE['ustatus']));
            $wid=str_filter(rc4($_COOKIE['wid']));

            if(str_filter(rc4($_COOKIE['userids']))=="" || str_filter(rc4($_COOKIE['uemails']))=="" || str_filter(rc4($_COOKIE['ustatus']))==""){
                     SetCookie("userids","",cotimes);
                     SetCookie("uemails","",cotimes);
                     SetCookie("ustatus","",cotimes);
                     SetCookie("wid","",cotimes);
                    C::jump('抱歉,您还没有登陆或身份过期，请重新登陆！','/index.php');

            }

            if($ustatus==1){ //没有设置网站的用户
                C::jump('抱歉,您还没有登陆或身份过期，请重新登陆!','/index.php');
            }
            if($ustatus==5){ //未完善信息的
                C::jump('抱歉,您还没有完善信息或还未通过审核!','/completeInfo.php');
            }


            $sql="SELECT id FROM wx_users WHERE id=$userid and  uemail='$uemails' AND status=$ustatus and  is_del=0 and status!=5";
            //die($sql);
            $is_reg = $db->getOne($sql);
            if(!$is_reg){
                     SetCookie("userids","",cotimes);
                     SetCookie("uemails","",cotimes);
                     SetCookie("ustatus","",cotimes);
                     SetCookie("wid","",cotimes);
                C::jump('抱歉,您还没有登陆或身份过期，请重新登陆！','/index.php');
            }
        }else{

            C::jump('抱歉,您还没有登陆或身份过期，请重新登陆！','/index.php');
        }

    }*/
    function int_val($str){
        if(is_array($str)) return $str;
        if(!preg_match("/^[0-9]{0,7}$/",$str)){
            dead();
        }
        if($str==''||$str==null){
            dead();
        }
        return intval($str);
    }

    function click_count($type,$id){
        $ip=realIp();
        global $db;
        //$db = dblink();
        $time=time();
        $nexttime=$time-24*60*60;
        $db->query("delete from ganqi_click where time<$nexttime");
        $num=$db->getOne("select count(*) from ganqi_click where ip='$ip' and type='$type' and id=$id and time>$nexttime and time<$time");
        if($num==0){
            switch($type){
                case 'news':
                    $db->query("update ganqi_news set hits=hits+1 where id=$id");
                    break;
                case 'company':
                    $db->query("update ganqi_member_company set hits=hits+1 where id=$id");
                    break;
                case 'product':
                    $db->query("update ganqi_product set hits=hits+1 where id=$id");
                    break;
            }
            $db->insert("ganqi_click",array('time'=>$time,'ip'=>$ip,'type'=>$type,'id'=>$id));
        }
    }

    //生成二维码的方法
    function createErWeiMaImg($datas,$filenames){
        require_once('phpqrcode/phpqrcode.php');
        // 二维码数据
        $data = $datas;
        // 生成的文件名
        //$filename = "Images/".time().$meberurl.".png";
        $filename=$filenames;
        //$filename = '1.png';
        // 纠错级别：L、M、Q、H
        $errorCorrectionLevel = 'L';
        // 点的大小：1到10
        $matrixPointSize = 6;
        QRcode::png($data, $filename, $errorCorrectionLevel, $matrixPointSize, 2);

    }
    //生成二维码的方法
    function createErWeiMaImgToDatabase($datas,$filenames){
    //die($datas);


        $my_file = file_get_contents("http://www.1wwz.com/showewm.php?urlsinfo=".$datas);
        $dbfile = dblink_file();
        //$filenames=time().strval(mt_rand(100, 999)).".png";
        //'/UpLoad/erweima/'.$filenames
        $mebersfile= array(
                'fileKey' =>md5($filenames),
                'fileUrl' =>$filenames,
                'addtime'=> time(),
                'fileContent'=>$my_file,
                'fileType'=>"png"
        );
        $fileid=$dbfile->autoExecute('wx_imgFiles',$mebersfile,$mode='insert',$where='');
        return $fileid;
    }
    function getuid(){
        global $db;
        $host=getHostName_new();#当前域名
        $uids=$db->getOne("select uid from wx_company_domain where domain='$host'");
        if($uids==""){ //用户绑定了域名
            $url=getDomain();  //企业域名
            $urlarr=explode(".",$url);
            if(!stristr($urlarr[0],"v")){ //连接里不包含V
                $uids =0;
            }else{
                $aryName1=explode('v',$urlarr[0]); //获取企业i
                $url=$aryName1[1];  //获取到的用户名
                $uids = str_filter($url);

            }
        }

        return $uids;
    }
    function havekeywords($content=''){
        global $db;
        $kerr=$db->getAll("select * from ganqi_keywords where status=1 order by ordor desc");
        $strings='';
        foreach($kerr as $row){
            $k=$row['name'];
            $k=preg_quote($k,'/');
            if(preg_match('/'.$k.'/',$content)){
                $strings.=' '.$row['name'];
            }
        }
        return $strings;
    }
    function Createapiurl($id){
        return "http://wx".$id.apihost."/API/weixin.php";
    }
    //判断员工在职状态
    function getzaizhitype($status,$zdystatus){
        if($status==1){
            return '在职';
        }else if($status==2){ //已启用
            return '离职';
        }else if($status==3){ //已启用
            return $zdystatus;
        }else{
            return "<span style='color:#F00'>异常</span>";
        }
    }
    /*
    * rc4加密算法
    * pwd密钥
    * data要加密的数据
    */
    /*function rc4($data)//$pwd密钥　$data需加密字符串
    {
    $pwd="0193e2e8db1369c41b7f80c69927a77c";
    $key[]="";
    $box[]="";
    $cipher="";
    $pwd_length=strlen($pwd);
    $data_length=strlen($data);
    for($i=0;$i<256;$i++)
    {
    $key[$i]=ord($pwd[$i%$pwd_length]);
    $box[$i]=$i;
    }
    for($j=$i=0;$i<256;$i++)
    {
    $j=($j+$box[$i]+$key[$i])%256;
    $tmp=$box[$i];
    $box[$i]=$box[$j];
    $box[$j]=$tmp;
    }
    for($a=$j=$i=0;$i<$data_length;$i++)
    {
    $a=($a+1)%256;
    $j=($j+$box[$a])%256;
    $tmp=$box[$a];
    $box[$a]=$box[$j];
    $box[$j]=$tmp;
    $k=$box[(($box[$a]+$box[$j])%256)];
    $cipher.=chr(ord($data[$i])^$k);
    }
    return $cipher;
    }

    */
    function create_str( $length = 8 ) {   // 密码字符集，可任意添加你需要的字符
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $str ='';
        for ( $i = 0; $i < $length; $i++ )
        {
            // 这里提供两种字符获取方式
            // 第一种是使用 substr 截取$chars中的任意一位字符；
            // 第二种是取字符数组 $chars 的任意元素   //
            // $password.=substr($chars, mt_rand(0,strlen($chars) –1),1);
            $str .= $chars[ mt_rand(0, strlen($chars) - 1) ];
        }
        return $str;
    }

    /*
    * 指定两个时间段，返回不同的时间数
    * $interval：只允许intervals有以下五个值："w"(周)、"d"（天）、"h"（小时）、"n"（分钟） 和"s"（秒）
    * $date1 通常为当前时间；
    * $date2 需要计算的时间；
    */
    function DateDiff ($interval = "d", $date1,$date2) {
        // 得到两日期之间间隔的秒数
        $timedifference = strtotime($date2) - strtotime($date1);
        switch ($interval) {
            case "w": $retval = bcdiv($timedifference, 604800); break;
            case "d": $retval = bcdiv($timedifference, 86400); break;
            case "h": $retval = bcdiv($timedifference, 3600); break;
            case "n": $retval = bcdiv($timedifference, 60); break;
            case "s": $retval = $timedifference; break;
        }
        return $retval;
    }

    //RC4加密/解密函数
    function authcode($string, $operation = 'DECODE', $key = '0193e2e8db1369c41b7f80c69927a77c', $expiry = 0)
    {
        $ckey_length = 4;
        $key = md5($key ? $key : UC_KEY);
        $keya = md5(substr($key, 0, 16));
        $keyb = md5(substr($key, 16, 16));
        $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';

        $cryptkey = $keya.md5($keya.$keyc);
        $key_length = strlen($cryptkey);

        $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
        $string_length = strlen($string);

        $result = '';
        $box = range(0, 255);

        $rndkey = array();
        for($i = 0; $i <= 255; $i++)
        {
            $rndkey[$i] = ord($cryptkey[$i % $key_length]);
        }

        for($j = $i = 0; $i < 256; $i++)
        {
            $j = ($j + $box[$i] + $rndkey[$i]) % 256;
            $tmp = $box[$i];
            $box[$i] = $box[$j];
            $box[$j] = $tmp;
        }

        for($a = $j = $i = 0; $i < $string_length; $i++)
        {
            $a = ($a + 1) % 256;
            $j = ($j + $box[$a]) % 256;
            $tmp = $box[$a];
            $box[$a] = $box[$j];
            $box[$j] = $tmp;
            $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
        }

        if($operation == 'DECODE')
        {
            if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16))
            {
                return substr($result, 26);
            }
            else
            {
                return '';
            }
        }
        else
        {
            return $keyc.str_replace('=', '', base64_encode($result));
        }
    }//end function


    function get000($time){

        return $time-date('O')*36-$time%86400;

    }


    /**
     * Curl 发送短信
     * 使用方法
     * $post_string = "app=request&version=beta";
     * request_by_curl('http://facebook.cn/restServer.php',$post_string);
     */
    function request_note_by_curl2($remote_server, $post_string)
    {
        $ch = curl_init();
        /*	$post_string = mb_convert_encoding($post_string, "gb2312", "utf-8");
            $this_header = array(
            "content-type: application/x-www-form-urlencoded;charset=gb2312"
            );*/
        curl_setopt($ch,CURLOPT_HTTPHEADER,$this_header);

        curl_setopt($ch, CURLOPT_URL, $remote_server);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, "aoyakeji");
        $result = curl_exec($ch);
    //	curl_exec($ch);
        $result = curl_multi_getcontent($ch);
        curl_close($ch);
        return $result;
    }

    //发送短信
    function dosendsms($uid,$tophone,$contentss)
    {
        global $db;
        $noinfoss= array(
                'uid'  => $uid,
                'addtime'=> time(),
                'jsphone'=> $tophone,
                'ncontent'=> $contentss
        );
        $dxid=$db->autoExecute('wx_noteLog',$noinfoss,$mode='insert',$where='');
        if($dxid){

            //$CORPID=authcode('aoyakeji','ENCODE');
            //$CPPW=authcode("b664144924cf27bc15a716f5c292795a",'ENCODE');


            //$phone=authcode($tophone,'ENCODE');
            //$mcontents=authcode($contentss,'ENCODE');
            //$mcontents=$contentss;
            //echo authcode($mcontents,'ENCODE');
            //$res_str=@request_note_by_curl2("http://16sms.ganqi.net/send.asp","CORPID=$CORPID&CPPW=$CPPW&PHONE=$phone&CONTENT=$mcontents");

            $res_str=@request_note_by_curl2("http://sms.1wwz.com/smsServer/submit?CORPID=aoyakeji&CPPW=b664144924cf27bc15a716f5c292795a&PHONE=$tophone&CONTENT=$contentss");

            //die("http://16sms.ganqi.net/send.asp?CORPID=$CORPID&CPPW=$CPPW&PHONE=$phone&CONTENT=$mcontents");
            if(trim($res_str)=="SUCCESS"){ //发送成功
                $db->execute("update wx_users set dxcount=dxcount-1  where id=".$uid); //减少一条
                $db->execute("update wx_noteLog set status=2,results='$res_str'  where uid=".$uid." and id=".$dxid); //发送成功
                return true;
            }else{
                $db->execute("update wx_noteLog set results='$res_str'  where uid=".$uid." and id=".$dxid); //发送失败
                return false;
            }
        }

    }


    function showprompt($titles='操作成功',$tourl='')
    {
        if($tourl==''){
            echo '<script>showprompt("'.$titles.'","index.php");</script>';
        }else{
            echo '<script>showprompt("'.$titles.'","'.$tourl.'");</script>';
        }
        die();
    }


    /**************************************************************
     *
     *    使用特定function对数组中所有元素做处理
     *    @param  string  &$array     要处理的字符串
     *    @param  string  $function   要执行的函数
     *    @return boolean $apply_to_keys_also     是否也应用到key上
     *    @access public
     *
     *************************************************************/
    function arrayRecursive(&$array, $function, $apply_to_keys_also = false)
    {
        static $recursive_counter = 0;
        if (++$recursive_counter > 1000) {
            die('possible deep recursion attack');
        }
        if (is_array($array)) {
            foreach ($array as $key => $value) {
                if (is_array($value)) {
                    arrayRecursive($array[$key], $function, $apply_to_keys_also);
                } else {
                    $array[$key] = $function($value);
                }

                if ($apply_to_keys_also && is_string($key)) {
                    $new_key = $function($key);
                    if ($new_key != $key) {
                        $array[$new_key] = $array[$key];
                        unset($array[$key]);
                    }
                }
            }
        }
        $recursive_counter--;
    }

    /**************************************************************
     *
     *    将数组转换为JSON字符串（兼容中文）
     *    @param  array   $array      要转换的数组
     *    @return string      转换得到的json字符串
     *    @access public
     *
     *************************************************************/
    function JSON($array) {
        arrayRecursive($array, 'urlencode', true);
        $json = json_encode($array);
        return urldecode($json);
    }



    //中文字符编码转换
    function safeEncoding($string,$outEncoding ='GB2312')
    {
        $encoding = "UTF-8";
        for($i=0;$i<strlen($string);$i++)
        {
            if(ord($string{$i})<128)
                continue;

            if((ord($string{$i})&224)==224)
            {
                //第一个字节判断通过
                $char = $string{++$i};
                if((ord($char)&128)==128)
                {
                    //第二个字节判断通过
                    $char = $string{++$i};
                    if((ord($char)&128)==128)
                    {
                        $encoding = "UTF-8";
                        break;
                    }
                }
            }

            if((ord($string{$i})&192)==192)
            {
                //第一个字节判断通过
                $char = $string{++$i};
                if((ord($char)&128)==128)
                {
                    // 第二个字节判断通过
                    $encoding = "GB2312";
                    break;
                }
            }
        }

        if(strtoupper($encoding) == strtoupper($outEncoding))
            return $string;
        else
            return iconv($encoding,$outEncoding,$string);
    }


    //获取订单状态
    function getOrderStatusByStatus($status,$paytypes=0){
        $data="";
        if($paytypes==1 or $paytypes==4 or $paytypes==6 or $paytypes==7){ //微支付
            switch($status){
                case"0":
                    $data='<button style="border-radius: 5px; background-color:grey; color: #ffFFFF; border: 0px; font-size: 10px; margin-bottom: 2px;">未支付订单</button>';
                    break;
                case"1":
                    $data='<button style="border-radius: 5px; background-color: #1ab394; color: #ffFFFF; border: 0px; font-size: 10px; margin-bottom: 2px;">待收货</button>';
                    break;
                case'2':
                    $data='<button style="border-radius: 5px; background-color: #1ab394; color: #ffFFFF; border: 0px; font-size: 10px; margin-bottom: 2px;">待收货</button>';
                    break;
                case'3':
                    $data='<button style="border-radius: 5px; background-color: red; color: #ffFFFF; border: 0px; font-size: 10px; margin-bottom: 2px;">支付失败</button>';
                    break;
                case'4':
                    $data='<button style="border-radius: 5px; background-color: red; color: #ffFFFF; border: 0px; font-size: 10px; margin-bottom: 2px;">已取消</button>';
                    break;
                case'5':
                    $data="<button style=\"border-radius: 5px; background-color: red; color: #ffFFFF; border: 0px; font-size: 10px; margin-bottom: 2px;\">退款申请中</button>";
                    break;
                case'6':
                    $data="<button style=\"border-radius: 5px; background-color: red; color: #ffFFFF; border: 0px; font-size: 10px; margin-bottom: 2px;\">换货申请中</button>";
                    break;
                case'7':
                    $data="<button style=\"border-radius: 5px; background-color: red; color: #ffFFFF; border: 0px; font-size: 10px; margin-bottom: 2px;\">已退款</button>";
                    break;
                case'9':
                    $data="<button style=\"border-radius: 5px; background-color: green; color: #ffFFFF; border: 0px; font-size: 10px; margin-bottom: 2px;\">已确认收货</button>";
                    break;
                case'10':
                    $data='<button style="border-radius: 5px; background-color: red; color: #ffFFFF; border: 0px; font-size: 10px; margin-bottom: 2px;">部分退款</button>';
                    break;
                case'11':
                    $data='<button style="border-radius: 5px; background-color: red; color: #ffFFFF; border: 0px; font-size: 10px; margin-bottom: 2px;">已部分退款</button>';
                    break;
                default:
                    $data="<button style=\"border-radius: 5px; background-color: red; color: #ffFFFF; border: 0px; font-size: 10px; margin-bottom: 2px;\">异常</button>";
                    break;
            }
        }else if($paytypes==2){ //货到付款
            switch($status){
                case"1":
                    $data="<em class='o_d_sign'>待发货</em>";
                    break;
                case'2':
                    $data="<em style='color:#24aa2f'>已发货,待收货</em>";
                    break;
                case'4':
                    $data="<em style='color:#FF0000'>已取消</em>";
                    break;
                case'5':
                    $data="<em style='color:#FF0000'>退款申请中</em>";
                    break;
                case'7':
                    $data="<em style='color:#FF0000'>已退款</em>";
                    break;
                case'9':
                    $data="<em style='color:#24aa2f'>已确认收货</em>";
                    break;
                default:
                    $data="<em style='color:#FF0000'>异常</em>";
                    break;
            }
        }else if($paytypes==5){ //到店付款
            switch($status){
                case"1":
                    $data="<em class='o_d_sign'>待提货</em>";
                    break;
                case'9':
                    $data="<em style='color:#24aa2f'>已提货</em>";
                    break;
                case'4':
                    $data="<em style='color:#FF0000'>已取消</em>";
                    break;
                case'5':
                    $data="<em style='color:#FF0000'>退款申请中</em>";
                    break;
                case'7':
                    $data="<em style='color:#FF0000'>已退款</em>";
                    break;
                default:
                    $data="<em style='color:#FF0000'>异常</em>";
                    break;
            }
        }else{
            $data="";
        }


        return $data;
    }

    //获取订单状态
    function getPayTypesByType($paytypes){
        $data="";

        switch($paytypes){
            case"1":
                $data="<button style=\"border-radius: 5px; background-color: #1ab394; color: #ffFFFF; border: 0px; font-size: 10px; margin-bottom: 2px;\">积分支付</button>";
                break;
            case"2":
                $data="货到付款";
                break;
            case"3":
                $data="翼支付";
                break;
            case"4":
                $data="优惠券全额抵扣";
                break;
            case"5":
                $data="门店自提";
                break;
            case"6":
                $data="<button style=\"border-radius: 5px; background-color: #1ab394; color: #ffFFFF; border: 0px; font-size: 10px; margin-bottom: 2px;\">微支付</button>";
                break;
            case"7":
                $data="<button style=\"border-radius: 5px; background-color: #ed5565; color: #ffFFFF; border: 0px; font-size: 10px; margin-bottom: 2px;\">余额支付</button>";
                break;
            default:
                $data="<button style=\"border-radius: 5px; background-color: red; color: #ffFFFF; border: 0px; font-size: 10px; margin-bottom: 2px;\">异常</button>";
                break;
        }

        return $data;
    }


    //是否为分销商
    function isfxs($paytypes){
        $data="";

        switch($paytypes){
            case"1":
                $data="普通用户";
                break;
            case"2":
                $data="厂家";
                break;
            default:
                $data="<em style='color:#FF0000'>异常</em>";
                break;
        }

        return $data;
    }

    function fx_utype($paytypes){
        $data="";

        switch($paytypes){
            case"1":
                $data="普通用户";
                break;
            case"2":
                $data="<em style='color:#0058ff'>代理商</em>";
                break;
            case"3":
                $data="<em style='color:#29f806'>厂家</em>";
                break;
            default:
                $data="<em style='color:#FF0000'>异常</em>";
                break;
        }

        return $data;
    }

    //获取订单状态 酒店商城模板
    function getOrderStatusByStatus_hotel($status,$paytypes=0){
        $data="";
        if($paytypes==1 or $paytypes==3 or $paytypes==6){ //微支付
            switch($status){
                case"0":
                    $data="<em class='o_d_sign'>未支付订单</em>";
                    break;
                case"1":
                    $data="<em style='color:#00F'>已支付,待使用</em>";
                    break;
                case'2':
                    $data="<em style='color:#24aa2f'>已使用</em>";
                    break;
                case'3':
                    $data="<em style='color:#FF0000'>支付失败</em>";
                    break;
                case'4':
                    $data="<em style='color:#FF0000'>已取消</em>";
                    break;
                case'5':
                    $data="<em style='color:#FF0000'>退款申请中</em>";
                    break;
                case'6':
                    $data="<em style='color:#FF0000'>换货申请中</em>";
                    break;
                case'7':
                    $data="<em style='color:#FF0000'>已退款</em>";
                    break;
                default:
                    $data="<em style='color:#FF0000'>异常</em>";
                    break;
            }
        }else if($paytypes==2){ //货到付款
            switch($status){
                case"1":
                    $data="<em class='o_d_sign'>待发货</em>";
                    break;
                case'2':
                    $data="<em style='color:#24aa2f'>已发货,待收货</em>";
                    break;
                case'4':
                    $data="<em style='color:#FF0000'>已取消</em>";
                    break;
                case'5':
                    $data="<em style='color:#FF0000'>退款申请中</em>";
                    break;
                case'7':
                    $data="<em style='color:#FF0000'>已退款</em>";
                    break;
                default:
                    $data="<em style='color:#FF0000'>异常</em>";
                    break;
            }
        }else if($paytypes==5){ //到店付款
            switch($status){
                case"1":
                    $data="<em class='o_d_sign'>待付款使用</em>";
                    break;
                case'2':
                    $data="<em style='color:#24aa2f'>已使用</em>";
                    break;
                case'4':
                    $data="<em style='color:#FF0000'>已取消</em>";
                    break;
                case'5':
                    $data="<em style='color:#FF0000'>退款申请中</em>";
                    break;
                case'7':
                    $data="<em style='color:#FF0000'>已退款</em>";
                    break;
                default:
                    $data="<em style='color:#FF0000'>异常</em>";
                    break;
            }
        }else{
            $data="";
        }


        return $data;
    }



    //获取订单状态
    function getPayTypesByType_hotel($paytypes){
        $data="";

        switch($paytypes){
            case"1":
                $data="微支付";
                break;
            case"2":
                $data="货到付款";
                break;
            case"3":
                $data="翼支付";
                break;

            case"5":
                $data="前台支付";
                break;
            case"6":
                $data="微支付";
                break;
            default:
                $data="<em style='color:#FF0000'>异常</em>";
                break;
        }

        return $data;
    }


    //生成唯一订单号:充值订单
    function creatorderNumber_order($id)
    {
        //echo time()."<br/>";
        $fists=substr(time(),-3);
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
        $lastnum=substr($sums,-1);
        $ornumbers=$ornumbers.$lastnum;

        return $ornumbers;
    }

    //会员自动登陆
    function vipAutoLogin($wxid,$key,$tis,$uid)
    {
        global $db;
        if($wxid){
            $vipinfo=$db->getRow("select * from wx_Card_Vip  where  wxid='$wxid' ");
            if($vipinfo){
                SetCookie("hy_wxid",authcode($vipinfo["wxid"],'ENCODE'),cotimes,'/');
                $_COOKIE['hy_wxid']=authcode($vipinfo["wxid"],'ENCODE');
                SetCookie("hy_id",authcode(strval($vipinfo["id"]),'ENCODE'),cotimes,'/');
                $_COOKIE['hy_id']=authcode(strval($vipinfo["id"]),'ENCODE');
                SetCookie("hy_phone",authcode($vipinfo["phone"],'ENCODE'),cotimes,'/');
                $_COOKIE['hy_phone']=authcode($vipinfo["phone"],'ENCODE');
                return true;

            }else{ //不是会员
                $phpself =$_SERVER['PHP_SELF'];
                $str = end(explode("/",$phpself)); //当前访问的文件名
                $str= empty($str) ? 'index.php' : trim($str);
                if($str=="hy.php"){
                    die("<meta HTTP-EQUIV=refresh Content='0;url=/allhy/userreg.php?wxid=".$wxid."&selflink=".common::CodePath()."'>");
                }

                return false;
            }

            //}

        }else{
            return false;
        }
    }


    /*function usersAutoLogin($id,$key,$tis,$uid)
    {
        global $db;
        if($id){
            $usersinfo=$db->getRow("select * from wx_users  where  id='$id' ");

                //SetCookie("hy_wxid",authcode($vipinfo["wxid"],'ENCODE'),cotimes,'/');
                //$_COOKIE['hy_wxid']=authcode($vipinfo["wxid"],'ENCODE');
                SetCookie("users_id",authcode(strval($usersinfo["id"]),'ENCODE'),cotimes,'/');
                $_COOKIE['users_id']=authcode(strval($usersinfo["id"]),'ENCODE');
                //SetCookie("hy_phone",authcode($vipinfo["phone"],'ENCODE'),cotimes,'/');
               //$_COOKIE['hy_phone']=authcode($vipinfo["phone"],'ENCODE');
                return true;
            //}

        }else{
            return false;
        }
    }*/


    //查询地区信息
    function getAllAddrList($leve=2,$pid=0,$nbsp="",$isadd='yes'){
        global $db;
        $sql2="select * from wx_addr where  pid=".$pid."  order by orderList asc,id desc";
        $resultcat=$db->execute($sql2);
        while($row=$db->fetch_array($resultcat)){
            $haveccat=$db->getOne("select count(id) from wx_addr where  pid=".$row["id"]);
            if($haveccat){

                echo "<option  value='".$row['id']."'>".$nbsp.$row['cattitle']."</option>";

                getAllAddrList($leve=2,$row['id'],$nbsp."&nbsp;&nbsp;&nbsp;&nbsp;");
            }else{
                echo "<option  value='".$row['id']."'>".$nbsp.$row['cattitle']."</option>";
            }


        }
        //$nbsp=$nbsp."&nbsp;&nbsp;";
    }



    //查询地区信息
    function getAllAddrList_toadd($leve=2,$pid=0,$nbsp="",$isadd='yes'){
        global $db;
        $sql2="select * from wx_addr where  pid=".$pid."  order by orderList asc,id desc";
        $resultcat=$db->execute($sql2);
        while($row=$db->fetch_array($resultcat)){
            echo "<option  value='".$row['id']."'>".$nbsp.$row['cattitle']."</option>";
        }
        //$nbsp=$nbsp."&nbsp;&nbsp;";
    }


    //根据用户id获得用户名称
    function get_city_name($id){
        global $db,$userid;
        $cattitle=$db->getOne("select cattitle from wx_addr where  id=".$id);
        if($cattitle){
            return $cattitle;
        }else{
            return "<span style='color:#FF0000'>城市不存在</span>";
        }

    }

    function getpic($fpic){

        $getpic=daddslashes($fpic);


        return $getpic;
    }

    function daddslashes($string, $force = 0) {
        !defined('MAGIC_QUOTES_GPC') && define('MAGIC_QUOTES_GPC', get_magic_quotes_gpc());
        if(!MAGIC_QUOTES_GPC || $force) {
            if(is_array($string)) {
                foreach($string as $key => $val) {
                    $string[$key] = daddslashes($val, $force);
                }
            } else {
                $string = addslashes($string);
            }
        }
        return $string;
    }

    //获取帖子状态
    function get_forum_topics_atatus($status){
        $data="";
        switch($status){
            case"1":
                $data="审核通过";
                break;
            case"-1":
                $data="<em style='color:#00F'>未审核</em>";
                break;
            default:
                $data="<em style='color:#FF0000'>已被删除</em>";
                break;
        }
        return $data;
    }

    //获取帖子状态
    function get_forum_comment_status($status){
        $data="";
        switch($status){
            case"1":
                $data="正常显示";
                break;
            case"-1":
                $data="<em style='color:#00F'>未审核</em>";
                break;
            default:
                $data="<em style='color:#FF0000'>已被删除</em>";
                break;
        }
        return $data;
    }

    //获取消息状态
    function get_forum_message_status($status){
        $data="";
        switch($status){
            case"1":
                $data="未读";
                break;
            case"2":
                $data="已读";
                break;
            case"3":
                $data="已通过";
                break;
            case"4":
                $data="已拒绝";
                break;
            default:
                $data="已读";
                break;
        }
        return $data;
    }

    function php_alert($text='', $time='', $fn='', $fn2=''){
        echo '<script>alert("'.$text.'","'.$time.'","'.$fn.'","'.$fn2.'");</script>';
    }


    // 关键字过滤函数
    function do_key_word_Check($str,$dir=""){
        // 去除空白
        $str = trim($str);
        // 读取关键字文本
        // 转换成数组
        $content = file_get_contents($dir.'upload/keyword.txt');


        // 转换成数组
        $arr = explode("\n", $content);
        // 遍历检测
        for($i=0,$k=count($arr);$i<$k;$i++){
            // 如果此数组元素为空则跳过此次循环
            if($arr[$i]==''){
                continue;
            }
            // 如果检测到关键字，则返回匹配的关键字,并终止运行
            if(@strpos($str,trim($arr[$i]))!==false){
                //$i=$k;
                return trim($arr[$i]);
            }
        }
        // 如果没有检测到关键字则返回false
        return false;
    }

    /**
     * 获取当前页面完整URL地址
     */
    function get_url() {
        $sys_protocal = isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://';
        $php_self = $_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
        $path_info = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '';
        $relate_url = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : $php_self.(isset($_SERVER['QUERY_STRING']) ? '?'.$_SERVER['QUERY_STRING'] : $path_info);
        return $sys_protocal.(isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '').$relate_url;
    }

    function unsetParam($param, $url) {
        return preg_replace(
                array("/{$param}=[^&]*/i", '/[&]+/', '/\?[&]+/', '/[?&]+$/',),
                array(''               , '&'     , '?'       , ''        ,),
                $url
        );
    }



    //获取Access_token
    function getAccess_token_new(){
        global $db;
        $wxinfo=$db->getRow("select * from wx_GongZhong");
        if(empty($wxinfo['access_token']) || time()>=$wxinfo["access_token_endtime"]){
            $token=getAccess_token($wxinfo['APPID'],$wxinfo['AppSecret']);
            if(empty($token)){
                return "";
            }
            $times=time()+6000;
            $arinfo = array(
                    'access_token'  => empty($token) ? '' : trim($token),
                    'access_token_endtime'    => $times
            );
            $db->update('wx_GongZhong',$arinfo, "1=1");

        }else{

            $token=$wxinfo['access_token'];
        }


        return $token;
    }


    //获取Access_token
    function getAccess_token($APPID,$AppSecret){
        //获取access_token
        $curls="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$APPID."&secret=".$AppSecret;

        $ch1 = curl_init() ;

        // curl_setopt($ch1, CURLOPT_POST, 1);
        /*	 curl_setopt($ch1, CURLOPT_CUSTOMREQUEST, "POST");
             curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
             curl_setopt($ch1, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));*/
        curl_setopt($ch1,CURLOPT_URL,$curls);
        curl_setopt($ch1, CURLOPT_HEADER, false);//设置header
        curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, false);  //协议头 https，curl 默认开启证书验证，所以应关闭
        curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);//要求结果为字符串且输出到屏幕上

        $result = json_decode(curl_exec($ch1));
        $token=$result->access_token;

    //echo $token.$curls;
        //echo exit();
        return $token;
    }

    //获取带参二维码ticket
    function get_ticket($token,$values){
        //获取access_token
        $curls="https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=".$token;
        $ch1 = curl_init() ;
        //$posts='{"action_name": "QR_LIMIT_SCENE", "action_info": {"scene": {"scene_id": '.$values.'}}}'; //永久二维码
        $posts='{"action_name": "QR_LIMIT_STR_SCENE", "action_info": {"scene": {"scene_str": "'.$values.'"}}}';//永久二维码

        // curl_setopt($ch1, CURLOPT_POST, 1);
        /*	 curl_setopt($ch1, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch1, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));*/
        curl_setopt($ch1,CURLOPT_URL,$curls);
        curl_setopt($ch1, CURLOPT_HEADER, false);//设置header
        curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, false);  //协议头 https，curl 默认开启证书验证，所以应关闭
        curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);//要求结果为字符串且输出到屏幕上
        curl_setopt($ch1, CURLOPT_POSTFIELDS, $posts);
        $result = json_decode(curl_exec($ch1),true);
        // $token=$result->access_token;

    //echo $token.$curls;
        //echo exit();
        return $result;
    }


    //发送模版信息
    function send_template_message($jsons='')
    {
        global $uid;
        $token=getAccess_token_new();
        if($token==""){return "notoken"; }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$token);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsons);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //$tmpInfo = curl_exec($ch);
        /*if (curl_errno($ch)) {
          return curl_error($ch);
        }*/
        $result = json_decode(curl_exec($ch));
        //print_r($result);
        curl_close($ch);
        return $result;
    }


    //http curl
    function http_request_curl($url, $data = null)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_TIMEOUT, 60); //设置超时
        if (!empty($data)){
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }


    //根据pid查询所有分类
    function get_all_wx_shop_cat_config_id($pid=0){
        global $db;
        $sql2="select * from wx_shop_cat where  pid=".$pid." and is_show=1  order by orderList asc,id desc";
        $resultcat=$db->execute($sql2);
        $ids="";
        //echo $sql2."<br/>";
        if($ids==""){
            $ids=$pid;
        }else{
            $ids=$ids.",".$pid;
        }
        while($row=$db->fetch_array($resultcat)){
            $ids=$ids.",".get_all_wx_shop_cat_config_id($row["id"]);
        }
        return $ids;
    }

    //根据pid查询所有分类
    function get_all_wx_card_cat_config_id($pid=0){
        global $db;
        $sql2="select * from wx_card_cat where  pid=".$pid." and is_show=1  order by orderList asc,id desc";
        $resultcat=$db->execute($sql2);
        $ids="";
        //echo $sql2."<br/>";
        if($ids==""){
            $ids=$pid;
        }else{
            $ids=$ids.",".$pid;
        }
        while($row=$db->fetch_array($resultcat)){
            $ids=$ids.",".get_all_wx_card_cat_config_id($row["id"]);
        }
        return $ids;
    }


    //get_all_hy_id_id(1,2,0)

    /**
     *查询某一深度用户id
     * $selive 需要查询的深度
     * $nowlive 当前查询的深度
     */
    function get_all_hy_id_id($pid='',$selive=1,$nowlive=0){
        global $db;
        $nowlive=$nowlive+1;//当前查询深度

        $sql2="select id from wx_Card_Vip where  tj_vip in ($pid)";
        $resultcat=$db->execute($sql2);
        //echo $sql2;
        //$ids="0";
        //if(!$resultcat){
        //$ids="0";
        //}
        if($nowlive==$selive){ //当前深度，等于需要查询的深度
            //echo $nowlive.":".$selive;
            while($row=$db->fetch_array($resultcat)){
                if($ids==""){
                    $ids=$row["id"];
                }else{
                    $ids=$ids.",".$row["id"];
                }
            }
        }else{ //还需要先查询子用户开始
            while($row=$db->fetch_array($resultcat)){
                $chi=get_all_hy_id_id($row["id"],$selive,$nowlive);
                if($ids==""){
                    //$ids=$row["id"].",".get_all_hy_id_id($row["id"],$selive,$nowlive);
                    //$ids=get_all_hy_id_id($row["id"],$selive,$nowlive);

                    //$chi=get_all_hy_id_id($row["id"],$selive,$nowlive);
                    if($chi<>""){
                        $ids=$chi;
                    }

                }else{
                    //$ids=$ids.",".get_all_hy_id_id($row["id"],$selive,$nowlive);

                    if($chi<>""){
                        $ids=$ids.",".$chi;
                    }

                }
            }
        }//还需要先查询子用户结束

        return $ids;
    }



    /**
     *查询某一深度用户id
     * $selive 需要查询的深度
     * $nowlive 当前查询的深度
     */
    function get_all_vip_id_id($pid='',$selive=1,$nowlive=0){
        global $db;
        $nowlive=$nowlive+1;//当前查询深度

        $sql2="select id from wx_Card_Vip where  tj_vip in ($pid)";
        $resultcat=$db->execute($sql2);
        //echo $sql2;
        //$ids="0";
        //if(!$resultcat){
        //$ids="0";
        //}
        if($nowlive==$selive){ //当前深度，等于需要查询的深度
            //echo $nowlive.":".$selive;
            while($row=$db->fetch_array($resultcat)){
                if($ids==""){
                    $ids=$row["id"];
                }else{
                    $ids=$ids.",".$row["id"];
                }
            }
        }else{ //还需要先查询子用户开始
            while($row=$db->fetch_array($resultcat)){
                $chi=get_all_vip_id_id($row["id"],$selive,$nowlive);
                if($ids==""){
                    //$ids=$row["id"].",".get_all_hy_id_id($row["id"],$selive,$nowlive);
                    //$ids=get_all_hy_id_id($row["id"],$selive,$nowlive);

                    //$chi=get_all_hy_id_id($row["id"],$selive,$nowlive);
                    if($chi<>""){
                        $ids=$chi;
                    }

                }else{
                    //$ids=$ids.",".get_all_hy_id_id($row["id"],$selive,$nowlive);

                    if($chi<>""){
                        $ids=$ids.",".$chi;
                    }

                }
            }
        }//还需要先查询子用户结束

        return $ids;
    }


    /**
     *根据某一会员查询其下所有会员id
     * $selive 需要查询的深度
     * $nowlive 当前查询的深度
     */
    function get_all_hy_id_id_all($pid='',$selive=3,$nowlive=0,$nowids=''){
        global $db;
        $nowlive=$nowlive+1;//当前查询深度

        $sql2="select id from wx_Card_Vip where  tj_vip in ($pid)";
        //$sql2="select wc.id from wx_Card_Vip as wc left join wx_attentionuser as wxatt on wc.wxid=wxatt.wxid where wc.tj_vip in(".$pid.") and wxatt.status=1";
        $resultcat=$db->execute($sql2);
        //echo $sql2."nowids:".$nowids."<br/>";
        //$ids="0";
        //if(!$resultcat){
        //$ids="0";
        //}
        $ids=$nowids;
        if($nowlive==$selive){ //当前深度，等于需要查询的深度
            //echo $nowlive.":ffffffffffffff".$pid.":".$selive;
            while($row=$db->fetch_array($resultcat)){
                if($ids==""){
                    $ids=$row["id"];
                }else{
                    $ids=$ids.",".$row["id"];
                }
            }
        }else{ //还需要先查询子用户开始
            while($row=$db->fetch_array($resultcat)){
                $chi=get_all_hy_id_id_all($row["id"],$selive,$nowlive);
                if($ids==""){ //之前没有
                    //$ids=$row["id"].",".get_all_hy_id_id($row["id"],$selive,$nowlive);
                    //$ids=get_all_hy_id_id($row["id"],$selive,$nowlive);

                    //$chi=get_all_hy_id_id($row["id"],$selive,$nowlive);
                    if($chi<>""){
                        $ids=$row["id"].",".$chi;
                    }else{
                        $ids=$row["id"];
                    }

                }else{
                    //$ids=$ids.",".get_all_hy_id_id($row["id"],$selive,$nowlive);

                    if($chi<>""){
                        $ids=$ids.",".$row["id"].",".$chi;
                    }else{
                        $ids=$ids.",".$row["id"];
                    }

                }
            }
        }//还需要先查询子用户结束

        //echo "ids:".$ids."<br/>";
        return $ids;
    }



    //获取订单类型
    function getincentive_info_type($status){
        $data="";
        switch($status){
            case"0":
                $data="<em style='color:#24aa2f'>一级返佣</em>";
                break;
            case"1":
                $data="<em style='color:#24aa2f'>一级返佣</em>";
                break;
            case'2':
                $data="<em style='color:#24aa2f'>二级返佣</em>";
                break;
            case'3':
                $data="<em style='color:#24aa2f'>三级返佣</em>";
                break;
            case'4':
                $data="<em style='color:#24aa2f'>推荐关注返佣</em>";
                break;
            default:
                $data="<em style='color:#FF0000'>异常</em>";
                break;
        }
        return $data;
    }

    //获取提现类型
    function get_present_record_type($type){
        $data="";
        switch($type){

            case"1":
                $data="<em style='color:#24aa2f'>余额提现</em>";
                break;
            case'2':
                $data="<em style='color:#24aa2f'>推广提现</em>";
                break;
            default:
                $data="<em style='color:#FF0000'>异常</em>";
                break;
        }
        return $data;
    }

    function get_present_record_status($status){
        $data="";
        switch($status){

            case"1":
                $data="<em style='color:#24aa2f'>待打款</em>";
                break;
            case'2':
                $data="<em style='color:blue'>已打款</em>";
                break;
            default:
                $data="<em style='color:#FF0000'>异常</em>";
                break;
        }
        return $data;
    }

    //获取订单类型
    function getincentive_info_types($status,$ortype){
        $data="";
        if($ortype==1){
            switch($status){
                case"0":
                    $data="<em style='color:#24aa2f'>自身返佣</em>";
                    break;
                case"1":
                    $data="<em style='color:#24aa2f'>一级返佣</em>";
                    break;
                case'2':
                    $data="<em style='color:#24aa2f'>二级返佣</em>";
                    break;
                case'3':
                    $data="<em style='color:#24aa2f'>三级返佣</em>";
                    break;
                case'4':
                    $data="<em style='color:#24aa2f'>推荐关注返佣</em>";
                    break;
                default:
                    $data="<em style='color:#FF0000'>异常</em>";
                    break;
            }
        }else{
            switch($status){
                case"1":
                    $data="<em style='color:#24aa2f'>省级提成</em>";
                    break;
                case'2':
                    $data="<em style='color:#24aa2f'>市级提成</em>";
                    break;
                case'3':
                    $data="<em style='color:#24aa2f'>县级提成</em>";
                    break;
                default:
                    $data="<em style='color:#FF0000'>异常</em>";
                    break;
            }
        }

        return $data;
    }


    //获取返佣状态
    function getincentive_info_status($status){
        $data="";
        switch($status){
            case"1":
                $data="<em style='color:#00F'>审核未通过</em>";
                break;
            case'2':
                $data="<em style='color:#00F'>成功返佣</em>";
                break;
            case'9':
                $data="<em style='color:#24aa2f'>待审核</em>";
                break;
            case'4':
                $data="<em style='color:#FF0000'>用户退货</em>";
                break;
            default:
                $data="<em style='color:#FF0000'>异常</em>";
                break;
        }
        return $data;
    }

    //判断优惠券类型
    function getTiXianStatus($types){
        switch ($types)
        {
            case "1":
                return "<em style='color:#FF0000'>待打款</em>";
                break;
            case "2":
                return "已打款";
                break;
            default :
                return "";
                break;
        }
    }

    //充值支付状态
    function getpayStatus($types){
        switch ($types)
        {
            case "0":
                return "等待支付";
                break;
            case "1":
                return '<button style="border-radius: 5px; background-color: green; color: #ffFFFF; border: 0px; font-size: 10px; margin-bottom: 2px;">支付成功</button>';
                break;
            case "2":
                return '<button style="border-radius: 5px; background-color: red; color: #ffFFFF; border: 0px; font-size: 10px; margin-bottom: 2px;">支付失败</button>';
                break;
            default :
                return "异常";
                break;
        }
    }

    //充值状态
    function getczStatus($types){
        switch ($types)
        {
            case "0":
                return "待支付";
                break;
            case "1":
                return "已提交";
                break;
            case "2":
                return '<button style="border-radius: 5px; background-color: green; color: #ffFFFF; border: 0px; font-size: 10px; margin-bottom: 2px;">缴费成功</button>';
                break;
            case "3":
                return '<button style="border-radius: 5px; background-color: red; color: #ffFFFF; border: 0px; font-size: 10px; margin-bottom: 2px;">缴费失败</button>';
                break;
            default :
                return "系统异常";
                break;
        }
    }

    //充值状态
    function getfyStatus($types){
        switch ($types)
        {
            case "1":
                return "待返佣";
                break;
            case "2":
                return '<button style="border-radius: 5px; background-color: green; color: #ffFFFF; border: 0px; font-size: 10px; margin-bottom: 2px;">返佣成功</button>';
                break;
            case "3":
                return '<button style="border-radius: 5px; background-color: red; color: #ffFFFF; border: 0px; font-size: 10px; margin-bottom: 2px;">返佣失败</button>';
                break;
            default :
                return "系统异常";
                break;
        }
    }
    function get_ticket_vip_img($vipid){
        global $db;
        $xzinfo=$db->getRow("select * from wx_card_vip where id=$vipid");
        $wxinfo=$db->getRow("select * from wx_GongZhong ");
        if(empty($wxinfo['APPID']) || empty($wxinfo['AppSecret'])){Common::jump("请先完善该公众号信息","publicNumber.php");}
        if(empty($wxinfo['access_token']) || time()>=$wxinfo["access_token_endtime"]){
            $token=getAccess_token($wxinfo['APPID'],$wxinfo['AppSecret']);
            if(empty($token)){return "false";}
            //echo exit();
            //token值修改
            $times=time()+6000;
            $arinfo = array(
                    'access_token'  => empty($token) ? '' : trim($token),
                    'access_token_endtime'    => $times
            );

            $db->update('wx_GongZhong',$arinfo, "1=1");
        }else{
            $token=$wxinfo['access_token'];
        }
        $result=get_ticket($token,"vip_".$vipid);
        //print_r($result);
        if($result["ticket"]){
            $db->query("update wx_card_vip set ticket='".$result["ticket"]."' WHERE   id=$vipid");
            $url="https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=".$result["ticket"];
            //$img = file_get_contents($url);
            //更换获取方法
            $url = $url;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $img = curl_exec($ch);
            //更换获取方法

            $imgurl='upload/erweima/'.$vipid.'_ewm.jpg';
            file_put_contents($imgurl,$img);
    //		echo '请将本带参二维码保存<br/><img src="'.$url.'" />';
    //		die("aaa");
            return $imgurl;
            //$content = https_request($url);

        }else{
            return "";
        }

    }

    function get_ticket_cj_img($addis){
        global $db;
        //$xzinfo=$db->getRow("select * from wx_card_vip where id=$vipid");
        $wxinfo=$db->getRow("select * from wx_GongZhong ");
        if(empty($wxinfo['APPID']) || empty($wxinfo['AppSecret'])){Common::jump("请先完善该公众号信息","publicNumber.php");}
        if(empty($wxinfo['access_token']) || time()>=$wxinfo["access_token_endtime"]){
            $token=getAccess_token($wxinfo['APPID'],$wxinfo['AppSecret']);
            if(empty($token)){return "false";}
            //echo exit();
            //token值修改
            $times=time()+6000;
            $arinfo = array(
                    'access_token'  => empty($token) ? '' : trim($token),
                    'access_token_endtime'    => $times
            );

            $db->update('wx_GongZhong',$arinfo, "1=1");
        }else{
            $token=$wxinfo['access_token'];
        }
        $result=get_ticket($token,"sc_".$addis);
        //print_r($result);
        if($result["ticket"]){
            //$db->query("update wx_card_vip set ticket='".$result["ticket"]."' WHERE  id=$vipid");
            $url="https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=".$result["ticket"];
            $img = file_get_contents($url);
            $imgurl='../upload/erweima/'.$addis.'_ewm.jpg';
            file_put_contents($imgurl,$img);
            return $imgurl;
            //$content = https_request($url);
            //echo '请将本带参二维码保存<br/><img src="'.$url.'" />';
        }else{
            return "";
        }


    }


    //根据用户wx_company_cat ID查询栏目名称
    function getLeftKeywords($srt,$lens){
        if(strlen($srt)>$lens*2){
            return subString(stripslashes(strip_tags($srt)),$lens)."..";
        }else{
            return $srt;
        }
    }



    ?>
