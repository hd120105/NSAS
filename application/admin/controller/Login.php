<?php
namespace app\admin\Controller;
use think\Controller;
use phpmailer\phpmailer\phpmailer;
use phpmailer\phpmailer\Exception;
use app\admin\model\Login as Log;//引入空间类元素
class Login extends Controller {
	//登入页面
 public function Login(){
    if(request()->isPost()){
		$login     = new Log;
		$status    = $login->login(input('userEmail'),input('password'),input('code'));//调用类中方法
		if($status == 1)
		{
			if(getenv('HTTP_CLIENT_IP')) {
			    $onlineip = getenv('HTTP_CLIENT_IP');
			} elseif(getenv('HTTP_X_FORWARDED_FOR')) {
			    $onlineip = getenv('HTTP_X_FORWARDED_FOR');
			} elseif(getenv('REMOTE_ADDR')) {
			    $onlineip = getenv('REMOTE_ADDR');
			} else {
			    $onlineip = $HTTP_SERVER_VARS['REMOTE_ADDR'];
			}
			$loginIP = db('admin')->where('id',session('id'))->update(['ip'=>$onlineip]);//保存ip
            $updateHit = db('admin')->where('id',session('id'))->setInc('hits');//登陆次数+1
			if($updateHit){
                return  $this->success("登录成功",1,1);
			}
		}else if($status ==2){   //验证码或者密码错误
			 return $this->error("验证码错误",0,0);
		}else if($status==3){
			return $this->error("用户名或者密码错误",0,0);
	    }else{
			return $this->error("未知错误",0,0);
		}
    }
    return $this->fetch(''); 
 }        
 public function forgetpwd()//忘记密码，生成随机密码，发送至其邮箱
 {
	    if(request()->isPost()){
	        $toemail = input('email');
			$name    = input('account');
			$admin   = \think\Db::name('admin')->where('username','=',$name)->find();
			if($admin)//查看用户名是否正确
			{
				if($admin['email']==$toemail){//查询该用户的邮箱是否正确
                    $time = date("Y-m-d H:i:s");
					$subject = 'NSAS实验室后台管理系统密码找回';//邮件标题
					$str='abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';//生成随机码
					$len=strlen($str)-1;
					$randstr='';
					for($i=0;$i<6;$i++){
						$num=mt_rand(0,$len);
						$randstr .= $str[$num];
					} 
					$content ="亲爱的" . $name . "：<br/>您在" . $time . "提交了通过邮箱".$toemail."找回密码请求。您的随机密码是：".$randstr."，密码2小时内有效，请尽快登陆系统进行修改密码操作。"; //设置邮件正文
					$url = '';
					$session['name']=$name;
					$session['email']=$toemail;
					$session['pwd'] = $randstr;
					\think\Cookie::set('emailcheck',$session,7200);//设置cookie，保证随机密码有效时间
					$str = send_mail($toemail,$name,$subject,$content);
					if(1){
						return json(array('code'=>200,'msg'=>$str));
					}else{
						return json(array('code'=>400,'msg'=>'失败'));
					}
				}else{
					return json(array('code'=>400,'msg'=>"您输入的邮箱不正确"));
				}
			}else{
				return json(array('code'=>400,'msg'=>"您输入的用户不存在"));
			}
			
		}
       

 }
 //退出
 public function Logout()
 {
	if(request()->isGet())
	{
		session(null);//清除session
		cookie(null);//清除cookie
		return json(array('code'=>200,'msg'=>"退出成功"));
	}
 }
 //注销
  public function logoff(){
	$id = session ('id');
	if(request()->isGet()){
		if(db('admin')->where('id','=',$id)->delete()){
			session(null);
			cookie(null);
			return json(array('code'=>200,'msg'=>'注销成功'));
		}else{
			return json(array('code'=>400,'msg'=>'注销失败'));
		}
	}
 }
 //查看个人信息
public function check(){
	if(request()->isGet()){
		$id = session('id');
		$user = \think\Db::name('admin')->where('id','=',$id)->select();
		$this->assign('user',$user);
	}
	return $this->fetch('');
}
//修改个人信息
public function edit(){
	$id = session('id');
	$username = session('username');
	$useremail = session('email');//获取session
	$user = \think\Db::name('admin')->where('id','=',$id)->select();
	$this->assign('user',$user);
	if(request()->isPost()){
		$date = date("Y-m-d H:i:s");
		$params = input('post.');
		$name = \think\Db::name('admin')->where('username','=',$params['username'])->find();//判断是否已经存在该用户
		if($name['username']!=$username){
			return json(array('code'=>401,'msg'=>$useremail));
		}
		$email = \think\Db::name('admin')->where('email','=',$params['email'])->find();//判断是否已经存在该邮箱
		if($email['email']!=$useremail){
			return json(array('code'=>402,'msg'=>'该邮箱已经被注册过了，请输入正确的邮箱号'));
		}
		$data=[
			'username'       => $params['username'],
			'email'          => $params['email'],
			'tel'            => $params['tel'],
			'register_time'           => $date
		];
		if(db('admin')->where('id','=',$id)->update($data)){
			return json(array('code'=>200,'msg'=>'修改成功'));
		}else{
			return json(array('code'=>400,'msg'=>'修改失败'));
		}
    }
    return $this->fetch('');
}
//修改密码
 public function change(){//修改密码
	$id = session('id');
	if(request()->isPost()){
		$params = input('post.'); 
		$date = date('Y-m-d H:i:s');
		$data = [
			'pwd'  => md5($params['password']),
			'register_time' => $date
		];
		if(db('admin')->where('id','=',$id)->update($data)){
			return json(array('code'=>200,'msg'=>'修改成功'));
		}else{
			return json(array('code'=>400,'msg'=>'修改失败'));
		}
	}
	return $this->fetch('');
 }
}