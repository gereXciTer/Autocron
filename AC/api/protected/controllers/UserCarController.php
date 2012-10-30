<?php

class UserCarController extends Controller
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
				'actions'=>array('index','view','create','update','delete','viewimage','updateimage'),
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

		if($model->uid !== Yii::app()->user->getId() && Yii::app()->user->name !== 'admin'){
			$this->redirect(array('site/index'));
		}

		if($model->uid == Yii::app()->user->getId()){
			$expenses = Expense::model()->findAll(array(
				'condition'=>'car_id=:cid',
				'params'=>array(':cid'=>$id),
				'limit'=>5,
				'order'=>'time DESC',
			));			
			$crons = Cron::model()->findAll('car_id=?', array($id));			
			$history = CronHistory::model()->count('uid=:uid AND car_id=:cid', array(':uid'=>Yii::app()->user->getId(), ':cid'=>$id));
		}
		$this->render('view',array(
			'model'=>$model,
			'crons'=>$crons,
			'history'=>$history,
			'expenses'=>$expenses,
		));
	}

	public function actionViewimage()
	{
		$model = UserCar::model()->findByPk($_GET['id']);

//		if($model->uid !== Yii::app()->user->getId())
//			$this->redirect(array('site/index'));

		$this->render('viewimage',array(
			'model'=>$model,
		));
	}

	public function actionUpdateimage()
	{
		$model = UserCar::model()->findByPk($_GET['id']);

		if($model->uid !== Yii::app()->user->getId())
			$this->redirect(array('site/index'));

		if(isset($_POST['UserCar']))
		{
			if($model->image)
				$old_image = $model->image;

			$model->attributes=$_POST['UserCar'];
			$model->image=CUploadedFile::getInstance($model,'image');
            if($model->image->size > 0 && $model->validate()){
				$baseUrl = str_replace('/protected','/',Yii::app()->basePath);
				if(isset($old_image)){
					@unlink($baseUrl.Yii::app()->params['carsUserImages'].$old_image);
					@unlink($baseUrl.Yii::app()->params['carsUserImages'].'tn/'.$old_image);
				}
				$tmpname = time().preg_replace('/[^a-z0-9_.]/i', '', strtolower($model->image->name));
//				$tmpname = $model->image->name;
                $model->image->saveAs(Yii::app()->params['carsUserImages'].$tmpname);
				$model->image = $tmpname;
				//saving thumbnail
				$image = new SimpleImage();
				$image->load($baseUrl.Yii::app()->params['carsUserImages'].$tmpname);
				$image->resizeToWidth(250);
				$image->save($baseUrl.Yii::app()->params['carsUserImages'].'tn/'.$tmpname);
            }else{
				$model->image = $old_image;
			}

			if($model->save())
				$this->redirect(array('viewimage','id'=>$model->id));
		}
			
		$this->render('updateimage',array(
			'model'=>$model,
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$user = User::model()->findByPk(Yii::app()->user->getId());
		if(!$user->isPremium()){
			$this->redirect(array('site/getPremium', 'controller'=>Yii::app()->controller->id, 'action'=>Yii::app()->controller->action->id));
		}
		$model=new UserCar;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		$car_make = CarMake::model()->findAll('`primary`=1');
		$car_make_list = CHtml::listData($car_make, 'id', 'name');
		$car_make_list[0] = 'Show All';
		ksort($car_make_list);

		if(isset($_POST['car_version']) || isset($_POST['car_variant']))
		{
			if(isset($_POST['car_variant'])){
				$car_variant = CarModelVariant::model()->findByPk($_POST['car_variant']);
				$_POST['car_version'] = $car_variant->version_id;
			}
			$car_version = CarModelVersion::model()->findByPk($_POST['car_version']);
			$model = new UserCar;
			$model->uid = Yii::app()->user->getId();
			$model->car_id = $car_version->id;
			$model->car_variant = $car_variant->id;
			$model->name = $car_version->name.' - '.$car_variant->name;
			$model->date_added = new CDbExpression('NOW()');
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('create',array(
			'car_make'=>$car_make_list,
			'model'=>$model,
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

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['UserCar']))
		{
			$last_mileage = $model->mileage;
			if($model->image)
				$old_image = $model->image;

			$model->attributes=$_POST['UserCar'];

			if($model->mileage_multiplier == 0){
				$model->mileage_multiplier = 1.609344;
			}else{
				$model->mileage_multiplier = 1;
			}

			if($last_mileage !== $model->mileage)
				$model->last_update = new CDbExpression('NOW()');
			
			$model->image=CUploadedFile::getInstance($model,'image');
            if($model->image->size && $model->validate()){
				$baseUrl = str_replace('/protected','/',Yii::app()->basePath);
				if(isset($old_image)){
					@unlink($baseUrl.Yii::app()->params['carsUserImages'].$old_image);
					@unlink($baseUrl.Yii::app()->params['carsUserImages'].'tn/'.$old_image);
				}
				$tmpname = time().preg_replace('/[^a-z0-9_.]/i', '', strtolower($model->image->name));
//				$tmpname = $model->image->name;
                $model->image->saveAs(Yii::app()->params['carsUserImages'].$tmpname);
				$model->image = $tmpname;
				//saving thumbnail
				$image = new SimpleImage();
				$image->load($baseUrl.Yii::app()->params['carsUserImages'].$tmpname);
				$image->resizeToWidth(250);
				$image->save($baseUrl.Yii::app()->params['carsUserImages'].'tn/'.$tmpname);
            }else{
				$model->image = $old_image;
			}

			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
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
				$this->redirect(array('site/index'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$userCars=new CActiveDataProvider('UserCar', array(
		  'criteria'=>array(
			'condition'=>'uid='.Yii::app()->user->getId(),
		  ),
		));
		$this->render('index',array(
			'userCars'=>$userCars,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new UserCar('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['UserCar']))
			$model->attributes=$_GET['UserCar'];

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
		$model=UserCar::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='user-car-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
