<?php
namespace app\index\controller;
use think\Controller;
class News extends Controller
{
    public function newsList()
    {
    	$news = db('news')->order('createtime desc')->where(['status'=>1])->paginate(10);
    	$page = $news->render();
    	$this->assign('newsList',$news);
        $this->assign('page',$page);
       return  $this->fetch('');
    }
    public function newsDisplay()
    {
    	$id=input('id');
    	$display = db('news')->where('id','=',$id)->select();
    	$this->assign('display',$display);
    	$before = db('news')->where('id','>',$id)->order('id desc')->limit(1)->select();
    	if($before==NULL){
    		$front='没有了';
    	}else{
    		$front = $before;
    	}
    //	$front = !$before?'没有了':$before;
    	$this->assign('before',$front);
    	//$this->before = $front;
    	$after = db('news')->where('id','<',$id)->order('id desc')->limit(1)->select();
    	$end = !$after?'这已经是最后一篇了':$after;
    	$this->assign('after',$end);
    	//$this->after = $end;
    	return $this->fetch('');
    }
}
