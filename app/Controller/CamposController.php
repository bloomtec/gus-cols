<?php
App::uses('AppController', 'Controller');
/**
 * Campos Controller
 *
 * @property Campo $Campo
 */
class CamposController extends AppController {

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->Campo->recursive = 0;
		$this->set('campos', $this->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Campo->exists($id)) {
			throw new NotFoundException(__('Invalid campo'));
		}
		$options = array('conditions' => array('Campo.' . $this->Campo->primaryKey => $id));
		$this->set('campo', $this->Campo->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Campo->create();
			if ($this->Campo->save($this->request->data)) {
				$this->Session->setFlash(__('The campo has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The campo could not be saved. Please, try again.'));
			}
		}
		$tiposDeCampos = $this->Campo->TiposDeCampo->find('list');
		$colecciones = $this->Campo->Coleccion->find('list');
		$this->set(compact('tiposDeCampos', 'colecciones'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Campo->exists($id)) {
			throw new NotFoundException(__('Invalid campo'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Campo->save($this->request->data)) {
				$this->Session->setFlash(__('The campo has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The campo could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Campo.' . $this->Campo->primaryKey => $id));
			$this->request->data = $this->Campo->find('first', $options);
		}
		$tiposDeCampos = $this->Campo->TiposDeCampo->find('list');
		$colecciones = $this->Campo->Coleccion->find('list');
		$this->set(compact('tiposDeCampos', 'colecciones'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Campo->id = $id;
		if (!$this->Campo->exists()) {
			throw new NotFoundException(__('Invalid campo'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Campo->delete()) {
			$this->Session->setFlash(__('Campo deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Campo was not deleted'));
		$this->redirect(array('action' => 'index'));
	}

/**
 * admin_index method
 *
 * @return void
 */
	public function admin_index() {
		$this->Campo->recursive = 0;
		$this->set('campos', $this->paginate());
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->Campo->exists($id)) {
			throw new NotFoundException(__('Invalid campo'));
		}
		$options = array('conditions' => array('Campo.' . $this->Campo->primaryKey => $id));
		$this->set('campo', $this->Campo->find('first', $options));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->Campo->create();
			if ($this->Campo->save($this->request->data)) {
				$this->Session->setFlash(__('The campo has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The campo could not be saved. Please, try again.'));
			}
		}
		$tiposDeCampos = $this->Campo->TiposDeCampo->find('list');
		$colecciones = $this->Campo->Coleccion->find('list');
		$this->set(compact('tiposDeCampos', 'colecciones'));
	}

/**
 * admin_edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_edit($id = null) {
		if (!$this->Campo->exists($id)) {
			throw new NotFoundException(__('Invalid campo'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Campo->save($this->request->data)) {
				$this->Session->setFlash(__('The campo has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The campo could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Campo.' . $this->Campo->primaryKey => $id));
			$this->request->data = $this->Campo->find('first', $options);
		}
		$tiposDeCampos = $this->Campo->TiposDeCampo->find('list');
		$colecciones = $this->Campo->Coleccion->find('list');
		$this->set(compact('tiposDeCampos', 'colecciones'));
	}

/**
 * admin_delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_delete($id = null) {
		$this->Campo->id = $id;
		if (!$this->Campo->exists()) {
			throw new NotFoundException(__('Invalid campo'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Campo->delete()) {
			$this->Session->setFlash(__('Campo deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Campo was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
}
