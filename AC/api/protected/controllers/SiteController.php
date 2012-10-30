<?php

class SiteController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','login','register','passRecovery','page','contact','getList','terms','payment','oAuthLogin','fbLogin'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('logout','getPremium'),
				'users'=>array('@'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('admin'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
	
	public function actionAdmin(){
		$this->render('admin');
	}

	public function actionPayment(){

	}
	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionCheckCrons(){
		$cron_check_offset = Settings::model()->find('name=?', array('cron_check_offset'));
		if(empty($cron_check_offset)){
			$cron_check_offset = new Settings;
			$cron_check_offset->name = 'cron_check_offset';
			$cron_check_offset->value = 0;
			$cron_check_offset->save();
		}
		$crons = Cron::model()->findAll(array(
//			'condition'=>'id > :last_id',
			'order'=>'id ASC',
			'limit'=>100,
			'offset'=>$cron_check_offset->value,
		));
		if(!empty($crons)){
			$cron_check_offset->value = count($crons);
			$cron_check_offset->update();
			foreach($crons as $cron){
				$cron->checkCron();
			}
			return false;
		}else{
			$cron_check_offset->value = 0;
			$cron_check_offset->update();
			return true;
		}
	}
	
	public function actionCheckUsers(){
		$users_check_offset = Settings::model()->find('name=?', array('users_check_offset'));
		if(empty($users_check_offset)){
			$users_check_offset = new Settings;
			$users_check_offset->name = 'users_check_offset';
			$users_check_offset->value = 0;
			$users_check_offset->save();
		}
		$users = User::model()->findAll(array(
//			'condition'=>'id > :last_id',
			'order'=>'id ASC',
			'limit'=>100,
			'offset'=>$users_check_offset->value,
		));
		if(!empty($users)){
			$users_check_offset->value = count($users);
			$users_check_offset->update();
			foreach($users as $user){
				$user->checkUser();
			}
			return false;
		}else{
			$users_check_offset->value = 0;
			$users_check_offset->update();
			return true;
		}
	}

	public function actionCheckCars(){
		$cars_check_offset = Settings::model()->find('name=?', array('cars_check_offset'));
		if(empty($cars_check_offset)){
			$cars_check_offset = new Settings;
			$cars_check_offset->name = 'cars_check_offset';
			$cars_check_offset->value = 0;
			$cars_check_offset->save();
		}
		$cars = UserCar::model()->findAll(array(
//			'condition'=>'id > :last_id',
			'order'=>'id ASC',
			'limit'=>100,
			'offset'=>$cars_check_offset->value,
		));
		if(!empty($cars)){
			$cars_check_offset->value = count($cars);
			$cars_check_offset->update();
			foreach($cars as $car){
				$car->checkCar();
			}
			return false;
		}else{
			$cars_check_offset->value = 0;
			$cars_check_offset->update();
			return true;
		}
	}

	public function actionCronExec(){
		$last_cron_check = Settings::model()->find('name=?', array('last_cron_check'));
		if(empty($last_cron_check)){
			$last_cron_check = new Settings;
			$last_cron_check->name = 'last_cron_check';
			$last_cron_check->value = 0;
			$last_cron_check->save();
		}
		if( (time() - $last_cron_check->value)>86400 ){
			if($this->actionCheckCrons()){
				$last_cron_check->value = time();
				$last_cron_check->update();
			}
		}
		
		$last_users_check = Settings::model()->find('name=?', array('last_users_check'));
		if(empty($last_users_check)){
			$last_users_check = new Settings;
			$last_users_check->name = 'last_users_check';
			$last_users_check->value = 0;
			$last_users_check->save();
		}
		if( (time() - $last_users_check->value)>86400 ){
			if($this->actionCheckUsers()){
				$last_users_check->value = time();
				$last_users_check->update();
			}
		}
		
		$last_cars_check = Settings::model()->find('name=?', array('last_cars_check'));
		if(empty($last_cars_check)){
			$last_cars_check = new Settings;
			$last_cars_check->name = 'last_cars_check';
			$last_cars_check->value = 0;
			$last_cars_check->save();
		}
		if( (time() - $last_cars_check->value)>86400 ){
			if($this->actionCheckCars()){
				$last_cars_check->value = time();
				$last_cars_check->update();
			}
		}
		
	}
	
	public function actionIndex()
	{
	
		if(isset($_REQUEST['code'])){
			$this->actionFbLogin();
		}
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
//		$userCars = '';
		if(!Yii::app()->user->isGuest){
			$user = User::model()->findByPk(Yii::app()->user->getId());
/*
			$userCars=new CActiveDataProvider('UserCar', array(
			  'criteria'=>array(
				'condition'=>'uid='.Yii::app()->user->getId(),
			  ),
			));
			if($user->isSeller()){
				$userShops=new CActiveDataProvider('Shop', array(
				  'criteria'=>array(
					'condition'=>'uid='.Yii::app()->user->getId(),
				  ),
				));
			}
*/
		}
		$this->render('index',array(
//			'userCars'=>$userCars,
//			'userShops'=>$userShops,
			'user'=>$user,
		));

		//Check Crons
		$this->actionCronExec();
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
	    if($error=Yii::app()->errorHandler->error)
	    {
	    	if(Yii::app()->request->isAjaxRequest)
	    		echo $error['message'];
	    	else
	        	$this->render('error', $error);
	    }
	}
	
	public function actionGetPremium()
	{
		$coupon = new Coupon;
		$user = User::model()->findByPk(Yii::app()->user->getId());
		
		if(isset($_POST['Coupon'])){
			$coupon->attributes = $_POST['Coupon'];
			$coupon = Coupon::model()->find('code=:code AND used=0', array(':code'=>$coupon->code));
			if(!empty($coupon)){
				if($coupon->username && $coupon->username !== $user->name){
					Yii::app()->user->setFlash('error',Yii::t('app', 'Wrong promo-code.'));
				}else{
					if( $coupon->expired && ( strtotime($coupon->expired) < time() ) ){
						$coupon->used = 1;
						$coupon->update();
						Yii::app()->user->setFlash('error',Yii::t('app', 'This promo-code has been already expired.'));
					}else{
						$pay = new Payment;
						$pay->uid = $user->id;
						$pay->time = time();
						$pay->type = $coupon->type;
						if($pay->save()){
							$user->ispremium = 1;
							$user->update();
							if($coupon->goodie)
								$user->applyGoodie($coupon->goodie);
							
							$coupon->used = 1;
							$coupon->update();
						}
						Yii::app()->user->setFlash('success',Yii::t('app', 'Welcome to Premium.'));
						$this->redirect(array('site/index'));
					}
				}
			}else{
				Yii::app()->user->setFlash('error',Yii::t('app', 'Wrong promo-code.'));
			}
		}
		
		$this->render('getPremium', array(
			'user'=>User::model()->findByPk(Yii::app()->user->getId()),
			'coupon'=>$coupon,
		));
	}

	/**
	 * Displays the contact page
	 */
	public function actionContact()
	{
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
				$headers="From: {$model->email}\r\nReply-To: {$model->email}";
				mail(Yii::app()->params['adminEmail'],$model->subject,$model->body,$headers);
				Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
				$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model));
	}
	
	public function actionGetList()
	{
		switch($_GET['table']){
			case 'car_make': {
				if($_GET['value']==0){
					$table = 'car_make';
					$model = CarMake::model();
					$prompt = Yii::t('app','Choose make');
				}else{
					$table = 'car_model';
					$model = CarModel::model();
					$parent_field = 'manufacturer_id';
					$prompt = Yii::t('app','Choose model');
				}
				break;
			}
			case 'car_model': {
				$table = 'car_model_version';
				$model = CarModelVersion::model();
				$parent_field = 'model_id';
				$prompt = Yii::t('app','Choose Version');
				break;
			}
			case 'car_model_version': {
				$table = 'car_model_variant';
				$model = CarModelVariant::model();
				$parent_field = 'version_id';
				$prompt = Yii::t('app','Choose Variant');
				$car_version = $_GET['value'];
				break;
			}
			case 'car_model_variant': {
				$car_variant = $_GET['value'];
				break;
			}
		}
		if(isset($car_variant)){
			echo CHtml::hiddenField('car_variant', $car_variant);
			echo CHtml::hiddenField('car_image', CarModelVersionImage::model()->find('car_model_version_id=:car_model_version_id', array(':car_model_version_id'=>CarModelVariant::model()->findByPk($_GET['value'])->version_id))->url);
		}else{
			if(isset($parent_field))
				$list = $model->findAll($parent_field.'=:id ORDER BY name ASC', array(':id'=>$_GET['value']));
			else
				$list = $model->findAll();
			if(!empty($list)){
				$list = CHtml::listData($list, 'id', 'name');
				$list = CHtml::dropDownList($table,'',$list,array(
					'prompt'=>$prompt, 
					'data-native-menu'=>'true', 
					'data-theme'=>'c',
					'class'=>'ui-select select-car',
					'actionUrl'=>Yii::app()->createUrl('site/getList'),
				));
				echo $list;
			}else{
				echo CHtml::hiddenField('car_version', $car_version);
				echo CHtml::hiddenField('car_image', CarModelVersionImage::model()->find('car_model_version_id=:car_model_version_id', array(':car_model_version_id'=>$car_version))->url);
			}
		}
	}

	public function actionRegister()
	{
		$user = new User('register');
		if(isset($_GET['seller'])){
				$user->role = 1;
		}

		if(isset($_GET['newcar'])){
			unset(Yii::app()->request->cookies['car_version']);
			unset(Yii::app()->request->cookies['car_variant']);
			$this->redirect(array('register'));
		}
		if(isset($_POST['car_version']))
			Yii::app()->request->cookies['car_version'] = new CHttpCookie('car_version', $_POST['car_version']);
		if(isset($_POST['car_variant']))
			Yii::app()->request->cookies['car_variant'] = new CHttpCookie('car_variant', $_POST['car_variant']);
			
		if(!$_POST['User']['role'] && !isset(Yii::app()->request->cookies['car_version']) && !isset(Yii::app()->request->cookies['car_variant'])){
			$car_make = CarMake::model()->findAll('`primary`=1');
			$car_make_list = CHtml::listData($car_make, 'id', 'name');
			$car_make_list[0] = Yii::t('app', 'Show All');
			ksort($car_make_list);
		}else{
			if(isset(Yii::app()->request->cookies['car_variant'])){
				$car_variant = CarModelVariant::model()->findByPk(Yii::app()->request->cookies['car_variant']->value);
				Yii::app()->request->cookies['car_version'] = new CHttpCookie('car_version', $car_variant->version_id);
			}
			$car_version = CarModelVersion::model()->findByPk(Yii::app()->request->cookies['car_version']->value);
			
			if($_POST['User']['role']){
				$user->role = 1;
			}
			if(isset($_POST['User']) && (!isset($_POST['no_validation'])))
			{
				$user->attributes=$_POST['User'];
				$pr = $user->password_repeat;
				if($user->name == 'admin'){
					$user->addError('','Username has been already taken.');
				}else
				if($user->validate()){
					$user->locale = Yii::app()->language;
					$user->save();
					
					//Saving user chosen car
					if(!empty($car_version)){
						$userCar = new UserCar;
						$userCar->uid = $user->primaryKey;
						$userCar->car_id = $car_version->id;
						$userCar->car_variant = $car_variant->id;
						$userCar->name = $car_version->name.' - '.$car_variant->name;
						$userCar->date_added = new CDbExpression('NOW()');
						$userCar->save();
						//Clear cookies
						unset(Yii::app()->request->cookies['car_version']);
						unset(Yii::app()->request->cookies['car_variant']);
					}
				
					$message = new YiiMailMessage;
					$message->setBody(Yii::t('app','Thank you!<br />You have been successfully registered on {sitename}', array('{sitename}'=>Yii::app()->name)), 'text/html');
					$message->subject = Yii::t('app','Registration confirmation');
					$message->addTo($user->email);
					$message->from = Yii::app()->params['noreplyEmail'];
					Yii::app()->mail->send($message);
					//Redirecting to successfull registration page with instructions:

					$identity=new UserIdentity($user->email,'');
					$identity->_id = $user->id;
					$identity->errorCode=UserIdentity::ERROR_NONE;
					// to logout admin, if logged in:
					if (!Yii::app()->user->isGuest)
						Yii::app()->user->logout();
					Yii::app()->user->login($identity,0);
					Yii::app()->user->name = $user->name;

					Yii::app()->user->setFlash('success', Yii::t('app','Registration completed.'));
					$this->redirect(array('index'));
				}
				$user->password_repeat = $pr;
			}
		}
		$this->render('register',array(
			'car_make'=>$car_make_list,
			'user'=>$user,
			'car_version'=>$car_version,
			'car_variant'=>$car_variant,
		));
	}
	
	public function actionPassRecovery(){
		$user = new User;
		if(isset($_POST['User'])){
			$user->attributes = $_POST['User'];
			$pr = $user->password_repeat;
			$user = User::model()->find('email=?', array($user->email));
			if(!empty($user)){
				$user->attributes = $_POST['User'];
				$user->password_repeat = $_POST['User']['password_repeat'];
				if(!isset($_POST['User']['verification_key'])){
					$user->verification_key = $user->keygen(20);
					$user->update();
					$message = new YiiMailMessage;
					$message->setBody(Yii::t('app','<h4>Hello, {username}</h4>
								<p>Someone asked for password reset on {sitename}.</p>
								<p>If it was you, please follow this <a href="{url}">link</a> or use following verification key on password reset page:</p>
								<p><b>{key}</b></p>
								<br />
								<p>You received this letter because you are registered on <a target="_blank" href="{siteurl}"><b>{sitename}</b></a></p>', array(
									'{username}'=>$user->name,
									'{key}'=>$user->verification_key,
									'{url}'=>Yii::app()->createAbsoluteUrl('site/passRecovery', array('key'=>$user->verification_key, 'email'=>$user->email)),
									'{sitename}'=>Yii::app()->name,
									'{siteurl}'=>Yii::app()->createAbsoluteUrl('site/index'),
								)), 'text/html');
					$message->subject = Yii::t('app','Password reset key');
					$message->addTo($user->email);
					$message->from = Yii::app()->params['noreplyEmail'];
					Yii::app()->mail->send($message);
					$_GET['key'] = '';
					Yii::app()->user->setFlash('message', Yii::t('app','Verification key was sent, please check you email.'));
					$this->redirect(array('passRecovery', 'email'=>$user->email, 'key'=>''));
				}else{
					if(!$user->checkKey()){
						Yii::app()->user->setFlash('error', Yii::t('app','Key is wrong.'));
					}else{
						$user->verification_key = "";
						if( ($user->password_repeat==$user->password) && $user->validate() ){
							$user->save();
							Yii::app()->user->setFlash('success', Yii::t('app','Password successfully changed.'.$user->password));
							$this->redirect(array('login'));
						}else{
							$user->addError('','Password should match'.$user->password_repeat.'-'.$user->password);
							$user->password_repeat = $pr;
						}
					}
				}
				
			}else{
				Yii::app()->user->setFlash('error', Yii::t('app','User with such email address is not registered.'));
				$user = new User;
			}
		}
		if(isset($_GET['key'])){
			$user->verification_key = $_GET['key'];
		}
		if(isset($_POST['User']['verification_key'])){
			$user->verification_key = $_POST['User']['verification_key'];
		}
		if(isset($_GET['email'])){
			$user->email = $_GET['email'];
		}
		$this->render('passrecovery',array(
			'user'=>$user,
			'enterkey'=>(isset($_GET['key']) || isset($_POST['User']['verification_key'])),
		));
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$model=new LoginForm;

		// if it is ajax validation request
//		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form'){
			$model->attributes=$_POST;
			if($model->validate() && $model->login()){
				$sessionKey = sha1(mt_rand());
			    $return['sessionKey']=$sessionKey;
			    $return['uid']=Yii::app()->user->getId();
			    $return['post']=$_POST;
			    $this->_sendResponse(200, CJSON::encode($return));
			}else{
				$this->_sendResponse(500, 'Wrong email or password');
			}
			Yii::app()->end();
//		}

/*
		// collect user input data
		if(isset($_POST['LoginForm'])){
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login()){
				header("Access-Control-Allow-Origin: *");
				header("Access-Control-Allow-Headers: X-Requested-With");
				header("Access-Control-Request-Method: GET,POST"); 
				header('Content-Type: application/json');
				$sessionKey = sha1(mt_rand());
			    $return['response']=$sessionKey;
			    $return['post']=$_POST;

			    $json = json_encode($return);

			    echo isset($_GET['callback'])
			    ? "{$_GET['callback']}($json)"
			    : $json;

				//$this->redirect(Yii::app()->user->returnUrl);
			}
		}
*/
/*		
		//Google OAuth url
		require_once 'protected/apis/google/apiClient.php';
		require_once 'protected/apis/google/contrib/apiPlusService.php';
		session_start();
		$client = new apiClient();
		$client->setApplicationName("Autocron");
		$client->setScopes(array(
//			'https://www.googleapis.com/auth/plus.me',
			'https://www.googleapis.com/auth/userinfo.profile',
			'https://www.googleapis.com/auth/userinfo.email',
		));
		
		//Twitter OAuth url
		$twiconf = Yii::app()->params['twitter'];
		require_once('protected/apis/twitteroauth/twitteroauth/twitteroauth.php');
		$connection = new TwitterOAuth($twiconf['consumer_key'], $twiconf['consumer_secret']);
		$temporary_credentials = $connection->getRequestToken();
		$twitter_url = $connection->getAuthorizeURL($temporary_credentials);
		
		// display the login form
		$this->render('login',array(
			'model'=>$model,
			'oAuthUrl'=>$client->createAuthUrl(),
			'twitter_url'=>$twitter_url,
		));
*/
	}
	
	public function actionFbLogin(){
	   $app_id = Yii::app()->params['facebook']['app_id'];
	   $app_secret = Yii::app()->params['facebook']['app_secret'];
//	   $my_url = 'http://autocron.ru/index.php?r=site/fblogin';
	   $my_url = Yii::app()->createAbsoluteUrl('site/fblogin');

	   session_start();
	   $code = $_REQUEST["code"];
	   if(empty($code)) {
		 $_SESSION['state'] = md5(uniqid(rand(), TRUE)); //CSRF protection
		 $dialog_url = "http://www.facebook.com/dialog/oauth?client_id=" 
		   . $app_id . "&redirect_uri=" . $my_url . "&state="
		   . $_SESSION['state'];

		 echo("<script> top.location.href='" . $dialog_url . "'</script>");
	   }

		if( $_REQUEST['state'] == $_SESSION['state'] ) {
			$token_url = "https://graph.facebook.com/oauth/access_token?";
			$opts = array(
				"client_id"=>$app_id,
				"redirect_uri"=>'http://autocron.ru/index.php',
				"client_secret"=>$app_secret,
				"code"=>$code,
			);
			$token_url .= http_build_query($opts,'','&');
//			echo $token_url;
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $token_url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//			curl_setopt($ch, CURLOPT_REFERER, $my_url);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			$response = curl_exec($ch);
			curl_close($ch);
//		 $response = file_get_contents($token_url);
			$params = null;
			parse_str($response, $params);

			if(!isset($params['access_token'])){
				echo $response;
				echo '<br />'.$_REQUEST["code"];
			}else{
			 
				 $graph_url = "https://graph.facebook.com/me?access_token=" 
				   . $params['access_token'];

				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $graph_url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				$response = curl_exec($ch);
				curl_close($ch);
//				echo $response;
				$user = json_decode($response);
				$user = User::model()->find('email=?',array($user->email));
				if(!empty($user)){
					$identity=new UserIdentity($user->email,'');
					$identity->_id = $user->id;
					$identity->errorCode=UserIdentity::ERROR_NONE;
					// to logout admin, if logged in:
					if (!Yii::app()->user->isGuest)
						Yii::app()->user->logout();
					Yii::app()->user->login($identity,0);
					Yii::app()->user->name = $user->name;
					$user->last_login = new CDbExpression('NOW()');
					$user->update();
					unset($_SESSION['access_token']);
					$this->redirect(array('index'));
				}else
					$this->redirect(array('register', 'email'=>$user->email, 'name'=>$user->name));
				$this->redirect(array('index'));
			}
	   }
	   else {
//			echo Yii::t('app', "The state does not match. You may be a victim of CSRF.");
			Yii::app()->user->setFlash('error', Yii::t('app', "The state does not match. You may be a victim of CSRF."));
			$this->redirect(array('index'));
			unset($_SESSION['state']);
	   }
		
	}
	
	public function actionOAuthLogin(){
		require_once 'protected/apis/google/apiClient.php';
		require_once 'protected/apis/google/contrib/apiPlusService.php';
		require_once 'protected/apis/google/contrib/apiOauth2Service.php';
		session_start();
		$client = new apiClient();
		$client->setApplicationName("Autocron");
		$client->setScopes(array(
			'https://www.googleapis.com/auth/plus.me',
			'https://www.googleapis.com/auth/userinfo.profile',
			'https://www.googleapis.com/auth/userinfo.email',
		));
		$plus = new apiPlusService($client);
		$oauth2Service = new apiOauth2Service($client);

		if (isset($_REQUEST['logout']))
		{
			unset($_SESSION['access_token']);
		}

		if (isset($_SESSION['access_token']))
		{
			$client->setAccessToken($_SESSION['access_token']);
		} else {
			$client->setAccessToken($client->authenticate());
		}

		$_SESSION['access_token'] = $client->getAccessToken();
		
		if (isset($_GET['code'])) {
			$this->redirect(array('oauthlogin'));
		}

		if ($client->getAccessToken())
		{
			$me = $plus->people->get('me');
			$userinfo = $oauth2Service->userinfo->get();
			$_SESSION['access_token'] = $client->getAccessToken();
		}
		else{
			$this->redirect(array('login'));
		}
		if(isset($userinfo))
		{
			$user = User::model()->find('email=?',array($userinfo['email']));
			if(!empty($user)){
				$identity=new UserIdentity($user->email,'');
				$identity->_id = $user->id;
				$identity->errorCode=UserIdentity::ERROR_NONE;
				// to logout admin, if logged in:
				if (!Yii::app()->user->isGuest)
					Yii::app()->user->logout();
				Yii::app()->user->login($identity,0);
				Yii::app()->user->name = $user->name;
				$user->last_login = new CDbExpression('NOW()');
				$user->update();
				unset($_SESSION['access_token']);
				$this->redirect(array('index'));
			}else
				$this->redirect(array('register', 'email'=>$userinfo['email'], 'name'=>$userinfo['name']));
		}
		if(isset($me))
		{
			$_SESSION['gplusdata']=$me;
		}
	}
	

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		if(isset($_SESSION['access_token']))
			unset($_SESSION['access_token']);
			
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}


	private function _sendResponse($status = 200, $body = '', $content_type = 'application/json')
	{
	    // set the status
	    $status_header = 'HTTP/1.1 ' . $status . ' ' . $this->_getStatusCodeMessage($status);
	    header($status_header);
	    // and the content type
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Headers: X-Requested-With");
		header("Access-Control-Request-Method: GET,POST"); 

	    header('Content-type: ' . $content_type);
	 
	    // pages with body are easy
	    if($body != '')
	    {
	        // send the body
	        echo $body;
	    }
	    // we need to create the body if none is passed
	    else
	    {
	        // create some body messages
	        $message = '';
	 
	        // this is purely optional, but makes the pages a little nicer to read
	        // for your users.  Since you won't likely send a lot of different status codes,
	        // this also shouldn't be too ponderous to maintain
	        switch($status)
	        {
	            case 401:
	                $message = 'You must be authorized to view this page.';
	                break;
	            case 404:
	                $message = 'The requested URL ' . $_SERVER['REQUEST_URI'] . ' was not found.';
	                break;
	            case 500:
	                $message = 'The server encountered an error processing your request.';
	                break;
	            case 501:
	                $message = 'The requested method is not implemented.';
	                break;
	        }
	 
	        // servers don't always have a signature turned on 
	        // (this is an apache directive "ServerSignature On")
	        $signature = ($_SERVER['SERVER_SIGNATURE'] == '') ? $_SERVER['SERVER_SOFTWARE'] . ' Server at ' . $_SERVER['SERVER_NAME'] . ' Port ' . $_SERVER['SERVER_PORT'] : $_SERVER['SERVER_SIGNATURE'];
	 
	        // this should be templated in a real-world solution
	        $body = '
	<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
	<html>
	<head>
	    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	    <title>' . $status . ' ' . $this->_getStatusCodeMessage($status) . '</title>
	</head>
	<body>
	    <h1>' . $this->_getStatusCodeMessage($status) . '</h1>
	    <p>' . $message . '</p>
	    <hr />
	    <address>' . $signature . '</address>
	</body>
	</html>';
	 
	        echo $body;
	    }
	    Yii::app()->end();
	}

	private function _getStatusCodeMessage($status)
	{
	    // these could be stored in a .ini file and loaded
	    // via parse_ini_file()... however, this will suffice
	    // for an example
	    $codes = Array(
	        200 => 'OK',
	        400 => 'Bad Request',
	        401 => 'Unauthorized',
	        402 => 'Payment Required',
	        403 => 'Forbidden',
	        404 => 'Not Found',
	        500 => 'Internal Server Error',
	        501 => 'Not Implemented',
	    );
	    return (isset($codes[$status])) ? $codes[$status] : '';
	}

}