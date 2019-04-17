<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2019/4/16
 * Time: 16:55
 */
namespace app\super\model;

use app\super\controller\Data;
use think\Db;
use think\Model;

class IndexModel extends Model{
	/**
	 * @var $data
	 * 用户资料
	 */
	protected $data;

	/**
	 * IndexModel constructor.
	 * @param $data
	 * @throws \Exception
	 */
	public function __construct($data)
	{
		parent::__construct();
		if( empty( $data )) throw new \Exception('用户未登录!');
		$this->data = $data;
	}

	public function welcome(){
		$info['note'] = Db::table('note')->where(['is_show' => 1])->count();    //帖子数量
		$info['admin'] = Db::table('user')->where(['ad' => 1])->count();    //会员人数
		return $info;
	}

}