<?php

/**
 * This is the model class for table "artiste_portfolio_profession".
 *
 * The followings are the available columns in table 'artiste_portfolio_profession':
 * @property string $professionid
 * @property string $artiste_portfolioid
 */
class ArtistePortfolioProfession extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ArtistePortfolioProfession the static model class
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
		return 'artiste_portfolio_profession';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('professionid, artiste_portfolioid', 'required'),
			array('professionid, artiste_portfolioid', 'length', 'max'=>100),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('professionid, artiste_portfolioid', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'professionid' => 'Professionid',
			'artiste_portfolioid' => 'Artiste Portfolioid',
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

		$criteria->compare('professionid',$this->professionid,true);
		$criteria->compare('artiste_portfolioid',$this->artiste_portfolioid,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}