<?php
	/**
	 * Organizar acorde el tipo de campo
	 */
	if($campo['listado']) {
		if($campo['tipos_de_campo_id'] == 1) {
			//Texto multilínea
			?>
			<td class="dato texto-multilinea">
				<?php //echo $campo['multilinea']; ?>
				<?php echo $this->Html->link($campo['multilinea'], array('action' => 'ver', $coleccion['Coleccion']['id'])); ?>
			</td>
		<?php
		} elseif($campo['tipos_de_campo_id'] == 2) {
			//Texto
			?>
			<td class="dato texto">
				<?php //echo $campo['texto']; ?>
				<?php echo $this->Html->link($campo['texto'], array('action' => 'ver', $coleccion['Coleccion']['id'])); ?>
			</td>
		<?php
		} elseif($campo['tipos_de_campo_id'] == 3) {
			//Archivo
			?>
			<td class="dato archivo">
				<?php
					//$ct_path = $coleccion['TipoDeContenido']['nombre'];
					$ct_path = $coleccion['TipoDeContenido']['id'];
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
						$link = !empty($campo['link_descarga']) ? $campo['link_descarga'] : $file;
						echo $this->Html->link(
							$link,
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
					//$ct_path = $coleccion['TipoDeContenido']['nombre'];
					$ct_path = $coleccion['TipoDeContenido']['id'];
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
						$link = !empty($campo['link_descarga']) ? $campo['link_descarga'] : $file;
						echo $this->Html->link(
							$link,
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
		} elseif($campo['tipos_de_campo_id'] == 9) {
			//Enlace
			?>
			<td class="dato enlace">
				<?php if(!empty($campo['texto'])) : ?>
					<a href="<?php echo $campo['texto']; ?>" target="_blank"><?php echo $campo['texto']; ?></a>
				<?php endif; ?>
			</td>
		<?php
		}
	}
?>