<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2019/4/24
 * Time: 14:50
 */
namespace app\super\model;

use think\Model;

class OrderModel extends Model{
	/**
	 * @var $data
	 * 用户资料
	 */
	protected $data;

	/**
	 * OrderModel constructor.
	 * @param $data
	 * @throws \Exception
	 */
	public function __construct($data)
	{
		parent::__construct();
		if(empty($data)) throw new \Exception('用户未登录!');
		$this->data = $data;
	}
}