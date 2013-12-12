<?php
	App::uses('AppController', 'Controller');
	/**
	 * Colecciones Controller
	 *
	 * @property Coleccion $Coleccion
	 */
	class ColeccionesController extends AppController {

		/**
		 * Parámetro interno para definir el limite de ítemes listados
		 * en el indice público.
		 * @var int
		 */
		private $publicIndexLimit = 5;

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
		 * @param      $coleccion_id
		 * @param bool $grupo_auditor
		 */
		public function admin_sendMail($coleccion_id, $grupo_auditor = false) {
			$this->_sendMail($coleccion_id, $grupo_auditor);
		}

		/**
		 * @param      $coleccion_id
		 * @param bool $grupo_auditor
		 */
		public function sendMail($coleccion_id, $grupo_auditor = false) {
			$this->_sendMail($coleccion_id, $grupo_auditor);
		}

		/**
		 * @param      $coleccion_id
		 * @param bool $grupo_auditor
		 */
		private function _sendMail($coleccion_id, $grupo_auditor = false) {
			$this->autoRender=false;
			$this->Coleccion->contain(
				'Usuario',
				'Grupo'
			);
			$coleccion = $this->Coleccion->read(null, $coleccion_id);

			// Enviar al grupo auditor o al usuario?
			if(!$grupo_auditor) {
				$email = $coleccion['Usuario']['correo'];
				$nombre = $coleccion['Usuario']['nombres'] . ' ' . $coleccion['Usuario']['apellidos'];

				if(!empty($email)) {
					// Enviar el correo al usuario

					// subject
					$subject = 'Notificación aplicación personería';

					// message
					$message =
						'<html>
							<head>
								  <title>Notificación Perrsonería Cali</title>
							</head>
							<body>
								  <p>Un listado ha sido auditado.</p>
								  <p>Ingresa <a href="' . $_SERVER['HTTP_HOST'] . '">aquí</a> para revisar el listado.</p>
								</body>
							</html>';

					// To send HTML mail, the Content-type header must be set
					$headers  = 'MIME-Version: 1.0' . "\r\n";
					$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

					// Additional headers
					$headers .= "To: $nombre <$email>" . "\r\n";
					$headers .= 'From: Aplicación Personería <no-reply@personeriacali.gov.co>' . "\r\n";

					// Mail it
					mail($email, $subject, $message, $headers);
				}
			} else {
				$grupo_id = $coleccion['Grupo']['id'];
				$this->Coleccion->Grupo->contain(
					'Usuario'
				);
				$grupo = $this->Coleccion->Grupo->read(null, $grupo_id);
				if(isset($grupo['Usuario']) && !empty($grupo['Usuario'])) {
					foreach($grupo['Usuario'] as $key => $usuario) {
						$email = $usuario['correo'];
						$nombre = $usuario['nombres'] . ' ' . $usuario['apellidos'];
						if(!empty($email)) {
							// Enviar el correo al usuario

							// subject
							$subject = 'Notificación aplicación personería';

							// message
							$message =
								'<html>
									<head>
										  <title>Notificación Perrsonería Cali</title>
									</head>
									<body>
										  <p>Hay un nuevo listado por auditar.</p>
										  <p>Ingresa <a href="' . $_SERVER['HTTP_HOST'] . '">aquí</a> para revisar el listado.</p>
								</body>
							</html>';

							// To send HTML mail, the Content-type header must be set
							$headers  = 'MIME-Version: 1.0' . "\r\n";
							$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

							// Additional headers
							$headers .= "To: $nombre <$email>" . "\r\n";
							$headers .= 'From: Aplicación Personería <no-reply@personeriacali.gov.co>' . "\r\n";

							// Mail it
							mail($email, $subject, $message, $headers);
						}
					}
				}
			}
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
				//'Coleccion.grupo_id' => $grupos_usuario,
				'Coleccion.es_tipo_de_contenido' => 0
			);
			if(!in_array(2, $grupos_usuario)) {
				$conditions['Coleccion.grupo_id'] = $grupos_usuario;
			}
			if($ct_index) {
				$conditions['Coleccion.coleccion_id'] = $id;
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

		/**
		 * @param null $nombre
		 * @return bool
		 */
		private function verificarNombreArchivo($nombre = null) {

			$chars = array(
				'á',
				'é',
				'í',
				'ó',
				'ú',
				'ä',
				'ë',
				'ï',
				'ö',
				'ü',
				'à',
				'è',
				'ì',
				'ò',
				'ù',
				'â',
				'ê',
				'î',
				'ô',
				'û',
			);

			foreach($chars as $char) {
				if(stristr($nombre, $char) !== false) {
					return 0;
				}
			}

			return 1;
		}

		/**
		 * @param $coleccion_id
		 *
		 * @return int
		 */
		public function verificarContenidoARevisar($coleccion_id) {
			$user_id = $this->Auth->user('id');
			$colecciones = $this->Coleccion->find(
				'all',
				array(
					'conditions' => array(
						'Coleccion.es_auditable' => 1,
						//'Coleccion.auditada' => 1,
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
			$conditions = array(
				'Coleccion.es_auditable' => 1,
				'Coleccion.auditada' => 0,
				'Coleccion.es_tipo_de_contenido' => 0
			);
			if(!in_array(2, $grupos_usuario)) {
				$conditions['Coleccion.grupo_id'] = $grupos_usuario;
			}
			$this->Coleccion->contain('Grupo');
			$auditables = $this->Coleccion->find(
				'list',
				array(
					'conditions' => $conditions,
					'fields' => array(
						'Coleccion.id'
					)
				)
			);
			return $auditables;
		}

		/**;
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
		 * @param $coleccion
		 * @param $directorio
		 * @param $archivo
		 */
		public function uploaded($coleccion, $directorio, $archivo) {

			$chars = array(
				'á' => 'a',
				'é' => 'e',
				'í' => 'i',
				'ó' => 'o',
				'ú' => 'u',
				'ä' => 'a',
				'ë' => 'e',
				'ï' => 'i',
				'ö' => 'o',
				'ü' => 'u',
				'à' => 'a',
				'è' => 'e',
				'ì' => 'i',
				'ò' => 'o',
				'ù' => 'u',
				'â' => 'a',
				'ê' => 'e',
				'î' => 'i',
				'ô' => 'o',
				'û' => 'u',
				' ' => '_'
			);

			$nombreOriginal = $archivo;
			$nombreNuevo = $archivo;

			foreach($chars as $search => $replace) {
				$nombreNuevo = str_ireplace($search, $replace, $nombreNuevo);
			}

			$rutaOriginal = WWW_ROOT . 'files' . DS . $coleccion . DS . $directorio . DS . $nombreOriginal;
			$rutaNueva = WWW_ROOT . 'files' . DS . $coleccion . DS . $directorio . DS . $nombreNuevo;

			if(rename($rutaOriginal, $rutaNueva)) {
				echo json_encode(
					array(
						'success' => 1,
						'coleccion' => $coleccion,
						'directorio' => $directorio,
						'nombreOriginal' => $nombreOriginal,
						'nombreNuevo' => $nombreNuevo,
						'ruta' => $rutaNueva
					)
				);
			} else {
				echo json_encode(
					array(
						'success' => 0,
						'coleccion' => $coleccion,
						'directorio' => $directorio,
						'nombreOriginal' => $nombreOriginal,
						'nombreNuevo' => $nombreNuevo,
						'ruta' => $rutaNueva
					)
				);
			}
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
			$path = WWW_ROOT . 'files' . DS . $json[3] . DS . $json[4] . DS . $json[0];
			$this->response->file(
				$path,
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
				throw new NotFoundException(__('Está tratando de eliminar un dato inexistente'));
			}
			if($this->verificarEliminar($usuario_id, $id)) {
				if($this->Coleccion->delete()) {
					$this->Session->setFlash(__('Información eliminada'));
					$this->redirect(array('action' => 'index'));
				}
				$this->Session->setFlash(__('No se pudo eliminar la información'));
			} else {
				$this->Session->setFlash(__('No tiene permiso para realizar esta acción o está tratando de eliminar una colección con listados'));
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
			$links = array();
			foreach($colecciones as $key => $coleccion) {
				$links[$coleccion['Coleccion']['nombre']] = FULL_BASE_URL . '/colecciones/indice/' . $coleccion['Coleccion']['id'];
			}
			echo json_encode($links);
			exit(0);
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

			// Sección ordenamiento
			$order = array(
				'Coleccion.created' => 'DESC'
			);
			$base = $this->Coleccion->read(null, $coleccion_id);
			if($base['Coleccion']['order_field']) {
				unset($order['Coleccion.created']);
				$order['Coleccion.order_field_data'] = $base['Coleccion']['order_asc'] ? 'ASC' : 'DESC';
			}

			$this->paginate = array(
				'conditions' => $conditions,
				'order' => $order,
				'limit' => $this->publicIndexLimit
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
					//$conditions['Coleccion.auditada'] = 1;
					$conditions['Coleccion.publicada'] = 0;
					$conditions['Coleccion.usuario_id'] = $this->Auth->user('id');
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

			// Sección ordenamiento
			$order = array(
				'Coleccion.created' => 'DESC'
			);
			$base = $this->Coleccion->read(null, $coleccion_id);
			if($base['Coleccion']['order_field']) {
				unset($order['Coleccion.created']);
				$order['Coleccion.order_field_data'] = $base['Coleccion']['order_asc'] ? 'ASC' : 'DESC';
			}

			$this->paginate = array(
				'conditions' => $conditions,
				'order' => $order
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
				'Auditoria.Usuario'
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
								'colección_aprobada' => $this->request->data['Coleccion']['publicada'] ? 1 : 0,
								'observación' => $this->request->data['Coleccion']['observación']
							)
						);
						if($this->Coleccion->Auditoria->save($auditoria)) {
							$this->_sendMail($id, false);
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
			$uno = 1;
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
								case 3:
									// Archivo
									if(!empty($campo['nombre_de_archivo'])) {
										if(!$this->verificarNombreArchivo($campo['nombre_de_archivo'])) {
											$datosValidos = false;
											$errMsg = 'Verifique que el nombre del archivo no contenga acentos para el campo ' . $campo['nombre'];
										} else {
											$datosValidos = true;
										}
									}
									break;
								case 4:
									// Imagen
									if(!empty($campo['imagen'])) {
										if(!$this->verificarNombreArchivo($campo['imagen'])) {
											$datosValidos = false;
											$errMsg = 'Verifique que el nombre de la imagen no contenga acentos para el campo ' . $campo['nombre'];
										} else {
											$datosValidos = true;
										}
									}
									break;
								case 6:
									// Número
									is_numeric(trim($campo['numero'])) ? $datosValidos = true : $datosValidos = false;
									$errMsg = 'El dato ingresado para el campo ' . $campo['nombre'] . ' no es numérico.';
									break;
								case 7:
									// Fecha
									$date_format = 'Y-m-d';
									$input = trim($campo['fecha']);
									$time = strtotime($input);
									(date($date_format, $time) == $input) ? $datosValidos = true : $datosValidos = false;
									$errMsg = 'Ha ingresado en un formato no aceptado la fecha para el campo ' . $campo['nombre'];
									break;
							}
							if(!$datosValidos) break;
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
							$this->cuadrarOrdenamiento($ct_id);
							$this->Session->setFlash(__('Se guardó la información'));
							$this->redirect(array('action' => 'index'));
						} else {
							$this->Session->setFlash(__('No se pudo guardar la información. Por favor, intente de nuevo.'));
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
		public function add_campo_form_contenido($campo_id, $c_name, $uid, $index, $ct_id) {
			$this->layout = 'ajax';
			$this->Campo->contain();
			$campo = $this->Campo->read(null, $campo_id);
			$path = WWW_ROOT . 'files';
			$exts = '';
			$seleccionListaPredefinidas = null;
			if($campo['Campo']['tipos_de_campo_id'] == 3) {
				$TMPexts = explode(',', $campo['Campo']['extensiones']);
				$j = count($TMPexts);
				for($i = 0; $i < $j; $i += 1) {
					if($i < $j - 1) {
						//$exts .= '*.' . trim($TMPexts[$i] . '; ');
						$exts .= "'" . trim($TMPexts[$i]) . "', ";
					} else {
						$exts .= "'" . trim($TMPexts[$i]) . "'";
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
			$this->set(compact('path', 'index', 'c_name', 'uid', 'campo', 'campo_id', 'exts', 'seleccionListaPredefinidas', 'ct_id'));
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
								case 3:
									// Archivo
									if(!empty($campo['nombre_de_archivo'])) {
										if(!$this->verificarNombreArchivo($campo['nombre_de_archivo'])) {
											$datosValidos = false;
											$errMsg = 'Verifique que el nombre del archivo no contenga acentos para el campo ' . $campo['nombre'];
										} else {
											$datosValidos = true;
										}
									}
									break;
								case 4:
									// Imagen
									if(!empty($campo['imagen'])) {
										if(!$this->verificarNombreArchivo($campo['imagen'])) {
											$datosValidos = false;
											$errMsg = 'Verifique que el nombre de la imagen no contenga acentos para el campo ' . $campo['nombre'];
										} else {
											$datosValidos = true;
										}
									}
									break;
								case 6:
									// Número
									is_numeric(trim($campo['numero'])) ? $datosValidos = true : $datosValidos = false;
									$errMsg = 'El dato ingresado para el campo ' . $campo['nombre'] . ' no es numérico.';
									break;
								case 7:
									// Fecha
									$date_format = 'Y-m-d';
									$input = trim($campo['fecha']);
									$time = strtotime($input);
									(date($date_format, $time) == $input) ? $datosValidos = true : $datosValidos = false;
									$errMsg = 'Ha ingresado en un formato no aceptado la fecha para el campo ' . $campo['nombre'];
									break;
							}
							if(!$datosValidos) break;
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
							$contenido = $this->Coleccion->read(null, $this->Coleccion->id);
							$this->cuadrarOrdenamiento($contenido['Coleccion']['coleccion_id']);
							$this->Session->setFlash(__('Se guardó la información'));
							$this->_sendMail($id, true);
							$this->redirect(array('action' => 'index', $this->request->data['Coleccion']['coleccion_id']));
						} else {
							$this->Session->setFlash(__('No se pudo guardar la información. Por favor, intente de nuevo.'));
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
					),
					'Auditoria.Usuario'
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

				// Validar campos
				$valid = true;
				$errMsg = '';
				if(isset($this->request->data['Campo']) && !empty($this->request->data['Campo'])) {
					foreach($this->request->data['Campo'] as $key=>$campo) {
						switch($campo['tipos_de_campo_id']) {
							case 3:
								if(empty($campo['extensiones'])) {
									$valid = false;
									$errMsg = 'Debe ingresar al menos una extensión para el campo ' . $campo['nombre'];
								}
								break;
						}
					}
				}

				if($valid) {
					// Crear el tipo de contenido
					$this->Coleccion->create();
					if($this->Coleccion->save($this->request->data)) {
						$this->Session->setFlash(__('Se creó la colección. Si desea modificar la presentación hagalo ahora.'));
						$this->redirect(array('action' => 'modificar_presentacion', $this->Coleccion->getLastInsertID()));
					} else {
						$this->Session->setFlash(__('No se pudo crear la colección. Por favor, intente de nuevo.'));
					}
				} else {
					$this->Session->setFlash($errMsg);
					//$this->request->data['Campo'] = array();
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
		public function admin_add_campo($campo_id, $coleccion_id = null, $tipo_de_campo = null) {
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
			$this->set(compact('campo_id', 'tiposDeCampos', 'colecciones', 'tipo_de_campo'));
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
			$uno = 1;
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
				/**
				 * Verificar cambios
				 */
				$guardarCambios = true;
				$datoEncontrado = true;
				$errMsg = '';
				set_time_limit(Configure::read('time_limit'));
				foreach($this->request->data['Campo'] as $keyA => $campo) {
					$this->Coleccion->CamposColeccion->contain('Hijos');
					if(isset($campo['id'])) {
						$campoColeccion = $this->Coleccion->CamposColeccion->read(null, $campo['id']);
						foreach($campoColeccion['Hijos'] as $keyB => $campoHijo) {
							switch($campoColeccion['CamposColeccion']['tipos_de_campo_id']) {
								case 5:
									// Lista
									$textParts = explode("\n", $campo['lista_predefinida']);
									foreach($textParts as $keyC => $text) $textParts[$keyC] = trim($text);
									if(!in_array($campoHijo['seleccion_lista_predefinida'], $textParts)) {
										$datoEncontrado = false;
									}
									break;
							}
							if(!$datoEncontrado) break;
						}
					}
					if(!$datoEncontrado) break;
				}
				if(!$datoEncontrado) {
					$guardarCambios = false;
					$errMsg = 'Existen listados con opciones que no ha dejado en la lista de selección';
				}
				/**
				 * Fin verificar cambios
				 */
				if($guardarCambios) {
					// TODO REVISAR AQUI
					if($this->Coleccion->save($this->request->data)) {
						/**
						 * Cambiar las listas en los hijos
						 */
						set_time_limit(Configure::read('time_limit'));
						$hijos = array();
						//foreach($this->request->data['Campo'] as $keyA => $campo) {
						foreach($this->request->data['Campo'] as $data) {
							if(isset($data['id'])) {
								$this->Coleccion->CamposColeccion->contain('Hijos');
								$campo = $this->Coleccion->CamposColeccion->read(null, $data['id']);
								//foreach($campoColeccion['Hijos'] as $keyB => $campoHijo) {
								foreach($campo['Hijos'] as $hijo) {
									switch($campo['CamposColeccion']['tipos_de_campo_id']) {
										case 3:
											// Archivo
											$hijo['link_descarga'] = $data['link_descarga'];
											//$this->Coleccion->CamposColeccion->save($campoHijo);
											$hijos[]['Campo'] = $hijo;
											break;
										case 5:
											// Lista
											$hijo['lista_predefinida'] = $data['lista_predefinida'];
											//$this->Coleccion->CamposColeccion->save($campoHijo);
											$hijos[]['Campo'] = $hijo;
											break;
									}
								}
							}
						}
						$this->loadModel('Campo');
						set_time_limit(Configure::read('time_limit'));
						if(
						!$this->Campo->saveMany(
							$hijos,
							array(
								'validate' => false,
								'atomic' => true,
								'deep' => false,
							)
						)
						) {
							$this->Campo->log('Colecciones (~1988)');
						}
						/**
						 * Fin cambio lista en los hijos
						 */
						/**
						 * Cambiar opciones en los contenidos de este tipo
						 */
						$this->Coleccion->contain('Contenido');
						$contentType = $this->Coleccion->read(null, $id);
						//foreach($contentType['Contenido'] as $key => $contenido) {
						set_time_limit(Configure::read('time_limit'));
						foreach($contentType['Contenido'] as $contenido) {
							$this->Coleccion->id=$contenido['id'];
							$this->Coleccion->saveField('es_auditable', $contentType['Coleccion']['es_auditable']);
							if($contentType['Coleccion']['es_auditable']) {
								$this->Coleccion->saveField('publicada', 0);
							} else {
								$this->Coleccion->saveField('publicada', 1);
							}
							$this->Coleccion->saveField('grupo_id', $contentType['Coleccion']['grupo_id']);
							$this->Coleccion->saveField('acceso_anonimo', $contentType['Coleccion']['acceso_anonimo']);
						}
						/**
						 * Fin cambio de opciones en los contenidos de este tipo
						 */
						$this->Session->setFlash(__('Ha modificado la colección. Revise la presentación de la misma ahora.'));
						$this->redirect(array('action' => 'modificar_presentacion', $id));
					} else {
						$this->Session->setFlash(__('No se pudo crear la colección. Por favor, intente de nuevo.'));
					}
				} else {
					$this->Session->setFlash(__($errMsg));
				}
			}

			$this->Coleccion->contain(
				'CamposColeccion.TiposDeCampo',
				'CamposColeccion.Coleccion',
				'CamposColeccion.Hijos',
				'Grupo',
				'Usuario'
			);

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
			$uno = 1;
			$contain = array(
				'CamposColeccion' => array(
					'conditions' => array(
						'CamposColeccion.tipos_de_campo_id <>' => 8
					),
					'order' => 'CamposColeccion.posicion ASC'
				),
				'CamposColeccion.TiposDeCampo',
				'CamposColeccion.Coleccion',
				'CampoOrdenamiento',
				'Contenido'
			);
			$options             = array(
				'contain' => $contain,
				'conditions' => array('Coleccion.' . $this->Coleccion->primaryKey => $id)
			);
			if($this->verificarModificar($this->Auth->user('id'), $id)) {
				if($this->request->is('post') || $this->request->is('put')) {
					if(isset($this->request->data['Campo'])) {
						set_time_limit(Configure::read('time_limit'));
						$campos = array();
						// TODO REVISION PREVIA
						$hijos = array();
						//foreach($this->request->data['Campo'] as $key1 => $data) {
						foreach($this->request->data['Campo'] as $data) {
							$this->Coleccion->CamposColeccion->contain('Hijos');
							$campo = $this->Coleccion->CamposColeccion->read(null, $data['id']);
							$campo['CamposColeccion']['posicion'] = $data['posicion'];
							$campo['CamposColeccion']['listado'] = $data['listado'];
							if(isset($data['filtro']))
								$campo['CamposColeccion']['filtro'] = $data['filtro'];
							if(isset($data['unico']))
								$campo['CamposColeccion']['unico'] = $data['unico'];
							//foreach($campo['Hijos'] as $key2 => $hijo) {
							foreach($campo['Hijos'] as $hijo) {
								$hijo['posicion'] = $data['posicion'];
								$hijo['listado'] = $data['listado'];
								if(isset($data['filtro']))
									$hijo['filtro'] = $data['filtro'];
								if(isset($data['unico']))
									$hijo['unico'] = $data['unico'];
								//$this->Coleccion->CamposColeccion->save($hijo);
								$hijos[]['Campo'] = $hijo;
							}
							//$this->Coleccion->CamposColeccion->save($campo);
							$campos[]['Campo'] = $campo['CamposColeccion'];
						}
						$this->loadModel('Campo');
						set_time_limit(Configure::read('time_limit'));
						if(
							!$this->Campo->saveMany(
								$campos,
								array(
									'validate' => false,
									'atomic' => true,
									'deep' => false,
								)
							)
						) {
							$this->Campo->log('Colecciones (~1988)');
						}
						set_time_limit(Configure::read('time_limit'));
						if(
							!empty($hijos)
							&& !$this->Campo->saveMany(
								$hijos,
								array(
									'validate' => false,
									'atomic' => true,
									'deep' => false,
								)
							)
						) {
							$this->Campo->log('Colecciones (~2002)');
						}
					}
					if(isset($this->request->data['Coleccion'])) {
						if($this->Coleccion->save($this->request->data['Coleccion'])) {
							$this->cuadrarOrdenamiento($id);
						}
					}
					$this->Session->setFlash('Se modificó la presentación de la colección');
					$this->redirect(array('action' => 'index'));
				}
				if(!$this->Coleccion->exists($id)) {
					throw new NotFoundException(__('Invalid coleccion'));
				}

				// Obtener la Coleccion
				$this->request->data = $this->Coleccion->find('first', $options);

				// Procesar los campos
				$this->request->data['Campo'] = $this->request->data['CamposColeccion'];
				unset($this->request->data['CamposColeccion']);
				//unset($this->request->data['Coleccion']);
			} else  {
				$this->Session->setFlash('Esta colección tiene listados así que no puede ser modificada');
				$this->redirect(array('action' => 'index'));
			}
		}

		/**
		 * @param $ct_id
		 */
		private function cuadrarOrdenamiento($ct_id) {
			$contain = array(
				'CamposColeccion' => array(
					'conditions' => array(
						'CamposColeccion.tipos_de_campo_id <>' => 8
					),
					'order' => 'CamposColeccion.posicion ASC'
				),
				'CamposColeccion.TiposDeCampo',
				'CamposColeccion.Coleccion',
				'CampoOrdenamiento',
				'Contenido'
			);
			$options = array(
				'contain' => $contain,
				'conditions' => array('Coleccion.' . $this->Coleccion->primaryKey => $ct_id)
			);
			$base = $this->Coleccion->find('first', $options);
			foreach($base['Contenido'] as $key1 => $contenido) {
				$opciones             = array(
					'contain' => $contain,
					'conditions' => array('Coleccion.' . $this->Coleccion->primaryKey => $contenido['id'])
				);
				$contenido = $this->Coleccion->find('first', $opciones);
				foreach($contenido['CamposColeccion'] as $key2 => $campo) {
					if($campo['campo_padre'] === $base['Coleccion']['order_field']) {
						$contenido['Coleccion']['order_field'] = $campo['id'];
						switch($campo['tipos_de_campo_id']) {
							case 2:
								// texto
								$contenido['Coleccion']['order_field_data'] = $campo['texto'];
								break;
							case 5:
								// Lista
								$contenido['Coleccion']['order_field_data'] = $campo['seleccion_lista_predefinida'];
								break;
							case 6:
								// Número
								$contenido['Coleccion']['order_field_data'] = $campo['numero'];
								break;
							case 7:
								// Fecha
								$contenido['Coleccion']['order_field_data'] = $campo['fecha']; //strtotime($campo['fecha']);
								break;
						}
					}
				}
				$contenido['Coleccion']['order_asc'] = $base['Coleccion']['order_asc'] ? 1 : 0;
				$this->Coleccion->id = $contenido['Coleccion']['id'];
				$this->Coleccion->saveField('order_field', $contenido['Coleccion']['order_field']);
				$this->Coleccion->saveField('order_field_data', $contenido['Coleccion']['order_field_data']);
				$this->Coleccion->saveField('order_asc', $contenido['Coleccion']['order_asc']);
			}
		}

	}
