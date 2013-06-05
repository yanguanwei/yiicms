<?php
class UniversalDataCell
{
	public $name;
	public $value;
	public $type = 'raw';
	public $data;
	public $typeOptions = array();
	
	public function __construct(array $options)
	{
		foreach ($options as $key => $value) 
			$this->{$key} = $value;
		
		if ( $this->value === null )
			$this->value = $this->resolveValue();
	}
	
	public function getValue()
	{
		$method = 'parse'.ucfirst($this->type);
		if ( method_exists($this, $method) )
			return $this->$method($this->typeOptions);
	}
	
	protected function parseRaw()
	{
		return $this->value;
	}
	
	protected function parseImage($options)
	{
		return CHtml::image('img', isset($options['alt']) ? $options['alt'] : '', $options);
	}
	
	protected function parseTime()
	{
		return date('H:i:s', $this->value);	
	}
	
	protected function parseDate()
	{
		return date('Y-m-d', $this->value);
	}
	
	protected function parseDateTime()
	{
		return date('Y-m-d H:i', $this->value);
	}
	
	protected function parseLink($options)
	{
		if ( isset($options['url']) ) {
			$url = $options['url'];
			unset($options['url']);
		} else {
			$url = $this->value;
		}
		
		return CHtml::link($this->value, $url, $options);
	}
	
	protected function resolveValue()
	{
		return CHtml::value($this->data,$this->name);
	}
}
?>