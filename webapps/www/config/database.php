<?php
if (IS_LOCAL) {
	//本地
	$config['host']   = '127.0.0.1';
	$config['user']   = 'root';
	$config['pass']   = '123456';
	$config['dbname'] = 'lnest_pintuan_v2';
	$config['pre']    = 'zz_';
} else {
	//服务器
	//$config['host']   = 'localhost';
	$config['host']   = '127.0.0.1';
	$config['user']   = 'root';
	$config['pass']   = '123456';
    $config['dbname'] = 'suyangpintuan';
	$config['pre']    = 'zz_';
}