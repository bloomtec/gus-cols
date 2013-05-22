<?php if(isset($user) && !empty($user)) : ?>
	<div id="loggedUser">
		<h5>Ha iniciado sesión como:</h5>
		<h5><?php echo $user['documento'] . ' :: ' . $user['nombres'] . ' ' . $user['apellidos']; ?></h5>
	</div>
<?php endif; ?>