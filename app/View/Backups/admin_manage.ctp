<div class="backups index">
	<h2>Respaldos de la base de datos</h2>
	<h4>Las copias de seguridad se muestran en orden de más reciente a más antiguo.</h4>
	<h4>El manejo aquí dado no afecta el directorio de archivos.</h4>
	<table>
		<thead>
		<th><?php echo 'Archivo'; //$this -> paginator -> sort('Archivo.filename', 'Archivo'); ?></th>
		<th><?php echo 'Fecha Y Hora'; //$this -> paginator -> sort('Archivo.created', 'Fecha Y Hora'); ?></th>
		<th>Acciones</th>
		</thead>
		<tbody>
		<?php foreach ($archivos as $key => $archivo): ?>
			<tr>
				<td><?php echo $archivo['filename']; ?></td>
				<td><?php echo $archivo['created']; ?></td>
				<td class="actions">
					<?php echo $this -> Html -> link(__('Descargar', true), array('action' => 'download', $archivo['filename'])); ?>
					<?php echo $this -> Html -> link(__('Restaurar', true), array('action' => 'restoreFromBackup', $archivo['filename'])); ?>
				</td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
	<?php echo $this->element('paginator'); ?>
</div>
<div class="actions" style="width: 98%;">
	<?php echo $this -> Html -> link(__('Vaciar la tabla de registros de la base de datos', true), array('action' => 'clearLog')); ?>
	<?php echo $this -> Html -> link(__('Crear un respaldo de la base de datos', true), array('action' => 'createBackup')); ?>
</div>