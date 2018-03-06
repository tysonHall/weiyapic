<?php
namespace app\admin\controller;
use think\Controller;
use app\admin\logic\User as UserApi;
use think\captcha;
use think\Validate;

class Index extends Controller{
	/**
	 * 后台登录
	 * @return   [type]                   [description]
	 */
	public function index(){
		// 检测登录状态
		if(session('user_auth') && session('user_auth_sign')){
			$this->redirect('main/index');
		}
		if(request()->isPost()){
			$username = input('post.username');
			$password = input('post.password');
			/*$code = input('post.captcha');			
			if(!$code){
				return $this->error('请填写验证码','Index/index');
			}
			if(!captcha_check($code)){
				 return $this->error('验证码错误','Index/index');
			}*/
			if(!$username || !$password){
				return $this->error('请填写用户名或密码','Index/index');
			}
			$user = new UserApi;
			$uid = $user->login($username, $password);
			if($uid>0){
				/*记录session和cookie*/
				$group_id = \think\Db::name('auth_group_access')->field('group_id')->where('uid',$uid)->find();
				$auth = [
					'uid'=>$uid,
					'group_id'=>$group_id['group_id'],
					'username'=>$username,
					'last_login_time'=>time(),
				];
				session('user_auth',$auth);
				session('user_auth_sign', data_auth_sign($auth));
				return $this->success('登录成功','main/index');
			}else{
				switch ($uid) {
					case '-1':
						$error = '用户不存在或被禁用';
						break;
					case '-2':
						$error = '密码错误';
						break;
					
					default:
						$error = '未知错误';
						break;
				}
				return $this->error($error);
			}
		}else{
			return view();
		}
	}


}