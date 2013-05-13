<?php
	App::uses('AppController', 'Controller');
	/**
	 * Campos Controller
	 *
	 * @property Campo $Campo
	 */
	class CamposController extends AppController {

		/**
		 * ordenar method
		 */
		public function ordenar() {
			$this->Campo->contain();
			$data = $_GET['data'];
			$success = true;
			foreach($_GET['data']['Campo'] as $id => $posicion) {
				$campo                      = $this->Campo->findById($id);
				$campo['Campo']['posicion'] = $posicion;
				if(!$this->Campo->save($campo)) {
					$success = false;
					debug($campo);
					debug($this->Campo->invalidFields());
				}
			}
			echo json_encode(array('success' => $success));
			exit(0);
		}

		/**
		 * eliminar method
		 */
		public function eliminar() {
			$this->Campo->contain();
			$success = $this->Campo->delete($_GET['id']);
			echo json_encode(array('success' => $success));
			exit(0);
		}

	}