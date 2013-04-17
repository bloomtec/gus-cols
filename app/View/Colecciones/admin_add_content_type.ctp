<div class="colecciones form">
	<?php echo $this->Form->create('Coleccion'); ?>
	<h2><?php echo __('Crear Base De Contenido'); ?></h2>
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
			<?php echo $this->Form->input('acceso_anonimo', array('label' => 'Acceso Anónimo')); ?>
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
						<td class="permiso acceso"><?php echo $this->Form->input("Grupo.$grupo_id.acceso", array('type' => 'checkbox', 'label' => false, 'div' => false, 'class' => 'permiso-acceso')); ?></td>
						<td class="permiso"><?php echo $this->Form->input("Grupo.$grupo_id.creación", array('type' => 'checkbox', 'label' => false, 'div' => false, 'class' => 'permiso-creación')); ?></td>
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
					<th class="nombre">Nombre</th><th>Tipo</th><th>Extensión</th><th class="multi">Opciones Lista</th><th>Requerido</th><th>Acciones</th>
				</tr>
			</thead>
			<tbody id="CamposColeccion">
			<?php if(isset($this->request->data['Campo'])) : ?>
			<?php foreach($this->request->data['Campo'] as $campo_id => $campo) : ?>
				<tr class="campo-<?php echo $campo_id; ?>">
					<td class="nombre"><?php echo $this->Form->input("Campo.$campo_id.nombre", array('label' => false, 'div' => false)); ?></td>
					<td><?php echo $this->Form->input("Campo.$campo_id.tipos_de_campo_id", array('empty' => 'Seleccione...', 'label' => false, 'div' => false)); ?></td>
					<td><?php echo $this->Form->input("Campo.$campo_id.es_requerido", array('label' => false, 'div' => false)); ?></td>
				</tr>
			<?php endforeach; ?>
			<?php endif; ?>
			</tbody>
		</table>
		<div class="actions">
			<a id="AgregarCampo" href="#">Agregar campo</a>
		</div>
	</fieldset>
	<?php echo $this->Form->end(__('Crear')); ?>
</div>
<script type="text/javascript">
	$(function() {
		$('.campos').css('max-height', $('.info').height());
	});
</script>