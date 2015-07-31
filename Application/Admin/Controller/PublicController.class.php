<?php
namespace Admin\Controller;

use Think\Controller;
class PublicController extends Controller{
    public function index(){
        die('not find file');
    }

    /**
     *生成验证码
     */
    public function simpleCaptcha(){
        $temp = array('seKey' => C('VERIFY_CODE'), 'length' => 4);
        $verify = new \Think\Verify($temp);
        $verify->entry(C('VERIFY_ID'));
        die;
    }
    /**
     *验证验证码
     */
    public function checkCaptcha($code){
        $temp = array('seKey' => C('VERIFY_CODE'));
        $verify = new \Think\Verify($temp);
        return $verify->check($code, C('VERIFY_ID'));
    }


    /*--- 以下为建权限方法 ---*/

    //登录
    public function login() {
        //如果已经登录则，进入主页
        if ($this->admin_id) {
            location(U(MODULE_NAME . '/Index/index'));
            exit();
        }

        //如果没有登录
        if (!IS_POST) {
            $this->display();
            exit();
        }

        //获取表单数据
        $passwd = $_POST['passwd'];
        $name   = $_POST['username'];

        //验证数据
        /* if (!$this->checkCaptcha($_POST['codeinput'])) {
            $this->error('验证码错误！');
        } */

        /* 验证是否存在该用户 */
        $admin = D('sys_admin');
        $data_get = $admin->where("name='%s'", $name)->find();
        if (!$data_get) {
            $this->error('该用户不存在');
            exit;
        }
        //如果该用户被锁定则不能登入
        if ($data_get['lock']) {
            $this->error('该用户已被锁定！！！请换其他帐号登录');
            exit;
        }
        //验证密码是否正确
        if (!strcmp(md5($passwd), $data_get['password'])) {
            $admin->where("id='%d'", $data_get['id'])->setField('login_ip', $_SERVER['REMOTE_ADDR']);
            //存入session
            session('admin_info', array('admin_id' => $data_get['id'], 'secret_key'=>md5($data_get['id']. C('secret_key')),'admin_authority' => M('sys_role')->where('id="%d"', $data_get['role_id'])->getField('authority'), 'admin_name' => $data_get['name']));

            //存入cookie
            cookie('admin_info', array('admin_name' => $data_get['name']));
            //获取登录用户管理模块
            $lAuthLogic = D('Auth', 'Logic');
            $return = $lAuthLogic->init_auth($data_get);
            if ($return['status'] != 1) {
                $this->error($return['info']);exit;
            } else {
                redirect(U('Index/index'));
            }
        } else {
            $this->error('密码验证失败');exit;
        }
    }
    /**
     * 注销登录
     * @author sea009
     */
    public function logout() {

        session('[destroy]');
        cookie('admin_info', null, 86400);
        showmessage('恭喜您已成功退出后台管理系统', U('Index/login'));
    }
}