<?php
class UserIdentity extends CUserIdentity
{
	private $_id;

	/**
	 * 验证用户
	 * 
	 * @return boolean 验证正确返回true，否则返回false
	 */
	public function authenticate()
	{
		$user = User::model()->find('LOWER(username)=?', array(strtolower($this->username)));
		
		if($user === null)
			$this->errorCode = self::ERROR_USERNAME_INVALID;
		else if(!$user->validatePassword($this->password))
			$this->errorCode = self::ERROR_PASSWORD_INVALID;
		else
		{
			$this->_id = $user->id;
			$this->username = $user->username;
			$this->errorCode = self::ERROR_NONE;
			
			$this->setPersistentStates(array(
				'id' => $user->id,
				'password' => $user->password,
				'username' => $user->username,
				'role_id' => $user->role_id,
				'token' => User::updateLoginToken($user->id)
			));
		}
		
		return $this->errorCode == self::ERROR_NONE;
	}

	/**
	 * @return integer 返回用户Id
	 */
	public function getId()
	{
		return $this->_id;
	}
	
	
}