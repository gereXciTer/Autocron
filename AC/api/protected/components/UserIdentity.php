<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
	public $_id;
	
	public function authenticate()
	{
		$email=strtolower($this->username);
        $user=User::model()->find('LOWER(email)=?',array($email));
        if(empty($user)){
            $this->errorCode=self::ERROR_USERNAME_INVALID;
        }else if(!$user->validatePassword($this->password)){
            $this->errorCode=self::ERROR_PASSWORD_INVALID;
        }else{
            $this->_id=$user->id;
			$this->username = $user->name;
			$user->last_login = new CDbExpression('NOW()');
			$user->update();
			Yii::app()->request->cookies['AutocronUserLanguage'] = new CHttpCookie('AutocronUserLanguage', $user->locale);
            $this->errorCode=self::ERROR_NONE;
        }
        return !$this->errorCode;
	}
	public function getId()
	{
	    return $this->_id;
	}
}