<?php
class UserForm extends CFormModel
{
	public $id;
	public $username;
	public $password;
	public $repeat_password;
	public $role_id;
	public $email;
	
	public function rules()
	{
		return array(
			array('id, username', 'required', 'on' => 'update'),
			array('username, password, repeat_password', 'required', 'on' => 'insert'),
			array('repeat_password', 'compare', 'compareAttribute'=>'password'),
		);
	}
	
	public function attributeLabels()
	{
		return array(
			'username' => '用户名',
			'password' => '密码',
			'repeat_password' => '确认密码',
			'email' => 'E-Mail',
			'role_id' => '角色'
		);
	}
	
	public function addUser(array $data)
	{
		//非超级管理员不能创建用户
		if ( !Yii::app()->user->isAdmin() )
			return false;
		
		$this->setAttributes($data, false);
		
		if($this->validate()) {
			$this->role_id = intval($this->role_id);
			
			
			$user = new User();
			$user->id = null;
			$user->username = $this->username;
			$user->password = User::hashPassword($this->password);
			$user->email = $this->email;
			$user->last_login = time();
			$user->role_id = $this->role_id;
			
			if ($user->save()) {
				$this->id = $user->id;
				return true;
			} else {
				$this->addErrors($user->getErrors());
			}
		}
		
		return false;
	}
	
	public function updateUser(array $data)
	{
		$this->setAttributes($data, false);
		if($this->validate()) {
			$this->role_id = intval($this->role_id);
			if ( !Yii::app()->user->isAdmin() && $this->role_id === User::ROLE_ADMIN ) {
				$this->addError('role_id', "非超级管理员不能更新用户角色为超级管理员！");
				return false;
			}
			
			$user = User::model()->findByPk($this->id);
			$user->id = $this->id;
			
			//只有超级管理员才能修改用户名
			if ( Yii::app()->user->isAdmin() ) {
				$user->username = $this->username;
			}
			
			$user->email = $this->email;
			$user->role_id = $this->role_id;
			
			//修改了密码，同时也清除登录信息
			if ($this->password) {
				$user->password = User::hashPassword($this->password);
				$user->login_token = null;
			}

			$this->password = $this->repeat_password = '';
			
			if ($user->save()) {
				return true;
			} else {
				$this->addErrors($user->getErrors());
			}
		}
		
		return false;
	}
}
?>