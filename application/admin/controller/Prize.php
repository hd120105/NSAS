<?php
namespace app\admin\controller;
use think\Controller;
use think\Paginator;
class Prize extends Base
{
  public function index()
  {
        return $this->fetch('index');
  }
  public function prizelist()//成果列表
  {
    if(request()->isPost())
    {
        $key   = input('search');
        $search['prize'] = ['like','%'.$key.'%'];
        $count = db('prize')->where($search)->count();
        $prize = db('prize')->where($search)->paginate(10);//status：{0：论文 | 1：专利 | 2: 软著}
        $page = $prize->render();
        $this->assign('count',$count);
        $this->assign('prize',$prize);
        $this->assign('page',$page);
    }else{
        $count = db('prize')->count();
        $prize = db('prize')->paginate(10);//status：{0：论文 | 1：专利 | 2: 软著}
        $page = $prize->render();
        $this->assign('count',$count);
        $this->assign('prize',$prize);
        $this->assign('page',$page);
    }
    return $this->fetch('Prize/prizelist');
  }
  public function Add()
  {	 
    $date = date("Y-m-d H:i:s");
    if(request()->isPost())
    {
        $params = input('post.');
        $data=[
            'prize'         => $params['prize'],
            'date'          => $params['date'],
            'content'       => $params['content'],
            'time'          => $date
        ];
        if(db('prize')->insert($data)){
            return json(array('code'=>200,'msg'=>'添加成功'));
        }else{
            return json(array('code'=>0,'msg'=>'失败'));
        }
    }  
        return $this->fetch('');
  } 
    
  public function Del() //删除文章
  {
    if(db('prize')->delete(input('id'))){
        return json(array('code'=>200,'msg'=>'删除成功'));
    }else{
        return json(array('code'=>0,'msg'=>'删除失败'));
    }
  }
  public function Stop() //文章下架
  {
    if(request()->isPost())
    {
        if(db('prize')->where('id',input('id'))->update(['status'=>2]))
        {
            return json(array('code'=>200,'msg'=>'已回收'));
        }else{
           return json(array('code'=>0,'msg'=>'回收不成功'));
        }
    }
  }
  public function Pub()//发表文章
  {
    if(request()->isPost())
    {
        if(db('prize')->where('id',input('id'))->update(['status'=>1]))
        {
            return json(array('code'=>200,'msg'=>'已发表'));
        }else{
            return json(array('code'=>0,'msg'=>'发表不成功'));
        }
    }
}
  public function Edit()
  {
    
    $prize = db('prize')->where('id',input('id'))->select();
    $this->assign('prize',$prize); 
    $id    = input('id');
    $date  = date("Y-m-d H:i:s");
    if(request()->isPost())
    {
        $params = input('post.');
        $data=[
            'prize'         => $params['prize'],
            'date'          => $params['date'],
            'content'       => $params['content'],
            'time'          => $date
        ];
        $update=db('prize')->where('id',$id)->update($data);
        if($update){
            return json(array('code'=>200,'msg'=>'提交成功'));
        }else{
            return json(array('code'=>0,'msg'=>'提交失败'));
        }
    }
    return $this->fetch('');
  }
  public function upload()
{
    $ret = array();
    if($_FILES['file']['error']>0)//发生错误
    {
        $ret['message'] = $_FILES['file']['error'];
        $ret['status']  = 0;
        $ret['src'] = "";
        return json($ret);
    }else{
        $file = $this->uploadFile();
        if($file['info']== 1){ 
            $url = '__INDEX__/uploadFiles/achievement/'.$file['savename'];
        }else {
            $ret["message"] = $this->error($file['err']);
            $ret["status"] = 0;   
        }
        $ret["message"]= "文件上传成功！";
        $ret["status"] = 1;   
        $ret["src"] = $url; 
        $ret['name']=$_FILES['file']['name'];
        $ret["realname"] = $file['realname'];
        return json($ret);
    }
}
private  function uploadFile()
{
    $file = request()->file('file'); 
        // 移动到框架应用根目录/public/uploads/ 目录下
    $info = $file->move(ROOT_PATH . 'public/static/index/uploadFiles' . DS . 'achievement');
    $reubfo = array();  //定义一个返回的数组
    if($info){
        $reubfo['info']= 1;
        $reubfo['savename'] = $info->getSaveName();
        $reubfo['pathname'] = $info->getPathname();
        $reubfo['realname'] = $info->getRealPath();
    }else{
            // 上传失败获取错误信息
        $reubfo['info']= 0;
        $reubfo['err'] = $file->getError();
    }
    return $reubfo;
}    
}
?>