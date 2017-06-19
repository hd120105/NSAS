<?php
namespace app\admin\controller;
use think\Controller;
use think\Paginator;
class Lab extends Base
{
  public function introduce()//获取实验室介绍
  {
        $lab = db("Lab")->select();
        $this->assign("lab",$lab);
        return $this->fetch('');
  }
  public function edit()//修改实验室介绍
  {
        $lab = db("Lab")->select();
        $this->assign("lab",$lab);
        $date = date("Y-m-d H:i:s");
        if(request()->isPost())
        {
            $params = input('post.');
            $data=[
                'name'         => $params['name'],
                'tel'          => $params['tel'],
                'address'      => $params['address'],
                'fields'       => $params['fields'],
                'introduce'    => $params['content'],
                'email'        => $params['email'],
                'time'         => $date
   
            ];
            if(db('lab')->where(['id'=>$params['id']])->update($data)){
               return json(array('code'=>200,'msg'=>'修改成功'));
            }
            else
            {
                return json(array('code'=>0,'msg'=>'失败'));
            }
           
         }  
        return $this->fetch('Lab/edit');
  }
  public function Pub()//发表文章
  {
       if(request()->isPost())
       {
            if(db('lab')->where('id',input('id'))->update(['status'=>1]))
            {
                return json(array('code'=>200,'msg'=>'已发表'));
            }
            else
            {
                return json(array('code'=>0,'msg'=>'发表不成功'));
            }
      }
  }
  public function Stop() //文章下架
  {
         if(request()->isPost())
         {
            if(db('lab')->where('id',input('id'))->update(['status'=>0]))
            {
                return json(array('code'=>200,'msg'=>'已回收'));
            }
            else
            {
                return json(array('code'=>0,'msg'=>'回收不成功'));
            }
         }
  }
  public function rules()//实验室制度
  {
      if(request()->isPost())
      {
        $key   = input('search');
        $search['name'] = ['like','%'.$key.'%'];//搜索
        $count = db('rules')->where($search)->count();
        $rules = db('rules')->where($search)->paginate(10);//status：{0：论文 | 1：专利 | 2: 软著}
        $page = $rules->render();
        $this->assign('count',$count);
        $this->assign('rules',$rules);
        $this->assign('page',$page);
      }else{
        $count = db('rules')->count();
        $rules = db("rules")->paginate(10);
        $page = $rules->render();
        $this->assign('count',$count);
        $this->assign('rules',$rules);
           $this->assign('page',$page);
      }
        return $this->fetch('');
  }
  public function fields()//实验室研究方向
  { 
        $fields = db("fields")->select();
        $this->assign('fields',$fields);
        return $this->fetch('');
  }
  public function Addfield()
  {
      if(request()->isPost()){
          $params = input('post.');
          $date = date("Y-m-d H:i:s");
          $data=[
              'name'      =>$params['name'],
              'introduce' => $params['content'],
              'fileurl'   => $params['url'],
              'time'      => $date
          ];
          if(db('fields')->insert($data)){
               return json(array('code'=>200,'msg'=>'添加成功'));
          }else
            {
                return json(array('code'=>0,'msg'=>'失败'));
            }    
      }
      return $this->fetch('Lab/addfield');
  }
  public function Editfield()//修改实验室介绍
  {     
        $id    = input('id');
        $field = db("fields")->where(['id'=>$id])->select();
        $this->assign("field",$field);
  
        if(request()->isPost())
        {
            $params = input('post.');
            $date = date("Y-m-d H:i:s");
            $data=[
              'name'      => $params['name'],
              'introduce' => $params['content'],
              'fileurl'   => $params['url'],
              'time'      => $date
   
            ];
            if(db('fields')->where(['id'=>$id])->update($data)){
               return json(array('code'=>200,'msg'=>'修改成功'));
            }
            else
            {
                return json(array('code'=>0,'msg'=>'失败'));
            }
           
         }  
        return $this->fetch('Lab/editfield');
  }
  public function Delfield() //删除
 {
    if(db('fields')->delete(input('id'))){
      return json(array('code'=>200,'msg'=>'删除成功'));
    }else{
      return json(array('code'=>0,'msg'=>'删除失败'));
    }
 }
  public function Pubfield()//发表
  {
       if(request()->isPost())
       {
            if(db('fields')->where('id',input('id'))->update(['status'=>1]))
            {
                return json(array('code'=>200,'msg'=>'已发表'));
            }
            else
            {
                return json(array('code'=>0,'msg'=>'发表不成功'));
            }
      }
  }
  public function Stopfield() //下架
 {
         if(request()->isPost())
         {
            if(db('fields')->where('id',input('id'))->update(['status'=>0]))
            {
                return json(array('code'=>200,'msg'=>'已回收'));
            }
            else
            {
                return json(array('code'=>0,'msg'=>'回收不成功'));
            }
         }
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
             $url = '__INDEX__/uploadFiles/Lab/'.$file['savename'];
          }  else {
             $ret["message"] = $this->error($file['err']);
             $ret["status"] = 0;   
         }
          $ret["message"]= "文件上传成功！";
          $ret["status"] = 1;   
          $ret["src"] = $url; 
          $ret['name']=$_FILES['file']['name'];
          $ret["date"] = date("Y-m-d");
          return json($ret);
      }
}
private  function uploadFile()
{
    $file = request()->file('file'); 
        // 移动到框架应用根目录/public/uploads/ 目录下
    $info = $file->move(ROOT_PATH . 'public/static/index/uploadFiles' . DS . 'Lab');
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
public function Addrule()
{
    if(request()->isPost())
    {
        $params = input('post.');
        $date = date("Y-m-d H:i:s");
        $data=[
                'name'         => $params['name'],          //姓名
                'time'         => $params['date'],
                'fileurl'      => $params['url']            //头像
        ];
        if(db('rules')->insert($data)){
               return json(array('code'=>200,'msg'=>'添加成功'));
        }else {
              return json(array('code'=>0,'msg'=>'添加失败' ));
        }
            
    }  
     return $this->fetch('Lab/addrule');
}
 public function Pubrule()//发表文章
  {
       if(request()->isPost())
       {
            if(db('rules')->where('id',input('id'))->update(['status'=>1])){
                return json(array('code'=>200,'msg'=>'已推送'));
            }else{
                return json(array('code'=>0,'msg'=>'推送不成功'));
            }
      }
  }
 public function Stoprule() //文章下架
  {
        if(request()->isPost())
        {
            if(db('rules')->where('id',input('id'))->update(['status'=>0])){
                return json(array('code'=>200,'msg'=>'已回收'));
            } else{
            return json(array('code'=>0,'msg'=>'回收不成功'));
            }
        }
  }
 public function Delrule() //删除文章
 {
    if(db('rules')->delete(input('id'))){
      return json(array('code'=>200,'msg'=>'删除成功'));
    }else{
      return json(array('code'=>0,'msg'=>'删除失败'));
    }
 }  
}
?>