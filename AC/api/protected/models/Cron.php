<?php

/**
 * This is the model class for table "cron".
 *
 * The followings are the available columns in table 'cron':
 * @property double $id
 * @property double $uid
 * @property double $car_id
 * @property double $type
 * @property integer $mileage
 * @property integer $value
 * @property integer $user_period
 * @property integer $user_mileage
 * @property string $last_update
 */
class Cron extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Cron the static model class
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
		return 'cron';
	}
	
	public $edit_current;

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('mileage, value, user_period, user_mileage', 'numerical', 'integerOnly'=>true),
			array('uid, car_id, type, edit_current', 'numerical'),
			array('edit_current', 'safe'),
			array('last_update', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, uid, car_id, type, mileage, value, user_period, user_mileage, last_update, edit_current', 'safe', 'on'=>'search'),
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
			'uid' => Yii::t('app','Uid'),
			'car_id' => Yii::t('app','Car'),
			'type' => Yii::t('app','Type'),
			'mileage' => Yii::t('app','Mileage'),
			'value' => Yii::t('app','Cost'),
			'user_period' => Yii::t('app','Your Period'),
			'user_mileage' => Yii::t('app','Your Mileage'),
			'last_update' => Yii::t('app','Last Update'),
			'edit_current' => Yii::t('app','Change current'),
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
		$criteria->compare('user_period',$this->user_period);
		$criteria->compare('user_mileage',$this->user_mileage);
		$criteria->compare('last_update',$this->last_update,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function getType(){
		return CronType::model()->findByPk($this->type)->name;
	}
	
	public function checkCron()
	{
		$seconds_in_month = 2592000;
		$seconds_in_week = 604800;
		$car = UserCar::model()->findByPk($this->car_id);
		$mileage_expired = ($car->mileage - $this->mileage) > ($this->user_mileage * 0.9);
		$time_expired = (time() - strtotime($this->last_update)) > ($this->user_period * $seconds_in_month - $seconds_in_week);
		if( $mileage_expired || $time_expired ){
			$user = User::model()->findByPk($this->uid);

			$lng = Yii::app()->language;
			if($user->locale)
				Yii::app()->language = $user->locale;

			$message = new YiiMailMessage;
			$message->setBody(Yii::t('app','<h4>Hello, {username}</h4>
						<p>We have to remind you about <b>{cron_name}</b></p>
						<p>It expires in: {expire_value} {expire_type}.</p>
						<br />
						<p>You can update it by following this <a href="{cron_url}">link</a>.</p>
						<br />
						<p>You received this letter because you are registered on <a target="_blank" href="{siteurl}"><b>{sitename}</b></a></p>', array(
							'{username}'=>$user->name,
							'{cron_name}'=>$this->getType(),
							'{expire_value}'=>( $mileage_expired ? 
												($this->mileage + $this->user_mileage - $car->mileage) :
												floor((time() - strtotime($this->last_update) - $this->user_period * $seconds_in_month)/86400) ),
							'{expire_type}'=>($mileage_expired ? $car->getMileageType() : Yii::t('app', 'days') ),
							'{cron_url}'=>Yii::app()->createAbsoluteUrl('cron/update', array('id'=>$this->id)),
							'{sitename}'=>Yii::app()->name,
							'{siteurl}'=>Yii::app()->createAbsoluteUrl('site/index'),
						)), 'text/html');
			$message->subject = Yii::t('app','Reminder: {name}', array('{name}'=>$this->getType()));
			$message->addTo($user->email);
			$message->from = Yii::app()->params['noreplyEmail'];
			Yii::app()->mail->send($message);

			Yii::app()->language = $lng;
		}
	}
}