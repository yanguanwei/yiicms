<?php
$this->beginWidget('zii.widgets.CPortlet', array('skin' => 'information'));
	echo '<ul class="prompt">';
	foreach ( $prompts as $prompt )
		echo "<li>{$prompt}</li>";
	echo '</ul>';
$this->endWidget();
?>