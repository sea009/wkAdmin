<?php
return array(
	//'配置项'=>'配置值'
    'TMPL_PARSE_STRING' => array(
        '__PUBLIC__'     => ADMIN_SRC,
        '__STATIC__'     => ADMIN_SRC.'/Static',
        '__CORE__'       => ADMIN_SRC.'/Admin/H-ui',
        '__JS__'         => ADMIN_SRC.'/Admin/js',
        '__CSS__'        => ADMIN_SRC.'/Admin/css',
        '__IMAGES__'     => ADMIN_SRC.'/Admin/images',
    ),

    'SESSION_AUTO_START' => true, //是否开启session

    'TMPL_L_DELIM'=>'<{',
    'TMPL_R_DELIM'=>'}>',

    //表单令牌配置
    'TOKEN_ON' => false, // 是否开启令牌验证 默认关闭
    'TOKEN_NAME' => '__hash__', // 令牌验证的表单隐藏字段名称，默认为__hash__
    'TOKEN_TYPE' => 'md5', //令牌哈希验证规则 默认为MD5
    'TOKEN_RESET' => true, //令牌验证出错后是否重置令牌 默认为true

);