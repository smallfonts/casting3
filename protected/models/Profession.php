<?php

/**
 * This is the model class for table "profession".
 *
 * The followings are the available columns in table 'profession':
 * @property string $professionid
 * @property string $name
 *
 * The followings are the available model relations:
 * @property ArtistePortfolio[] $artistePortfolios
 */
class Profession extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Profession the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'profession';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name', 'required'),
            array('name', 'length', 'max' => 255),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('professionid, name', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'artistePortfolios' => array(self::MANY_MANY, 'ArtistePortfolio', 'artiste_portfolio_profession(professionid, artiste_portfolioid)'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'professionid' => 'Professionid',
            'name' => 'Name',
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

        if($this->professionid){
            $arr = $this->professionid;
            if(!is_array($this->professionid)) $arr = array($this->professionid);
            $criteria->addInCondition('professionid', $arr);
        }
        
        $criteria->compare('name', $this->name, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

}