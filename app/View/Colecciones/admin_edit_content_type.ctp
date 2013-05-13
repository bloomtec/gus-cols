<div class="colecciones form">
	<?php echo $this->Form->create('Coleccion'); ?>
	<h2><?php echo __('Modificar Base De Contenido'); ?></h2>
	<fieldset class="info">
		<legend><?php echo __('Datos de la colección'); ?></legend>
		<div class="crear-col-sec-1">
			<?php echo $this->Form->input('id'); ?>
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
						<?php
							if(isset($this->request->data['Grupo'][$grupo_id]['id'])) {
								echo $this->Form->hidden("Grupo.$grupo_id.id");
							}
						?>
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
		<h4><?php echo __('IMPORTANTE :: Recuerde verificar el orden de los campos luego de realizar una modificación'); ?></h4>
		<table id="sortable" class="coleccion crear campos">
			<thead>
			<tr class="ui-state-disabled">
				<th class="nombre">Nombre</th>
				<th class="tipo">Tipo</th>
				<th>Requerido</th>
				<th>Acciones</th>
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
						<?php echo $this->Form->hidden("Campo.$campo_id.usuario_id"); ?>
						<td class="nombre">
							<?php
								if(isset($campo['campo_id']) || $campo['tipos_de_campo_id'] == 8) {
									echo $this->Form->hidden("Campo.$campo_id.nombre", array('label' => false, 'div' => false, 'class' => 'campo-nombre'));
									echo $campo['nombre'];
								} else {
									echo $this->Form->input("Campo.$campo_id.nombre", array('label' => false, 'div' => false, 'class' => 'campo-nombre'));
								}
							?>
						</td>
						<td class="tipo">
							<?php
								if(isset($campo['campo_id']) || $campo['tipos_de_campo_id'] == 8) {
									echo $this->Form->hidden("Campo.$campo_id.tipos_de_campo_id", array('empty' => 'Seleccione...', 'label' => false, 'div' => false, 'class' => "tipo-campo-$uid"));
									echo $campo['TiposDeCampo']['nombre'];
								} else {
									echo $this->Form->input("Campo.$campo_id.tipos_de_campo_id", array('empty' => 'Seleccione...', 'label' => false, 'div' => false, 'class' => "tipo-campo-$uid"));
								}
							?>
							<?php
								if($campo['coleccion_id']) {
									echo $this->Form->hidden("Campo.$campo_id.coleccion_id", array('empty' => 'Seleccione...', 'class' => "elementos-$uid", 'div' => array('class' => "input select elemento-$uid")));
									echo '<br />' . '<br />' .  $campo['Coleccion']['nombre'];
								} else {
									echo $this->Form->input("Campo.$campo_id.coleccion_id", array('empty' => 'Seleccione...', 'class' => "elementos-$uid", 'div' => array('class' => "input select elemento-$uid")));
								}
							?>
							<?php
								if($campo['tipos_de_campo_id'] == 3 && $campo['campo_id']) {
									echo $this->Form->hidden("Campo.$campo_id.extensiones", array('class' => "extensiones-$uid", 'div' => array('class' => "input text div-ext-$uid"), 'placeholder' => 'ext1, ext2, ..., extN'));
									echo '<br />' . '<br />' . $campo['extensiones'];
								} else {
									echo $this->Form->input("Campo.$campo_id.extensiones", array('class' => "extensiones-$uid", 'div' => array('class' => "input text div-ext-$uid"), 'placeholder' => 'ext1, ext2, ..., extN'));
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
								} else {
									echo $this->Form->input("Campo.$campo_id.lista_predefinida", array('class' => "lista-$uid", 'placeholder' => 'Una opción por línea', 'style' => 'height: 75px', 'div' => array('class' => "input textarea div-lista-$uid")));
								}
							?>
							<?php
								if(isset($campo['campo_id'])) {
									echo $this->Form->hidden("Campo.$campo_id.campo_id");
								}
							?>
						</td>
						<td class="requerido">
							<?php
								if(isset($campo['campo_id']) || $campo['tipos_de_campo_id'] == 8) {
									echo $this->Form->hidden("Campo.$campo_id.es_requerido", array('class' => "req-$uid", 'label' => false, 'div' => false));
								} else {
									echo $this->Form->input("Campo.$campo_id.es_requerido", array('class' => "req-$uid", 'label' => false, 'div' => false));
								}
							?>
						</td>
						<td class="actions">
							<?php if(!$campo['campo_id'] && empty($campo['Hijos'])) : ?>
							<a class="remover-campo-<?php echo $uid; ?>">Eliminar</a>
							<script>
								$(function() {
									$('.remover-campo-<?php echo $uid; ?>').click(function() {
										if(confirm('¿Seguro desea eliminar el campo <?php echo $campo['nombre']; ?>?')) {
											$.ajax({
												url: '/campos/eliminar',
												data: { id : <?php echo $campo['id']; ?> },
												cache: false,
												async: false,
												dataType: 'json',
												success: function(response) {
													if(response.success) {
														alert('Se ha eliminado el campo');
													} else {
														alert('No se pudo eliminar el campo');
													}
												}
											});
										}
									});
								});
							</script>
							<?php endif; ?>
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
		<div class="actions">
			<a id="AgregarCampo" href="#">Agregar campo</a>
		</div>
	</fieldset>
	<?php echo $this->Form->end(__('Modificar')); ?>
</div>
<script type="text/javascript">
	$(function() {
		$('.campos').css('max-height', $('.info').height());
	});
</script>