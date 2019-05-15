<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2019/5/14
 * Time: 15:39
 */
namespace app\sadmin\model;

use think\Db;
use think\Model;

class CateModel extends Model{
	/**
	 * @var $data
	 * 用户资料
	 */
	protected $data;

	/**
	 * CateModel constructor.
	 * @param $data
	 * @throws \Exception
	 */
	public function __construct($data)
	{
		parent::__construct();
		if( empty( $data ) ) throw new \Exception('用户未登录!');
		$this->data = $data;
	}

	/**
	 * @return mixed
	 * 获取数据
	 */
	public function cate(){
		$tion = Db::table('shop_tion')->where(['is_del' => 1])->select();
		foreach ($tion as $k => $v){
			$tion[$k]['name'] = Db::table('user')->where(['username' => $v['username']])->value('name');
		}
		return $tion;
	}

	/**
	 * @param $data
	 * @throws \Exception
	 * 添加分类
	 */
	public function addCate( $data ){
		$data['is_show'] = 1;
		$data['is_del'] = 1;
		$data['username'] = $this->data['username'];
		if($data['title'] == ""){
			throw new \Exception('{"code":"0","msg":"标题不能为空!"}');
		}
		$titleList = Db::table('shop_tion')->value('title');
		for ($i = 0; $i < count($titleList); $i++){
			if($data['title'] == $titleList[$i]){
				echo 1;
			}
		}
		if($data['sort'] < 1 || !is_numeric($data['sort'])){
			$data['sort'] = 1;
		}
		Db::table('shop_tion')->insert($data);
		throw new \Exception('{"code":"1","msg":"添加成功!"}');
	}

	/**
	 * @param $data
	 * @throws \Exception
	 * 编辑分类
	 */
	public function editCate( $data ){
		if($data['sort'] == "" || !is_numeric($data['sort'])){
			$data['sort'] = 0;
		}
		if($data['username'] != $this->data['username']){
			throw new \Exception('{"code":"0","msg":"没有权限修改别人发布的分类！"}');
		}elseif($data['title'] == ""){
			throw new \Exception('{"code":"0","msg":"标题不能为空!"}');
		}
		Db::table('shop_tion')->where(['id' => $data['id']])->update($data);
		throw new \Exception('{"code":"1","msg":"修改成功!"}');
	}

	/**
	 * @param $id
	 * @param $value
	 * @return mixed
	 * @throws \Exception
	 * 分类信息
	 */
	public function infoCate( $id , $value){
		if(empty( $id )){
			throw new \Exception('{"code":"0","msg":"参数错误！"}');
		}
		$key = Db::table('shop_tion')->find(); //随便查找一条数据，为了获取字段
		$keys = array_keys($key);   //获取全部字段
		unset($key);
		if($value == ""){
			unset($value);
			$Cate = Db::table('shop_tion')->where(['id' => $id])->find();
		}else{
			if(!in_array($value,$keys)){    //检测字段名是否正确
				throw new \Exception('{"code":"0","msg":"没有字段名!"}');
			}
			$Cate = Db::table('shop_tion')->where(['id' => $id])->value($value);
		}
		return $Cate;
	}



	/**
	 * @param $data
	 * @throws \Exception
	 * 是否显示
	 */
	public function is_show($data){
		if(empty($data['id'])){
			throw new \Exception('{"code":"0","msg":"参数错误!"}');
		}
		Db::table('shop_tion')->where(['id' => $data['id']])->update(['is_show' => $data['type']]);
		if($data['type'] == 1){
			throw new \Exception('{"code":"1","msg":"已启用!"}');
		}else{
			throw new \Exception('{"code":"1","msg":"已禁用!"}');
		}
	}

	/**
	 * @param $data
	 * @throws \Exception
	 * 删除分类
	 */
	public function is_del( $data ){
		if(empty($data['id'])){
			throw new \Exception('{"code":"0","msg":"参数错误!"}');
		}
		Db::table('shop_tion')->where(['id' => $data['id']])->update(['is_del' => 2]);
		throw new \Exception('{"code:"1","msg":"删除成功!"}');
	}



}