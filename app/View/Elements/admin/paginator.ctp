<p>
	<?php
	echo $this->Paginator->counter(
		array(
			'format' => __('Página {:page} de {:pages}, mostrando {:current} registros de un total de {:count}, iniciando en {:start}, terminando en {:end}')
		)
	);
	?>
</p>
<div class="paging">
	<?php
	echo $this->Paginator->prev('< ' . __('anterior'), array(), null, array('class' => 'prev disabled'));
	echo $this->Paginator->numbers(array('separator' => ''));
	echo $this->Paginator->next(__('siguiente') . ' >', array(), null, array('class' => 'next disabled'));
	?>
</div>