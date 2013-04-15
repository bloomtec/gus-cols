<div class="colecciones index">
	<h2><?php echo __('Bases de contenido'); ?></h2>
	<table cellpadding="0" cellspacing="0">
		<tr>
			<th><?php echo $this->Paginator->sort('nombre'); ?></th>
			<th><?php echo $this->Paginator->sort('es_auditable'); ?></th>
			<th><?php echo $this->Paginator->sort('acceso_anonimo', 'Acceso Anónimo'); ?></th>
			<th><?php echo $this->Paginator->sort('created', 'Creada'); ?></th>
			<th><?php echo $this->Paginator->sort('modified', 'Modificada'); ?></th>
			<th class="actions"><?php echo __('Acciones'); ?></th>
		</tr>
		<?php foreach($colecciones as $coleccion): ?>
			<tr>
				<td><?php echo h($coleccion['Coleccion']['nombre']); ?>&nbsp;</td>
				<td>
					<?php if($coleccion['Coleccion']['es_auditable']) { ?>
						<input type="checkbox" disabled="disabled" checked="checked">
					<?php } else { ?>
						<input type="checkbox" disabled="disabled">
					<?php } ?>
					&nbsp;
				</td>
				<td>
					<?php if($coleccion['Coleccion']['acceso_anonimo']) { ?>
						<input type="checkbox" disabled="disabled" checked="checked">
					<?php } else { ?>
						<input type="checkbox" disabled="disabled">
					<?php } ?>
					&nbsp;
				</td>
				<td><?php echo h($coleccion['Coleccion']['created']); ?>&nbsp;</td>
				<td><?php echo h($coleccion['Coleccion']['modified']); ?>&nbsp;</td>
				<td class="actions">
					<?php echo $this->Html->link(__('Ver'), array('action' => 'view_content_type', $coleccion['Coleccion']['id'])); ?>
					<?php echo $this->Html->link(__('Modificar'), array('action' => 'edit_content_type', $coleccion['Coleccion']['id'])); ?>
					<?php echo $this->Form->postLink(__('Eliminar'), array('action' => 'delete_content_type', $coleccion['Coleccion']['id']), null, __('Are you sure you want to delete # %s?', $coleccion['Coleccion']['id'])); ?>
				</td>
			</tr>
		<?php endforeach; ?>
	</table>
	<?php echo $this -> element('admin/paginator'); ?>
</div>