<?php
	App::uses('AppController', 'Controller');
	/**
	 * Usuarios Controller
	 *
	 * @property Usuario $Usuario
	 */
	class UsuariosController extends AppController {

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
			if(!$this->Usuario->exists($id)) {
				throw new NotFoundException(__('El usuario no existe'));
			}
			$options = array('conditions' => array('Usuario.' . $this->Usuario->primaryKey => $id));
			$this->set('usuario', $this->Usuario->find('first', $options));
		}

		/**
		 * add method
		 *
		 * @return void
		 */
		public function add() {
			if($this->request->is('post')) {
				$this->Usuario->create();
				if($this->Usuario->save($this->request->data)) {
					$this->Session->setFlash(__('Se registró el usuario'));
					$this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash(__('No se pudo registrar el usuario. Por favor, intente de nuevo.'));
				}
			}
			$grupos = $this->Usuario->Grupo->find('list');
			$this->set(compact('grupos'));
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
			if(!$this->Usuario->exists($id)) {
				throw new NotFoundException(__('El usuario no existe'));
			}
			if($this->request->is('post') || $this->request->is('put')) {
				if($this->Usuario->save($this->request->data)) {
					$this->Session->setFlash(__('Se registró el usuario'));
					$this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash(__('No se pudo registrar el usuario. Por favor, intente de nuevo.'));
				}
			} else {
				$options             = array('conditions' => array('Usuario.' . $this->Usuario->primaryKey => $id));
				$this->request->data = $this->Usuario->find('first', $options);
			}
			$grupos = $this->Usuario->Grupo->find('list');
			$this->set(compact('grupos'));
		}

		/**
		 * admin_index method
		 *
		 * @return void
		 */
		public function admin_index() {
			$this->Usuario->recursive = 0;
			$this->set('usuarios', $this->paginate());
		}

		/**
		 * admin_view method
		 *
		 * @throws NotFoundException
		 *
		 * @param string $id
		 *
		 * @return void
		 */
		public function admin_view($id = null) {
			$this->Usuario->contain('Grupo', 'Auditoria', 'Coleccion');
			if(!$this->Usuario->exists($id)) {
				throw new NotFoundException(__('El usuario no existe'));
			}
			$options = array('conditions' => array('Usuario.' . $this->Usuario->primaryKey => $id));
			$this->set('usuario', $this->Usuario->find('first', $options));
		}

		/**
		 * admin_add method
		 *
		 * @return void
		 */
		public function admin_add() {
			if($this->request->is('post')) {
				// Agregar el grupo 'Registrados'
				$this->request->data['Grupo']['Grupo'][] = '1';
				$this->Usuario->create();
				if($this->Usuario->save($this->request->data)) {
					$this->Session->setFlash(__('Se registró el usuario'));
					$this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash(__('No se pudo registrar el usuario. Por favor, intente de nuevo.'));
				}
			}
			// Mostrar los grupos menos el grupo 'Registrados'
			$grupos = $this->Usuario->Grupo->find('list', array('conditions' => array('id <>' => 1)));
			$this->set(compact('grupos'));
		}

		/**
		 * admin_edit method
		 *
		 * @throws NotFoundException
		 *
		 * @param string $id
		 *
		 * @return void
		 */
		public function admin_edit($id = null) {
			$this->Usuario->contain('Grupo', 'Auditoria', 'Coleccion');
			if(!$this->Usuario->exists($id)) {
				throw new NotFoundException(__('El usuario no existe'));
			}
			if($this->request->is('post') || $this->request->is('put')) {
				// Agregar el grupo 'Registrados'
				$this->request->data['Grupo']['Grupo'][] = '1';
				if($this->Usuario->save($this->request->data)) {
					$this->Session->setFlash(__('Se registró el usuario'));
					$this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash(__('No se pudo registrar el usuario. Por favor, intente de nuevo.'));
				}
			} else {
				$options             = array('conditions' => array('Usuario.' . $this->Usuario->primaryKey => $id));
				$this->request->data = $this->Usuario->find('first', $options);
			}
			// Mostrar los grupos menos el grupo 'Registrados'
			$grupos = $this->Usuario->Grupo->find('list', array('conditions' => array('id <>' => 1)));
			$this->set(compact('grupos'));
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
		public function admin_delete($id = null) {
			$this->Usuario->id = $id;
			if(!$this->Usuario->exists()) {
				throw new NotFoundException(__('El usuario no existe'));
			}
			$this->request->onlyAllow('post', 'delete');
			if($this->Usuario->delete()) {
				$this->Session->setFlash(__('Se eliminó el usuario'));
				$this->redirect(array('action' => 'index'));
			}
			$this->Session->setFlash(__('No se eliminó el usuario'));
			$this->redirect(array('action' => 'index'));
		}

		/**
		 * login method
		 *
		 * @return void
		 */
		public function login() {
			if ($this -> request -> is('post')) {
				if ($this -> Auth -> login()) {
					$this -> redirect($this -> Auth -> redirect());
				} else {
					$this -> Session -> setFlash(__('Usuario y/o contraseña no válido.'));
				}
			}
		}

		/**
		 * logout method
		 *
		 * @return void
		 */
		public function logout() {
			$this -> redirect($this -> Auth -> logout());
		}

		/**
		 * admin_login method
		 *
		 * @return void
		 */
		public function admin_login() {
			if ($this -> request -> is('post')) {
				if ($this -> Auth -> login()) {
					$this->redirect(array('controller' => 'usuarios', 'action' => 'index', 'admin' => true));
				} else {
					$this -> Session -> setFlash(__('Usuario y/o contraseña no válido.'));
				}
			}
		}

		/**
		 * admin_logout method
		 *
		 * @return void
		 */
		public function admin_logout() {
			$this -> redirect($this -> Auth -> logout());
		}

	}