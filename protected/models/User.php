<?php

class User extends CActiveRecord
{
	public $id;
	public $username;
	public $password;
	public $email;
	public $role_id;
	public $last_login;
	
	const ROLE_ADMIN = 1;
	const ROLE_MANAGER = 2;

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
		return '{{user}}';
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
			array('username, password, role_id', 'required', 'on' => 'insert'),
			//在添加新用户或编辑用户信息时，用户名的验证规则
			array(
				'username','match',
				'pattern'=>'/^[a-zA-Z0-9_]{4,16}$/u',
				'message'=>'账号只能由4-16个字母，数字，下划线组成', 
				'on' => 'insert, update'
			),
			array('id', 'required', 'on' => 'update')
		);
	}
	
	protected function beforeSave()
	{
		if ( $this->getIsNewRecord() ) {
			if ( $this->exists('username=:username', array(':username' => $this->username))) {
				$this->addError('username', '用户名已经存在！');
				return false;
			}
		} else {
			if ( $this->exists('id<>:id AND username=:username', array(':id' => $this->id, ':username' => $this->username))) {
				$this->addError('username', '用户名已经存在！');
				return false;
			}
		}
		
		return parent::beforeSave();
	}
	
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'username' => '用户名',
			'password' => '密码',
			'email' => 'Email',
			'role_id' => '角色',
			'last_login' => '最近登录时间',
		);
	}

	/**
	 * 验证给定的密码（未加密）是否正确，
	 * 这个用在用户登录的时候根据用户名得到用户信息，然后再验证密码是否正确
	 * 
	 * @param string the password
	 * @return boolean
	 */
	public function validatePassword($password)
	{
		return self::hashPassword($password) === $this->password;
	}

	/**
	 * 对密码加密
	 * 
	 * @param string password
	 * @param string salt
	 * @return string hash
	 */
	public static function hashPassword($password)
	{
		return md5($password);
	}
	
	public static function getRoleSelectOptions()
	{
		return array(
			self::ROLE_MANAGER	=> '管理员',
			self::ROLE_ADMIN => '超级管理员'
		);
	}
	
	/**
	 * 验证$token是否与保存在数据库中的token是否一致
	 * 
	 * @param int $id
	 * @param string $token
	 * @return boolean
	 */
	public static function checkLoginToken($id, $token)
	{
		if ( !$token )
			return false;
		
		$sql = "SELECT id FROM {{user}} WHERE id=:id AND login_token=:login_token";
		$row = Yii::app()->db->createCommand($sql)->queryRow(true, array(
			':id' => $id,
			':login_token' => $token
		));
		
		if ( $row )
			return true;
		
		return false;
	}
	
	/**
	 * 更新用户token信息，在登录成功时调用，并保存到cookie中，以便在下次访问时调用ckeckLoginToken来进行验证
	 * 
	 * @param int $id
	 * @return string 返回token
	 */
	public static function updateLoginToken($id)
	{
		$time = time();
		$token = strtolower(md5($id . $time));
		$sql = "UPDATE {{user}} SET login_token='{$token}', last_login='{$time}' WHERE id=:id";
		Yii::app()->db->createCommand($sql)->execute(array(':id' => $id));
		
		return $token;
	}
}