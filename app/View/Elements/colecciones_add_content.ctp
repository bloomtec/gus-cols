<?php
	if($ct_id) {
		$uid = uniqid();
		$nombre = $uid;
		$auditable = $this->request->data['Coleccion']['es_auditable'] ? 1 : 0;
		$anonimo = $this->request->data['Coleccion']['acceso_anonimo'] ? 1 : 0;
		$publicada = $auditable ? 0 : 1;
		//mkdir(WWW_ROOT . 'files' . DS . $this->request->data['Coleccion']['nombre'] . DS . $uid, 0777);
		mkdir(WWW_ROOT . 'files' . DS . $ct_id . DS . $uid, 0777);
		?>
		<div class="colecciones form">
			<?php echo $this->Form->create('Coleccion', array('controller' => 'colecciones', 'action' => 'add', $ct_id)); ?>
			<fieldset>
				<legend><?php echo $this->request->data['Coleccion']['nombre']; ?></legend>
				<h3><?php echo __('Nombre interno: ' . $nombre); ?></h3>
				<?php echo $this->Form->hidden('nombre', array('value' => $nombre)); ?>
				<?php echo $this->Form->hidden('coleccion_id', array('value' => $this->request->data['Coleccion']['id'])); ?>
				<?php echo $this->Form->hidden('es_auditable', array('value' => $auditable)); ?>
				<?php echo $this->Form->hidden('acceso_anonimo', array('value' => $anonimo)); ?>
				<?php echo $this->Form->hidden('publicada', array('value' => $publicada)); ?>
				<?php echo $this->Form->hidden('grupo_id', array('value' => $this->request->data['Coleccion']['grupo_id'])); ?>
				<?php echo $this->Form->hidden('es_tipo_de_contenido', array('value' => 0)); ?>
				<?php foreach($this->request->data['CamposColeccion'] as $key => $campo) : ?>
					<div class="campos" campo_id="<?php echo $campo['id']; ?>"></div>
				<?php endforeach; ?>
			</fieldset>
			<?php echo $this->Form->end(__('Enviar')); ?>
		</div>
		<script type="text/javascript" language="JavaScript">
			$(function() {
				var campos = $('.campos').add(), count = campos.length;
				campos.each(function(key, node) {
					var div = $(node);
					div.load('/colecciones/add_campo_form_contenido/' + div.attr('campo_id') + '/' + '<?php echo urlencode($this->request->data['Coleccion']['nombre']); ?>' + '/' + '<?php echo $uid; ?>' + '/' + key + '/' + <?php echo $ct_id; ?>);
					count--;
					if(!count) {
						//Agregar la validación del form
						//$('#ColeccionAddForm').validator();
					}
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