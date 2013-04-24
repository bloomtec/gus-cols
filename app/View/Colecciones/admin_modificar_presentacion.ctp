<div class="colecciones presentacion">
	<?php echo $this->Form->create('Coleccion'); ?>
	<h2><?php echo __('Modificar Presentación'); ?></h2>
	<fieldset class="campos">
		<legend><?php echo __('Campos de la colección'); ?></legend>
		<table id="sortable" class="coleccion crear campos">
			<thead>
			<tr class="ui-state-disabled">
				<th class="posicion">Posición</th>
				<th class="nombre">Nombre</th>
				<th class="tipo">Tipo</th>
				<th class="listado">Listado</th>
				<th class="unico">Único</th>
				<th class="filtro">Filtro</th>
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
						<?php echo $this->Form->hidden("Campo.$campo_id.posicion", array('class' => 'position')); ?>
						<td class="posicion"><?php echo $this->request->data['Campo'][$campo_id]['posicion']; ?></td>
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
						<td class="listado"><?php echo $this->Form->input("Campo.$campo_id.listado", array('label' => false, 'div' => false)); ?></td>
						<td class="unico"><?php echo $this->Form->input("Campo.$campo_id.unico", array('label' => false, 'div' => false)); ?></td>
						<td class="filtro">
							<?php
								if(in_array($this->request->data['Campo'][$campo_id]['tipos_de_campo_id'], array(2, 5, 6, 7))) {
									echo $this->Form->input("Campo.$campo_id.filtro", array('label' => false, 'div' => false));
								}
							?>
						</td>
					</tr>
				<?php endforeach; ?>
			<?php endif; ?>
			</tbody>
		</table>
	</fieldset>
	<?php echo $this->Form->end('Modificar'); ?>
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
				for( i = 0; i < info.length; i += 1) {
					$('tr#' + info[i]).children('.position').val(i + 1);
					$('tr#' + info[i]).children('.posicion').text(i + 1);
				}
				/*data = {};
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
								$('tr#' + info[i]).children('.position').val(i + 1);
							}
						} else {
							alert('Error al tratar de reordenar los elementos');
						}
					}
				});*/
			}
		});
	});
</script>