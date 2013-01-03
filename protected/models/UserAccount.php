<?php

/**
 * This is the model class for table "user_account".
 *
 * The followings are the available columns in table 'user_account':
 * @property string $userid
 * @property string $password
 * @property string $email
 * @property string $roleid
 * @property string $statusid
 */
class UserAccount extends CActiveRecord {

    public $password2;
    public $email2;
    public $role;
    public $status;
    public $name;

    public function toArray() {
        return array(
            'userid' => $this->userid,
            'email' => $this->email,
        );
    }

    /*
     * Performs the finding of role.name after user account object is retrieved
     */

    public function afterFind() {

        //store role name locally
        $model = Role::model()->findByPK($this->roleid);
        $this->role = $model->name;

        //store status name locally
        $model = Status::model()->findByPK($this->statusid);
        $this->status = $model->name;
    }

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return UserAccount the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'user_account';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('password, email, roleid, statusid', 'required'),
            array('password2,email2', 'required', 'on' => 'insert'),
            array('email', 'email'),
            array('email', 'unique'),
            array('youtube_token', 'safe'),
            // passwords must be identical
            array('email2', 'compare', 'compareAttribute' => 'email', 'on' => 'insert'),
            array('password2', 'compare', 'compareAttribute' => 'password', 'on' => 'insert'),
            array('password, email', 'length', 'max' => 255),
            array('roleid, statusid', 'length', 'max' => 100),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('userid, name, password, email, roleid, statusid', 'safe', 'on' => 'search'),
        );
    }

    public function beforeSave() {
        $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);
        $log->logInfo("Scenario is : " . $this->scenario);
        switch ($this->scenario) {
            case 'insert': case 'changePassword':
                $log->logInfo("Password plaintext is " . $this->password);
                $this->password = CryptoUtil::hashPassword($this->password);
                $log->logInfo("Password hash is " . $this->password);
                break;
        }

        return true;
    }

    /*
     * Creates Portfolio for user after account is created
     * 
     * 
     */

    public function afterSave() {
        $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);
        $url = time() . $this->userid;
        if ($this->scenario == 'insert') {
            switch ($this->roleid) {
                case 1:

                    $log->logInfo("Creating ArtistePortfolio");
                    $model = new ArtistePortfolio;
                    $model->setAttributes(array(
                        'userid' => $this->userid,
                        'name' => '',
                        'email' => $this->email,
                    ));
                    $model->url = $url;
                    $model->photoid = 1;
                    $model->videoid = 1;
                    $model->save();

                    break;

                case 2:

                    $log->logInfo("Creating ProductionHousePortfolio");
                    $model = new ProductionPortfolio;
                    $model->setAttributes(array(
                        'userid' => $this->userid,
                        'name' => '',
                        'photoid' => 2,
                    ));
                    $model->url = $url;
                    $model->save();

                    break;
            }
        }
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'password' => 'Password',
            'password2' => 'Repeat Password',
            'email' => 'Email',
            'email2' => 'Repeat Email to Verify',
        );
    }

    public function getPortfolio() {
        switch ($this->roleid) {
            case '1':
                return $this->artistePortfolio;
            case '2':
                return $this->productionPortfolio;
            case '4':
                return $this->castingmanagerPortfolio;
        }
        return null;
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'artistePortfolio' => array(self::HAS_ONE, 'ArtistePortfolio', 'userid'),
            'productionPortfolio' => array(self::HAS_ONE, 'ProductionPortfolio', 'userid'),
            'castingmanagerPortfolio' => array(self::HAS_ONE, 'CastingManagerPortfolio', 'userid'),
            'roleid' => array(self::BELONGS_TO, 'Role', 'roleid'),
            'statusid' => array(self::BELONGS_TO, 'Status', 'statusid'),
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        if (!is_null($this->name)) {
            $criteria->addCondition("userid IN (SELECT userid FROM `artiste_portfolio` WHERE name LIKE :name)", 'OR');
            $criteria->addCondition("userid IN (SELECT userid FROM `production_portfolio` WHERE name LIKE :name)", 'OR');
            $criteria->addCondition("userid IN (SELECT userid FROM `casting_manager_portfolio` WHERE first_name LIKE :name or last_name LIKE :name)", 'OR');
            $criteria->params[':name'] = '%' . $this->name . '%';
        }

        if (!is_null($this->email)) {
            $criteria->addCondition("email LIKE :email", 'OR');
            $criteria->params[':email'] = "%" . $this->email . "%";
        }

        if (!is_null($this->userid)) {
            if (is_array($this->userid)) {
                $criteria->addInCondition('userid',$this->userid);
            } else {
                $criteria->compare('userid', $this->userid, true);
            }
        }
        $criteria->compare('password', $this->password, true);
        $criteria->compare('roleid', $this->roleid, true);
        $criteria->compare('statusid', $this->statusid, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

}

?>