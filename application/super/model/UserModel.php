<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2019/4/16
 * Time: 16:01
 */
namespace app\super\model;

use think\Db;
use think\Model;

class UserModel extends Model{
	/**
	 * UserModel constructor.
	 */
	public function __construct()
	{
		parent::__construct();
	}

	public function login( $msg ){
		if(empty($msg)){
			throw new \Exception('{"code":"0","msg":"必填项不能为空!"}');
		}
		$data = Db::table('user')->where(['username' => $msg['username']])->find();
		if(empty($data)){
			throw new \Exception('{"code":"0","msg":"用户不存在!"}');
		}elseif ($data['password'] != $msg['password']){
			throw new \Exception('{"code":"0","msg":"密码错误!"}');
		}elseif( $data['ad'] != 1){
			throw new \Exception('{"code":"0","msg":"没有超级管理权限!"}');
		}else{
			session('data',$data);
			throw new \Exception('{"code":"1","msg":"登录成功!"}');
		}
	}
}