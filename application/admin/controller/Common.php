<?php
namespace app\admin\controller;

use think\Config;
use think\View;

/**
 * 后台公共控制类
 */
class Common{
    protected $view;//视图
    // 架构方法注入
    public function __construct() {
        $this->view = View::instance([], Config::get('replace_str'));
    }
}
?>