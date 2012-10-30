<?php

class ShopController extends Controller
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
				'actions'=>array('index','view','create','update','delete','addDefinition','deleteDefinition'),
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
		
		$brands = new CActiveDataProvider('ShopDefinition', array(
				  'criteria'=>array(
					'condition'=>'(brand_id>0 OR all_brands=1) AND shop_id='.$id,
				  ),
				));
		$cron_types = new CActiveDataProvider('ShopDefinition', array(
				  'criteria'=>array(
					'condition'=>'cron_type_id>0 AND shop_id='.$id,
				  ),
				));
		
		$this->render('view',array(
			'model'=>$model,
			'brands'=>$brands,
			'cron_types'=>$cron_types,
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Shop('create');
		$user = User::model()->findByPk(Yii::app()->user->getId());
		
//		if(Shop::model()->count('uid=?', array($user->id)))
			if(!$user->isPremium()){
//				Yii::app()->user->setFlash('message', Yii::t('app', 'Without premium account you can have only one shop.'));
				Yii::app()->user->setFlash('message', Yii::t('app', 'Without premium account you cannot add your shop.'));
				$this->redirect(array('site/getPremium', 'controller'=>Yii::app()->controller->id, 'action'=>Yii::app()->controller->action->id));
			}
		
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Shop']))
		{
			$model->attributes=$_POST['Shop'];
			$model->uid = $user->id;
//			$model->coordinates = $user->coordinates;
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('create',array(
			'model'=>$model,
			'user'=>$user,
		));
	}
	
	public function actionAddDefinition(){
		$model=Shop::model()->findByPk($_GET['id']);
			
		if($model->uid !== Yii::app()->user->getId())
			$this->redirect(array('site/index'));
			
		if(ShopDefinition::model()->count('shop_id=?', array($model->id)) >= Yii::app()->params['max-free-definitions'])
			if(!$user->isPremium()){
				Yii::app()->user->setFlash('message', Yii::t('app', 'Without premium account you can have only {num} items attached to your shop.', array('{num}'=>Yii::app()->params['max-free-definitions'])));
				$this->redirect(array('site/getPremium', 'controller'=>Yii::app()->controller->id, 'action'=>Yii::app()->controller->action->id));
			}

		$definition = new ShopDefinition('create');

		if(isset($_POST['ShopDefinition']))
		{
			$definition->attributes=$_POST['ShopDefinition'];
			$definition->shop_id = $model->id;
			if($definition->all_brands){
				if(!$user->isPremium()){
					Yii::app()->user->setFlash('message', Yii::t('app', 'You can sell for all brands only with premium account.'));
					$this->redirect(array('site/getPremium', 'controller'=>Yii::app()->controller->id, 'action'=>Yii::app()->controller->action->id));
				}
				$definition->brand_id = 0;
			}
			if($definition->save())
				$this->redirect(array('view','id'=>$model->id));
		}
		
		$this->render('addDefinition',array(
			'model'=>$model,
			'definition'=> $definition,
			'brands'=>CarMake::model()->findAll(),
			'cron_types'=>CronType::model()->findAll('`primary`=1'),
		));
	}

	public function actionDeleteDefinition(){
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			$model = ShopDefinition::model()->findByPk($_GET['id']);
			$shop = $this->loadModel($model->shop_id);
			if($shop->uid == Yii::app()->user->getId())
				$model->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(array('view', 'id'=>$_GET['shop_id']));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
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

		$user = User::model()->findByPk(Yii::app()->user->getId());
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Shop']))
		{
			$model->attributes=$_POST['Shop'];
			$model->uid = $user->id;
//			$model->coordinates = $user->coordinates;
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
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
				$this->redirect(array('view', 'id'=>$model->id));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Shop', array(
				  'criteria'=>array(
					'condition'=>'uid='.Yii::app()->user->getId(),
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
		$model=new Shop('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Shop']))
			$model->attributes=$_GET['Shop'];

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
		$model=Shop::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='shop-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
