<div class="usuarios view">
<h2><?php  echo __('Usuario'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($usuario['Usuario']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Documento'); ?></dt>
		<dd>
			<?php echo h($usuario['Usuario']['documento']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Contraseña'); ?></dt>
		<dd>
			<?php echo h($usuario['Usuario']['contraseña']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Nombres'); ?></dt>
		<dd>
			<?php echo h($usuario['Usuario']['nombres']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Apellidos'); ?></dt>
		<dd>
			<?php echo h($usuario['Usuario']['apellidos']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Activo'); ?></dt>
		<dd>
			<?php echo h($usuario['Usuario']['activo']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($usuario['Usuario']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($usuario['Usuario']['modified']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Usuario'), array('action' => 'edit', $usuario['Usuario']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Usuario'), array('action' => 'delete', $usuario['Usuario']['id']), null, __('Are you sure you want to delete # %s?', $usuario['Usuario']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Usuarios'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Usuario'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Auditorias'), array('controller' => 'auditorias', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Auditoria'), array('controller' => 'auditorias', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Colecciones'), array('controller' => 'colecciones', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Coleccion'), array('controller' => 'colecciones', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Grupos'), array('controller' => 'grupos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Grupo'), array('controller' => 'grupos', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php echo __('Related Auditorias'); ?></h3>
	<?php if (!empty($usuario['Auditoria'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Usuario Id'); ?></th>
		<th><?php echo __('Model'); ?></th>
		<th><?php echo __('Foreign Key'); ?></th>
		<th><?php echo __('Colección Aprobada'); ?></th>
		<th><?php echo __('Observación'); ?></th>
		<th><?php echo __('Created'); ?></th>
		<th><?php echo __('Modified'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($usuario['Auditoria'] as $auditoria): ?>
		<tr>
			<td><?php echo $auditoria['id']; ?></td>
			<td><?php echo $auditoria['usuario_id']; ?></td>
			<td><?php echo $auditoria['model']; ?></td>
			<td><?php echo $auditoria['foreign_key']; ?></td>
			<td><?php echo $auditoria['colección_aprobada']; ?></td>
			<td><?php echo $auditoria['observación']; ?></td>
			<td><?php echo $auditoria['created']; ?></td>
			<td><?php echo $auditoria['modified']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'auditorias', 'action' => 'view', $auditoria['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'auditorias', 'action' => 'edit', $auditoria['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'auditorias', 'action' => 'delete', $auditoria['id']), null, __('Are you sure you want to delete # %s?', $auditoria['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Auditoria'), array('controller' => 'auditorias', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
<div class="related">
	<h3><?php echo __('Related Colecciones'); ?></h3>
	<?php if (!empty($usuario['Coleccion'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Usuario Id'); ?></th>
		<th><?php echo __('Grupo Id'); ?></th>
		<th><?php echo __('Es Auditable'); ?></th>
		<th><?php echo __('Created'); ?></th>
		<th><?php echo __('Modified'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($usuario['Coleccion'] as $coleccion): ?>
		<tr>
			<td><?php echo $coleccion['id']; ?></td>
			<td><?php echo $coleccion['usuario_id']; ?></td>
			<td><?php echo $coleccion['grupo_id']; ?></td>
			<td><?php echo $coleccion['es_auditable']; ?></td>
			<td><?php echo $coleccion['created']; ?></td>
			<td><?php echo $coleccion['modified']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'colecciones', 'action' => 'view', $coleccion['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'colecciones', 'action' => 'edit', $coleccion['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'colecciones', 'action' => 'delete', $coleccion['id']), null, __('Are you sure you want to delete # %s?', $coleccion['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Coleccion'), array('controller' => 'colecciones', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
<div class="related">
	<h3><?php echo __('Related Grupos'); ?></h3>
	<?php if (!empty($usuario['Grupo'])): ?>
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
		foreach ($usuario['Grupo'] as $grupo): ?>
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
