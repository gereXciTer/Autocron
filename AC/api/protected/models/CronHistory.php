<?php

/**
 * This is the model class for table "cron_history".
 *
 * The followings are the available columns in table 'cron_history':
 * @property double $id
 * @property double $uid
 * @property double $car_id
 * @property double $type
 * @property integer $mileage
 * @property integer $value
 * @property string $last_update
 */
class CronHistory extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return CronHistory the static model class
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
		return 'cron_history';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('mileage, value', 'numerical', 'integerOnly'=>true),
			array('uid, car_id, type', 'numerical'),
			array('last_update', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, uid, car_id, type, mileage, value, last_update', 'safe', 'on'=>'search'),
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
			'id' => 'ID',
			'uid' => 'Uid',
			'car_id' => Yii::t('app','Car'),
			'type' => Yii::t('app','Type'),
			'mileage' => Yii::t('app','Mileage'),
			'value' => Yii::t('app','Value'),
			'last_update' => Yii::t('app','Last Update'),
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

		$criteria->compare('id',$this->id);
		$criteria->compare('uid',$this->uid);
		$criteria->compare('car_id',$this->car_id);
		$criteria->compare('type',$this->type);
		$criteria->compare('mileage',$this->mileage);
		$criteria->compare('value',$this->value);
		$criteria->compare('last_update',$this->last_update,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public function getType(){
		return CronType::model()->findByPk($this->type)->getName();
	}

}