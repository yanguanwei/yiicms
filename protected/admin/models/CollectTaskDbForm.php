<?php
class CollectTaskDbForm extends CFormModel
{
	const TYPE_ARCHIVE = 0;
	const TYPE_LINK = 1;
	
	public $id;
	public $title;
	public $type_id;
	public $cid;
	public $configs = array();
	public $is_repeat = 0;
	
	private $_type;
	
	public function setType($type)
	{
		$this->_type = $type;	
	}
	
	public function setTypeByChannelModelId($modelId)
	{
		if ( $modelId == 2 ) {
			$this->_type = self::TYPE_LINK;
		} else {
			$this->_type = self::TYPE_ARCHIVE;
		}
	}
	
	public function rules()
	{
		return array(
			array('id', 'required', 'on' => 'update'),
			array('cid, title', 'required')		
		);	
	}
	
	public function attributeLabels()
	{
		$labels = array(
			'title' => '任务名称',
			'cid' => '栏目',
			'type_id' => '采集方式',
			'is_repeat' => '是否重复采集',
			'configs[host]' => '数据库IP',
			'configs[db]' => '数据库名称',
			'configs[user]' => '数据库用户名',
			'configs[pass]' => '数据库密码',
			'configs[table]' => '表名',
			'configs[pk]' => '主键字段名',
			'configs[where]' => '条件语句'
		);
		
		if ( $this->_type == self::TYPE_LINK ) {
			$labels = array_merge($labels, array(
					'configs[title]' => '网站字段名',
					'configs[logo]' => 'LOGO字段名',
					'configs[order]' => '排序字段名',
					'configs[url]' => 'URL字段名',
			));
		} else {
			$labels = array_merge($labels, array(
				'configs[title]' => '标题字段名',
				'configs[content]' => '内容字段名',
				'configs[time]' => '时间字段名',
				'configs[source]' => '来源字段名',
			));
		}
		
		return $labels;
	}
	
	public function post(array $data, $insert = true)
	{
		$this->setAttributes($data, false);
		$this->type_id = CollectTask::TYPE_DB;
		
		if ( $this->validate() ) {
			if ( $insert ) {
				$model = new CollectTask();
				$this->id = null;
			} else {
				$model = CollectTask::model()->findByPk($this->id);
				if ( !$model ) {
					$this->addError('id', '记录不存在！');
					return false;
				}
			}
			
			$model->setAttributes($this->getAttributes(), false);
			
			if ( $model->save() ) {
				$this->id = $model->id;
				return true;
			} else {
				$this->addErrors($model->getErrors());
			}
		}
		
		return false;
		
	}
	
	public function getFormView()
	{
		$views = array(
			self::TYPE_ARCHIVE => 'form',
			self::TYPE_LINK => 'link_form'		
		);
		
		return $views[$this->_type];
	}
}
?>