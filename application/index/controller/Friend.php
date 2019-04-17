<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2019/2/21
 * Time: 17:45
 */
namespace app\index\controller;
use app\index\model\FriendModel;
use app\index\Traits\Date;
use app\index\Traits\Whole;
use think\Controller;
use think\Db;

class Friend extends Controller
{
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
     * 初始化
     */
    public function initialize()
    {
        parent::initialize(); // TODO: Change the autogenerated stub
        try{
            self::isUser();
            self::isOutInsider();
            $data = Whole::dataInfo();
            $this->model = new FriendModel( $data );
            $this->data = $data;
        }catch (\Exception $e){
            $this->error( $e->getMessage() );
        }
    }

	/**
	 * @return FriendModel
	 * @throws \Exception
	 */
    public function model(){
        return new FriendModel( Whole::dataInfo() );
    }

	/**
	 * @return mixed
	 * @throws \Exception
	 * 好友列表
	 */
    public function index(){
		try{
			$this->model = $this->model();
			$arr = Whole::Classify();    //获取头部分类
			$data = Whole::dataInfo();  //获取用户信息
			$friends = $this->model->index($data);
			$tion = input('get.tion');
			if(empty($tion)){
				$tion = 1;
			}
			return view('index',[
				'data'  =>  $data,
				'tion'  =>  $tion,
				'friends' => $friends,
				'arr'   =>  $arr,
				'title' =>  '我的好友'
			]);
		}catch (\Exception $e){
			$this->error( $e->getMessage() );
		}
    }
    /**
     * @return \think\response\Json
     * 搜索好友
     */
    public function friend(){
        if(Request()->isPost()){
            $friend = input('post.friend');
            if($friend == ""){
                return json(['code' => 0 , 'msg' => '请填写用户名!']);
            }
            $list = Db::table('user')->where(array('username' => $friend))->find(); //搜索好友的信息
            if($list == null){
                return json(['code' => 0 , 'msg' => '没有此用户!']);
            }else{
                return json(['code' => 1 , 'msg' => $list]);
            }
        }
    }
    /**
     * @return \think\response\Json
     * 添加好友
     */
    public function getFriend(){
        try{
            $this->model = $this->model();
            if(Request()->isGet()){
                $friend = input('get.friend');
                $msg = [
                    'username' => $this->data['username'],
                    'friend'    =>  $friend,
                    'is_fd' =>  2,
                    'date'  => time() ,
                    'type' => 0,
                    'my' => 1
                ];
                $fsg = [
                    'username' => $friend,
                    'friend'    =>  $this->data['username'],
                    'is_fd' =>  2,
                    'date'  => time() ,
                    'type' => 0,
                    'my' => 0
                ];
                $this->model->getFriend($friend,$msg,$fsg);
            }
        }catch (\Exception $e){
            return json_decode($e->getMessage(),true);
        }
    }
    /**
     * @return \think\response\Json
     * 删除好友
     */
    public function delFriend(){
        try{
            $this->model = $this->model();
            if(Request()->isGet()){
                $friend = input('friend');
                $this->model->delFriend( $friend );
            }
        }catch (\Exception $e){
            return json_decode( $e->getMessage() ,true);
        }
    }
    /**
     * @return mixed
     * 好友互发信息
     */
    public function message(){
        try{
            $this->model = $this->model();
            if(Request()->isPost()){
                $param = input('post.');
                $param['date'] = Date::getNowTime();
                $this->model->message( $param );
            }
            $arr = Whole::Classify();
            $fid = input('get.friend');
            $friend = Db::table('user')->where(array('id' => $fid))->find(); //好友信息
            $message = $this->model->messageInfo( $friend );
            return view('message',[
	            'arr'   =>  $arr,
	            'data'  =>  $this->data,
	            'friend'    =>  $friend,
	            'message'   =>  $message,
	            'title' =>  '我的好友'
            ]);
        }catch (\Exception $e){
            return json_decode( $e->getMessage() ,true);
        }

    }
}