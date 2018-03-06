<?php
namespace app\admin\controller;

class Main extends Base{

	public function index(){
        return view();
	}



	public function delCache(){
		//\think\Cache::clear();
		//$this->del_dir(LOG_PATH);
        $this->del_dir(CACHE_PATH);
        //$this->del_dir(TEMP_PATH);
        //$this->del_dir(RUNTIME_PATH);
        return $this->success('清理成功');        
	}


	/**
	 * 删除目录（包括下面的文件）
	 * @return void
	 */
	protected function del_dir($directory, $subdir = true) {
	    //$directory=str_replace("\\","/",$directory);
	    if (is_dir($directory) == false) {
	        return false;
	    }
	    $handle = opendir($directory);
	    while (($file = readdir($handle)) !== false) {
	        if ($file != "." && $file != "..") {
	            is_dir("$directory/$file") ?$this->del_dir("$directory/$file") : unlink("$directory/$file");
	        }
	    }
	    if (readdir($handle) == false) {
	        closedir($handle);
	        rmdir($directory);
	    }
	}

}