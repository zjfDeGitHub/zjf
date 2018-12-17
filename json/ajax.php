<?php
require_once('../../ini.php');
require_once('../../members/function.php');
$act = isset($_REQUEST['act']) ? str_filter($_REQUEST['act']) : 'info';

if ($act == 'newsadd')
{
    $id=intval(str_filter($_REQUEST["id"]));
    if (empty($_REQUEST['news_name'])) {
        $res['state'] = -1;
        $res['msg'] = "请输入新闻名称!";
        die(json_encode($res));
    }
    if (empty($_REQUEST['pid'])) {
        $res['state'] = -1;
        $res['msg'] = "所属栏目必须填写!";
        die(json_encode($res));
    }
    if (empty($_REQUEST['news_title'])) {
        $res['state'] = -1;
        $res['msg'] = "请输入新闻标题!";
        die(json_encode($res));
    }
    //得到文件名称
    $array = array(
        'id'=>empty($_REQUEST['id']) ? 0 : str_filter($_REQUEST['id']),
        'news_name'=>empty($_REQUEST['news_name']) ? 0 : str_filter($_REQUEST['news_name']),
        'news_content'=>empty($_REQUEST['introduction']) ? " " : str_filter($_REQUEST['introduction']),
        'news_title'=>empty($_REQUEST['news_title']) ? "" : str_filter($_REQUEST['news_title']),
        'news_index_img'=>empty($_REQUEST['classtime']) ? 0 : str_filter($_REQUEST['classtime']),
        'news_url'=>empty($_REQUEST['news_url']) ? " " : str_filter($_REQUEST['news_url']),
        'pid'=>empty($_REQUEST['pid']) ? " " : str_filter($_REQUEST['pid']),
        'addtime' => time(),

    );
    if($id<>""){
        if($db->update('web_news',$array,"id=$id"))
        {
            $res['state'] = 1;
            $res['msg'] = "恭喜修改成功!";
            die(json_encode($res));
        }
        else
        {
            $res['state'] = -1;
            $res['msg'] = "修改失败!";
            die(json_encode($res));
        }
    }else{
        $insids = $db->insert('web_news', $array);
        if ($insids <> "") { //添加了栏目
            $res['state'] = 1;
            $res['msg'] = "恭喜添加成功!";
            die(json_encode($res));
        } else {
            $res['state'] = -1;
            $res['msg'] = "系统错误!";
            die(json_encode($res));
        }
    }
}
if ($act == 'nextshow')
{
    $id=intval(str_filter($_REQUEST["id"]));
    //得到文件名称
    $array = array(
        'id'=>empty($_REQUEST['id']) ? 0 : str_filter($_REQUEST['id']),
    );
    if($id<>""){
        if($db->execute("select * from web_index_cate where pid = $id"))
        {
            $res = $db->getAll("select * from web_index_cate where pid = $id");
            die(json_encode($res));
        }
        else
        {
            $res['state'] = -1;
            $res['msg'] = "修改失败!";
            die(json_encode($res));
        }
    }else{
        $insids = $db->insert('web_index_cate', $array);
        if ($insids <> "") { //添加了栏目
            $res['state'] = 1;
            $res['msg'] = "恭喜添加成功!";
            die(json_encode($res));
        } else {
            $res['state'] = -1;
            $res['msg'] = "系统错误!";
            die(json_encode($res));
        }
    }
}
if ($act == 'storeadd')
{
    $id=intval(str_filter($_REQUEST["id"]));
    //得到文件名称
    $array = array(
        'id'=>empty($_REQUEST['id']) ? 0 : str_filter($_REQUEST['id']),
        'img'=>empty($_REQUEST['classtime']) ? 0 : str_filter($_REQUEST['classtime']),
        'sid'=>empty($_REQUEST['sid']) ? 100 : str_filter($_REQUEST['sid']),
        'ppid'=>34, //门店风采图片
        'addtime' => time(),

    );
    if($id<>""){
        if($db->update('web_index_cate',$array,"id=$id"))
        {
            $res['state'] = 1;
            $res['msg'] = "恭喜修改成功!";
            die(json_encode($res));
        }
        else
        {
            $res['state'] = -1;
            $res['msg'] = "修改失败!";
            die(json_encode($res));
        }
    }else{
        $insids = $db->insert('web_index_cate', $array);
        if ($insids <> "") { //添加了栏目
            $res['state'] = 1;
            $res['msg'] = "恭喜添加成功!";
            die(json_encode($res));
        } else {
            $res['state'] = -1;
            $res['msg'] = "系统错误!";
            die(json_encode($res));
        }
    }
}
if ($act == 'cateadd')
{
    $id=intval(str_filter($_REQUEST["id"]));
    if (empty($_REQUEST['cate_name'])) {
        $res['state'] = -1;
        $res['msg'] = "请输入栏目名称!";
        die(json_encode($res));
    }
    if (empty($_REQUEST['cate_desc'])) {
        $res['state'] = -1;
        $res['msg'] = "请输入栏目简介!";
        die(json_encode($res));
    }
    //得到文件名称
    $file=$_FILES['cate_img'];
    $name = $file['name'];
    $type = strtolower(substr($name,strrpos($name,'.')+1)); //得到文件类型，并且都转化成小写

    $array = array(
       'id'=>empty($_REQUEST['id']) ? 0 : str_filter($_REQUEST['id']),
       'cate_desc'=>empty($_REQUEST['cate_desc']) ? 0 : str_filter($_REQUEST['cate_desc']),
        'content'=>empty($_REQUEST['introduction']) ? " " : str_filter($_REQUEST['introduction']),
        'cate_name'=>empty($_REQUEST['cate_name']) ? 0 : str_filter($_REQUEST['cate_name']),
       'pid'=>empty($_REQUEST['pid']) ? "" : str_filter($_REQUEST['pid']),
      'cate_img'=>empty($_REQUEST['classtime']) ? 0 : str_filter($_REQUEST['classtime']),
        'sid'=>empty($_REQUEST['sid']) ? "100" : str_filter($_REQUEST['sid']),
        'url'=>empty($_REQUEST['url']) ? " " : str_filter($_REQUEST['url']),
        'ppid'=>empty($_REQUEST['ppid']) ? 0 : str_filter($_REQUEST['ppid']),
        //'cate_img'=>$type,



       'addtime' => time(),

    );
    if($id<>""){
        if($db->update('web_index_cate',$array,"id=$id"))
        {
            $res['state'] = 1;
            $res['msg'] = "恭喜修改成功!";
            die(json_encode($res));
        }
        else
        {
            $res['state'] = -1;
            $res['msg'] = "修改失败!";
            die(json_encode($res));
        }
    }else{
        $insids = $db->insert('web_index_cate', $array);
        if ($insids <> "") { //添加了栏目
            $res['state'] = 1;
            $res['msg'] = "恭喜添加成功!";
            die(json_encode($res));
        } else {
            $res['state'] = -1;
            $res['msg'] = "系统错误!";
            die(json_encode($res));
        }
    }
}
if ($act == 'groupadd') //管理员添加
{
    $id=$_REQUEST["id"];
    if (empty($_REQUEST['groupName'])) {
        $res['state'] = -1;
        $res['msg'] = "请输入角色名称!";
        die(json_encode($res));
    }
    if (empty($_REQUEST['conent'])) {
        $res['state'] = -1;
        $res['msg'] = "请输入内容!";
        die(json_encode($res));
    }
    $xxs=@implode(',',$_REQUEST['xx']);
    $array = array(
        'groupName' => empty($_REQUEST['groupName']) ? 0 : str_filter($_REQUEST['groupName']),
        'conent' => empty($_REQUEST['conent']) ? 0 : str_filter($_REQUEST['conent']),
        'priv' => $xxs,
        'add_time' => time(),
    );
    if($id<>""){
        if($db->update('wx_admin_group',$array,"id=$id"))
        {
            $res['state'] = 1;
            $res['msg'] = "恭喜修改成功!";
            die(json_encode($res));
        }
        else
        {
            $res['state'] = -1;
            $res['msg'] = "修改失败!";
            die(json_encode($res));
        }
    }else{
        $insids = $db->insert('wx_admin_group', $array);
        if ($insids <> "") { //添加了规格
            $res['state'] = 1;
            $res['msg'] = "恭喜添加成功!";
            die(json_encode($res));
        } else {
            $res['state'] = -1;
            $res['msg'] = "系统错误!";
            die(json_encode($res));
        }
    }
}


if ($act == 'useradd') //管理员添加
{
    $id=$_REQUEST["id"];
    if (empty($_REQUEST['user_name'])) {
        $res['state'] = -1;
        $res['msg'] = "请输入角色名称!";
        die(json_encode($res));
    }
    if (empty($_REQUEST['phone'])) {
        $res['state'] = -1;
        $res['msg'] = "请输入手机号码!";
        die(json_encode($res));
    }
     if (empty($_REQUEST['email'])) {
         $res['state'] = -1;
         $res['msg'] = "请输入电子邮件!";
         die(json_encode($res));
     }
    if (empty($_REQUEST['group_id'])) {
        $res['state'] = -1;
        $res['msg'] = "请选择角色身份!";
        die(json_encode($res));
    }
    if (empty($_REQUEST['passwords'])) {
        $res['state'] = -1;
        $res['msg'] = "请输入您的密码!";
        die(json_encode($res));
    }

    $array = array(
        'user_name' => empty($_REQUEST['user_name']) ? 0 : str_filter($_REQUEST['user_name']),
        'phone' => empty($_REQUEST['phone']) ? 0 : str_filter($_REQUEST['phone']),
        'email' => empty($_REQUEST['email']) ? 0 : str_filter($_REQUEST['email']),
        'group_id' => empty($_REQUEST['group_id']) ? 0 : str_filter($_REQUEST['group_id']),
        'passwords' => empty($_REQUEST['passwords']) ? 0 : str_filter($_REQUEST['passwords']),
        'school_id' => empty($_REQUEST['school_id']) ? 0 : str_filter($_REQUEST['school_id']),
        'register_time' => time(),
        'state' => 1,
    );
    if($id<>""){
        if($db->update('wx_administrator',$array,"id=$id"))
        {
            $res['state'] = 1;
            $res['msg'] = "恭喜修改成功!";
            die(json_encode($res));
        }
        else
        {
            $res['state'] = -1;
            $res['msg'] = "修改失败!";
            die(json_encode($res));
        }
    }else{
        $insids = $db->insert('wx_administrator', $array);
        if ($insids <> "") { //添加了规格
            $res['state'] = 1;
            $res['msg'] = "恭喜添加成功!";
            die(json_encode($res));
        } else {
            $res['state'] = -1;
            $res['msg'] = "系统错误!";
            die(json_encode($res));
        }
    }
}
if ($act == 'privsadd') //管理员添加
{
    $id=$_REQUEST["id"];
    if (empty($_REQUEST['priv_name'])) {
        $res['state'] = -1;
        $res['msg'] = "请输入链接名称!";
        die(json_encode($res));
    }
    if (empty($_REQUEST['priv_link'])) {
        $res['state'] = -1;
        $res['msg'] = "请输入链接名称!";
        die(json_encode($res));
    }
    $array = array(
        'list_order' => empty($_REQUEST['list_order']) ? 0 : str_filter($_REQUEST['list_order']),
        'priv_name' => empty($_REQUEST['priv_name']) ? 0 : str_filter($_REQUEST['priv_name']),
        'priv_link' => empty($_REQUEST['priv_link']) ? '' : str_filter($_REQUEST['priv_link']),
        'addtime' => time(),
    );
    if($id<>""){
        if($db->update('wx_admin_privs',$array,"id=$id"))
        {
            $res['state'] = 1;
            $res['msg'] = "恭喜修改成功!";
            die(json_encode($res));
        }
        else
        {
            $res['state'] = -1;
            $res['msg'] = "修改失败!";
            die(json_encode($res));
        }
    }else{
        $insids = $db->insert('wx_admin_privs', $array);
        if ($insids <> "") { //添加了规格
            $res['state'] = 1;
            $res['msg'] = "恭喜添加成功!";
            die(json_encode($res));
        } else {
            $res['state'] = -1;
            $res['msg'] = "系统错误!";
            die(json_encode($res));
        }
    }
}
if ($act == 'adminadd') //管理员添加
{
    $id=intval(str_filter($_REQUEST["id"]));
    if (empty($_REQUEST['title'])) {
        $res['state'] = -1;
        $res['msg'] = "请输入你的门店名称!";
        die(json_encode($res));
    }
    if (empty($_REQUEST['uname'])){
        $res['state'] = -1;
        $res['msg'] = "请输入你的门店负责人!";
        die(json_encode($res));
    }
    if (empty($_REQUEST['Telephone'])) {
        $res['state'] = -1;
        $res['msg'] = "请输入你的门店联系方式!";
        die(json_encode($res));
    }
    if (empty($_REQUEST['address'])) {
        $res['state'] = -1;
        $res['msg'] = "请输入你的门店地址!";
        die(json_encode($res));
    }
    $array = array(
        'title' => empty($_REQUEST['title']) ? 0 : str_filter($_REQUEST['title']),
        'longitude' => empty($_REQUEST['longitude']) ? 0 : str_filter($_REQUEST['longitude']),
        'latitude' => empty($_REQUEST['latitude']) ? 0 : str_filter($_REQUEST['latitude']),
        'address' => empty($_REQUEST['address']) ? 0 : str_filter($_REQUEST['address']),
        'Telephone' => empty($_REQUEST['Telephone']) ? 0 : str_filter($_REQUEST['Telephone']),
        'work_sttime' => empty($_REQUEST['work_sttime']) ? '' : str_filter($_REQUEST['work_sttime']),
        'work_endtime' => empty($_REQUEST['work_endtime']) ? '' : str_filter($_REQUEST['work_endtime']),
        'money' => empty($_REQUEST['money']) ? '' : str_filter($_REQUEST['money']),
        'old_money' => empty($_REQUEST['old_money']) ? '' : str_filter($_REQUEST['old_money']),
        'score' => empty($_REQUEST['score']) ? '' : str_filter($_REQUEST['score']),
        'Analysis_img' => empty($_REQUEST['classtime']) ? '' : str_filter($_REQUEST['classtime']),
        'uname' => empty($_REQUEST['uname']) ? '' : str_filter($_REQUEST['uname']),
        'addtime' => time(),
        'state' => 1,
    );
    if($id<>""){
        if($db->update('wx_store',$array,"id=$id"))
        {
            $res['state'] = 1;
            $res['msg'] = "恭喜修改成功!";
            die(json_encode($res));
        }
        else
        {
            $res['state'] = -1;
            $res['msg'] = "修改失败!";
            die(json_encode($res));
        }
    }else{
        $insids = $db->insert('wx_store', $array);
        if ($insids <> "") { //添加了规格
            $res['state'] = 1;
            $res['msg'] = "恭喜添加成功!";
            die(json_encode($res));
        } else {
            $res['state'] = -1;
            $res['msg'] = "系统错误!";
            die(json_encode($res));
        }
    }
}
if ($act == 'orderadd') //订单添加
{
    $id=$_REQUEST["id"];
    if (empty($_REQUEST['uname'])) {
        $res['state'] = -1;
        $res['msg'] = "请输入你的姓名!";
        die(json_encode($res));
    }
    if (empty($_REQUEST['phone'])) {
        $res['state'] = -1;
        $res['msg'] = "请输入你的联系方式!";
        die(json_encode($res));
    }
    $array = array(
        'uname' => empty($_REQUEST['uname']) ? 0 : str_filter($_REQUEST['uname']),
        'wx_id' => "ombw95fj9tWqlW1uQxjAkXzhBFqQ",
        'phone' => empty($_REQUEST['phone']) ? 0 : str_filter($_REQUEST['phone']),
        'Classtype' => empty($_REQUEST['Classtype']) ? 0 : str_filter($_REQUEST['Classtype']),
        'Classhour' => empty($_REQUEST['Classhour']) ? 0 : str_filter($_REQUEST['Classhour']),
        'Grade' => empty($_REQUEST['Grade']) ? 0 : str_filter($_REQUEST['Grade']),
        'state' => empty($_REQUEST['state']) ? '' : str_filter($_REQUEST['state']),
        'teacher_id' => empty($_REQUEST['teacher_id']) ? '' : str_filter($_REQUEST['teacher_id']),
        'money' => empty($_REQUEST['money']) ? '' : str_filter($_REQUEST['money']),
        'paymoney' => empty($_REQUEST['paymoney']) ? '' : str_filter($_REQUEST['paymoney']),
        'teacher_uname' => empty($_REQUEST['teacher_uname']) ? '' : str_filter($_REQUEST['teacher_uname']),
        'school_id' => empty($_REQUEST['school_id']) ? '' : str_filter($_REQUEST['school_id']),
        'addtime' => time(),
    );
    if($id<>""){
        if($db->update('wx_object_order',$array,"id=$id"))
        {
            $res['state'] = 1;
            $res['msg'] = "恭喜修改成功!";
            die(json_encode($res));
        }
        else
        {
            $res['state'] = -1;
            $res['msg'] = "修改失败!";
            die(json_encode($res));
        }
    }else{
        $insids = $db->insert('wx_object_order', $array);
        if ($insids <> "") { //添加了规格
            $ordernumber="wx".time().$insids;
            $db->update('wx_object_order',array('ordernumber'=>$ordernumber), "id=".$insids);
            //订单添加成功需要添加一条备注的
            $sqls = "SELECT * FROM wx_object_order WHERE 	id=$insids";
            $orderinfo = $db->getRow($sqls);
            $postinfo = array(
                'wxid'=> $orderinfo["wx_id"], //postData 数据
                'oid'=> $orderinfo["id"], //订单号
                'sumClass'=> $Classhour, //总课时
                'overClass'=> $Classhour, //剩余课时
                't_vipid'=> $orderinfo["teacher_id"], //教师ID
                'Classtype'=> $orderinfo["Classtype"], //班型
                'Grade'=>$orderinfo["Grade"], //年级
                'addtime'=> time(), //addtime
                'addip'=> realIp() //addip
            );
            $addorid=$db->insert('wx_subject',$postinfo);
            $res['state'] = 1;
            $res['msg'] = "恭喜添加成功!";
            die(json_encode($res));
        } else {
            $res['state'] = -1;
            $res['msg'] = "系统错误!";
            die(json_encode($res));
        }
    }
}
if ($act == 'delnews') //管理员添加
{
    $id=$_REQUEST["id"];
    if($db->query("DELETE FROM web_news WHERE id=$id")){
        $res['state'] = 1;
        $res['msg'] = "恭喜删除成功!";
        die(json_encode($res));
    }else{
        $res['state'] = -1;
        $res['msg'] = "系统错误!";
        die(json_encode($res));
    }
}
if ($act == 'del') //管理员添加
{
    $id=$_REQUEST["id"];
    if($db->query("DELETE FROM web_index_cate WHERE id=$id")){
        $res['state'] = 1;
        $res['msg'] = "恭喜删除成功!";
        die(json_encode($res));
    }else{
        $res['state'] = -1;
        $res['msg'] = "系统错误!";
        die(json_encode($res));
    }
}
if ($act == 'storedel') //管理员添加
{
    $id=$_REQUEST["id"];
    if($db->query("DELETE FROM web_index_cate WHERE id=$id")){
        $res['state'] = 1;
        $res['msg'] = "恭喜删除成功!";
        die(json_encode($res));
    }else{
        $res['state'] = -1;
        $res['msg'] = "系统错误!";
        die(json_encode($res));
    }
}
if ($act == 'storedel') //管理员添加
{
    $id=$_REQUEST["id"];
    if($db->query("DELETE FROM web_index_cate WHERE id=$id")){
        $res['state'] = 1;
        $res['msg'] = "恭喜删除成功!";
        die(json_encode($res));
    }else{
        $res['state'] = -1;
        $res['msg'] = "系统错误!";
        die(json_encode($res));
    }
}
if ($act == 'prdel') //权限删除
{
    $id=$_REQUEST["id"];
    if($db->query("DELETE FROM wx_admin_privs WHERE id=$id")){
        $res['state'] = 1;
        $res['msg'] = "恭喜删除成功!";
        die(json_encode($res));
    }else{
        $res['state'] = -1;
        $res['msg'] = "系统错误!";
        die(json_encode($res));
    }
}
if ($act == 'upprice') //权限删除
{
    $id=$_REQUEST["id"];
    $Surplus_money=$_REQUEST["Surplus_money"];
    $is_all=$_REQUEST["is_all"];
    if($db->update('wx_object_order',array('Surplus_money'=>$Surplus_money,'is_all'=>$is_all), "id=$id")){
        $res['state'] = 1;
        $res['msg'] = "恭喜更新成功!";
        die(json_encode($res));
    }else{
        $res['state'] = -1;
        $res['msg'] = "系统错误!";
        die(json_encode($res));
    }
}

if ($act == 'gradedel') //管理员添加
{
    $id=$_REQUEST["id"];
    if($db->query("DELETE FROM wx_grade WHERE id=$id")){
        $res['state'] = 1;
        $res['msg'] = "恭喜删除成功!";
        die(json_encode($res));
    }else{
        $res['state'] = -1;
        $res['msg'] = "系统错误!";
        die(json_encode($res));
    }
}
if ($act == 'coupondel') //优惠券删除
{
    $id=$_REQUEST["id"];
    if($db->query("DELETE FROM wx_couponinfo WHERE id=$id")){
        $res['state'] = 1;
        $res['msg'] = "恭喜删除成功!";
        die(json_encode($res));
    }else{
        $res['state'] = -1;
        $res['msg'] = "系统错误!";
        die(json_encode($res));
    }
}
if ($act == 'couponLogdel') //优惠券领取记录删除
{
    $id=$_REQUEST["id"];
    if($db->query("DELETE FROM wx_couponlog WHERE id=$id")){
        $res['state'] = 1;
        $res['msg'] = "恭喜删除成功!";
        die(json_encode($res));
    }else{
        $res['state'] = -1;
        $res['msg'] = "系统错误!";
        die(json_encode($res));
    }
}
if ($act == 'hourdel') //管理员添加
{
    $id=$_REQUEST["id"];
    if($db->query("DELETE FROM wx_class_hour WHERE id=$id")){
        $res['state'] = 1;
        $res['msg'] = "恭喜删除成功!";
        die(json_encode($res));
    }else{
        $res['state'] = -1;
        $res['msg'] = "系统错误!";
        die(json_encode($res));
    }
}
if ($act == 'classdel') //管理员添加
{
    $id=$_REQUEST["id"];
    if($db->query("DELETE FROM wx_class_type WHERE id=$id")){
        $res['state'] = 1;
        $res['msg'] = "恭喜删除成功!";
        die(json_encode($res));
    }else{
        $res['state'] = -1;
        $res['msg'] = "系统错误!";
        die(json_encode($res));
    }
}
if ($act == 'classadd') //教师添加
{
    $id=$_REQUEST["id"];

    if (empty($_REQUEST['title'])) {
        $res['state'] = -1;
        $res['msg'] = "请输入课时";
        die(json_encode($res));
    }
    $array = array(
        'sort' => empty($_REQUEST['sort']) ? 0 : str_filter($_REQUEST['sort']),
        'title' => empty($_REQUEST['title']) ? 0 : str_filter($_REQUEST['title']),
        'addtime' => time(),
    );
    if($id<>""){
        if($db->update('wx_class_type',$array,"id=$id"))
        {
            $res['state'] = 1;
            $res['msg'] = "恭喜修改成功!";
            die(json_encode($res));
        }
        else
        {
            $res['state'] = -1;
            $res['msg'] = "修改失败!";
            die(json_encode($res));
        }
    }else{
        $insids = $db->insert('wx_class_type', $array);
        if ($insids <> "") {
            $res['state'] = 1;
            $res['msg'] = "恭喜添加成功!";
            die(json_encode($res));
        } else {
            $res['state'] = -1;
            $res['msg'] = "系统错误!";
            die(json_encode($res));
        }
    }
}

if ($act == 'gradeadd') //教师添加
{
    $id=$_REQUEST["id"];

    if (empty($_REQUEST['title'])) {
        $res['state'] = -1;
        $res['msg'] = "请输入课时";
        die(json_encode($res));
    }
    $array = array(
        'sort' => empty($_REQUEST['sort']) ? 0 : str_filter($_REQUEST['sort']),
        'title' => empty($_REQUEST['title']) ? 0 : str_filter($_REQUEST['title']),
        'addtime' => time(),
    );
    if($id<>""){
        if($db->update('wx_grade',$array,"id=$id"))
        {
            $res['state'] = 1;
            $res['msg'] = "恭喜修改成功!";
            die(json_encode($res));
        }
        else
        {
            $res['state'] = -1;
            $res['msg'] = "修改失败!";
            die(json_encode($res));
        }
    }else{
        $insids = $db->insert('wx_grade', $array);
        if ($insids <> "") {
            $res['state'] = 1;
            $res['msg'] = "恭喜添加成功!";
            die(json_encode($res));
        } else {
            $res['state'] = -1;
            $res['msg'] = "系统错误!";
            die(json_encode($res));
        }
    }
}

if ($act == 'comentadd') //课时费
{
    $id=$_REQUEST["id"];
    if (empty($_REQUEST['content'])) {
        $res['state'] = -1;
        $res['msg'] = "请输入内容";
        die(json_encode($res));
    }
    $array = array(
        'vipid' => empty($_REQUEST['vipid']) ? 0 : str_filter($_REQUEST['vipid']),
        'school_id' => empty($_REQUEST['school_id']) ? 0 : str_filter($_REQUEST['school_id']),
        'content' => empty($_REQUEST['content']) ? 0 : str_filter($_REQUEST['content']),
        'addtime' => time(),
        'status' => 1,
        'type' => 1
    ,
    );
    if($id<>""){
        if($db->update('wx_comment_list',$array,"id=$id"))
        {
            $res['state'] = 1;
            $res['msg'] = "恭喜修改成功!";
            die(json_encode($res));
        }
        else
        {
            $res['state'] = -1;
            $res['msg'] = "修改失败!";
            die(json_encode($res));
        }
    }else{
        $insids = $db->insert('wx_comment_list', $array);
        if ($insids <> "") {
            $res['state'] = 1;
            $res['msg'] = "恭喜添加成功!";
            die(json_encode($res));
        } else {
            $res['state'] = -1;
            $res['msg'] = "系统错误!";
            die(json_encode($res));
        }
    }
}

if ($act == 'homeadd') //课时费
{
    $id=$_REQUEST["id"];
    if (empty($_REQUEST['name'])) {
        $res['state'] = -1;
        $res['msg'] = "请输入时间";
        die(json_encode($res));
    }
    $array = array(
        'sort' => empty($_REQUEST['sort']) ? 0 : str_filter($_REQUEST['sort']),
        'money' => empty($_REQUEST['money']) ? 0 : str_filter($_REQUEST['money']),
        'school_id' => empty($_REQUEST['school_id']) ? 0 : str_filter($_REQUEST['school_id']),
        'name' => empty($_REQUEST['name']) ? 0 : str_filter($_REQUEST['name']),
        'addtime' => time(),
    );
    if($id<>""){
        if($db->update('wx_home_school',$array,"id=$id"))
        {
            $res['state'] = 1;
            $res['msg'] = "恭喜修改成功!";
            die(json_encode($res));
        }
        else
        {
            $res['state'] = -1;
            $res['msg'] = "修改失败!";
            die(json_encode($res));
        }
    }else{
        $insids = $db->insert('wx_home_school', $array);
        if ($insids <> "") {
            $res['state'] = 1;
            $res['msg'] = "恭喜添加成功!";
            die(json_encode($res));
        } else {
            $res['state'] = -1;
            $res['msg'] = "系统错误!";
            die(json_encode($res));
        }
    }
}

if ($act == 'houradd') //教师添加
{
    $id=$_REQUEST["id"];

    if (empty($_REQUEST['title'])) {
        $res['state'] = -1;
        $res['msg'] = "请输入课时";
        die(json_encode($res));
    }
    $array = array(
        'sort' => empty($_REQUEST['sort']) ? 0 : str_filter($_REQUEST['sort']),
        'title' => empty($_REQUEST['title']) ? 0 : str_filter($_REQUEST['title']),
        'addtime' => time(),
    );
    if($id<>""){
        if($db->update('wx_class_hour',$array,"id=$id"))
        {
            $res['state'] = 1;
            $res['msg'] = "恭喜修改成功!";
            die(json_encode($res));
        }
        else
        {
            $res['state'] = -1;
            $res['msg'] = "修改失败!";
            die(json_encode($res));
        }
    }else{
        $insids = $db->insert('wx_class_hour', $array);
        if ($insids <> "") {
            $res['state'] = 1;
            $res['msg'] = "恭喜添加成功!";
            die(json_encode($res));
        } else {
            $res['state'] = -1;
            $res['msg'] = "系统错误!";
            die(json_encode($res));
        }
    }
}

if ($act == 'couponadd') //优惠券添加
{
    $id=$_REQUEST["id"];

    if (empty($_REQUEST['title'])) {
        $res['state'] = -1;
        $res['msg'] = "请输入优惠券标题";
        die(json_encode($res));
    }
    if (empty($_REQUEST['types'])) {
        $res['state'] = -1;
        $res['msg'] = "请选择优惠券类型";
        die(json_encode($res));
    }
    if (empty($_REQUEST['moneys'])) {
        $res['state'] = -1;
        $res['msg'] = "请输入优惠券面值";
        die(json_encode($res));
    }
//    if (empty($_REQUEST['useRestrictions'])) {
//        $res['state'] = -1;
//        $res['msg'] = "请输入优惠券使用条件";
//        die(json_encode($res));
//    }
    if (empty($_REQUEST['maxCounts'])) {
        $res['state'] = -1;
        $res['msg'] = "请输入优惠券发行数量";
        die(json_encode($res));
    }
    if (empty($_REQUEST['sumdays'])) {
        $res['state'] = -1;
        $res['msg'] = "请输入优惠券有效天数";
        die(json_encode($res));
    }
    if (empty($_REQUEST['fromSid'])) {
        $res['state'] = -1;
        $res['msg'] = "请填写适用门店id";
        die(json_encode($res));
    }
    $array = array(
        'moneys' => empty($_REQUEST['moneys']) ? 0 : str_filter($_REQUEST['moneys']),
        'types' => empty($_REQUEST['types']) ? 0 : str_filter($_REQUEST['types']),
        'fromSid' => empty($_REQUEST['fromSid']) ? 0 : str_filter($_REQUEST['fromSid']),
        'title' => empty($_REQUEST['title']) ? 0 : str_filter($_REQUEST['title']),
        'useRestrictions' => empty($_REQUEST['useRestrictions']) ? 0 : str_filter($_REQUEST['useRestrictions']),
        'maxCounts' => empty($_REQUEST['maxCounts']) ? 0 : str_filter($_REQUEST['maxCounts']),
        'sumdays' => empty($_REQUEST['sumdays']) ? 0 : str_filter($_REQUEST['sumdays']),
        'commentSid' => empty($_REQUEST['commentSid']) ? 0 : str_filter($_REQUEST['commentSid']),
        'newMoney' => empty($_REQUEST['newMoney']) ? 0 : str_filter($_REQUEST['newMoney']),
        'addtime' => time(),
    );
    if($id<>""){
        if($db->update('wx_couponinfo',$array,"id=$id"))
        {
            $res['state'] = 1;
            $res['msg'] = "恭喜修改成功!";
            die(json_encode($res));
        }
        else
        {
            $res['state'] = -1;
            $res['msg'] = "修改失败!";
            die(json_encode($res));
        }
    }else{
        $insids = $db->insert('wx_couponinfo', $array);
        if ($insids <> "") {
            $res['state'] = 1;
            $res['msg'] = "恭喜添加成功!";
            die(json_encode($res));
        } else {
            $res['state'] = -1;
            $res['msg'] = "系统错误!";
            die(json_encode($res));
        }
    }
}

if ($act == 'priceadd') //教师添加
{
    $id=$_REQUEST["id"];

    if (empty($_REQUEST['hour_id'])) {
        $res['state'] = -1;
        $res['msg'] = "请选择课时！";
        die(json_encode($res));
    }
    if (empty($_REQUEST['class_id'])) {
        $res['state'] = -1;
        $res['msg'] = "请选择班型!";
        die(json_encode($res));
    }
    if (empty($_REQUEST['grade_id'])) {
        $res['state'] = -1;
        $res['msg'] = "请选择年级!";
        die(json_encode($res));
    }
    if (empty($_REQUEST['price'])) {
        $res['state'] = -1;
        $res['msg'] = "请填写价格!";
        die(json_encode($res));
    }
    $array = array(
        'school_id' => empty($_REQUEST['school_id']) ? 0 : str_filter($_REQUEST['school_id']),
        'hour_id' => empty($_REQUEST['hour_id']) ? 0 : str_filter($_REQUEST['hour_id']),
        'sumprice' => empty($_REQUEST['sumprice']) ? 0 : str_filter($_REQUEST['sumprice']),
        'class_id' => empty($_REQUEST['class_id']) ? 0 : str_filter($_REQUEST['class_id']),
        'grade_id' => empty($_REQUEST['grade_id']) ? 0 : str_filter($_REQUEST['grade_id']),
        'price' => empty($_REQUEST['price']) ? '' : str_filter($_REQUEST['price']),
        'discount' => empty($_REQUEST['discount']) ? '' : str_filter($_REQUEST['discount']),
        'addtime' => time(),
    );
    if($id<>""){
        if($db->update('wx_school_price',$array,"id=$id"))
        {
            $res['state'] = 1;
            $res['msg'] = "恭喜修改成功!";
            die(json_encode($res));
        }
        else
        {
            $res['state'] = -1;
            $res['msg'] = "修改失败!";
            die(json_encode($res));
        }
    }else{
        $insids = $db->insert('wx_school_price', $array);
        if ($insids <> "") {
            $res['state'] = 1;
            $res['msg'] = "恭喜添加成功!";
            die(json_encode($res));
        } else {
            $res['state'] = -1;
            $res['msg'] = "系统错误!";
            die(json_encode($res));
        }
    }
}
if ($act == 'teacheradd') //理发师添加
{
    $id=str_filter($_REQUEST["id"]);
    if (empty($_REQUEST['name'])) {
        $res['state'] = -1;
        $res['msg'] = "请输入理发师姓名";
        die(json_encode($res));
    }
    if (empty($_REQUEST['phone'])) {
        $res['state'] = -1;
        $res['msg'] = "请输入理发师联系方式!";
        die(json_encode($res));
    }
    $array = array(
        'name' => empty($_REQUEST['name']) ? 0 : str_filter($_REQUEST['name']),
        'money' => empty($_REQUEST['money']) ? 0 : str_filter($_REQUEST['money']),
        'old_money' => empty($_REQUEST['old_money']) ? 0 : str_filter($_REQUEST['old_money']),
        'S_id' => empty($_REQUEST['S_id']) ? 0 : str_filter($_REQUEST['S_id']),
        'first_Letter' => empty($_REQUEST['first_Letter']) ? 0 : str_filter($_REQUEST['first_Letter']),
        'sort' => empty($_REQUEST['sort']) ? 0 : str_filter($_REQUEST['sort']),
        'Label' => empty($_REQUEST['Label']) ? 0 : str_filter($_REQUEST['Label']),
        'score' => empty($_REQUEST['score']) ? 0 : str_filter($_REQUEST['score']),
        'head_img' => empty($_REQUEST['img']) ? 0 : str_filter($_REQUEST['img']),
        'vipid' => empty($_REQUEST['vipid']) ? 0 : str_filter($_REQUEST['vipid']),
        'uname' => empty($_REQUEST['uname']) ? 0 : str_filter($_REQUEST['uname']),
        'experience' => empty($_REQUEST['experience']) ? 0 : str_filter($_REQUEST['experience']),
        'works' => empty($_REQUEST['works']) ? 0 : str_filter($_REQUEST['works']),
        'pwd' => empty($_REQUEST['pwd']) ? 0 : md5($_REQUEST['pwd']),
        'addtime' => time(),
        'state' => 1,
    );
    if($id<>""){
        if($db->update('wx_barber',$array,"id=$id"))
        {
            $res['state'] = 1;
            $res['msg'] = "恭喜修改成功!";
            die(json_encode($res));
        }
        else
        {
            $res['state'] = -1;
            $res['msg'] = "修改失败!";
            die(json_encode($res));
        }
    }else{
        $insids = $db->insert('wx_barber', $array);
        if ($insids <> "") {
            $res['state'] = 1;
            $res['msg'] = "恭喜添加成功!";
            die(json_encode($res));
        } else {
            $res['state'] = -1;
            $res['msg'] = "系统错误!";
            die(json_encode($res));
        }
    }
}
if ($act == 'pricedel')
{
    $id=$_REQUEST["id"];
    if($db->query("DELETE FROM wx_school_price WHERE id=$id")){
        $res['state'] = 1;
        $res['msg'] = "恭喜删除成功!";
        die(json_encode($res));
    }else{
        $res['state'] = -1;
        $res['msg'] = "系统错误!";
        die(json_encode($res));
    }
}
if ($act == 'teacherdel')
{
    $id=$_REQUEST["id"];
    if($db->query("DELETE FROM wx_teacher_list WHERE id=$id")){
        $res['state'] = 1;
        $res['msg'] = "恭喜删除成功!";
        die(json_encode($res));
    }else{
        $res['state'] = -1;
        $res['msg'] = "系统错误!";
        die(json_encode($res));
    }
}
if ($act == 'advadd')
{
    $id=$_REQUEST["id"];

    if (empty($_REQUEST['titles'])) {
        $res['state'] = -1;
        $res['msg'] = "请输入标题";
        die(json_encode($res));
    }

    $array = array(
        'titles' => empty($_REQUEST['titles']) ? 0 : str_filter($_REQUEST['titles']),
        'img' => empty($_REQUEST['img']) ? '' : str_filter($_REQUEST['img']),
        'websize' => empty($_REQUEST['websize']) ? '' : str_filter($_REQUEST['websize']),
        'tip' => empty($_REQUEST['tip']) ? '' : str_filter($_REQUEST['tip']),
        'uid' => $uid,
        'status' => 1,
        'add_time' => time(),
    );
    if($id<>""){
        if($db->update('wx_shop_pcfocus',$array,"id=$id"))
        {
            $res['state'] = 1;
            $res['msg'] = "恭喜修改成功!";
            die(json_encode($res));
        }
        else
        {
            $res['state'] = -1;
            $res['msg'] = "修改失败!";
            die(json_encode($res));
        }
    }else{
        $insids = $db->insert('wx_shop_pcfocus', $array);
        if ($insids <> "") {
            $res['state'] = 1;
            $res['msg'] = "恭喜添加成功!";
            die(json_encode($res));
        } else {
            $res['state'] = -1;
            $res['msg'] = "系统错误!";
            die(json_encode($res));
        }
    }
}
if ($act == 'adv_status')
{
    $id=$_REQUEST["id"];
    $status=$_REQUEST["status"];
    if($db->query("update wx_shop_pcfocus set status=$status where id=$id")){
        $res['state'] = 1;
        $res['id'] = $id;
        $res['msg'] = "恭喜修改成功!";
        die(json_encode($res));
    }else{
        $res['state'] = -1;
        $res['msg'] = "系统错误!";
        die(json_encode($res));
    }
}

if ($act == 'bredel')
{
    $id=$_REQUEST["id"];
    if($db->query("DELETE FROM wx_barber WHERE id=$id")){
        $res['state'] = 1;
        $res['msg'] = "恭喜删除成功!";
        die(json_encode($res));
    }else{
        $res['state'] = -1;
        $res['msg'] = "系统错误!";
        die(json_encode($res));
    }
}

if ($act == 'advdel')
{
    $id=$_REQUEST["id"];
    if($db->query("DELETE FROM wx_shop_pcfocus WHERE id=$id")){
        $res['state'] = 1;
        $res['msg'] = "恭喜删除成功!";
        die(json_encode($res));
    }else{
        $res['state'] = -1;
        $res['msg'] = "系统错误!";
        die(json_encode($res));
    }
}

if ($act == 'advdelcome')
{
    $id=$_REQUEST["id"];
    if($db->query("DELETE FROM wx_order_comment WHERE id=$id")){
        $res['state'] = 1;
        $res['msg'] = "恭喜删除成功!";
        die(json_encode($res));
    }else{
        $res['state'] = -1;
        $res['msg'] = "系统错误!";
        die(json_encode($res));
    }
}

if ($act == 'advlistadd')
{
    $id=$_REQUEST["id"];

    if (empty($_REQUEST['titles'])) {
        $res['state'] = -1;
        $res['msg'] = "请输入标题";
        die(json_encode($res));
    }

    $array = array(
        'titles' => empty($_REQUEST['titles']) ? 0 : str_filter($_REQUEST['titles']),
        'cid' => empty($_REQUEST['cid']) ? 0 : str_filter($_REQUEST['cid']),
        'img' => empty($_REQUEST['img']) ? '' : str_filter($_REQUEST['img']),
        'websize' => empty($_REQUEST['websize']) ? '' : str_filter($_REQUEST['websize']),
        'tip' => empty($_REQUEST['tip']) ? '' : str_filter($_REQUEST['tip']),
        'uid' => $uid,
        'status' => 1,
        'add_time' => time(),
    );
    if($id<>""){
        if($db->update('wx_shop_pcfocus',$array,"id=$id"))
        {
            $res['state'] = 1;
            $res['msg'] = "恭喜修改成功!";
            die(json_encode($res));
        }
        else
        {
            $res['state'] = -1;
            $res['msg'] = "修改失败!";
            die(json_encode($res));
        }
    }else{
        $insids = $db->insert('wx_shop_pcfocus', $array);
        if ($insids <> "") {
            $res['state'] = 1;
            $res['msg'] = "恭喜添加成功!";
            die(json_encode($res));
        } else {
            $res['state'] = -1;
            $res['msg'] = "系统错误!";
            die(json_encode($res));
        }
    }
}
if ($act == 'advlist_status')
{
    $id=$_REQUEST["id"];
    $status=$_REQUEST["status"];
    if($db->query("update wx_shop_pcfocus set status=$status where id=$id")){
        $res['state'] = 1;
        $res['id'] = $id;
        $res['msg'] = "恭喜修改成功!";
        die(json_encode($res));
    }else{
        $res['state'] = -1;
        $res['msg'] = "系统错误!";
        die(json_encode($res));
    }
}
if ($act == 'advlistdel')
{
    $id=$_REQUEST["id"];
    if($db->query("DELETE FROM wx_shop_pcfocus WHERE id=$id")){
        $res['state'] = 1;
        $res['msg'] = "恭喜删除成功!";
        die(json_encode($res));
    }else{
        $res['state'] = -1;
        $res['msg'] = "系统错误!";
        die(json_encode($res));
    }
}
if ($act == 'courseadd')
{
    $id=$_REQUEST["id"];

    if (empty($_REQUEST['name'])) {
        $res['state'] = -1;
        $res['msg'] = "请输入课程名称";
        die(json_encode($res));
    }

    $array = array(
        'name' => empty($_REQUEST['name']) ? '' : str_filter($_REQUEST['name']),
        'head_img' => empty($_REQUEST['head_img']) ? '' : str_filter($_REQUEST['head_img']),
        'phone' => empty($_REQUEST['phone']) ? '' : str_filter($_REQUEST['phone']),
        'content' => empty($_REQUEST['content']) ? '' : str_filter($_REQUEST['content']),
        'address' => empty($_REQUEST['address']) ? '' : str_filter($_REQUEST['address']),
        'price' => empty($_REQUEST['price']) ? '' : str_filter($_REQUEST['price']),
        'teacher_id' => empty($_REQUEST['teacher_id']) ? '' : str_filter($_REQUEST['teacher_id']),
        'student_num' => empty($_REQUEST['student_num']) ? '' : str_filter($_REQUEST['student_num']),
        'orderList' => empty($_REQUEST['orderList']) ? 100 : str_filter($_REQUEST['orderList']),
        'status' => 1,
        'addtime' => time(),
    );
    if($id<>""){
        if($db->update('wx_course_list',$array,"id=$id"))
        {
            $res['state'] = 1;
            $res['msg'] = "恭喜修改成功!";
            die(json_encode($res));
        }
        else
        {
            $res['state'] = -1;
            $res['msg'] = "修改失败!";
            die(json_encode($res));
        }
    }else{
        $insids = $db->insert('wx_course_list', $array);
        if ($insids <> "") {
            $res['state'] = 1;
            $res['msg'] = "恭喜添加成功!";
            die(json_encode($res));
        } else {
            $res['state'] = -1;
            $res['msg'] = "系统错误!";
            die(json_encode($res));
        }
    }
}
if ($act == 'course_status')
{
    $id=$_REQUEST["id"];
    $status=$_REQUEST["status"];
    if($db->query("update wx_course_list set status=$status where id=$id")){
        $res['state'] = 1;
        $res['id'] = $id;
        $res['msg'] = "恭喜修改成功!";
        die(json_encode($res));
    }else{
        $res['state'] = -1;
        $res['msg'] = "系统错误!";
        die(json_encode($res));
    }
}
if ($act == 'coursedel')
{
    $id=$_REQUEST["id"];
    if($db->query("DELETE FROM wx_course_list WHERE id=$id")){
        $res['state'] = 1;
        $res['msg'] = "恭喜删除成功!";
        die(json_encode($res));
    }else{
        $res['state'] = -1;
        $res['msg'] = "系统错误!";
        die(json_encode($res));
    }
}
if ($act == 'comment_status')
{
    $id=$_REQUEST["id"];
    $status=$_REQUEST["status"];
    if($db->query("update wx_comment_list set status=$status where id=$id")){
        $res['state'] = 1;
        $res['id'] = $id;
        $res['msg'] = "恭喜修改成功!";
        die(json_encode($res));
    }else{
        $res['state'] = -1;
        $res['msg'] = "系统错误!";
        die(json_encode($res));
    }
}
if ($act == 'commentdel')
{
    $id=$_REQUEST["id"];
    if($db->query("DELETE FROM wx_comment_list WHERE id=$id")){
        $res['state'] = 1;
        $res['msg'] = "恭喜删除成功!";
        die(json_encode($res));
    }else{
        $res['state'] = -1;
        $res['msg'] = "系统错误!";
        die(json_encode($res));
    }
}
if($act == 'addmessage'){
    $id=$_REQUEST["id"];

    if (empty($_REQUEST['title'])) {
        $res['state'] = -1;
        $res['msg'] = "请输入标题名称";
        die(json_encode($res));
    }
    if (empty($_REQUEST['img'])) {
        $res['state'] = -1;
        $res['msg'] = "请上传缩略图";
        die(json_encode($res));
    }
    if (empty($_REQUEST['content'])) {
        $res['state'] = -1;
        $res['msg'] = "请输入内容";
        die(json_encode($res));
    }
    if (empty($_REQUEST['type'])) {
        $res['state'] = -1;
        $res['msg'] = "请选择发送对象";
        die(json_encode($res));
    }
    $array = array(
        'title' => empty($_REQUEST['title']) ? '' : str_filter($_REQUEST['title']),
        'title_show' => empty($_REQUEST['title_show']) ? '' : str_filter($_REQUEST['title_show']),
        'content' => empty($_REQUEST['content']) ? '' : str_filter($_REQUEST['content']),
        'type' => empty($_REQUEST['type']) ? '' : str_filter($_REQUEST['type']),
        'img' => empty($_REQUEST['img']) ? '' : str_filter($_REQUEST['img']),
        'time' => time(),
    );
    if($id<>""){
        if($db->update('wx_message',$array,"id=$id"))
        {
            $res['state'] = 1;
            $res['msg'] = "恭喜修改成功!";
            die(json_encode($res));
        }
        else
        {
            $res['state'] = -1;
            $res['msg'] = "修改失败!";
            die(json_encode($res));
        }
    }else{
        $insids = $db->insert('wx_message', $array);
        if ($insids <> "") {
            $res['state'] = 1;
            $res['msg'] = "恭喜添加成功!";
            die(json_encode($res));
        } else {
            $res['state'] = -1;
            $res['msg'] = "系统错误!";
            die(json_encode($res));
        }
    }
}
?>
