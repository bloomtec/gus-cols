<?php
	App::uses('AppModel', 'Model');
	/**
	 * Coleccion Model
	 *
	 * @property Usuario $Usuario
	 * @property Grupo   $Grupo
	 */
	class Coleccion extends AppModel {

		public $actsAs = array('Logger');

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
				'nombreValido' => array(
					'rule' => array('nombreValido'),
					'message' => 'El nombre no puede contener los caracteres: \ / : * ? " < > |',
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
		 * nombreValido method
		 *
		 * @return bool
		 */
		public function nombreValido() {
			if(strpos($this->data['Coleccion']['nombre'], "\\") !== false) {
				return false;
			}
			if(strpos($this->data['Coleccion']['nombre'], "/") !== false) {
				return false;
			}
			if(strpos($this->data['Coleccion']['nombre'], "*") !== false) {
				return false;
			}
			if(strpos($this->data['Coleccion']['nombre'], "?") !== false) {
				return false;
			}
			if(strpos($this->data['Coleccion']['nombre'], '"') !== false) {
				return false;
			}
			if(strpos($this->data['Coleccion']['nombre'], "<") !== false) {
				return false;
			}
			if(strpos($this->data['Coleccion']['nombre'], ">") !== false) {
				return false;
			}
			if(strpos($this->data['Coleccion']['nombre'], "|") !== false) {
				return false;
			}
			return true;
		}

		/**
		 * afterDelete method
		 *
		 * @return bool
		 */
		public function beforeDelete($cascade = true) {
			$this->recursive = -1;
			$coleccion = $this->read(null, $this->id);
			$path = WWW_ROOT . 'files' . DS . $coleccion['Coleccion']['nombre'];
			return rmdir($path);
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
			$this->limpiarDirectorios();
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
						$campoColeccion['CamposColeccion']['usuario_id'] = $this->data['Coleccion']['usuario_id'];
						$this->CamposColeccion->create();
						if(!$this->CamposColeccion->save($campoColeccion)) {
							$this->CamposColeccion->log('Coleccion (185)::' . print_r($this->CamposColeccion->invalidFields(), true));
						}
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
				// Crear los campos
				if(isset($this->data['CamposColeccion'])) {
					foreach($this->data['CamposColeccion'] as $key => $campo) {
						$campoColeccion = array(
							'CamposColeccion' => $campo
						);
						$campoColeccion['CamposColeccion']['model'] = 'Coleccion';
						$campoColeccion['CamposColeccion']['foreign_key'] = $this->id;
						$campoColeccion['CamposColeccion']['usuario_id'] = $this->data['Coleccion']['user_id'];
						if(!isset($campoColeccion['CamposColeccion']['id'])) {
							$this->CamposColeccion->create();
						}
						if(!$this->CamposColeccion->save($campoColeccion)) {
							$this->CamposColeccion->log('Coleccion (185)::' . print_r($this->CamposColeccion->invalidFields(), true));
						}
					}
				}
			}
		}

		/**
		 * afterSaveTipoDeContenido method
		 *
		 * @param $created
		 */
		private function afterSaveTipoDeContenido($created) {
			$coleccion_id = $this->id;
			$user_id = null;
			if(!$created) {
				$permisosActuales = $this->ColeccionesGrupo->find(
					'all',
					array(
						'conditions' => array(
							'ColeccionesGrupo.coleccion_id' => $coleccion_id
						)
					)
				);
				$this->ColeccionesGrupo->deleteAll($permisosActuales);
				$camposActuales = $this->CamposColeccion->find(
					'all',
					array(
						'conditions' => array(
							'CamposColeccion.model' => 'Coleccion',
							'CamposColeccion.foreign_key' => $coleccion_id
						)
					)
				);
				/*foreach($camposActuales as $key => $campo) {
					$this->CamposColeccion->delete($campo['CamposColeccion']['id']);
				}*/
				$user_id = $this->data['Coleccion']['user_id'];
			} else {
				// Asignar el user_id
				$user_id = $this->data['Coleccion']['usuario_id'];
			}
			// Crear el directorio
			$path = WWW_ROOT . 'files' . DS . $this->data['Coleccion']['nombre'];
			if(!file_exists($path)) {
				mkdir($path, 0777);
			}
			// Crear los permisos de acceso y creación
			foreach($this->data['Grupo'] as $grupo_id => $permisos) {
				if($permisos['creación'] || $permisos['acceso']) {
					$coleccionesGrupo = array(
						'ColeccionesGrupo' => array(
							'coleccion_id' => $coleccion_id,
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
				$posicion = 1;
				foreach($this->data['Campo'] as $key => $campo) {
					$campoColeccion = array(
						'CamposColeccion' => $campo
					);
					$campoColeccion['CamposColeccion']['model'] = 'Coleccion';
					$campoColeccion['CamposColeccion']['foreign_key'] = $coleccion_id;
					$campoColeccion['CamposColeccion']['posicion'] = $posicion;
					$posicion += 1;
					if(!isset($campoColeccion['CamposColeccion']['usuario_id'])) {
						$campoColeccion['CamposColeccion']['usuario_id'] = $user_id;
					} elseif(empty($campoColeccion['CamposColeccion']['usuario_id'])) {
						$campoColeccion['CamposColeccion']['usuario_id'] = $user_id;
					}
					if(!isset($campoColeccion['CamposColeccion']['campo_id'])) {
						$this->CamposColeccion->create();
						if($this->CamposColeccion->save($campoColeccion) && $campoColeccion['CamposColeccion']['tipos_de_campo_id'] == 8) {
							$campo_id = $this->CamposColeccion->id;
							$this->agregarCamposElemento($user_id, $campo_id, $coleccion_id, $campoColeccion['CamposColeccion']['coleccion_id'], $created);
						}
					}
				}
			}
		}

		/**
		 * agregarCamposElemento method
		 *
		 * @param $coleccion_id
		 * @param $elemento_id
		 * @param $created
		 */
		private function agregarCamposElemento($user_id, $campo_id, $coleccion_id, $elemento_id, $created) {
			$this->CamposColeccion->contain();
			$campos = $this->CamposColeccion->find(
				'all',
				array(
					'conditions' => array(
						'CamposColeccion.model' => 'Coleccion',
						'CamposColeccion.foreign_key' => $elemento_id
					)
				)
			);
			foreach($campos as $key => $campo) {
				unset($campo['CamposColeccion']['id']);
				$campo['CamposColeccion']['foreign_key'] = $coleccion_id;
				$campo['CamposColeccion']['usuario_id'] = $user_id;
				if(!$campo['CamposColeccion']['campo_id']) {
					$campo['CamposColeccion']['campo_id'] = $campo_id;
					$this->CamposColeccion->create();
					if($this->CamposColeccion->save($campo) && $campo['CamposColeccion']['tipos_de_campo_id'] == 8) {
						$new_campo_id = $this->CamposColeccion->id;
						$this->agregarCamposElemento(
							$user_id,
							$new_campo_id,
							$coleccion_id,
							$campo['CamposColeccion']['coleccion_id'],
							$created
						);
					}
				}
			}
		}

		/**
		 * limpiarDirectorios method
		 *
		 * @return void
		 */
		private function limpiarDirectorios() {
			$directories = $this->find(
				'list',
				array(
					'conditions' => array(
						'Coleccion.es_tipo_de_contenido' => 1
					),
					'fields' => array(
						'Coleccion.nombre'
					)
				)
			);
			$path = WWW_ROOT . 'files';
			$handle = opendir($path);
			if ($handle) {
				while (false !== ($entry = readdir($handle))) {
					if ($entry != "." && $entry != ".." && $entry != "empty") {
						if(!in_array($entry, $directories)) {
							rmdir($path . DS . $entry);
						}
					}
				}
				closedir($handle);
			}
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
			),
			'TipoDeContenido'   => array(
				'className'  => 'Coleccion',
				'foreignKey' => 'coleccion_id',
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
			'CamposColeccion' => array(
				'className'    => 'Campo',
				'foreignKey'   => 'foreign_key',
				'dependent'    => true,
				'conditions'   => array('CamposColeccion.model' => 'Coleccion'),
				'fields'       => '',
				'order'        => 'CamposColeccion.posicion ASC',
				'limit'        => '',
				'offset'       => '',
				'exclusive'    => '',
				'finderQuery'  => '',
				'counterQuery' => ''
			),
			'Contenido' => array(
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
			'Auditoria' => array(
				'className'    => 'Auditoria',
				'foreignKey'   => 'foreign_key',
				'dependent'    => false,
				'conditions'   => array('Auditoria.model' => 'Coleccion'),
				'fields'       => '',
				'order'        => '',
				'limit'        => '',
				'offset'       => '',
				'exclusive'    => '',
				'finderQuery'  => '',
				'counterQuery' => ''
			),
		);

	}
