<div class="usuarios view">
<h2><?php  echo __('Usuario'); ?></h2>
	<dl>
		<dt><?php echo __('Documento'); ?></dt>
		<dd>
			<?php echo h($usuario['Usuario']['documento']); ?>
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
		<dt><?php echo __('Correo'); ?></dt>
		<dd>
			<?php echo h($usuario['Usuario']['correo']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Recibe correos'); ?></dt>
		<dd>
			<?php if($usuario['Usuario']['recibir_correos']) { ?>
				<input type="checkbox" disabled="disabled" checked="checked">
			<?php } else { ?>
				<input type="checkbox" disabled="disabled">
			<?php } ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Activo'); ?></dt>
		<dd>
			<?php if($usuario['Usuario']['activo']) { ?>
				<input type="checkbox" disabled="disabled" checked="checked">
			<?php } else { ?>
				<input type="checkbox" disabled="disabled">
			<?php } ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Creado'); ?></dt>
		<dd>
			<?php echo h($usuario['Usuario']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modificado'); ?></dt>
		<dd>
			<?php echo h($usuario['Usuario']['modified']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="related">
	<h3><?php echo __('Grupos'); ?></h3>
	<?php if (!empty($usuario['Grupo'])): ?>
		<table cellpadding = "0" cellspacing = "0">
			<tr>
				<th><?php echo __('Nombre'); ?></th>
				<th><?php echo __('Creado'); ?></th>
				<th><?php echo __('Modificado'); ?></th>
			</tr>
			<?php
				$i = 0;
				foreach ($usuario['Grupo'] as $grupo): ?>
					<tr>
						<td><?php echo $grupo['nombre']; ?></td>
						<td><?php echo $grupo['created']; ?></td>
						<td><?php echo $grupo['modified']; ?></td>
					</tr>
				<?php endforeach; ?>
		</table>
	<?php endif; ?>
</div>
<div class="related">
	<h3><?php echo __('Auditorias'); ?></h3>
	<?php if (!empty($usuario['Auditoria'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Modelo'); ?></th>
		<th><?php echo __('ID'); ?></th>
		<th><?php echo __('Colección Aprobada'); ?></th>
		<th><?php echo __('Observación'); ?></th>
		<th><?php echo __('Creado'); ?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($usuario['Auditoria'] as $auditoria): ?>
		<tr>
			<td><?php echo $auditoria['model']; ?></td>
			<td><?php echo $auditoria['foreign_key']; ?></td>
			<?php /*<td><?php echo $auditoria['colección_aprobada']; ?></td>*/ ?>
			<td>
				<?php if($auditoria['colección_aprobada']) { ?>
					<input type="checkbox" disabled="disabled" checked="checked">
				<?php } else { ?>
					<input type="checkbox" disabled="disabled">
				<?php } ?>
			</td>
			<td><?php echo $auditoria['observación']; ?></td>
			<td><?php echo $auditoria['created']; ?></td>
		</tr>
	<?php endforeach; ?>
	</table>
	<?php endif; ?>
</div>
<div class="related">
	<h3><?php echo __('Colecciones'); ?></h3>
	<?php if (!empty($usuario['Coleccion'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('ID'); ?></th>
		<th><?php echo __('Nombre'); ?></th>
		<th><?php echo __('Nombre interno'); ?></th>
		<th><?php echo __('Es Auditable'); ?></th>
		<th><?php echo __('Creado'); ?></th>
		<th><?php echo __('Modificado'); ?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($usuario['Coleccion'] as $coleccion): ?>
		<tr>
			<td><?php echo $coleccion['id']; ?></td>
			<td>
				<?php
					if(!empty($coleccion['TipoDeContenido'])) {
						echo $coleccion['TipoDeContenido']['nombre'];
					} else {
						echo $coleccion['nombre'];
					}
				?>
			</td>
			<td><?php echo $coleccion['nombre']; ?></td>
			<td>
				<?php if($coleccion['es_auditable']) { ?>
					<input type="checkbox" disabled="disabled" checked="checked">
				<?php } else { ?>
					<input type="checkbox" disabled="disabled">
				<?php } ?>
			</td>
			<td><?php echo $coleccion['created']; ?></td>
			<td><?php echo $coleccion['modified']; ?></td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>
</div>