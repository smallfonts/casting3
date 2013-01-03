<?php

/**
 * This is the model class for table "production_house_user".
 *
 * The followings are the available columns in table 'production_house_user':
 * @property string $production_house_userid
 * @property string $production_userid
 * @property string $cm_userid
 *
 * The followings are the available model relations:
 * @property UserAccount $productionUser
 * @property UserAccount $cmUser
 */
class ProductionHouseUser extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return ProductionHouseUser the static model class
     */
    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'production_house_user';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('production_userid, cm_userid', 'required'),
            array('production_userid, cm_userid', 'length', 'max' => 100),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('production_house_userid, production_userid, cm_userid', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'productionUser' => array(self::BELONGS_TO, 'UserAccount', 'production_userid'),
            'cmUser' => array(self::BELONGS_TO, 'UserAccount', 'cm_userid'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'production_house_userid' => 'Production House Userid',
            'production_userid' => 'Production Userid',
            'cm_userid' => 'Cm Userid',
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

        $criteria->compare('production_house_userid', $this->production_house_userid, true);
        $criteria->compare('production_userid', $this->production_userid, true);
        $criteria->compare('cm_userid', $this->cm_userid, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

    public function toArray() {
        return array(
            'production_house_userid' => $this->production_house_userid,
            'production_userid' => $this->production_userid,
            'cm_userid' => $this->cm_userid,
            'casting_manager_portfolio' => $this->getCastingManagerPortfolio()->toArray()
        );
    }

    public function getCastingManagerPortfolio() {
        $log = new Logger(get_class($this));
        $log->setMethod(__FUNCTION__);
        $result = CastingManagerPortfolio::model()->findByAttributes(array(
            'userid' => $this->cm_userid
                ));
        $log->logInfo('cm ' . json_encode($result->toArray()));
        return $result;
    }

}