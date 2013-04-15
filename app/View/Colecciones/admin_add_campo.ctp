<?php $uid = uniqid(); ?>
<td class="nombre"><?php echo $this->Form->input("Campo.$campo_id.nombre", array('label' => false, 'div' => false)); ?></td>
<td><?php echo $this->Form->input("Campo.$campo_id.tipos_de_campo_id", array('empty' => 'Seleccione...', 'label' => false, 'div' => false, 'class' => "tipo-campo-$uid")); ?></td>
<td><?php echo $this->Form->input("Campo.$campo_id.extensión", array('label' => false, 'div' => false, 'class' => "extensión-$uid")); ?></td>
<td><?php echo $this->Form->input("Campo.$campo_id.es_requerido", array('label' => false, 'div' => false)); ?></td>
<td class="actions"><a class="remover-campo-<?php echo $uid; ?>">Eliminar</a></td>
<script>
	$(function(){
		var campoExt = $('.extensión-<?php echo $uid; ?>'), campoTipo = $('.tipo-campo-<?php echo $uid; ?>'), eliminarCampo = $('.remover-campo-<?php echo $uid; ?>');

		campoExt.attr('disabled', 'disabled');
		campoTipo.change(function(){
			if(campoTipo.val() == 3) {
				campoExt.removeAttr('disabled');
			} else {
				campoExt.attr('disabled', 'disabled');
				campoExt.val('');
			}
		});
		eliminarCampo.click(function() {
			eliminarCampo.parent().parent().remove();
		});
	});
</script>