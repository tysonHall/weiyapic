<?php
/*
 *获取资源控制器
 */

namespace app\index\controller;
use think\Controller;
use think\Db;

class Myfile extends Controller
{

	function ajax_file()
	{
		$typeid = input('typeid','','strip_tags');
		$num = input('num','','strip_tags');
		$count = input('count','','strip_tags');
		$table = input('table','','strip_tags');
		$data = Db::name($table)->where('typeid',$typeid)->order('id desc')->limit($num,$count)->select();
		echo json_encode($data);
		exit;

	}

	function ajax_newimg()
	{
		$maxid = input('maxid',0,'strip_tags');
		$typeid = input('typeid','','strip_tags');
		$table = input('table','','strip_tags');
		$count = input('count','','strip_tags');

		if($maxid == 0)
		{
			exit;
		}
		$data = Db::name($table)->where("typeid=$typeid AND id>$maxid")->order('id asc')->count();
		echo $data;
		exit;
	}
}