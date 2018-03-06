<?php
namespace app\admin\controller;
use app\admin\model\AuthGroup as AuthGroupModel;

class AuthGroup extends Base{
	/**
	 * 角色列表
	 */
	public function index(){
		$roles = \think\Db::name('auth_group')->where('status',1)->paginate('10');
		$this->assign('roles', $roles);
		return view('index');
	}

	/**
	 * 添加角色
	 */
	public function add(){
		if(request()->isPost() && input('post.')){
			$authGroupModel = new AuthGroupModel;
			if($authGroupModel->save(input('post.'))){
				return $this->success('添加成功','auth_group/index');
			}else{
				return $this->error($authGroupModel->getError());
			}
		}else{
			return view('add');
		}
	}

	/**
	 * 编辑角色
	 */
	public function edit(){
		$authGroupModel = new AuthGroupModel;
		if(request()->isPost() && input('post.')){
			$id = input('?param.id') ? input('param.id') : '';
			if(!$id || $id==1){
				return $this->error('参数错误');
			}
			//$this->checkValidate();
			if($authGroupModel->save(input('post.'),['id'=>input('post.id')])){
				return $this->success('修改成功','index');
			}else{
				return $this->error('修改失败');
			}
		}else{
			$id = input('?param.id') ? input('param.id') : '';
			if(!$id){
				return $this->error('参数错误');
			}
			$data = $authGroupModel->field('id,title,description')->where('id',$id)->find();
			$this->assign('data',$data);
			return view('edit');
		}
	}

	/**
	 * 删除角色
	 */
	public function del(){
		$id = input('?param.id') ? input('param.id') : '';
		if(!$id || $id == 1){
			return $this->error('参数错误');
		}
		$authGroupModel = new AuthGroupModel;
		if($authGroupModel->where('id',$id)->delete()){
			return $this->success('删除成功');
		}else{
			return $this->error('删除失败');
		}
	}

	/**
	 * 资源管理
	 */
	public function resource(){
		if(request()->isPost() && input('post.')){
			$id = input('?post.id') ? input('post.id') : '';
			if(!$id || $id == 1){
				return $this->error('参数错误');
			}
			$authGroupModel = new AuthGroupModel;
			if($authGroupModel->isUpdate(true)->save(['rules'=>''],['id'=>$id])){
				$this->getSidebar();
				session('_auth_list_'.session('user_auth')['uid'].'1', null);
				return $this->success('修改成功');
			}else{
				return $this->error($authGroupModel->getError());
			}
		}else{

			$id = input('?param.id') ? input('param.id') : '';
			if(!$id){
				return $this->error('参数错误');
			}
			$data = \think\Db::name('auth_group')->field('id,title,rules')->where('id',$id)->find();
			$authRuleData = \think\Db::name('auth_rule')->field('id,name,title,pid,path')->where('type',1)->order('path,sort asc')->select();
			$resource = [];
			foreach ($authRuleData as $key => $value) {
				$path = explode('-', $value['path']);
				switch(count($path)){
					case 1:
						$resource[$value['id']] = $value;
						break;
					case 2:
						$resource[$path[1]]['child'][$value['id']] = $value;
						break;
					case 3:
						$resource[$path[1]]['child'][$path[2]]['child'][$value['id']] = $value;
						break;
				}
			}	
			$this->assign('resource', $resource);
			$this->assign('data', $data);
			return view('resource');
		}
	}
}