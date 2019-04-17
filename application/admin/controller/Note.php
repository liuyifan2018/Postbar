<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2019/2/26
 * Time: 18:20
 */
namespace app\admin\controller;
use app\admin\model\NoteModel;
use app\admin\Traits\Whole;
use think\Controller;
class Note extends Controller{
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
            if( empty($data) ) throw new \Exception('用户未登录!');
            $this->model = new NoteModel( $data );
        }catch (\Exception $e){
            $this->error( $e->getMessage() );
        }
    }

    /**
     * @return NoteModel
     * @throws \Exception
     */
    public function model(){
        return new NoteModel( Whole::dataInfo() );
    }

    /**
     * @return mixed
     * 帖子列表
     */
    public function note(){
        try{
            $this->model = $this->model();
            $tion = input('get.tion');
            $note = input('post.note');
            $noteList = $this->model->note($tion,$note);
            // 获取分页显示
            $page = $noteList['data']->render();
        }catch (\Exception $e){
            return json_decode( $e->getMessage() ,true);
        }
        return view('note',[
	        'noteList'  =>  $noteList,
	        'tion'  =>   $tion,
	        'note'  =>  $note,
	        'page'  =>  $page
        ]);
    }
    /**
     * 审核帖子
     */
    public function is_Note(){
        try{
            $this->model = $this->model();
            if(Request()->isGet()){
                $msg = input('get.');   //state=1(通过);  state=3(拒绝);
                $this->model->is_Note($msg);
            }
        }catch (\Exception $e){
            return json_decode( $e->getMessage() ,true);
        }
    }

    /**
     * @return mixed
     * 批量删除
     */
    public function deleteAll(){
        try{
            $this->model = $this->model();
            if(Request()->isGet()){
                $data = input('o_data');
                $this->model->deleteAll($data);
            }
        }catch (\Exception $e){
            return json_decode($e->getMessage() ,true);
        }
    }
}