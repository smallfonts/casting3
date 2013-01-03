<?php

/**
 * This is the model class for table "video".
 *
 * The followings are the available columns in table 'video':
 * @property string $videoid
 * @property string $userid
 * @property string $url
 *
 * The followings are the available model relations:
 * @property ArtistePortfolio[] $artistePortfolios
 * @property UserAccount $user
 */
class Video extends CActiveRecord
{
    
        public function toArray(){
            return array(
                'videoid' => $this->videoid,
                'url' => $this->url,
            );
        }
    
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Video the static model class
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
		return 'video';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('url', 'required'),
			array('videoid, userid', 'length', 'max'=>100),
			array('url', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('videoid, userid, url', 'safe', 'on'=>'search'),
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
			'artistePortfolios' => array(self::HAS_MANY, 'ArtistePortfolio', 'videoid'),
			'user' => array(self::BELONGS_TO, 'UserAccount', 'userid'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'videoid' => 'Videoid',
			'userid' => 'Userid',
			'url' => 'Url',
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

		$criteria->compare('videoid',$this->videoid,true);
		$criteria->compare('userid',$this->userid,true);
		$criteria->compare('url',$this->url,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}