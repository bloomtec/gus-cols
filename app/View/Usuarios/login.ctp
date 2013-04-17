<div class="login">
	<?php
	echo $this->Session->flash('auth');
	echo $this->Form->create('Usuario', array('action' => 'login'));
	echo $this->Form->input('documento', array('required' => 'required', 'value' => '', 'placeholder' => 'Ingrese su documento de identidad'));
	echo $this->Form->input('contraseña', array('type' => 'password', 'required' => 'required', 'value' => '', 'placeholder' => 'Ingrese su contraseña'));
	echo $this->Form->end('Iniciar Sesión');
	?>
</div>