<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
        
	/**
	 * Authenticates a user.
	 * The example implementation makes sure if the username and password
	 * are both 'demo'.
	 * In practical applications, this should be changed to authenticate
	 * against some persistent user identity storage (e.g. database).
	 * @return boolean whether authentication succeeds.
	 */
	public function authenticate()
	{
                        
                $distributors = Distributors::model()->findByAttributes(array('username'=>$this->username));
            		
                if($distributors===null)
                {
                    $this->errorCode=self::ERROR_USERNAME_INVALID;
                }
                else
                {
                    if($distributors->password !== $distributors->hashPassword($this->password))
                        $this->errorCode=self::ERROR_PASSWORD_INVALID;
                    else
                    {
                        // Get user status
                        $status = Distributors::getUserStatus($this->username);
                        
                        if ($status == 1) {
                            
                            $this->errorCode=self::ERROR_NONE;
                        }
                        else {
                            $this->errorCode=self::ERROR_USER_INACTIVE;
                        }
                    }
                }
                   
		return !$this->errorCode;
            
	}
}