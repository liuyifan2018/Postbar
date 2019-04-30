<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2019/4/17
 * Time: 15:09
 */
namespace app\super\model;

use think\Db;
use think\Model;

class AdminModel extends Model{
	/**
	 * @var $data
	 * 用户资料
	 */
	protected $data;

	/**
	 * AdminModel constructor.
	 * @param $data
	 * @throws \Exception
	 */
	public function __construct($data)
	{
		parent:: __construct();
		if( empty( $data ) ) throw new \Exception('用户未登录!');
		$this->data = $data;
	}

	/**
	 * @param $data
	 * @return mixed
	 * 会员列表
	 */
	public function member_list( $data ){
		if(empty( $data )){
			$admin['data'] = Db::table('user')->where(['ad' => 1,'is_del' => 1])->paginate(5,false,['query' => request()->param()]);
			$admin['count'] = count($admin['data']);
		}else{
			//搜索
			$admin['data'] = Db::table('user')->where(
				['email','like',$data.'%'],
				['name','like',$data.'%'],
				['username','like',$data.'%']
			)->select();
			$admin['count'] = count($admin['data']);
		}
		return $admin;
	}

	/**
	 * @param $data
	 * @throws \Exception
	 * 添加会员
	 */
	public function member_add( $data ){
		$arr = ['email','username','password','repassword','name'];
		for ($i = 0; $i < count($arr); $i++){
			if($data[$arr[$i]] == ""){
				throw new \Exception('{"code":"0","msg":"必填项不能为空!"}');
			}
		}
		if($data['password'] != $data['repassword']){
			throw new \Exception('{"code":"0","msg":"两次密码不一致!"}');
		}
		Db::table('user')->insert($data);
		throw new \Exception('{"code":"1","msg":"添加成功!"}');
	}

	/**
	 * @param $id
	 * @return mixed
	 * @throws \Exception
	 * 查询会员资料
	 */
	public function adminInfo( $id ){
		if( empty( $id ) ) throw new \Exception('{"code":"0","msg":"参数错误!"}');
		$adminInfo = Db::table('user')->where(['id' => $id])->find();
		return $adminInfo;
	}

	/**
	 * @param $data
	 * @throws \Exception
	 * 编辑会员信息
	 */
	public function member_edit( $data ){
		$arr = ['email','city','name','number'];
		for ($i = 0; $i < count($arr); $i++){
			if($data[$arr[$i]] == ""){
				throw new \Exception('{"code":"0","msg":"必填项不能为空!"}');
			}
		}
		Db::table('user')->where(['id' => $data['id']])->update($data);
		throw new \Exception('编辑成功!');
	}

	/**
	 * @param $data
	 * @throws \Exception
	 * 修改会员密码
	 */
	public function member_password( $data ){
		$arr = ['password','newpass','repass'];
		for ($i = 0; $i < count($arr); $i++){
			if($data[$arr[$i]] == ""){
				throw new \Exception('{"code":"0","msg":"必填项不能为空!"}');
			}
		}
		Db::table('user')->where(['id' => $data['id']])->update($data);
		throw new \Exception('{"code":"1","msg":"添加成功!"}');
	}

	/**
	 * @param $data
	 * @throws \Exception
	 * 会员禁用或启用
	 */
	public function stop( $data ){
		if(empty( $data['id'] ) ) throw new \Exception('{"code":"0","msg":"参数错误!"}');
		Db::table('user')->where(['id' => $data['id']])->update(['is_show' => $data['type']]);
		if($data['type'] == 1){
			throw new \Exception('{"code":"1","msg":"已上架!"}');
		}elseif($data['type'] == 0){
			throw new \Exception('{"code":"1","msg":"已下架!"}');
		}
		throw new \Exception('{"code":"0","msg":"操作失败!"}');
	}

	/**
	 * @return mixed
	 * 删除会员列表
	 */
	public function member_del(){
		$list = Db::table('user')->where(['is_del' => 2])->select();
		return $list;
	}

	/**
	 * @param $data
	 * @throws \Exception
	 * 删除会员
	 */
	public function del_admin( $data ){
		if( empty( $data['id'] ) ) throw new \Exception('{"code":"0","msg":"参数错误"}');
		Db::table('user')->where(['id' => $data['id']])->update(['is_del' => 2]);
		throw new \Exception('{"code":"0","msg":"删除成功!"}');
	}

	/**
	 * @param $data
	 * @throws \Exception
	 * 删除会员
	 */
	public function letme( $data ){
		if( empty( $data['id'] ) ) throw new \Exception('{"code":"0","msg":"参数错误"}');
		Db::table('user')->where(['id' => $data['id']])->update(['is_del' => 1]);
		throw new \Exception('{"code":"0","msg":"恢复成功!"}');
	}

}