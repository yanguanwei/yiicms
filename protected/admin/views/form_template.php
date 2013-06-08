<?php
if ( !$view ) {
	$view = 'form';
}

if ( $view[0] != '/' && strpos($view, '.') === false) {
	$view = $this->getUniqueId() . '/' . $view;
}

if ( strpos($view, '.') === false ) {
	$view = 'admin.views.' . str_replace('/', '.', $view);
}

$this->widget('application.widgets.AdminForm', array(
	'model' => $form,
	'view' => $view,
	'viewOptions' => array(
		'title' => $title,
    'channel' => $channel,
    'model' => $model,
    'isAttach' => $isAttach
	)
));
