<?php

/**
 * This is the model class for table "car_model_variant".
 *
 * The followings are the available columns in table 'car_model_variant':
 * @property double $id
 * @property string $name
 * @property string $code
 * @property string $model_name
 * @property string $model_img
 * @property integer $model_id
 * @property integer $version_id
 * @property integer $doors
 * @property string $power
 * @property string $maxspeed
 * @property integer $acceleration
 * @property integer $capacity
 * @property integer $start
 * @property integer $stop
 */
class CarModelVariant extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return CarModelVariant the static model class
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
		return 'car_model_variant';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('model_id, version_id, doors, acceleration, capacity, start, stop', 'numerical', 'integerOnly'=>true),
			array('name, code, model_img', 'length', 'max'=>255),
			array('model_name', 'length', 'max'=>50),
			array('power, maxspeed', 'length', 'max'=>10),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, code, model_name, model_img, model_id, version_id, doors, power, maxspeed, acceleration, capacity, start, stop', 'safe', 'on'=>'search'),
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
			'name' => 'Name',
			'code' => 'Code',
			'model_name' => 'Model Name',
			'model_img' => 'Model Img',
			'model_id' => 'Model',
			'version_id' => 'Version',
			'doors' => 'Doors',
			'power' => 'Power',
			'maxspeed' => 'Maxspeed',
			'acceleration' => 'Acceleration',
			'capacity' => 'Capacity',
			'start' => 'Start',
			'stop' => 'Stop',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('code',$this->code,true);
		$criteria->compare('model_name',$this->model_name,true);
		$criteria->compare('model_img',$this->model_img,true);
		$criteria->compare('model_id',$this->model_id);
		$criteria->compare('version_id',$this->version_id);
		$criteria->compare('doors',$this->doors);
		$criteria->compare('power',$this->power,true);
		$criteria->compare('maxspeed',$this->maxspeed,true);
		$criteria->compare('acceleration',$this->acceleration);
		$criteria->compare('capacity',$this->capacity);
		$criteria->compare('start',$this->start);
		$criteria->compare('stop',$this->stop);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}