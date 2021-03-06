<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2019/3/29
 * Time: 15:07
 */
namespace app\admin\controller;

use app\admin\model\ReportModel;
use app\admin\Traits\Whole;
use think\Controller;
class Report extends Controller{
    use Whole{
        isUser as public;
        isOutInsider as public;
    }
    /**
     * @var $model
     * 公共模型
     */
    protected $model;
    /**
     * @var $data
     * 用户资料
     */
    protected $data;

    /**
     * 初始化
     */
    public function initialize()
    {
        parent::initialize(); // TODO: Change the autogenerated stub
        try{
            self::isUser();
            self::isOutInsider();
            $data = Whole::dataInfo();
            $this->model = new ReportModel( $data );
        }catch (\Exception $e){
            $this->error( $e->getMessage() );
        }
    }
    public function model(){
        return new ReportModel( Whole::dataInfo() );
    }

    /**
     * @return mixed
     * 举报列表
     */
    public function report(){
        try{
            $this->model = $this->model();
            $tion = input('get.tion');
            $report = $this->model->report( $tion );
            $page = $report['data']->render();
            return view('report',[
	            'tion'  =>  $tion,
	            'report'  =>  $report,
	            'page'  =>  $page
            ]);
        }catch (\Exception $e){
            $this->error( $e->getMessage() );
        }
    }

    /**
     * @return mixed
     * 审核举报信息
     */
    public function is_report(){
        try{
            $this->model = $this->model();
            $msg = input('get.');
            $this->model->is_report( $msg );
        }catch (\Exception $e){
            return json_decode( $e->getMessage() ,true);
        }
    }

}