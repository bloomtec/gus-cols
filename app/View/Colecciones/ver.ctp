<div class="colecciones view">
	<h2><?php echo h($coleccion['TipoDeContenido']['nombre']); ?></h2>
	<?php /*
	<dl>
		<dt><?php echo __('Creado'); ?></dt>
		<dd>
			<?php echo h($coleccion['Coleccion']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modificado'); ?></dt>
		<dd>
			<?php echo h($coleccion['Coleccion']['modified']); ?>
			&nbsp;
		</dd>
	</dl>
    */ ?>
</div>
<div class="related">
	<?php /*<h3><?php echo __('Campos'); ?></h3>*/ ?>
	<?php if (!empty($coleccion['CamposColeccion'])): ?>
		<table cellpadding = "0" cellspacing = "0">
			<?php /*<tr>
				<th><?php echo __('Nombre'); ?></th>
				<th><?php echo __('Dato'); ?></th>
			</tr>*/ ?>
			<?php
				$i = 0;
				foreach ($coleccion['CamposColeccion'] as $campo):
					$mostrar = true;
					switch($campo['tipos_de_campo_id']) {
						case 1:
							empty($campo['multilinea']) ? $mostrar = false : $mostrar = true;
							break;
						case 2:
							empty($campo['texto']) ? $mostrar = false : $mostrar = true;
							break;
						case 3:
							empty($campo['nombre_de_archivo']) ? $mostrar = false : $mostrar = true;
							break;
						case 4:
							empty($campo['imagen']) ? $mostrar = false : $mostrar = true;
							break;
						case 5:
							empty($campo['seleccion_lista_predefinida']) ? $mostrar = false : $mostrar = true;
							break;
						case 6:
							empty($campo['numero']) ? $mostrar = false : $mostrar = true;
							break;
						case 7:
							empty($campo['fecha']) ? $mostrar = false : $mostrar = true;
							break;
					}
					if($mostrar) :
			?>
			<tr>
				<td><?php echo $campo['nombre']; ?></td>
				<td>::</td>
				<?php
					/**
					 * Organizar acorde el tipo de campo
					 */
					if($campo['tipos_de_campo_id'] == 1) {
						//Texto multilínea
						?>
						<td class="dato texto-multilinea"><?php echo $campo['multilinea']; ?></td>
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
								//$encoded = htmlentities($encoded, ENT_SUBSTITUTE, 'UTF-8', false);
								$encoded = htmlentities($encoded);
								if(!empty($file)) {
									echo $this->Html->link(
										'Descargar',
										array(
											'controller' => 'colecciones',
											'action' => 'download',
											$encoded
										)
									);
								}
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
								//$encoded = htmlentities($encoded, ENT_SUBSTITUTE, 'UTF-8', false);
								$encoded = htmlentities($encoded);
								if(!empty($file)) {
									echo $this->Html->link(
										'Descargar',
										array(
											'controller' => 'colecciones',
											'action' => 'download',
											$encoded
										)
									);
								}
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
						<td class="dato numero"><?php echo $campo['numero']; ?></td>
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
			</tr>
			<?php
					endif;
				endforeach;
			?>
		</table>
	<?php endif; ?>
</div>
<div class="actions">
	<?php echo $this->Html->link('Volver', array('action' => 'indice', $coleccion['Coleccion']['coleccion_id'])); ?>
</div>