<div class="grupos index">
	<h2><?php echo __('Grupos'); ?></h2>
	<table cellpadding="0" cellspacing="0">
		<tr>
			<th><?php echo $this->Paginator->sort('nombre'); ?></th>
			<th><?php echo $this->Paginator->sort('created', 'Creado'); ?></th>
			<th><?php echo $this->Paginator->sort('modified', 'Modificado'); ?></th>
			<th class="actions"><?php echo __('Acciones'); ?></th>
		</tr>
		<?php foreach($grupos as $grupo): ?>
			<tr>
				<td><?php echo h($grupo['Grupo']['nombre']); ?>&nbsp;</td>
				<td><?php echo h($grupo['Grupo']['created']); ?>&nbsp;</td>
				<td><?php echo h($grupo['Grupo']['modified']); ?>&nbsp;</td>
				<td class="actions">
					<?php
					echo $this->Html->link(__('Ver'), array('action' => 'view', $grupo['Grupo']['id']));
					?>
					<?php
					if($grupo['Grupo']['id'] != 1 & $grupo['Grupo']['id'] != 2)
						echo $this->Html->link(__('Modificar'), array('action' => 'edit', $grupo['Grupo']['id']));
					?>
					<?php
					if($grupo['Grupo']['id'] != 1 & $grupo['Grupo']['id'] != 2)
						echo $this->Form->postLink(__('Eliminar'), array('action' => 'delete', $grupo['Grupo']['id']), null, __('Are you sure you want to delete # %s?', $grupo['Grupo']['id']));
					?>
				</td>
			</tr>
		<?php endforeach; ?>
	</table>
	<?php echo $this->element('admin/paginator'); ?>
</div>