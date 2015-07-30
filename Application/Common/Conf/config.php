<?php
return array(
	//'配置项'=>'配置值'
    'DEFAULT_MODULE'        =>  'Admin',  // 默认模块
    'TMPL_TEMPLATE_SUFFIX'  =>  '.htm',     // 默认模板文件后缀

    'URL_ROUTER_ON'   => true, //开启路由
    'URL_MODEL' => '2', //URL模式
    'URL_PATHINFO_DEPR'=>'-',

    'DB_TYPE' => 'mysqli', // 数据库类型
    'DB_HOST' => '192.168.6.6', // 服务器地址
    'DB_NAME' => 'wkAdmin', // 数据库名
    'DB_USER' => 'root', // 用户名
    'DB_PWD' => 'root', // 密码
    'DB_PORT' => '3306', // 端口
    'DB_PREFIX' => 'wk_', // 数据库表前缀
    'DB_CHARSET'=> 'utf8', // 字符集
);