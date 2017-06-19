<?php
namespace app\admin\controller;
use think\Controller;

class Index extends Base
{
    public function index()
    {
        return $this->fetch('index1');
	}
	public function home()
	{
		return view('home');
	}
}
?>
