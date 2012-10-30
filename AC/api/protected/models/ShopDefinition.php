<?php

/**
 * This is the model class for table "shop_definition".
 *
 * The followings are the available columns in table 'shop_definition':
 * @property double $id
 * @property double $shop_id
 * @property double $brand_id
 * @property double $cron_type_id
 */
class ShopDefinition extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ShopDefinition the static model class
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
		return 'shop_definition';
	}
	
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('shop_id, brand_id, cron_type_id', 'numerical'),
			array('all_brands', 'boolean'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, shop_id, brand_id, cron_type_id, all_brands', 'safe', 'on'=>'search'),
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
			'shop_id' => Yii::t('app','Shop'),
			'brand_id' => Yii::t('app','Brand'),
			'cron_type_id' => Yii::t('app','Cron Type'),
			'all_brands'=>Yii::t('app','All Brands'),
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
		$criteria->compare('shop_id',$this->shop_id);
		$criteria->compare('brand_id',$this->brand_id);
		$criteria->compare('cron_type_id',$this->cron_type_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}