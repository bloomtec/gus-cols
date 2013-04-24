<?php
	$unContenido = $colecciones[0];
	$user_id = $this->Session->read('Auth.User.id');
?>
<div class="colecciones index">
	<h2><?php echo __('Listado de: ' . $unContenido['TipoDeContenido']['nombre']); ?></h2>
	<table cellpadding="0" cellspacing="0">
		<tr>
			<?php foreach($unContenido['Campo'] as $key => $campo) : ?>
				<th><?php echo $campo['nombre']; //$this->Paginator->sort('Campo.multilinea', $campo['nombre']); ?></th>
				<?php /*if($campo['tipos_de_campo_id'] == 1) : ?>
				<th><?php echo $this->Paginator->sort('Campo.multilinea', $campo['nombre']); ?></th>
				<?php endif; ?>
				<?php if($campo['tipos_de_campo_id'] == 2) : ?>
					<th><?php echo $this->Paginator->sort('Campo.texto', $campo['nombre']); ?></th>
				<?php endif; ?>
				<?php if($campo['tipos_de_campo_id'] == 3) : ?>
					<th><?php echo $this->Paginator->sort('Campo.nombre_de_archivo', $campo['nombre']); ?></th>
				<?php endif; ?>
				<?php if($campo['tipos_de_campo_id'] == 4) : ?>
					<th><?php echo $this->Paginator->sort('Campo.imagen', $campo['nombre']); ?></th>
				<?php endif; ?>
				<?php if($campo['tipos_de_campo_id'] == 5) : ?>
					<th><?php echo $this->Paginator->sort('Campo.seleccion_lista_predefinida', $campo['nombre']); ?></th>
				<?php endif; ?>
				<?php if($campo['tipos_de_campo_id'] == 6) : ?>
					<th><?php echo $this->Paginator->sort('Campo.numero', $campo['nombre']); ?></th>
				<?php endif; ?>
				<?php if($campo['tipos_de_campo_id'] == 7) : ?>
					<th><?php echo $this->Paginator->sort('Campo.fecha', $campo['nombre']); ?></th>
				<?php endif; */?>
			<?php endforeach; ?>
			<th>Fecha de ingreso<?php //echo $this->Paginator->sort('created', 'Fecha De Ingreso'); ?></th>
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
<?php //debug($colecciones); ?>