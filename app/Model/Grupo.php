<?php
	App::uses('AppModel', 'Model');
	/**
	 * Grupo Model
	 *
	 * @property Coleccion $Coleccion
	 * @property Usuario   $Usuario
	 */
	class Grupo extends AppModel {

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
			'nombre' => array(
				'notempty' => array(
					'rule' => array('notempty'),
					'message' => 'Debe ingresar un nombre',
					//'allowEmpty' => false,
					//'required' => false,
					//'last' => false, // Stop validation after this rule
					//'on' => 'create', // Limit validation to 'create' or 'update' operations
				),
				'isUnique' => array(
					'rule' => array('isUnique'),
					'message' => 'Ya está registrado este nombre',
					//'allowEmpty' => false,
					//'required' => false,
					//'last' => false, // Stop validation after this rule
					//'on' => 'create', // Limit validation to 'create' or 'update' operations
				),
			),
		);

		//The Associations below have been created with all possible keys, those that are not needed can be removed

		/**
		 * hasMany associations
		 *
		 * @var array
		 */
		public $hasMany = array(
			'Coleccion' => array(
				'className'    => 'Coleccion',
				'foreignKey'   => 'grupo_id',
				'dependent'    => false,
				'conditions'   => '',
				'fields'       => '',
				'order'        => '',
				'limit'        => '',
				'offset'       => '',
				'exclusive'    => '',
				'finderQuery'  => '',
				'counterQuery' => ''
			)
		);


		/**
		 * hasAndBelongsToMany associations
		 *
		 * @var array
		 */
		public $hasAndBelongsToMany = array(
			'Coleccion' => array(
				'className'             => 'Coleccion',
				'joinTable'             => 'colecciones_grupos',
				'foreignKey'            => 'grupo_id',
				'associationForeignKey' => 'coleccion_id',
				'unique'                => 'keepExisting',
				'conditions'            => '',
				'fields'                => '',
				'order'                 => '',
				'limit'                 => '',
				'offset'                => '',
				'finderQuery'           => '',
				'deleteQuery'           => '',
				'insertQuery'           => ''
			),
			'Usuario'   => array(
				'className'             => 'Usuario',
				'joinTable'             => 'grupos_usuarios',
				'foreignKey'            => 'grupo_id',
				'associationForeignKey' => 'usuario_id',
				'unique'                => 'keepExisting',
				'conditions'            => '',
				'fields'                => '',
				'order'                 => '',
				'limit'                 => '',
				'offset'                => '',
				'finderQuery'           => '',
				'deleteQuery'           => '',
				'insertQuery'           => ''
			)
		);

	}
