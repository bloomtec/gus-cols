<div class="logs view">
<h2><?php  echo __('Registro'); ?></h2>
	<dl>
		<dt><?php echo __('Usuario Que Realiza La Acción'); ?></dt>
		<dd>
			<?php echo $this->Html->link($log['Usuario']['documento'], array('controller' => 'usuarios', 'action' => 'view', $log['Usuario']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modelo'); ?></dt>
		<dd>
			<?php echo h($log['Log']['model']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('ID'); ?></dt>
		<dd>
			<?php echo h($log['Log']['foreign_key']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Dato Previo'); ?></dt>
		<dd>
			<?php echo $log['Log']['dato_previo']; ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Dato Nuevo'); ?></dt>
		<dd>
			<?php echo $log['Log']['dato_nuevo']; ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Crear'); ?></dt>
		<dd>
			<?php
				if($log['Log']['add']) {
					echo '<input type="checkbox" checked disabled />';
				} else {
					echo '<input type="checkbox" disabled />';
				}
			?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modificar'); ?></dt>
		<dd>
			<?php
				if($log['Log']['edit']) {
					echo '<input type="checkbox" checked disabled />';
				} else {
					echo '<input type="checkbox" disabled />';
				}
			?>
			&nbsp;
		</dd>
		<dt><?php echo __('Eliminar'); ?></dt>
		<dd>
			<?php
				if($log['Log']['delete']) {
					echo '<input type="checkbox" checked disabled />';
				} else {
					echo '<input type="checkbox" disabled />';
				}
			?>
			&nbsp;
		</dd>
		<dt><?php echo __('Fecha De Registro'); ?></dt>
		<dd>
			<?php echo h($log['Log']['created']); ?>
			&nbsp;
		</dd>
	</dl>
</div>