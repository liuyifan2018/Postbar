<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2019/3/21
 * Time: 16:33
 */
namespace app\admin\model;
use think\Model;
use think\Db;
class ClassifyModel extends Model {
    /**
     * @var string
     * 数据库表名
     */
    protected $table = 'classify';
    /**
     * @var $data
     * 用户信息
     */
    protected $data;

    /**
     * ClassifyModel constructor.
     * @param $data
     * @throws \Exception
     */
    public function __construct($data)
    {
        parent::__construct();
        if( empty($data) ) throw new \Exception('用户未登录!');
        $this->data = $data;
    }
    /**
     * @return mixed
     * 分类列表
     */
    public function Classify(){
        $Classify['data'] = Db::table('forum_classify')->select();
        $Classify['count'] = count($Classify['data']);
        return $Classify;
    }
    /**
     * @param $data
     * @throws \Exception
     * 添加列表
     */
    public function addClassify($data){
        if( empty($data) ){
            throw new \Exception('{"code":0 , "msg":"信息提交失败!"}');
        }
        $data['date']   =   time();
        $data['username'] = $this->data['username'];
        $data['is_show']    =   1;
        if($data['tion'] == ""){
            throw new \Exception('{"code":0 , "msg":"分类名不能为空!"}');
        }else{
            Db::table('forum_classify')->insert($data);
            throw new \Exception('{"code":1 , "msg":"添加成功!"}');
        }
    }
    /**
     * @param $data
     * @throws \Exception
     *  编辑分类
     */
    public function editClassify($data){
        if( empty($data) ){
            throw new \Exception('{"code":0 , "msg":"信息提交失败!"}');
        }
        if($this->data['ad'] != 1){
            throw new \Exception('{"code":0 , "msg":"你当前没有权限操作!"}');
        }elseif($data['tion'] == ""){
            throw new \Exception('{"code":0 , "msg":"分类名不能为空!"}');
        }else{
            Db::table('forum_classify')->where(array('id' => $data['id']))->update($data);
            throw new \Exception('{"code":1 , "msg":"修改成功!"}');
        }
    }
    /**
     * @param $id
     * @return mixed
     * @throws \Exception
     * 分类详情
     */
    public function infoClassify($id){
        if( empty($id) || $id < 0){
            throw new \Exception('{"code":0 ,"msg": "参数错误!"}');
        }
        $classify = Db::table('forum_classify')->where(['id' => $id])->find();
        return $classify;
    }
    /**
     * @param $data
     * @throws \Exception
     * 修改分类状态
     */
    public function is_show($data){
        if( empty($data) ){
            throw new \Exception('{"code":0 ,"msg": "参数错误!"}');
        }
        if($this->data['ad'] != 1){
            throw new \Exception('{"code":0 ,"msg": "你当前没有权限操作!"}');
        }else{
            Db::table('forum_classify')->where(['id' => $data['id']])->update(['is_show' => $data['is_show']]);
            throw new \Exception('{"code":1 ,"msg": "修改成功!"}');
        }
    }
    /**
     * @param $data
     * @throws \Exception
     * 删除分类
     */
    public function is_del($data){
        if( empty($data) ){
            throw new \Exception('{"code":0 ,"msg": "参数错误!"}');
        }
        if($this->data['ad'] != 1){
            throw new \Exception('{"code":0 ,"msg": "你当前没有权限操作!"}');
        }else{
            Db::table('forum_classify')->where(['id' => $data['id']])->delete();
            throw new \Exception('{"code":1 ,"msg": "删除成功!"}');
        }
    }
}