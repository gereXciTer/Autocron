<?php

/**
 * This is the model class for table "expense".
 *
 * The followings are the available columns in table 'expense':
 * @property double $id
 * @property double $uid
 * @property double $car_id
 * @property double $category_id
 * @property string $title
 * @property string $description
 * @property double $value
 * @property integer $currency
 */
class Expense extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Expense the static model class
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
		return 'expense';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('currency', 'numerical', 'integerOnly'=>true),
			array('uid, car_id, category_id, value', 'numerical'),
			array('title', 'length', 'max'=>255),
			array('description', 'safe'),
			array('time, value', 'required', 'on'=>'create'),
//			array('time', 'default', 'value'=>new CDbExpression('NOW()'), 'setOnEmpty'=>false, 'on'=>'create'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, uid, car_id, category_id, title, description, value, currency, time', 'safe', 'on'=>'search'),
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
			'uid' => Yii::t('app', 'User'),
			'car_id' => Yii::t('app', 'Car'),
			'category_id' => Yii::t('app', 'Category'),
			'title' => Yii::t('app', 'Title'),
			'description' => Yii::t('app', 'Description'),
			'value' => Yii::t('app', 'Cost'),
			'currency' => Yii::t('app', 'Currency'),
			'time' => Yii::t('app', 'Time'),
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
		$criteria->compare('category_id',$this->category_id);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('value',$this->value);
		$criteria->compare('currency',$this->currency);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function getValue(){
		return $this->value;
	}
	
	public function getCategory(){
		$cat = ExpenseCategory::model()->findByPk($this->category_id);
		$catname = $cat->name ? $cat->name : $this->title;
		return $catname;
	}
}