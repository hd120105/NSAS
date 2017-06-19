<?php
namespace app\index\controller;
use think\Controller;
class Index extends Controller
{
    public function index()
    {
    	$news    = db('news')->order('createtime desc')->where(['status'=>1])->limit(10)->select();
        $project = db('project')->where(['status'=>1])->limit(3)->select();
        $lab     = db('lab')->where(['status'=>1])->select();
        $pic     = db('pic')->where(['status'=>1,"type"=>0])->select();
        $photo   = db('pic')->where(['status'=>1,"type"=>1])->select();
        $this->assign('pic',$pic);
        $this->assign('photo',$photo);
        $this->assign('project',$project);
    	$this->assign('news',$news);
        $this->assign('lab',$lab);
       return  $this->fetch('');
    }
}
