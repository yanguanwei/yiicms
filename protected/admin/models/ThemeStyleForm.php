<?php
class ThemeStyleForm extends CFormModel
{
	public $id;
	public $css;
	
	public function rules()
	{
		return array(
			array('id', 'required')
		);
	}
	
	public function attributeLabels()
	{
		return array(
			'css' => 'CSS代码'
		);
	}
	
	public function post(array $data)
	{
		$this->setAttributes($data, false);
		
		if ( $this->validate() ) {
			Theme::updateThemeStyle($this->id, $this->css);
			Theme::updateThemeStyleFile(Theme::getThemeName($this->id), $this->css);
			return true;
		}
		
		return false;
	}
}
?>