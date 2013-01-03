<?php

/**
 * This is the model class for table "spoken_language".
 *
 * The followings are the available columns in table 'spoken_language':
 * @property string $languageid
 * @property string $artiste_portfolioid
 * @property string $language_proficiencyid
 *
 * The followings are the available model relations:
 * @property ArtistePortfolio $artistePortfolio
 */
class SpokenLanguage extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return SpokenLanguage the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'spoken_language';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('languageid, artiste_portfolioid, language_proficiencyid', 'required'),
			array('languageid, artiste_portfolioid, language_proficiencyid', 'length', 'max'=>100),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('languageid, artiste_portfolioid, language_proficiencyid', 'safe', 'on'=>'search'),
		);
	}
        
        
        public function toArray(){
            return array(
                'languageid' => $this->languageid,
                'name' => $this->language->name,
                'language_proficiencyid' => $this->language_proficiencyid,
            );
        }

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
                        'language' => array(self::BELONGS_TO, 'Language', 'languageid'),
			'artistePortfolio' => array(self::BELONGS_TO, 'ArtistePortfolio', 'artiste_portfolioid'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'languageid' => 'Languageid',
			'artiste_portfolioid' => 'Artiste Portfolioid',
                        'language_proficiencyid' => 'Language Proficiencyid',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('languageid',$this->languageid,true);
		$criteria->compare('artiste_portfolioid',$this->artiste_portfolioid,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}