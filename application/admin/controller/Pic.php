<?php
namespace app\admin\controller;
use think\Controller;
use think\Paginator;
class Pic extends Base
{
  public function index()
  {
        return $this->fetch('index');
  }
  public function picList()//成果列表
  {
      $count = db('pic')->where(['type'=>0])->count();
      $pic = db('pic')->where(['type'=>0])->paginate(8);
      $this->assign('pic',$pic);
      $page = $pic->render();
      $this->assign('count',$count);
      $this->assign('page',$page);
      return $this->fetch('');
	}
  public function photoList()//成果列表
  {
      $count = db('pic')->where(['type'=>1])->count();
      $pic = db('pic')->where(['type'=>1])->paginate(8);
      $this->assign('pic',$pic);
      $page = $pic->render();
      $this->assign('count',$count);
      $this->assign('page',$page);
      return $this->fetch('');
	}
	public function Add()
	{	 
       $date = date("Y-m-d H:i:s");
       if(request()->isPost())
        {
            $params = input('post.');
            $data=[
                'picurl'      => $params['picurl'],
                'content'     => $params['desc'],
                'type'        => $params['type'],
                'time'        => $date
            ];
            if(db('pic')->insert($data)){
               return json(array('code'=>200,'msg'=>'添加成功'));
            } else{
               return json(array('code'=>0,'msg'=>'失败'));
            }
           
         }  
         return $this->fetch('');
  } 
    
 public function Del() //删除文章
 {
    if(db('pic')->delete(input('id'))){
      return json(array('code'=>200,'msg'=>'删除成功'));
    }else{
      return json(array('code'=>0,'msg'=>'删除失败'));
    }
 }
public function Stop() //文章下架
{
    if(request()->isPost())
    {
        if(db('pic')->where('id',input('id'))->update(['status'=>2]))
        {
            return json(array('code'=>200,'msg'=>'已回收'));
        } else
        {
            return json(array('code'=>0,'msg'=>'回收不成功'));
        }
    }
}
public function Pub()//发表文章
{
    if(request()->isPost())
    {
        if(db('pic')->where('id',input('id'))->update(['status'=>1]))
        {
            return json(array('code'=>200,'msg'=>'已发表'));
        }else{
            return json(array('code'=>0,'msg'=>'发表不成功'));
        }
    }
}
public function upload()
{
    $ret = array();
    if($_FILES['file']['error']>0)
    {
        $ret['message'] = $_FILES['file']['error'];
        $ret['status']  = 0;
        $ret['src'] = "";
        return json($ret);
    }else{
        $pic = $this->uploadPic();
        if($pic['info']== 1){ 
            $url = '__INDEX__/uploads/loopimg/'.$pic['savename'];
        }else{
            $ret["message"] = $this->error($pic['err']);
            $ret["status"] = 0;   
        }
        $ret["message"]= "图片上传成功！";
        $ret["status"] = 1;   
        $ret["src"] = $url; 
        return json($ret);
    }
}
private  function uploadPic()
{
    $file = request()->file('file'); 
        // 移动到框架应用根目录/public/uploads/ 目录下
    $info = $file->move(ROOT_PATH . 'public/static/index/uploads/' . DS . 'loopimg');
    $reubfo = array();  //定义一个返回的数组
    if($info){
        $reubfo['info']= 1;
        $reubfo['savename'] = $info->getSaveName();
    }else{
            // 上传失败获取错误信息
        $reubfo['info']= 0;
        $reubfo['err'] = $file->getError();;
    }
    return $reubfo;
}
    
}
?>