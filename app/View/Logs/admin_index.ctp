<div class="logs index">
	<h2><?php echo __('Registros'); ?></h2>
	<table cellpadding="0" cellspacing="0">
		<tr>
			<th><?php echo $this->Paginator->sort('usuario_id', 'Usuario Que Realiza La Acción'); ?></th>
			<th><?php echo $this->Paginator->sort('model', 'Modelo'); ?></th>
			<th><?php echo $this->Paginator->sort('foreign_key', 'ID'); ?></th>
			<th><?php echo $this->Paginator->sort('add', 'Crear'); ?></th>
			<th><?php echo $this->Paginator->sort('edit', 'Modificar'); ?></th>
			<th><?php echo $this->Paginator->sort('delete', 'Eliminar'); ?></th>
			<th><?php echo $this->Paginator->sort('created', 'Fecha De Registro'); ?></th>
			<th class="actions"><?php echo __('Acciones'); ?></th>
		</tr>
		<?php foreach($logs as $log): ?>
			<tr>
				<td>
					<?php echo $this->Html->link($log['Usuario']['documento'], array('controller' => 'usuarios', 'action' => 'view', $log['Usuario']['id'])); ?>
				</td>
				<td><?php echo h($log['Log']['model']); ?>&nbsp;</td>
				<td><?php echo h($log['Log']['foreign_key']); ?>&nbsp;</td>
				<td>
					<?php
						if($log['Log']['add']) {
							echo '<input type="checkbox" checked disabled />';
						} else {
							echo '<input type="checkbox" disabled />';
						}
					?>
					&nbsp;
				</td>
				<td>
					<?php
						if($log['Log']['edit']) {
							echo '<input type="checkbox" checked disabled />';
						} else {
							echo '<input type="checkbox" disabled />';
						}
					?>
					&nbsp;
				</td>
				<td>
					<?php
						if($log['Log']['delete']) {
							echo '<input type="checkbox" checked disabled />';
						} else {
							echo '<input type="checkbox" disabled />';
						}
					?>
					&nbsp;
				</td>
				<td><?php echo h($log['Log']['created']); ?>&nbsp;</td>
				<td class="actions">
					<?php echo $this->Html->link(__('Ver'), array('action' => 'view', $log['Log']['id'])); ?>
				</td>
			</tr>
		<?php endforeach; ?>
	</table>
	<?php echo $this->element('paginator'); ?>
</div>