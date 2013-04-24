<?php
echo $this -> Html -> css('superfish');
echo $this -> Html -> css('superfish-navbar');
echo $this -> Html -> css('superfish-vertical');

echo $this -> Html -> script('superfish');
echo $this -> Html -> script('hoverIntent');
echo $this -> Html -> script('supersubs');
?>
<ul id="user-menu" class="sf-menu sf-js-enabled">
	<li>
		<?php echo $this -> Html -> link('Inicio', array('controller' => 'colecciones', 'action' => 'index')); ?>
	</li>
	<?php if($this->Session->read('Auth.User')) { ?>
	<li>
		<?php echo $this -> Html -> link('Cerrar Sesión', array('controller' => 'usuarios', 'action' => 'logout')); ?>
	</li>
	<?php } else { ?>
	<li>
		<a>Inicia Sesión</a>
		<ul>
			<li><?php echo $this -> Html -> link('Usuario', array('controller' => 'usuarios', 'action' => 'login', 'admin' => false)); ?></li>
			<li><?php echo $this -> Html -> link('Administrador', array('controller' => 'usuarios', 'action' => 'login', 'admin' => true)); ?></li>
		</ul>
	</li>
	<?php } ?>
</ul>