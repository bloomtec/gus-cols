<div class="usuarios form">
<?php echo $this->Form->create('Usuario'); ?>
	<fieldset>
		<legend><?php echo __('Crear Usuario'); ?></legend>
	<?php
		echo $this->Form->input('documento');
		echo $this->Form->input('contraseña', array('type' => 'password', 'value' => ''));
		echo $this->Form->input('verificar_contraseña', array('type' => 'password', 'value' => ''));
		echo $this->Form->input('nombres');
		echo $this->Form->input('apellidos');
		echo $this->Form->input('correo', array('type' => 'email'));
		echo $this->Form->input('recibir_correos', array('checked' => 'checked'));
		echo $this->Form->input('activo', array('checked' => 'checked'));
		echo $this->Form->input('Grupo', array('label' => 'Grupos', 'multiple' => 'checkbox'));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Crear')); ?>
</div>