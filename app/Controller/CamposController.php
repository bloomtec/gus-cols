<?php
	App::uses('AppController', 'Controller');
	/**
	 * Campos Controller
	 *
	 * @property Campo $Campo
	 */
	class CamposController extends AppController {

		public function eliminarArchivo() {
			$this->autoRender=false;
			$campo_id = $this->request['data']['campo_id'];
			$campo = $this->Campo->read(null, $campo_id);
			$coleccion = $this->Campo->Coleccion->read(null, $campo['Campo']['foreign_key']);
			$path = WWW_ROOT
				. 'files'
				. DS
				. $coleccion['Coleccion']['coleccion_id']
				. DS
				. $coleccion['Coleccion']['nombre']
				. DS
				. $campo['Campo']['nombre_de_archivo'];
			$campo['Campo']['nombre_de_archivo'] = null;
			if($this->Campo->save($campo)) {
				unlink($path);
				echo 1;
			} else {
				echo 0;
			}
			exit(0);
		}

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