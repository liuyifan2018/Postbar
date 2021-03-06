<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2019/4/16
 * Time: 14:40
 */
namespace app\super\controller;

use app\super\model\IndexModel;
use app\super\Traits\Data;
use think\Controller;

class Index extends Controller{
	/**
	 * @var $data
	 * 用户资料
	 */
	protected $data;
	/**
	 * @var $model
	 * 公共模型
	 */
	protected $model;

	use Data{
		isUser as public;
	}
	public function initialize()
	{
		parent::initialize(); // TODO: Change the autogenerated stub
		try{
			self::isUser();
			$data = Data::dataInfo();
			$this->model = new IndexModel( $data );
			$this->data = $data;
		}catch (\Exception $e){
			$this->error( $e->getMessage() );
		}
	}

	/**
	 * @return IndexModel
	 * @throws \Exception
	 */
	public function model(){
		return new IndexModel( Data::dataInfo() );
	}

	/**
	 * @return \think\response\View
	 * @throws \Exception
	 * 首页
	 */
	public function welcome(){
		$this->model = $this->model();
		try{
			$info =  $this->model->welcome();
			return view('welcome',[
				'info'  =>  $info,
				'date'  =>  date('Y-m-d H:i:s',time()),
				'data'  =>  $this->data
			]);
		}catch (\Exception $e){
			$this->error( $e->getMessage() );
		}
	}

	/**
	 * @return \think\response\View
	 * 导航栏
	 */
	public function index(){
		return view('index',[
			'time'  =>  date('Y',time()),
			'data'  =>  $this->data
		]);
	}

}