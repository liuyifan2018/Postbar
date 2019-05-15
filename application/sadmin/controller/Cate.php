<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2019/5/14
 * Time: 12:00
 */
namespace app\sadmin\controller;

use app\sadmin\model\CateModel;
use app\sadmin\Traits\Whole;
use think\Controller;
use think\Request;

class Cate extends Controller{
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

	/**
	 * 初始化
	 */
	public function initialize()
	{
		parent::initialize(); // TODO: Change the autogenerated stub
		try{
			$data = Whole::dataInfo();
			$this->model = new CateModel( $data );
			$this->data = $data;
		}catch (\Exception $e){
			$this->error( $e->getMessage() );
		}
	}

	/**
	 * @return CateModel
	 * @throws \Exception
	 */
	public function model(){
		return new CateModel( Whole::dataInfo() );
	}

	/**
	 * @return \think\response\View
	 * 获取分类列表
	 */
	public function cate(){
		try{
			$this->model = $this->model();
			$tion = $this->model->cate();
			return view('cate',[
				'tion'  =>  $tion
			]);
		}catch (\Exception $e){
			$this->error( $e->getMessage() );
		}
	}

	/**
	 * @return mixed
	 * 添加分类
	 */
	public function addCate(){
		try{
			$this->model = $this->model();
			if(Request()->isPost()){
				$data = input('post.');
				$this->model->addCate( $data );
			}
			return view('addCate');
		}catch (\Exception $e){
			return json_decode( $e->getMessage() ,true);
		}
	}

	/**
	 * @return mixed|\think\response\View
	 * 编辑分类
	 */
	public function editCate(){
		try{
			$this->model = $this->model();
			if(Request()->isPost()){
				$data = input('post.');
				$this->model->editCate( $data );
				$cate = $this->model->infoCate( $data['id'] , '');
			}else{
				$id = input('get.id');
				$cate = $this->model->infoCate( $id ,'');
			}
			return view('editCate',[
				'cate'    =>  $cate
			]);
		}catch (\Exception $e){
			return json_decode( $e->getMessage() ,true);
		}
	}

	/**
	 * @return mixed
	 * 是否显示
	 */
	public function is_show(){
		try{
			$this->model = $this->model();
			$data = input('get.');
			$this->model->is_show( $data );
		}catch (\Exception $e){
			return json_decode( $e->getMessage() ,true);
		}
	}

	public function is_del(){
		try{
			$this->model = $this->model();
			$data = input('get.');
			$this->model->is_del( $data );
		}catch (\Exception $e){
			return json_decode( $e->getMessage() ,true);
		}
	}

}