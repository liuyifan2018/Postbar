<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2019/4/16
 * Time: 16:51
 */
namespace app\super\Traits;

use think\facade\Session;
use think\Db;

trait Data{
	/**
	 * 验证登录
	 */
	public function isUser(){
		$data = Session::get('data');
		if(empty($data)){
			header('Location: http://localhost/Postbar/Public/Super/User/login');  //用户未登录跳入登录页
		}
	}
	/**
	 * 用户信息
	 */
	public static function dataInfo(){
		$data = Session::get('data');
		$data = Db::table('user')->where(array('id' => $data['id']))->find();  //我的信息
		return $data;
	}
}
