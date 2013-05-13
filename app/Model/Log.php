<?php
App::uses('AppModel', 'Model');
/**
 * Log Model
 *
 * @property Usuario $Usuario
 */
class Log extends AppModel {


	//The Associations below have been created with all possible keys, those that are not needed can be removed

	/**
	 * belongsTo associations
	 *
	 * @var array
	 */
	public $belongsTo = array(
		'Usuario' => array(
			'className'  => 'Usuario',
			'foreignKey' => 'usuario_id',
			'conditions' => '',
			'fields'     => '',
			'order'      => ''
		)
	);
}
