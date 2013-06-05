<?php
class AdminForm extends CWidget
{
	/**
	 * 表单视图路径
	 * 
	 * @var string
	 */
	public $view;
	
	/**
	 * 传递给表单视图的配置数组
	 * @var array
	 */
	public $viewOptions = array();
	
	/**
	 * @var CActiveRecord
	 */
	public $model;
	
	/**
	 * @var CActiveForm
	 */
	public $form;
	
	private $_backurlName = '__backurl__';
	
	private $_backurl;
	
	public function __call($name, $parameters)
	{
		if ( substr($name, 0, 6) == 'render' && substr($name, -3) == 'Row' ) {
			$method = substr($name, 0, -3) . 'Field';
			return $this->renderRow(call_user_func_array(array($this, $method), $parameters));
		}
		
		return parent::__call($name, $parameters);
	}
	
	public function init()
	{
		if (!$this->model) {
			throw new CException("需要指定model属性！");
		}
		
		$this->form = $this->beginWidget('CActiveForm');
		
		if ( !Yii::app()->request->isPostRequest ) {
			$this->_backurl = Yii::app()->getRequest()->getUrlReferrer();
		} else {
			$this->_backurl = $_POST[$this->_backurlName];
		}
		echo CHtml::hiddenField($this->_backurlName, $this->_backurl);
	}
	
	public function renderChannelSelectField($attribute, $parent_id, array $htmlOptions = array())
	{
		$conn = Yii::app()->db;
		$command = $conn->createCommand("SELECT id, title FROM {{channel}} WHERE parent_id='{$parent_id}' AND `visible`='1' AND `model`='' ORDER BY sort_id DESC, id ASC");
		$data = $command->queryAll();
		$opts = array();
		foreach ($data as $row) {
			$opts[$row['id']] = $row['title'];
		}
		return $this->renderField(array(
			'attribute' => $attribute,
			'type' => 'dropDownList',
			'options' => array(
				$opts, $htmlOptions
			)
		));
	}
	
	public function renderChannelTreeSelectField($attribute, $model_id, $note = null, array $htmlOptions = array())
	{
		$conn = Yii::app()->db;
		$sql = "SELECT id, title, parent_id FROM {{channel}} WHERE `model_id`='{$model_id}' AND `visible`='1'  ORDER BY sort_id DESC, id ASC";
		$command = $conn->createCommand($sql);
		$data = $command->queryAll();
	
		return $this->renderTreeSelectField($attribute, $data, 0, $note, $htmlOptions);
	}
	
	public function renderCKEditorField($attribute)
	{
		list($model, $attr) = $this->parseAttribute($attribute);

		Yii::import('application.widgets.CKFinderInput');
		CKFinderInput::initCKFinderSession();
		
		return $this->renderField(array(
			'attribute' => $attribute,
			'widget' => 'ext.ckeditor.CKEditor',
			'options' => array(
				'model' => $model,
				'attribute' => $attr,
				'language' => 'zh-cn',
				'editorTemplate' => 'full',
				'options' => array('height' => '300px')
			),
		));
	}
	
	public function renderCKFinderInputField($attribute, $note = null, $hasPreview = true, $path = 'Images:/')
	{
		list($model, $attr) = $this->parseAttribute($attribute);
		
		return $this->renderField(array(
			'attribute' => $attribute,
			'widget' => 'application.widgets.CKFinderInput',
			'note' => $note,
			'options' => array(
				'attribute' => $attr,
				'model' => $model,
				'htmlOptions' => array('class' => 'text-input medium-input'),
				'startupPath' => $path,
				'buttonHtmlOptions' => array('class' => 'button'),
				'hasPreview' => $hasPreview
			)
		));
	}
	
	public function renderDateTimerField($attribute, $note = null, array $htmlOptions = array())
	{
		$htmlOptions['class'] .= (isset($htmlOptions['class']) ? ' ' : '') . 'dateTimer';
		return $this->renderTextField($attribute, $note, $htmlOptions);
	}
	
	public function renderHiddenField($attribute)
	{
		list($model, $attribute) = $this->parseAttribute($attribute);
		return $this->form->hiddenField($model, $attribute);
	}
	
	
	public function renderHiddenTextField($attribute, $text, $note = null, array $htmlOptions = array())
	{
		$htmlOptions['disabled'] = 'disabled';
		$field = $this->renderHiddenField($attribute) . CHtml::textField('', $text, $htmlOptions);
		
		return $this->renderField(array(
				'attribute' => $attribute,
				'field' => $field,
				'note' => $note
		));
	}
	
	public function renderHiddenDisabledTextField($attribute, $note = null, array $htmlOptions = array())
	{
		$field = $this->renderHiddenField($attribute);

		$htmlOptions['disabled'] = 'disabled';
		list($model, $attribute) = $this->parseAttribute($attribute);
		$field .= $this->form->textField($model, $attribute, $htmlOptions);
		
		return $this->renderField(array(
			'attribute' => $attribute,
			'field' => $field,
			'note' => $note	
		));
	}
	
	public function renderSelectField($attribute, array $data, $note = null, array $htmlOptions = array())
	{
		return $this->renderField(array(
			'type'		=> 'dropDownList',
			'attribute'	=> $attribute,
			'options'	=> array(
				$data, $htmlOptions
			),
			'note' => $note
		));
	}
	
	public function renderTreeSelectField($attribute, array $data, $rootId = 0, $note = null, array $htmlOptions = array())
	{
		return $this->renderField(array(
				'attribute' => $attribute,
				'field' => $this->treeSelect($attribute, $data, $rootId, $htmlOptions),
				'note' => $note
		));
	}
	
	public function renderHiddenDisabledSelectField($attribute, array $data, $note = null, array $htmlOptions = array())
	{
		$field = $this->renderHiddenField($attribute);
	
		$htmlOptions['disabled'] = 'disabled';
		list($model, $attr) = $this->parseAttribute($attribute);
		$field .= $this->form->dropDownList($model, $attr, $data, $htmlOptions);
	
		return $this->renderField(array(
				'attribute' => $attribute,
				'field' => $field,
				'note' => $note
		));
	}

	public function renderHiddenDisabledTreeSelectField($attribute, array $data, $note = null, array $htmlOptions = array())
	{
		$field = $this->renderHiddenField($attribute);

		$htmlOptions['disabled'] = 'disabled';
		$field .= $this->treeSelect($attribute, $data, 0, $htmlOptions);
		
		return $this->renderField(array(
			'attribute' => $attribute,
			'field' => $field,
			'note' => $note
		));
	}
	
	public function treeSelect($attribute, array $data, $rootId = 0, array $htmlOptions = array())
	{
		list($model, $attribute) = $this->parseAttribute($attribute);
		
		return $this->widget('application.widgets.UnlimitedSelect', array(
			'model' => $model,
			'attribute' => $attribute,
			'data' => $data,
			'htmlOptions' => $htmlOptions,
			'rootId' => $rootId
		), true);
	}
	
	public function renderCheckboxField($attribute, $note = null, array $htmlOptions = array())
	{
		return $this->renderField(array(
			'attribute' => $attribute,
			'type' => 'checkBox',
			'options' => array($htmlOptions),
			'note' => $note
		));
	}
	
	public function renderTextField($attribute, $note = null, array $htmlOptions = array())
	{
		return $this->renderField(array(
			'type'		=> 'textField',
			'attribute'	=> $attribute,
			'options' => array($htmlOptions),
			'note' => $note
		));
	}
	
	public function renderPasswordField($attribute, $note = null, array $htmlOptions = array())
	{
		return $this->renderField(array(
				'type'		=> 'passwordField',
				'attribute'	=> $attribute,
				'options' => array($htmlOptions),
				'note' => $note
		));
	}
	
	public function renderTextareaField($attribute, $note = null, array $htmlOptions = array())
	{
		return $this->renderField(array(
			'attribute' => $attribute,
			'type' => 'textArea',
			'note' => $note,
			'options' => array($htmlOptions)
		));
	}
	
	public function renderErrorSummary($models = null)
	{
		return $this->form->errorSummary(null === $models ? $this->model : $models);
	}
	
	public function renderSubmitRow($value = '提交')
	{
		$row = '<div class="row submit">'. CHtml::submitButton($value, array('class' => 'button'));

		if ( $this->_backurl )
			$row .= '<a href="'.$this->_backurl.'" class="button">返回</a>';
		
		$row .= '</div>';
		
		return $row;
	}

	public function renderRow()
	{
		switch ( func_num_args() ) {
			case 1:
				$field = func_get_arg(0);
				break;
			case 2:
				$left = func_get_arg(0);
				$right = func_get_arg(1);
				$left = CHtml::tag('div', array('class' => 'column-left'), $left);
				$right = CHtml::tag('div', array('class' => 'column-right'), $right);
				$field = "{$left}\n{$right}";
				break;
		}
		
		return CHtml::tag('div', array('class' => 'row'), $field);
	}

	public function renderField($config)
	{
		list($label, $field, $error, $note) = $this->parseField($config);
		return "{$label}\n{$field}\n{$error}\n{$note}";
	}
	
	public function renderCheckboxListField()
	{
		$names = func_get_args();
		$checkboxes = array();
		foreach ($names as $name) {
			list($model, $attribute) = $this->parseAttribute($name);
			$checkboxes[] = $this->form->checkBox($model, $attribute) . $model->getAttributeLabel($name);
		}
		$checkboxes = implode("\n", $checkboxes);
		
		return "{$checkboxes}";
	}
	
	public function run()
	{
		if ($this->view) {
			$this->render($this->view, $this->viewOptions);
		}
		
		$this->endWidget();
	}

	/**
	 * $config = array(
	 *  'attribute' => 属性名，必须要指定
	 * 	'field'	=> 域的HTML代码，如果没有指定则从type或widget中获取
	 * 	'type' => string, 调用$form即CActiveForm类下的方法
	 * 	'widget' => string, widget名称
	 * 	'options' => array
	 * 		如果指定了type，则是传递给$form->$type($model, $attribute, $options[0], $options[1], ...)
	 * 		如果指定了widget，则是传递给这个widget的配置数组
	 * 	'htmlOptions' => array, 给父级div配置Html属性
	 * 	'note' => string, 提示信息
	 * )
	 * @param array $config
	 * @throws CException
	 * @return array($label, $field, $error, $note)
	 */
	protected function parseField($config)
	{
		if (is_array($config)) {
			if (!isset($config['attribute']))
				throw new CException('未指定属性名！');
		
			$attribute = $config['attribute'];
			if (isset($config['field'])) {
				$field = $config['field'];
			} else if (isset($config['type'])) {
				$type = $config['type'];
			} elseif (isset($config['widget'])) {
				$widget = $config['widget'];
			}
				
			if (isset($config['options'])) $options = $config['options'];
			if (isset($config['note']) && $config['note']) $note = "<br /><small>{$config['note']}</small>";
		} else {
			$attribute = $config;
			$type = 'textField';
		}
		
		list($model, $attribute) = $this->parseAttribute($attribute);
		
		if (!isset($field)) {
			if (!isset($options)) $options = array();
				
			if (isset($type)) {
				$field = $this->parseFieldType($attribute, $model, $type, $options);
			} elseif (isset($widget)) {
				$field = $this->parseFieldWidget($widget, $options);
			} else {
				throw new CException("属性 [{$attribute}] 没有指定表单域类型！");
			}
		}
		
		if (!isset($note)) $note = '';
		
		$label = $this->form->labelEx($model, $attribute);
		$error = $this->form->error($model, $attribute);
		
		return array(
			$label, $field, $error, $note
		);
	}
	
	protected function parseFieldWidget($widget, $options)
	{
		return $this->widget($widget, $options, true);
	}
	
	protected function parseFieldType($attribute, $model, $type, $options)
	{
		array_unshift($options, $model, $attribute);
		return call_user_func_array(array($this->form, $type), $options);
	}
	
	protected function parseAttribute($attribute)
	{
		if (strpos($attribute, '.')) {
			list($model, $attribute) = explode('.', $attribute);
		} else {
			$model = 'model';
		}
		return array($this->{$model}, $attribute);
	}
}
?>