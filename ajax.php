<?php
/**
 * Created by PhpStorm.
 * user: Administrator
 * Date: 2018/11/11
 * Time: 15:29
 */

namespace app\index\controller;
use think\Loader;
use think\Controller;
use think\Db;
use think\Model;

class Message extends Controller
{
    public function index()
    {

         if(request()->isPost()){

            $data=input('post.');   //读取前台Ajax数据
       
		    $message = model('message');
		    $message->message=$data['message'];
		    $result=$message->save();
            
            if (1) {
                //数据插入成功
                 return json_encode ($data);
                
            }
            else
            {
                //数据插入错误
                return false;
            }
           
            

         }
         $message = model('message');
		// 查询数据集
		$messageData=$message->order('id', 'desc')->paginate(6);       		
         $this->assign('messageData',$messageData);		//将数据应用到视图
       
            
        

        return view();
    }
    public function del()
    {
    	$message = model('message');
        $messageId = input('id');
        $message->delete()->where('id',$messageId)->delete();
     	$messageData=$message->order('id', 'desc')->paginate(6);       
          $this->assign('messageData',$messageData);
          return view('index');
        
        

    }
    
}
