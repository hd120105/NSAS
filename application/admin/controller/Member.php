<?php
/*
 * type{0: 老师 1: 在校学生 2: 毕业学生}
 */
namespace app\admin\controller;
use think\Controller;
use think\Paginator;
class Member extends Base
{
  public function index()
  {
        return $this->fetch('index');
  }
  public function teacher()
  {
      if(request()->isPost())//搜索
      {
        $key   = input('search');
        $search['name'] = ['like','%'.$key.'%'];
        $search['type'] = ['=',0];
        $count_teacher = db('member')->where($search)->count();
        $teacher = db('member')->where($search)->paginate(10);
        $page_teacher = $teacher->render();//渲染分页，thinkphp/paginator/driver
        $this->assign('count_teacher',$count_teacher);
        $this->assign('teacher',$teacher);
        $this->assign('page_teacher',$page_teacher);
      }else{
        $count_teacher = db('member')->where(['type'=>0])->count();
        $teacher = db('member')->where(['type'=>0])->paginate(5);
        $page_teacher = $teacher->render();
        $this->assign('count_teacher',$count_teacher);
        $this->assign('teacher',$teacher);
        $this->assign('page_teacher',$page_teacher);
      }
      return $this->fetch('Member/teacher');
  }
  public function student()
  {
       if(request()->isPost())
      {
        $key   = input('search');
        $search['name'] = ['like','%'.$key.'%'];
        $search['type'] = ['=',1];
        $count_student = db('member')->where($search)->count();
        $student = db('member')->where($search)->paginate(5);
        $page_student = $student->render();
        $this->assign('count_student',$count_student);
        $this->assign('student',$student);
        $this->assign('page_student',$page_student);
      }else{
        $count_student = db('member')->where(['type'=>1])->count();
        $student = db('member')->where(['type'=>1])->paginate(5);
        $page_student = $student->render();
        $this->assign('count_student',$count_student);
        $this->assign('student',$student);
        $this->assign('page_student',$page_student);
      }
      return $this->fetch('Member/student');
  }
  public function graduate()
  {
       if(request()->isPost())
      {
        $key   = input('search');
        $search['name'] = ['like','%'.$key.'%'];
        $search['type'] = ['=',2];
        $count_graduate = db('member')->where($search)->count();
        $graduate = db('member')->where($search)->paginate(5);
        $page_graduate = $graduate->render();
        $this->assign('count_graduate',$count_graduate);
        $this->assign('graduate',$graduate);
        $this->assign('page_graduate',$page_graduate);
      }else{
        $count_graduate = db('member')->where(['type'=>2])->count();
        $graduate = db('member')->where(['type'=>2])->paginate(5);
        $page_graduate = $graduate->render();
        $this->assign('count_graduate',$count_graduate);
        $this->assign('graduate',$graduate);
        $this->assign('page_graduate',$page_graduate);
      }
      return $this->fetch('Member/graduate');
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
             $url = '__INDEX__/uploads/personPic/'.$pic['savename'];
          }  else {
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
    $file = request()->file('file'); // 移动到框架应用根目录/public/uploads/personPic 目录下
    $info = $file->move(ROOT_PATH . 'public/static/index/uploads' . DS . 'personPic');
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
public function Add()
{	 
    if(request()->isPost())
    {
        $params = input('post.');
        $date = date("Y-m-d H:i:s");
        if($params['typeSelected']==0)//老师
        {
            $data=[
                'name'         => $params['name'],          //姓名
                'type'         => $params['typeSelected'],  //类型 0: 老师 1: 学生 2: 已毕业学生
                'title'        => $params['title'],         //老师头衔
                'manager'      => $params['manager'],         
                'area'         => $params['area'],          //研究方向
                'achievement'  => $params['content'],       // 老师主要成就
                'email'        => $params['email'],         //邮箱
                'pic'          => $params['url'],            //头像
                'time'         => $date
             
                ];
        }else if($params['typeSelected']==1)//学生
        {
            $data=[
                'name'         => $params['name'],          //姓名
                'type'         => $params['typeSelected'],  //类型 0: 老师 1: 学生 2: 已毕业学生
                'area'         => $params['area1'],          //研究方向
                'educate'      => $params['educate1'],       //学生学历
                'degree'       => $params['degree'],         //年级
                'major'        => $params['major1'],         //专业
                'email'        => $params['email1'],         //邮箱
                'pic'          => $params['url'],             //头像
                'time'         => $date
            ];
        }
        else if($params['typeSelected']==2)//毕业生
        {
            $data=[
                'name'             => $params['name'],          //姓名
                'type'             => $params['typeSelected'],  //类型 0: 老师 1: 学生 2: 已毕业学生
                'educate'          => $params['educate'],       //学生学历
                'major'            => $params['major'],         //专业
                'company'          => $params['company'],       //毕业生就职公司
                'job'              => $params['job'],           //毕业生就职职位
                'gra_time'        => $params['gra_time'],         //邮箱
                'pic'             => $params['url'],            //头像
                'time'            => $date
            ];
        }
        if(db('member')->insert($data))
        {
               return json(array('code'=>200,'msg'=>'添加成功'));
        }else{
             return json(array('code'=>0,'msg'=>'添加失败' ));
        }
           
     }    
        return $this->fetch('');
  } 
  
public function Del() //删除文章
{
    if(db('member')->delete(input('id'))){
      return json(array('code'=>200,'msg'=>'删除成功'));
    }else{
      return json(array('code'=>0,'msg'=>'删除失败'));
    }
}
public function Stop() //文章下架
{
    if(request()->isPost())
    {
      if(db('member')->where('id',input('id'))->update(['status'=>2]))
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
        if(db('member')->where('id',input('id'))->update(['status'=>1]))
        {
            return json(array('code'=>200,'msg'=>'已发表'));
        } else {
            return json(array('code'=>0,'msg'=>'发表不成功'));
        }
    }
 }
 public function Edit()
 {
       
    $member=db('member')->where('id',input('id'))->select();
    $this->assign('member',$member); 
    $id = input('id');
    $type = input('type');
    $date = date("Y-m-d H:i:s");
    if(request()->isPost())
    {
        $params = input('post.');
        if($params['typeSelected']==0)//老师
        {
            $data=[
            'name'         => $params['name'],          //姓名
            'type'         => $params['typeSelected'],  //类型 0: 老师 1: 学生 2: 已毕业学生
            'title'        => $params['title'],         //老师头衔
            'manager'      => $params['manager'],
            'area'         => $params['area'],          //研究方向
            'achievement'  => $params['content'],       // 老师主要成就
            'email'        => $params['email'],         //邮箱
            'pic'          => $params['url'],            //头像
            'time'         => $date
            ];
        }else if($params['typeSelected']==1)//学生
        {
            $data=[
                'name'         => $params['name'],          //姓名
                'type'         => $params['typeSelected'],  //类型 0: 老师 1: 学生 2: 已毕业学生
                'area'         => $params['area1'],          //研究方向
                'educate'      => $params['educate1'],       //学生学历
                'degree'       => $params['degree'],         //年级
                'major'        => $params['major1'],         //专业
                'email'        => $params['email1'],         //邮箱
                'pic'          => $params['url'],            //头像
                'time'         => $date
            ];
        }
        else if($params['typeSelected']==2)//毕业生
        {
            $data=[
                'name'             => $params['name'],          //姓名
                'type'             => $params['typeSelected'],  //类型 0: 老师 1: 学生 2: 已毕业学生
                'educate'          => $params['educate'],       //学生学历
                'major'            => $params['major'],         //专业
                'company'          => $params['company'],       //毕业生就职公司
                'job'              => $params['job'],           //毕业生就职职位
                'gra_time'        => $params['gra_time'],        //邮箱
                'time'            => $date
                
            ];
        }
        $update=db('member')->where('id',$id)->update($data);
        if($update){
            return json(array('code'=>200,'msg'=>'提交成功'));
        }else{
            return json(array('code'=>0,'msg'=>'提交失败'));
        }

    }
    if($type==0)
    {
        return $this->fetch('Member/teacherEdit');
    }elseif($type==1){
       return $this->fetch('Member/studentEdit');
    }else{
       return $this->fetch('Member/graduateEdit');
    }
 }   
}
?>