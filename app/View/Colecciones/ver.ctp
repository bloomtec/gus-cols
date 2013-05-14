<div class="colecciones view">
	<h2><?php echo h($coleccion['Coleccion']['nombre']); ?></h2>
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
</div>
<div class="related">
	<h3><?php echo __('Campos'); ?></h3>
	<?php if (!empty($coleccion['CamposColeccion'])): ?>
		<table cellpadding = "0" cellspacing = "0">
			<tr>
				<th><?php echo __('Nombre'); ?></th>
				<th><?php echo __('Dato'); ?></th>
			</tr>
			<?php
				$i = 0;
				foreach ($coleccion['CamposColeccion'] as $campo): ?>
					<tr>
						<td><?php echo $campo['nombre']; ?></td>
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
										$encoded = htmlentities($encoded, ENT_SUBSTITUTE, 'UTF-8', false);
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
					</tr>
				<?php endforeach; ?>
		</table>
	<?php endif; ?>
</div>