<?php
namespace app\admin\model;
use think\Model;

class AuthRule extends Model{
	protected $auto = ['update_time','path','is_show','sort'];
	protected $insert = ['create_time'];
    
    protected $type       = [
        'create_time'     => 'timestamp',
        'update_time'     => 'timestamp',

    ];

    protected function setUpdateTimeAttr(){
		return time();
	}

	protected function setCreateTimeAttr(){
		return time();
	}
	
	protected function setPathAttr(){
		$type = input('post.type');
		$pid = input('post.pid');
		$path = 0;
		if($type == 1 && $pid != 0){
			$data = $this->field('path')->where('id',$pid)->find();
			$path = $data['path'] . '-' . $pid;
		}
		return $path;
	}

	protected function setIsShowAttr(){
		return input('?post.is_show') ? 1 : 0;
	}

	protected function setSortAttr(){
		return input('?post.sort') ? input('post.sort') : 0;
	}
}