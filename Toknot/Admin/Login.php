<?php

/**
 * Toknot (http://toknot.com)
 *
 * @copyright  Copyright (c) 2011 - 2013 Toknot.com
 * @license    http://toknot.com/LICENSE.txt New BSD License
 * @link       https://github.com/chopins/toknot
 */

namespace Toknot\Admin;

use Toknot\Admin\AdminBase;
use Toknot\User\UserClass;

class Login extends AdminBase {

	protected $permissions = 0777;

	public function GET() {
		self::$FMAI->D->act = 'login';
		self::$FMAI->D->message = '';
		self::$FMAI->display('login');
	}

	public function POST() {
		$userName = $_POST['username'];
		$password = $_POST['password'];
		$user = UserClass::login($userName, $password);
		if ($user) {
			if (isset($_POST['week'])) {
				$user->setLoginExpire('1w');
			}
			$this->setAdminLogin($user);
			self::$FMAI->redirectController('\Index');
		} else {
			self::$FMAI->D->act = 'login';
			self::$FMAI->D->message = 'Username or password error';
			self::$FMAI->display('login');
		}
	}
	public function logout() {
		$this->currentUser->logout();
		$this->SESSION->unsetSession();
	}

}

