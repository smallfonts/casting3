<?php

/**
 * This is the model class for table "casting_manager_portfolio".
 *
 * The followings are the available columns in table 'casting_manager_portfolio':
 * @property string $casting_manager_portfolioid
 * @property string $userid
 * @property integer $mobile
 * @property string $first_name
 * @property string $last_name
 * @property string $token
 * @property string $photoid
 *
 * The followings are the available model relations:
 * @property UserAccount $user
 * @property Status $status
 * @property Photo $photo
 */
class CastingManagerPortfolio extends CActiveRecord {

    
    public function getName(){
        return $this->first_name;
    }
    
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return CastingManagerPortfolio the static model class
     */
    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'casting_manager_portfolio';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('userid', 'required'),
            array('mobile', 'numerical', 'integerOnly' => true),
            array('userid', 'length', 'max' => 100),
            array('first_name, last_name', 'length', 'max' => 255),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('casting_manager_portfolioid, userid, mobile, first_name, last_name', 'safe', 'on' => 'search'),
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
            'status' => array(self::BELONGS_TO, 'Status', 'statusid'),
            'photo' => array(self::BELONGS_TO, 'Photo', 'photoid'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'casting_manager_portfolioid' => 'Casting Manager Portfolioid',
            'userid' => 'Userid',
            'mobile' => 'Mobile',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
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

        $criteria->compare('casting_manager_portfolioid', $this->casting_manager_portfolioid, true);
        $criteria->compare('userid', $this->userid, true);
        $criteria->compare('mobile', $this->mobile);
        $criteria->compare('first_name', $this->first_name, true);
        $criteria->compare('last_name', $this->last_name, true);
        $criteria->compare('token', $this->token, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

    public function toArray() {
        return array(
            'casting_manager_portfolioid' => $this->casting_manager_portfolioid,
            'userid' => $this->userid,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'mobile' => $this->mobile,
            'email' => $this->user->email,
            'statusid' => $this->statusid,
            'token' => $this->token,
            'prodhouse' => $this->getProductionHouse(),
            'photourl' => $this->photo->url,
        );
    }
    
    public function getProductionHouse(){
        $phu = ProductionHouseUser::model()->findByAttributes(array(
            'cm_userid' => $this->userid
        ));
        $prodhse = ProductionPortfolio::model()->findByAttributes(array(
            'userid' => $phu->production_userid
        ));
        return $prodhse->name;
    }
    

}