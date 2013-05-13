<?php
	/**
	 * Class LoggerBehavior
	 */
	class LoggerBehavior extends ModelBehavior {

		private $oldData = null;

		/**
		 * @param Model $Model
		 *
		 * @return bool|mixed
		 */
		public function beforeSave(Model $Model) {
			if($Model->id) {
				//EDICION
				$Model->recursive = -1;
				$this->oldData    = $Model->findById($Model->id);
			} else {
				// CREACION
			}

			return true;
		}

		/**
		 * @param Model $Model
		 * @param bool  $created
		 *
		 * @return bool
		 */
		public function afterSave(Model $Model, $created) {
			if(isset($_SESSION)) {
				App::import('Model', 'Log');
				$this->Log = new Log;
				$this->Log->create();
				$log = array();
				if($this->oldData) {
					//EDICION
					$log['Log']    = array(
						'usuario_id'  => $_SESSION['Auth']['User']['id'],
						'model'       => $Model->alias,
						'foreign_key' => $Model->id,
						'dato_previo' => $this->parseData($Model, $this->oldData),
						'dato_nuevo'  => $this->parseData($Model, $Model->data),
						'add'         => false,
						'edit'        => true,
						'delete'      => false
					);
					$this->oldData = null;
				} else {
					//CREACION
					$log['Log'] = array(
						'usuario_id'  => $_SESSION['Auth']['User']['id'],
						'model'       => $Model->alias,
						'foreign_key' => $Model->id,
						'dato_previo' => 'No hay dato previo',
						'dato_nuevo'  => $this->parseData($Model, $Model->data),
						'add'         => true,
						'edit'        => false,
						'delete'      => false
					);
				}

				$this->Log->save($log);
			}

			return true;
		}

		/**
		 * @param Model $Model
		 * @param bool  $cascade
		 *
		 * @return bool|mixed
		 */
		function beforeDelete(Model $Model, $cascade = true) {
			if($Model->id) {
				//EDICION
				$Model->recursive = -1;
				$this->oldData    = $Model->findById($Model->id);
			} else {

			}

			return true;
		}

		/**
		 * @param Model $Model
		 *
		 * @return bool|void
		 */
		function afterDelete(Model $Model) {
			if(isset($_SESSION)) {
				App::import('Model', 'Log');
				$this->Log = new Log;
				$this->Log->create();
				$log        = array();
				$log['Log'] = array(
					'usuario_id'  => $_SESSION['Auth']['User']['id'],
					'model'       => $Model->alias,
					'foreign_key' => $Model->id,
					'dato_previo' => $this->parseData($Model, $this->oldData),
					'dato_nuevo'  => 'No existen datos nuevos',
					'add'         => false,
					'edit'        => false,
					'delete'      => true
				);
				$this->Log->save($log);
				$this->oldData = null;
			}

			return true;
		}

		/**
		 * @param $data
		 *
		 * @return string
		 */
		private function parseData(Model $Model, $data) {
			$newData = "";
			if($data && is_array($data)) {
				foreach($data as $alias => $rows) {
					if($alias == $Model->alias) {
						$newData .= '<div class="audit">';
						foreach($rows as $row => $value) {
							$newData .= "<div class='audit-entity'>";
							$newData .= "<label>";
							$newData .= $row;
							$newData .= "</label>";
							$newData .= "<span>";
							$newData .= $value;
							$newData .= "</span><div style='clear:both'></div>";
							$newData .= "</div>";
						}
						$newData .= '</div>';
					}
				}
			}

			return trim($newData);
		}
	}