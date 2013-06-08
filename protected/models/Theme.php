<?php
class Theme extends CActiveRecord
{
	public $id;
	public $title;
	public $name;
	public $entry;
	public $configs = array();
	public $css;
	public $js;
	
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
		return '{{theme}}';
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
			array('title, name', 'required')
		);
	}
	
	public function beforeSave()
	{
		if ( $this->getScenario() === 'insert' ) {
			if ( $this->exists('name=:name', array(':name' => $this->name)) ) {
				$this->addError('name', "目录名称已经存在！");
				return false;
			}
			
			if ( $this->entry && $this->exists('entry=:entry', array(':entry' => $this->entry)) ) {
				$this->addError('name', "入口文件名已经存在！");
				return false;
			}
			
		} else if ( $this->getScenario() === 'update' ) {
			
			if ( $this->entry && $this->exists('id<>:id AND entry=:entry', array(':id' => $this->id, ':entry' => $this->entry)) ) {
				$this->addError('name', "入口文件名已经存在！");
				return false;
			}
		}
		
		if ( $this->configs !== null )
			$this->configs = ConfigType::filterConfigValueFromFormToDb($this->configs);
		
		
		return parent::beforeSave();
	}
	
	public static function fetchThemeSelectOptions()
	{
        static $options;

        if (null===$options) {
            $sql = "SELECT id, title FROM {{theme}} ORDER BY id ASC";
            $options = array();
            foreach (Yii::app()->db->createCommand($sql)->queryAll() as $row) {
                $options[$row['id']] = $row['title'];
            }
        }
		return $options;
	}
	
	/**
	 * 根据主题ID返回该主题的目录名
	 * 
	 * @param int $id
	 * @return string|null
	 */
	public static function getThemeName($id)
	{
		$sql = "SELECT name FROM {{theme}} WHERE id='{$id}'";
		$row = Yii::app()->db->createCommand($sql)->queryRow();
		if ( $row )
			return $row['name'];
	}
	
	/**
	 * 根据主题ID返回该主题名
	 *
	 * @param int $id
	 * @return string|null
	 */
	public static function findThemeTitle($id)
	{
		$sql = "SELECT title FROM {{theme}} WHERE id='{$id}'";
		$row = Yii::app()->db->createCommand($sql)->queryRow();
		if ( $row )
			return $row['title'];
	}
	
	/**
	 * 根据主题名称返回主题的绝对路径
	 * 
	 * @param string $name
	 * @return string
	 */
	public static function getThemeBasePath($name)
	{	
		return Yii::app()->getThemeManager()->getBasePath().DIRECTORY_SEPARATOR.$name;
	}
	
	/**
	 * 根据主题ID返回该主题路径，如果$id为0，则返回frontend.views路径
	 * 
	 * @param int $id 
	 * @return string
	 */
	public static function getThemeBasePathById($id)
	{
		if ( $id == 0 )
			return Yii::getPathOfAlias('frontend');
	
		return self::getThemeBasePath(self::getThemeName($id));
	}
	
	/**
	 * 返回主题的入口文件
	 * 
	 * @param string $entry
	 * @return string
	 */
	public static function getEntryFile($entry)
	{
		return Yii::getPathOfAlias('wwwroot') . "/{$entry}.php";
	}
	
	public static function getThemeStyle($id)
	{
		$id = intval($id);
		$sql = "SELECT css FROM {{theme}} WHERE id='{$id}'";
		$row = Yii::app()->db->createCommand($sql)->queryRow();
		if ( $row )
			return $row['css'];
	}
	
	public static function getThemeConfigs($id)
	{
		$id = intval($id);
		$sql = "SELECT configs FROM {{theme}} WHERE id='{$id}'";
		$row = Yii::app()->db->createCommand($sql)->queryRow();
		if ( $row )
			return $row['configs'];
	}
	
	public static function updateThemeStyle($id, $css)
	{
		$sql = 'UPDATE {{theme}} SET css=:css WHERE id=:id';
		return Yii::app()->db->createCommand($sql)->execute(array(
				':css' => $css,
				':id' => $id	
			));
	}
	
	public static function getThemeScript($id)
	{
		$id = intval($id);
		$sql = "SELECT js FROM {{theme}} WHERE id='{$id}'";
		$row = Yii::app()->db->createCommand($sql)->queryRow();
		if ( $row )
			return $row['js'];
	}
	
	public static function updateThemeScript($id, $js)
	{
		$sql = 'UPDATE {{theme}} SET js=:js WHERE id=:id';
		return Yii::app()->db->createCommand($sql)->execute(array(
				':js' => $js,
				':id' => $id
		));
	}
	
	public static function updateThemeConfigs($id, $configs)
	{
		$sql = 'UPDATE {{theme}} SET configs=:configs WHERE id=:id';
		$result = Yii::app()->db->createCommand($sql)->execute(array(
				':configs' => $configs,
				':id' => $id
		));
		
		self::updateThemeEntry($id);
		
		return $result;
	}
	
	public static function updateThemeEntry($id, $oldEntry = null)
	{
		$theme = Theme::model()->findByPk($id);
		$entry = $theme->entry;
		
		//删除旧的入口文件
		if ( $oldEntry !== null && $entry != $oldEntry ) {
			$file = Theme::getEntryFile($oldEntry);
			if ( is_file($file) )
				unlink($file);
		}
		
		//没有指定入口文件，则不创建之
		if ( !$entry ) return ;
		
		$appKeys = ConfigType::getAppConfigKeys();

		$configs = ConfigType::filterConfigValueFromDb($theme->configs);
		$themeName = $theme->name;
		
		$appConfig = array();
		foreach ($configs as $key => $value) {
			if ( in_array($key, $appKeys) ) {
				$appConfig[$key] = $value;
			}
		}
		
		$configs['theme_id'] = intval($id);
		$configs = var_export($configs, true);
		$appConfig = var_export($appConfig, true);
		$code = <<<CODE
<?php
\$main = require dirname(__FILE__).'/protected/config/main.php';
\$config = require Yii::getPathOfAlias('frontend').'/config/main.php';
\$config['basePath'] = Yii::getPathOfAlias('frontend');
\$config['theme'] = '{$themeName}';
\$config = CMap::mergeArray(\$config, {$appConfig});
\$config['params'] = {$configs};
Yii::createWebApplication(CMap::mergeArray(\$main, \$config))->run();	
CODE;
		file_put_contents(Theme::getEntryFile($entry), $code);
	}
			
	public static function updateThemeScriptFile($name, $script)
	{
		file_put_contents(Theme::getThemeBasePath($name) . "/static/js/common.js", $script);
	}
	
	public static function updateThemeStyleFile($name, $style)
	{
		file_put_contents(Theme::getThemeBasePath($name) . "/static/css/common.css", $style);
	}
}
?>