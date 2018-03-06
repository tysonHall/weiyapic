<?php
namespace app\admin\controller;
use think\Db;
use think\Image;
class Video extends Base
{
	function index()
	{
		$userid = input('userid','','strip_tags');
		$where = '1=1';
		if($userid != '') {
			$where .= " and userid=$userid";
		}
		$data = Db::name('video')->alias('a')->join('category b','a.typeid=b.id','LEFT')->field('a.*,b.channel')->where($where)->order('a.addtime desc')->paginate(16,false,['query' => request()->param()]);

		$this->assign('data',$data);
		$this->assign('webtitle','短视频列表');
		return $this->fetch();
	}

	function add()
	{

		if(!request()->isPost()){

			$typeid = input('typeid','','strip_tags');

			$types = get_category(2);
			if($typeid == '')
			{
				$typeid = isset($types[0])?$types[0]['id']:0;
			}

			$this->assign('typeid',$typeid);
			$this->assign('types',$types);

			$this->assign('webtitle',' 短视频上传');			
			return $this->fetch();
		}
		$userArr = session('user_auth');
		$userid = $userArr['uid'];
		$username = $userArr['username'];
		$typeid = input('post.typeid');
		$data = array();
		$num = 0;
		$jump = 'admin/video/add?typeid='.$typeid;

		$video = request()->file('video');

		if(empty($video)) {
			return $this->error('没有上传短视频',$jump);
		}



		//处理短视频
		$infovideo = $video->validate(['size'=>31770165,'ext'=>'mp4,mov'])->move(ROOT_PATH . 'public' . DS . 'uploads' . DS . 'video');
		if($infovideo) {
			//上传成功
			$videourl = '/uploads/video/' . str_replace('\\','/',$infovideo->getSaveName()); //视频路径
			$infovideoarr = $infovideo->getInfo(); 
			$imageurl = '/uploads/video/allimg/'.time().'.jpg';				
			$info = get_video_info(ROOT_PATH . 'public' . $videourl);
			// dump($info);exit;
			$width = isset($info['streams'][1]['width']) ? $info['streams'][1]['width'] : $info['streams'][0]['width'];
			$height = isset($info['streams'][1]['height']) ? $info['streams'][1]['height'] : $info['streams'][0]['height'];
			get_video_thumb(ROOT_PATH . 'public' . $videourl,ROOT_PATH . 'public' .$imageurl,$width/2,$height/2);
			
		}else{
			return $this->error($video->getError(),$jump);
		}

		$data = array(
			'userid'   => $userid,
			'typeid'   => $typeid,
			'username' => $username,
			'imageurl' => isset($imageurl) ? $imageurl : '',
			'videourl' => $videourl,
			'size'     => $infovideoarr['size'],
			'width'     => $width,
			'height'     => $height,
			'addtime'  => time(),
			);
		// dump($data);exit;
		$r = Db::name('video')->insert($data);
		if($r) {
			return $this->success('成功上传',$jump);
		}else {
			return $this->error('失败',$jump);
		}


	}

	function del()
	{
		$id = input('id');
		if(session('user_auth')['uid'] == 1){
			$where = "id in ($id)";
		}else{
			$where = "userid=".session('user_auth')['uid']." and id in ($id)";
		}
		$data = db()->query("SELECT videourl,imageurl FROM pic_video WHERE $where");

		$type = input('?param.type') ? input('type') : '';
		$r = Db::name('video')->where($where)->delete();
		if($r){

			foreach ($data as $k => $v) {
				del_video($v);
			}
			if($type == 'ajax'){
				echo json_encode(['state'=>1]);
			}else{
				return $this->success('删除成功');
			}
			
		}else{
			if($type == 'ajax'){
			    echo json_encode(['state'=>2]);
			}else{
				return $this->error('你无权删除');
			}			
		}
	}


}