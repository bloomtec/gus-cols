<?php
	App::uses('AppController', 'Controller');
	/**
	 * Colecciones Controller
	 *
	 * @property Coleccion $Coleccion
	 */
	class ColeccionesController extends AppController {

		public $uses = array('Coleccion', 'Campo');

		/**
		 * beforeFilter method
		 *
		 * @return void
		 */
		public function beforeFilter() {
			parent::beforeFilter();

			// Limite de items paginados
			if(!$this->Session->read('Paginator.limit')) {
				$this->Session->write('Paginator.limit', 10);
			}
		}

		/**
		 * removerFiltro method
		 *
		 * @param $coleccion_id
		 */
		public function removerFiltro($coleccion_id, $auditable) {
			$this->_removerFiltro($coleccion_id, $auditable);
		}

		/**
		 * admin_removerFiltro method
		 *
		 * @param $coleccion_id
		 */
		public function admin_removerFiltro($coleccion_id, $auditable) {
			$this->_removerFiltro($coleccion_id, $auditable);
		}

		/**
		 * @param $coleccion_id
		 * @param $auditable
		 */
		private function _removerFiltro($coleccion_id, $auditable) {
			$this->Session->write('Filtros.activos', 0);
			$this->redirect(array('action' => 'index', $coleccion_id, $auditable));
		}

		/**
		 * @param $coleccion_id
		 */
		public function removerFiltroPublico($coleccion_id) {
			$this->Session->write('Filtros.activos', 0);
			$this->redirect(array('action' => 'indice', $coleccion_id));
		}

		/**
		 * @return array
		 */
		public function esAuditable($id, $ct_index) {
			$user_id = $this->Auth->user('id');
			$grupos_usuario = $this->Coleccion->Usuario->GruposUsuario->find(
				'list',
				array(
					'conditions' => array(
						'GruposUsuario.usuario_id' => $user_id
					),
					'fields' => array(
						'GruposUsuario.grupo_id'
					)
				)
			);
			$this->Coleccion->contain('Grupo');
			$conditions = array(
				'Coleccion.es_auditable' => 1,
				'Coleccion.auditada' => 0,
				'Coleccion.grupo_id' => $grupos_usuario,
				'Coleccion.es_tipo_de_contenido' => 0
			);
			if($ct_index) {
				$conditions['Coleccion.coleccion_id'] = $id;
			} else {
				// TODO
			}
			$auditables = $this->Coleccion->find(
				'list',
				array(
					'conditions' => $conditions,
					'fields' => array(
						'Coleccion.id'
					)
				)
			);
			return !empty($auditables) ? 1 : 0;
		}

		public function verificarContenidoARevisar($coleccion_id) {
			$user_id = $this->Auth->user('id');
			$colecciones = $this->Coleccion->find(
				'all',
				array(
					'conditions' => array(
						'Coleccion.es_auditable' => 1,
						'Coleccion.auditada' => 1,
						'Coleccion.publicada' => 0,
						'Coleccion.usuario_id' => $user_id,
						'Coleccion.coleccion_id' => $coleccion_id
					)
				)
			);
			return empty($colecciones) ? 0 : 1;
		}

		/**
		 * @return array
		 */
		public function verificarAuditables() {
			$user_id = $this->Auth->user('id');
			$grupos_usuario = $this->Coleccion->Usuario->GruposUsuario->find(
				'list',
				array(
					'conditions' => array(
						'GruposUsuario.usuario_id' => $user_id
					),
					'fields' => array(
						'GruposUsuario.grupo_id'
					)
				)
			);
			$this->Coleccion->contain('Grupo');
			$auditables = $this->Coleccion->find(
				'list',
				array(
					'conditions' => array(
						'Coleccion.es_auditable' => 1,
						'Coleccion.auditada' => 0,
						'Coleccion.grupo_id' => $grupos_usuario,
						'Coleccion.es_tipo_de_contenido' => 0
					),
					'fields' => array(
						'Coleccion.id'
					)
				)
			);
			return $auditables;
		}

		/**
		 * verificarCrear method
		 *
		 * @param $usuario_id
		 * @param $coleccion_id
		 *
		 * @return bool
		 */
		public function verificarCrear($usuario_id, $coleccion_id) {
			$gruposColeccion = $this->Coleccion->ColeccionesGrupo->find(
				'list',
				array(
					'conditions' => array(
						'ColeccionesGrupo.coleccion_id' => $coleccion_id,
						'ColeccionesGrupo.creación' => 1
					),
					'fields' => array(
						'ColeccionesGrupo.grupo_id'
					)
				)
			);
			$gruposUsuario = $this->Coleccion->Usuario->GruposUsuario->find(
				'list',
				array(
					'conditions' => array(
						'GruposUsuario.usuario_id' => $usuario_id
					),
					'fields' => array(
						'GruposUsuario.grupo_id'
					)
				)
			);
			$interseccion = array_intersect($gruposUsuario, $gruposColeccion);
			if(!empty($interseccion)) {
				return true;
			} else {
				return false;
			}
		}

		/**
		 * verificarEliminar method
		 *
		 * @param $coleccion_id
		 *
		 * @return bool
		 */
		public function verificarListar($coleccion_id) {
			$this->Coleccion->contain('Contenido');
			$colecciones = $this->Coleccion->find(
				'all',
				array(
					'conditions' => array(
						'Coleccion.coleccion_id' => $coleccion_id,
						'OR' => array(
							'Coleccion.es_auditable' => 0,
							'Coleccion.publicada' => 1
						)
					)
				)
			);
			if(empty($colecciones)) {
				return false;
			} else {
				return true;
			}
		}

		/**
		 * verificarEliminar method
		 *
		 * @param $usuario_id
		 * @param $coleccion_id
		 *
		 * @return bool
		 */
		public function verificarEliminar($usuario_id, $coleccion_id) {
			// Validación por acceso
			$gruposColeccion = $this->Coleccion->ColeccionesGrupo->find(
				'list',
				array(
					'conditions' => array(
						'ColeccionesGrupo.coleccion_id' => $coleccion_id,
						'ColeccionesGrupo.creación' => 1
					),
					'fields' => array(
						'ColeccionesGrupo.grupo_id'
					)
				)
			);
			$gruposUsuario = $this->Coleccion->Usuario->GruposUsuario->find(
				'list',
				array(
					'conditions' => array(
						'GruposUsuario.usuario_id' => $usuario_id
					),
					'fields' => array(
						'GruposUsuario.grupo_id'
					)
				)
			);
			$interseccion = array_intersect($gruposUsuario, $gruposColeccion);

			// Validación por contenido creado
			$this->Coleccion->contain('Contenido');
			$coleccion = $this->Coleccion->read(null, $coleccion_id);
			if(!empty($interseccion) && empty($coleccion['Contenido'])) {
				return true;
			} else {
				return false;
			}
		}

		/**
		 * verificarModificar method
		 *
		 * @param $usuario_id
		 * @param $coleccion_id
		 *
		 * @return bool
		 */
		public function verificarModificar($usuario_id, $coleccion_id) {
			// Validación por acceso
			$gruposColeccion = $this->Coleccion->ColeccionesGrupo->find(
				'list',
				array(
					'conditions' => array(
						'ColeccionesGrupo.coleccion_id' => $coleccion_id,
						'ColeccionesGrupo.creación' => 1
					),
					'fields' => array(
						'ColeccionesGrupo.grupo_id'
					)
				)
			);
			$gruposUsuario = $this->Coleccion->Usuario->GruposUsuario->find(
				'list',
				array(
					'conditions' => array(
						'GruposUsuario.usuario_id' => $usuario_id
					),
					'fields' => array(
						'GruposUsuario.grupo_id'
					)
				)
			);

			$this->Coleccion->contain('Contenido');
			$coleccion = $this->Coleccion->read(null, $coleccion_id);

			if($coleccion['Coleccion']['es_tipo_de_contenido']) {
				$grupoPermitido = array(0 => 2);
				$interseccion = array_intersect($gruposUsuario, $gruposColeccion, $grupoPermitido);
				if(!empty($interseccion)) {
					return true;
				} else {
					return false;
				}
			} else {
				$interseccion = array_intersect($gruposUsuario, $gruposColeccion);
				if(!empty($interseccion)) {
					return true;
				} else {
					return false;
				}
			}
		}

		/**
		 * setPaginatorLimit method
		 *
		 * @param $limit
		 */
		public function setPaginatorLimit($limit) {
			if($this->request->is('ajax') && is_numeric($limit)) {
				$this->Session->write('Paginator.limit', $limit);
				echo json_encode(array('success', true));
			} else {
				echo json_encode(array('success', false));
			}
			exit(0);
		}

		/**
		 * uploaded method
		 *
		 * @param $ruta
		 */
		public function uploaded($coleccion, $nombre) {
			$ruta = WWW_ROOT . 'files' . DS . $coleccion . DS . $nombre;
			echo json_encode(
				array(
					'success' => file_exists($ruta),
					'coleccion' => $coleccion,
					'nombre' => $nombre,
					'ruta' => $ruta
				)
			);
			exit(0);
		}

		/**
		 * admin_download method
		 *
		 * @param $encoded
		 */
		public function admin_download($encoded) {
			return $this->_download($encoded);
		}

		/**
		 * download method
		 *
		 * @param $encoded
		 */
		public function download($encoded) {
			return $this->_download($encoded);
		}

		/**
		 * _download method
		 *
		 * @param $encoded
		 */
		private function _download($encoded) {
			$decoded = html_entity_decode($encoded);
			$json = (array) json_decode($decoded);
			$this->response->file(
				WWW_ROOT . 'files' . DS . $json[3] . DS . $json[4] . DS . $json[0],
				array(
					'download' => true,
					'name' => $json[1]
				)
			);
			return $this->response;
		}

		/**
		 * admin_delete method
		 *
		 * @param string $id
		 *
		 * @return void
		 */
		public function admin_delete($id = null) {
			$this->_delete($id);
		}

		/**
		 * delete method
		 *
		 * @param string $id
		 *
		 * @return void
		 */
		public function delete($id = null) {
			$this->_delete($id);
		}

		/**
		 * _delete method
		 *
		 * @throws NotFoundException
		 *
		 * @param string $id
		 *
		 * @return void
		 */
		private function _delete($id = null) {
			$usuario_id = $this->Auth->user('id');
			$this->Coleccion->id = $id;
			if(!$this->Coleccion->exists()) {
				throw new NotFoundException(__('Colección no válida'));
			}
			if($this->verificarEliminar($usuario_id, $id)) {
				if($this->Coleccion->delete()) {
					$this->Session->setFlash(__('Se eliminó la colección'));
					$this->redirect(array('action' => 'index'));
				}
				$this->Session->setFlash(__('No se pudo eliminar la colección'));
			} else {
				$this->Session->setFlash(__('No tiene permiso para realizar esta acción o la colección tiene listados'));
			}
			$this->redirect(array('action' => 'index'));
		}

		public function getPublicMenu() {
			$colecciones = $this->Coleccion->find(
				'all',
				array(
					'conditions' => array(
						'Coleccion.es_tipo_de_contenido' => 1,
						'Coleccion.acceso_anonimo' => 1
					)
				)
			);
			$this->exit(0);
		}

		/**
		 * @param $coleccion_id
		 */
		public function indice($coleccion_id) {
			$this -> layout ="front";
			// Condiciones
			$conditions = array();
			$ultimosCampos = array();

			if($coleccion_id) {
				$this->Coleccion->contain(
					'CamposColeccion',
					'TipoDeContenido'
				);
				$this->set('coleccionBase', $this->Coleccion->read(null, $coleccion_id));
				if(!$this->Session->read('Filtros.activos')) {
					$this->Session->write('Filtros.activos', 0);
				}
				$this->Coleccion->contain(
					'TipoDeContenido',
					'CamposColeccion.listado = 1'
				);
				$conditions['Coleccion.publicada'] = 1;
				$conditions['Coleccion.es_tipo_de_contenido'] = 0;
				$conditions['Coleccion.coleccion_id'] = $coleccion_id;
				if($this->request->is('post')) {
					$ultimosCampos = $this->Session->read('Filtros.ultimo');
					// Guardar los ID's de las colecciones que contengan campos que hayan salido
					// con resultados para los filtros aplicados
					$colecciones = array();

					// Recorrer los filtros definidos
					foreach($this->request->data['Filtros'] as $key => $filtro) {
						$listados = array();
						// Filtrado por listas
						if(isset($filtro[5]) && !empty($filtro[5]['value'])) {
							$this->Session->write('Filtros.activos', 1);
							$listados = $this->Coleccion->CamposColeccion->find(
								'list',
								array(
									'conditions' => array(
										'CamposColeccion.seleccion_lista_predefinida LIKE' => '%' . $filtro[5]['value'] . '%',
										'CamposColeccion.lista_predefinida' => $filtro[5]['lista']
									),
									'fields' => 'CamposColeccion.foreign_key'
								)
							);
						}
						// Filtrado por texto
						if(isset($filtro[2]) && !empty($filtro[2]['value'])) {
							$listados = $this->Coleccion->CamposColeccion->find(
								'list',
								array(
									'conditions' => array(
										'CamposColeccion.texto LIKE' => '%' . $filtro[2]['value'] . '%'
									),
									'fields' => 'CamposColeccion.foreign_key'
								)
							);
						}
						// Filtrado por fecha
						if(isset($filtro[7])) {
							$min_date = null;
							$max_date = null;
							if(!empty($filtro[7]['value']['min'])) $min_date = $filtro[7]['value']['min'];
							if(!empty($filtro[7]['value']['max'])) $max_date = $filtro[7]['value']['max'];
							if(($min_date || $max_date) && (!$min_date || !$max_date)) {
								$this->Session->setFlash(
									"Debe ingresar una fecha en ambos campos."
										. "\n Si busca para un día especifico ingrese la misma fecha en ambos campos."
								);
							} else {
								$min = strtotime($min_date);
								$max = strtotime($max_date);

								if($min > $max) {
									$this->Session->setFlash(
										"No puede poner la fecha menor con un valor superior a la fecha mayor"
									);
								} else {
									$listados = $this->Coleccion->CamposColeccion->find(
										'list',
										array(
											'conditions' => array(
												'CamposColeccion.fecha BETWEEN ? AND ?' => array($min_date, $max_date)
											),
											'fields' => 'CamposColeccion.foreign_key'
										)
									);
								}
							}
						}
						// Filtrado por número
						if(isset($filtro[6])) {
							$min = null;
							$max = null;
							if(!empty($filtro[6]['value']['min'])) $min = $filtro[6]['value']['min'];
							if(!empty($filtro[6]['value']['max'])) $max = $filtro[6]['value']['max'];
							if((is_numeric($min) || is_numeric($max)) && (!is_numeric($min) || !is_numeric($max))) {
								$this->Session->setFlash(
									"Debe ingresar un valor en ambos campos numéricos."
										. "\n Si busca un valor especifico ingrese el mismo valor en ambos campos."
								);
							} else {
								if($min > $max) {
									$this->Session->setFlash(
										"No puede poner el número menor con un valor superior al número mayor"
									);
								} else {
									$listados = $this->Coleccion->CamposColeccion->find(
										'list',
										array(
											'conditions' => array(
												'CamposColeccion.numero BETWEEN ? AND ?' => array($min, $max)
											),
											'fields' => 'CamposColeccion.foreign_key'
										)
									);
								}
							}
						}
						if(empty($colecciones)) {
							foreach($listados as $key => $foreign_key) {
								if(!in_array($foreign_key, $colecciones)) {
									$colecciones[] = $foreign_key;
								}
							}
						} else {
							if(!empty($listados)) {
								$found = false;
								foreach($listados as $key => $foreign_key) {
									if(in_array($foreign_key, $colecciones)) {
										$found = true;
									}
								}
								if(!$found) {
									$colecciones = array();
									break;
								}
							}
						}
					}

					// Asignar el filtro de ID's
					if(!empty($colecciones)) {
						$conditions['Coleccion.id'] = $colecciones;
						$this->Session->write('Filtros.activos', 1);
					} else {
						$this->Session->setFlash('La selección de filtros actual no ha retornado resultados');
					}
				}
				$this->set('filtrado', $this->Session->read('Filtros.activos'));
			} else {
				$this->Coleccion->contain('TipoDeContenido', 'Contenido');
				$conditions['Coleccion.es_tipo_de_contenido'] = true;
			}

			// Hay usuario logueado?
			if(!$this->Auth->user('id')) {
				$conditions['Coleccion.acceso_anonimo'] = 1;
			}

			//debug($conditions);

			$this->paginate = array(
				'conditions' => $conditions
			);

			$paginated = $this->paginate();

			if($coleccion_id) {
				foreach($paginated as $key => $value) {
					$tmp = $value['CamposColeccion'];
					unset($paginated[$key]['CamposColeccion']);
					$paginated[$key]['Campo'] = $tmp;
				}
			}

			$this->set('ultimosCampos', $ultimosCampos);
			$this->set('colecciones', $paginated);
			$this->set('coleccion_id', $coleccion_id);
		}

		/**
		 * admin_index method
		 *
		 * @return void
		 */
		public function admin_index($coleccion_id = null, $auditable = 0, $revision = 0) {
			$this->_index($coleccion_id, $auditable, $revision);
		}

		/**
		 * index method
		 *
		 * @return void
		 */
		public function index($coleccion_id = null, $auditable = 0, $revision = 0) {
            $this->_index($coleccion_id, $auditable, $revision);
		}

		/**
		 * _index method
		 *
		 * @param $coleccion_id
		 *
		 * @return void
		 */
		private function _index($coleccion_id, $auditable, $revision) {
			// Condiciones
			$conditions = array();
			$ultimosCampos = array();

			if($coleccion_id || $auditable || $revision) {
				$this->Coleccion->contain(
					'CamposColeccion',
					'TipoDeContenido'
				);
				$this->set('coleccionBase', $this->Coleccion->read(null, $coleccion_id));
				if(!$this->Session->read('Filtros.activos')) {
					$this->Session->write('Filtros.activos', 0);
				}
				$this->Coleccion->contain(
					'TipoDeContenido',
					'CamposColeccion.listado = 1'
				);
				if(!$auditable && !$revision) {
					$conditions['Coleccion.publicada'] = 1;
				} elseif($revision) {
					$conditions['Coleccion.es_auditable'] = 1;
					$conditions['Coleccion.auditada'] = 1;
					$conditions['Coleccion.publicada'] = 0;
				} else {
					$conditions['Coleccion.es_auditable'] = 1;
					$conditions['Coleccion.auditada'] = 0;
				}
				$conditions['Coleccion.es_tipo_de_contenido'] = 0;
				$conditions['Coleccion.coleccion_id'] = $coleccion_id;
				if($this->request->is('post')) {
					$ultimosCampos = $this->Session->read('Filtros.ultimo');
					// Guardar los ID's de las colecciones que contengan campos que hayan salido
					// con resultados para los filtros aplicados
					$colecciones = array();

					// Recorrer los filtros definidos
					foreach($this->request->data['Filtros'] as $key => $filtro) {
						$listados = array();
						// Filtrado por listas
						if(isset($filtro[5]) && !empty($filtro[5]['value'])) {
							$this->Session->write('Filtros.activos', 1);
							$listados = $this->Coleccion->CamposColeccion->find(
								'list',
								array(
									'conditions' => array(
										'CamposColeccion.seleccion_lista_predefinida LIKE' => '%' . $filtro[5]['value'] . '%',
										'CamposColeccion.lista_predefinida' => $filtro[5]['lista']
									),
									'fields' => 'CamposColeccion.foreign_key'
								)
							);
						}
						// Filtrado por texto
						if(isset($filtro[2]) && !empty($filtro[2]['value'])) {
							$listados = $this->Coleccion->CamposColeccion->find(
								'list',
								array(
									'conditions' => array(
										'CamposColeccion.texto LIKE' => '%' . $filtro[2]['value'] . '%'
									),
									'fields' => 'CamposColeccion.foreign_key'
								)
							);
						}
						// Filtrado por fecha
						if(isset($filtro[7])) {
							$min_date = null;
							$max_date = null;
							if(!empty($filtro[7]['value']['min'])) $min_date = $filtro[7]['value']['min'];
							if(!empty($filtro[7]['value']['max'])) $max_date = $filtro[7]['value']['max'];
							if(($min_date || $max_date) && (!$min_date || !$max_date)) {
								$this->Session->setFlash(
									"Debe ingresar una fecha en ambos campos."
									. "\n Si busca para un día especifico ingrese la misma fecha en ambos campos."
								);
							} else {
								$min = strtotime($min_date);
								$max = strtotime($max_date);

								if($min > $max) {
									$this->Session->setFlash(
										"No puede poner la fecha menor con un valor superior a la fecha mayor"
									);
								} else {
									$listados = $this->Coleccion->CamposColeccion->find(
										'list',
										array(
											'conditions' => array(
												'CamposColeccion.fecha BETWEEN ? AND ?' => array($min_date, $max_date)
											),
											'fields' => 'CamposColeccion.foreign_key'
										)
									);
								}
							}
						}
						// Filtrado por número
						if(isset($filtro[6])) {
							$min = null;
							$max = null;
							if(!empty($filtro[6]['value']['min'])) $min = $filtro[6]['value']['min'];
							if(!empty($filtro[6]['value']['max'])) $max = $filtro[6]['value']['max'];
							if((is_numeric($min) || is_numeric($max)) && (!is_numeric($min) || !is_numeric($max))) {
								$this->Session->setFlash(
									"Debe ingresar un valor en ambos campos numéricos."
										. "\n Si busca un valor especifico ingrese el mismo valor en ambos campos."
								);
							} else {
								if($min > $max) {
									$this->Session->setFlash(
										"No puede poner el número menor con un valor superior al número mayor"
									);
								} else {
									$listados = $this->Coleccion->CamposColeccion->find(
										'list',
										array(
											'conditions' => array(
												'CamposColeccion.numero BETWEEN ? AND ?' => array($min, $max)
											),
											'fields' => 'CamposColeccion.foreign_key'
										)
									);
								}
							}
						}
						if(empty($colecciones)) {
							foreach($listados as $key => $foreign_key) {
								if(!in_array($foreign_key, $colecciones)) {
									$colecciones[] = $foreign_key;
								}
							}
						} else {
							if(!empty($listados)) {
								$found = false;
								foreach($listados as $key => $foreign_key) {
									if(in_array($foreign_key, $colecciones)) {
										$found = true;
									}
								}
								if(!$found) {
									$colecciones = array();
									break;
								}
							}
						}
					}

					// Asignar el filtro de ID's
					if(!empty($colecciones)) {
						$conditions['Coleccion.id'] = $colecciones;
						$this->Session->write('Filtros.activos', 1);
					} else {
						$this->Session->setFlash('La selección de filtros actual no ha retornado resultados');
					}
				}
				$this->set('filtrado', $this->Session->read('Filtros.activos'));
			} else {
				$this->Coleccion->contain('TipoDeContenido', 'Contenido');
				$conditions['Coleccion.es_tipo_de_contenido'] = true;
			}

			// Hay usuario logueado?
			if(!$this->Auth->user('id')) {
				$conditions['Coleccion.acceso_anonimo'] = 1;
			}

			//debug($conditions);

			$this->paginate = array(
				'conditions' => $conditions
			);

			$paginated = $this->paginate();

			if($coleccion_id) {
				foreach($paginated as $key => $value) {
					$tmp = $value['CamposColeccion'];
					unset($paginated[$key]['CamposColeccion']);
					$paginated[$key]['Campo'] = $tmp;
				}
			}

			$this->set('ultimosCampos', $ultimosCampos);
			$this->set('colecciones', $paginated);
			$this->set('coleccion_id', $coleccion_id);

			// Sección para ver auditables
			$this->set('auditable', $auditable);
			$auditables = $this->verificarAuditables();
			if(!empty($auditables)) {
				$this->set('auditables', 1);
			} else {
				$this->set('auditables', 0);
			}
		}

		/**
		 * @param null $id
		 */
		public function ver($id = null) {
			$this -> layout ="front";
			$this->Coleccion->contain(
				'Usuario',
				'Grupo',
				'CamposColeccion.TiposDeCampo',
				'TipoDeContenido',
				'Auditoria'
			);
			if(!$this->Coleccion->exists($id)) {
				throw new NotFoundException(__('Invalid coleccion'));
			}
			$options = array('conditions' => array('Coleccion.' . $this->Coleccion->primaryKey => $id));
			$coleccion = $this->Coleccion->find('first', $options);
			$auditables = $this->verificarAuditables();
			$auditar = false;
			if(in_array($id, $auditables)) {
				$auditar = true;
				$this->set('user_id', $this->Auth->user('id'));
			}
			$this->set('auditar', $auditar);
			$this->set('coleccion', $coleccion);
			if($this->request->is('post') || $this->request->is('put')) {
				if(!(!$this->request->data['Coleccion']['publicada'] && empty($this->request->data['Coleccion']['observación']))) {
					if($this->Coleccion->save($this->request->data)) {
						$this->Coleccion->Auditoria->create();
						$auditoria = array(
							'Auditoria' => array(
								'usuario_id' => $this->request->data['Coleccion']['user_id'],
								'model' => 'Coleccion',
								'foreign_key' => $this->request->data['Coleccion']['id'],
								'coleccion_aprobada' => $this->request->data['Coleccion']['publicada'],
								'observación' => $this->request->data['Coleccion']['observación']
							)
						);
						if($this->Coleccion->Auditoria->save($auditoria)) {
							$this->Session->setFlash('Se guardó su revisión');
						} else {
							$this->Session->setFlash('Ocurrió un error al tratar de guardar la información de auditoría');
						}
					} else {
						$this->Session->setFlash('No se pudo guardar su revisión. Por favor, intente de nuevo.');
					}
					$this->redirect(array('action' => 'index', $coleccion['Coleccion']['coleccion_id'], 1));
				} else {
					$this->Session->setFlash('Debe ingresar su observación');
				}
			}
		}

		/**
		 * admin_view method
		 *
		 * @param string $id
		 *
		 * @return void
		 */
		public function admin_view($id = null) {
			$this->_view($id);
		}

		/**
		 * view method
		 *
		 * @param string $id
		 *
		 * @return void
		 */
		public function view($id = null) {
			$this->_view($id);
		}

		/**
		 * _view method
		 *
		 * @throws NotFoundException
		 *
		 * @param string $id
		 *
		 * @return void
		 */
		private function _view($id = null) {
			$this->Coleccion->contain(
				'Usuario',
				'Grupo',
				'CamposColeccion.TiposDeCampo',
				'TipoDeContenido',
				'Auditoria'
			);
			if(!$this->Coleccion->exists($id)) {
				throw new NotFoundException(__('Invalid coleccion'));
			}
			$options = array('conditions' => array('Coleccion.' . $this->Coleccion->primaryKey => $id));
			$coleccion = $this->Coleccion->find('first', $options);
			$auditables = $this->verificarAuditables();
			$auditar = false;
			if(in_array($id, $auditables)) {
				$auditar = true;
				$this->set('user_id', $this->Auth->user('id'));
			}
			$this->set('auditar', $auditar);
			$this->set('coleccion', $coleccion);
			if($this->request->is('post') || $this->request->is('put')) {
				if(!(!$this->request->data['Coleccion']['publicada'] && empty($this->request->data['Coleccion']['observación']))) {
					if($this->Coleccion->save($this->request->data)) {
						$this->Coleccion->Auditoria->create();
						$auditoria = array(
							'Auditoria' => array(
								'usuario_id' => $this->request->data['Coleccion']['user_id'],
								'model' => 'Coleccion',
								'foreign_key' => $this->request->data['Coleccion']['id'],
								'coleccion_aprobada' => $this->request->data['Coleccion']['publicada'],
								'observación' => $this->request->data['Coleccion']['observación']
							)
						);
						if($this->Coleccion->Auditoria->save($auditoria)) {
							$this->Session->setFlash('Se guardó su revisión');
						} else {
							$this->Session->setFlash('Ocurrió un error al tratar de guardar la información de auditoría');
						}
					} else {
						$this->Session->setFlash('No se pudo guardar su revisión. Por favor, intente de nuevo.');
					}
					$this->redirect(array('action' => 'index', $coleccion['Coleccion']['coleccion_id'], 1));
				} else {
					$this->Session->setFlash('Debe ingresar su observación');
				}
			}
		}

		/**
		 * admin_add method
		 *
		 * @return void
		 */
		public function admin_add($ct_id = false) {
			$this->_add($ct_id);
		}

		/**
		 * add method
		 *
		 * @return void
		 */
		public function add($ct_id = false) {
			$this->_add($ct_id);
		}

		/**
		 * _add method
		 *
		 * @param $ct_id
		 *
		 * @return void
		 */
		private function _add($ct_id) {
			if($ct_id && !$this->verificarCrear($this->Auth->user('id'), $ct_id)) {
				$this->Session->setFlash('No tiene permiso para ver esta sección');
				$this->redirect(array('action' => 'index'));
			} elseif(!$ct_id) {
				$this->Session->setFlash('Está tratando de acceder de manera indebida');
				$this->redirect(array('action' => 'index'));
			}
			if(!$ct_id) {
				$tipoDeContenidos = $this->Coleccion->find(
					'list',
					array(
						'conditions' => array(
							'Coleccion.es_tipo_de_contenido' => 1
						)
					)
				);
				$this->set(compact('tipoDeContenidos'));
				if($this->request->is('post')) {
					$this->redirect(array('action' => 'add', $this->request->data['Coleccion']['tipo_de_contenido']));
				}
			} else {
				if($this->request->is('post') || $this->request->is('put')) {
					if($this->Auth->user('id')) {
						$this->request->data['Coleccion']['usuario_id'] = $this->Auth->user('id');
					}
					/**
					 * Validar campos
					 */
					$requeridosValido = true;
					$datosValidos = true;
					$unicosValido = true;
					$errMsg = '';
					// Requeridos
					foreach($this->request->data['CamposColeccion'] as $key => $campo) {
						if($campo['es_requerido']) {
							switch($campo['tipos_de_campo_id']) {
								case 1:
									// Multilínea
									empty($campo['multilinea']) ? $requeridosValido = false : $requeridosValido = true;
									$errMsg = 'El campo ' . $campo['nombre'] . ' no puede estar sin completar';
									break;
								case 2:
									// Texto
									empty($campo['texto']) ? $requeridosValido = false : $requeridosValido = true;
									$errMsg = 'El campo ' . $campo['nombre'] . ' no puede estar sin completar';
									break;
								case 3:
									// Archivo
									empty($campo['nombre_de_archivo']) ? $requeridosValido = false : $requeridosValido = true;
									$errMsg = 'Debe subir un archivo para el campo ' . $campo['nombre'];
									break;
								case 4:
									// Imagen
									empty($campo['imagen']) ? $requeridosValido = false : $requeridosValido = true;
									$errMsg = 'Debe subir una imagen para el campo ' . $campo['nombre'];
									break;
								case 5:
									// Lista
									empty($campo['seleccion_lista_predefinida']) ? $requeridosValido = false : $requeridosValido = true;
									$errMsg = 'Debe seleccionar un valor para el campo ' . $campo['nombre'];
									break;
								case 6:
									// Número
									empty($campo['numero']) ? $requeridosValido = false : $requeridosValido = true;
									$errMsg = 'El campo ' . $campo['nombre'] . ' no puede estar sin completar';
									break;
								case 7:
									// Fecha
									empty($campo['fecha']) ? $requeridosValido = false : $requeridosValido = true;
									$errMsg = 'El campo ' . $campo['nombre'] . ' no puede estar sin completar';
									break;
							}
						}
						if(!$requeridosValido) break;
					}
					if($requeridosValido) {
						// Datos correctos
						foreach($this->request->data['CamposColeccion'] as $key => $campo) {
							switch($campo['tipos_de_campo_id']) {
								case 6:
									// Número
									is_numeric(trim($campo['numero'])) ? $datosValidos = true : $datosValidos = false;
									$errMsg = 'Dato erróneo en el campo ' . $campo['nombre'];
									break;
								case 7:
									// Fecha
									$date_format = 'Y-m-d';
									$input = trim($campo['fecha']);
									$time = strtotime($input);
									(date($date_format, $time) == $input) ? $datosValidos = true : $datosValidos = false;
									$errMsg = 'Dato erróneo en el campo ' . $campo['nombre'];
									break;
							}
							if(!$requeridosValido) break;
						}
						if($datosValidos) {
							// Únicos
							foreach($this->request->data['CamposColeccion'] as $keyA => $campo) {
								if($campo['unico']) {
									$padre_id = $campo['campo_padre'];
									$this->Coleccion->CamposColeccion->contain('Hijos');
									$campoPadre = $this->Coleccion->CamposColeccion->read(null, $padre_id);
									$datoEncontrado = false;
									foreach($campoPadre['Hijos'] as $keyB => $campoHijo) {
										switch($campo['tipos_de_campo_id']) {
											case 2:
												// Texto
												(trim($campo['texto']) == trim($campoHijo['texto'])) ? $datoEncontrado = true : $datoEncontrado = false;
												$errMsg = 'El dato ingresado en el campo ' . $campo['nombre'] . ' ya existe';
												break;
											case 6:
												// Número
												($campo['numero'] == $campoHijo['numero']) ? $datoEncontrado = true : $datoEncontrado = false;
												$errMsg = 'El dato ingresado en el campo ' . $campo['nombre'] . ' ya existe';
												break;
											case 7:
												// Fecha
												($campo['fecha'] == $campoHijo['fecha']) ? $datoEncontrado = true : $datoEncontrado = false;
												$errMsg = 'El dato ingresado en el campo ' . $campo['nombre'] . ' ya existe';
												break;
										}
										if($datoEncontrado) {
											$unicosValido = false;
											break;
										}
									}
								}
								if(!$unicosValido) break;
							}
						}
					}
					/**
					 * Fin validar campos
					 */
					if(!$requeridosValido || !$datosValidos || !$unicosValido) {
						$this->Session->setFlash($errMsg);
					} else {
						$this->Coleccion->create();
						if($this->Coleccion->save($this->request->data)) {
							$this->Session->setFlash(__('The coleccion has been saved'));
							$this->redirect(array('action' => 'index'));
						} else {
							$this->Session->setFlash(__('The coleccion could not be saved. Please, try again.'));
							debug($this->Coleccion->invalidFields());
						}
					}
				}
				$contain = array(
					'CamposColeccion' => array(
						'order' => 'CamposColeccion.posicion ASC',
						'conditions' => 'CamposColeccion.tipos_de_campo_id <> 8'
					)
				);
				$options = array(
					'contain' => $contain,
					'conditions' => array('Coleccion.' . $this->Coleccion->primaryKey => $ct_id)
				);
				$this->request->data = $this->Coleccion->find('first', $options);
			}
			$this->set('ct_id', $ct_id);
		}

		/**
		 * add_campo_form_contenido method
		 *
		 * @param int $campo_id
		 */
		public function add_campo_form_contenido($campo_id, $c_name, $uid, $index) {
			$this->layout = 'ajax';
			$this->Campo->contain();
			$campo = $this->Campo->read(null, $campo_id);
			$exts = '';
			$seleccionListaPredefinidas = null;
			if($campo['Campo']['tipos_de_campo_id'] == 3) {
				$TMPexts = explode(',', $campo['Campo']['extensiones']);
				$j = count($TMPexts);
				for($i = 0; $i < $j; $i += 1) {
					if($i < $j - 1) {
						$exts .= '*.' . trim($TMPexts[$i] . '; ');
					} else {
						$exts .= '*.' . trim($TMPexts[$i]);
					}
				}
			} elseif($campo['Campo']['tipos_de_campo_id'] == 5) {
				$seleccionListaPredefinidas = explode("\n", $campo['Campo']['lista_predefinida']);
				foreach($seleccionListaPredefinidas as $key => $value) {
					$value = trim($value);
					unset($seleccionListaPredefinidas[$key]);
					if(!empty($value)) {
						$seleccionListaPredefinidas[$value] = $value;
					}
				}
			}
			//$c_name = urldecode($c_name);
			//$uid = urldecode($uid);
			$this->set(compact('index', 'c_name', 'uid', 'campo', 'campo_id', 'exts', 'seleccionListaPredefinidas'));
		}

		/**
		 * @param null $id
		 */
		public function admin_edit($id = null) {
			$this->_edit($id);
		}

		/**
		 * @param null $id
		 */
		public function edit($id = null) {
			$this->_edit($id);
		}

		/**
		 * edit method
		 *
		 * @throws NotFoundException
		 *
		 * @param string $id
		 *
		 * @return void
		 */
		private function _edit($id = null) {
			if($id && $this->verificarModificar($this->Auth->user('id'), $id)) {
				if(($this->request->is('post') || $this->request->is('put'))) {
					$this->request->data['Coleccion']['user_id'] = $this->Auth->user('id');
					if($this->request->data['Coleccion']['es_auditable']) {
						$this->request->data['Coleccion']['publicada'] = 0;
						$this->request->data['Coleccion']['auditada'] = 0;
					}

					/**
					 * Validar campos
					 */
					$requeridosValido = true;
					$datosValidos = true;
					$unicosValido = true;
					$errMsg = '';
					// Requeridos
					foreach($this->request->data['CamposColeccion'] as $key => $campo) {
						if($campo['es_requerido']) {
							switch($campo['tipos_de_campo_id']) {
								case 1:
									// Multilínea
									empty($campo['multilinea']) ? $requeridosValido = false : $requeridosValido = true;
									$errMsg = 'El campo ' . $campo['nombre'] . ' no puede estar sin completar';
									break;
								case 2:
									// Texto
									empty($campo['texto']) ? $requeridosValido = false : $requeridosValido = true;
									$errMsg = 'El campo ' . $campo['nombre'] . ' no puede estar sin completar';
									break;
								case 3:
									// Archivo
									empty($campo['nombre_de_archivo']) ? $requeridosValido = false : $requeridosValido = true;
									$errMsg = 'Debe subir un archivo para el campo ' . $campo['nombre'];
									break;
								case 4:
									// Imagen
									empty($campo['imagen']) ? $requeridosValido = false : $requeridosValido = true;
									$errMsg = 'Debe subir una imagen para el campo ' . $campo['nombre'];
									break;
								case 5:
									// Lista
									empty($campo['seleccion_lista_predefinida']) ? $requeridosValido = false : $requeridosValido = true;
									$errMsg = 'Debe seleccionar un valor para el campo ' . $campo['nombre'];
									break;
								case 6:
									// Número
									empty($campo['numero']) ? $requeridosValido = false : $requeridosValido = true;
									$errMsg = 'El campo ' . $campo['nombre'] . ' no puede estar sin completar';
									break;
								case 7:
									// Fecha
									empty($campo['fecha']) ? $requeridosValido = false : $requeridosValido = true;
									$errMsg = 'El campo ' . $campo['nombre'] . ' no puede estar sin completar';
									break;
							}
						}
						if(!$requeridosValido) break;
					}
					if($requeridosValido) {
						// Datos correctos
						foreach($this->request->data['CamposColeccion'] as $key => $campo) {
							switch($campo['tipos_de_campo_id']) {
								case 6:
									// Número
									is_numeric(trim($campo['numero'])) ? $datosValidos = true : $datosValidos = false;
									$errMsg = 'Dato erróneo en el campo ' . $campo['nombre'];
									break;
								case 7:
									// Fecha
									$date_format = 'Y-m-d';
									$input = trim($campo['fecha']);
									$time = strtotime($input);
									(date($date_format, $time) == $input) ? $datosValidos = true : $datosValidos = false;
									$errMsg = 'Dato erróneo en el campo ' . $campo['nombre'];
									break;
							}
							if(!$requeridosValido) break;
						}
						if($datosValidos) {
							// Únicos
							foreach($this->request->data['CamposColeccion'] as $keyA => $campo) {
								if($campo['unico']) {
									$padre_id = $campo['campo_padre'];
									$this->Coleccion->CamposColeccion->contain('Hijos');
									$campoPadre = $this->Coleccion->CamposColeccion->read(null, $padre_id);
									$datoEncontrado = false;
									foreach($campoPadre['Hijos'] as $keyB => $campoHijo) {
										if($campo['id'] != $campoHijo['id']) {
											switch($campo['tipos_de_campo_id']) {
												case 2:
													// Texto
													(trim($campo['texto']) == trim($campoHijo['texto'])) ? $datoEncontrado = true : $datoEncontrado = false;
													$errMsg = 'El dato ingresado en el campo ' . $campo['nombre'] . ' ya existe';
													break;
												case 6:
													// Número
													($campo['numero'] == $campoHijo['numero']) ? $datoEncontrado = true : $datoEncontrado = false;
													$errMsg = 'El dato ingresado en el campo ' . $campo['nombre'] . ' ya existe';
													break;
												case 7:
													// Fecha
													($campo['fecha'] == $campoHijo['fecha']) ? $datoEncontrado = true : $datoEncontrado = false;
													$errMsg = 'El dato ingresado en el campo ' . $campo['nombre'] . ' ya existe';
													break;
											}
										}
										if($datoEncontrado) {
											$unicosValido = false;
											break;
										}
									}
								}
								if(!$unicosValido) break;
							}
						}
					}
					/**
					 * Fin validar campos
					 */
					if(!$requeridosValido || !$datosValidos || !$unicosValido) {
						$this->Session->setFlash($errMsg);
					} else {
						if($this->Coleccion->save($this->request->data)) {
							$this->Session->setFlash(__('The coleccion has been saved'));
							$this->redirect(array('action' => 'index', $this->request->data['Coleccion']['coleccion_id']));
						} else {
							$this->Session->setFlash(__('The coleccion could not be saved. Please, try again.'));
							debug($this->Coleccion->invalidFields());
						}
					}
				}
				$contain = array(
					'CamposColeccion' => array(
						'order' => 'CamposColeccion.posicion ASC',
						'conditions' => 'CamposColeccion.tipos_de_campo_id <> 8'
					),
					'Auditoria' => array(
						'order' => 'Auditoria.created DESC'
					)
				);
				$options = array(
					'contain' => $contain,
					'conditions' => array('Coleccion.' . $this->Coleccion->primaryKey => $id)
				);
				$this->request->data = $this->Coleccion->find('first', $options);
				$options = array(
					'contain' => $contain,
					'conditions' => array('Coleccion.' . $this->Coleccion->primaryKey => $this->request->data['Coleccion']['coleccion_id'])
				);
				$this->set('coleccionBase', $this->Coleccion->find('first', $options));
			} elseif(!$id) {
				$this->Session->setFlash('Está tratando de acceder de manera indebida');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash('No tiene permiso para ver esta sección.');
				$this->redirect(array('action' => 'index'));
			}
		}

		/**
		 * admin_add_content_type method
		 *
		 * @return void
		 */
		public function admin_add_content_type() {
			if($this->request->is('post')) {
				// Asignar datos extra que no se piden en el formulario
				$this->request->data['Coleccion']['usuario_id']           = $this->Auth->user('id');
				$this->request->data['Coleccion']['es_tipo_de_contenido'] = '1';
				$this->request->data['Grupo'][2]                          = array();
				$this->request->data['Grupo'][2]['creación']              = '1';
				$this->request->data['Grupo'][2]['acceso']                = '1';
				// Crear el tipo de contenido
				$this->Coleccion->create();
				if($this->Coleccion->save($this->request->data)) {
					$this->Session->setFlash(__('Se creó la colección. Si desea modificar la presentación hagalo ahora.'));
					$this->redirect(array('action' => 'modificar_presentacion', $this->Coleccion->getLastInsertID()));
				} else {
					$this->Session->setFlash(__('The coleccion could not be saved. Please, try again.'));
				}
			}
			$grupos        = $this->Coleccion->Grupo->find('list', array('conditions' => array('Grupo.id <>' => 2)));
			$tiposDeCampos = $this->Campo->TiposDeCampo->find('list');
			$this->set(compact('grupos', 'tiposDeCampos'));
		}

		/**
		 * admin_add_campo method
		 *
		 * @param $campo_id
		 */
		public function admin_add_campo($campo_id, $coleccion_id = null) {
			$this->layout  = 'ajax';
			$tiposDeCampos = $this->Campo->TiposDeCampo->find('list');
			$colecciones = array();
			if($coleccion_id) {
				$colecciones = $this->Coleccion->find(
					'list',
					array(
						'conditions' => array(
							'Coleccion.es_tipo_de_contenido' => 1,
							'Coleccion.id <>' => $coleccion_id
						)
					)
				);
			} else {
				$colecciones = $this->Coleccion->find('list', array('conditions' => 'Coleccion.es_tipo_de_contenido = 1'));
			}
			$this->set(compact('campo_id', 'tiposDeCampos', 'colecciones'));
		}

		/**
		 * admin_edit_content_type method
		 *
		 * @throws NotFoundException
		 *
		 * @param string $id
		 *
		 * @return void
		 */
		public function admin_edit_content_type($id = null) {
			$this->Coleccion->contain(
				'CamposColeccion.TiposDeCampo',
				'CamposColeccion.Coleccion',
				'CamposColeccion.Hijos',
				'Grupo',
				'Usuario'
			);
			if(!$this->Coleccion->exists($id)) {
				throw new NotFoundException(__('Invalid coleccion'));
			}
			if($this->request->is('post') || $this->request->is('put')) {
				// Asignar datos extra que no se piden en el formulario
				$this->request->data['Coleccion']['user_id'] = $this->Auth->user('id');
				$this->request->data['Coleccion']['es_tipo_de_contenido'] = '1';
				$this->request->data['Grupo'][2]                          = array();
				$this->request->data['Grupo'][2]['creación']              = '1';
				$this->request->data['Grupo'][2]['acceso']                = '1';
				//debug($this->request->data);
				if($this->Coleccion->save($this->request->data)) {
					$this->Session->setFlash(__('Ha modificado la colección. Revise la presentación de la misma ahora.'));
					$this->redirect(array('action' => 'modificar_presentacion', $this->Coleccion->getID()));
				} else {
					$this->Session->setFlash(__('The coleccion could not be saved. Please, try again.'));
				}
			} else {
				// Obtener la Coleccion
				$options             = array('conditions' => array('Coleccion.' . $this->Coleccion->primaryKey => $id));
				$this->request->data = $this->Coleccion->find('first', $options);
				// Procesar los campos
				$this->request->data['Campo'] = $this->request->data['CamposColeccion'];
				unset($this->request->data['CamposColeccion']);
				// Procesar los grupos (los permisos)
				foreach($this->request->data['Grupo'] as $key => $grupo) {
					if(!$grupo) {
						unset($this->request->data['Grupo'][$key]);
					}
				}
				$permisos                     = $this->request->data['Grupo'];
				$this->request->data['Grupo'] = array();
				foreach($permisos as $key => $permiso) {
					if(is_array($permiso) && $permiso['ColeccionesGrupo']['grupo_id'] != 2) {
						$this->request->data['Grupo'][$permiso['ColeccionesGrupo']['grupo_id']] = $permiso['ColeccionesGrupo'];
					}
				}
			}
			$grupos        = $this->Coleccion->Grupo->find('list', array('conditions' => array('Grupo.id <>' => 2)));
			$tiposDeCampos = $this->Campo->TiposDeCampo->find('list');
			$colecciones = $this->Coleccion->find(
				'list',
				array(
					'conditions' => array(
						'Coleccion.es_tipo_de_contenido' => 1,
						'Coleccion.id <>' => $id
					)
				)
			);
			$this->set(compact('grupos', 'tiposDeCampos', 'colecciones'));
		}

		/**
		 * admin_modificar_presentacion method
		 *
		 * @throws NotFoundException
		 *
		 * @param string $id
		 *
		 * @return void
		 */
		public function admin_modificar_presentacion($id = null) {
			if($this->verificarModificar($this->Auth->user('id'), $id)) {
				if($this->request->is('post')) {
					foreach($this->request->data['Campo'] as $key1 => $data) {
						$this->Coleccion->CamposColeccion->contain('Hijos');
						$campo = $this->Coleccion->CamposColeccion->read(null, $data['id']);
						//debug($campo);
						$campo['CamposColeccion']['posicion'] = $data['posicion'];
						$campo['CamposColeccion']['listado'] = $data['listado'];
						if(isset($data['filtro']))
							$campo['CamposColeccion']['filtro'] = $data['filtro'];
						if(isset($data['unico']))
						$campo['CamposColeccion']['unico'] = $data['unico'];
						foreach($campo['Hijos'] as $key2 => $hijo) {
							$hijo['posicion'] = $data['posicion'];
							$hijo['listado'] = $data['listado'];
							if(isset($data['filtro']))
								$hijo['filtro'] = $data['filtro'];
							if(isset($data['unico']))
							$hijo['unico'] = $data['unico'];
							$this->Coleccion->CamposColeccion->save($hijo);
						}
						$this->Coleccion->CamposColeccion->save($campo);
					}
					$this->Session->setFlash('Se modificó la presentación de la colección');
					$this->redirect(array('action' => 'index'));
				} else {
					$contain = array(
						'CamposColeccion' => array(
							'conditions' => array(
								'CamposColeccion.tipos_de_campo_id <>' => 8
							),
							'order' => 'CamposColeccion.posicion ASC'
						),
						'CamposColeccion.TiposDeCampo',
						'CamposColeccion.Coleccion'
					);

					if(!$this->Coleccion->exists($id)) {
						throw new NotFoundException(__('Invalid coleccion'));
					}

					// Obtener la Coleccion
					$options             = array(
						'contain' => $contain,
						'conditions' => array('Coleccion.' . $this->Coleccion->primaryKey => $id)
					);
					$this->request->data = $this->Coleccion->find('first', $options);

					// Procesar los campos
					$this->request->data['Campo'] = $this->request->data['CamposColeccion'];
					unset($this->request->data['CamposColeccion']);
					unset($this->request->data['Coleccion']);
				}
			} else  {
				$this->Session->setFlash('Esta colección tiene listados así que no puede ser modificada');
				$this->redirect(array('action' => 'index'));
			}
		}

	}
