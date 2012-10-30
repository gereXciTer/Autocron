<?php

/**
 * This is the model class for table "content".
 *
 * The followings are the available columns in table 'content':
 * @property double $id
 * @property string $code
 * @property string $lang
 * @property string $text
 */
class Content extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Content the static model class
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
		return 'content';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('code', 'length', 'max'=>50),
			array('lang', 'length', 'max'=>10),
			array('text', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, code, lang, text', 'safe', 'on'=>'search'),
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
			'code' => 'Code',
			'lang' => 'Lang',
			'text' => 'Text',
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
		$criteria->compare('code',$this->code,true);
		$criteria->compare('lang',$this->lang,true);
		$criteria->compare('text',$this->text,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function getText(){
		$localized = Content::model()->find('code=:code AND lang=:lang', array(':code'=>$this->code, ':lang'=>Yii::app()->language));
		if(!empty($localized))
			$this->text = $localized->text;
		else{
			$localized = Content::model()->find('code=:code AND text>0', array(':code'=>$this->code));
			if(!empty($localized))
				$this->text = $localized->text;
		}
		return $this->text;
	}
	
//	protected function afterFind(){
//		$this->getText();
//		return true;
//	}
	protected function beforeSave(){
		if(!in_array(Yii::app()->controller->action->id,array('update')))
			$this->lang = Yii::app()->language;
		return true;
	}
	
	public static function getStatic($code){
		$content = Content::model()->find('code=:code', array(':code'=>$code));
		return $content->getText();
	}
	
}