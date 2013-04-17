<?php
	App::uses('AppModel', 'Model');
	/**
	 * Coleccion Model
	 *
	 * @property Usuario $Usuario
	 * @property Grupo   $Grupo
	 */
	class Coleccion extends AppModel {

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
			'usuario_id'     => array(
				'numeric' => array(
					'rule' => array('numeric'),
					//'message' => 'Your custom message here',
					//'allowEmpty' => false,
					//'required' => false,
					//'last' => false, // Stop validation after this rule
					//'on' => 'create', // Limit validation to 'create' or 'update' operations
				),
			),
			'grupo_id'       => array(
				'validarEsAuditable' => array(
					'rule' => array('validarEsAuditable'),
					'message' => 'Seleccione el grupo auditor',
					//'allowEmpty' => false,
					//'required' => false,
					//'last' => false, // Stop validation after this rule
					//'on' => 'create', // Limit validation to 'create' or 'update' operations
				),
			),
			'nombre'         => array(
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
					'message' => 'El nombre ingresado ya existe',
					//'allowEmpty' => false,
					//'required' => false,
					//'last' => false, // Stop validation after this rule
					//'on' => 'create', // Limit validation to 'create' or 'update' operations
				),
			),
			'es_auditable'   => array(
				'boolean' => array(
					'rule' => array('boolean'),
					//'message' => 'Your custom message here',
					//'allowEmpty' => false,
					//'required' => false,
					//'last' => false, // Stop validation after this rule
					//'on' => 'create', // Limit validation to 'create' or 'update' operations
				),
			),
			'acceso_anonimo' => array(
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

		/**
		 * validarEsAuditable method
		 *
		 * @return bool
		 */
		public function validarEsAuditable() {
			if($this->data['Coleccion']['es_auditable'] && empty($this->data['Coleccion']['grupo_id'])) {
				return false;
			} else {
				return true;
			}
		}

		/**
		 * afterSave method
		 *
		 * @return void
		 */
		public function afterSave($created) {
			/**
			 * Verificar si es tipo de contenido o contenido
			 */
			if($this->data['Coleccion']['es_tipo_de_contenido']) {
				$this->afterSaveTipoDeContenido($created);
			} else {
				$this->afterSaveContenido($created);
			}
		}

		/**
		 * afterSaveContenido method
		 *
		 * @param $created
		 */
		private function afterSaveContenido($created) {
			if($created) {
				// Crear los campos
				if(isset($this->data['CamposColeccion'])) {
					foreach($this->data['CamposColeccion'] as $key => $campo) {
						$campoColeccion = array(
							'CamposColeccion' => $campo
						);
						$campoColeccion['CamposColeccion']['model'] = 'Coleccion';
						$campoColeccion['CamposColeccion']['foreign_key'] = $this->id;
						$this->CamposColeccion->create();
						$this->CamposColeccion->save($campoColeccion);
					}
				}
				// Crear los permisos de acceso y creación
				$colecciones_grupos = $this->ColeccionesGrupo->find(
					'all',
					array(
						'conditions' => array(
							'ColeccionesGrupo.coleccion_id' => $this->data['Coleccion']['coleccion_id']
						),
						'fields' => array(
							'ColeccionesGrupo.grupo_id',
							'ColeccionesGrupo.creación',
							'ColeccionesGrupo.acceso'
						)
					)
				);
				foreach($colecciones_grupos as $key => $coleccion_grupo) {
					$coleccion_grupo['ColeccionesGrupo']['coleccion_id'] = $this->id;
					$this->ColeccionesGrupo->create();
					$this->ColeccionesGrupo->save($coleccion_grupo);
				}
			} else {

			}
		}

		/**
		 * afterSaveTipoDeContenido method
		 *
		 * @param $created
		 */
		private function afterSaveTipoDeContenido($created) {
			if(!$created) {
				$permisosActuales = $this->ColeccionesGrupo->find(
					'all',
					array(
						'conditions' => array(
							'ColeccionesGrupo.coleccion_id' => $this->id
						)
					)
				);
				$this->ColeccionesGrupo->deleteAll($permisosActuales);
				$camposActuales = $this->CamposColeccion->find(
					'all',
					array(
						'conditions' => array(
							'CamposColeccion.model' => 'Coleccion',
							'CamposColeccion.foreign_key' => $this->id
						)
					)
				);
				foreach($camposActuales as $key => $campo) {
					$this->CamposColeccion->delete($campo['CamposColeccion']['id']);
				}
			}
			// Crear los permisos de acceso y creación
			foreach($this->data['Grupo'] as $grupo_id => $permisos) {
				if($permisos['creación'] || $permisos['acceso']) {
					$coleccionesGrupo = array(
						'ColeccionesGrupo' => array(
							'coleccion_id' => $this->id,
							'grupo_id' => $grupo_id,
							'creación' => $permisos['creación'],
							'acceso' => $permisos['acceso']
						)
					);
					$this->ColeccionesGrupo->create();
					$this->ColeccionesGrupo->save($coleccionesGrupo);
				}
			}
			// Crear los campos
			if(isset($this->data['Campo'])) {
				foreach($this->data['Campo'] as $key => $campo) {
					$campoColeccion = array(
						'CamposColeccion' => $campo
					);
					$campoColeccion['CamposColeccion']['model'] = 'Coleccion';
					$campoColeccion['CamposColeccion']['foreign_key'] = $this->id;
					$this->CamposColeccion->create();
					$this->CamposColeccion->save($campoColeccion);
				}
			}
			// Crear el directorio
			mkdir(WWW_ROOT . 'files' . DS . $this->data['Coleccion']['nombre'], 0777);
		}

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
			),
			'Grupo'   => array(
				'className'  => 'Grupo',
				'foreignKey' => 'grupo_id',
				'conditions' => '',
				'fields'     => '',
				'order'      => ''
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
				'joinTable'             => 'colecciones_grupos',
				'foreignKey'            => 'coleccion_id',
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

		/**
		 * hasMany associations
		 *
		 * @var array
		 */
		public $hasMany = array(
			'CampoTipoElemento' => array(
				'className'    => 'Campo',
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
			'CamposColeccion' => array(
				'className'    => 'Campo',
				'foreignKey'   => 'foreign_key',
				'dependent'    => true,
				'conditions'   => array('CamposColeccion.model' => 'Coleccion'),
				'fields'       => '',
				'order'        => '',
				'limit'        => '',
				'offset'       => '',
				'exclusive'    => '',
				'finderQuery'  => '',
				'counterQuery' => ''
			)
		);

	}
