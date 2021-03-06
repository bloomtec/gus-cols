<div class="colecciones view">
	<h2><?php  echo __('Coleccion :: ' . $coleccion['TipoDeContenido']['nombre']); ?></h2>
	<dl>
		<dt><?php echo __('ID Interno'); ?></dt>
		<dd>
			<?php echo h($coleccion['Coleccion']['nombre']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Publicado'); ?></dt>
		<dd>
			<?php if($coleccion['Coleccion']['publicada']) { ?>
			<input type="checkbox" disabled="disabled" checked="checked" />
			<?php } else { ?>
			<input type="checkbox" disabled="disabled" />
			<?php } ?>
			&nbsp;
		</dd>

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
				<th><?php echo __('Tipo De Campo'); ?></th>
				<th><?php echo __('Nombre'); ?></th>
				<th><?php echo __('Dato'); ?></th>
			</tr>
			<?php
				$i = 0;
				foreach ($coleccion['CamposColeccion'] as $campo): ?>
					<tr>
						<td><?php echo $campo['TiposDeCampo']['nombre']; ?></td>
						<td><?php echo $campo['nombre']; ?></td>
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
									<?php
										if(!empty($campo['texto'])) :
									?>
									<a href="<?php echo $campo['texto']; ?>" target="_blank"><?php echo $campo['texto']; ?></a>
									<?php endif; ?>
								</td>
							<?php
							}
						?>
					</tr>
				<?php endforeach; ?>
		</table>
	<?php endif; ?>
</div>
<?php if($auditar) : ?>
<br />
<div>
	<h2><?php echo __('Auditoría'); ?></h2>
	<table>
		<caption><h4>Historial de revisiones</h4></caption>
		<tr>
			<th>Auditor</th>
			<th>Aprobado</th>
			<th>Fecha Revision</th>
			<th>Comentario</th>
		</tr>
		<?php foreach($coleccion['Auditoria'] as $key => $auditoria) : ?>
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
	<br />
	<?php echo $this->Form->create('Coleccion'); ?>
	<h4>Revisión</h4>
	<fieldset>
		<?php echo $this->Form->hidden('es_tipo_de_contenido', array('value' => $coleccion['Coleccion']['es_tipo_de_contenido'])); ?>
		<?php echo $this->Form->hidden('auditada', array('value' => 1)); ?>
		<?php echo $this->Form->hidden('user_id', array('value' => $user_id)); ?>
		<?php echo $this->Form->input('id', array('value' => $coleccion['Coleccion']['id'])); ?>
		<?php
			echo $this->Form->input(
				'publicada',
				array(
					'type' => 'select',
					'label' => 'Estado',
					'value' => $coleccion['Coleccion']['publicada'] ? 1 : 0,
					'options' => array('1' => 'Aceptada', '0' => 'Rechazada')
				)
			);
		?>
		<?php echo $this->Form->input('observación', array('type' => 'textarea', 'label' => 'Motivo de rechazo')); ?>
		<?php echo $this->Form->submit('Enviar'); ?>
	</fieldset>
	<?php echo $this->Form->end(); ?>
</div>
<?php endif; ?>