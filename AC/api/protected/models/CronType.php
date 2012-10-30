<?php

/**
 * This is the model class for table "cron_type".
 *
 * The followings are the available columns in table 'cron_type':
 * @property double $id
 * @property string $name
 * @property integer $period
 * @property integer $mileage
 * @property integer $primary
 */
class CronType extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return CronType the static model class
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
		return 'cron_type';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('period, mileage, primary, creator_id', 'numerical', 'integerOnly'=>true),
			array('mileage_multiplier', 'numerical'),
			array('name, name_ru', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, creator_id, name, name_ru, period, mileage, primary', 'safe', 'on'=>'search'),
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
			'creator_id' => Yii::t('app','Creator'),
			'name' => Yii::t('app','Name'),
			'name_ru' => Yii::t('app','Name RU'),
			'period' => Yii::t('app','Period'),
			'mileage' => Yii::t('app','Mileage'),
			'primary' => Yii::t('app','Primary'),
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
		$criteria->compare('creator_id',$this->creator_id,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('period',$this->period);
		$criteria->compare('mileage',$this->mileage);
		$criteria->compare('primary',$this->primary);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public function getName(){
		return isset($this[name.'_'.Yii::app()->language])&&$this[name.'_'.Yii::app()->language] ? 
					$this[name.'_'.Yii::app()->language] : 
					$this->name;
	}
	
	protected function afterFind(){
		$this->name = $this->getName();
		return true;
	}
}