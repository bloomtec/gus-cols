<?php
	if($ct_id) {
		$uid = uniqid();
		//$nombre = $this->request->data['Coleccion']['nombre'] . ' ' . $uid;
		$nombre = $uid;
		$auditable = $this->request->data['Coleccion']['es_auditable'] ? 1 : 0;
		mkdir(WWW_ROOT . 'files' . DS . $this->request->data['Coleccion']['nombre'] . DS . $uid, 0777);
?>
	<div class="colecciones form">
		<?php echo $this->Form->create('Coleccion'); ?>
		<fieldset>
			<legend><?php echo $this->request->data['Coleccion']['nombre'] . ' :: ' . $nombre; ?></legend>
			<?php echo $this->Form->hidden('nombre', array('value' => $nombre)); ?>
			<?php echo $this->Form->hidden('coleccion_id', array('value' => $this->request->data['Coleccion']['id'])); ?>
			<?php echo $this->Form->hidden('es_auditable', array('value' => $auditable)); ?>
			<?php echo $this->Form->hidden('acceso_anonimo', array('value' => $this->request->data['Coleccion']['acceso_anonimo'])); ?>
			<?php echo $this->Form->hidden('grupo_id', array('value' => $this->request->data['Coleccion']['grupo_id'])); ?>
			<?php echo $this->Form->hidden('es_tipo_de_contenido', array('value' => 0)); ?>
			<?php foreach($this->request->data['CamposColeccion'] as $key => $campo) : ?>
				<div class="campos" campo_id="<?php echo $campo['id']; ?>"></div>
			<?php endforeach; ?>
		</fieldset>
		<?php echo $this->Form->end(__('Enviar')); ?>
	</div>
	<script type="text/javascript">
		$(function() {
			$.each($('.campos'), function(key, node) {
				var div = $(node);
				div.load('/colecciones/add_campo_form_contenido/' + div.attr('campo_id') + '/' + '<?php echo urlencode($this->request->data['Coleccion']['nombre']); ?>' + '/' + '<?php echo urlencode($uid); ?>' + '/' + key);
			});
		});
	</script>
<?php
	} else {
?>
	<div class="colecciones form">
	<?php echo $this->Form->create('Coleccion'); ?>
	<fieldset>
		<legend><?php echo __('Tipos De Contenido Disponibles'); ?></legend>
		<?php echo $this->Form->input('tipo_de_contenido'); ?>
	</fieldset>
	<?php echo $this->Form->end(__('Continuar')); ?>
<?php
	}
?>