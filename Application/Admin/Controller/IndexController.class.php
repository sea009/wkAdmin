<?php
namespace Admin\Controller;
use Admin\Controller;
class IndexController extends CommonController{
    
    function __construct(){
        parent::__construct();
    }
    
    public function index(){
        //列表
        $moduleList = session('moduleList');

        //获取面板
        $this->assign('list', $moduleList);
        $this->display('Global:index');
    }
    
    public function panl(){
        $this->display('index');
    }

}