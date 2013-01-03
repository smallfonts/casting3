<?php

/**
 * This is the model class for table "message_tree".
 *
 * The followings are the available columns in table 'message_tree':
 * @property string $parent_messageid
 * @property string $child_messageid
 *
 * The followings are the available model relations:
 * @property Message $childMessage
 * @property Message $parentMessage
 */
class MessageTree extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return MessageTree the static model class
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
		return 'message_tree';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('parent_messageid, child_messageid', 'required'),
			array('parent_messageid, child_messageid', 'length', 'max'=>100),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('parent_messageid, child_messageid', 'safe', 'on'=>'search'),
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
			'childMessage' => array(self::BELONGS_TO, 'Message', 'child_messageid'),
			'parentMessage' => array(self::BELONGS_TO, 'Message', 'parent_messageid'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'parent_messageid' => 'Parent Messageid',
			'child_messageid' => 'Child Messageid',
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

		$criteria->compare('parent_messageid',$this->parent_messageid,true);
		$criteria->compare('child_messageid',$this->child_messageid,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}