<?php
	App::uses('AppModel', 'Model');
	/**
	 * Campo Model
	 *
	 * @property TiposDeCampo $TiposDeCampo
	 * @property Coleccion    $Coleccion
	 */
	class Campo extends AppModel {

		/**
		 * Display field
		 *
		 * @var string
		 */
		public $displayField = 'nombre';

		/**
		 * Validation rules
		 *
		 * @var array
		 */
		public $validate = array(
			'model'             => array(
				'notempty' => array(
					'rule' => array('notempty'),
					//'message' => 'Your custom message here',
					//'allowEmpty' => false,
					//'required' => false,
					//'last' => false, // Stop validation after this rule
					//'on' => 'create', // Limit validation to 'create' or 'update' operations
				),
			),
			'foreign_key'       => array(
				'numeric' => array(
					'rule' => array('numeric'),
					//'message' => 'Your custom message here',
					//'allowEmpty' => false,
					//'required' => false,
					//'last' => false, // Stop validation after this rule
					//'on' => 'create', // Limit validation to 'create' or 'update' operations
				),
			),
			'tipos_de_campo_id' => array(
				'numeric' => array(
					'rule' => array('numeric'),
					//'message' => 'Your custom message here',
					//'allowEmpty' => false,
					//'required' => false,
					//'last' => false, // Stop validation after this rule
					//'on' => 'create', // Limit validation to 'create' or 'update' operations
				),
			),
			'nombre'            => array(
				'notempty' => array(
					'rule' => array('notempty'),
					//'message' => 'Your custom message here',
					//'allowEmpty' => false,
					//'required' => false,
					//'last' => false, // Stop validation after this rule
					//'on' => 'create', // Limit validation to 'create' or 'update' operations
				),
			),
			'es_requerido'      => array(
				'boolean' => array(
					'rule' => array('boolean'),
					//'message' => 'Your custom message here',
					//'allowEmpty' => false,
					//'required' => false,
					//'last' => false, // Stop validation after this rule
					//'on' => 'create', // Limit validation to 'create' or 'update' operations
				),
			),
			'numero'            => array(
				'numeric' => array(
					'rule' => array('numeric'),
					//'message' => 'Your custom message here',
					'allowEmpty' => true,
					//'required' => false,
					//'last' => false, // Stop validation after this rule
					//'on' => 'create', // Limit validation to 'create' or 'update' operations
				),
			),
			'fecha'             => array(
				'date' => array(
					'rule' => array('date'),
					//'message' => 'Your custom message here',
					'allowEmpty' => true,
					//'required' => false,
					//'last' => false, // Stop validation after this rule
					//'on' => 'create', // Limit validation to 'create' or 'update' operations
				),
			),
			'coleccion_id'      => array(
				'numeric' => array(
					'rule' => array('numeric'),
					//'message' => 'Your custom message here',
					'allowEmpty' => true,
					//'required' => false,
					//'last' => false, // Stop validation after this rule
					//'on' => 'create', // Limit validation to 'create' or 'update' operations
				),
			),
			'campo_id'      => array(
				'numeric' => array(
					'rule' => array('numeric'),
					//'message' => 'Your custom message here',
					'allowEmpty' => true,
					//'required' => false,
					//'last' => false, // Stop validation after this rule
					//'on' => 'create', // Limit validation to 'create' or 'update' operations
				),
			),
			'posicion'      => array(
				'numeric' => array(
					'rule' => array('numeric'),
					//'message' => 'Your custom message here',
					//'allowEmpty' => false,
					//'required' => false,
					//'last' => false, // Stop validation after this rule
					//'on' => 'create', // Limit validation to 'create' or 'update' operations
				),
			),
		);

		//The Associations below have been created with all possible keys, those that are not needed can be removed

		/**
		 * belongsTo associations
		 *
		 * @var array
		 */
		public $belongsTo = array(
			'TiposDeCampo' => array(
				'className'  => 'TiposDeCampo',
				'foreignKey' => 'tipos_de_campo_id',
				'conditions' => '',
				'fields'     => '',
				'order'      => ''
			),
			'Coleccion'    => array(
				'className'  => 'Coleccion',
				'foreignKey' => 'coleccion_id',
				'conditions' => '',
				'fields'     => '',
				'order'      => ''
			),
			'CampoElemento'    => array(
				'className'  => 'Campo',
				'foreignKey' => 'campo_id',
				'conditions' => '',
				'fields'     => '',
				'order'      => ''
			)
		);

		/**
		 * hasOne associations
		 *
		 * @var array
		 */
		public $hasOne = array(
			'Elemento' => array(
				'className'    => 'Coleccion',
				'foreignKey'   => 'coleccion_id',
				'dependent'    => false,
				'conditions'   => '',
				'fields'       => '',
				'order'        => '',
				'limit'        => '',
				'offset'       => '',
				'exclusive'    => '',
				'finderQuery'  => '',
				'counterQuery' => ''
			),
		);

		/**
		 * hasMany associations
		 *
		 * @var array
		 */
		public $hasMany = array(
			'CamposElemento' => array(
				'className'    => 'Campo',
				'foreignKey'   => 'campo_id',
				'dependent'    => true,
				'conditions'   => '',
				'fields'       => '',
				'order'        => 'CamposElemento.posicion ASC',
				'limit'        => '',
				'offset'       => '',
				'exclusive'    => '',
				'finderQuery'  => '',
				'counterQuery' => ''
			),
		);
	}
