<div class="colecciones form">
	<?php echo $this->Form->create('Coleccion'); ?>
	<h2><?php echo __('Crear Coleccion'); ?></h2>
	<fieldset class="info">
		<legend><?php echo __('Datos de la colección'); ?></legend>
		<div class="crear-col-sec-1">
			<?php echo $this->Form->input('nombre'); ?>
		</div>
		<div class="crear-col-sec-2">
			<?php
			echo $this->Form->input('es_auditable');
			echo $this->Form->input('grupo_id', array('label' => 'Grupo auditor', 'empty' => 'Seleccione el grupo auditor'));
			?>
		</div>
		<div class="crear-col-sec-3">
			<?php echo $this->Form->input('acceso_anonimo'); ?>
			<table class="coleccion crear grupos">
				<thead>
				<tr>
					<th class="grupo">Grupo</th>
					<th class="permiso">Acceso</th>
					<th class="permiso">Crear</th>
				</tr>
				</thead>
				<tbody>
				<?php foreach($grupos as $grupo_id => $grupo) : ?>
					<tr>
						<td class="grupo"><?php echo $grupo; ?></td>
						<td class="permiso"><?php echo $this->Form->input("Grupo.$grupo_id.acceso", array('type' => 'checkbox', 'label' => false, 'div' => false)); ?></td>
						<td class="permiso"><?php echo $this->Form->input("Grupo.$grupo_id.crear", array('type' => 'checkbox', 'label' => false, 'div' => false)); ?></td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
			<?php //echo $this->Form->input('Grupo'); ?>
		</div>
		<?php echo $this->Form->hidden('usuario_id'); ?>
	</fieldset>
	<fieldset class="campos">
		<legend><?php echo __('Campos de la colección'); ?></legend>
		<table class="coleccion crear campos">
			<thead>
				<tr>
					<th class="nombre">Nombre</th><th>Tipo</th><th>Requerido</th>
				</tr>
			</thead>
			<tbody id="CamposColeccion">
			</tbody>
		</table>
		<div class="actions">
			<a id="AgregarCampo" href="#">Agregar campo</a>
		</div>
	</fieldset>
	<?php echo $this->Form->end(__('Crear')); ?>
</div>