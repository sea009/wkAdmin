<?php
namespace Admin\Model;
use Extend\Tree;
use Think\Model;
class SysNodeModel extends Model{
    
	protected $tableName="sys_node";
	
	protected $_validate = array(
		array('code', '', 'url已存在', 0, 'unique', 1), // 在新增的时候验证pageurl字段是否唯一
		
		array('name', '', '节点名称已存在', 0, 'unique', 1),
		
		array('status', array(1, 0, -1), '显示范围不正确', 2, 'in'),
	);
	/**
	 *获取数据
	 */
	public function getAllData($map, $type = false){
        $return = array();
        $nodeList = $this->where($map)->order('sort desc')->field('id,pid,code,name,status')->select();
        $node_list = array();
        foreach ($nodeList as $val) {
            if ($val['status'] == 1) {
                $node_list[] = $val;
            }
            if (true === $type and !empty($val['code'])) {
                $return['node_url'][$val['code']] = true;
            }
        }
        import("Extend.Tree");
		$Tree = Tree::getInstance($node_list, array('id', 'pid'), 1, true);
        $return['node_list'] = $Tree->leaf(1);
        return $return;
	}
}