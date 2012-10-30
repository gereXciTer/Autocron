<?php

class CouponController extends Controller
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
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete','create','update','index','view','createMany'),
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

	public function actionCreateMany(){
		$model=new Coupon;
		$codes = array();
		if(isset($_POST['Coupon'])){
			for($i = 0; $i < $_POST['Coupon']['count']; $i++){
				$model=new Coupon;
				$model->code = $this->keygen(10);
				while($coupon = Coupon::model()->find('code=:code AND used=0', array(':code'=>$model->code))){
					$model->code = $this->keygen(10);
				}
				$model->type = $_POST['Coupon']['type'];
				$model->goodie = $_POST['Coupon']['goodie'];
				$model->expired = date('Y-m-d H:i:s',time() + $model->type * 2628000);
				if($model->save())
					$codes[] = $model->code;
			}
		}
		$this->render('createMany',array(
			'model'=>$model,
			'codes'=>$codes,
		));
	}

	public function actionCreate()
	{
		$model=new Coupon;
		
		

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Coupon']))
		{
			$model->attributes=$_POST['Coupon'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}else{
			$model->code = $this->keygen(10);
			while($coupon = Coupon::model()->find('code=:code AND used=0', array(':code'=>$model->code))){
				$model->code = $this->keygen(10);
			}
			$model->type = 6;
			$model->expired = date('Y-m-d H:i:s',time() + $model->type * 2628000);
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

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Coupon']))
		{
			$model->attributes=$_POST['Coupon'];
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
			$this->loadModel($id)->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Coupon');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Coupon('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Coupon']))
			$model->attributes=$_GET['Coupon'];

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
		$model=Coupon::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='coupon-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
