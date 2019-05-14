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
		return $tion;
	}
}