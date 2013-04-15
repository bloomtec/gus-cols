<div class="colecciones view">
<h2><?php  echo __('Coleccion'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($coleccion['Coleccion']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Usuario'); ?></dt>
		<dd>
			<?php echo $this->Html->link($coleccion['Usuario']['documento'], array('controller' => 'usuarios', 'action' => 'view', $coleccion['Usuario']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Grupo'); ?></dt>
		<dd>
			<?php echo $this->Html->link($coleccion['Grupo']['nombre'], array('controller' => 'grupos', 'action' => 'view', $coleccion['Grupo']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Nombre'); ?></dt>
		<dd>
			<?php echo h($coleccion['Coleccion']['nombre']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Es Auditable'); ?></dt>
		<dd>
			<?php echo h($coleccion['Coleccion']['es_auditable']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Acceso Anónimo'); ?></dt>
		<dd>
			<?php echo h($coleccion['Coleccion']['acceso_anonimo']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($coleccion['Coleccion']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($coleccion['Coleccion']['modified']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Coleccion'), array('action' => 'edit', $coleccion['Coleccion']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Coleccion'), array('action' => 'delete', $coleccion['Coleccion']['id']), null, __('Are you sure you want to delete # %s?', $coleccion['Coleccion']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Colecciones'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Coleccion'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Usuarios'), array('controller' => 'usuarios', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Usuario'), array('controller' => 'usuarios', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Grupos'), array('controller' => 'grupos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Grupo'), array('controller' => 'grupos', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php echo __('Related Grupos'); ?></h3>
	<?php if (!empty($coleccion['Grupo'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Nombre'); ?></th>
		<th><?php echo __('Created'); ?></th>
		<th><?php echo __('Modified'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($coleccion['Grupo'] as $grupo): ?>
		<tr>
			<td><?php echo $grupo['id']; ?></td>
			<td><?php echo $grupo['nombre']; ?></td>
			<td><?php echo $grupo['created']; ?></td>
			<td><?php echo $grupo['modified']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'grupos', 'action' => 'view', $grupo['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'grupos', 'action' => 'edit', $grupo['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'grupos', 'action' => 'delete', $grupo['id']), null, __('Are you sure you want to delete # %s?', $grupo['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Grupo'), array('controller' => 'grupos', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
