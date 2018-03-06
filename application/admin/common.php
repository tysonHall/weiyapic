<?php
//得到分类
function get_category($channel,$state=1)
{
	$data = array();
	if($state == ''){
		$where = '1=1';
	}else{
		$where = "state = $state and channel=$channel";
	}
	$data = \think\Db::name('category')->where($where)->order('id asc')->select();

	return $data;
}

//处理缩略图转为超级链接
function image_to_url($imageurl)
{
	if($imageurl) {
		$imageurl = str_replace('\\', '/', $imageurl);
	}else{
		$imageurl = '/default.png'; 
	}
	return "<img src='".$imageurl."' width='60' />";
}
//通过分类id得到分类名
function get_category_by_id($channel,$typeid)
{
	$data = get_category($channel,'');
	foreach ($data as $k => $v) {
		if($v['id'] == $typeid) {
			return $v['title'];
		}
	}
}


//字节转化成兆
function size_b_to_mb($b)
{
    $b = (int)$b;
    return round(($b / 1048576),2);
    
}

function get_category_state($state)
{
	$statearr = [1=>'开启',2=>'禁用'];
	return $statearr[$state];
}

function del_image($data)
{
	$imageurl1 = ROOT_PATH . 'public' . DS . $data['imageurl1'];
	$imageurl2 = ROOT_PATH . 'public' . DS . $data['imageurl2'];
	$imageurl3 = ROOT_PATH . 'public' . DS . $data['imageurl3'];

	unlink($imageurl1);
	unlink($imageurl2);
	unlink($imageurl3);
}

function del_video($data)
{
	$videourl = ROOT_PATH . 'public' . DS . $data['videourl'];
	$imageurl = ROOT_PATH . 'public' . DS . $data['imageurl'];

	unlink($videourl);
	unlink($imageurl);
}

function set_channel()
{
	$arr = array('1'=>'图集','2'=>'视频');
	return $arr;
}

function channel_to_str($channel)
{
	$arr = set_channel();
	return $arr[$channel];
}
//视频截图
function get_video_thumb($videourl,$outimg,$width,$height) {   
   $command = "ffmpeg -v 0 -y -i $videourl -vframes 1 -ss 5 -vcodec mjpeg -f rawvideo -s {$width}x{$height} -aspect 16:9 $outimg";
   shell_exec( $command );
}

function get_video_info($videourl)
{
	$command = "ffprobe -v quiet -print_format json -show_format -show_streams $videourl";
	$info = shell_exec($command);
	return json_decode($info,true);
}

function get_category_num($id,$channel)
{
	if($channel == 1){
		$table = 'image';
	}else if($channel == 2){
		$table = 'video';
	}
	$count = \think\Db::name($table)->where(array('typeid'=>$id))->count();
	return $count;
}


