<?php
namespace app\index\controller;
use think\Controller;
use think\Paginator;
class Lab extends Controller{

   public function introduce()//成果列表
   {
      $introduce = db('introduce')->where(['status'=>1])->select();
      $this->assign('introduce',$introduce);
      return $this->fetch('Lab/introduce');
  }
  public function introduceDisplay()//成果列表
  {
      $introduce = db('Lab')->where('id','=',input('id'))->order('time desc')->select();
      $this->assign('introduce',$introduce);
      return $this->fetch('Lab/introduceDisplay');
  }
   public function field()//成果列表 
  {
      $field = db('fields')->where(['status'=>1])->order('time desc')->select();
      $this->assign('field',$field);
      return $this->fetch('Lab/field');
  }
     public function rules()//成果列表
  {
      $rules = db('rules')->where(['status'=>1])->order('time desc')->paginate(9);
      $page  = $rules->render();
      $this->assign('rules',$rules);
      $this->assign('page',$page);
      return $this->fetch('Lab/rules');
  }
	public function Add()
	{	 
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

                // 'FilePath'    =>
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
    
   public function Del() //删除文章
  {
    if(db('achievement')->delete(input('id'))){
      // $arr=array("success"=>true);
      // echo json_encode($arr);   //删除成功向前端返回提示信息。
      return json(array('code'=>200,'msg'=>'删除成功'));
    }else{
      //  $arr=array("error"=>true);
      // echo json_encode($arr); 
      return json(array('code'=>0,'msg'=>'删除失败'));
    }
  }
    public function Stop() //文章下架
    {
         if(request()->isPost())
         {
            if(db('achievement')->where('id',input('id'))->update(['status'=>2]))
            {
                return json(array('code'=>200,'msg'=>'已回收'));
            }
            else
            {
                return json(array('code'=>0,'msg'=>'回收不成功'));
            }
         }
    }
    public function Pub()//发表文章
    {
         if(request()->isPost())
         {
            if(db('achievement')->where('id',input('id'))->update(['status'=>1]))
            {
                return json(array('code'=>200,'msg'=>'已发表'));
            }
            else
            {
                return json(array('code'=>0,'msg'=>'发表不成功'));
            }
         }
    }
    public function Edit()
    {
       
        $achievement=db('achievement')->where('id',input('id'))->select();
        $this->assign('achievement',$achievement); 
        $id = input('id');
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

                // 'FilePath'    =>
            ];
           //  $validate = \think\Loader::validate('Article');//验证
           //  if(!$validate->scene('add')->check($data)){
           //       $this->error($validate->getError());
            
           //      die();
           // }
           $update=db('achievement')->where('id',$id)->update($data);
            if($update){
                  return json(array('code'=>200,'msg'=>'提交成功'));
            }
            else
            {
                  return json(array('code'=>0,'msg'=>'提交失败dd'));
            }

         }
        return $this->fetch('');
    }
    
}
?>