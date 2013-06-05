<?php
/**
 * 视图表
 * 
 * @author yanguanwei@qq.com
 * 
 * @property string $id
 * @property string $router 路径名称
 * @property int tid 模板id
 * @property int $enable 是否启用
 */
class View extends CActiveRecord
{
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
		return '{{view}}';
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
			array('router, tid', 'required'),
			array('id', 'required', 'on' => 'update'),
			array('enable', 'safe')
		);
	}
	
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'router' => '路径名称',
			'tid' => '模板ID',
			'enable' => '是否启用'
		);
	}
}
?>