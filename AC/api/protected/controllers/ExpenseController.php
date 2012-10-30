<?php

class ExpenseController extends Controller
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
				'actions'=>array('create','update','index','charts', 'delete'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','view'),
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
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Expense('create');

		$user = User::model()->findByPk(Yii::app()->user->getId());
		if($user->isPremium())
			$categories = ExpenseCategory::model()->findAll(array(
				'condition'=>'`primary`=1 OR uid=:uid',
				'params'=>array(':uid'=>$user->id),
				'order'=>'name ASC',
				));
		else
			$categories = ExpenseCategory::model()->findAll('`primary`=1');

		if(isset($_POST['Expense']))
		{
			$model->attributes=$_POST['Expense'];
			if(isset($_GET['car_id']))
				$model->car_id = addslashes($_GET['car_id']);
			$car = UserCar::model()->findByPk($model->car_id);
			if($car->uid !== $user->id){
				$this->redirect(array('index'));
			}
			$model->car_id = $car->id;
			$model->uid = $user->id;
			$model->time = date('Y-m-d H:i:s', strtotime($model->time));
			if($model->save())
				$this->redirect(array('index', 'car_id'=>$_GET['car_id']));
		}else{
			$model->time = date('m/d/Y',time());
		}

		$cars = $user->getCars();

		$this->render('create',array(
			'model'=>$model,
			'categories'=>$categories,
			'cars'=>$cars,
			'user'=>$user,
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

		$user = User::model()->findByPk(Yii::app()->user->getId());
		if($user->isPremium())
			$categories = ExpenseCategory::model()->findAll(array(
				'condition'=>'`primary`=1 OR uid=:uid',
				'params'=>array(':uid'=>$user->id),
				'order'=>'name ASC',
				));
		else
			$categories = ExpenseCategory::model()->findAll('`primary`=1');

		$car = UserCar::model()->findByPk($model->car_id);
		if($car->uid !== $user->id){
			$this->redirect(array('index'));
		}

		if(isset($_POST['Expense']))
		{
			$model->attributes=$_POST['Expense'];
			$model->car_id = $car->id;
			$model->uid = $user->id;
			$model->time = date('Y-m-d H:i:s', strtotime($model->time));
			if($model->save())
				$this->redirect(array('index', 'car_id'=>$model->car_id));
		}else{
			$model->time = date('m/d/Y',strtotime($model->time));
		}

		$this->render('update',array(
			'model'=>$model,
			'categories'=>$categories,
			'user'=>$user,
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
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Lists all models.
	 */
	public $last_car;
	public function actionIndex()
	{
		$user = User::model()->findByPk(Yii::app()->user->getId());
		if(isset($_GET['car_id'])){
			$car = UserCar::model()->findByPk($_GET['car_id']);
			if($car->uid !== $user->id){
				$this->redirect(array('index'));
			}
			$dataProvider=new CActiveDataProvider('Expense', array(
				'criteria'=>array(
					'condition'=>'car_id=:cid',
					'params'=>array(':cid'=>addslashes($_GET['car_id'])),
					'order'=>'time DESC',
				),
			));
		}else{
			$dataProvider=new CActiveDataProvider('Expense', array(
				'criteria'=>array(
					'condition'=>'uid=:uid',
					'params'=>array(':uid'=>$user->id),
					'order'=>'time DESC',
				),
			));
		}
		$unusedcats = 0;
		if($user->isPremium()){
			$categories = ExpenseCategory::model()->findAll('uid=?', array($user->id));
			if(!empty($categories))
				foreach($categories as $category){
					if(!Expense::model()->count('category_id=:cid', array(':cid'=>$category->id)))
						$unusedcats++;
				}
		}
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
			'unusedcats'=>$unusedcats,
			'expenses'=>$expenses,
		));
	}
	
	public function actionCharts(){
		$user = User::model()->findByPk(Yii::app()->user->getId());
		if(isset($_GET['car_id'])){
			$car = UserCar::model()->findByPk($_GET['car_id']);
			if($car->uid !== $user->id){
				$this->redirect(array('index'));
			}
			$expenses = Expense::model()->findAll('car_id=:car_id',array(':car_id'=>$_GET['car_id']));
			$expenses_cats = Expense::model()->findAll(array('condition'=>'car_id=:car_id', 'params'=>array(':car_id'=>$_GET['car_id']), 'order'=>'category_id ASC'));
//			$crons = Cron::model()->findAll('car_id=:car_id',array(':car_id'=>$_GET['car_id']));
//			$crons_cats = Cron::model()->findAll(array('condition'=>'car_id=:car_id', 'params'=>array(':car_id'=>$_GET['car_id']), 'order'=>'type ASC'));
		}else{
			$expenses = Expense::model()->findAll('uid=:uid',array(':uid'=>$user->id));
			$expenses_cats = Expense::model()->findAll(array('condition'=>'uid=:uid', 'params'=>array(':uid'=>$user->id), 'order'=>'category_id ASC'));
//			$crons = Expense::model()->findAll('uid=:uid',array(':uid'=>$user->id));
//			$crons_cats = Expense::model()->findAll(array('condition'=>'uid=:uid', 'params'=>array(':uid'=>$user->id), 'order'=>'type ASC'));
		}
		$this->render('charts',array(
			'expenses'=>$expenses,
			'expenses_cats'=>$expenses_cats,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Expense('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Expense']))
			$model->attributes=$_GET['Expense'];

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
		$model=Expense::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='expense-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
