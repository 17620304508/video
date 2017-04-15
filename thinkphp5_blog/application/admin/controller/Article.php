<?php

namespace app\admin\controller;

use app\common\model\Category;
use think\Controller;

class Article extends Controller
{

	protected $db;

	public function _initialize ()
	{
		parent::_initialize(); // TODO: Change the autogenerated stub
		$this->db = new \app\common\model\Article();
	}

	//首页
	public function index ()
	{
		$field = $this->db->getAll(2 );
		$this->assign( 'field' , $field );

		return $this->fetch();
	}

	//添加
	public function store ()
	{
		if ( request()->isPost() ) {
			$res = $this->db->store( input( 'post.' ) );
			if ( $res[ 'valid' ] ) {
				//说明执行成功
				$this->success( $res[ 'msg' ] , 'index' );
				exit;
			}
			else {
				//执行失败
				$this->error( $res[ 'msg' ] );
				exit;
			}
		}
		//1.获取分类数据
		$cateData = ( new Category() )->getAll();
		$this->assign( 'cateData' , $cateData );
		//2.获取标签数据
		$tagData = db( 'tag' )->select();
		$this->assign( 'tagData' , $tagData );

		return $this->fetch();
	}

	/**
	 * 编辑
	 */
	public function edit ()
	{
		if ( request()->isPost() ) {
			$res = $this->db->edit( input( 'post.' ) );
			if ( $res[ 'valid' ] ) {
				$this->success( $res[ 'msg' ] , 'index' );
				exit;
			}
			else {
				$this->error( $res[ 'msg' ] );
				exit;
			}
		}
		$arc_id = input( 'param.arc_id' );
		//1.获取分类数据
		$cateData = ( new Category() )->getAll();
		$this->assign( 'cateData' , $cateData );
		//2.获取标签数据
		$tagData = db( 'tag' )->select();
		$this->assign( 'tagData' , $tagData );
		//3.获取旧数据
		$oldData = db( 'article' )->find( $arc_id );
		$this->assign( 'oldData' , $oldData );
		//4.获取当前文章所有标签id
		$tag_ids = db( 'arc_tag' )->where( 'arc_id' , $arc_id )->column( 'tag_id' );
		$this->assign( 'tag_ids' , $tag_ids );

		return $this->fetch();
	}

	/**
	 * 修改排序
	 */
	public function changeSort ()
	{
		if ( request()->isAjax() ) {
			$res = $this->db->changeSort( input( 'post.' ) );
			if ( $res[ 'valid' ] ) {
				$this->success( $res[ 'msg' ] , 'index' );
				exit;
			}
			else {
				$this->error( $res[ 'msg' ] );
				exit;
			}
		}
	}

	/**
	 * 删除到回收站
	 */
	public function delToRecycle ()
	{
		$arc_id = input( 'param.arc_id' );
		//将该数据删除到回收站
		if ( $this->db->save( [ 'is_recycle' => 1 ] , [ 'arc_id' => $arc_id ] ) ) {
			$this->success( '操作成功' , 'index' );
			exit;
		}
		else {
			$this->error( '操作失败' );
			exit;
		}
	}
	/**
	 * 恢复数据
	 */
	public function outToRecycle()
	{
		$arc_id = input( 'param.arc_id' );
		//将该数据删除到回收站
		if ( $this->db->save( [ 'is_recycle' => 2 ] , [ 'arc_id' => $arc_id ] ) ) {
			$this->success( '操作成功' , 'index' );
			exit;
		}
		else {
			$this->error( '操作失败' );
			exit;
		}
	}
	/**
	 * 回收站管理
	 */
	public function recycle()
	{
		$field = $this->db->getAll( 1);
		$this->assign( 'field' , $field );
		return $this->fetch();
	}
	/**
	 * 回收站真正的删除
	 */
	public function del()
	{
		$arc_id = input('get.arc_id');
		$res = $this->db->del($arc_id);
		if($res['valid'])
		{
			$this->success($res['msg'],'index');exit;
		}else{
			$this->error($res['msg']);exit;
		}
	}

}