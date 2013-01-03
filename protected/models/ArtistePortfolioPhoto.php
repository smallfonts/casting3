<?php

/**
 * This is the model class for table "artiste_portfolio_photo".
 *
 * The followings are the available columns in table 'artiste_portfolio_photo':
 * @property string $artiste_portfolioid
 * @property string $photoid
 * @property integer $order
 */
class ArtistePortfolioPhoto extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ArtistePortfolioPhoto the static model class
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
		return 'artiste_portfolio_photo';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('artiste_portfolioid, photoid, order', 'required'),
			array('order', 'numerical', 'integerOnly'=>true),
			array('artiste_portfolioid, photoid', 'length', 'max'=>100),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('artiste_portfolioid, photoid, order', 'safe', 'on'=>'search'),
		);
	}
        
        
        public function toArray(){
            return array(
                'url' => $this->photo->url,
                'order' => $this->order,
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
                    'photo' => array(self::BELONGS_TO, 'Photo', 'photoid'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'artiste_portfolioid' => 'Artiste Portfolioid',
			'photoid' => 'Photoid',
			'order' => 'Order',
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

		$criteria->compare('artiste_portfolioid',$this->artiste_portfolioid,true);
		$criteria->compare('photoid',$this->photoid,true);
		$criteria->compare('order',$this->order);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}