<?php
	App::uses('AppController', 'Controller');
	/**
	 * Backups Controller
	 *
	 * @property Campo $Campo
	 */
	class BackupsController extends AppController {

		/**
		 * _download method
		 *
		 * @param $encoded
		 */
		public function admin_download($file) {
			$path = WWW_ROOT . 'bakap_databeis' . DS . $file;
			$this->response->file(
				$path,
				array(
					'download' => true,
					'name' => $file
				)
			);
			return $this->response;
		}

		public function admin_manage() {
			$this->set('archivos', $this->paginate());
		}

		public function admin_clearLog() {
			// Crear el backup antes de vaciar registros
			$this->admin_createBackup(false);

			// Cargar el modelo y vaciar registros
			$this->loadModel('Log');
			$logs = $this->Log->find('all');
			foreach($logs as $key => $log) {
				$this->Log->delete($log['Log']['id']);
			}

			// Redireccionar
			$this->Session->setFlash('Se ha vaciado el registro');
			$this->redirect($this->referer());
		}

		/**
		 * @param bool $redirect
		 */
		public function admin_createBackup($redirect = true) {
			// Obtener datos de la conexión a la BD
			App::import('Core', 'ConnectionManager');
			$dataSource = ConnectionManager::getDataSource('default');

			// Crear el backup
			$this -> backup_database(
				$dataSource -> config['login'],
				$dataSource -> config['password'],
				$dataSource -> config['database'],
				$dataSource -> config['host']
			);

			if($redirect) {
				$this->Session->setFlash('Se ha creado un respaldo de la base de datos');
				$this->redirect($this->referer());
			}
		}

		/**
		 * @param $filename
		 */
		public function admin_restoreFromBackup($filename) {
			// Obtener datos de la conexión a la BD
			App::import('Core', 'ConnectionManager');
			$dataSource = ConnectionManager::getDataSource('default');
			$this -> restore(
				$dataSource -> config['login'],
				$dataSource -> config['password'],
				$dataSource -> config['database'],
				$dataSource -> config['host'],
				$filename
			);
			$this -> Session -> setFlash(__("Se restauró la base de datos con el archivo :: $filename"));
			$this -> redirect($this -> referer());
		}

		/**
		 * @param $username
		 * @param $password
		 * @param $database
		 * @param $host
		 * @param $filename
		 */
		private function restore($username, $password, $database, $host, $filename) {
			$file = WWW_ROOT . 'bakap_databeis' . DS . 'clean_db.sql';
			exec("mysql -u $username -p$password --database=$database --host=$host < $file");
			$backup_file = WWW_ROOT . 'bakap_databeis' . DS . $filename;
			exec("mysql -u $username -p$password --database=$database --host=$host < $backup_file");
		}

		/**
		 * @param $username
		 * @param $password
		 * @param $database
		 * @param $host
		 */
		private function backup_database($username, $password, $database, $host) {
			$filename = $this -> filename();
			exec("mysqldump -u $username -p$password --host=$host --opt $database > $filename");
		}

		/**
		 * Generar la fecha actual formateada para comparar con la fecha de mysql
		 * GMT -5 para hora colombiana
		 */
		private function now() {
			return gmdate('Y-m-d H:i:s', time() + (3600 * -5));
		}

		/**
		 * Generar el nombre de archivo para el backup de la base de datos
		 */
		private function filename() {
			$now = $this -> now();
			$now = str_replace(' ', '_', $now);
			$now = str_replace(':', '-', $now);
			$backup_file = WWW_ROOT . 'bakap_databeis' . DS . $now . '.sql';
			return $backup_file;
		}

	}