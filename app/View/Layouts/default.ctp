<?php
/**
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
?>
<!DOCTYPE html>
<html>
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php echo $title_for_layout; ?>
	</title>
	<?php
	echo $this->Html->meta('icon');

	echo $this->Html->css('cake.generic');
	echo $this->Html->css('styles');
	echo $this->Html->css('jquery-ui-1.10.2.custom');
	echo $this->Html->css('uploadify');

	echo $this->Html->script('jquery-1.9.1.min');
	echo $this->Html->script('jquery-ui-1.10.2.custom.min');
	echo $this->Html->script('jquery.uploadify.min');
	echo $this->Html->script('default');

	echo $this->fetch('meta');
	echo $this->fetch('css');
	echo $this->fetch('script');
	?>
</head>
<body>
<div id="container">
	<div id="header">
		<?php echo $this -> element('menu'); ?>
	</div>
	<div id="content">
		<?php echo $this->Session->flash(); ?>
		<?php echo $this->fetch('content'); ?>
		<div class="actions">
			<?php if(!in_array($this->action, array('index'))) { ?>
			<a href="<?php echo $previous; ?>">Volver</a>
			<?php } ?>
		</div>
	</div>
	<div id="footer">

	</div>
</div>
<?php echo $this->element('sql_dump'); ?>
</body>
</html>