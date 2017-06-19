<?php
namespace app\index\controller;
use think\Controller;
use think\Paginator;
class Achievement extends Controller{
    public function paperList(){
        $paper = db('paper')->order('id desc')->where(['status'=>1])->paginate(5);
        $page  = $paper->render();
        $this->assign('paper',$paper);
        $this->assign('page',$page);
        return $this->fetch('Achievement/paperList');
    }
    public function patentList(){
         $patent = db('patent')->order('id desc')->where(['status'=>1])->paginate(10);
         $page   = $patent->render();
         $this->assign('patent',$patent);
         $this->assign('page',$page);
         return $this->fetch('Achievement/patentList');
    }
    public function swrightList(){
         $swright = db('swright')->order('id desc')->where(['status'=>1])->paginate(5);
         $page   = $swright->render();
         $this->assign('swright',$swright);
         $this->assign('page',$page);
         return $this->fetch('Achievement/swrightList');
    }
    public function projectList(){//项目
         $project = db('project')->order('id desc')->where(['status'=>1])->paginate(10);
         $page = $project->render();
         $this->assign('project',$project);
         $this->assign('page',$page);
         return $this->fetch('Achievement/projectList');
   }
   public function prizeList(){//奖项
         $prize = db('prize')->order('id desc')->where(['status'=>1])->paginate(10);
         $page = $prize->render();
         $this->assign('prize',$prize);
         $this->assign('page',$page);
         return $this->fetch('Achievement/prizeList');
  }
    public function paperDisplay(){
        $id=input('id');
        $display = db('paper')->where('id','=',$id)->select();
        $this->assign('display',$display);
        return $this->fetch('');
    }
    public function patentDisplay(){
        $id=input('id');
        $display = db('patent')->where('id','=',$id)->select();
        $this->assign('display',$display);
        return $this->fetch('');
    }
    public function swDisplay(){
        $id=input('id');
        $display = db('swright')->where('id','=',$id)->select();
        $this->assign('display',$display);
        return $this->fetch('');
    }
    public function prizeDisplay(){
        $id=input('id');
        $display = db('prize')->where('id','=',$id)->select();
        $this->assign('display',$display);
        return $this->fetch('');
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