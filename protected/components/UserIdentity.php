<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity {

    /**
     * Authenticates a user.
     * The example implementation makes sure if the username and password
     * are both 'demo'.
     * In practical applications, this should be changed to authenticate
     * against some persistent user identity storage (e.g. database).
     * @return boolean whether authentication succeeds.
     */
    public function authenticate() {
        //settin up logger

        $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);

        //gets user object
        $user = UserAccount::model()->findByAttributes(array('email' => $this->username));
        if ($user == null) {
            $this->errorCode = self::ERROR_USERNAME_INVALID;
            $log->LogError("Username not in database");
        } else if ($user->password != $this->password) {

            $this->errorCode = self::ERROR_PASSWORD_INVALID;
            $log->LogError("Passwords do not match");
        } else {
            //assign roles to the UserIdentity
            $this->setState('roles', $user->role);
            $this->errorCode = self::ERROR_NONE;
            $this->setState('account', $user);
            $log->LogInfo("Authentication Successful");
        }

        return !$this->errorCode;
    }

    public function tokenAuthenticate() {
        $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);
        
        //gets user object
        $user = UserAccount::model()->findByAttributes(array('email' => $this->username));
        
        //assign roles to the UserIdentity
        $this->setState('roles', $user->role);
        $this->errorCode = self::ERROR_NONE;
        $this->setState('account', $user);
        $log->LogInfo("Authentication Successful");

        return !$this->errorCode;
    }

}

?>