<?php

/**
 * This is the model class for table "password_reset_token".
 *
 * The followings are the available columns in table 'password_reset_token':
 * @property string $userid
 * @property string $token
 *
 * The followings are the available model relations:
 * @property UserAccount $user
 */
class PasswordResetToken extends CActiveRecord {

    public $email;
    public $tokenLength = 100;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return PasswordResetToken the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'password_reset_token';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
// NOTE: you should only define rules for those attributes that
// will receive user inputs.
        return array(
            array('email', 'required'),
            array('email', 'email'),
            array('email', 'exist', 'className' => 'UserAccount', 'attributeName' => 'email'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
// NOTE: you may need to adjust the relation name and the related
// class name for the relations automatically generated below.
        return array(
            'user' => array(self::BELONGS_TO, 'UserAccount', 'userid'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'email' => 'Email'
        );
    }

    public function beforeSave() {
        $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);
        $log->logInfo('before save');
        $this->token = CryptoUtil::generateToken($this->tokenLength);
        $user = null;

        if (isset(Yii::app()->user->account)) {
            $user = Yii::app()->user->account;
        } else {
            $user = UserAccount::model()->findByAttributes(array('email' => $this->email));
        }
        $log->logInfo('user:'.$user->userid);
        //check for existing tokens first
        $criteria = new CDbCriteria;
        $criteria->compare('userid', $user->userid, true);
        $prt = PasswordResetToken::model()->find($criteria);
        $log->logInfo('search');
        //delete existing tokens if any
        if (!is_null($prt)) {
            $prt->delete();
        }

        //create new token
        $this->userid = $user->userid;

        $log->logInfo("Reset Password requested for : " . $this->email);
        $log->logInfo("Reset Password request userid: " . $this->userid);
        $log->logInfo("Generate Reset Password Token: " . $this->token);
        return true;
    }

}