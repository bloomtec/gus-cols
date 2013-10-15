<div class="usuarios index">
	<h2><?php echo __('Usuarios'); ?></h2>
	<table cellpadding="0" cellspacing="0">
		<tr>
			<th><?php echo $this->Paginator->sort('documento'); ?></th>
			<th><?php echo $this->Paginator->sort('nombres'); ?></th>
			<th><?php echo $this->Paginator->sort('apellidos'); ?></th>
			<th><?php echo $this->Paginator->sort('correo'); ?></th>
			<th><?php echo $this->Paginator->sort('recibir_correos'); ?></th>
			<th><?php echo $this->Paginator->sort('activo'); ?></th>
			<th><?php echo $this->Paginator->sort('created', 'Creado'); ?></th>
			<th><?php echo $this->Paginator->sort('modified', 'Modificado'); ?></th>
			<th class="actions"><?php echo __('Acciones'); ?></th>
		</tr>
		<?php foreach($usuarios as $usuario): ?>
			<tr>
				<td><?php echo h($usuario['Usuario']['documento']); ?>&nbsp;</td>
				<td><?php echo h($usuario['Usuario']['nombres']); ?>&nbsp;</td>
				<td><?php echo h($usuario['Usuario']['apellidos']); ?>&nbsp;</td>
				<td><?php echo h($usuario['Usuario']['correo']); ?>&nbsp;</td>
				<td>
					<?php if($usuario['Usuario']['recibir_correos']) { ?>
						<input type="checkbox" disabled="disabled" checked="checked">
					<?php } else { ?>
						<input type="checkbox" disabled="disabled">
					<?php } ?>
					&nbsp;
				</td>
				<td>
					<?php if($usuario['Usuario']['activo']) { ?>
					<input type="checkbox" disabled="disabled" checked="checked">
					<?php } else { ?>
					<input type="checkbox" disabled="disabled">
					<?php } ?>
					&nbsp;
				</td>
				<td><?php echo h($usuario['Usuario']['created']); ?>&nbsp;</td>
				<td><?php echo h($usuario['Usuario']['modified']); ?>&nbsp;</td>
				<td class="actions">
					<?php echo $this->Html->link(__('Ver'), array('action' => 'view', $usuario['Usuario']['id'])); ?>
					<?php echo $this->Html->link(__('Modificar'), array('action' => 'edit', $usuario['Usuario']['id'])); ?>
					<?php echo $this->Form->postLink(__('Eliminar'), array('action' => 'delete', $usuario['Usuario']['id']), null, __('Are you sure you want to delete # %s?', $usuario['Usuario']['id'])); ?>
				</td>
			</tr>
		<?php endforeach; ?>
	</table>
	<?php echo $this -> element('paginator'); ?>
</div>