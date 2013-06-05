<?php
class SettingForm extends CFormModel
{
	public function update($key, array $data)
	{
		$this->attributes = $data;
		
		if ($this->validate()) {
			$data = $this->getAttributes();
			$content = var_export($data, true);
			$content = "<?php\nreturn {$content};\n?>";
			file_put_contents(Yii::app()->basePath . "/config/{$key}.php", $content);
			return true;
		}
		
		return false;
	}
}
?>