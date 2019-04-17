<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2019/4/9
 * Time: 17:35
 */
namespace app\index\model;

use app\index\Traits\Date;
use app\index\Traits\User;
use think\Db;
use think\Model;

class TaskModel extends Model{

	/**
	 * @var $data
	 * 用户资料
	 */
	protected $data;

	/**
	 * TaskModel constructor.
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
	 * @return int
	 * 检测任务完成度
	 */
	public function task(){
		$width['task'] = 0;
		$arr = ['signed','note','content','good','recharge'];
		for ($i = 0; $i < count($arr); $i++) {
			$width[$arr[$i]] = "";  //初始值
		}
		$time = array(Date::getNowStartTime(), Date::getNowEndTime());
		$signed = Db::table('signed')->where(User::username())->whereTime('date', 'between', $time)->select(); //检测今天是否签到
		if ($signed) {
			$width['signed'] = 1;
			$width['task'] = $width['task'] + 20;
		}
		$dayNote = Db::table('note')->where(User::username())->whereTime('date', 'between', $time)->find();   //查询今天是否发过帖子
		if ($dayNote){
			$width['note'] = 1;
			$width['task'] = $width['task'] + 20;
		}
		$dayContent = Db::table('content')->where(User::username())->whereTime('date', 'between', $time)->find();   //查询今天是否评论过
		if ($dayContent) {
			$width['content'] = 1;
			$width['task'] = $width['task'] + 20;
		}
		$dayGood = Db::table('good')->where(User::username())->whereTime('date', 'between', $time)->find();   //查询今天是否点过赞
		if ($dayGood) {
			$width['good'] = 1;
			$width['task'] = $width['task'] + 20;
		}
		$dayRecharge = Db::table('recharge')->where(User::username())->whereTime('date', 'between', $time)->find();   //查询今天是否充值
		if ($dayRecharge) {
			$width['recharge'] = 1;
			$width['task'] = $width['task'] + 20;
		}
		return $width;
	}

	/**
	 * @param $msg
	 * @throws \Exception
	 * 领取奖品
	 */
	public function getLihe( $msg ){
		if(empty($msg)){
			throw new \Exception('{"code":"0","msg":"用户未登录!"}');
		}
		$time = array(Date::getNowStartTime(), Date::getNowEndTime());
		$signed = Db::table('signed')->where(User::username())->whereTime('date', 'between', $time)->find(); //检测今天是否签到
		$dayNote = Db::table('note')->where(User::username())->whereTime('date', 'between', $time)->find();   //查询今天是否发过帖子
		$dayContent = Db::table('content')->where(User::username())->whereTime('date', 'between', $time)->find();   //查询今天是否评论过
		$dayGood = Db::table('good')->where(User::username())->whereTime('date', 'between', $time)->find();   //查询今天是否点过赞
		$dayRecharge = Db::table('recharge')->where(User::username())->whereTime('date', 'between', $time)->find();   //查询今天是否充值
		$arr = [$signed,$dayNote,$dayContent,$dayGood,$dayRecharge];
		for($i = 0; $i < count($arr); $i++){
			if(empty($arr[$i])){
				throw new \Exception('{"code":"0","msg":"领取失败,请做完任务再领取!"}');
			}
		}
		switch ($this->data['task']){
			case 1:
				throw new \Exception('{"code":"0","msg":"你已领取过,请不要重复!"}');
				break;
			case 0:
				Db::table('user')->where(User::username())->update(['task' => 1]);
				Db::table('user')->where(User::username())->setInc('money',100);
				throw new \Exception('{"code":"1","msg":"领取成功!"}');
				break;
			default: null;
		}
	}
}