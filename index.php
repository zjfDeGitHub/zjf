<?php
require '../checkindex.php';
$act = $_GET['act'];
if($act == 'search'){
    $keyword = $_POST['keyword'];
}
if($act == 'agree'){
    $db->update("wx_card_vip",array("is_agree"=>1)," id=".$vipinfo['id']);
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?php echo $shopdata["wtitles"];?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0;">
    <script src="/js/jquery-1.7.2.min.js"></script>
    <link href="/layer/mobile/need/layer.css" rel="stylesheet">
    <script type="text/javascript"  src="/layer/layer.js"></script>
    <link charset="utf-8" rel="stylesheet" href="css/frozen.css?v=0.0.0.1">
    <link charset="utf-8" rel="stylesheet" href="css/app.css?v=1.1.7.5">
    <link charset="utf-8" rel="stylesheet" href="css/comm.css">
    <link charset="utf-8" rel="stylesheet" href="css/swiper.min.css">
    <link rel="stylesheet" href="font/demo.css">
    <link rel="stylesheet" href="font/iconfont.css">
    <style>
        .goods-box {
            background: #FFF;
            padding: 10px 0;
        }

        .swiper-container .swiper-slide a {
            display: inline-block;
        }

        .swiper-container .swiper-slide img {
            display: block;
            border: 0;
        }

        .swiper-container .swiper-slide span {
            display: block;
            border: 0;
            padding: 5px 10px;
            text-align: center;
        }


        #notice_list {
            display: block;
            padding: 0;
            margin: 0;
            padding: 10px 15px;
        }

        #notice_list li {
            list-style: none;
            height: 22px;
            line-height: 22px;
            overflow: hidden;
            border-bottom: 1px dashed #5098E3;
        }

        #notice_list li a {
            color: #000;
        }
    </style>


</head>
<body class="w-640">
<img id="user-avatar"
     src=""
     style="display:none;">
<div class="full" id="wrapper-top-bottom">
    <!--轮播图-->
    <link rel="stylesheet" href="/swiper/dist/css/swiper.min.css">
    <script src="/swiper/dist/js/swiper.min.js"></script>
    <div class="full" id="wrapper-top-bottom">
        <!--轮播图-->
        <div class="swiper-container">
            <div class="swiper-wrapper">
                <?php
                $pcfous=get_wx_shop_pcfocus_limit(1);
                foreach($pcfous as $rs) {
                    ?>
                    <div class="swiper-slide">
                        <a href="<?php echo $rs["websize"];?>">
                            <img src="<?php echo $rs["img"];?>" width="100%" height="auto">
                        </a>
                    </div>
                    <?php
                }
                ?>
            </div>
            <div class="swiper-pagination"></div>
        </div>
        <script>
            var swiper = new Swiper('.swiper-container', {
                autoplay: {
                    delay: 2500,
                    disableOnInteraction: false,
                },
                pagination: {
                    el: '.swiper-pagination',
                },
            });
        </script>
    <style type="text/css">
        img {
            pointer-events: none;
        }

        .gl_class {
            padding: 0 0 10px;;
        }

        .gl_w5 {
            width: 50%;
        }

        .gl_w5 img {
            width: 100%;
            border: 0;
            display: block;
        }

        .gl_class ul li {
            float: left;
            width: 20%;
            margin-left: 3.8%;
            text-align: center;
        }

        .gl_class ul li span {
            display: block;
            margin-top: 10px;
            height: 30px;
            line-height: 30px;
            color: #666;
            font-size: 14px;
        }

        .gl_class ul li img {
            border: 0;
            display: block;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            border: 2px solid #FF4200;
        }

        .gl_toutiao {
            padding: 3px 15px 0;
            line-height: 22px;
            font-family: Helvetica, STHeiti, "Microsoft YaHei", Verdana, Arial, Tahoma, sans-serif;
        }

        .gl_toutiao h3 {
            font-size: 14px;
            font-weight: bold;
            color: #434a54;
            width: 84px;
            color: #5098E3;
        }

        .gl_clearfix {
            *zoom: 1;
        }

        .gl_clearfix:before, .gl_clearfix:after {
            display: table;
            line-height: 0;
            content: "";
        }

        .gl_clearfix:after {
            clear: both;
        }

        .gl_fl {
            float: left;
        }

        .gl_fr {
            float: right;
        }

        /*@font-face {*/
            /*font-family: 'iconfont';  !* project id 294555 *!*/
            /*src: url('//at.alicdn.com/t/font_lkram1gex0k65hfr.eot');*/
            /*src: url('//at.alicdn.com/t/font_lkram1gex0k65hfr.eot?#iefix') format('embedded-opentype'),*/
            /*url('//at.alicdn.com/t/font_lkram1gex0k65hfr.woff') format('woff'),*/
            /*url('//at.alicdn.com/t/font_lkram1gex0k65hfr.ttf') format('truetype'),*/
            /*url('//at.alicdn.com/t/font_lkram1gex0k65hfr.svg#iconfont') format('svg');*/
        /*}*/

        /*@font-face {*/
            /*font-family: 'iconfont';  !* project id 304566 new_pro*!*/
            /*src: url('//at.alicdn.com/t/font_7urbs9tjoe09t3xr.eot');*/
            /*src: url('//at.alicdn.com/t/font_7urbs9tjoe09t3xr.eot?#iefix') format('embedded-opentype'),*/
            /*url('//at.alicdn.com/t/font_7urbs9tjoe09t3xr.woff') format('woff'),*/
            /*url('//at.alicdn.com/t/font_7urbs9tjoe09t3xr.ttf') format('truetype'),*/
            /*url('//at.alicdn.com/t/font_7urbs9tjoe09t3xr.svg#iconfont') format('svg');*/
        /*}*/

        /*.iconfont {*/
            /*font-family: "iconfont" !important;*/
            /*font-size: 16px;*/
            /*font-style: normal;*/
            /*-webkit-font-smoothing: antialiased;*/
            /*-moz-osx-font-smoothing: grayscale;*/
        /*}*/

        .gl_toutiao_a {
            display: block;
            font-size: 12px;
            color: #666;
        }

        .gl_toutiao_a i {
            font-size: 20px;
        }

        /*.gl_toutiao_a span{ color: #008620; display: inline-block; height: 28px; padding:0 10px; border:1px solid #008620; border-radius: 3px;}*/
        .gl_font {
            font-family: Helvetica, STHeiti, "Microsoft YaHei", Verdana, Arial, Tahoma, sans-serif;
        }

        .gl_vip {
            display: inline-block;
            color: #F43D30;
            border: 1px solid #F43D30;
            border-radius: 3px;
            padding: 0 3px;
            height: 18px;
            line-height: 18px;
            vertical-align: middle;
        }

        #marquee-box {
            /*padding: 0 21px 0 21px;*/
            position: relative;
        }

        .qimo8, #marquee-con {
            overflow: hidden;
        }

        .qimo8 .qimo { /*width:99999999px;*/
            width: 8000%;
        }

        .qimo8 .qimo div {
            float: left;
        }

        .qimo8 .qimo ul {
            float: left;
            height: 20px;
            overflow: hidden;
            zoom: 1;
            font-size: 13px;
        }

        .qimo8 .qimo ul li {
            float: left;
            line-height: 20px;
            list-style: none;
            color: #5098E3;
        }

        .laba {
            position: absolute;
            left: 3px;
            top: 0px;
            color: #5098E3;
        }

        .fenlei {
            width: 100%;
            border-collapse: collapse;
        }

        .fenlei td {
            border: 1px solid #ccc;
        }

        .fenlei img {
            width: 40px;
            margin: 10px;
            vertical-align: middle;
        }

        .fenlei span {
            vertical-align: middle;
            font-size: 16px;
            color: #333 !important;
        }
    </style>
    <div class="descover_search_top gl_clearfix">
        <form id="search-result" method="post" action="?act=search">
        <div class="input_wp gl_fl" style="width: 80%;"
            <i class="icon iconfont icon-sousuo"></i>
            <input style="width: 100%" name="keyword" placeholder="请输入拍品、商家进行搜索">
        </div>
        </form>
        <div style="text-align: center; line-height: 25px;" id="search">搜索
        </div>
    </div>
    <script>
        $(function () {
            $("#search").click(function () {
                $("#search-result").submit();
            })
        })
    </script>
    <style>
           //跑马灯样式
            .tips_marquee_div2{
                height:80px;
                overflow:hidden; //隐藏滚动条
                margin-right: 20px;
            }
          .left2{
              height: 30px;
              display: inline-block;
              padding-left:20px;
              padding-right:20px;
              float:left;
          }
        .tips_marquee_msg2 {
            font-size: 12px;
            color:#5098E3;
            display: inline-block;
            width:80%;
            white-space: nowrap;
            word-wrap: normal;
            animation: marquee2 30s linear infinite;
        }
            @keyframes marquee2 {
                0% {
                    transform: translateX(100%);
                }
                100% {
                    transform: translateX(-180%);
                }
            }
    </style>
    <div id="marquee-box" class="qimo8">
        <i class="iconfont icon-gonggao laba" ></i>
        <div id="marquee-con">
            <div class="qimo">
                <div id="demo1">
                    <div class="tips_marquee2">
                        <div class="left2">
                            <div class="tips_marquee_div2">
                                <p  class="tips_marquee_msg2">注意：任何第三方机构或个人，以提额名义索要账号密码的，都是骗子，请勿透漏！</p>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
        <input type="hidden" value="<?php echo $vipinfo['phone'] ?>" id="phone">
        <input type="hidden" value="<?php echo $vipinfo['utype'] ?>" id="utype">
    <script type="text/javascript">
        var demo = document.getElementById("marquee-con");
        var demo1 = document.getElementById("demo1");
        var demo2 = document.getElementById("demo2");
        demo2.innerHTML = document.getElementById("demo1").innerHTML;

        function Marquee() {
            if (demo.scrollLeft - demo2.offsetWidth >= 0) {
                demo.scrollLeft -= demo1.offsetWidth;
            }
            else {
                demo.scrollLeft++;
            }
        }

        var myvar = setInterval(Marquee, 30);
        //				demo.onmouseout=function (){myvar=setInterval(Marquee,30);}
        //				demo.onmouseover=function(){clearInterval(myvar);}
    </script>

    <div class="gl_class">
        <table class="fenlei" cellpadding="0" cellspacing="0" border="0">
            <tbody>
            <!--循环后台分类，没两个分一组-->
            <?php
                 $shopcat=get_Shop_Cat_ListArry();
                 if($shopcat) {
                     $i=0;
                     foreach($shopcat as $rs){
                         if($i%2==0 && $i<count($shopcat)){ echo "<tr>";}
                         ?>
                             <td>
                                 <a href="?pid=<?php echo $rs["id"];?>">
                                     <img src="<?php echo $rs["img"];?>" width=""><span><?php echo $rs["cattitle"];?></span>
                                 </a>
                             </td>
            <?php
                         if($i%2>0 && $i!=0 && $i<count($shopcat)){ echo "</tr>";}
                         $i++;
                     }
                 }
            ?>
            </tbody>
        </table>
    </div>
    <div>
        <div class="gl_toutiao gl_clearfix">
            <h3 class="gl_fl gl_font">每日速递</h3>
            <div id="timer"></div>
            <a href="newslist.php" target="_top" class="gl_fr gl_toutiao_a">查看全部<i class="icon iconfont" style="font-size: 12px; margin-left: 5px;">&#xe727;</i>
            </a>
        </div>
        <ul id="notice_list">
            <?php
                  $newslist=cat_get_newlist(0,5);
                  if($newslist) {
                      foreach($newslist as $rs){
                          ?>
                          <li data-id="18"><a href="news_details.php?id=<?php echo $rs["id"];?>">
                                  <i class="icon iconfont" style="font-size: 14px; margin-right: 10px; color: #666;">&#xe790;</i>
                                  <?php echo $rs["title"]; ?>
                              </a>
                          </li>
                          <?php
                      }
                  }
            ?>
        </ul>
    </div>
    <div class="gl_toutiao gl_clearfix" style=" border-bottom: 1px solid #ccc; margin-bottom: 10px;">
        <h3 class="gl_fl gl_font">今日推荐</h3>
        <a href="" target="_top" class="gl_fr gl_toutiao_a">查看全部<i class="icon iconfont" style="font-size: 12px; margin-left: 5px;">&#xe727;</i></a>
    </div>
    <div class="jp-box goods-box" style="background:none;padding:0;">


    </div>
    <div style="text-align: center; margin-top: 30px;color: #999;font-size: 14px;">
        <span>温馨提示：</span>首页只推荐<span style="color: #F43D30;">20</span>件商品
    </div>
</div>

<div class="mask"></div>

<div class="share-mask"></div>

<div class="bottom-menu follow-menu" id="upinfo">
    <div class="full">
        <div class="ui-btn-wrap">
            <p>必须要先进行会员认证等待后台审核才能出价</p>
            <a href="upVip.php" class="ui-btn-lg ui-btn-green loading" data-uid="0">
                会员认证
            </a>
            <button class="ui-btn-lg hide-follow">
                取消
            </button>
        </div>
    </div>
</div>

<!--验证手机-->
<div class="bottom-menu mobile-validate">
    <div class="full">
        <div class="ui-btn-wrap">
            <p>必须先验证手机号才能够出价</p>
            <div class="ui-form ui-border-t" style="margin-bottom:10px;">
                <form action="#">
                    <div class="ui-form-item ui-form-item-pure ui-border-b">
                        <input id="mobile" name="mobile" type="text" size="25" class="inputBg" placeholder="请输入手机号码">
                    </div>
                    <div class="ui-form-item ui-form-item-r ui-border-b">
                        <input type="text" placeholder="请输入短信验证码" id="code">
                        <!-- 若按钮不可点击则添加 disabled 类 -->
                        <input id="zphone" class="ui-border-l loading gl_button_0525"
                               style="width: auto; padding: 0; text-align: center; color: #F43D30;" type="button"
                               value=" 点击发送 " onclick="get_mobile_code();">
                    </div>
                </form>
            </div>

            <a href="" class="ui-btn-lg ui-btn-green loading" id="save-mobile">
                保存
            </a>
            <!--
            <button class="ui-btn-lg hide-follow">
                取消
            </button>
        -->
        </div>
    </div>
</div>

<div class="auction-box">
    <div class="auction full">
        <h2 class="title">领先价<span class="money-after">0元</span></h2>
        <div class="ui-form-item" id="price-input-item">
            <label>出价</label>
            <input type="tel" id="auction-money" placeholder="请输入您的出价">
            <span class="tips">一口价立即成交!</span>
        </div>
        <div class="money-btns">
            <div class="left">
                <button class="ui-btn-lg ui-btn-primary" id="one-price">一口价￥200</button>
            </div>
            <div class="right">
                <button class="ui-btn-lg ui-btn-green loading" id="auction-confirm-btn"
                        data-url="">出价
                </button>
            </div>
        </div>
    </div>
</div>

<?php
/*$vipinfo['is_agree'] = $db->getOne("select is_agree from wx_card_vip where id = ".$vipinfo['id']);
if($vipinfo['is_agree']==2){
    */?><!-- <div class="bg_back"></div><div class="popup"><p>听雨文化VIP会员协议书</p><hr><div style="height: 16rem;overflow: auto;">&nbsp;&nbsp;&nbsp;&nbsp;下述甲（入驻客户）、乙（听雨文化）双方，经友好协商一致，就甲方加入听雨文化VIP会员事宜达成以下协议。双方申明，双方都已理解并认可了本协议的所有内容，同意承担各自应承担的权利和义务，忠实地履行本协议。<br>
            本公司宗旨：公司为服务性质公司，不承诺成交，不参与藏品定价，估价。通过对藏品宣传极力促成成交，协助完成成交交接，收取服务费用及成交佣金。
            <br>
            第一条 甲方自愿付费加入乙方网站(听雨文化)的VIP会员，享受听雨文化的VIP会员待遇。加入VIP会员的工作流程与安排、价款、交付和验收方式等由附件载明。
<br>
            第二条	双方的基本权利和基本义务
            <br>
            2-1甲方的基本权利和基本义务
            <br>
            2-1-1甲方承诺，在听雨文化发布的内容、资料等不会侵犯任何第三方的权利，若发生侵犯第三方权利的情形，由甲方承担全部责任。因甲方使用本协议标的给第三人造成损害的，由甲方自行承担责任。
            <br>
            2-1-2甲方承诺，在听雨文化发布的内容真实可靠，符合国家法律规定和社会公共利益，特别地，应当严格遵守《计算机信息网络国际联网安全保护管理办法》《中华人民共和国计算机信息网络国际联网管理暂行规定》《中华人民共和国计算机信息系统安全保护条例》《中华人民共和国电信条例》《全国人大常委会关于维护互联网安全的决定》、《互联网信息服务管理办法》、《互联网电子公告服务管理规定》、《互联网站从事登载新闻业务管理暂行规定》、《互联网等信息网络传播视听节目管理办法》、《互联网文化管理暂行规定》和国家其他有关法律、法规、行政规章，不得利用网站制作、复制、发布、传播任何法律法规禁止的有害信息。如果甲方利用本协议服务进行的经营活动需要获得国家有关部门的认可或批准的，甲方应获得该有关的认可或批准。甲方应对违反本条规定所引起的问题及产生的影响、后果承担全部责任。
            <br>
            2-1-3按本协议约定按时支付费用，否则乙方可以单方面解除协议且不需通知甲方。
            <br>
            2-1-4甲方应确保联系人员为提交资料本人，如有特殊情况应书面提交给乙方平台，经过乙方平台许可，确立合作关系。
            <br>
            2-2 乙方的基本权利和基本义务
            <br>
            2-2-1按照本协议约定赋予甲方在听雨文化的VIP权限，具体工作详见协议附件；
            <br>
            2-2-2按照本协议规定收取费用；
            <br>
            2-2-3可以根据甲方的要求对甲方发布产品信息进行技术指导，以便获得更好的推广效果，但不保证100%成交。
            <br>
            2-2-4乙方将派客服人员负责与甲方联络、协调，乙方指派的联系人为听雨文化客服人员，客服人员不为甲方产品估价以及定价，客服人员为甲方参考的价位为第三方平台提供与乙方无关，乙方不承担任何责任。
            <br>
            第三条	双方应当保守在履行本协议过程中获知的对方商业秘密。本协议的终止、撤消、无效不应影响本条款约定的效力。
            <br>
            第四条	协议的变更与解除
            <br>
            4-1协议履行过程中，任何一方欲对协议期限、项目内容、工作进度、费用等协议内容或条款进行变更或补充的，应与对方协商一致并签定补充协议进行确定。否则，视为未作变更或补充，双方仍应按照原协议的约定履行。
            <br>
            4-2协议履行过程中，如甲方欲提前解除协议的，应提前  7 日通知乙方。甲方提前解除协议的，已支付的费用不予退还。如届时甲方有已确认的工作成果所对应的费用未支付的，甲方还应于协议解除后的  3 日内将应付费用支付给乙方。如因甲方擅自解除协议而给乙方造成其他经济损失的，甲方还应据实赔偿。
            <br>
            4-3如乙方因自身原因需提前解除协议的，应提前  3 日通知甲方，并返还甲方所支付的费用。
            <br>
            4-4任何一方在履行中发现或者有证据表明对方已经、正在或将要违约，可以中止履行本协议，但应及时通知对方。若对方继续不履行、履行不当或者违反本协议，乙方可以解除本协议并要求对方赔偿损失。
            <br>
            4-5无论因何原因导致的协议解除或终止，对于甲方已确认的工作成果所对应的费用，乙方均不予退还（如甲方未支付的，还应支付）。但因乙方自身原因提前解除协议的除外。
            <br>
            第五条	不可抗力及责任承担
            <br>
            5-1如果出现不可抗力，双方在本协议中的义务在不可抗力影响范围及其持续期间内将中止履行。合作期限可根据中止的期限而作相应延长，但须双方协商一致。任何一方均不会因此而承担责任。
            <br>
            5-2 声称遭受不可抗力的一方应在不可抗力发生后不迟于十五(15)日通知另一方，并提供经有关部门确认的不可抗力书面证明，且应尽可能减少不可抗力所产生之影响。
            <br>
            5-3如果不可抗力持续三十(30)日以上，且对本协议之履行产生重大不利影响，则任何一方均可解除本协议。因不可抗力导致的协议解除，甲方已支付的费用不予退还。
            <br>
            5-4甲方不能按时支付协议费用的，每延期一天，应支付协议总金额的 5 ‰作为滞纳金，同时乙方还有权中止履行本协议，因此导致的工期延误等后果，责任由甲方自行承担，与乙方无关。
            <br>
            5-5任何一方违反本协议给对方造成损失的，应赔偿损失。在本协议其他条款对违约有具体约定时，从其约定。
            <br>
            第六条	争议解决
            <br>
            6-1 双方当事人对本协议的订立、解释、履行、效力等发生争议的，应友好协商解决；协商不成的，双方同意向乙方所在地南昌市人民法院起诉。
            <br>
            6-2本协议的终止、撤消、无效不应影响前款约定的效力。
            <br>
            第七条	其他
            <br>
            7-1一方变更联系人、通讯地址或者联系方式的，应及时将变更后的联系人、通讯地址或者联系方式通知另一方，否则变更方应对此造成的一切后果承担责任。
            <br>
            7-2本协议的订立、解释、履行、效力和争议的解决等均适用中华人民共和国法律。对本协议的理解与解释应根据原意并结合本协议目的进行。
            <br>
            7-3如果本协议任何条款根据现行法律被确定为无效或无法实施，本协议的其他所有条款将继续有效。此种情况下，双方将以有效的约定替换该约定，且该有效约定应尽可能接近原约定和本协议相应的精神和宗旨。
            <br>
            7-4本协议附件为本协议不可分割的一部分，与协议正文具有同等法律效力，如与本协议内容有不同之处，则以本协议的相关规定为准。
            <br>
            7-5本协议经甲方点击我愿意提交，提交当日起生效。生效同时具有同等法律效力。
            <br>
            7-6甲方应按照乙方的指导认真发布信息，乙方保证甲方部分信息在甲方自选的网站搜索页能够搜寻查看，否则乙方重新审核继续服务或退还相应款项。
            <br>
            听雨文化员工职务准则
            <br>
            1、在与藏友沟通当中，以服务客户为主要沟通方式，不得存在诱导性语言，不得给到客户承诺，不参与客户对藏品的定价，客户问到藏品价格，员工可协助客户查照第三方平台（例如雅昌艺术网等）给到客户参考价格。如存在以上违规情节，公司概不负责，所有责任由对接客服个人承担。
            <br>
            2、客服人员不得以任何名义和任何形式收取客户的红包，与客户所有资金往来必须以对公账户形式交易（如若客户存在不会使用对公账户支付费用，要求客服人员协商代缴费用，代缴费必须申报部门经理，经理同意方可代缴）。
            <br>
            3、公司以不断开发买家为生存根本，所有买家通过平台购买藏品，客户经理应如实回答，不得为了成交进行欺骗行为。
            <br>
            4、客户一旦加入本公司会员体系，对接客服人员必须在三个工作日内将客户藏品发给本公司网运部，并督促网运部在接收当天对客户藏品进行包装宣传，所宣传内容必须及时回馈给到客户。
            <br>
            5、对接客服人员要做到积极与客户沟通，协助客户了解本公司运营平台，如对接客服存在不接电话，微信拉黑等情况的，客户有正当理由投诉的，公司将视情况对客服人员予以处罚。
            <br>
            6、不得以买家购买藏品的方式邀约卖家客户入住平台。
            <br>
            如在入驻过程中发现对接客服有违背上述行为，可通过平台申诉渠道协商处理


            附件一：听雨文化VIP会员加入流程与安排

            一、加入VIP会员的好处
            1、可以提高发布信息在互联网的诚信度，获得更多的订单。
            2、甲方根据相应的会员等级发布相应数量的产品信息。
            3、乙方指导甲方发布产品信息，可在平台中获得更好的交易机会。
            4、可以定期参与平台推出的营销活动，免费获得更多推广机会。
            5、在发布产品、获得推广数目或其他项目中获得较大的价格优惠。

            二、协议金额、付款方式及会员有效期
            1、本协议金额总计： 银牌会员：998元。
            金牌会员：2998元。
            钻石会员：4998元
            私人订制：9999元。
            2、付款方式：
            甲方一次性向乙方支付相应等级会员全部协议金额。
            3、自协议生效后，VIP会员推广项目有效服务为一年，若甲方在协议到期前3天内续费，可以继续享受VIP会员推广待遇，不需另外提交本协议。
            三、工作流程：
            1甲方自行注册、登陆并发布信息。甲方提交产品正反两面清晰图片各一张；实名注册审核298元；

            2乙方应在收到甲方付款后 1 日内为甲方开通VIP权限，

            3乙方优先审核甲方发布的信息，并对甲方发布信息的操作和技巧进行指导，以获得更好的推广效果。
            4若甲方不熟悉网站操作，可由乙方代为注册、发布（需要另外邮寄委托书至乙方办公地点）。甲方应协助乙方提供相关资料信息。

            5乙方根据相应的会员等级在不同平台对甲方产品进行推广，如有意向客户购买甲方产品，乙方负责沟通。

            6如甲方产品在乙方平台确认交易，确定交易细节后，公司安排买卖双方见面，根据需要看是到公司直接交易，还是对接买卖双方当地直接交易（要现金提前3个工作日以上预约），成交后与本公司结算佣金，包括你个人所得税在内。

            注：所有来往款项请认准听雨文化财务账户。</div><div class="close" style="border:none "><a href="/serviceNotice.doc" style="width: 100%;">点击下载协议书</a></div><div class="close"><a href="?act=agree" style="width: 49%;">同意</a><a class="popup-close" style="width: 49%;">拒绝</a></div></div>

    --><?php
/*}
*/?>
<!-- /主体 -->


<!-- 底部 -->


<style>
    .wx_subscribe {
        position: fixed;
        left: 0;
        bottom: 54px;
        width: 100%;
        height: 50px;
        line-height: 50px;
        padding: 0 15px;
        background: rgba(0, 0, 0, 0.5);
        box-sizing: border-box;
    }

    .wx_subscribe img {
        float: left;
        width: 50px;
        height: 50px;
        border-radius: 5px;
        margin-top: -10px;
    }

    .wx_subscribe span {
        float: right;
        height: 30px;
        line-height: 30px;
        margin-top: 10px;
        padding: 0 15px;
        background: #D01C0E;
        color: #fff;
        border-radius: 5px;
    }

    .wx_subscribe p {
        font-size: 14px;
        color: #fff;
        margin-left: 60px;
    }

    .wx_subscribe_img {
        display: none;
        position: absolute;
        width: 100%;
        height: auto;
        left: 0;
        top: 0;
        text-align: center;
        z-index: 99;
    }

    .wx_subscribe_img img {
        max-width: 80%;
        margin-top: 50px;
    }

    .wx_subscribe_color {
        color: #5098E3 !important;
    }

    /*@font-face {*/
        /*font-family: 'iconfont';  !* project id 294555 *!*/
        /*src: url('//at.alicdn.com/t/font_8r5zdowxwoob6gvi.eot');*/
        /*src: url('//at.alicdn.com/t/font_8r5zdowxwoob6gvi.eot?#iefix') format('embedded-opentype'),*/
        /*url('//at.alicdn.com/t/font_8r5zdowxwoob6gvi.woff') format('woff'),*/
        /*url('//at.alicdn.com/t/font_8r5zdowxwoob6gvi.ttf') format('truetype'),*/
        /*url('//at.alicdn.com/t/font_8r5zdowxwoob6gvi.svg#iconfont') format('svg');*/
    /*}*/

    .gl_top {
        display: none;
        position: fixed;
        right: 2.5rem;
        bottom: 6rem;
        text-align: center;
        line-height: 35px;
        box-shadow: 0px 2px 5px #ccc;
        border-radius: 100%;
        z-index: 999;
        width: 35px;
        height: 35px;
        border-radius: 100%;
        background-color: rgba(255, 255, 255, .818);
        overflow: hidden;
        color: #999;
    }
</style>
<?php
require 'foot.php';
?>
<div class="gl_top">
    <i class="icon iconfont" style="font-size: 24px;">&#xe659;</i>
</div>
<script type="text/javascript">
    $(window).scroll(function () {
        if ($(this).scrollTop() > 100) {
            $(".gl_top").fadeIn();
            $(".descover_search_top").addClass('bg');
            $(".descover_search_top .input_wp").addClass("bg");
        } else {
            $(".gl_top").fadeOut();
            $(".descover_search_top").removeClass('bg');
            $(".descover_search_top .input_wp").removeClass("bg");
        }
    })
    $(".gl_top").click(function () {
        $('body,html').animate({"scrollTop": 0}, 50);
    })
</script>
<script type="text/javascript">

    $(document).ready(function(){
        var isfist = true;             //距下边界长度/单位px
        var range = 0;             //距下边界长度/单位px
        var _srollPos = 0;
        var pageNum = 0;
        var totalheight = 0;
        var main = $(".jp-box");
        var js = 0;
        if(isfist){
            getinfo(); //获取信息
        }
        $(window).scroll(function(){
            var srollPos = $(window).scrollTop();    //滚动条距顶部距离(页面超出窗口的高度)
            if(srollPos<_srollPos){
                return;
            }else{
                _srollPos = srollPos;
            }
            totalheight = parseFloat($(window).height()) + parseFloat(srollPos);
            if(js==0 && ($(document).height()-range) <= totalheight) {
                getinfo(); //获取信息
            }
        });

        function getinfo(){

            layer.load();
            pageNum ++;
            isfist=false;
            $.ajax({
                url: "../ajaxget.php",
                type:"GET",
                dataType: "JSON",
                cache: "false",
                data: {
                    page: pageNum ,
                    act:'getgoodlist_by_cid_json',
                    keyword:'<?php echo $keyword ?>',
                    id:'<?php $id = $_GET['pid']; echo $id ?>',
                    orders:'<?php echo $orders; ?>',
                },
                
                success: function(res){
                    console.log(res);
                    if(res.status=="isend"){
                        if(js == 0){
                            if(pageNum==1){
                                $('#divlist_article').append('<article style="display:block" id="noMore"><header style="margin:7px;text-align:center;color:#a77746;">该分类下还没有宝贝哦</header></article>');
                            }else{
                                $('#divlist_article').append('<article style="display:block" id="noMore"><header style="margin:7px;text-align:center;color:#a77746;">没有更多宝贝了</header></article>');
                            }
                        }
                        js = 1;
                    }else{
                        var htmlss="";
                        //var jsonobj=html;
                        for(var i=0;i<res.length;i++){
                            oProduct=res[i];
                            var arrLength = '';
                            var show = '';
                            var imgArr = oProduct.goodinfo.phontos
                            var reg = new RegExp('"',"g");
                             imgArr = imgArr.replace("[","");
                             imgArr = imgArr.replace("]","");
                             imgArr = imgArr.replace(reg,"");
                            imgArr=imgArr.split(",");
                            if(imgArr.length>3){
                                arrLength = 3;
                            }else{
                                arrLength = imgArr.length;
                            }
                            for(var u=0;u<arrLength;u++){
                                show = show+'<a href="details.php?id='+oProduct.goodinfo.id+'" class="preview"><img src="'+imgArr[u]+'"></a>'
                            }
                            //转换时间
                            var date = new Date(oProduct.goodinfo.addtime*1000);//如果date为13位不需要乘1000
                            var Y = date.getFullYear() + '-';
                            var M = (date.getMonth()+1 < 10 ? '0'+(date.getMonth()+1) : date.getMonth()+1) + '-';
                            var D = (date.getDate() < 10 ? '0' + (date.getDate()) : date.getDate()) + ' ';
                            var h = (date.getHours() < 10 ? '0' + date.getHours() : date.getHours()) + ':';
                            var m = (date.getMinutes() <10 ? '0' + date.getMinutes() : date.getMinutes()) + ':';
                            var s = (date.getSeconds() <10 ? '0' + date.getSeconds() : date.getSeconds());
                            var shijian = Y+M+D+h+m+s;
                            //转换时间

                            //倒计时
                            var str = (new Date()).getTime().toString();//获取当前时间戳
                            var date1 = str.substring(0,str.length-3);//去掉毫秒
                            var date2 = oProduct.goodinfo.endtime-date1;
                            function onChange(timeStamp)
                                var day = Math.floor(timeStamp/24/60/60);
                                timeStamp = timeStamp-(day*24*60*60);
                                var hour = Math.floor(timeStamp/60/60);
                                timeStamp = timeStamp-(hour*60*60);
                                var minute = Math.floor(timeStamp/60);
                                timeStamp = timeStamp-(minute*60);
                                var second = Math.floor(timeStamp);
                                var result = day+'天'+hour+'时'+minute+'分'+second+'秒';
                                return result;
                            }
                            var utypeV = '';
                            switch(oProduct.vipinfo.utype){
                                case '0':
                                    utypeV = '游客';
                                    break;
                                case '1':
                                    utypeV = '认证';
                                    break;
                                case '2':
                                    utypeV = '银牌';
                                    break;
                                case '3':
                                    utypeV = '金牌';
                                    break;
                                case '4':
                                    utypeV = '钻石';
                                    break;
                                case '5':
                                    utypeV = '定制';
                                    break;
                            }
                            //倒计时
//<div class="users clear" id="users-'+oProduct.goodinfo.id+'">'+oProduct.praisePer+'</div>点赞头像位置
                            htmlss=htmlss+'<div class="goods-item"><div class="left"><a href=""><img class="seller-avatar loading" src="'+oProduct.vipinfo.head_img+'"></a></div><div class="right"><a class="send_message" style="color:#00a5e0" href="">关注</a><h2 class="goods-title"><span class="gl_vip">'+utypeV+'</span><!--<span class="gl_vip"><i style="font-style:italic; font-size: 16px;">V</i>2</span>--><a href=""><span style="color:#576B95;font-size:16px;font-weight:bold;vertical-align:top;">'+oProduct.vipinfo.uname+'</span></a></h2><div class="goods-content">'+oProduct.goodinfo.shopname+'</div><div class="picture-box clear" onclick="" style="overflow:hidden; max-height:174px">'+show+'</div><div class="meta"><div class="left"><span class="date">'+shijian+'</span></div><div class="right"><span><i class="icon iconfont" style="vertical-align: middle; margin-right: 3px;">&#xe693;</i><font style="vertical-align: middle;">'+oProduct.goodinfo.see_num+'</font></span><span style="margin-left:0px;padding-left:0px;" class="praise"><i class="icon iconfont" data-id="'+oProduct.goodinfo.id+'">&#xe681;</i><font class="praiseNum" id=praise'+oProduct.goodinfo.id+'>'+oProduct.goodinfo.praise+'</font></span></div></div><!--点赞头像位置--><div class="goods-status"><div class="status">上架中</div><div class="time-remain" style="padding-left:0px;margin-left:0px;">距离结束：<span class="time-str">'+onChange(date2)+'</span><input style="display: none" id="endTime" value="'+oProduct.goodinfo.endtime+'"></div></div><button class="ui-btn-lg ui-btn-primary auction-btn" data-id="'+oProduct.goodinfo.id+'" style="margin:10px 0;">我要竞投</button><div class="meta-box clear"><div class="meta-border"><p><a href="#"><span>起</span>￥'+oProduct.goodinfo.fixed_price+'</a></p><p><a href="#"><span>加</span>￥'+oProduct.goodinfo.price_up+'</a></p><p><a href="#"><span>保</span>￥'+oProduct.goodinfo.floor_price+'</a></p></div></div><div class="user-box clear" id="user-box'+oProduct.goodinfo.id+'">'+oProduct.bidUser+'</div></div></div><div class="hr-line" style="width=100%;height:1px;margin:4px 10px 5px 50px;border-top:1px solid #eaeae8;box-sizing:border-box;"></div>';
                            // alert(jsonobj[i].id);
                        }
                        main.append(htmlss);
                        //main.append(res);
                    }
                    layer.closeAll('loading');
                    var endTime,str,date1,date2,day,timeStamp,hour,minute,second,result;
                    loopFun();
                    function loopFun(){
                        $(".time-remain").each(function () {
                            endTime = $(this).find("#endTime").val();
                            str = (new Date()).getTime().toString();
                            date1 = str.substring(0,str.length-3);
                            date2 = endTime-date1;
                            day = Math.floor(date2/24/60/60);
                            timeStamp = date2-(day*24*60*60);
                            hour = Math.floor(timeStamp/60/60);
                            timeStamp = timeStamp-(hour*60*60);
                            minute = Math.floor(timeStamp/60);
                            timeStamp = timeStamp-(minute*60);
                            second = Math.floor(timeStamp);
                            result = day+'天'+hour+'时'+minute+'分'+second+'秒';
                            $(this).find(".time-str").html(result);
                        })
                        setTimeout(loopFun,1000);
                    }
                    function praiseFun(){
                        $(".praiseNum").each(function () {
                            var endTime = $(this).find("#endTime").val();
                            var str = (new Date()).getTime().toString();
                            var date1 = str.substring(0,str.length-3);
                            var date2 = endTime-date1;
                            var day = Math.floor(date2/24/60/60);
                            var timeStamp = date2-(day*24*60*60);
                            var hour = Math.floor(timeStamp/60/60);
                            timeStamp = timeStamp-(hour*60*60);
                            var minute = Math.floor(timeStamp/60);
                            timeStamp = timeStamp-(minute*60);
                            var second = Math.floor(timeStamp);
                            var result = day+'天'+hour+'时'+minute+'分'+second+'秒';
                            $(this).find(".time-str").html(result);
                            setTimeout(loopFun,1000);
                        })
                    }
                    // if(pageNum==1){
                        $(".auction-btn").click(function () {
                            if($("#utype").val()==0){
                                $("#upinfo").show();
                                return;
                            }
                            window.location.href="details.php?id="+$(this).attr("data-id");
                        });
                        $(".hide-follow").click(function () {
                            $(".bottom-menu").hide();
                        })
                        $(".praise").unbind('click');
                        $(".praise").click(function () {
                            $.ajax({
                                url: "ajax.php",
                                type:"GET",
                                dataType: "JSON",
                                cache: "false",
                                data: {
                                    act:'addzan',
                                    id:$(this).find("i").attr("data-id"),
                                    type:'1',
                                },
                                success:function (res) {
                                    alert(res.message);
                                    if(res.status==1){
                                        $("#praise"+res.goodId).html(res.praise);
                                        $("#users-"+res.goodId).html(res.praisePer);
                                    }
                                }
                            })
                            return;
                        })
                    // }
                }
            });
            setTimeout(function(){

            }, 0);
        }
    });

</script>
</body>
</html>
