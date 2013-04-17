<?php $uid = uniqid(); ?>
<td class="nombre"><?php echo $this->Form->input("Campo.$campo_id.nombre", array('label' => false, 'div' => false)); ?></td>
<td><?php echo $this->Form->input("Campo.$campo_id.tipos_de_campo_id", array('empty' => 'Seleccione...', 'label' => false, 'div' => false, 'class' => "tipo-campo-$uid")); ?></td>
<td><?php echo $this->Form->input("Campo.$campo_id.extensión", array('label' => false, 'div' => false, 'class' => "extensión-$uid")); ?></td>
<td class="multi"><?php echo $this->Form->input("Campo.$campo_id.lista_predefinida", array('label' => false, 'div' => false, 'class' => "lista-$uid", 'placeholder' => 'Una opción por línea')); ?></td>
<td><?php echo $this->Form->input("Campo.$campo_id.es_requerido", array('label' => false, 'div' => false)); ?></td>
<td class="actions"><a class="remover-campo-<?php echo $uid; ?>">Eliminar</a></td>
<script type="text/javascript">
	$(function(){
		var campoLista = $('.lista-<?php echo $uid; ?>'), campoExt = $('.extensión-<?php echo $uid; ?>'), campoTipo = $('.tipo-campo-<?php echo $uid; ?>'), eliminarCampo = $('.remover-campo-<?php echo $uid; ?>');

		campoExt.prop('disabled', true);
		campoLista.prop('disabled', true);
		campoTipo.change(function(){
			if(campoTipo.val() == 3) {
				campoExt.removeAttr('disabled');
			} else {
				campoExt.prop('disabled', true);
				campoExt.val('');
			}
			if(campoTipo.val() == 5) {
				campoLista.removeAttr('disabled');
			} else {
				campoLista.prop('disabled', true);
				campoLista.val('');
			}
		});
		eliminarCampo.click(function() {
			eliminarCampo.parent().parent().remove();
		});
	});
</script>