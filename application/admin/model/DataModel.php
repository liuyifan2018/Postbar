<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2019/3/21
 * Time: 18:32
 */
namespace app\admin\model;
use app\admin\Traits\User;
use think\Model;
use think\Db;
class DataModel extends Model{
    /**
     * @var $table
     * 数据库表名
     */
    protected $table = 'data';
    /**
     * @var $data
     * 用户信息
     */
    protected $data;

    /**
     * DataModel constructor.
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
     * @param array $msg
     * @return false|int|void
     * @throws \Exception
     * 修改资料
     */
    public function saveData( $msg ){
        if( empty($msg) ){
            throw new \Exception('{"code":"0" , "msg":"信息修改失败!"}');
        }
        Db::table('user')->where(User::username())->update($msg);
        throw new \Exception('{"code":"1" , "msg":"修改成功!"}');
    }
    /**
     * @param $tion
     * @return mixed
     * @throws \Exception
     * 我的帖子详情
     */
    public function note($tion){
        if( empty($tion) ){
            throw  new \Exception('访问错误!');
        }
        $note['data'] = Db::table('note')
            ->where(User::username())
            ->where('state','<>',2)
            ->select();  //我的帖子
        foreach ($note['data'] as &$item){
            $item['name'] = Db::table('user')
                ->where(['username' => $item['username']])
                ->value('name');    //我的昵称
        }
        if($tion == 1){
            $note['count'] = Db::table('note')
                ->where(User::username())
                ->where('state','<>',2)
                ->count();  //我的帖子总数
        }else{
            $note['count'] = Db::table('coll')
                ->where(User::username())
                ->count();//我收藏的帖子总数
        }
        $note['coll'] = Db::table('coll')
            ->where(User::username())
            ->select(); //我收藏的帖子
        foreach ($note['coll'] as &$v){
            $v['info']  =   Db::table('note')->where(['id' => $v['nid']])->column('*','id'); //帖子信息
            $v['name']   =   Db::table('user')->where(['username' => $v['username']])->value('name'); //我的昵称
            foreach ($v['info'] as &$val){
                $val['tion'] = Db::table('classify')->where(['id' => $val['tion']])->value('tion');   //分类名
            }
        }
        return $note;
    }

    /**
     * @param $msg
     * @param array $note
     * @return \think\response\Json
     * @throws \Exception
     * 编辑帖子
     */
    public function editNoteInfo($msg ){
        $note = Db::table('note')->where(['id' => $msg['id']])->find();   //帖子信息
        if( empty($msg) ){
            throw new \Exception('{"code":"0","msg":"编辑失败,请重试!"}');
        }
        $arr = ['title','content','tion'];
        for ($i = 0; $i < count($arr);$i++){    //循环检查
            if($msg[$arr[$i]] == ""){
                throw new \Exception('{"code":"0","msg":必填项不能为空!"}');
            }
        }
        if($msg['tion'] == 0){  //分类没选择将不改变分类
            $msg['tion'] = $note['tion'];
        }
        Db::table('note')->where(['id' => $msg['id']])->update($msg);   //修改帖子
        throw new \Exception('{"code":"1","msg":"编辑成功!"}');
    }

    /**
     * @param $id
     * @return mixed
     * @throws \Exception
     * 帖子详情
     */
    public function infoNote($id){
        if( empty( $id ) ){
            throw new \Exception('{"code":"0","msg":"参数错误!"}');
        }
        $note = Db::table('note')->where(['id' => $id])->find();   //帖子信息
        return $note;
    }

    /**
     * @param $nid
     * @throws \Exception
     * 取消收藏
     */
    public function collOut($nid){
        if($nid < 1 || empty($nid)){
            throw new \Exception('{"code":"0","msg":"参数错误!"}');
        }else{
            Db::table('coll')->where(['id' => $nid])->update(['coll' => 2]);   //取消收藏帖子
            throw new \Exception('{"code":"1","msg":"取消收藏!"}');
        }
    }

    /**
     * @param $nid
     * @throws \Exception
     * 删除帖子
     */
    public function delNote($nid){
        if($nid <1 || empty($nid)){
            throw new \Exception('{"code":"0","msg":"参数错误!"}');
        }else{
            Db::table('note')->where(['id' => $nid])->update(['is_show' => 2]);  //删除帖子(软删)
            throw new \Exception('{"code":"1","msg":"删除成功!"}');
        }
    }

    /**
     * @return mixed
     * 回收站(找回删过的帖子和彻底删除帖子!)
     */
    public function bin(){
        $note['data'] = Db::table('note')->where(['is_show' => 2])->select();
        foreach ($note['data'] as &$item){
            $item['name'] = Db::table('user')->where(['username' => $item['username']])->value('name');
        }
        $note['count'] = count($note['data']);
        return $note;
    }

    /**
     * @param $nid
     * @throws \Exception
     * 恢复帖子
     */
    public function recovery($nid){
        if($nid < 1 || empty($nid)){
            throw new \Exception('{"code":"0","msg":"参数错误!"}');
        }else{
            Db::table('note')->where(['id' => $nid])->update(['is_show' => 1]); //恢复帖子
            throw new \Exception('{"code":"1","msg":"恢复成功!"}');
        }
    }

    /**
     * @param $nid
     * @throws \Exception
     * 真删帖子
     */
    public function delSure($nid){
        if($nid < 1 || empty($nid)){
            throw new \Exception('{"code":"0","msg":"参数错误!"}');
        }else{
            Db::table('note')->where(['id' => $nid])->delete(); //删除帖子(硬删)
            throw new \Exception('{"code":"1","msg":"删除成功!"}');
        }
    }
}