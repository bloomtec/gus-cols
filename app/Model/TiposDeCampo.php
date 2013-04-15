<?php
App::uses('AppModel', 'Model');
/**
 * TiposDeCampo Model
 *
 * @property Campo $Campo
 */
class TiposDeCampo extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'nombre';


	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Campo' => array(
			'className' => 'Campo',
			'foreignKey' => 'tipos_de_campo_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);

}
