<?php

/**
 * This is the model class for table "user".
 *
 * The followings are the available columns in table 'user':
 * @property double $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $last_login
 * @property integer $active
 * @property integer $role
 * @property integer $location
 */
class User extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return User the static model class
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
		return 'user';
	}

	public $password_repeat;
	public $agreeterms;
	
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('active, role, ispremium', 'numerical', 'integerOnly'=>true),
			array('name, email, password', 'required', 'on'=>'register'),
			array('name, email, location, coordinates, locale, custom_style', 'length', 'max'=>255),
			array('verification_key', 'length', 'max'=>20),
			array('email', 'unique', 'message'=>Yii::t('app','This email is already in use.')),
			array('agreeterms', 'required', 'on'=>'register'),
			array('password', 'length', 'min'=>6),
			array('password', 'length', 'max'=>20, 'on'=>'register'),
			array('password_repeat', 'compare', 'on'=>'register, profile', 'message'=>Yii::t('app','Passwords should match'),
						'allowEmpty'=>false, 'compareAttribute'=>'password', 'strict'=>true),
			array('last_login', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, email, password, password_repeat, last_login, active, role, ispremium, custom_style, location, locale, coordinates, agreeterms', 'safe', 'on'=>'search'),
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
			'name' => Yii::t('app', 'Name'),
			'email' => Yii::t('app', 'Email'),
			'password' => Yii::t('app', 'Password'),
			'password_repeat' => Yii::t('app', 'Password repeat'),
			'agreeterms' => Yii::t('app', 'Agree with terms.'),
			'last_login' => Yii::t('app', 'Last Login'),
			'active' => Yii::t('app', 'Active'),
			'role' => Yii::t('app', 'I\'m a seller'),
			'location' => Yii::t('app', 'Location'),
			'locale' => Yii::t('app', 'Language'),
			'coordinates' => Yii::t('app', 'Coordinates'),
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
		$criteria->compare('email',$this->email,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('last_login',$this->last_login,true);
		$criteria->compare('active',$this->active);
		$criteria->compare('role',$this->role);
		$criteria->compare('location',$this->location);
		$criteria->compare('coordinates',$this->coordinates);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function isAdmin(){
		return $this->name == 'admin';
	}
	
	public function beforeSave()
	{
	 	if(in_array(Yii::app()->controller->action->id,array('password','register','passRecovery','profile')))
		 	if($this->password !== User::model()->findByPk($this->id)->password)
		 		$this->password = $this->hashPassword($this->password);
	 	return true;
	}

	public function hashPassword($password)
	{
	    return md5($password);
	}

	public function validatePassword($password)
	{
	    // Yii password hash check.
	    // Legacy password hash check.
	    $hash = md5($password);
	    $valid = $hash === $this->password;
	
	    if ($valid) 
	    	return true;
		else
			return false;
	}
	
	public function getCars(){
		$cars = UserCar::model()->findAll('uid=?', array($this->id));
		return $cars;
	}
	
	public function isPremium(){
		return $this->ispremium;
	}

	public function isSeller(){
		return $this->role == 1;
	}
	
	public function keygen($length=10)
	{
		$key = '';
		list($usec, $sec) = explode(' ', microtime());
		mt_srand((float) $sec + ((float) $usec * 100000));
		
		$inputs = array_merge(range('z','a'),range(0,9),range('A','Z'));

		for($i=0; $i<$length; $i++)
		{
			$key .= $inputs{mt_rand(0,61)};
		}
		return $key;
	}
	
	public function checkKey() {
		$key = User::model()->findByPk($this->id)->verification_key;
		return $key == $this->verification_key;
	}
	
	public function checkUser(){
		$payments = Payment::model()->findAll(array(
			'condition'=>'uid=:uid',
			'params'=>array(':uid'=>$this->id),
			'order'=>'time DESC',
		));
		if(!empty($payments)){
			foreach($payments as $payment){
				$period = $payment->type * 2628000;
				if($payment->type == 0){
					$this->ispremium = 1;
					$this->update();
					$return = true;
				}else{
					if( ($payment->time + $period) < time() ){
						$this->ispremium = 0;
						$this->update();
						$return = false;
					}else{
						$this->ispremium = 1;
						$this->update();
						$return = false;
					}
					$last_payment = $payment;
				}
			}
			$lng = Yii::app()->language;
			if($this->locale)
				Yii::app()->language = $this->locale;
			if(isset($last_payment)){
				$message = new YiiMailMessage;
				$time_left = time() - $last_payment->time - ($payment->type * 2628000);
				if( $time_left > 0 ){
					$message->setBody(Yii::t('app','<h4>Hello, {username}</h4>
								<p>Your premium subscription expires in <b>{time_left} days</b></p>
								<p>After that you won\'t be able to access some feature like managing multiple cars and custom reminders, etc.</p>
								<br />
								<p>You can update it by following this <a href="{url}">link</a>.</p>
								<br />
								<p>You received this letter because you are registered on <a target="_blank" href="{siteurl}"><b>{sitename}</b></a></p>', array(
									'{username}'=>$this->name,
									'{time_left}'=>round($time_left/86400),
									'{url}'=>Yii::app()->createAbsoluteUrl('site/getPremium'),
									'{sitename}'=>Yii::app()->name,
									'{siteurl}'=>Yii::app()->createAbsoluteUrl('site/index'),
								)), 'text/html');
					$message->subject = Yii::t('app','Premium subscription expiration reminder');
					$message->addTo($this->email);
					$message->from = Yii::app()->params['noreplyEmail'];
					Yii::app()->mail->send($message);
				}elseif(abs($time_left) < 2628000){
					$message->setBody(Yii::t('app','<h4>Hello, {username}</h4>
								<p>Your premium subscription has been expired <b>{time_left} days ago</b></p>
								<p>Without it you are not able to access some feature like managing multiple cars and custom reminders, etc.</p>
								<br />
								<p>You can update it by following this <a href="{url}">link</a>.</p>
								<br />
								<p>You received this letter because you are registered on <a target="_blank" href="{siteurl}"><b>{sitename}</b></a></p>', array(
									'{username}'=>$this->name,
									'{time_left}'=>round(abs($time_left)/86400),
									'{url}'=>Yii::app()->createAbsoluteUrl('site/getPremium'),
									'{sitename}'=>Yii::app()->name,
									'{siteurl}'=>Yii::app()->createAbsoluteUrl('site/index'),
								)), 'text/html');
					$message->subject = Yii::t('app','Premium subscription expiration reminder');
					$message->addTo($this->email);
					$message->from = Yii::app()->params['noreplyEmail'];
					Yii::app()->mail->send($message);
				}
			}
			Yii::app()->language = $lng;
			return $return;
		}else{
			if($this->name !== 'admin'){
				$this->ispremium = 0;
				$this->update();
				return false;
			}
		}
	}
	
	public function applyGoodie($id){
		$goodie = Goodie::model()->findByPK($id);
		if(!empty($goodie))
			switch($goodie->code){
				case 'custom_style' : 
					$this->custom_style = $goodie->value;
					$this->update();
					break;
			
			}
	}

}