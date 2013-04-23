<div class="colecciones ordenar">
	<h2><?php echo __('Ordenar Campos'); ?></h2>
	<fieldset class="campos">
		<legend><?php echo __('Campos de la colección'); ?></legend>
		<table id="sortable" class="coleccion crear campos">
			<thead>
			<tr class="ui-state-disabled">
				<th class="nombre">Nombre</th>
				<th class="tipo">Tipo</th>
			</tr>
			</thead>
			<tbody id="CamposColeccion">
			<?php if(isset($this->request->data['Campo'])) : ?>
				<?php foreach($this->request->data['Campo'] as $campo_id => $campo) : ?>
					<?php
					$relationship = 0;
					$element = 0;
					if($campo['campo_id']) {
						$relationship = $campo['campo_id'];
						$element = 1;
					} else {
						$relationship = $campo['id'];
						if($campo['coleccion_id']) {
							$element = 1;
						}
					}
					?>
					<tr id="<?php echo $campo['id']; ?>" class="ui-state-default campo-<?php echo $campo_id; ?>" relationship="<?php echo $relationship; ?>" element="<?php echo $element; ?>">
						<?php $uid = uniqid(); ?>
						<?php echo $this->Form->hidden("Campo.$campo_id.id"); ?>
						<?php echo $this->Form->hidden("Campo.$campo_id.posicion", array('class' => 'posicion')); ?>
						<td class="nombre">
							<?php
								echo $this->Form->hidden("Campo.$campo_id.nombre", array('label' => false, 'div' => false, 'class' => 'campo-nombre'));
								echo $campo['nombre'];
							?>
						</td>
						<td class="tipo">
							<?php
								echo $this->Form->hidden("Campo.$campo_id.tipos_de_campo_id", array('empty' => 'Seleccione...', 'label' => false, 'div' => false, 'class' => "tipo-campo-$uid"));
								echo $campo['TiposDeCampo']['nombre'];
							?>
							<?php
								if($campo['coleccion_id']) {
									echo $this->Form->hidden("Campo.$campo_id.coleccion_id", array('empty' => 'Seleccione...', 'class' => "elementos-$uid", 'div' => array('class' => "input select elemento-$uid")));
									echo '<br />' . '<br />' . $campo['Coleccion']['nombre'];
								}
							?>
							<?php
								if($campo['tipos_de_campo_id'] == 3) {
									echo $this->Form->hidden("Campo.$campo_id.extensiones", array('class' => "extensiones-$uid", 'div' => array('class' => "input text div-ext-$uid"), 'placeholder' => 'ext1, ext2, ..., extN'));
									echo '<br />' . '<br />' . $campo['extensiones'];
								}
							?>
							<?php
								if($campo['tipos_de_campo_id'] == 5) {
									echo $this->Form->hidden("Campo.$campo_id.lista_predefinida", array('class' => "lista-$uid", 'placeholder' => 'Una opción por línea', 'style' => 'height: 75px', 'div' => array('class' => "input textarea div-lista-$uid")));
									$textParts = explode("\n", $campo['lista_predefinida']);
									echo '<br />';
									foreach($textParts as $key => $text) {
										echo '<br />' . trim($text);
									}
								}
							?>
							<?php
								if(isset($campo['campo_id'])) {
									echo $this->Form->hidden("Campo.$campo_id.campo_id");
								}
							?>
						</td>
						<script type="text/javascript">
							$(function() {
								var campoReq = $('.req-<?php echo $uid; ?>'), campoEle = $('.elementos-<?php echo $uid; ?>'), divEle = $('.elemento-<?php echo $uid; ?>'), divLista = $('.div-lista-<?php echo $uid; ?>'), campoLista = $('.lista-<?php echo $uid; ?>'), divExt = $('.div-ext-<?php echo $uid; ?>'), campoExt = $('.extensiones-<?php echo $uid; ?>'), campoTipo = $('.tipo-campo-<?php echo $uid; ?>'), eliminarCampo = $('.remover-campo-<?php echo $uid; ?>');

								function verificarTipo() {
									if(campoTipo.val() == 3) {
										divExt.css('display', 'inline');
									} else {
										campoExt.val('');
										divExt.css('display', 'none');
									}
									if(campoTipo.val() == 5) {
										divLista.css('display', 'inline');
									} else {
										campoLista.val('');
										divLista.css('display', 'none');
									}
									if(campoTipo.val() == 8) {
										divEle.css('display', 'inline');
										campoReq.prop('disabled', true);
									} else {
										campoEle.val('');
										campoReq.prop('disabled', false);
										divEle.css('display', 'none');
									}
								}

								verificarTipo();
								campoTipo.change(function() {
									verificarTipo();
								});
								eliminarCampo.click(function() {
									id = eliminarCampo.parent().parent().attr('relationship');
									$.each($('tr.ui-state-default'), function(i, k) {
										row = $(k);
										if(row.attr('relationship') == id) {
											row.remove();
										}
									});
								});
							});
						</script>
					</tr>
				<?php endforeach; ?>
			<?php endif; ?>
			</tbody>
		</table>
	</fieldset>
</div>
<script type="text/javascript">
	$(function() {
		$('.campos').css('max-height', $('.info').height());
	});
</script>
<script type="text/javascript">
	$(function() {// Posicionamiento de los campos
		$('#sortable').sortable({
			revert: true,
			items : "tr:not(.ui-state-disabled)",
			update: function(event, ui) {
				info = $(this).sortable("toArray");
				data = {};
				for( i = 0; i < info.length; i += 1) {
					data["data[Campo][" + info[i] + "]"] = (i + 1);
				}
				$.ajax({
					url: '/campos/ordenar',
					data: data,
					cache: false,
					async: false,
					dataType: 'json',
					success: function(response) {
						if(response.success) {
							for( i = 0; i < info.length; i += 1) {
								$('tr#' + info[i]).children('.posicion').val(i + 1);
							}
						} else {
							alert('Error al tratar de reordenar los elementos');
						}
					}
				});
			}
		});
	});
</script>