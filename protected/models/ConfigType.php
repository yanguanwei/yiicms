<?php
class ConfigType extends CActiveRecord
{
	public $id;
	public $type;
	public $is_app = 0;
	public $title;
	public $note;
	public $key;
	public $default;
	public $sort_id = 0;
	
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
		return '{{config_type}}';
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
			array('title, key, type', 'required')
		);
	}
	
	public function attributeLabels()
	{
		return array(
				'type' => '数据类型',
				'title' => '配置名称',
				'key' => '键名',
				'is_app' => '项目配置',
				'note' => '提示'	,
				'default' => '默认值',
				'sort_id' => '排序'
			);
	}
	
	public static function getTypeSelectOpotions($type = null)
	{
		$types = array(
				0 => 'String',
				1 => 'Integer',
				2 => 'Array',
				3 => 'File',
				4 => 'Textarea'
			);
		if ( null !== $type ) {
			return $types[$type];
		}
		
		return $types;
	}
	
	public static function renderForm(AdminForm $form)
	{
		$sql = "SELECT `key`, `note`, `type` FROM {{config_type}} ORDER BY sort_id DESC, id ASC";
		
		$fields = array();
		
		foreach ( Yii::app()->db->createCommand($sql)->queryAll() as $row ) {
			$name = "configs[{$row['key']}]";
			$note = $row['note'] ? $row['note'] : null;
			switch (intval($row['type'])) {
				case 2:
				case 4:
					$fields[] = $form->renderTextareaRow($name, $note);
					break;
				case 3:
					$fields[] = $form->renderCKFinderInputRow($name, $note);
					break;
				default:
					$fields[] = $form->renderTextRow($name, $note, array('class' => 'text-input medium-input'));
			}
		}
		
		return implode("\n", $fields);
	}
	
	/**
	 * 为表单提供每项配置的默认值
	 * 
	 * @return array
	 */
	public static function getConfigDefaultValueForForm()
	{
		$sql = "SELECT `key`, `default`, `type` FROM {{config_type}} ORDER BY sort_id DESC, id ASC";
		
		$defaults = array();
		
		foreach ( Yii::app()->db->createCommand($sql)->queryAll() as $row ) {
			switch (intval($row['type'])) {
				case 0:
					$value = $row['default'];
					break;
				case 1:
					$value = $row['default'] ? intval($row['default']) : 0;
					break;
				case 2:
					$value = $row['default'];
					break;
				default:
					$value = $row['default'];
			}
			$defaults[$row['key']] = $value;
		}
		
		return $defaults;
	}
	
	public static function getConfigLabels()
	{
		$sql = "SELECT `key`,`title` FROM {{config_type}}";
		$labels = array();
		foreach ( Yii::app()->db->createCommand($sql)->queryAll() as $row ) {
			$labels["configs[{$row['key']}]"] = $row['title'];
		}
		return $labels;
	}
	
	public static function getConfigTypes()
	{
		$sql = "SELECT `key`,`type` FROM {{config_type}}";
		$types = array();
		foreach ( Yii::app()->db->createCommand($sql)->queryAll() as $row ) {
			$types[$row['key']] = intval($row['type']);
		}
		return $types;
	}
	
	public static function filterConfigValueFromFormToDb(array $configs)
	{
		$types = self::getConfigTypes();
		foreach ($configs as $key => &$value) {
			switch ($types[$key]) {
				case 0:
					break;
				case 1:
					$value = intval($value);
					break;
				case 2:
					$value = explode("\n", $value);
					break;
			}
		}
		return serialize($configs);
	}
	
	public static function filterConfigValueFromDbToForm($configs)
	{
		$types = self::getConfigTypes();
		$configs = self::filterConfigValueFromDb($configs);
		foreach ($configs as $key => &$value) {
			switch ($types[$key]) {
				case 0:
					break;
				case 1:
					$value = intval($value);
					break;
				case 2:
					$value = implode("\n", $value);
					break;
			}
		}
		return $configs;
	}
	
	public static function filterConfigValueFromDb($configs)
	{
		$configs = unserialize($configs);
		return $configs ? $configs : array();
	}
	
	public static function getAppConfigKeys()
	{
		$sql = "SELECT `key` FROM {{config_type}} WHERE is_app='1' ORDER BY sort_id DESC, id ASC";
		$keys = array();
		foreach (Yii::app()->db->createCommand($sql)->queryAll() as $row) {
			$keys[] = $row['key'];
		}
		return $keys;
	}
}
?>