<?php
namespace Admin\Controller;
use Think\Controller;
class CommonController extends Controller{
    
	public $admin_id = null;
	public $admin_name = '';

    function __construct(){
        parent::__construct();
		//检查 session 是否已经登录
		$users = session('admin_info');
		if($users){
			if (md5($users['admin_id']. C('secret_key')) != $users['secret_key']) {
				session_destroy();
				redirect(U('Public/login'));exit;
			}
			$this->admin_id		= $users['admin_id'];
			$this->admin_name	= $users['admin_name'];
		}else{
            redirect(U('Public/login'));exit;
		}
		$this->assign('loginUser', $users);

		/***----------权限控制-----------***/
		$AuthLogic = new \Index\Logic\AuthLogic($this->admin_id);
		if (true === $AuthLogic->check_auth()) {
		} else {
			if (IS_AJAX) {
				$this->error('没有操作权限');exit;
			} else {
				showmessage('没有该页面权限');exit;
			}
		}
    }

}
