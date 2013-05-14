<?php $uid = uniqid(); ?>
<td class="nombre"><?php echo $this->Form->input("Campo.$campo_id.nombre", array('label' => false, 'div' => false, 'class' => 'campo-nombre')); ?></td>
<td class="tipo">
	<?php echo $this->Form->input("Campo.$campo_id.tipos_de_campo_id", array('empty' => 'Seleccione...', 'label' => false, 'div' => false, 'class' => "tipo-campo-$uid")); ?>
	<?php echo $this->Form->input("Campo.$campo_id.coleccion_id", array('empty' => 'Seleccione...', 'class' => "elementos-$uid", 'div' => array('class' => "input select elemento-$uid"))); ?>
	<?php echo $this->Form->input("Campo.$campo_id.extensiones", array('class' => "extensiones-$uid", 'div' => array('class' => "input text div-ext-$uid"), 'placeholder' => 'ext1, ext2, ..., extN')); ?>
	<?php echo $this->Form->input("Campo.$campo_id.lista_predefinida", array('class' => "lista-$uid", 'placeholder' => 'Una opción por línea', 'style' => 'height: 75px', 'div' => array('class' => "input textarea div-lista-$uid"))); ?>
</td>
<td><?php echo $this->Form->input("Campo.$campo_id.es_requerido", array('class' => "req-$uid", 'label' => false, 'div' => false)); ?></td>
<td class="actions"><a class="remover-campo-<?php echo $uid; ?>">Eliminar</a></td>
<script type="text/javascript" language="JavaScript">
	$(function(){
		var campoReq = $('.req-<?php echo $uid; ?>'), campoEle = $('.elementos-<?php echo $uid; ?>'), divEle = $('.elemento-<?php echo $uid; ?>'), divLista = $('.div-lista-<?php echo $uid; ?>'), campoLista = $('.lista-<?php echo $uid; ?>'), divExt = $('.div-ext-<?php echo $uid; ?>'), campoExt = $('.extensiones-<?php echo $uid; ?>'), campoTipo = $('.tipo-campo-<?php echo $uid; ?>'), eliminarCampo = $('.remover-campo-<?php echo $uid; ?>');
		divEle.css('display', 'none');
		divLista.css('display', 'none');
		divExt.css('display', 'none');
		campoTipo.change(function(){
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
				campoReq.prop('disabled', false);
				campoEle.val('');
				divEle.css('display', 'none');
			}
		});
		eliminarCampo.click(function() {
			eliminarCampo.parent().parent().remove();
		});
	});
</script>