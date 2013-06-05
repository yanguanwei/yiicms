<?php
class ChannelAlias extends CActiveRecord
{
	public $id;
	public $identifier;
	public $alias;
	
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
		return '{{channel_alias}}';
	}
	
	public function primaryKey()
	{
		return 'id';
	}
	
	public function rules()
	{
		return array(
			array('id, identifier, alias', 'required')		
		);	
	}
	
	public function attributeLabels()
	{
		return array(
			'id'	=> '栏目ID',
			'alias' => '栏目别名'		
		);	
	}
	
	protected function beforeSave()
	{
		if ( in_array(strtolower($this->alias), array('index')) ) {
			$this->addError('alias', '此别名为限制的别名！');
			return false;
		}
		
		if ( $this->exists('id<>:id AND identifier=:identifier', array('id'=>$this->id, 'identifier' => $this->identifier))) {
			$this->addError('alias', '栏目别名已经存在！');
			return false;
		}
		
		return parent::beforeSave();
	}
	
	public static function getChannelAlias($id)
	{
		$id = intval($id);
		$sql = "SELECT alias FROM {{channel_alias}} WHERE id='{$id}'";
		$row = Yii::app()->db->createCommand($sql)->queryRow();
		if ( $row )
			return $row['alias'];
	}
	
	public static function generateIdentifier($themeId, $alias)
	{
		return substr(md5($themeId.$alias), 8, 16);
	}
	
	/**
	 * 为栏目创建别名控制器
	 */
	public static function updateChannelController()
	{
		$sql = "SELECT ca.alias, c.theme_id, c.* FROM {{channel_alias}} ca LEFT JOIN {{channel}} c ON ca.id=c.id ORDER BY ca.id ASC";
		$controllerFile = Yii::getPathOfAlias('frontend.controllers') . '/ChannelController.php';
	
		$actions = array();
		
		foreach (Yii::app()->db->createCommand($sql)->queryAll() as $row) {
			$actions[$row['alias']][$row['theme_id']] = array(
				'id' => $row['id'],
				'template' => $row['channel_template']	
			);
			
		}
		
		
		$code = <<<code
<?php
class ChannelController extends ChannelBehaviorController
{

code;
		foreach ($actions as $name => $channels) {
			$actionName = ucfirst($name);
			
			$channels = var_export($channels, true);
			
			$code .= <<<code
	public function action{$actionName}()
	{
		\$channels = {$channels};
	
		if ( !\$channels[Yii::app()->params['theme_id']] )
			throw new CHttpException(404);
		
		\$current = \$channels[Yii::app()->params['theme_id']];
		if ( !\$current['template'] )
			throw new CHttpException(404);
			
		if ( \$current['template'] === '1' )
			return \$this->redirect(\$this->createChannelUrl(\$this->getFirstSubChannelId(\$current['id'])));
			
		\$this->activeNavKey = ChannelAlias::getChannelAlias(Channel::getTopChannelId(\$current['id']));

		return \$this->render(\$current['template'], array(
				'channel_id' => \$current['id']
			));
	}

code;
		}
		
		$code .= <<<code
}	
code;
		file_put_contents($controllerFile, $code);
	}
}
?>