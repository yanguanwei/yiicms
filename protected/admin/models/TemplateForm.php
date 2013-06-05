<?php
class TemplateForm extends CFormModel
{
	public $id;
	public $theme_id;
	public $path;
	public $content;
	
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
				array('path, theme_id', 'required')
		);
	}
	
	public function post(array $data, $insert = true)
	{
		$this->setAttributes($data, false);
		if ( $this->validate() ) {
			if ( $insert ) {
				$model = new ThemeTemplate();
				$this->id = null;
				$model->setAttributes($this->getAttributes(), false);
				if ( !$model->save() ) {
					$this->addErrors($model->getErrors());
					return false;
				} else {
					$this->id = $model->id;
				}
			} else {
				$model = ThemeTemplate::model()->findByPk($this->id);
				if ( !$model ) {
					$this->addError('id', '不存在的模板ID！');
					return false;
				}
				//路径不能更新
				$this->path = $model->path;
			}
			
			if ( false !== ThemeTemplate::updateThemeTemplateContent($this->theme_id, $this->path, $this->content) ) {
				return true;
			} else {
				$this->addError('content', '代码保存失败！');
				return false;	
			}
		}
		
		return false;
	}
}
?>