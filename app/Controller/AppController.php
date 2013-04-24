<?php
	/**
	 * Application level Controller
	 *
	 * This file is application-wide controller file. You can put all
	 * application-wide controller-related methods here.
	 *
	 * PHP 5
	 *
	 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
	 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
	 *
	 * Licensed under The MIT License
	 * For full copyright and license information, please see the LICENSE.txt
	 * Redistributions of files must retain the above copyright notice.
	 *
	 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
	 * @link          http://cakephp.org CakePHP(tm) Project
	 * @package       app.Controller
	 * @since         CakePHP(tm) v 0.2.9
	 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
	 */
	App::uses('Controller', 'Controller');

	/**
	 * Application Controller
	 *
	 * Add your application-wide methods in the class below, your controllers
	 * will inherit them.
	 *
	 * @package        app.Controller
	 * @link           http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
	 */
	class AppController extends Controller {

		public $components = array('Auth', 'Session');

		protected $exclusiveActions = array();

		/**
		 * beforeFilter method
		 *
		 * @return void
		 */
		public function beforeFilter() {
			// Contener las relaciones
			$Class = $this -> modelClass;
			$this -> $Class -> contain();
			// Manejo de la parte de autentificación
			$this -> authConfig();
			// Asignar el layout acorde el contexto
			$this -> layoutConfig();
		}

		/**
		 * authConfig method
		 *
		 * @return void
		 */
		protected function authConfig() {
			$this -> Auth -> deny();
			$this -> Auth -> authorize = 'Controller';
			$this -> Auth -> authenticate = array(
				'Form' => array(
					'scope' => array('activo' => 1),
					'userModel' => 'Usuario',
					'fields' => array(
						'username' => 'documento',
						'password' => 'contraseña'
					)
				)
			);
			$this -> Auth -> authError = 'No tiene permiso para ver esta sección';
			if (!isset($this -> params['prefix'])) {
				$this -> Auth -> allow();
				$this -> Auth -> loginAction = array('controller' => 'usuarios', 'action' => 'login', 'admin' => false);
				$this -> Auth -> loginRedirect = array($this->referer());
				$this -> Auth -> logoutRedirect = array('controller' => 'colecciones', 'action' => 'index', 'admin' => false);
			} elseif ($this -> params['prefix'] == 'admin') {
				$this -> Auth -> loginAction = array('controller' => 'usuarios', 'action' => 'login', 'admin' => true);
				$this -> Auth -> logoutRedirect = array('controller' => 'colecciones', 'action' => 'index', 'admin' => false);
			}
		}

		/**
		 * layoutConfig method
		 *
		 * @return void
		 */
		protected function layoutConfig() {
			if(in_array($this->action, array('login', 'admin_login'))) {
				$this -> layout = 'login';
			} elseif (!isset($this -> params['prefix'])) {
				$this -> layout = 'default';
			} elseif ($this -> params['prefix'] == 'admin') {
				$this -> layout = 'admin';
			}
		}

		/**
		 * isAuthorized method
		 *
		 * @return bool
		 */
		public function isAuthorized() {
			if(!isset($this -> params['prefix'])) {
				return true;
			} elseif($this -> params['prefix'] == 'admin' && in_array(2, $this -> getGruposUsuario())) {
				if(in_array($this -> action, $this -> exclusiveActions)) {
					return false;
				} else {
					return true;
				}
			} else {
				return false;
			}
		}

		/**
		 * getGruposUsuario method
		 *
		 * @return array
		 */
		protected function getGruposUsuario() {
			$usuario_id = $this -> Auth -> user('id');
			$this -> loadModel('GruposUsuario');
			$grupos_usuario = $this -> GruposUsuario -> find(
				'list',
				array(
					'conditions' => array('usuario_id' => $usuario_id),
					'fields' => array('grupo_id')
				)
			);
			return $grupos_usuario;
		}

	}
