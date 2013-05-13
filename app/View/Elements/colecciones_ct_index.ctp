<div class="colecciones index">
	<h2><?php echo __('Colecciones'); ?></h2>
	<table cellpadding="0" cellspacing="0">
		<tr>
			<th><?php echo $this->Paginator->sort('nombre'); ?></th>
			<th><?php echo $this->Paginator->sort('created', 'Creada'); ?></th>
			<th class="actions"><?php echo __('Acciones'); ?></th>
		</tr>
		<?php foreach($colecciones as $coleccion): ?>
			<tr>
				<td><?php echo h($coleccion['Coleccion']['nombre']); ?>&nbsp;</td>
				<td><?php echo h($coleccion['Coleccion']['created']); ?>&nbsp;</td>
				<td class="actions">
					<?php
						if($this->requestAction('/colecciones/verificarListar/' . $coleccion['Coleccion']['id'])) {
							echo $this->Html->link(__('Listar'), array('action' => 'index', $coleccion['Coleccion']['id']));
						} else {
							echo '<a>No hay ítems listados</a>';
						}
					?>
					<?php
						$user_id = $this->Session->read('Auth.User.id');
						if($user_id && $this->requestAction('/colecciones/verificarCrear/' . $user_id . '/' . $coleccion['Coleccion']['id'])) {
							echo $this->Html->link(__('Crear'), array('action' => 'add', $coleccion['Coleccion']['id']));
						}
						if($user_id && $this->requestAction('/colecciones/verificarModificar/' . $user_id . '/' . $coleccion['Coleccion']['id'])) {
							echo $this->Html->link(__('Cambiar Presentación'), array('action' => 'modificar_presentacion', $coleccion['Coleccion']['id']));
							echo $this->Html->link(__('Modificar'), array('action' => 'edit_content_type', $coleccion['Coleccion']['id']));
						}
						if($user_id && $this->requestAction('/colecciones/verificarEliminar/' . $user_id . '/' . $coleccion['Coleccion']['id'])) {
							echo $this->Html->link(__('Eliminar'), array('action' => 'delete', $coleccion['Coleccion']['id']));
						}
						if($this->requestAction('/colecciones/esAuditable/' . $coleccion['Coleccion']['id'] . '/1')) {
							echo $this->Html->link(__('Contenido Para Auditar'), array('action' => 'index', $coleccion['Coleccion']['id'], 1));
						}
						if($this->requestAction('/colecciones/verificarContenidoARevisar/' . $coleccion['Coleccion']['id'])) {
							echo $this->Html->link(__('Contenido Para Revisar'), array('action' => 'index', $coleccion['Coleccion']['id'], 0, 1));
						}
					?>
				</td>
			</tr>
		<?php endforeach; ?>
	</table>
	<?php echo $this->element('paginator'); ?>
</div>