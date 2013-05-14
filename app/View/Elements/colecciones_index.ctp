<?php
	// Obtener el ID del usuario (si lo hay)
	$user_id = $this->Session->read('Auth.User.id');
	// Obtener el primer contenido para crear de manera dinámica la tabla y los filtros
	$unContenido = null;
	$filtros = array();
	/*if(!empty($colecciones)) {
		$unContenido = $colecciones[0];
	} elseif(!empty($ultimosCampos)) {
		$unContenido = $ultimosCampos;
	}*/
	$unContenido = $coleccionBase;
	$camposIDs = array();
	foreach($unContenido['CamposColeccion'] as $key => $campo) {
		$camposIDs[] = $campo['id'];
	}
	foreach($unContenido['CamposColeccion'] as $key => $campo) {
		if($campo['filtro']) $filtros[] = $campo;
	}
	$_SESSION['Filtros']['ultimo'] = $unContenido;
?>
<div class="colecciones index">
	<h2><?php echo __('Listado de: ' . $unContenido['Coleccion']['nombre']); ?></h2>
	<?php if(!empty($filtros)) { ?>
	<?php echo $this->Form->create('Coleccion', array('id' => 'FiltrosForm', 'action' => 'index/' . $coleccion_id . '/' . $auditable)); ?>
	<table class="filtro">
		<tr>
			<?php
			$filter_counter = 0;
			foreach($filtros as $key => $campo) {
				if($campo['listado']){
					echo '<td class="label">' . $campo['nombre'] . '</td>';
					if($campo['tipos_de_campo_id'] == 2) {
						// TEXTO
						echo '<td class="input text">' . $this->Form->input("Filtros.$filter_counter.2.value", array('label' => false, 'div' => false, 'type' => 'text')) . '</td>';
					} elseif($campo['tipos_de_campo_id'] == 5) {
						// LISTA
						$TMPOpciones = explode("\n", $campo['lista_predefinida']);
						$opciones = array();
						foreach($TMPOpciones as $TMPOpcionesKey => $opcion) {
							$val = trim($opcion);
							$opciones[$val] = $val;
						}
						echo '<td class="input select">' . $this->Form->input("Filtros.$filter_counter.5.value", array('empty' => 'Seleccione...', 'label' => false, 'div' => false, 'type' => 'select', 'options' => $opciones)) . '</td>';
						echo $this->Form->hidden("Filtros.$filter_counter.5.lista", array('value' => $campo['lista_predefinida']));
					} elseif($campo['tipos_de_campo_id'] == 6) {
						// NUMERO
						echo
							'<td class="input number">'
							. $this->Form->input("Filtros.$filter_counter.6.value.min", array('label' => false, 'div' => false, 'type' => 'number'))
							. ' - '
							. $this->Form->input("Filtros.$filter_counter.6.value.max", array('label' => false, 'div' => false, 'type' => 'number'))
							. '</td>';
					} elseif($campo['tipos_de_campo_id'] == 7) {
						// FECHA
						echo
							'<td class="input dates">'
							. $this->Form->input("Filtros.$filter_counter.7.value.min", array('placeholder' => 'aaaa-mm-dd', 'label' => false, 'div' => false, 'type' => 'text', 'class' => 'date'))
							. ' - '
							. $this->Form->input("Filtros.$filter_counter.7.value.max", array('placeholder' => 'aaaa-mm-dd', 'label' => false, 'div' => false, 'type' => 'text', 'class' => 'date'))
							. '</td>';
					}
					$filter_counter += 1;
				}
			}
			?>
			<td class="input submit"><?php echo $this->Form->submit('Filtrar'); ?></td>
			<?php if($filtrado) : ?>
			<td class="actions"><?php echo $this->Html->link(__('Remover filtro actual'), array('action' => 'removerFiltro', $coleccion_id, $auditable)); ?></td>
			<?php endif; ?>
		</tr>
	</table>
	<?php echo $this->Form->end(); ?>
		<script type="text/javascript" language="JavaScript">
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
		<?php if(isset($unContenido)) : ?>
		<tr>
			<?php foreach($unContenido['CamposColeccion'] as $key => $campo) : ?>
				<?php if($campo['listado']) : ?>
				<th><?php echo $campo['nombre']; ?></th>
				<?php endif; ?>
			<?php endforeach; ?>
            <?php /*
			<th>Fecha de ingreso</th>
			<th class="actions"><?php echo __('Acciones'); ?></th>
                */ ?>
		</tr>
		<?php endif; ?>
		<?php foreach($colecciones as $coleccion): ?>
			<tr>
				<?php
					foreach($unContenido['CamposColeccion'] as $keyBase => $campoBase) {
						$tdVacio = true;
						$elCampo = null;
						foreach($coleccion['Campo'] as $campo) {
							if($campo['campo_padre'] === $campoBase['id']) {
								$tdVacio = false;
								$elCampo = $campo;
								break;
							}
						}
						if($tdVacio && $campoBase['listado']) {
							echo '<td></td>';
						} else {

							echo $this->element(
								'filtradoTipoCampo',
								array(
									'campo' => $elCampo,
									'coleccion' => $coleccion
								)
							);
						}
					}
				?>
               <?php /*
				<td><?php echo $coleccion['Coleccion']['created']; ?></td>

				<td class="actions">
					<?php echo $this->Html->link(__('Ver'), array('action' => 'view', $coleccion['Coleccion']['id'])); ?>
					<?php
						if($user_id && $this->requestAction('/colecciones/verificarModificar/' . $user_id . '/' . $coleccion['Coleccion']['id'])) {
							echo $this->Html->link(__('Modificar'), array('action' => 'edit', $coleccion['Coleccion']['id']));
						}
					?>
				</td>
				*/?>
			</tr>
		<?php endforeach; ?>
	</table>
	<?php echo $this -> element('paginator'); ?>
</div>
<?php //debug($unContenido); ?>