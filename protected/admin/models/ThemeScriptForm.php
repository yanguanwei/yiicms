<?php
class ThemeScriptForm extends CFormModel
{
	public $id;
	public $js;
	
	public function rules()
	{
		return array(
			array('id', 'required')
		);
	}
	
	public function attributeLabels()
	{
		return array(
			'js' => 'Javascript代码'
		);
	}
	
	public function post(array $data)
	{
		$this->setAttributes($data, false);
		
		if ( $this->validate() ) {
			Theme::updateThemeScript($this->id, $this->js);
			
			$basepath = Theme::getThemeBasePathById($this->id) . '/assets';
			$cssfile = $basepath . '/common.js';
			file_put_contents($cssfile, $this->js);
			
			return true;
		}
		
		return false;
	}
}
?>