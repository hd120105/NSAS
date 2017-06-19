<?php
namespace app\admin\controller;
use think\Controller;
class Admin extends Controller{
public function adminList()
{
    if(request()->isPost()){
        $key   = input('search');
        $search['username'] = ['like','%'.$key.'%'];
        $admin = db("admin")->where($search)->paginate(10);
        $count = db('admin')->where($search)->count();
        $page = $admin->render();
        $this->assign('count',$count);
        $this->assign('page',$page);
        $this->assign("admin",$admin);
    }else{
        $admin = db("admin")->paginate(10);
        $count = db('admin')->count();
        $page = $admin->render();
        $this->assign('count',$count);
        $this->assign('page',$page);
        $this->assign("admin",$admin);
        }
    return $this->fetch('');	
}
public function add(){
    $date = date("Y-m-d H:i:s");
    if(request()->isPost())
    {
        $params = input('post.');
        $name = \think\Db::name('admin')->where('username','=',$params['username'])->find();//判断是否已经存在该用户
        if($name){
            return json(array('code'=>401,'msg'=>'该用户名已被注册过了'));
        }
        $email = \think\Db::name('admin')->where('email','=',$params['email'])->find();//判断是否已经存在该邮箱
        if($email){
            return json(array('code'=>402,'msg'=>'该邮箱已经被注册过了，请输入正确的邮箱号'));
        }
        $data=[
            'username'       => $params['username'],
            'pwd'            => md5($params['password']),
            'email'          => $params['email'],//将输入关键字中的中文逗号转化为英文逗号
            'tel'            => $params['tel'],
            'register_time'  => $date,
        ];
        if(db('admin')->insert($data)){
            return json(array('code'=>200,'msg'=>'添加成功'));
        }
        else
        {
            return json(array('code'=>400,'msg'=>'添加失败'));
        }   
    }  
    return $this->fetch('');
}
}
?>