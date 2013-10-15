<?php
$uid = $this->request->data['Coleccion']['nombre'];
$nombre = $uid;
$auditable = $this->request->data['Coleccion']['es_auditable'] ? 1 : 0;
$anonimo = $this->request->data['Coleccion']['acceso_anonimo'] ? 1 : 0;
$publicada = $auditable ? 0 : 1;
?>
<div class="colecciones form">
	<?php echo $this->Form->create('Coleccion', array('controller' => 'colecciones', 'action' => 'edit', $this->request->data['Coleccion']['id'])); ?>
	<fieldset>
		<legend><?php echo $uid; ?></legend>
		<h3><?php echo __('Nombre interno: ' . $nombre); ?></h3>
		<?php echo $this->Form->hidden('id', array('value' => $this->request->data['Coleccion']['id'])); ?>
		<?php echo $this->Form->hidden('nombre', array('value' => $nombre)); ?>
		<?php echo $this->Form->hidden('coleccion_id', array('value' => $this->request->data['Coleccion']['coleccion_id'])); ?>
		<?php echo $this->Form->hidden('es_auditable', array('value' => $auditable)); ?>
		<?php echo $this->Form->hidden('acceso_anonimo', array('value' => $anonimo)); ?>
		<?php echo $this->Form->hidden('publicada', array('value' => $publicada)); ?>
		<?php echo $this->Form->hidden('grupo_id', array('value' => $this->request->data['Coleccion']['grupo_id'])); ?>
		<?php echo $this->Form->hidden('es_tipo_de_contenido', array('value' => 0)); ?>
		<?php
			foreach($coleccionBase['CamposColeccion'] as $keyBase => $campoBase) {
				$elCampo = $campoBase;
				foreach($this->request->data['CamposColeccion'] as $keyCampo => $campo) {
					if($campo['campo_padre'] === $campoBase['id']) {
						$elCampo = $campo;
						break;
					}
				}
		?>
				<div class="campos" campo_id="<?php echo $elCampo['id']; ?>"></div>
		<?php
			}
		?>
	</fieldset>
	<?php echo $this->Form->end(__('Enviar')); ?>
</div>
	<script type="text/javascript" language="JavaScript">
	$(function() {
		var campos = $('.campos').add(), count = campos.length;
		campos.each(function(key, node) {
			var div = $(node);
			div.load('/colecciones/add_campo_form_contenido/' + div.attr('campo_id') + '/' + '<?php echo urlencode($coleccionBase['Coleccion']['nombre']); ?>' + '/' + '<?php echo $uid; ?>' + '/' + key + '/' + <?php echo $coleccionBase['Coleccion']['id']; ?>);
			count--;
			if(!count) {
				//Agregar la validación del form
				//$('#ColeccionEditForm').validator();
			}
		});
	});
</script>
<?php if(!empty($this->request->data['Auditoria']) && !$this->request->data['Coleccion']['publicada']) : ?>
	<div class="colecciones auditorias">
		<table>
			<caption><h4>Historial de revisiones</h4></caption>
			<tr>
				<th>Auditor</th>
				<th>Aprobado</th>
				<th>Fecha Revision</th>
				<th>Comentario</th>
			</tr>
			<?php foreach($this->request->data['Auditoria'] as $key => $auditoria) : ?>
				<tr>
					<td><?php echo $auditoria['Usuario']['documento'] . ' :: ' . $auditoria['Usuario']['nombres'] . ' ' . $auditoria['Usuario']['apellidos']; ?></td>
					<td>
						<?php if($auditoria['colección_aprobada']) { ?>
							<input type="checkbox" disabled="disabled" checked="checked">
						<?php } else { ?>
							<input type="checkbox" disabled="disabled">
						<?php } ?>
					</td>
					<td><?php echo $auditoria['created']; ?></td>
					<td><?php echo $auditoria['observación']; ?></td>
				</tr>
			<?php endforeach; ?>
		</table>
	</div>
<?php endif; ?>