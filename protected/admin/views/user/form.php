<?php 
echo $this->renderErrorSummary();

$widget = $this->beginWidget('application.widgets.Tabs', array(
	'title' => $title,
	'tabs' => array(
		'account' => array('label' => '帐号信息')
	),
	'defaultTab' => 'account'
));

$widget->beginTab('account');

echo $this->renderHiddenField('id');

if ( Yii::app()->user->isAdmin() ) {
	echo $this->renderTextRow('username', null, array('class' => 'text-input medium-input'));
} else {
	echo $this->renderHiddenTextRow(
		'username', $this->model->username, null, 
		array('class' => 'text-input medium-input', 'disabled' => 'disabled')
	);
}


$passwordNote = null;
if ( $this->model->id )
	$passwordNote = "不修改密码，请直接留空";

echo $this->renderPasswordRow('password', $passwordNote, array('class' => 'text-input medium-input'));
echo $this->renderPasswordRow('repeat_password', $passwordNote, array('class' => 'text-input medium-input'));

if ( Yii::app()->user->isAdmin() ) {	//不是超级管理员不能更改用户角色
	echo $this->renderSelectRow('role_id', User::getRoleSelectOptions());
} else {
	echo $this->renderHiddenTextRow('role_id', '管理员', null, array('class' => 'text-input', 'disabled' => 'disabled'));
}

//echo $this->renderTextRow('email', null, array('class' => 'text-input medium-input'));

$widget->endTab();//menuTab

$this->endWidget(); //contentBox

echo $this->renderSubmitRow();

?>
