<?php

/**
 * Wrapper for Datepicker, http://www.eyecon.ro/bootstrap-datepicker/
 */
class Datepicker extends CInputWidget
{
    /**
     * @var string twitter bootstrap version, set to either 2 or 3
     */
    public $bootstrapVersion = '3';

	/**
	 * @var array
	 */
	public $options = array();
	
	/**
     * @var integer Script position
     */
	public $scriptPosition=null;

	/**
	 * @var string Html element selector
	 */
	public $selector;

	public static function initClientScript($scriptPosition = null, $bootstrapVersion = '3') {
		$bu=Yii::app()->assetManager->publish(dirname(__FILE__).'/assets/');
	    $cs=Yii::app()->clientScript;
		if($scriptPosition===null) $scriptPosition=$cs->coreScriptPosition;
		$cs->registerScriptFile($bu.'/js/moment-with-langs.js',$scriptPosition);
		$cs->registerScriptFile($bu.'/js/bootstrap-datepicker.js',$scriptPosition);
		if(Yii::app()->language != 'en'){
			$cs->registerScriptFile($bu.'/js/locales/bootstrap-datepicker.'.Yii::app()->language.'.js',$scriptPosition);
		}
		
		$cs->registerCssFile($bu.'/css/datepicker'.($bootstrapVersion == '2' ? '' : $bootstrapVersion).'.css');
	}
	
	public function run() {
		if($this->selector==null) {
			list($this->name,$this->id)=$this->resolveNameId();
			$this->selector='#'.$this->id;
		}

		if (!isset($this->htmlOptions['value'])) {
			if ($this->hasModel()) {
				$this->value = CHtml::resolveValue($this->model, $this->attribute);
			}
		} else {
			$this->value = $this->htmlOptions['value'];
			unset($this->htmlOptions['value']);
		}

        $this->htmlOptions['autocomplete'] = 'off';
		
		self::initClientScript($this->scriptPosition, $this->bootstrapVersion);
		$options=$this->options?CJavaScript::encode($this->options):'';
		Yii::app()->clientScript->registerScript(__CLASS__.'#'.$this->id, "jQuery('{$this->selector}').datepicker({$options})");

		echo CHtml::textField($this->name, $this->value, $this->htmlOptions);
	}
}
