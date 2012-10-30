<?php
 
/**
 * ApplicationConfigBehavior is a behavior for the application.
 * It loads additional config paramenters that cannot be statically 
 * written in config/main
 */
class ApplicationConfigBehavior extends CBehavior
{
    /**
     * Declares events and the event handler methods
     * See yii documentation on behaviour
     */
    public function events()
    {
        return array_merge(parent::events(), array(
            'onBeginRequest'=>'beginRequest',
        ));
    }
 
    /**
     * Load configuration that cannot be put in config/main
     */
    public function beginRequest()
    {
        
		if(Yii::app()->request->cookies['AutocronUserLanguage']->value)
            $this->owner->language=Yii::app()->request->cookies['AutocronUserLanguage']->value;
        else{
			$userlang = CLocale::getInstance(Yii::app()->request->getPreferredLanguage())->getLanguageID(Yii::app()->request->getPreferredLanguage());
			if($userlang)
				$this->owner->language=$userlang;
			else
				$this->owner->language='en';
				
			Yii::app()->request->cookies['AutocronUserLanguage'] = new CHttpCookie('AutocronUserLanguage', $this->owner->language);
		}
    }
}
?>