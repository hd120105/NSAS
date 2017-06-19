<?php
namespace app\admin\model;

use think\Model;

class Login extends Model
{
    public function login($useremail,$password,$captcha){
        $cookieStatus = 0;
        if(cookie('emailcheck'))//存在cookie
        {
            $emailcheck = cookie('emailcheck');
            
            if($useremail == $emailcheck['email']&&$password  = $emailcheck['pwd']){ //登陆成功
                $cookieStatus =  1;//验证通过
            }else{
                $cookieStatus = 2;//验证不通过
            } 
        }else{
            $cookieStatus = 0;//cookie不存在
        }
            
        $captchaStatus=0;
        if(captcha_check($captcha))//验证码检验
        {
            $captchaStatus = 1;//验证码正确
        }else{
            $captchaStatus = 0;//验证码错误
        }
        $admin= \think\Db::name('admin')->where('email','=',$useremail)->find();//从数据库中获取数据
        $adminStatus = 0;
        if($admin){
            if($admin['pwd']==md5($password))
            {
                $adminStatus = 1;//在数据库中找到该用户记录
            }else{
                $adminStatus = 2;//密码错误
            }
        }else{
            $adminStatus = 0;
        }
        if($cookieStatus == 1 || $adminStatus == 1){//存在cookie或者在数据库中找到该用户记录
          if($captchaStatus == 1)
          {
                \think\Session::set('username',$admin['username']);//设置session
                \think\Session::set('email',$admin['email']);//设置session
                \think\Session::set('id',$admin['id']);
                return 1;
          }else{
                return 2; //验证码错误
          }
        }else if($adminStatus ==2 ||$cookieStatus == 2){
          if($captchaStatus == 0)
           {
                return 2;//验证码错误
            }else{
                return 3; //密码错误
            }
        }else{
            return 4;//其他错误
        }
    }
}
