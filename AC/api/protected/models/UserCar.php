<?php

/**
 * This is the model class for table "user_car".
 *
 * The followings are the available columns in table 'user_car':
 * @property double $id
 * @property double $uid
 * @property double $car_id
 * @property double $car_variant
 * @property string $name
 * @property string $date_added
 * @property integer $mileage_initial
 * @property integer $mileage
 * @property string $image
 * @property integer $year_built
 */
class UserCar extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return UserCar the static model class
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
		return 'user_car';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('mileage_initial, mileage, year_built', 'numerical', 'integerOnly'=>true),
			array('uid, car_id, car_variant, mileage_multiplier', 'numerical'),
			array('name, image, last_update', 'length', 'max'=>255),
//			array('last_update', 'default', 'value'=>new CDbExpression('NOW()'), 'setOnEmpty'=>false, 'on'=>'update'),
			array('date_added', 'safe'),
			array('image', 'file', 'types'=>'png,jpg,gif', 'allowEmpty'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, uid, car_id, car_variant, name, date_added, mileage_initial, mileage, image, year_built', 'safe', 'on'=>'search'),
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
			'uid' => Yii::t('app','User'),
			'car_id' => Yii::t('app','Car model version'),
			'car_variant' => Yii::t('app','Car model variant'),
			'name' => Yii::t('app','Car name'),
			'date_added' => Yii::t('app','Date added'),
			'mileage_multiplier' => Yii::t('app','Miles/Kilometers'),
			'mileage_initial' => Yii::t('app','Initial mileage'),
			'mileage' => Yii::t('app','Current mileage'),
			'image' => Yii::t('app','Image'),
			'year_built' => Yii::t('app','Year built'),
			'last_update'=> Yii::t('app','Last update'),
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
		$criteria->compare('car_variant',$this->car_variant);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('date_added',$this->date_added,true);
		$criteria->compare('mileage_initial',$this->mileage_initial);
		$criteria->compare('mileage',$this->mileage);
		$criteria->compare('image',$this->image,true);
		$criteria->compare('year_built',$this->year_built);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	static function getMileageTypes(){
		return array(
			0 => Yii::t('app','Kilometers'),
			1 => Yii::t('app','Miles'),
		);
	}
	static function getMileageTypesShort(){
		return array(
			0 => Yii::t('app','km'),
			1 => Yii::t('app','miles'),
		);
	}
	public function getMileageType(){
		$types = UserCar::getMileageTypesShort();
		if($this->mileage_multiplier == 1)
			return $types[1];
		else
			return $types[0];
	}
	
	public function getMileage($m = 0){
		if(!$m)
			$m = $this->mileage;
		return round($m * $this->mileage_multiplier, 0);
	}
	public function getMileageGap($m){
		$m = $m - $this->mileage;
		return (($m > 0) ? 'in '.$m : '<span style="color: red;">in '.$m.'<span>');
	}
	
	public function getMileagePassed($m){
		$m = $this->mileage - $m;
		return (($m > 0) ? $m : 0);
	}
	
	public function getReminders(){
		$r = array();
		if(!$this->mileage_initial || !$this->mileage)
			$r[] = Yii::t('app', 'Mileage is not set');
		if((time() - strtotime($this->last_update)) > 2592000)
			$r[] = Yii::t('app', 'Mileage is outdated');
		if(!$this->year_built)
			$r[] = Yii::t('app', 'Year is not set');
		$crons = Cron::model()->findAll('car_id=?', array($this->id));
		if(!empty($crons)) foreach($crons as $cron){
			if($this->mileage > ($cron->mileage + $cron->user_mileage) || 
				time() < (strtotime($cron->last_update) + strtotime($cron->user_period)))
				$r[] = Yii::t('app', '{name} is outdated', array('{name}'=>CronType::model()->findByPk($cron->type)->getName()));
		}
		return $r;
	}
	
	public function getCarInfo(){
		$car_var = CarModelVariant::model()->findByPk($this->car_variant);
		$car_version = CarModelVersion::model()->findByPk($car_var->version_id);
		$car_model = CarModel::model()->findByPk($car_var->model_id);
		$car_make = CarMake::model()->findByPk($car_model->manufacturer_id);
		return array(
			'make'=>$car_make->name,
			'model'=>$car_model->name,
			'version'=>$car_version->name,
			'variant'=>$car_var->name,
		);
	}

	public function getImageUrl(){
		if(is_file(Yii::app()->params['carsUserImages'].$this->image)){
			return Yii::app()->params['carsUserImages'].$this->image;
		}else{
			$image = CarModelVersionImage::model()->find('car_model_version_id=:car_model_version_id', array(':car_model_version_id'=>$this->car_id));
			return (!empty($image)&&is_file(Yii::app()->params['carsImages'].$image->url) ? Yii::app()->params['carsImages'].$image->url : Yii::app()->params['carsImages'].'noimage.png');
		}
	}

	public function getImageTnUrl(){
		if(is_file(Yii::app()->params['carsUserImages'].$this->image)){
			return Yii::app()->params['carsUserImages'].'tn/'.$this->image;
		}else{
			$image = CarModelVersionImage::model()->find('car_model_version_id=:car_model_version_id', array(':car_model_version_id'=>$this->car_id));
			return (!empty($image)&&is_file(Yii::app()->params['carsImages'].$image->url) ? Yii::app()->params['carsImages'].$image->url : Yii::app()->params['carsImages'].'noimage.png');
		}
	}
	
	public function checkCar(){
		$reminders = $this->getReminders();
		
		if(count($reminders)){
			$user = User::model()->findByPk($this->uid);

			$lng = Yii::app()->language;
			if($user->locale)
				Yii::app()->language = $user->locale;
			$message = new YiiMailMessage;
			$message->setBody(Yii::t('app','<h4>Hello, {username}</h4>
						<p>Your car <b>{car_name}</b> is outdated:</p>
						<ul><li>{reminders}</li></ul>
						<br />
						<p>You can update it by following this <a href="{url}">link</a>.</p>
						<br />
						<p>You received this letter because you are registered on <a target="_blank" href="{siteurl}"><b>{sitename}</b></a></p>', array(
							'{username}'=>$user->name,
							'{car_name}'=>$this->name,
							'{reminders}'=>implode('</li><li>', $reminders),
							'{url}'=>Yii::app()->createAbsoluteUrl('userCar/update', array('id'=>$this->id)),
							'{sitename}'=>Yii::app()->name,
							'{siteurl}'=>Yii::app()->createAbsoluteUrl('site/index'),
						)), 'text/html');
			$message->subject = Yii::t('app','Your car data has been expired');
			$message->addTo($user->email);
			$message->from = Yii::app()->params['noreplyEmail'];
			Yii::app()->mail->send($message);

			Yii::app()->language = $lng;
		}
	}
	
}