<?php
class ThemeConfigForm extends CFormModel
{
	public $id;
	public $configs;
	
	public function rules()
	{
		return array(
			array('id', 'required')		
		);
	}
	
	public function attributeLabels()
	{
		return ConfigType::getConfigLabels();
	}
}
?>