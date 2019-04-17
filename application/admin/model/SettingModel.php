<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2019/3/22
 * Time: 11:42
 */

namespace app\admin\model;
use think\Db;
use think\Model;

class SettingModel extends Model
{
    /**
     * @var string
     * 数据库表名
     */
    protected $table = 'log';

    /**
     * @var $data
     * 用户信息
     */
    protected $data;

    /**
     * SettingModel constructor.
     * @param $data
     * @throws \Exception
     * 初始化
     */
    public function __construct($data)
    {
        parent::__construct();
        if( empty($data) ) throw new \Exception('用户未登录!');
        $this->data = $data;
    }

    /**
     * @return mixed
     * 用户登录日志
     */
    public function log(){
        $logList['data'] = Db::table('log')->where(array('is_show' => 1))->select();
        $logList['count'] = count($logList['data']);
        return $logList;
    }

	/**
	 * @param $data
	 * @throws \Exception
	 * 修改前端帖子显示数量
	 */
    public function set( $data ){
		if($data['items'] == ""){
			$data['items'] = 7;
		}elseif(!is_numeric($data['items'])){
			$data['items'] = 7;
		}elseif($data['items'] > 7){
			$data['items'] = 7;
		}
		Db::table('config')->where(['id' => 1])->update(['items' => $data['items']]);
	    throw new \Exception('{"code":"1","msg":"修改成功!"}');
    }

	/**
	 * @return mixed
	 * 显示数量
	 */
    public function item(){
	    $item = Db::table('config')->where(['id' => 1])->value('items');
	    return $item;
    }
}