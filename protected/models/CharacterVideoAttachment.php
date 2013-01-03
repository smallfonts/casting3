<?php

/**
 * This is the model class for table "character_video_attachment".
 *
 * The followings are the available columns in table 'character_video_attachment':
 * @property string $character_video_attachmentid
 * @property string $characterid
 * @property string $title
 * @property string $desc
 *
 * The followings are the available model relations:
 * @property Character $character
 */
class CharacterVideoAttachment extends CActiveRecord
{
    
        public function toArray(){
            return array(
                'character_video_attachmentid' => $this->character_video_attachmentid,
                'title' => $this->title,
                'desc' => $this->desc,
            );
        }
    
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return CharacterVideoAttachment the static model class
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
		return 'character_video_attachment';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('characterid', 'required'),
                        array('title, desc', 'required', 'on'=>'update, insert'),
			array('characterid', 'length', 'max'=>100),
			array('title', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('character_video_attachmentid, characterid, title, desc', 'safe', 'on'=>'search'),
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
			'character' => array(self::BELONGS_TO, 'Character', 'characterid'),
        
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'character_video_attachmentid' => 'Character Video Attachmentid',
			'characterid' => 'Characterid',
			'title' => 'Title',
			'desc' => 'Description',
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

		$criteria->compare('character_video_attachmentid',$this->character_video_attachmentid,true);
		$criteria->compare('characterid',$this->characterid,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('desc',$this->desc,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}