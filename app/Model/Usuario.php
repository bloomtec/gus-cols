<?php
	App::uses('AppModel', 'Model');
	App::uses('AuthComponent', 'Controller/Component');
	/**
	 * Usuario Model
	 *
	 * @property Auditoria $Auditoria
	 * @property Coleccion $Coleccion
	 * @property Grupo     $Grupo
	 */
	class Usuario extends AppModel {

		/**
		 * Display field
		 *
		 * @var string
		 */
		public $displayField = 'documento';

		/**
		 * Validation rules
		 *
		 * @var array
		 */
		public $validate = array(
			'documento'  => array(
				'notempty' => array(
					'rule'    => array('notempty'),
					'message' => 'Debe ingresar su documento',
					//'allowEmpty' => false,
					//'required' => false,
					//'last' => false, // Stop validation after this rule
					//'on' => 'create', // Limit validation to 'create' or 'update' operations
				),
				'isUnique'     => array(
					'rule'    => array('isUnique'),
					'message' => 'Este documento ya está registrado',
					//'allowEmpty' => false,
					//'required' => false,
					//'last' => false, // Stop validation after this rule
					//'on' => 'create', // Limit validation to 'create' or 'update' operations
				),
			),
			'contraseña' => array(
				'notempty'  => array(
					'rule'    => array('notempty'),
					'message' => 'Debe ingresar una contraseña',
					//'allowEmpty' => false,
					//'required' => false,
					//'last' => false, // Stop validation after this rule
					//'on' => 'create', // Limit validation to 'create' or 'update' operations
				),
				'minlength' => array(
					'rule'    => array('minlength', 8),
					'message' => 'La contraseña debe de ser de al menos 8 caracteres',
					//'allowEmpty' => false,
					//'required' => false,
					//'last' => false, // Stop validation after this rule
					//'on' => 'create', // Limit validation to 'create' or 'update' operations
				),
			),
			'nombres'    => array(
				'notempty' => array(
					'rule'    => array('notempty'),
					'message' => 'Debe ingresar su(s) nombre(s)',
					//'allowEmpty' => false,
					//'required' => false,
					//'last' => false, // Stop validation after this rule
					//'on' => 'create', // Limit validation to 'create' or 'update' operations
				),
			),
			'apellidos'  => array(
				'notempty' => array(
					'rule'    => array('notempty'),
					'message' => 'Ingrese sus apellidos',
					//'allowEmpty' => false,
					//'required' => false,
					//'last' => false, // Stop validation after this rule
					//'on' => 'create', // Limit validation to 'create' or 'update' operations
				),
			),
			'activo'     => array(
				'boolean' => array(
					'rule' => array('boolean'),
					//'message' => 'Your custom message here',
					//'allowEmpty' => false,
					//'required' => false,
					//'last' => false, // Stop validation after this rule
					//'on' => 'create', // Limit validation to 'create' or 'update' operations
				),
			),
		);

		public function beforeSave($options = array()) {
			if(isset($this -> data['Usuario']['contraseña']) && !empty($this -> data['Usuario']['contraseña'])) {
				$this -> data['Usuario']['contraseña'] = AuthComponent::password($this -> data['Usuario']['contraseña']);
			}
		}

		//The Associations below have been created with all possible keys, those that are not needed can be removed

		/**
		 * hasMany associations
		 *
		 * @var array
		 */
		public $hasMany = array(
			'Auditoria' => array(
				'className'    => 'Auditoria',
				'foreignKey'   => 'usuario_id',
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
			'Coleccion' => array(
				'className'    => 'Coleccion',
				'foreignKey'   => 'usuario_id',
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
			'Grupo' => array(
				'className'             => 'Grupo',
				'joinTable'             => 'grupos_usuarios',
				'foreignKey'            => 'usuario_id',
				'associationForeignKey' => 'grupo_id',
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
