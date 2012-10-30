<?php

class CronHistoryController extends Controller
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
	
	public $last_car = '';

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('index','view','update','delete'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','create'),
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
			$this->redirect(array('index'));

		if($model->uid !== Yii::app()->user->getId())
			$this->redirect(array('index'));
			
		$this->render('view',array(
			'model'=>$model,
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new CronHistory;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['CronHistory']))
		{
			$model->attributes=$_POST['CronHistory'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('create',array(
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
			$this->redirect(array('index'));

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['CronHistory']))
		{
			$model->attributes=$_POST['CronHistory'];
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
			$model = CronHistory::model()->findByPk($id);
			if(empty($model))
				$this->redirect(array('cronHistory/index'));
			$car_id = $model->car_id;
			if($model->uid == Yii::app()->user->getId())
				$model->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(array('cronHistory/index', 'car_id'=>$car_id));
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
		$criteria= new CDbCriteria(array(
				'condition'=>$condition,
				'params'=>$params,
        ));
		$crons = Cron::model()->findAll($criteria);
		$cronhistory = CronHistory::model()->findAll($criteria);
		
		$history=new CArrayDataProvider(array_merge($cronhistory, $crons), array(
			'sort'=>array(
				'attributes'=>array('last_update'),
				'defaultOrder'=>'last_update DESC',
			),
		));

		$this->render('index',array(
			'history'=>$history,
			'model'=>CronHistory::model(),
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new CronHistory('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['CronHistory']))
			$model->attributes=$_GET['CronHistory'];

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
		$model=CronHistory::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='cron-history-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
