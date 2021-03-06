<?php
/**
 * 新闻表
 * 
 * @author yanguanwei@qq.com
 * 
 * @property int $id 新闻ID，对应于Archive的ID
 * @property string $source 新闻来源
 * @property string $content 新闻内容
 *
 */
class News extends CActiveRecord
{
	public $id;
	public $source;
	public $content;
	
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
		return '{{news}}';
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
			array('id', 'required')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'content' => '内容',
			'status' => '状态',
			'cover' => '图片',
			'source' => '来源'
		);
	}
	
	public function relations()
	{
		return array(
			'archive' => array(self::BELONGS_TO, 'Archive', 'id')
		);
	}
	
	protected function beforeSave()
	{
		if (!$this->id) {
			$this->addError('id', '插入或更新需要指定ID');
			return false;
		}
		return parent::beforeSave();
	}
	
	public static function deleteNews($ids)
	{
		if ( !is_array($ids) )
			$ids = array($ids);
		
		$criteria = new CDbCriteria();
		$criteria->addInCondition('id', $ids);
	
		return self::model()->deleteAll($criteria);
	}
	
	public static function findNews($id)
	{
		$id = intval($id);
		$sql = "SELECT * FROM `{{news}}` WHERE `id`='{$id}'";
		return Yii::app()->db->createCommand($sql)->queryRow();
	}
}
?>