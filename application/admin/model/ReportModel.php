<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2019/3/29
 * Time: 15:09
 */
namespace app\admin\model;
use think\Db;
use think\Model;

class ReportModel extends Model{
    /**
     * @var $data
     * 用户资料
     */
    protected $data;

    /**
     * ReportModel constructor.
     * @param $data
     * @throws \Exception
     */
    public function __construct($data)
    {
        parent::__construct();
        if ( empty( $data ) ) throw new \Exception('用户未登录!');
        $this->data = $data;
    }

    /**
     * @param $tion
     * @return mixed
     * 举报列表
     */
    public function report( $tion ){
        $map = array();
        $map['type'] = $tion;
        $report['data'] = Db::table('forum_report')->where($map)
            ->paginate(10,false,['query' => request()->param()]);
        $report['items']  = $report['data']->items();
        foreach ($report['items'] as $k => $v){
            $report['items'][$k]['title'] = Db::table('forum_note')
                ->where(array('id' => $v['nid']))
                ->value('title');
        }
        $report['count'] = count($report['data']);
        return $report;
    }
    /**
     * @param $msg
     * @throws \Exception
     * 审核举报信息
     */
    public function is_report( $msg ){
        if( empty( $msg['nid'] )) {
            throw new \Exception('{"code":"0","msg":"参数错误!"}');
        }
        $note = Db::table('forum_note')->where(array('id' => $msg['nid']))->find();
        if($note == null){
            throw new \Exception('{"code":"0","msg":"帖子不存在!"}');
        }
        Db::table('forum_report')
            ->where(array('id' => $msg['id']))
            ->update(array('type' => $msg['type']));
        if($msg['type'] == 2){
            throw new \Exception('{"code":"0","msg":"已拒绝!"}');
        }elseif($msg['type'] == 3){
            throw new \Exception('{"code":"0","msg":"已通过!"}');
        }else{
            throw new \Exception('{"code":"0","msg":"操作失败!"}');

        }
    }
}