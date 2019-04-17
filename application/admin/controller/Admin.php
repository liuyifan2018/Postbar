<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2019/2/27
 * Time: 14:36
 */
namespace app\admin\controller;
use app\admin\model\AdminModel;
use app\admin\Traits\Whole;
use think\Controller;
class Admin extends Controller{
    use Whole{
        isUser as public;
        isOutInsider as public;
    }
    /**
     * 公共模型
     * @var $model
     */
    protected $model;
    /**
     * @var $data
     * 用户信息
     */
    protected $data;

    /**
     * 实例化模型
     */
    public function initialize()
    {
        parent::initialize(); // TODO: Change the autogenerated stub
        try{
            self::isUser();
            self::isOutInsider();
            $data = Whole::dataInfo();
            $this->model = new AdminModel( $data );
            $this->data = $data;
        }catch (\Exception $e){
            $this->error( $e->getMessage() );
        }
    }

    /**
     * @return AdminModel
     * @throws \think\Exception
     */
    public function model(){
        return new AdminModel( Whole::dataInfo() );
    }

    /**
     * @return mixed
     * 用户列表
     */
    public function userList(){
        try{
            $this->model = $this->model();
            $tion = input('get.tion');
            switch ($tion){
                case 1 :$userList = $this->model->userList($tion);break; //用户列表信息
                case 2: $userList = $this->model->userList($tion);break;//会员列表信息
                default: $userList = null;
            }
            if($userList != null){
                $page = $userList['data']->render();
            }
            return view('userList',[
	            'tion'  =>  $tion,
	            'userList'  =>  $userList,
	            'page'  =>  $page
            ]);
        }catch (\Exception $e){
            $this->error( $e->getMessage() );
        }

    }
    /**
     * @return \think\response\Json
     * 添加用户
     */
    public function addUser(){
        try{
            $this->model = $this->model();
            if(Request()->isPost()){
                $data = input('post.');
                $this->model->addUser($data);
            }
	        return $this->fetch('addUser');
        }catch (\Exception $e){
            return json_decode($e->getMessage(),true);
        }
    }
    /**
     * 操作用户权限
     */
    public function is_use(){
        try{
            $this->model = $this->model();
            if(Request()->isGet()){
                $uid = input('get.uid',0,'intval');
                $this->model->is_use($uid);
            }
        }catch (\Exception $e){
            return json_decode($e->getMessage(),true);
        }
    }
    public function upPass(){
    	try{

    		return $this->fetch('upPass');
	    }catch (\Exception $e){
			return json_decode( $e->getMessage() ,true);
	    }
    }
}