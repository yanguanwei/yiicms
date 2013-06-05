<?php
class FriendLink extends CActiveRecord
{
	public $id;
	public $cid;
	public $title;
	public $url;
	public $logo;
	public $visible;
	public $sort_id;
	public $post_time;


	/**
	 * Returns the static model of the specified AR class.
	 * @return CActiveRecord the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{link}}';
	}

	public function primaryKey()
	{
		return 'id';
	}
	
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('id', 'required', 'on' => 'update'),
			array('title, cid', 'required')
		);
	}

	public function afterFind()
	{
		if ( $this->post_time !== null ) {
			$this->post_time = date('Y-m-d H:i', $this->post_time);
		}
	}
	
	public function beforeSave()
	{
		if ( in_array($this->getScenario(), array('insert', 'update')) )
			$this->post_time = time();
		
		return parent::beforeSave();
	}
	
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'title' => '网站名称',
			'url' => '网址',
			'visible' => '是否可见',
			'logo' => '网站LOGO',
			'cid' => '所属分类',
			'sort_id' => '排序',
			'post_time' => '更新时间'
		);
	}
	
	public static function getSelectData()
	{
		$conn = Yii::app()->db;
		$command = $conn->createCommand("SELECT id, title, parent_id FROM {{menu}} ORDER BY sort_id DESC, id ASC");
		$data = $command->queryAll();
		
		return $data;
	}
	
	public static function getFrieldLinksByTopChannelId($topid)
	{
		$channels = Channel::getEnabledSubChannelTitles($topid);
		
		$ids = array_keys($channels);
		$ids = "'" . implode("', '", $ids) . "'";
		
		$sql = "SELECT id, title, url, cid, logo FROM {{link}} WHERE cid IN ({$ids}) AND visible='1' ORDER BY sort_id DESC, id ASC";

		$links = array();
		foreach ($channels as $cid => $channelTitle)
			$links[$cid] = array(
				'title' => $channelTitle,
				'links' => array()
			);
		
		foreach (Yii::app()->db->createCommand($sql)->queryAll() as $row)
			$links[$row['cid']]['links'][] = $row;
		
		return $links;
	}
}
?>