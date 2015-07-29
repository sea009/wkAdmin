<?php
/**
 *简单权限控制
 */
namespace Admin\Logic;
use Think\Controller;
class AuthLogic extends Controller{
    
    private $login_user_id = 0;
    
    function __construct($usreid){
        $this->login_user_id = $usreid;
    }
    /**
     *登录初始化权限
     */
    public function init_auth($user){
        $return = array();
        $return['status'] = 0;
        if($user['department_id'] == 0 && $user['role_id'] == 0){
            $return['info'] = '无效角色！！！请联系管理员';
            return $return;
        }
        //查出拥有的权限ID
        $nodeIdArr = array();
        if($user['department_id'] != 0){
            $rid = M('sys_department')->where("id='%d'",$user['department_id'])->getField('rid');
            $node_a =  M('sys_role')->where("id='%d' AND  status=0",$rid)->getField('authority');
            $temp = explode(',',$node_a);
            $nodeIdArr = array_merge($nodeIdArr,$temp);
        }

        $isSuperAdmin = false;
        if($user['role_id'] != 0){
            $roleInfo =  M('sys_role')->where("id='%d' AND status=0",$user['role_id'])->find();
            if($roleInfo['authority'] == '-1'){
                $isSuperAdmin = true;
            }else{
                $temp = explode(',',$roleInfo['authority']);
                $nodeIdArr = array_merge($nodeIdArr,$temp);
            }
        }
        $nodeIdArr = array_unique($nodeIdArr);

        //如果该角色被锁定则不能操作
        if (empty($nodeIdArr) && !$isSuperAdmin) {
            $return['info'] = '该角色异常！！！请联系管理员';
            return $return;
        }
        $mNode = D('SysNode');
        $map = array();
        if ($isSuperAdmin) {//超级管理员
            $map['status'] = array('EGT', 0);
        } else {
            $map['id'] = array('in', $nodeIdArr);
            $map['status'] = array('EGT', -1);
        }
        $nodeList = $mNode->getAllData($map, true);
        //把目录存入session
        session('moduleList', $nodeList['node_list']);
        if (!$isSuperAdmin) {
            session('urlNode', $nodeList['node_url']);
        }else{
            session('isSuperAdmin', true);
        }
        $return['status'] = 1;
        return $return;
    }

    /**
     *判断是否有权限
     */
    public function check_auth(){
        if (session('isSuperAdmin') === true) {
            return true;
        }
        /*免权限控制器*/
        $controllerName = CONTROLLER_NAME;
        $free_access_controller = empty(C('FREE_ACCESS_CONTROLLER')) ? array() : C('FREE_ACCESS_CONTROLLER');
        if (in_array($controllerName, $free_access_controller)) {
            return true;
        }
        /*免权限url*/
        $actionName = $controllerName . '-' . ACTION_NAME;
        $free_access_action = empty(C('FREE_ACCESS_ACTION')) ? array() : C('FREE_ACCESS_ACTION');
        if (in_array($actionName, $free_access_action)) {
            return true;
        }
        /*免权限方法*/
        $free_method = empty(C('FREE_ACCESS_METHOD')) ? array() : C('FREE_ACCESS_METHOD');
        if (in_array(ACTION_NAME, $free_method)) {
            return true;
        }
        //获取session中的权限
        $urlNode = session('urlNode');
        if ($urlNode[$actionName]) {
            return true;
        }
        return false;
    }
}