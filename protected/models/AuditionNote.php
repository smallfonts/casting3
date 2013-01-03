<?php

/**
 * This is the model class for table "audition_note".
 *
 * The followings are the available columns in table 'audition_note':
 * @property string $audition_noteid
 * @property string $auditionid
 * @property string $start
 * @property string $end
 * @property string $title
 * @property string $text
 *
 * The followings are the available model relations:
 * @property Audition $audition
 */
class AuditionNote extends CActiveRecord
{
    
        public function toArray(){
            return array(
              'audition_noteid' => $this->audition_noteid,
              'auditionid' => $this->auditionid,
              'start' => $this->start,
              'end' => $this->end,
              'title' => $this->title,
              'text' => $this->text,
            );
        }
        
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return AuditionNote the static model class
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
		return 'audition_note';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('auditionid, start, end', 'required'),
			array('auditionid, title', 'length', 'max'=>100),
                        array('text','safe'),
                        // The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('audition_noteid, auditionid, start, end, title, text', 'safe', 'on'=>'search'),
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
			'audition' => array(self::BELONGS_TO, 'Audition', 'auditionid'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'audition_noteid' => 'Audition Noteid',
			'auditionid' => 'Auditionid',
			'start' => 'Start',
			'end' => 'End',
			'title' => 'Title',
			'text' => 'Text',
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

		$criteria->compare('audition_noteid',$this->audition_noteid,true);
		$criteria->compare('auditionid',$this->auditionid,true);
		$criteria->compare('start',$this->start,true);
		$criteria->compare('end',$this->end,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('text',$this->text,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}