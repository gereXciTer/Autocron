<?php

class CronController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('index','view','create','update','delete','getDefaults','findStore','updateSearchResults','findShops'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$model = $this->loadModel($id);
		if($model->uid !== Yii::app()->user->getId())
			$this->redirect(array('site/index'));

		$this->render('view',array(
			'model'=>$model,
		));
	}
	
	public function actionGetDefaults(){
		$type = CronType::model()->findByPk(addslashes($_GET['id']));
		$userCar = UserCar::model()->findByPk(addslashes($_GET['car_id']));
		if($type->mileage_multiplier !== $userCar->mileage_multiplier){
			$type->mileage = round(($type->mileage/$type->mileage_multiplier) * $userCar->mileage_multiplier, 0);
		}
		echo $type->mileage.'|'.$type->period;
	}
	
	public function retrieveGoogleSearch($searchTerms="",$searchURL="", $start=0) {
		$searchTerms = str_replace(' ', '%20', $searchTerms);
		$searchURL = str_replace(' ', '%20', $searchURL);
		$googleBaseUrl = "http://ajax.googleapis.com/ajax/services/search/web";
		$googleBaseQuery = "?v=1.0&rsz=4&hl=".(Yii::app()->getLanguage() ? Yii::app()->getLanguage() : 'en' )."&q=";
		$start = $start ? '&start='.$start : '';
		$googleFullUrl = $googleBaseUrl . $googleBaseQuery . $searchURL . "%20" . $searchTerms . $start; 
		$curlObject = curl_init();
		curl_setopt($curlObject,CURLOPT_URL,$googleFullUrl);
		curl_setopt($curlObject,CURLOPT_RETURNTRANSFER,true);
		curl_setopt($curlObject,CURLOPT_HEADER,false);
		curl_setopt($curlObject,CURLOPT_REFERER,"http://www.b13ed.com/");
		$returnGoogleSearch = curl_exec($curlObject);
		curl_close($curlObject);
		$returnGoogleSearch = json_decode($returnGoogleSearch,true);
		return $returnGoogleSearch["responseData"];
	}

	public function actionUpdateSearchResults(){
		$setSearchURL = '';
		$searchResultsArray = $this->retrieveGoogleSearch($_GET['searchstring'],$setSearchURL,$_GET['start']);
		if(isset($searchResultsArray))
		foreach($searchResultsArray["results"] as $result){
			echo '<li><a href="'.$result['unescapedUrl'].'"><h5>'.$result['title'].' - '.$result['visibleUrl'].'</h5>';
			echo '<p>'.$result['content'].'</p>';
			echo '</a></li>';
		}
	}
	
	public function actionFindShops()
	{
		$model = Cron::model()->findByPk(addslashes($_GET['id']));
		$userCar = UserCar::model()->findByPk(addslashes($_GET['car_id']));
		$carModel = CarModel::model()->findByPk(CarModelVersion::model()->findByPk($userCar->car_id)->model_id);
		$shopdefs = ShopDefinition::model()->findAll('all_brands=1 OR cron_type_id=:cid OR brand_id=:bid', array(
													':bid'=>$carModel->manufacturer_id,
													':cid'=>$model->type,
												));
		$shops = array();
		foreach($shopdefs as $def){
			$shops[] = $def->shop_id;
		}
		$shops = Shop::model()->findAllByAttributes(array(
				'id'=>$shops,
				'country'=>$_GET['country'],
				'state'=>$_GET['state'],
			),array(
				'group'=>'id',
			)
		);
		foreach($shops as $shop){
			echo '<li>'.CHtml::link($shop->name, array('shop/view', 'id'=>$shop->id), array('data-ajax'=>'false')).'</li>';
		}
	}
	
	public function actionFindStore()
	{
		$model=$this->loadModel($_GET['id']);

		$user = User::model()->findByPk(Yii::app()->user->getId());
		
		if($user->location){
			$location = explode(',', $user->location);
			$location = $location[count($location) - 1].','.$location[count($location) - 2];
			
			$userCar = UserCar::model()->findByPk(addslashes($_GET['car_id']));
			$carInfo = $userCar->getCarInfo();
			
			$searchstring = $location.' '.$carInfo['make'].' '.$carInfo['version'].' '.$model->getType();
			
			$setSearchTerms = $searchstring;
			$setSearchURL = $location;
			$searchResultsArray = $this->retrieveGoogleSearch($setSearchTerms,$setSearchURL,$_GET['start']);
		}
		$this->render('findStore',array(
			'model'=>$model,
			'userCar'=>$userCar,
			'searchstring'=>$searchstring,
			'user'=>$user,
			'location'=>$location,
			'searchResultsArray' => $searchResultsArray,
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Cron;

		$user = User::model()->findByPk(Yii::app()->user->getId());
				
		$userCar = UserCar::model()->findByPk(addslashes($_GET['car_id']));
		
		if(!$userCar->mileage){
			Yii::app()->user->setFlash('message', Yii::t('app', 'Before adding reminders you should set car mileage.'));
			$this->redirect(array('userCar/update', 'id'=>addslashes($_GET['car_id'])));
		}

		$model->mileage = $userCar->mileage;

		if(isset($_POST['Cron']))
		{
			$model->attributes=$_POST['Cron'];
			$type = CronType::model()->findByPk($model->type);
			if($type->creator_id && ($type->creator_id !== $user->id || !$user->isPremium())){
				Yii::app()->user->setFlash('error', Yii::t('app', 'Only premium account users can add their own reminder types.'));
				$this->redirect(array('site/getPremium', 'controller'=>Yii::app()->controller->id, 'action'=>Yii::app()->controller->action->id));
			}
			if(!Cron::model()->count('type=:type AND uid=:uid AND car_id=:car_id', array(':type'=>$model->type, ':uid'=>$user->id, ':car_id'=>addslashes($_GET['car_id'])))){
				$model->car_id = addslashes($_GET['car_id']);
				$model->uid = $user->id;
				$model->last_update = date('Y-m-d H:i:s',strtotime($model->last_update));
				$model->validate();
				if($model->save()){
					if($model->value){
						$expense=new Expense('create');
						$expense->time = $model->last_update;
						$expense->uid = $model->uid;
						$expense->car_id = $model->car_id;
						$expense->category_id = 3;
						$expense->title = CronType::model()->findByPk($model->type)->name;
						$expense->value = $model->value;
						$expense->save();
					}
					$this->redirect(array('userCar/view','id'=>$_GET['car_id']));
				}
				else
					Yii::app()->user->setFlash('error', Yii::t('app', 'Error adding reminder.'));
			}else
				Yii::app()->user->setFlash('info', Yii::t('app', 'You already have such reminder type.'));
		}
		
		if($user->isPremium()){
			$types = CronType::model()->findAll('`primary`=1 OR creator_id=:crid', array(':crid'=>$user->id));
		}else{
			$types = CronType::model()->findAll('`primary`=1');
		}

		$this->render('create',array(
			'model'=>$model,
			'userCar'=>$userCar,
			'types'=>$types,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		if($model->uid !== Yii::app()->user->getId())
			$this->redirect(array('site/index'));

		$userCar = UserCar::model()->findByPk(addslashes($_GET['car_id']));
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Cron']))
		{
			if(!$_POST['Cron']['edit_current']){
				$history = new CronHistory;
				$history->attributes = $model->attributes;
				$history->save();
			}
			$model->attributes=$_POST['Cron'];
			$model->last_update = date('Y-m-d H:i:s',strtotime($model->last_update));
			if($model->save()){
				if(!$_POST['Cron']['edit_current']){
					if($model->value){
						$expense=new Expense('create');
						$expense->time = $model->last_update;
						$expense->uid = $model->uid;
						$expense->car_id = $model->car_id;
						$expense->category_id = 3;
						$expense->title = CronType::model()->findByPk($model->type)->name;
						$expense->value = $model->value;
						$expense->save();
					}
				}
				$this->redirect(array('userCar/view','id'=>$_GET['car_id']));
			}
		}else
			$model->edit_current = true;

		$types = CronType::model()->findAll('`primary`=1');
		$this->render('update',array(
			'model'=>$model,
			'userCar'=>$userCar,
			'types'=>$types,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			$model = $this->loadModel($id);
			if($model->uid == Yii::app()->user->getId())
				$model->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('userCar/index'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		if(isset($_GET['car_id'])){
			$condition = 'uid=:uid AND car_id=:cid';
			$params = array(
					':uid'=>Yii::app()->user->getId(),
					':cid'=>$_GET['car_id'],
				);
		}else{
			$condition = 'uid=:uid';
			$params = array(':uid'=>Yii::app()->user->getId());
		}

		$dataProvider=new CActiveDataProvider('Cron', array(
			'criteria'=>array(
				'condition'=>$condition,
				'params'=>$params,
			),
		));
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Cron('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Cron']))
			$model->attributes=$_GET['Cron'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Cron::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='cron-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
