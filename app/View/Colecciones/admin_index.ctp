<?php
	if(!isset($coleccion_id) || !$coleccion_id) {
		echo $this->element('colecciones_ct_index');
	} else {
		echo $this->element('colecciones_index');
	}
?>