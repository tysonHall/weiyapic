<?php
namespace app\admin\controller;
use think\Db;
class Category extends Base
{
	function index()
	{
		$data = Db::name('category')->order('id asc')->select();
		$this->assign('data',$data);
		$this->assign('title','分类管理');
		return $this->fetch();
	}

	function add()
	{
		$data = input('post.');
		$data['addtime'] = time(); 

        $res = Db::name('category')->insert($data);
        if($res){
        	return json_encode(['state'=>1]);
        }else{
        	return json_encode(['state'=>2]);
        }		
	}

    function edit()
    {
    	$link = Db::name('category');
    	$id = input('id');
    	$make = input('?param.make') ? input('make') : '';
    	if($make == 'do'){
            $data['title'] = input('title');
            $data['channel'] = input('channel');
    		$data['id'] = $id;
            if($link->update($data)){
            	return json_encode(['state'=>1]);
            }
    	}
    	$data = $link->field('id,title,channel')->find($id);
    	if($data){
    		return json_encode($data);
    	}
    }

    function del($id)
    {
    	$res = Db::name('category')->delete($id);
         if($res){
            return $this->success('删除成功','index');
        }else{
            return $this->error('删除失败');
        }
    }
    //控制状态开关
    function switchstate($id)
    {
		$link = Db::name('category');
		$id = input('id');
		$state = $link->where('id',$id)->value('state');
		if($state === 1){

		     $link->update(['id'=>$id,'state'=>2]);
		     return json_encode(['code'=>1]);
		}else if($state === 2){

			 $link->update(['id'=>$id,'state'=>1]);
		     return json_encode(['code'=>2]);
		}
    }

}