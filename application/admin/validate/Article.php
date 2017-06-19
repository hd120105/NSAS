<?php
namespace app\admin\validate;
use think\Validate;
class Article extends Validate
{
    protected $rule = [
        'title'  =>  'require|max:25',
    ];

    protected $message  =   [
        'title.require' => '文章标题必须填写',
        'title.max' => '文章标题长度不得大于25位',

    ];

    protected $scene = [
        'add'  =>  ['title'],
        'edit'  =>  ['title'],
    ];




}
?>