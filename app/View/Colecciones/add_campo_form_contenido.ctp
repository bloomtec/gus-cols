<?php
$timestamp = time();
$token = md5('8206092654127895411132256' . $timestamp);
$options = array();
$options['label'] = $campo['Campo']['nombre'];
$es_unico = $campo['Campo']['unico'] ? 1 : 0;
$es_filtro = $campo['Campo']['filtro'] ? 1 : 0;
$es_listado = $campo['Campo']['listado'] ? 1 : 0;
$es_requerido = $campo['Campo']['es_requerido'] ? 1 : 0;
//Cambio de ruta de guardar archivos
$c_name = $ct_id;

if($es_requerido) {
	$options['required'] = 'required';
} else {
	if(isset($options['required'])) unset($options['required']);
}
/**
 * Campos Generales
 */
if(isset($campo['Campo']['id']) && !empty($campo['Campo']['id']) && !empty($campo['Campo']['campo_padre'])) {
	echo $this->Form->hidden(
		"CamposColeccion.$index.id",
		array('value' => $campo['Campo']['id'])
	);
	echo $this->Form->hidden(
		"CamposColeccion.$index.campo_padre",
		array('value' => $campo['Campo']['campo_padre'])
	);
} else {
	echo $this->Form->hidden(
		"CamposColeccion.$index.campo_padre",
		array('value' => $campo_id)
	);
}
echo $this->Form->hidden(
	"CamposColeccion.$index.nombre",
	array('value' => $campo['Campo']['nombre'])
);
echo $this->Form->hidden(
	"CamposColeccion.$index.es_requerido",
	array('value' => $es_requerido)
);
echo $this->Form->hidden(
	"CamposColeccion.$index.tipos_de_campo_id",
	array('value' => $campo['Campo']['tipos_de_campo_id'])
);
echo $this->Form->hidden(
	"CamposColeccion.$index.filtro",
	array('value' => $es_filtro)
);
echo $this->Form->hidden(
	"CamposColeccion.$index.listado",
	array('value' => $es_listado)
);
echo $this->Form->hidden(
	"CamposColeccion.$index.unico",
	array('value' => $es_unico)
);
echo $this->Form->hidden(
	"CamposColeccion.$index.posicion",
	array('value' => $campo['Campo']['posicion'])
);
/**
 * Campos acorde el tipo de campo
 */
if($campo['Campo']['tipos_de_campo_id'] == 1) {
	// Texto multilínea
	$options['class'] = "campo-$index campo-multilinea";
	$options['id']    = "CamposColeccion$index" . "Multilinea$campo_id";
	$options['type']  = 'textarea';
	if(!empty($campo['Campo']['multilinea'])) $options['value'] = $campo['Campo']['multilinea'];
	echo $this->Form->input(
		"CamposColeccion.$index.multilinea",
		$options
	);
} elseif($campo['Campo']['tipos_de_campo_id'] == 2) {
	// Texto
	$options['class'] = "campo-$index campo-texto";
	$options['id']    = "CamposColeccion$index" . "Texto$campo_id";
	if(!empty($campo['Campo']['texto'])) $options['value'] = $campo['Campo']['texto'];
	echo $this->Form->input(
		"CamposColeccion.$index.texto",
		$options
	);
} elseif($campo['Campo']['tipos_de_campo_id'] == 3) {
	// Archivo
	$options['class'] = "campo-$index campo-archivo";
	$options['id']    = "CamposColeccion$index" . "NombreDeArchivo$campo_id";
	if(!empty($campo['Campo']['nombre_de_archivo'])) $options['value'] = $campo['Campo']['nombre_de_archivo'];
	echo $this->Form->hidden(
		"CamposColeccion.$index.nombre_de_archivo",
		$options
	);
	echo $this->Form->hidden(
		"CamposColeccion.$index.link_descarga",
		array(
			'value' => $campo['Campo']['link_descarga'],
			'id' => "CamposColeccion$index" . "LinkDescarga$campo_id",
		)
	);
	echo $this->Form->hidden(
		"CamposColeccion.$index.extensiones",
		array('value' => $campo['Campo']['extensiones'])
	);
	?>
	<div class="file-info">
		<span id="FileInfo<?php echo $campo_id; ?>">
			<?php
				if(!empty($campo['Campo']['nombre_de_archivo'])) {
					echo $campo['Campo']['nombre'] . ' :: ' . $campo['Campo']['nombre_de_archivo'];
				} else {
					echo 'Actualmente no hay archivo para este campo';
				}
			?>
		</span>
	</div>
	<div class="delete-link-wrapper">
		<?php if($campo['Campo']['es_requerido']) : ?>
			<span>Este campo no puede estar sin archivo relacionado.</span>
			<br />
			<span>Luego, no hay opción de elimar archivo.</span>
		<?php else: ?>
			<?php if(!empty($campo['Campo']['nombre_de_archivo'])): ?>
			<div class="actions">
				<a id="DeleteFile<?php echo $campo_id; ?>" href="#">Eliminar archivo <?php //echo $campo['Campo']['nombre_de_archivo']; ?></a>
			</div>
			<?php endif; ?>
		<?php endif; ?>
	</div>
	<div class="upload-container">
		<label id="Label<?php echo $campo_id; ?>" for="Upload<?php echo $campo_id; ?>"><?php echo $campo['Campo']['nombre'] ?></label>
		<h4>Puede subir archivos con extensiones tipo: <?php echo $exts; ?></h4>
		<div id="Upload<?php echo $campo_id; ?>"></div>
		<div id="Result<?php echo $campo_id; ?>"></div>
	</div>
	<script type="text/javascript" language="JavaScript">
		$(function() {
			var delete_link = $('#DeleteFile<?php echo $campo_id; ?>');
			delete_link.click(function(e) {
				e.preventDefault();
				$.ajax({
					'url':'/campos/eliminarArchivo',
					'type': 'post',
					'data': {
						'campo_id': <?php echo $campo_id; ?>
					},
					'success': function(response) {
						if(response == 1) {
							delete_link.hide();
							$('#FileInfo<?php echo $campo_id; ?>').html('Actualmente no hay archivo para este campo');
							$('.delete-link-wrapper').append('<br /><span>Se eliminó el archivo</span>');
						} else {

						}
					}
				});
			});
			$('#Upload<?php echo $campo_id; ?>').ajaxupload({
				url : '/upload.php',
				maxConnections : 2,
				maxFiles : 1,
				allowExt : [<?php echo $exts; ?>],
				autoStart : true,
				editFilename : false,
				remotePath : '<?php echo $path; ?>',
				data : {
					token : '<?php echo $token; ?>',
					timestamp : '<?php echo $timestamp; ?>',
					contentType : '<?php echo $c_name; ?>',
					directory : '<?php echo $uid; ?>'
				},
				finish : function (filesName){
					$.ajax({
						url: "/colecciones/uploaded/<?php echo urlencode($c_name); ?>/<?php echo urlencode($uid); ?>/" + encodeURIComponent(filesName),
						cache: false,
						async: false,
						dataType: 'json',
						success: function(response) {
							if(response.success) {
								$('#CamposColeccion<?php echo $index; ?>NombreDeArchivo<?php echo $campo_id; ?>').val(response.nombreNuevo);
								$('#Upload<?php echo $campo_id; ?>').remove();
								$('#Result<?php echo $campo_id; ?>').html('Se ha subido el archivo ' + response.nombreOriginal);
								$('#FileInfo<?php echo $campo_id; ?>').html('');
								<?php if(empty($campo['Campo']['link_descarga'])) : ?>
								$('#CamposColeccion<?php echo $index; ?>LinkDescarga<?php echo $campo_id; ?>').val(response.nombreOriginal);
								<?php endif; ?>
							} else {
								$('#Result<?php echo $campo_id; ?>').html('Ocurrió un error al subir el archivo. Por favor, intente de nuevo.');
							}
						}
					});
				},
				error : function (err, fileName) {
					alert('Error: ' + err + '. Al tratar de subir el archivo: ' + fileName);
				}
			});
		});
	</script>
<?php
} elseif($campo['Campo']['tipos_de_campo_id'] == 4) {
	// Imagen
	$options['class'] = "campo-$index campo-imagen";
	$options['id']    = "CamposColeccion$index" . "Imagen$campo_id";
	echo $this->Form->hidden(
		"CamposColeccion.$index.imagen",
		$options
	);
	echo $this->Form->hidden(
		"CamposColeccion.$index.link_descarga",
		array('value' => $campo['Campo']['link_descarga'])
	);
	?>
	<div class="upload-container">
		<label id="Label<?php echo $campo_id; ?>" for="Upload<?php echo $campo_id; ?>"><?php echo $campo['Campo']['nombre'] ?></label>
		<h4>Puede subir archivos con extensiones tipo: 'gif', 'jpg', 'png'</h4>
		<div id="Upload<?php echo $campo_id; ?>"></div>
		<div id="Result<?php echo $campo_id; ?>"></div>
	</div>
	<script type="text/javascript" language="JavaScript">
		$(function() {
			$('#Upload<?php echo $campo_id; ?>').ajaxupload({
				url : '/upload.php',
				maxConnections : 2,
				maxFiles : 1,
				allowExt : ['gif', 'jpg', 'png'],
				autoStart : true,
				editFilename : false,
				remotePath : '<?php echo $path; ?>',
				data : {
					token : '<?php echo $token; ?>',
					timestamp : '<?php echo $timestamp; ?>',
					contentType : '<?php echo $c_name; ?>',
					directory : '<?php echo $uid; ?>'
				},
				finish : function (filesName){
					$.ajax({
						url: "/colecciones/uploaded/<?php echo urlencode($c_name); ?>/<?php echo urlencode($uid); ?>/" + encodeURIComponent(filesName),
						cache: false,
						async: false,
						dataType: 'json',
						success: function(response) {
							if(response.success) {
								$('#CamposColeccion<?php echo $index; ?>Imagen<?php echo $campo_id; ?>').val(response.archivo);
								$('#Upload<?php echo $campo_id; ?>').remove();
								$('#Result<?php echo $campo_id; ?>').html('Se ha subido el archivo ' + response.archivo);
							} else {
								$('#Result<?php echo $campo_id; ?>').html('Ocurrió un error al subir el archivo. Por favor, intente de nuevo.');
							}
						}
					});
				},
				error : function (err, fileName) {
					alert('Error: ' + err + '. Al tratar de subir el archivo: ' + fileName);
				}
			});
		});
	</script>
<?php
} elseif($campo['Campo']['tipos_de_campo_id'] == 5) {
	// Lista predefinida
	$options['type']    = 'select';
	$options['options'] = $seleccionListaPredefinidas;
	$options['class']   = "campo-$index campo-selección-lista";
	$options['id']      = "CamposColeccion$index" . "SelecciónListaPredefinida$campo_id";
	if(!empty($campo['Campo']['seleccion_lista_predefinida'])) $options['value'] = $campo['Campo']['seleccion_lista_predefinida'];
	echo $this->Form->input(
		"CamposColeccion.$index.seleccion_lista_predefinida",
		$options
	);
	echo $this->Form->hidden(
		"CamposColeccion.$index.lista_predefinida",
		array('value' => $campo['Campo']['lista_predefinida'])
	);
} elseif($campo['Campo']['tipos_de_campo_id'] == 6) {
	// Número
	$options['class'] = "campo-$index campo-número";
	$options['id']    = "CamposColeccion$index" . "Numero$campo_id";
	if(!empty($campo['Campo']['numero'])) $options['value'] = $campo['Campo']['numero'];
	echo $this->Form->input(
		"CamposColeccion.$index.numero",
		$options
	);
} elseif($campo['Campo']['tipos_de_campo_id'] == 7) {
	// Fecha
	$options['placeholder'] = 'aaaa-mm-dd';
	$options['class']       = "campo-$index campo-fecha";
	$options['id']          = "CamposColeccion$index" . "Fecha$campo_id";
	if(!empty($campo['Campo']['fecha'])) $options['value'] = $campo['Campo']['fecha'];
	echo $this->Form->input(
		"CamposColeccion.$index.fecha",
		$options
	);
	?>
	<script type="text/javascript" language="JavaScript">
		$(function() {
			if($(".campo-fecha")) {
				var currentYear = (new Date).getFullYear();
				var minRange = (currentYear - 100).toString();
				var maxRange = (currentYear + 100).toString();
				$(".campo-fecha").datepicker({
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
<?php
} elseif($campo['Campo']['tipos_de_campo_id'] == 8) {
	// Elemento
	/** Y esto es... **/
} elseif($campo['Campo']['tipos_de_campo_id'] == 9) {
	// Enlace
	$options['class'] = "campo-$index campo-enlace";
	$options['id']    = "CamposColeccion$index" . "Texto$campo_id";
	if(!empty($campo['Campo']['texto'])) $options['value'] = $campo['Campo']['texto'];
	echo $this->Form->input(
		"CamposColeccion.$index.texto",
		$options
	);
}
?>
<script type="text/javascript" language="JavaScript">
	$(function() {
		var campo = $('.campo-<?php echo $index; ?>');
		if(campo.attr('required')) {
			$(campo.parent()).addClass('required');
		}
	});
</script>