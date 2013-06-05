<?php
class ThemeForm extends CFormModel
{
	public $id;
	public $title;
	public $name;
	public $entry;
	public $configs = array();
	public $css;
	public $js;
	
	public function rules()
	{
		return array(
			array('id', 'required', 'on' => 'update'),
			array('name, title', 'required')
		);
	}
	
	public function attributeLabels()
	{
		return ConfigType::getConfigLabels() + array(
				'title' => '主题名',
				'name' => '目录名称',
				'entry' => '入口文件名',
				'css' => 'CSS代码',
				'js' => 'Javascript代码'
		);
	}
	
	public function post(array $data, $insert = true)
	{
		$this->setAttributes($data, false);
		if ( $this->validate() ) {
			if ( $insert ) {
				$theme = new Theme();
				$this->id = null;
			} else {
				$theme = Theme::model()->findByPk($this->id);
				if ( !$theme )
					return false; 
				$this->name = $theme->name;	//目录名称不能更新
			}

			$theme->setAttributes($this->getAttributes(), false);
			
			$oldEntry = $theme->entry;
			
			if ( $theme->save() ) {
				$this->id = $theme->id;
				
				//创建主题目录
				if ( $insert ) {
					$this->buildThemePaths($theme->name);
				}
				Theme::updateThemeEntry($theme->id, $oldEntry);
				
				$this->updateAssets($theme->name, $theme->css, $theme->js);
				
				return true;
			} else {
				$this->addErrors($theme->getErrors());
			}
		}
		
		return false;
	}
	
	protected function buildThemePaths($name)
	{
		$basepath = Theme::getThemeBasePath($name);
		$paths = array(
			$basepath,
			$basepath . '/assets'
		);
			
		foreach ($paths as $path) {
			if ( !is_dir($path) )
				mkdir($path, 0777);
		}
	}
	
	protected function updateAssets($name, $css, $js)
	{
		Theme::updateThemeStyleFile($name, $css);
		Theme::updateThemeScriptFile($name, $js);
	}
}
?>