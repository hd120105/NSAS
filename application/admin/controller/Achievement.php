<?php
namespace app\admin\controller;
use think\Controller;
use think\Paginator;
class Achievement extends Base
{
 public function index()
{
     return $this->fetch('index');
}
public function paperList()//成果列表
{
    $search="";
    if(request()->isPost())
    {
       
        $key   =  input('search');
        $search['title'] = ['like','%'.$key.'%'];
        $count = db('paper')->where($search)->count();
        $paper = db('paper')->where($search)->paginate(10);//status：{0：论文 | 1：专利 | 2: 软著}
        $page = $paper->render();
        $this->assign('count',$count);
        $this->assign('paper',$paper);
        $this->assign('page',$page);
   }else{
        $count = db('paper')->count();
        $paper = db('paper')->paginate(10);//status：{0：论文 | 1：专利 | 2: 软著}
        $page = $paper->render();
        $this->assign('count',$count);
        $this->assign('paper',$paper);
        $this->assign('page',$page);
   }
    return $this->fetch('Achievement/paperList');
}
public function paperAdd()
{
    $date = date("Y-m-d H:i:s");
    if(request()->isPost())
    {
        $params = input('post.');
        $data=[
            'title'         => $params['title'],//标题
            'participator'  => $params['participator'],//参与者
            'level'         => $params['level'],//检索级别
            'periodical'    => $params['periodical'],//会议期刊
            'detalied'      => $params['detalied'],//论文知网地址
            'time'          => $date,
        ];
        if(db('paper')->insert($data)){
           return json(array('code'=>200,'msg'=>'添加成功'));
        }
        else
        {
            return json(array('code'=>0,'msg'=>'失败'));
        }
        
    }  
    return $this->fetch();
}
public function paperEdit()
{
        $date = date("Y-m-d H:i:s");
        $paper=db('paper')->where('id',input('id'))->select();
        $this->assign('paper',$paper); 
        $id   = input('id');
    
    if(request()->isPost())
    {
       
        $params = input('post.');
        $data=[
            'title'         => $params['title'],//标题
            'participator'  => $params['participator'],//参与者
            'level'         => $params['level'],//检索级别
            'periodical'    => $params['periodical'],//会议期刊
            'detailed'      => $params['detailed'],//论文知网地址
            'time'          => $date,
        ];
        if(db('paper')->where('id',$params['id'])->update($data)){
           return json(array('code'=>200,'msg'=>'修改成功'));
        }
        else
        {
            return json(array('code'=>0,'msg'=>'失败'));
        }
        
    }  
    return $this->fetch();
}
public function patentList()//成果列表
{
    $search="";
    if(request()->isPost())
    {
       
        $key   =  input('search');
        $search['title'] = ['like','%'.$key.'%'];
        $count = db('patent')->where($search)->count();
        $patent = db('patent')->where($search)->paginate(10);//status：{0：论文 | 1：专利 | 2: 软著}
        $page = $patent->render();
        $this->assign('count',$count);
        $this->assign('patent',$patent);
        $this->assign('page',$page);
   }else{
        $count = db('patent')->count();
        $patent = db('patent')->paginate(10);//status：{0：论文 | 1：专利 | 2: 软著}
        $page = $patent->render();
        $this->assign('count',$count);
        $this->assign('patent',$patent);
        $this->assign('page',$page);
   }
    return $this->fetch('Achievement/patentList');
}
public function patentAdd()
{
    $date = date("Y-m-d H:i:s");
    if(request()->isPost())
    {
        $params = input('post.');
         $data=[
            'title'         => $params['title'],//标题
            'inventor'      => $params['inventor'],//发明者
            'patent_id'     => $params['patent_id'],//公开时间
            'type_id'       => $params['type_id'],//IPC分类
            'detailed'      => $params['detailed'],
            'time'          => $date
        ];
        if(db('patent')->insert($data)){
           return json(array('code'=>200,'msg'=>'添加成功'));
        }
        else
        {
            return json(array('code'=>0,'msg'=>'失败'));
        }
        
    }  
    return $this->fetch();
}
public function patentEdit()
{
        $date = date("Y-m-d H:i:s");
        $patent=db('patent')->where('id',input('id'))->select();
        $this->assign('patent',$patent); 
        $id   = input('id');
    
    if(request()->isPost())
    {
       
        $params = input('post.');
        $data=[
            'title'         => $params['title'],//标题
            'inventor'      => $params['inventor'],//发明者
            'patent_id'     => $params['patent_id'],//公开时间
            'type_id'       => $params['type_id'],//IPC分类
            'detailed'        => $params['detailed'],
            'time'          => $date
        ];
        if(db('patent')->where('id',$params['id'])->update($data)){
           return json(array('code'=>200,'msg'=>'修改成功'));
        }
        else
        {
            return json(array('code'=>0,'msg'=>'失败'));
        }
        
    }  
    return $this->fetch();
}
public function swrightList()//成果列表
{
    $search="";
    if(request()->isPost())
    {
       
        $key   =  input('search');
        $search['title'] = ['like','%'.$key.'%'];
        $count = db('swright')->where($search)->count();
        $swright = db('swright')->where($search)->paginate(10);//status：{0：论文 | 1：专利 | 2: 软著}
        $page = $swright->render();
        $this->assign('count',$count);
        $this->assign('swright',$swright);
        $this->assign('page',$page);
   }else{
        $count = db('swright')->count();
        $swright = db('swright')->paginate(10);//status：{0：论文 | 1：专利 | 2: 软著}
        $page = $swright->render();
        $this->assign('count',$count);
        $this->assign('swright',$swright);
        $this->assign('page',$page);
   }
    return $this->fetch('Achievement/swrightList');
}
public function swrightAdd()
{
    $date = date("Y-m-d H:i:s");
    if(request()->isPost())
    {
        $params = input('post.');
        $data=[
            'title'             => $params['title'],//标题
            'participator'      => $params['participator'],//发明者
            'finish_time'       => $params['finish_time'],//完成时间
            'pub_time'          => $params['pub_time'],//首次发表时间
            'way'               => $params['way'],//权利取得方式
            'range'             => $params['range'],//权利范围
            'register_id'       => $params['register_id'],//登记号
            'time'              => $date,
            'content'           => $params['content']
        ];
        if(db('swright')->insert($data)){
           return json(array('code'=>200,'msg'=>'添加成功'));
        }
        else
        {
            return json(array('code'=>0,'msg'=>'失败'));
        }
        
    }  
    return $this->fetch();
}
public function swrightEdit()
{
        $date = date("Y-m-d H:i:s");
        $swright=db('swright')->where('id',input('id'))->select();
        $this->assign('swright',$swright); 
        $id   = input('id');
    
   $date = date("Y-m-d H:i:s");
    if(request()->isPost())
    {
        $params = input('post.');
        $data=[
           'title'             => $params['title'],//标题
            'participator'      => $params['participator'],//发明者
            'finish_time'       => $params['finish_time'],//完成时间
            'pub_time'          => $params['pub_time'],//首次发表时间
            'way'               => $params['way'],//权利取得方式
            'range'             => $params['range'],//权利范围
            'register_id'       => $params['register_id'],//登记号
            'time'              => $date,
            'content'           => $params['content']
        ];
        if(db('swright')->where('id',$params['id'])->update($data)){
           return json(array('code'=>200,'msg'=>'添加成功'));
        }
        else
        {
            return json(array('code'=>0,'msg'=>'失败'));
        }
        
    }  
    return $this->fetch();
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
public function Add()
{	 
    $date = date("Y-m-d H:i:s");
    if(request()->isPost())
    {
        $params = input('post.');
        $data=[
            'title'         => $params['title'],
            'participator'  => $params['participator'],
            'number'        => $params['no'],//将输入关键字中的中文逗号转化为英文逗号
            'type'          => $params['type'],
            'progress'      => $params['progress'],
            'pub_time'      => $params['date'],
            'unit'          => $params['unit'],
            'time'          => $date,
            'file'          => $params['file']
        ];
        if(db('achievement')->insert($data)){
           return json(array('code'=>200,'msg'=>'添加成功'));
        }
        else
        {
            return json(array('code'=>0,'msg'=>'失败'));
        }
        
    }  
            return $this->fetch('');
  } 
    
public function Del() //删除
{
    if(input('type')==1){
        if(db('paper')->delete(input('id'))){
        return json(array('code'=>200,'msg'=>'删除成功'));
        }else{
        return json(array('code'=>0,'msg'=>'删除失败'));
        }
    }else if(input('type')==2){
        if(db('patent')->delete(input('id'))){
        return json(array('code'=>200,'msg'=>'删除成功'));
        }else{
        return json(array('code'=>0,'msg'=>'删除失败'));
        }
    }else if(input('type')==3){
        if(db('swright')->delete(input('id'))){
        return json(array('code'=>200,'msg'=>'删除成功'));
        }else{
        return json(array('code'=>0,'msg'=>'删除失败'));
        }
    }
}
public function Stop() //下架
{
     if(request()->isPost())
     {
         if(input('type')==1){
            if(db('paper')->where('id',input('id'))->update(['status'=>2]))
            {
                return json(array('code'=>200,'msg'=>'已回收'));
            }
            else
            {
                return json(array('code'=>0,'msg'=>'回收不成功'));
            }
        } else if(input('type')==2){
            if(db('patent')->where('id',input('id'))->update(['status'=>2]))
            {
                return json(array('code'=>200,'msg'=>'已回收'));
            }
            else
            {
                return json(array('code'=>0,'msg'=>'回收不成功'));
            }
        }else if(input('type')==3){
            if(db('swright')->where('id',input('id'))->update(['status'=>2]))
            {
                return json(array('code'=>200,'msg'=>'已回收'));
            }
            else
            {
                return json(array('code'=>0,'msg'=>'回收不成功'));
            }
        }
        
    }
}
public function Pub()//发表
{
    if(request()->isPost())
    {
        if(input('type')==1){
            if(db('paper')->where('id',input('id'))->update(['status'=>1]))
            {
                return json(array('code'=>200,'msg'=>'已发表'));
            }
            else
            {
                return json(array('code'=>0,'msg'=>'发表不成功'));
            }
        }else if(input('type')==2){
            if(db('patent')->where('id',input('id'))->update(['status'=>1]))
            {
                return json(array('code'=>200,'msg'=>'已发表'));
            }
            else
            {
                return json(array('code'=>0,'msg'=>'发表不成功'));
            }
        }else if(input('type')==3){
            if(db('swright')->where('id',input('id'))->update(['status'=>1]))
            {
                return json(array('code'=>200,'msg'=>'已发表'));
            }
            else
            {
                return json(array('code'=>0,'msg'=>'发表不成功'));
            }
        }
     }
} 
}
?>