<?php
	App::uses('AppModel', 'Model');
	/**
	 * Backup Model
	 */
	class Backup extends AppModel {

		public $useTable = false;

		/**
		 * @param       $conditions
		 * @param       $fields
		 * @param       $order
		 * @param       $limit
		 * @param int   $page
		 * @param null  $recursive
		 * @param array $extra
		 *
		 * @return mixed
		 */
		public function paginate($conditions, $fields, $order, $limit, $page = 1, $recursive = null, $extra = array()) {
			$files = $this -> backup_list($limit, $page);
			return $files['Archivo'];
		}

		/**
		 * @param null  $conditions
		 * @param int   $recursive
		 * @param array $extra
		 *
		 * @return int
		 */
		public function paginateCount($conditions = null, $recursive = 0, $extra = array()) {
			$files = $this -> backup_list();
			return count($files['Archivo']);
		}

		/**
		 * @param null $limit
		 * @param null $page
		 *
		 * @return array
		 */
		private function backup_list($limit = null, $page = null) {
			$path = WWW_ROOT . 'bakap_databeis';
			$files = array('Archivo' => array());
			if ($handle = opendir($path)) {
				while (false !== ($entry = readdir($handle))) {
					if($entry !== 'clean_db.sql')
						$files['Archivo'][] = array('filename' => $entry);
				}
				closedir($handle);
			}
			foreach ($files['Archivo'] as $key => $file) {
				if ($file['filename'] == '.' || $file['filename'] == '..' || $file['filename'] == 'empty') {
					unset($files['Archivo'][$key]);
				} else {
					$hora = explode('.', $file['filename']);
					$hora = $hora[0];
					$hora = str_replace('_', ' ', $hora);
					$hora = explode(' ', $hora);
					$hora[1] = str_replace('-', ':', $hora[1]);
					$hora = $hora[0] . ' ' . $hora[1];
					$files['Archivo'][$key]['created'] = $hora;
				}
			}
			sort($files['Archivo']);
			$files['Archivo'] = array_reverse($files['Archivo']);
			if(!$limit && !$page) {
				return $files;
			} else {
				$pages = array();
				$total_files = count($files['Archivo']);
				if($total_files === 0) return $files;
				$total_pages = ceil($total_files / $limit);
				$current_file = 0;
				for ($i=1; $i <= $total_pages ; $i++) {
					$pages[$i]['Archivo'] = array();
					for ($j=1; $j <= $limit; $j++) {
						$pages[$i]['Archivo'][$current_file] = $files['Archivo'][$current_file];
						if($current_file == ($total_files - 1)) {
							break;
						} else {
							$current_file++;
						}
					}
				}
				return $pages[$page];
			}
		}

	}
