<div class="login">
	<?php
	echo $this->Session->flash('auth');
	echo $this->Form->create('Usuario', array('action' => 'login'));
	echo $this->Form->input('documento', array('required' => 'required'));
	echo $this->Form->input('contraseña', array('type' => 'password', 'required' => 'required'));
	echo $this->Form->end('Acceder');
	?>
</div>