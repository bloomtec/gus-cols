<div class="colecciones view">
	<h2><?php  echo __('Base de contenido'); ?></h2>
	<dl>
		<dt><?php echo __('Creado por'); ?></dt>
		<dd>
			<?php echo $this->Html->link($coleccion['Usuario']['documento'], array('controller' => 'usuarios', 'action' => 'view', $coleccion['Usuario']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Nombre'); ?></dt>
		<dd>
			<?php echo h($coleccion['Coleccion']['nombre']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Es Auditable'); ?></dt>
		<dd>
			<?php if($coleccion['Coleccion']['es_auditable']) { ?>
				<input type="checkbox" disabled="disabled" checked="checked">
			<?php } else { ?>
				<input type="checkbox" disabled="disabled">
			<?php } ?>
			&nbsp;
		</dd>
		<?php if($coleccion['Coleccion']['es_auditable']) { ?>
		<dt><?php echo __('Grupo Auditor'); ?></dt>
		<dd>
			<?php echo $this->Html->link($coleccion['Grupo']['nombre'], array('controller' => 'grupos', 'action' => 'view', $coleccion['Grupo']['id'])); ?>
			&nbsp;
		</dd>
		<?php } ?>
		<dt><?php echo __('Acceso Anónimo'); ?></dt>
		<dd>
			<?php if($coleccion['Coleccion']['acceso_anonimo']) { ?>
				<input type="checkbox" disabled="disabled" checked="checked">
			<?php } else { ?>
				<input type="checkbox" disabled="disabled">
			<?php } ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Creado'); ?></dt>
		<dd>
			<?php echo h($coleccion['Coleccion']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modificado'); ?></dt>
		<dd>
			<?php echo h($coleccion['Coleccion']['modified']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="related grupos">
	<h3><?php echo __('Grupos con permisos'); ?></h3>
	<?php if(!empty($coleccion['Grupo'])): ?>
		<table cellpadding="0" cellspacing="0">
			<tr>
				<th><?php echo __('Nombre'); ?></th>
				<th><?php echo __('Acceso'); ?></th>
				<th><?php echo __('Creación'); ?></th>
			</tr>
			<?php
			$i = 0;
			foreach($coleccion['Grupo'] as $grupo): ?>
				<?php if(is_array($grupo) && $grupo['id'] != 2) : ?>
				<tr>
					<td><?php echo $grupo['nombre']; ?></td>
					<td>
						<?php if($grupo['ColeccionesGrupo']['acceso']) { ?>
							<input type="checkbox" disabled="disabled" checked="checked">
						<?php } else { ?>
							<input type="checkbox" disabled="disabled">
						<?php } ?>
					</td>
					<td>
						<?php if($grupo['ColeccionesGrupo']['creación']) { ?>
							<input type="checkbox" disabled="disabled" checked="checked">
						<?php } else { ?>
							<input type="checkbox" disabled="disabled">
						<?php } ?>
					</td>
				</tr>
				<?php endif; ?>
			<?php endforeach; ?>
		</table>
	<?php endif; ?>
</div>
<div class="related campos">
	<h3><?php echo __('Campos'); ?></h3>
	<?php if(!empty($coleccion['CamposColeccion'])): ?>
		<table cellpadding="0" cellspacing="0">
			<tr>
				<th><?php echo __('Nombre'); ?></th>
				<th><?php echo __('Tipo de campo'); ?></th>
				<th><?php echo __('Es requerido'); ?></th>
			</tr>
			<?php
			$i = 0;
			foreach($coleccion['CamposColeccion'] as $campo): ?>
				<?php if(is_array($campo) && $campo['id'] != 2) : ?>
					<tr>
						<td><?php echo $campo['nombre']; ?></td>
						<td><?php echo $campo['TiposDeCampo']['nombre']; ?></td>
						<td>
							<?php if($campo['es_requerido']) { ?>
								<input type="checkbox" disabled="disabled" checked="checked">
							<?php } else { ?>
								<input type="checkbox" disabled="disabled">
							<?php } ?>
						</td>
					</tr>
				<?php endif; ?>
			<?php endforeach; ?>
		</table>
	<?php endif; ?>
</div>