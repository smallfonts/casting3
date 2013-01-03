<?php

/**
 * This is the model class for table "other_requirement".
 *
 * The followings are the available columns in table 'other_requirement':
 * @property string $other_requirementid
 * @property string $characterid
 * @property string $requirement
 * @property string $desc
 *
 * The followings are the available model relations:
 * @property Character $character
 */
class OtherRequirement extends CActiveRecord
{
        
        public function toArray(){
            return array(
                'other_requirementid' => $this->other_requirementid,
                'characterid' => $this->characterid,
                'requirement' => $this->requirement,
                'desc' => $this->desc,
            );
        }
    
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return OtherRequirement the static model class
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
		return 'other_requirement';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
                        array('characterid, requirement, desc','required','on'=>'insert, update'),
			array('characterid', 'length', 'max'=>100),
			array('requirement', 'length', 'max'=>255),
			array('desc', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('other_requirementid, characterid, requirement, desc', 'safe', 'on'=>'search'),
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
			'other_requirementid' => 'Other Requirementid',
			'characterid' => 'Characterid',
			'requirement' => 'Requirement',
			'desc' => 'Desc',
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

		$criteria->compare('other_requirementid',$this->other_requirementid,true);
		$criteria->compare('characterid',$this->characterid,true);
		$criteria->compare('requirement',$this->requirement,true);
		$criteria->compare('desc',$this->desc,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}