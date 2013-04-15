<div class="colecciones form">
<?php echo $this->Form->create('Coleccion'); ?>
	<fieldset>
		<legend><?php echo __('Admin Edit Coleccion'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('usuario_id');
		echo $this->Form->input('grupo_id');
		echo $this->Form->input('nombre');
		echo $this->Form->input('es_auditable');
		echo $this->Form->input('acceso_anonimo');
		echo $this->Form->input('Grupo');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Coleccion.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('Coleccion.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Colecciones'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Usuarios'), array('controller' => 'usuarios', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Usuario'), array('controller' => 'usuarios', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Grupos'), array('controller' => 'grupos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Grupo'), array('controller' => 'grupos', 'action' => 'add')); ?> </li>
	</ul>
</div>
