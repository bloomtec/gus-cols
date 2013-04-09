<div class="grupos form">
	<?php echo $this->Form->create('Grupo'); ?>
	<fieldset>
		<legend><?php echo __('Crear Grupo'); ?></legend>
		<?php
		echo $this->Form->input('nombre');
		//echo $this->Form->input('Coleccion');
		//echo $this->Form->input('Usuario');
		?>
	</fieldset>
	<?php echo $this->Form->end(__('Crear')); ?>
</div>