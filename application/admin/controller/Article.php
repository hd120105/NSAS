<?php
namespace app\admin\controller;
use think\Controller;
use think\Paginator;
class Article extends Base
{
    public function index()
    {
        return $this->fetch('index');
	}
	public function articleList()//文章列表
	{
      if(request()->isPost())
      {
          if(input('search')){
            $key   = input('search');
            $search['title'] = ['like','%'.$key.'%'];
            $count = db('news')->where($search)->count();
            $Article = db('news')->where($search)->paginate(10);//status：{0：论文 | 1：专利 | 2: 软著}
            $page = $Article->render();
            $this->assign('count',$count);
            $this->assign('Article',$Article);
            $this->assign('page',$page);
          }else{
            $key   = input('type');
            $search['type'] = ['=',$key];
            $count = db('news')->where($search)->count();
            $Article = db('news')->where($search)->paginate(10);//status：{0：论文 | 1：专利 | 2: 软著}
            $page = $Article->render();
            $this->assign('count',$count);
            $this->assign('Article',$Article);
            $this->assign('page',$page);
             
          }
      }else{
	  $count = db('news')->count();
      $Article=db('news')->paginate(10);
      $article = array();
      $page = $Article->render();
      $this->assign('Article',$Article);
      $this->assign('page',$page);
      $this->assign('count',$count);
      }
      return $this->fetch('');
	}
	public function Add()
	{	 
        $date = date("Y-m-d H:i:s");
         if(request()->isPost())
        {
            $params = input('post.');
            $data=[
                'title'        => $params['title'],
                'author'       => session('username'),
                'keywords'     => str_replace('，', ',', $params['keyword']),//将输入关键字中的中文逗号转化为英文逗号
                'type'         => $params['type'],
                'content'      => $params['content'],
                'createtime'   => $date,
            ];
            $validate = \think\Loader::validate('Article');//验证
            if(!$validate->scene('add')->check($data)){
                 $this->error($validate->getError());
                die();
           }
            if(db('news')->insert($data)){
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
    if(db('news')->delete(input('id'))){
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
            if(db('news')->where('id',input('id'))->update(['status'=>2]))
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
            if(db('news')->where('id',input('id'))->update(['status'=>1]))
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
       
        $news = db('news')->where('id',input('id'))->select();
        $this->assign('news',$news); 
        $id   = input('id');
        $date = date("Y-m-d H:i:s");
        if(request()->isPost())
        {
            $params = input('post.');
            $data=[
                'title'        => $params['title'],
                'author'       => session('username'),
                'keywords'     => str_replace('，', ',', $params['keyword']),//将输入关键字中的中文逗号转化为英文逗号
                'type'         =>$params['type'],
                'content'      =>$params['content'],
                'createtime'   => $date
                // 'FilePath'    =>
            ];
           //  $validate = \think\Loader::validate('Article');//验证
           //  if(!$validate->scene('add')->check($data)){
           //       $this->error($validate->getError());
            
           //      die();
           // }
           $update=db('news')->where('id',$id)->update($data);
            if($update){
                  return json(array('code'=>200,'msg'=>'提交成功'));
            }
            else
            {
                  return json(array('code'=>0,'msg'=>'提交失败'));
            }

         }
        return $this->fetch('');
    }
    
}
?>