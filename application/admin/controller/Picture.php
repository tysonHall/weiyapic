<?php
namespace app\admin\controller;
use think\Db;

class Picture extends Base
{
	var $big_maxwidth = 500;
	var $big_maxheight = 800;
	function index()
	{
		$userid = input('userid','','strip_tags');
		$where = '1=1';
		if($userid != '') {
			$where .= " and userid=$userid";
		}
		$data = Db::name('image')->alias('a')->join('category b','a.typeid=b.id','LEFT')->field('a.*,b.channel')->where($where)->order('a.addtime desc')->paginate(16,false,['query' => request()->param()]);
		// dump($data);exit;
		$this->assign('webtitle','相片列表');
		$this->assign('data',$data);
		return $this->fetch();
	}

	function add()
	{
		set_time_limit(0);
		if(!request()->isPost()){

			$typeid = input('typeid','','strip_tags');
			$types = get_category(1);
			if($typeid == '')
			{
				$typeid = isset($types[0])?$types[0]['id']:0;
			}

			$this->assign('typeid',$typeid);
			$this->assign('types',$types);
			$this->assign('webtitle','相片上传');			
			return $this->fetch();
		}
		$userArr = session('user_auth');
		$userid = $userArr['uid'];
		$username = $userArr['username'];
		$typeid = input('post.typeid');
		$data = array();
		$num = 0;
		$jump = 'admin/picture/add?typeid='.$typeid;
		$filearr = request()->file('image');
		if(count($filearr) > 9) {
			return $this->error('至多9张图片',$jump);
		}
		// ob_start();  
		// var_dump(request());  
		// $result = ob_get_clean();
		// file_put_contents('file.log', date('Y-m-d H:i:s').':'.$result."\r\n", FILE_APPEND);
		foreach($filearr as $file){
			$info = $file->validate(['ext'=>'jpg,png,gif,jpeg'])->move(ROOT_PATH . 'public' . DS . 'uploads' . DS . 'picture');			

		    if($info){	
		    	$infoarr = $info->getInfo(); 
		    	//原图地址   	
				// file_put_contents('file.log', date('Y-m-d H:i:s').':'.$info->getSaveName()."\r\n", FILE_APPEND);
		        $imageurl1 = "/uploads/picture/" . str_replace('\\','/',$info->getSaveName());  
		        //缩略图地址
		        $imageurl2 = substr($imageurl1,0,strpos($imageurl1, '.'.$info->getExtension())) . '_small.' . $info->getExtension();
		        //大图详情地址
		        $imageurl3 = substr($imageurl1,0,strpos($imageurl1, '.'.$info->getExtension())) . '_big.' . $info->getExtension();
		        
		        $image = \think\Image::open('.'.$imageurl1);
		        //详情图片裁剪	        
		        $o_width = $image->width();
		        $o_height = $image->height();

		        $n_width = $this->big_maxwidth;
		        $rate = $o_width/$n_width;
		        $n_height = floor($o_height/$rate);
		        if($n_height > $this->big_maxheight)
		        {
		        	$n_height = $this->big_maxheight;
		        	$rate = $o_height/$n_height;
		        	$n_width = floor($o_width/$rate);
		        }
		        $image->thumb($n_width, $n_height,\think\Image::THUMB_SOUTHEAST)->save('.'.$imageurl3);
		        //生成缩略图
				$thumb_height = $n_height*212/$n_width;
		        $image->thumb(212,$thumb_height,\think\Image::THUMB_SOUTHEAST)->save('.'.$imageurl2);

		        $data[] = array(
		        		'userid'  => $userid,
		        		'username'  => $username,
		        		'imageurl1'  => $imageurl1,
		        		'imageurl2'  => $imageurl2,
		        		'imageurl3'  => $imageurl3,
		        		'imagesize'  => $infoarr['size'],
		        		'width'  => 212,
		        		'height'  => $thumb_height,
		        		'typeid'     => $typeid,
		        		'addtime'    => time(),
		        	);
		       	$num++;
		       	unset($image);
		    }else{
		        continue;
		    }
	    }
	    if($data){
	    	Db::name('image')->insertAll($data);
	    	return $this->success('成功上传'.$num.'/'.count($filearr).'张图片',$jump);
	    }else{
	    	return $this->error('你没有选择图片',$jump);
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

		$data = db()->query("SELECT imageurl1,imageurl2,imageurl3 FROM pic_image WHERE $where");

		$type = input('?param.type') ? input('type') : '';
		$r = Db::name('image')->where($where)->delete();
		if($r){

			foreach ($data as $k => $v) {
				del_image($v);
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