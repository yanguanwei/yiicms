<?php
class NewsForm extends ArchiveForm
{
	public $source;
	public $content;
	
	public function rules()
	{
		$rules = parent::rules();
		
		return $rules;
	}
	
	public function attributeLabels()
	{
		return parent::attributeLabels() + array(
			'source' => '来源',
			'content' => '内容'
		);
	}
	
	protected function save(array $data, $insert = true)
	{
		parent::save($data, $insert);
		
		if ( $insert ) {
			$news = new News();
		} else {
			$news = News::model()->findByPk($this->id);
		}
			
		$news->setAttributes($this->getAttributes(), false);
			
		if ( !$news->save() ) {
			$this->addErrors($news->getErrors());
			throw new CException();
		}
	}
}
?>