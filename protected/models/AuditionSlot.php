<?php

/**
 * This is the model class for table "audition_slot".
 *
 * The followings are the available columns in table 'audition_slot':
 * @property string $audition_slotid
 * @property string $auditionid
 * @property string $start
 * @property string $end
 *
 * The followings are the available model relations:
 * @property Audition $audition
 */
class AuditionSlot extends CActiveRecord
{
    
        public function toArray(){
            return array(
                'audition_slotid'=>$this->audition_slotid,
                'auditionid'=>$this->auditionid,
                'start'=>$this->start,
                'end'=>$this->end,
                'status'=>$this->status->toArray(),
                'fixed'=>$this->fixed,
            );
        }
    
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return AuditionSlot the static model class
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
		return 'audition_slot';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('auditionid, start, end, statusid', 'required'),
			array('auditionid', 'length', 'max'=>100),
                        array('fixed','safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('audition_slotid, auditionid, start, end', 'safe', 'on'=>'search'),
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
                        'status' => array(self::BELONGS_TO, 'Status', 'statusid'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'audition_slotid' => 'Audition Slotid',
			'auditionid' => 'Auditionid',
			'start' => 'Start',
			'end' => 'End',
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

		$criteria->compare('audition_slotid',$this->audition_slotid,true);
		$criteria->compare('auditionid',$this->auditionid,true);
		$criteria->compare('start',$this->start,true);
		$criteria->compare('end',$this->end,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}