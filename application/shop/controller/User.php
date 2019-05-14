<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2019/4/24
 * Time: 16:10
 */
namespace app\shop\controller;

use think\Controller;
use think\Db;
use think\facade\Session;

class User extends Controller{
	/**
	 * @return int|string|\think\response\View
	 * 登录
	 */
	public function login(){
		$str = "0123456789";
		$max = strlen($str) - 1;
		$username = "";
		for ($i = 0; $i < 10; $i++) {
			$username .= $str[mt_rand(0, $max)];
		}
		$user = Db::table('user')->select();
		foreach ($user as $k => $v){
			if($user[$k]['username'] == $username){
				for ($i = 0; $i < 10; $i++) {
					$username .= $str[mt_rand(0, $max)];
				}
			}
		}
		try{
			if(Request()->isPost()){
				$data = input('post.');
				$info = Db::table('user')->where(['username' => $data['username']])->find();
				if(empty($info)){
					throw new \Exception('{"code":"0","msg":"用户不存在!"}');
				}elseif($data['password'] != $info['password']){
					throw new \Exception('{"code":"0","msg":"密码错误!"}');
				}
				Session::set('data',$info);
				throw new \Exception('{"code":"1","msg":"登录成功!"}');
			}
		}catch (\Exception $e){
			return json_decode($e->getMessage(),true);
		}
		return view('login',[
			'user'  =>  $username
		]);
	}

	/**
	 * @return string
	 * 注册
	 */
	public function register(){
		if(Request()->isPost()){
			$data = input('post.');
			$arr = ['username','password','email','number'];
			for ($i = 0; $i < count($arr); $i++){
				if($data[$arr[$i]] == ""){
					return '必填项不能为空!';
				}
			}
			$preg_email='/^[a-zA-Z0-9]+([-_.][a-zA-Z0-9]+)*@([a-zA-Z0-9]+[-.])+([a-z]{2,5})$/ims';
			$preg_phone='/^1[34578]\d{9}$/ims';
			if(!preg_match($preg_email,$data['email'])){
			 	return '邮箱不正确!';
			}elseif(preg_match($preg_phone,$data['phone'])){
				return '手机号不正确!';
			}
			$register = Db::table('user')->insert();
			if(!$register){
				return '注册失败!';
			}
			return 1;
		}
	}
}