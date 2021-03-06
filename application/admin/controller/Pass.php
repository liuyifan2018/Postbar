<?php
/**wE
 * Created by PhpStorm.
 * User: Admin
 * Date: 2019/3/2
 * Time: 10:14
 */
namespace app\admin\controller;
use app\admin\model\PassModel;
use app\admin\Traits\Whole;
use think\Controller;
class Pass extends Controller{
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
            $this->model = new PassModel( $data );
            if( empty($data) ) throw new \Exception('用户未登录');
            $this->data = $data;
        }catch (\Exception $e){
            $this->error( $e->getMessage() );
        }
    }

    /**
     * @return PassModel
     * @throws \Exception
     */
    public function model(){
        return new PassModel( Whole::dataInfo() );
    }

    /**
     * @return mixed|\think\response\Json
     * 验证账号
     */
    public function type(){
        try{
            $this->model = $this->model();
            if(Request()->isPost()){
                $user = input('post.user');
                $this->model->type($user);
            }
        }catch (\Exception $e){
            return json_decode( $e->getMessage() ,true);
        }
        return $this->fetch('type');
    }
    /**
     * @return mixed
     * 选择方式
     */
    public function tion(){
        $this->model = $this->model();
        $username = input('get.username');
        if(empty($username)){
            $this->redirect('User/login');
        }
        $problem = $this->model->tion($username);
        return view('tion',[
	        'problem'   =>  $problem,
	        'username'  =>  $username
        ]);
    }
    /**
     * @return \think\response\Json
     * 密保问题
     */
    public function ProblemPass(){
        try{
            $this->model = $this->model();
            if(Request()->isPost()) {
                $msg = input('post.');
                $this->model->ProblemPass($msg);
            }
        }catch (\Exception $e){
            return json_decode($e->getMessage() , true);
        }
    }
    /**
     * @return \think\response\Json
     * 修改密码
     */
    public function upPass(){
        try{
            $this->model = $this->model();
            if(Request()->isPost()) {
                $msg = input('post.');
                $this->model->upPass($msg);
            }
        }catch (\Exception $e){

        }
        return $this->fetch('upPass');
    }
    /**
     * @return \think\response\Json
     * 申诉提交
     */
    public function Appeal()
    {
        try{
            $this->model = $this->model();
            if (Request()->isPost()) {
                $msg = input('post.');
                $this->model->Appeal($msg);
            }
        }catch (\Exception $e){
            return json_decode( $e->getMessage() ,true);
        }
    }
}