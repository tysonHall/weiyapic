<?php

namespace app\index\controller;
use think\Controller;

class Index extends Controller
{
	var $count = 6; //每次请求的数量
	function index($id=0)
	{
		
		$this->assign('id',$id);
		$this->assign('count',$this->count);		
		return $this->fetch();
	}
}