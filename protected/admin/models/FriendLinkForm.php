<?php
class FriendLinkForm extends CFormModel
{
	public $id;
	public $title;
	public $url = 'http://';
	public $logo;
	public $post_time;
	public $cid = 0;
	public $sort_id = 0;
	public $visible = true;
	
	public function rules()
	{
		return array(
			array('title, url, cid', 'required')
		);
	}
	
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'title' => '网站名称',
			'cid' => '所属栏目',
			'logo' => 'LOGO',
			'sort_id' => '排序',
			'visible' => '是否可见',
			'url' => '网址',
			'post_time' => '更新时间'
		);
	}
}
?>