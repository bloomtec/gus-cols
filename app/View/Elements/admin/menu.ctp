<?php
echo $this -> Html -> css('superfish');
echo $this -> Html -> css('superfish-navbar');
echo $this -> Html -> css('superfish-vertical');

echo $this -> Html -> script('superfish');
echo $this -> Html -> script('hoverIntent');
echo $this -> Html -> script('supersubs');
?>
<ul id="admin-menu" class="sf-menu sf-js-enabled">
	<li>
		<a>Colecciones</a>
		<ul>
			<li>
				<?php echo $this -> Html -> link('Bases de contenido', array('controller' => 'colecciones', 'action' => 'index_content_type')); ?>
				<ul>
					<li>
						<?php echo $this -> Html -> link('Crear', array('controller' => 'colecciones', 'action' => 'add_content_type')); ?>
					</li>
				</ul>
			</li>
			<li>
				<?php echo $this -> Html -> link('Contenidos', array('controller' => 'colecciones', 'action' => 'index_content')); ?>
				<ul>
					<li>
						<?php echo $this -> Html -> link('Crear', array('controller' => 'colecciones', 'action' => 'add_content')); ?>
					</li>
				</ul>
			</li>
		</ul>
	</li>
	<li>
		<?php echo $this -> Html -> link('Grupos', array('controller' => 'grupos', 'action' => 'index')); ?>
		<ul>
			<li>
				<?php echo $this -> Html -> link('Crear', array('controller' => 'grupos', 'action' => 'add')); ?>
			</li>
		</ul>
	</li>
	<li>
		<?php echo $this -> Html -> link('Usuarios', array('controller' => 'usuarios', 'action' => 'index')); ?>
		<ul>
			<li>
				<?php echo $this -> Html -> link('Crear', array('controller' => 'usuarios', 'action' => 'add')); ?>
			</li>
		</ul>
	</li>
	<li>
		<?php echo $this -> Html -> link('Cerrar Sesión', array('controller' => 'usuarios', 'action' => 'logout')); ?>
	</li>
</ul>