<?php
namespace Admin\Controller;
use Think\Controller;
/**
 * 权限控制表
 * @author sea009<835902479@qq.com>
 */
class AccessController extends CommonController {
	/**
	 * 部门列表
	 */
	public function departmentList(){
		$dp = M('sys_department');
		$dpList= $dp->order('sort desc')->select();
		$this->assign('dpList', $dpList);
		$this->display();
	}

	/**
	 * 添加部门
	 */
	public function departmentAdd(){
        if(!IS_POST){
            //查找所有角色
            $roleList = M('sys_role')->where('authority!=-1')->field('id,name')->select();
            $this->assign('roleList',$roleList);
            $this->display();die;
        }

		$dp = M('sys_department');
		if (false === $dp->data($_POST)->add()) {
			echo json_encode(array('status'=>'0','msg'=>'添加失败'));die;
		}
        echo json_encode(array('status'=>'1','msg'=>'添加成功'));die;
	}

	/**
	 * 修改部门
	 */
	public function departmentModif(){
        if(!IS_POST){
            //查找所有角色
            $roleList = M('sys_role')->where('authority!=-1')->field('id,name')->select();
            $this->assign('roleList',$roleList);

            //部门信息
            $id = I('id','','int');
            $this->assign('info',M('sys_department')->where("id='%d'",$id)->find());
            $this->display();die;
        }

		$dp = M('sys_department');
		$_POST['name'] = htmlentities($_POST['name']);
		$_POST['name'] = str_replace('&nbsp;', '', $_POST['name']);
		$_POST['name'] = trim($_POST['name']);
		if (false === $dp->data($_POST)->save()) {
			$this->error('修改失败');exit;
		}
		$this->success('修改成功');
	}

	/**
	 * 删除部门
	 */
	public function departmentDel(){
		$dp = M('sys_department');
		if ($dp->where('id="%d"', $_GET['id'])->getField('status') == 1){
			if (false !== $dp->where('id="%d"', $_GET['id'])->setField('status', 0)) {
				$this->success('操作成功');
			} else {
				$this->error('操作失败');
			}
		} else {
			if (false !== $dp->where('id="%d"', $_GET['id'])->setField('status', 1)) {
				$this->success('操作成功');
			} else {
				$this->error('操作失败');
			}
		}
	}

	/**
	 * 管理员列表
	 */
	public function adminList(){
		//获取管理员列表
		$admin = M('sys_admin');
        $dp = M('sys_department');
        $nowPage = empty($_GET['p'])?1:$_GET['p'];
        $where['status'] = 0;
        $retData = myPage($admin,$nowPage,10,$where,'','sort desc');
        $adminList = $retData['list'];
		foreach ($adminList as $k => $v) {
			$adminList[$k]['role'] = M('sys_role')->where('id="%d"', $v['role_id'])->getField('name');
			$adminList[$k]['department'] = $dp->where('id="%d"', $v['department_id'])->getField('name');
		}
		$this->assign('adminList', $adminList);
		//获取角色列表
        $this->assign('show',$retData['show']);
		$this->display();
	}

	/**
	 * 添加管理员
	 */
	public function adminAdd(){
        if(!IS_POST){
            //获取部门列表
            $dp = M('sys_department');
            $dpList= $dp->field('id,name')->order('sort desc')->select();
            $this->assign('dpList', $dpList);

            //获取角色列表
            $roleList = M('sys_role')->order('sort desc')->field('id,name')->select();
            $this->assign('roleList', $roleList);
            $this->display();die;
        }

        $data['name']       = I('name','','htmlentities');
        $data['code']       = I('code','','intval');
        $data['user_pin']   = I('user_pin','','htmlentities');
        $data['phone']      = I('phone','','htmlentities');
        $data['role_id']    = I('role_id','','int');
        $data['department_id'] = I('department_id','','int');
        $data['lock']       = I('lock');
        $data['limit_ip']   = I('limit_ip','','htmlentities');

        if(empty($data['name']) || empty($data['phone'])){
            echo json_encode(array('status'=>0,'msg'=>'添加失败'));die;
        }

		$admin = M('sys_admin');
		if (false === $admin->data($_POST)->add()) {
            echo json_encode(array('status'=>0,'msg'=>'添加失败'));die;
		}
        echo json_encode(array('status'=>200,'msg'=>'添加失败'));die;
	}

	/**
	 * 修改管理员
	 */
	public function adminModif(){
		if(!IS_POST){
			$id = I('id','','int');
		
			//获取部门列表
			$dp = M('sys_department');
			$dpList= $dp->field('id,name')->order('sort desc')->select();
			$this->assign('dpList', $dpList);
		
			//获取角色列表
			$roleList = M('sys_role')->order('sort desc')->field('id,name')->select();
			$this->assign('roleList', $roleList);
		
			//获取当前管理员的基本信息
			$adminInfo = M('sys_admin')->where("id='%d'",$id)->field('sort,status',true)->find();
			$this->assign('adminInfo',$adminInfo);
			$this->display();die;
		}
		$admin = M('sys_admin');
		if (false === $admin->data($_POST)->save()) {
			$this->error('修改失败');die;
		}
		$this->redirect('Access/adminList');
	}

	/**
	 * 删除管理员
	 */
	public function adminDel(){
		$admin = M('sys_admin');
		if ($admin->where('id="%d"', $_GET['id'])->getField('status') == 1){
			if (false !== $admin->where('id="%d"', $_GET['id'])->setField('status', 0)) {
				$this->success('操作成功');
			} else {
				$this->error('操作失败');
			}
		} else {
			if (false !== $admin->where('id="%d"', $_GET['id'])->setField('status', 1)) {
				$this->success('操作成功');
			} else {
				$this->error('操作失败');
			}
		}
	}

	/**
	 * 管理员禁用
	 */
	public function adminLocked(){
		$admin = M('sys_admin');
		if ($admin->where('id="%d"', $_GET['id'])->getField('lock') == 1){
			if (false !== $admin->where('id="%d"', $_GET['id'])->setField('lock', 0)) {
				$this->success('操作成功');
			} else {
				$this->error('操作失败');
			}
		} else {
			if (false !== $admin->where('id="%d"', $_GET['id'])->setField('lock', 1)) {
				$this->success('操作成功');
			} else {
				$this->error('操作失败');
			}
		}
	}

	/**
	 * 管理员密码初始化
	 */
	public function adminPasswordReset(){
		$admin = M('sys_admin');
		if (false !== $admin->where('id="%d"', $_GET['id'])->setField('password', md5('000000'))) {
			$this->success('操作成功');
		} else {
			$this->error('操作失败');
		}
	}

	/**
	 * 管理员密码修改
	 */
	public function passwordModif(){
		$admin = M('sys_admin');
		$admin_id = session('admin_info')['admin_id'];
		if ($_GET['act'] == 'mod') {
			if ($_POST['newPass'] !== $_POST['rePass']) {
				$this->error('两次密码输入不一致');die;
			}
			if (!$admin->where('id="'.$admin_id.'" and password="'.md5($_POST['oldPass']).'"')->getField('id')) {
				$this->error('旧密码错误');die;
			}
			if (false !== $admin->where('id="%d"', $admin_id)->setField('password', md5($_POST['newPass']))) {
				session('[destroy]');
		        cookie('admin_info', null, 86400);
		        showmessage('密码修改成功，请重新登录', U('Index/login'));
			} else {
				$this->error('密码修改失败');
			}
			die;
		}
		$this->assign('admin_name', session('admin_info')['admin_name']);
		$this->display();
	}

	/**
	 * 角色列表
	 */
	public function roleList(){
		//获取角色列表
        $sysrole = M('sys_role');
        $nowPage = empty($_GET['p'])?1:$_GET['p'];
        $retData = myPage($sysrole,$nowPage,10,'','','sort desc');
        $roleList = $retData['list'];
		$this->assign('roleList', $roleList);
        $this->assign('show', $retData['show']);
		$this->display();
	}

	/**
	 * 添加角色
	 */
	public function roleAdd(){
        if(!IS_POST){
            $nodeList = M('sys_node')->field('id,pid,name')->select();
            $nodeList['0']['open'] = true;
            $this->assign('advTypeDateJson',json_encode($nodeList));
            $this->display();die;
        }

		$role = M('sys_role');
		if (false === $role->data($_POST)->add()) {
            echo json_encode(array('status'=>0,'msg'=>'添加失败'));die;
		}
        echo json_encode(array('status'=>200,'msg'=>'添加成功'));die;
	}

	/**
	 * 修改角色
	 */
	public function roleModif(){
        if(!IS_POST){
            $id = I('id','','int');
            $nodeList = M('sys_node')->field('id,pid,name')->select();
            $nodeList['0']['open'] = true;
            $this->assign('advTypeDateJson',json_encode($nodeList));

            //基本信息
            $role = M('sys_role')->where("id='%d'",$id)->find();
            $this->assign('info',$role);
            $this->display();die;
        }

        if (false === M('sys_role')->data($_POST)->save()) {
            echo json_encode(array('status'=>0,'msg'=>'修改失败'));die;
        }
        echo json_encode(array('status'=>200,'msg'=>'修改成功'));die;
	}

	/**
	 * 删除角色
	 */
	public function roleDel(){
		$role = M('sys_role');
		if ($role->where('id="%d"', $_GET['id'])->getField('status') == 1){
			if ($role->where('id="%d"', $_GET['id'])->setField('status', 0)) {
				$this->success('操作成功');
			} else {
				$this->error('操作失败');
			}
		} else {
			if ($role->where('id="%d"', $_GET['id'])->setField('status', 1)) {
				$this->success('操作成功');
			} else {
				$this->error('操作失败');
			}
		}
	}

	/**
	 * 节点列表
	 */
	public function nodeList(){
        $nodeModel = M('sys_node');
        $firstNode = $nodeModel->where('rank=1 AND status<>-1')->field('id,name')->select();
		$firstNode[-1] = array('id'=>'1', 'name'=>'顶级分类');
		ksort($firstNode);
        $this->assign('firstNode',$firstNode);
        $nodeList = $nodeModel->where('status<>-1')->field('id,pid,name')->select();
        $nodeList['0']['open'] = true;
        $this->assign('advTypeDateJson',json_encode($nodeList));
        $this->display('nodeList');
	}

    public function getSecondNode(){
        $id = I('id', 0, 'int');
		$html = '';
		if ($id) {
			$twoNode = M('sys_node')->where("pid='%d' AND status<>-1",$id)->field('id,name')->select();
			if ($twoNode) {
				$this->assign('twoNode',$twoNode);
				$html = $this->fetch('getSecondNode');
			}
		}
        exit($html);
    }

	/**
	 * 添加节点
	 */
	public function nodeAdd(){
        $insertData = array();
        $pid  = I('pid');
		//键值倒序
		krsort($pid);
		//获取第一个有效值
		foreach ($pid as $val) {
			if ($val) {
				$insertData['pid'] = $val;
				break;
			}
		}
        $insertData['pid'] = empty($insertData['pid'])? 0 : $insertData['pid'];
        $insertData['name'] = I('name');
        $insertData['code'] = I('code','','trim');
        $insertData['sort'] = I('sort','','int');

        if(I('status') == 'on'){
            $insertData['status'] = 1;
        }else{
            $insertData['status'] = 0;
        }

		$node = M('sys_node');
		if ($insertData['pid'] == 0) {
            $insertData['rank'] = 1;
		} else {
			$pid = $node->where('id="%d"', $insertData['pid'])->getField('pid');
			if ($pid == 0) {
                $insertData['rank'] = 2;
			} else {
                $insertData['rank'] = 3;
			}
		}
		if (false === $node->data($insertData)->add()) {
            echo json_encode(array('status'=>0,'msg'=>'添加失败'));die;
		}
        echo json_encode(array('status'=>200,'msg'=>'添加成功'));die;
	}

	/**
	 * 修改节点
	 */
	public function nodeModif(){
		$isedit = I('save', 0, 'int');
		if ($isedit) {
			$node = M('sys_node');
			$_POST['name'] = I('name','','htmlentities');
			if (false === $node->data($_POST)->save()) {
				$this->error('修改失败');exit;
			}
			$this->success('修改成功');
		} else {
			$id = I(id,'','int');
            if(empty($id)){
                echo 0;die;
            }
            $info = M('sys_node')->where("id='%d'",$id)->find();
            $this->assign('aData',$info);
            $this->display();die;
		}
	}
	/**
	 * 删除节点
	 */
	public function nodeDel(){
		$node = M('sys_node');
        if ($node->where('id="%d"', $_GET['id'])->setField('status', '-1')) {
            $this->success('操作成功');
        } else {
            $this->error('操作失败');
        }
	}

	/**
	 * 操作日志列表
	 */
	public function logOperateList(){

	}

	/**
	 * 安全日志列表
	 */
	public function logSecurityList(){

	}

	/**
	 * 查看日志详细信息
	 */
	public function logDetail(){

	}

}
