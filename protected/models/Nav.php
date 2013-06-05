<?php
/**
 * 新闻表
 * 
 * @author yanguanwei@qq.com
 *
 */
class Nav extends CActiveRecord
{
	public $id;
	public $identifier;
	public $theme_id;
	public $type_id = 0;
	public $title;
	public $parent_id = 0;
	public $sort_id = 0;
	public $url;
	public $enabled = 1;
	
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
		return '{{nav}}';
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
			array('identifier, theme_id, type_id, title, url', 'required')
		);
	}
	
	public function beforeSave()
	{
		$this->identifier = trim($this->identifier);
		
		if ( $this->getScenario() === 'insert' ) {
			if ( $this->exists("type_id=:type_id AND identifier=:identifier", array(
					':type_id' => $this->type_id,
					':identifier' => $this->identifier	
				)) ) {
				$this->addError('identifier', '已经存在的标识符！');
				return false;
			}
		} else if ( $this->getScenario() === 'update' ) {
			if ( $this->exists("id<>:id AND type_id=:type_id AND identifier=:identifier", array(
					':id' => $this->id,
					':type_id' => $this->type_id,
					':identifier' => $this->identifier
				)) ) {
				$this->addError('identifier', '已经存在的标识符！');
				return false;
			}
		}
		
		return parent::beforeSave();
	}
	
	public static function generateNavId($identifier)
	{
		return strtolower(substr(md5($identifier), 8, 16));
	}

	public function findByNavId($navId)
	{
		return $this->findByAttributes(array('nav_id' => $navId));	
	}
	
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'identifier' => '标识符',
			'title' => '导航名',
			'url' => 'URL',
			'sort_id' => '排序',
			'type_id' => '导航位置',
			'parent_id' => '父级导航',
			'theme_id' => '发布在',
			'enabled' => '是否启用'
		);
	}
	
	public static function getAllNavsForTreeSelect($theme_id, $type_id)
	{
		$conn = Yii::app()->db;
		$theme_id = intval($theme_id);
		$type_id = intval($type_id);
		$command = $conn->createCommand("SELECT id, title, parent_id FROM {{nav}} WHERE `theme_id`='{$theme_id}' AND `type_id`='{$type_id}' AND `enabled`='1'  ORDER BY sort_id DESC, id ASC");
		return $command->queryAll();
	}
	
	public static function getNavigations($theme_id, $type_id)
	{
		$theme_id = intval($theme_id);
		$sql = "SELECT * FROM {{nav}} WHERE `theme_id`='{$theme_id}' AND type_id='{$type_id}' AND `enabled`='1'  ORDER BY sort_id DESC, id ASC";
		return Yii::app()->db->createCommand($sql)->queryAll();
	}
	
	public static function getNavTypeSelectOptions()
	{
		return array(
			0 => '主导航',
			1 => '底部导航'	
		);
	}
	
	/**
	 * 根据主题ID删除导航
	 * 
	 * @param int $themeId
	 * @return int
	 */
	public static function deleteNavByThemeId($themeId)
	{
		$sql = "DELETE FROM {{nav}} WHERE theme_id='{$themeId}'";
		return Yii::app()->db->createCommand($sql)->execute();
	}
}
?>