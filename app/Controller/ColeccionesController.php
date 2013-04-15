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
		 * index method
		 *
		 * @return void
		 */
		public function index() {
			$this->Coleccion->recursive = 0;
			$this->set('colecciones', $this->paginate());
		}

		/**
		 * view method
		 *
		 * @throws NotFoundException
		 *
		 * @param string $id
		 *
		 * @return void
		 */
		public function view($id = null) {
			if(!$this->Coleccion->exists($id)) {
				throw new NotFoundException(__('Invalid coleccion'));
			}
			$options = array('conditions' => array('Coleccion.' . $this->Coleccion->primaryKey => $id));
			$this->set('coleccion', $this->Coleccion->find('first', $options));
		}

		/**
		 * add method
		 *
		 * @return void
		 */
		public function add() {
			if($this->request->is('post')) {
				$this->Coleccion->create();
				if($this->Coleccion->save($this->request->data)) {
					$this->Session->setFlash(__('The coleccion has been saved'));
					$this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash(__('The coleccion could not be saved. Please, try again.'));
				}
			}
			$usuarios = $this->Coleccion->Usuario->find('list');
			$grupos   = $this->Coleccion->Grupo->find('list');
			$grupos   = $this->Coleccion->Grupo->find('list');
			$this->set(compact('usuarios', 'grupos', 'grupos'));
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
		public function edit($id = null) {
			if(!$this->Coleccion->exists($id)) {
				throw new NotFoundException(__('Invalid coleccion'));
			}
			if($this->request->is('post') || $this->request->is('put')) {
				if($this->Coleccion->save($this->request->data)) {
					$this->Session->setFlash(__('The coleccion has been saved'));
					$this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash(__('The coleccion could not be saved. Please, try again.'));
				}
			} else {
				$options             = array('conditions' => array('Coleccion.' . $this->Coleccion->primaryKey => $id));
				$this->request->data = $this->Coleccion->find('first', $options);
			}
			$usuarios = $this->Coleccion->Usuario->find('list');
			$grupos   = $this->Coleccion->Grupo->find('list');
			$grupos   = $this->Coleccion->Grupo->find('list');
			$this->set(compact('usuarios', 'grupos', 'grupos'));
		}

		/**
		 * delete method
		 *
		 * @throws NotFoundException
		 *
		 * @param string $id
		 *
		 * @return void
		 */
		public function delete($id = null) {
			$this->Coleccion->id = $id;
			if(!$this->Coleccion->exists()) {
				throw new NotFoundException(__('Invalid coleccion'));
			}
			$this->request->onlyAllow('post', 'delete');
			if($this->Coleccion->delete()) {
				$this->Session->setFlash(__('Coleccion deleted'));
				$this->redirect(array('action' => 'index'));
			}
			$this->Session->setFlash(__('Coleccion was not deleted'));
			$this->redirect(array('action' => 'index'));
		}

		/**
		 * admin_index_content_type method
		 *
		 * @return void
		 */
		public function admin_index_content_type() {
			$this->Coleccion->recursive = 0;
			$this->paginate             = array(
				'conditions' => array(
					'Coleccion.es_tipo_de_contenido' => true
				)
			);
			$this->set('colecciones', $this->paginate());
		}

		/**
		 * admin_index_content_type method
		 *
		 * @return void
		 */
		public function admin_index_content() {
			$this->Coleccion->recursive = 0;
			$this->paginate             = array(
				'conditions' => array(
					'Coleccion.es_tipo_de_contenido' => false
				)
			);
			$this->set('colecciones', $this->paginate());
		}

		/**
		 * admin_view_content_type method
		 *
		 * @throws NotFoundException
		 *
		 * @param string $id
		 *
		 * @return void
		 */
		public function admin_view_content_type($id = null) {
			$this->Coleccion->contain('Usuario', 'Grupo', 'CamposColeccion.TiposDeCampo');
			if(!$this->Coleccion->exists($id)) {
				throw new NotFoundException(__('Invalid coleccion'));
			}
			$options   = array(
				'conditions' => array(
					'Coleccion.' . $this->Coleccion->primaryKey => $id,
					'Coleccion.es_tipo_de_contenido'            => true
				)
			);
			$coleccion = $this->Coleccion->find('first', $options);
			$this->set('coleccion', $coleccion);
		}

		/**
		 * admin_view_content method
		 *
		 * @throws NotFoundException
		 *
		 * @param string $id
		 *
		 * @return void
		 */
		public function admin_view_content($id = null) {
			if(!$this->Coleccion->exists($id)) {
				throw new NotFoundException(__('Invalid coleccion'));
			}
			$options = array('conditions' => array('Coleccion.' . $this->Coleccion->primaryKey => $id));
			$this->set('coleccion', $this->Coleccion->find('first', $options));
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
					$this->Session->setFlash(__('The coleccion has been saved'));
					$this->redirect(array('action' => 'index_content_type'));
				} else {
					$this->Session->setFlash(__('The coleccion could not be saved. Please, try again.'));
					//debug($this->Coleccion->invalidFields());
				}
			}
			$grupos        = $this->Coleccion->Grupo->find('list', array('conditions' => array('Grupo.id <>' => 2)));
			$tiposDeCampos = $this->Campo->TiposDeCampo->find('list');
			$this->set(compact('grupos', 'tiposDeCampos'));
		}

		/**
		 * admin_add_content method
		 *
		 * @return void
		 */
		public function admin_add_content() {
			if($this->request->is('post')) {
				$this->Coleccion->create();
				if($this->Coleccion->save($this->request->data)) {
					$this->Session->setFlash(__('The coleccion has been saved'));
					$this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash(__('The coleccion could not be saved. Please, try again.'));
				}
			}
			$usuarios   = $this->Coleccion->Usuario->find('list');
			$usuario_id = $this->Auth->user('id');
			foreach($usuarios as $id => $documento) {
				if($id != $usuario_id) unset($usuarios[$id]);
			}
			$grupos = $this->Coleccion->Grupo->find('list');
			$grupos = $this->Coleccion->Grupo->find('list');
			$this->set(compact('usuarios', 'grupos', 'grupos'));
		}

		/**
		 * admin_add_campo method
		 *
		 * @param $campo_id
		 */
		public function admin_add_campo($campo_id) {
			$this->layout  = 'ajax';
			$tiposDeCampos = $this->Campo->TiposDeCampo->find('list');
			$this->set(compact('campo_id', 'tiposDeCampos'));
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
			$this->Coleccion->contain('CamposColeccion', 'Grupo', 'Usuario');
			if(!$this->Coleccion->exists($id)) {
				throw new NotFoundException(__('Invalid coleccion'));
			}
			if($this->request->is('post') || $this->request->is('put')) {
				// Asignar datos extra que no se piden en el formulario
				$this->request->data['Coleccion']['es_tipo_de_contenido'] = '1';
				$this->request->data['Grupo'][2]                          = array();
				$this->request->data['Grupo'][2]['creación']              = '1';
				$this->request->data['Grupo'][2]['acceso']                = '1';
				//debug($this->request->data);
				if($this->Coleccion->save($this->request->data)) {
					$this->Session->setFlash(__('The coleccion has been saved'));
					$this->redirect(array('action' => 'index_content_type'));
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
			$this->set(compact('grupos', 'tiposDeCampos'));
		}

		/**
		 * admin_edit_content method
		 *
		 * @throws NotFoundException
		 *
		 * @param string $id
		 *
		 * @return void
		 */
		public function admin_edit_content($id = null) {
			if(!$this->Coleccion->exists($id)) {
				throw new NotFoundException(__('Invalid coleccion'));
			}
			if($this->request->is('post') || $this->request->is('put')) {
				if($this->Coleccion->save($this->request->data)) {
					$this->Session->setFlash(__('The coleccion has been saved'));
					$this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash(__('The coleccion could not be saved. Please, try again.'));
				}
			} else {
				$options             = array('conditions' => array('Coleccion.' . $this->Coleccion->primaryKey => $id));
				$this->request->data = $this->Coleccion->find('first', $options);
			}
			$usuarios = $this->Coleccion->Usuario->find('list');
			$grupos   = $this->Coleccion->Grupo->find('list');
			$grupos   = $this->Coleccion->Grupo->find('list');
			$this->set(compact('usuarios', 'grupos', 'grupos'));
		}

		/**
		 * admin_delete method
		 *
		 * @throws NotFoundException
		 *
		 * @param string $id
		 *
		 * @return void
		 */
		public function admin_delete_content_type($id = null) {
			$this->Coleccion->id = $id;
			if(!$this->Coleccion->exists()) {
				throw new NotFoundException(__('Invalid coleccion'));
			}
			$this->request->onlyAllow('post', 'delete');
			if($this->Coleccion->delete()) {
				$this->Session->setFlash(__('Coleccion deleted'));
				$this->redirect(array('action' => 'index_content_type'));
			}
			$this->Session->setFlash(__('Coleccion was not deleted'));
			$this->redirect(array('action' => 'index_content_type'));
		}
	}
