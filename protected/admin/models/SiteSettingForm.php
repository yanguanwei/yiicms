<?php
class SiteSettingForm extends SettingForm
{
	public $name;
	public $title;
	public $keywords;
	public $description;
	public $address;
	public $zipcode;
	public $phone;
	public $url;
	public $fax;
	public $email;
	
	public function rules()
	{
		return array(
			array('name, title', 'required'),
			array('url', 'url'),
			array('email', 'email'),
			array('keywords, description, address, zipcode, phone, fax', 'safe')
		);
	}
	
	public function attributeLabels()
	{
		return array(
			'name' => '网站名称',
			'title' => '网站标题',
			'keywords' => '关键字',
			'description' => '描述',
			'url' => '网址',
			'address' => '地址',
			'zipcode' => '邮政编码',
			'phone' => '电话',
			'fax' => '传真',
			'email' => 'E-Mail'
		);
	}
}
?>