<div class="usuarios form">
<?php echo $this->Form->create('Usuario'); ?>
	<fieldset>
		<legend><?php echo __('Modificar Usuario'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('documento');
		echo $this->Form->input('modificar_contraseña', array('type' => 'password', 'value' => ''));
		echo $this->Form->input('verificar_contraseña', array('type' => 'password', 'value' => ''));
		echo $this->Form->input('nombres');
		echo $this->Form->input('apellidos');
		echo $this->Form->input('activo');
		echo $this->Form->input('Grupo', array('label' => 'Grupos', 'multiple' => 'checkbox'));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Modificar')); ?>
</div>
