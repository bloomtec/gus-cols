<div class="grupos form">
<?php echo $this->Form->create('Grupo'); ?>
	<fieldset>
		<legend><?php echo __('Modificar Grupo'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('nombre');
		echo $this->Form->input('Usuario', array('label' => 'Usuarios', 'multiple' => 'checkbox'));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Modificar')); ?>
</div>