<?php

/**
 * This is the model class for table "shop".
 *
 * The followings are the available columns in table 'shop':
 * @property double $id
 * @property double $uid
 * @property string $name
 * @property string $country
 * @property string $state
 * @property string $address
 * @property string $coordinates
 * @property string $description
 * @property string $phones
 */
class Shop extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Shop the static model class
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
		return 'shop';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('uid', 'numerical'),
			array('name, country, state, address, coordinates, phones', 'length', 'max'=>255),
			array('description', 'safe'),
			array('name, description', 'required', 'on'=>'create'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, uid, name, country, state, address, coordinates, description, phones', 'safe', 'on'=>'search'),
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
			'name' => Yii::t('app', 'Shop name'),
			'country' => Yii::t('app', 'Country'),
			'state' => Yii::t('app', 'State'),
			'address' => Yii::t('app', 'Address'),
			'coordinates' => Yii::t('app', 'Coordinates'),
			'description' => Yii::t('app', 'Description'),
			'phones' => Yii::t('app', 'Phones'),
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('country',$this->country,true);
		$criteria->compare('state',$this->state,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('coordinates',$this->coordinates,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('phones',$this->phones,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}