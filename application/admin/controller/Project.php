<?php
namespace app\admin\controller;
use think\Controller;
use think\Paginator;
class Project extends Base
{
  public function index()
  {
        return $this->fetch('index');
	}
	public function projectlist()//成果列表
	{
      if(request()->isPost())
      {
        $key   = input('search');
        $search['name'] = ['like','%'.$key.'%'];
        $count = db('project')->where($search)->count();
        $project = db('project')->where($search)->paginate(10);//status：{0：论文 | 1：专利 | 2: 软著}
        $page = $project->render();
        $this->assign('count',$count);
        $this->assign('project',$project);
        $this->assign('page',$page);
      }else{
        $count = db('project')->count();
        $project = db('project')->paginate(10);//status：{0：论文 | 1：专利 | 2: 软著}
        $page = $project->render();
        $this->assign('count',$count);
        $this->assign('project',$project);
        $this->assign('page',$page);
      }
      return $this->fetch('Project/projectlist');
	}
	public function Add()
	{	 
       if(request()->isPost())
        {
            $params = input('post.');
            $date   = date("Y-m-d H:i:s");
            $data=[
                'name'         => $params['name'],
                'source'       => $params['source'],
                'no'           => $params['no'],//将输入关键字中的中文逗号转化为英文逗号
                'progress'     => $params['progress'],
                 'projectSrc'   => $params['projectSrc'],
                'date'         => $params['date'],
                'time'         => $date          
            ];
            if(db('project')->insert($data)){
               return json(array('code'=>200,'msg'=>'添加成功'));
            }else{
                return json(array('code'=>0,'msg'=>'失败'));
            }    
         }  
        return $this->fetch('');
  } 
    
   public function Del() //删除文章
   {
    if(db('project')->delete(input('id'))){
      return json(array('code'=>200,'msg'=>'删除成功'));
    }else{
      return json(array('code'=>0,'msg'=>'删除失败'));
    }
   }
   public function Stop() //文章下架
  {
    if(request()->isPost())
    {
        if(db('project')->where('id',input('id'))->update(['status'=>2]))
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
        if(db('project')->where('id',input('id'))->update(['status'=>1]))
        {
            return json(array('code'=>200,'msg'=>'已发表'));
        }else{
            return json(array('code'=>0,'msg'=>'发表不成功'));
        }
    }
 }
 public function Edit()
 {
       
    $project = db('project')->where('id',input('id'))->select();
    $this->assign('project',$project); 
    $id      = input('id');
    $date    = date("Y-m-d H:i:s");
    if(request()->isPost())
    {
        $params = input('post.');
            $data=[
            'name'         => $params['name'],
            'source'       => $params['source'],
            'no'           => $params['no'],//将输入关键字中的中文逗号转化为英文逗号
            'progress'     => $params['progress'],
            'date'         => $params['date'],
            'projectSrc'   => $params['projectSrc'],
            'time'         => $date
        ];
        $update=db('project')->where('id',$id)->update($data);
        if($update){
                return json(array('code'=>200,'msg'=>'提交成功'));
        }else{
                return json(array('code'=>0,'msg'=>'提交失败'));
        }
        }
    return $this->fetch('');
  }
}
?>