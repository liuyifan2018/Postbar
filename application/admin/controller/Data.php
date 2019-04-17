<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2019/2/26
 * Time: 15:19
 */
namespace app\admin\controller;
use app\admin\model\DataModel;
use app\admin\Traits\Whole;
use think\Controller;
class Data extends Controller{
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
     * @throws \Exception
     * 初始化
     */
    public function initialize()
    {
        parent::initialize(); // TODO: Change the autogenerated stub
        try{
            self::isUser();
            self::isOutInsider();
            $data = Whole::dataInfo();
            $this->model = new DataModel( $data );
            $this->data = $data;
        }catch (\Exception $e){
            $this->error( $e->getMessage() ,'','',10);
        }
    }

    /**
     * @return DataModel
     * @throws \Exception
     */
    public function model(){
        return new DataModel( Whole::dataInfo() );
    }

    /**
     * @return mixed
     * 用户资料
     */
    public function user(){
        try{
            $this->model = $this->model();
            $data = Whole::dataInfo();
            return view('user',[
            	'data'  =>  $data
            ]);
        }catch (\Exception $e){
            $this->error( $e->getMessage() );
        }
    }
    /**
     * @return \think\response\Json
     * 修改资料
     */
    public function save(){
        try{
            $this->model = $this->model();
            if(Request()->isPost()){
                $msg = input('post.');
                $this->model->saveData( $msg ); //thinkPhp不能定义关键字方法名 save()
            }
        }catch (\Exception $e){
            return json_decode( $e->getMessage() ,true);
        }
    }
    /**
     * @return mixed
     * 设置密保
     */
    public function problem(){
        $data = $this->data;
        return $this->fetch('problem',array(
            'data'  =>  $data
        ));
    }
    /**
     * @return mixed
     * 我的帖子
     */
    public function note(){
        try{
            $this->model = $this->model();
            $tion = input('get.tion');
            $note = $this->model->note($tion);
            return view('note',[
	            'tion'  =>  $tion,
	            'note'  =>  $note,
            ]);
        }catch (\Exception $e){
            $this->error( $e->getMessage() );
        }

    }
    /**
     * @return mixed|\think\response\Json
     * 编辑帖子页面
     */
    public function editNote(){
        try{
            $this->model = $this->model();
            $id = input('get.id');
            $note = $this->model->infoNote($id);
            $arr = Whole::classify();   //分类
	        return view('editNote',[
		        'note'  =>  $note,
		        'arr'   =>  $arr
	        ]);
        }catch (\Exception $e){
            $this->error( $e->getMessage() );
        }
    }

    /**
     * @return mixed
     * 编辑帖子操作
     */
    public function editNoteInfo(){
        try{
            $this->model = $this->model();
            if(Request()->isPost()){
                $msg = input('post.');
                $this->model->editNoteInfo( $msg );
            }
        }catch (\Exception $e){
            return json_decode( $e->getMessage() ,true);
        }
    }
    /**
     * @return \think\response\Json
     * 取消收藏
     */
    public function collOut(){
        try{
            $this->model = $this->model();
            if(Request()->isGet()){
                $nid = input('get.nid');
                $this->model->collOut($nid);
            }
        }catch (\Exception $e){
            return json_decode( $e->getMessage() ,true);
        }
    }
    /**
     * @return \think\response\Json
     * 删除帖子
     */
    public function delNote(){
        try{
            $this->model = $this->model();
            if(Request()->isGet()){
                $nid = input('get.nid');
                $this->model->delNote($nid);
            }
        }catch (\Exception $e){
            return json_decode( $e->getMessage() ,true);
        }
    }
    /**
     * @return mixed
     * 回收站(找回删过的帖子和彻底删除帖子!)
     */
    public function bin(){
        try{
            $this->model = $this->model();
	        $data = $this->data;
	        $note = $this->model->bin();
            return View('bin',[
	            'data'  =>  $data,
	            'note'  =>  $note,
            ]);
        }catch (\Exception $e){
            return json_decode( $e->getMessage() , true);
        }
    }
    /**
     * @return \think\response\Json
     * 恢复帖子
     */
    public function recovery(){
        try{
            $this->model = $this->model();
            if(Request()->isGet()){
                $nid = input('get.nid');
                $this->model->recovery($nid);
            }
        }catch (\Exception $e){
            return json_decode( $e->getMessage() , true);
        }
    }
    /**
     * 绝对删除(无法撤回)
     */
    public function delSure(){
        try{
            $this->model = $this->model();
            if(Request()->isGet()){
                $nid = input('get.nid');
                $this->model->delSure($nid);
            }
        }catch (\Exception $e){
            return json_decode( $e->getMessage() , true);
        }
    }
}