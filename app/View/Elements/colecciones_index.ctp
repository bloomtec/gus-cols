<?php
	// Obtener el ID del usuario (si lo hay)
	$user_id = $this->Session->read('Auth.User.id');
	// Obtener el primer contenido para crear de manera dinámica la tabla y los filtros
	$unContenido = $colecciones[0];
	$filtros = array();
	foreach($unContenido['Campo'] as $key => $campo) {
		if($campo['filtro']) $filtros[] = $campo;
	}
?>
<div class="colecciones index">
	<h2><?php echo __('Listado de: ' . $unContenido['TipoDeContenido']['nombre']); ?></h2>
	<?php if(!empty($filtros)) { ?>
	<?php echo $this->Form->create('Coleccion', array('id' => 'FiltrosForm', 'action' => 'index/' . $unContenido['TipoDeContenido']['id'])); ?>
	<table class="filtro">
		<tr>
			<?php
			foreach($filtros as $key => $campo) {
				echo '<td class="label">' . $campo['nombre'] . '</td>';
				if($campo['tipos_de_campo_id'] == 2) {
					// TEXTO
					echo '<td class="input text">' . $this->Form->input('2.value', array('label' => false, 'div' => false, 'type' => 'text')) . '</td>';
				} elseif($campo['tipos_de_campo_id'] == 5) {
					// LISTA
					$TMPOpciones = explode("\n", $campo['lista_predefinida']);
					$opciones = array();
					foreach($TMPOpciones as $TMPOpcionesKey => $opcion) {
						$opciones[] = trim($opcion);
					}
					echo '<td class="input select">' . $this->Form->input('5.value', array('empty' => 'Seleccione...', 'label' => false, 'div' => false, 'type' => 'select', 'options' => $opciones)) . '</td>';
				} elseif($campo['tipos_de_campo_id'] == 6) {
					// NUMERO
					echo
						'<td class="input number">'
						. $this->Form->input('6.value.min', array('label' => false, 'div' => false, 'type' => 'number'))
						. ' - '
						. $this->Form->input('6.value.max', array('label' => false, 'div' => false, 'type' => 'number'))
						. '</td>';
				} elseif($campo['tipos_de_campo_id'] == 7) {
					// FECHA
					echo
						'<td class="input dates">'
						. $this->Form->input('7.value.min', array('placeholder' => 'aaaa-mm-dd', 'label' => false, 'div' => false, 'type' => 'text', 'class' => 'date'))
						. ' - '
						. $this->Form->input('7.value.max', array('placeholder' => 'aaaa-mm-dd', 'label' => false, 'div' => false, 'type' => 'text', 'class' => 'date'))
						. '</td>';
				}
			}
			?>
			<td class="input submit"><?php echo $this->Form->submit('Filtrar'); ?></td>
			<?php if($filtrado) : ?>
			<td><?php echo $this->Html->link(__('Remover filtro actual'), array('action' => 'removerFiltro', $unContenido['Coleccion']['id'])); ?></td>
			<?php endif; ?>
		</tr>
	</table>
	<?php echo $this->Form->end(); ?>
	<script type="text/javascript">
		$(function() {
			if($(".date")) {
				var currentYear = (new Date).getFullYear();
				var minRange = (currentYear - 100).toString();
				var maxRange = (currentYear + 100).toString();
				$(".date").datepicker({
					dateFormat     : "yy-mm-dd",
					dayNames       : [ "Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado" ],
					dayNamesMin    : [ "Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa" ],
					dayNamesShort  : [ "Dom", "Lun", "Mar", "Mie", "Jue", "Vie", "Sab" ],
					monthNames     : [ "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre" ],
					monthNamesShort: [ "Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic" ],
					changeMonth    : true,
					changeYear     : true,
					yearRange      : minRange + ":" + maxRange
				});
			}
		});
	</script>
	<?php } else { ?>
	<h4>Este listado no tiene filtros definidos</h4>
	<?php } ?>
	<table cellpadding="0" cellspacing="0">
		<tr>
			<?php foreach($unContenido['Campo'] as $key => $campo) : ?>
				<th><?php echo $campo['nombre']; ?></th>
			<?php endforeach; ?>
			<th>Fecha de ingreso</th>
			<th class="actions"><?php echo __('Acciones'); ?></th>
		</tr>
		<?php foreach($colecciones as $coleccion): ?>
			<tr>
			<?php foreach($coleccion['Campo'] as $campo): ?>
				<!--<td><?php //echo h($coleccion['Coleccion']['nombre']); ?>&nbsp;</td>-->
				<?php
				/**
				 * Organizar acorde el tipo de campo
				 */
				if($campo['tipos_de_campo_id'] == 1) {
					//Texto multilínea
					?>
					<td class="dato texto-multilínea"><?php echo $campo['multilinea']; ?></td>
				<?php
				} elseif($campo['tipos_de_campo_id'] == 2) {
					//Texto
					?>
					<td class="dato texto"><?php echo $campo['texto']; ?></td>
				<?php
				} elseif($campo['tipos_de_campo_id'] == 3) {
					//Archivo
					?>
					<td class="dato archivo">
						<?php
							$ct_path = $coleccion['TipoDeContenido']['nombre'];
							$co_path = $coleccion['Coleccion']['nombre'];
							$file = $campo['nombre_de_archivo'];
							$fileName = explode('.', $file);
							$fileExt = $fileName[count($fileName) - 1];
							$fileNameTMP = '';
							unset($fileName[count($fileName) - 1]);
							foreach($fileName as $key => $fileNamePart) {
								$fileNameTMP .= $fileNamePart;
							}
							$fileName = $fileNameTMP;
							$encoded = json_encode(array($file, $fileName, $fileExt, $ct_path, $co_path));
							$encoded = htmlentities($encoded, ENT_SUBSTITUTE, 'UTF-8', false);
							echo $this->Html->link(
								'Descargar',
								array(
									'controller' => 'colecciones',
									'action' => 'download',
									$encoded
								)
							);
						?>
					</td>
				<?php
				} elseif($campo['tipos_de_campo_id'] == 4) {
					//Imagen
					?>
					<td class="dato imagen">
						<?php
							$ct_path = $coleccion['TipoDeContenido']['nombre'];
							$co_path = $coleccion['Coleccion']['nombre'];
							$file = $campo['imagen'];
							$fileName = explode('.', $file);
							$fileExt = $fileName[count($fileName) - 1];
							$fileNameTMP = '';
							unset($fileName[count($fileName) - 1]);
							foreach($fileName as $key => $fileNamePart) {
								$fileNameTMP .= $fileNamePart;
							}
							$fileName = $fileNameTMP;
							$encoded = json_encode(array($file, $fileName, $fileExt, $ct_path, $co_path));
							$encoded = htmlentities($encoded, ENT_SUBSTITUTE, 'UTF-8', false);
							echo $this->Html->link(
								'Descargar',
								array(
									'controller' => 'colecciones',
									'action' => 'download',
									$encoded
								)
							);
						?>
					</td>
				<?php
				} elseif($campo['tipos_de_campo_id'] == 5) {
					//Lista predefinida
					?>
					<td class="dato lista-predefinida"><?php echo $campo['seleccion_lista_predefinida']; ?></td>
				<?php
				} elseif($campo['tipos_de_campo_id'] == 6) {
					//Número
					?>
					<td class="dato número"><?php echo $campo['numero']; ?></td>
				<?php
				} elseif($campo['tipos_de_campo_id'] == 7) {
					//Fecha
					?>
					<td class="dato fecha"><?php echo $campo['fecha']; ?></td>
				<?php
				} elseif($campo['tipos_de_campo_id'] == 8) {
					//Elemento
					?>
					<td class="dato elemento"></td>
				<?php
				}
				?>
			<?php endforeach; ?>
				<td><?php echo $coleccion['Coleccion']['created']; ?></td>
				<td class="actions">
					<?php echo $this->Html->link(__('Ver'), array('action' => 'view', $coleccion['Coleccion']['id'])); ?>
					<?php
						if($user_id && $this->requestAction('/colecciones/verificarModificar/' . $user_id . '/' . $coleccion['Coleccion']['id'])) {
							echo $this->Html->link(__('Modificar'), array('action' => 'edit', $coleccion['Coleccion']['id']));
						}
					?>
				</td>
			</tr>
		<?php endforeach; ?>
	</table>
	<?php echo $this -> element('paginator'); ?>
</div>
<?php //debug($unContenido); ?>