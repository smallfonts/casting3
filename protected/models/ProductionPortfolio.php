<?php

/**
 * This is the model class for table "production_portfolio".
 *
 * The followings are the available columns in table 'production_portfolio':
 * @property string $production_portfolioid
 * @property string $userid
 * @property string $name
 * @property string $country
 * @property string $address
 * @property string $address2
 * @property string $postalcode
 * @property string $email
 * @property string $phone
 * @property string $photoid
 * @property string $url
 *
 * The followings are the available model relations:
 * @property UserAccount $user
 * @property Photo $photo
 */
class ProductionPortfolio extends CActiveRecord {

        
    public function getName(){
        return $this->name;
    }
    
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return ProductionPortfolio the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'production_portfolio';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('userid, url', 'required'),
            array('url', 'unique'),
            array('url', 'match', 'pattern' => '/^[a-zA-Z0-9]*$/', 'message' => 'Url must contain only <strong>Alpha Numeric</strong> characters'),
            array('userid, photoid', 'length', 'max' => 100),
            array('name, country, address, address2, postalcode, email, phone, website' , 'length', 'max' => 255),
            array('description, products','length', 'max' =>500),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('production_portfolioid, userid, name, country, address, address2, email, phone, photoid', 'safe', 'on' => 'search'),
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
            'photo' => array(self::BELONGS_TO, 'Photo', 'photoid'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'production_portfolioid' => 'Production Portfolioid',
            'userid' => 'Userid',
            'name' => 'Name',
            'country' => 'Country',
            'address' => 'Address',
            'address2' => 'Address2',
            'email' => 'Email',
            'phone' => 'Phone',
            'photoid' => 'Photoid',
            'postalcode' => 'Postal Code',
            'url' => 'URL',
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

        $criteria->compare('production_portfolioid', $this->production_portfolioid, true);
        $criteria->compare('userid', $this->userid, true);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('country', $this->country, true);
        $criteria->compare('address', $this->address, true);
        $criteria->compare('address2', $this->address2, true);
        $criteria->compare('email', $this->email, true);
        $criteria->compare('phone', $this->phone, true);
        $criteria->compare('photoid', $this->photoid, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

}