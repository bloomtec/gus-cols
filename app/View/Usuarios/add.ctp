<div class="usuarios form">
<?php echo $this->Form->create('Usuario'); ?>
	<fieldset>
		<legend><?php echo __('Add Usuario'); ?></legend>
	<?php
		echo $this->Form->input('documento');
		echo $this->Form->input('contraseña');
		echo $this->Form->input('nombres');
		echo $this->Form->input('apellidos');
		echo $this->Form->input('activo');
		echo $this->Form->input('Grupo');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
