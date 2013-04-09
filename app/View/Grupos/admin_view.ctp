<div class="grupos view">
	<h2><?php  echo __('Grupo'); ?></h2>
	<dl>
		<dt><?php echo __('Nombre'); ?></dt>
		<dd>
			<?php echo h($grupo['Grupo']['nombre']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Creado'); ?></dt>
		<dd>
			<?php echo h($grupo['Grupo']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modificado'); ?></dt>
		<dd>
			<?php echo h($grupo['Grupo']['modified']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="related">
	<h3><?php echo __('Usuarios'); ?></h3>
	<?php if(!empty($grupo['Usuario'])): ?>
		<table cellpadding="0" cellspacing="0">
			<tr>
				<th><?php echo __('Documento'); ?></th>
				<th><?php echo __('Nombres'); ?></th>
				<th><?php echo __('Apellidos'); ?></th>
				<th><?php echo __('Activo'); ?></th>
				<th><?php echo __('Creado'); ?></th>
				<th><?php echo __('Modificado'); ?></th>
			</tr>
			<?php
			$i = 0;
			foreach($grupo['Usuario'] as $usuario): ?>
				<tr>
					<td><?php echo $usuario['documento']; ?></td>
					<td><?php echo $usuario['nombres']; ?></td>
					<td><?php echo $usuario['apellidos']; ?></td>
					<td>
						<?php if($usuario['activo']) { ?>
							<input type="checkbox" disabled="disabled" checked="checked">
						<?php } else { ?>
							<input type="checkbox" disabled="disabled">
						<?php } ?>
					</td>
					<td><?php echo $usuario['created']; ?></td>
					<td><?php echo $usuario['modified']; ?></td>
				</tr>
			<?php endforeach; ?>
		</table>
	<?php endif; ?>
</div>