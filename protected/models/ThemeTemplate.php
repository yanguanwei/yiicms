<?php
class ThemeTemplate extends CActiveRecord
{
	public $id;
	public $theme_id;
	public $path;
	public $post_time;
	
	/**
	 * Returns the static model of the specified AR class.
	 * @return ThemeTemplate the static model class
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
		return '{{theme_template}}';
	}
	
	public function primaryKey()
	{
		return 'id';
	}
	
	public function attributeLabels()
	{
		return array(
				'theme_id' => '所属主题',
				'path' => '路径',
				'content' => '代码'
			);
	}
	
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('id', 'required', 'on' => 'update'),
			array('theme_id, path', 'required')
		);
	}
	
	protected function beforeSave()
	{
		if ( $this->path !== null ) {
			if ( $this->path[0] !== '/' )
				$this->path = '/' . $this->path;
		}
		
		if ( $this->getIsNewRecord() ) {
			if ( $this->exists(
					'theme_id=:theme_id AND path=:path', 
					array(':theme_id'=>$this->theme_id, ':path' => $this->path)
				) ) {
				$this->addError('path', "此路径的模板已经存在！");
				return false;
			}
			
		} else {
			if ( $this->exists(
					'theme_id=:theme_id AND id<>:id AND path=:path', 
					array(':theme_id'=>$this->theme_id,':id'=> $this->id, ':path'=>$this->path)
				) ) {
				$this->addError('path', "此路径的模板已经存在！");
				return false;
			}
		}
		
		$this->post_time = time();
		
		return parent::beforeSave();
	}
	
	public static function getTemplateSelectOptions()
	{
		$sql = "SELECT id, path FROM {{theme_template}} WHERE theme_id='0' ORDER BY path ASC";
		$options = array();
		foreach ( Yii::app()->db->createCommand($sql)->queryAll() as $row ) {
			$options[$row['path']] = $row['path'];
		}
		return $options;
	}
	
	/**
	 * 根据主题ID删除所有模板记录
	 * 
	 * @param int $theme_id
	 * @return number
	 */
	public static function deleteTemplateByThemeId($theme_id)
	{
		$theme_id = intval($theme_id);
		$sql = "DELETE FROM {{theme_template}} WHERE theme_id='{$theme_id}'";
		return Yii::app()->db->createCommand($sql)->execute();
	}
	
	public static function getThemeTemplateByThemeId($themeId, $path)
	{
		return Theme::getThemeBasePathById($themeId) . "/views{$path}.php";
	}
	
	public static function getThemeTemplate($theme, $path)
	{
		return Theme::getThemeBasePath($theme) . "/views{$path}.php";
	}
	
	public static function updateThemeTemplateContent($themeId, $path, $content)
	{
		$path = self::getThemeTemplateByThemeId($themeId, $path);
		if ( !is_dir(dirname($path)) )
			mkdir(dirname($path), 0777, true);
		return file_put_contents($path, $content);
	}
	
	public static function getThemeTemplateContent($themeId, $path)
	{
		$path = self::getThemeTemplateByThemeId($themeId, $path);
		if ( is_file($path) )
			return file_get_contents($path);
	}
}
?>