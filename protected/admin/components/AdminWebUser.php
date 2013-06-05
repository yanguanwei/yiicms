<?php
class AdminWebUser extends CWebUser
{
	public function isManager()
	{
		return intval($this->getState('role_id', 0)) === User::ROLE_MANAGER;	
	}
	
	public function isAdmin()
	{
		return intval($this->getState('role_id', 0)) === User::ROLE_ADMIN;	
	}
	
	protected function beforeLogin($id, $states, $fromCookie)
	{
		if ( $fromCookie ) {
			if ( !User::checkLoginToken($id, $states['token']) ) {
				return false;
			}
		}
		
		return true;
	}	
}
?>