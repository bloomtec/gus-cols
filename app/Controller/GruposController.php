<?php
	App::uses('AppController', 'Controller');
	/**
	 * Grupos Controller
	 *
	 * @property Grupo $Grupo
	 */
	class GruposController extends AppController {

		/**
		 * admin_index method
		 *
		 * @return void
		 */
		public function admin_index() {
			$this->Grupo->recursive = 0;
			$this->set('grupos', $this->paginate());
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
			$this->Grupo->contain('Usuario');
			if(!$this->Grupo->exists($id)) {
				throw new NotFoundException(__('No existe el grupo'));
			}
			$options = array('conditions' => array('Grupo.' . $this->Grupo->primaryKey => $id));
			$this->set('grupo', $this->Grupo->find('first', $options));
		}

		/**
		 * admin_add method
		 *
		 * @return void
		 */
		public function admin_add() {
			if($this->request->is('post')) {
				$this->Grupo->create();
				if($this->Grupo->save($this->request->data)) {
					$this->Session->setFlash(__('Se registró el grupo'));
					$this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash(__('No se registró el grupo. Por favor, intente de nuevo.'));
				}
			}
			$colecciones = $this->Grupo->Coleccion->find('list');
			$usuarios    = $this->Grupo->Usuario->find('list');
			$this->set(compact('colecciones', 'usuarios'));
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
			$this->Grupo->contain('Coleccion', 'Usuario');
			if(in_array($id, array(1, 2))) {
				$this->Session->setFlash(_('Este grupo no se puede modificar'));
				$this->redirect(array('controller' => 'grupos', 'action' => 'index'));
			}
			if(!$this->Grupo->exists($id)) {
				throw new NotFoundException(__('No existe el grupo'));
			}
			if($this->request->is('post') || $this->request->is('put')) {
				if($this->Grupo->save($this->request->data)) {
					$this->Session->setFlash(__('Se registró el grupo'));
					$this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash(__('No se registró el grupo. Por favor, intente de nuevo.'));
				}
			} else {
				$options             = array('conditions' => array('Grupo.' . $this->Grupo->primaryKey => $id));
				$this->request->data = $this->Grupo->find('first', $options);
			}
			$colecciones = $this->Grupo->Coleccion->find('list');
			$usuarios    = $this->Grupo->Usuario->find('list');
			$this->set(compact('colecciones', 'usuarios'));
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
			if(in_array($id, array(1, 2))) {
				$this->Session->setFlash(_('Este grupo no se puede eliminar'));
				$this->redirect(array('controller' => 'grupos', 'action' => 'index'));
			}
			$this->Grupo->id = $id;
			if(!$this->Grupo->exists()) {
				throw new NotFoundException(__('No existe el grupo'));
			}
			$this->request->onlyAllow('post', 'delete');
			if($this->Grupo->delete()) {
				$this->Session->setFlash(__('Se eliminó el grupo'));
				$this->redirect(array('action' => 'index'));
			}
			$this->Session->setFlash(__('No se eliminó el grupo'));
			$this->redirect(array('action' => 'index'));
		}

	}
