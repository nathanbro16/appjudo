<?php
class AuthentValidator extends Authent
{

	/**
	* @param $data formulaire
	*/
	public function validatesconnect(array $data,\PDO $db):array{
		parent::validates($data, $db);
		$this->validate('login', 'separator', '-');
		if (empty($this->errors)) $this->validate('login', 'userexist');
		$this->validate('Password', 'passexist');
		if (empty($this->errors)) $this->validate('Password', 'passtrue');
		$return['errors'] = $this->errors;
		if (empty($this->errors)) $return['newpass'] = $this->newpass;
		if (empty($this->errors)) $return['infouser']['name'] = $this->data['name'];
		if (empty($this->errors)) $return['infouser']['surname'] = $this->data['surname'];
		return $return;
	}
	/**
	* @param $data formulaire
	*/
	public function validatesnewpassword(array $data,\PDO $db):array{
		parent::validates($data, $db);
		$this->validate('Password1', 'insertpassword', 'Password1', 'Password2');
		$return['errors'] = $this->errors;
		return $return;
	}


}
/**
 * Authentication
 */
class Authent
{

	private $bdd;
	protected $data;
	public $errors = [];
	public $nameerror = [];
	public $newpass = [];

	protected function validates(array $data,\PDO $db){
		$this->errors = [];
		$this->data = $data;
		$this->bdd = $db;

	}
	/**
	*
	* @param $field = , $method = nom de la fonction,
	*/
	public function validate(string $field, string $method, ...$parameters)
	{
		$this->nameerror[] = $field;
		if (empty($this->data[$field])) {
			$this->errors[$field] = "le champs $field n\'est pas remplis";

		}else{
			call_user_func([$this, $method], $field, ...$parameters);
		}
	}
	/**
	*
	*@param $filed champ,
	*@param $filed champ,

	*/
	public function separator(string $field, string $separator)
	{
		if (preg_match('#([a-zA-Z]+)'.$separator.'([a-zA-Z]+)#', $this->data[$field], $params)) {
			$this->data['name'] = $params[1];
			$this->data['surname'] = $params[2];
			return true;
		}
		$this->errors[$field] = "le champs login posse un problème.".$separator;
		return false;

	}
	public function userexist(string $field)
	{
		$requser = $this->bdd->prepare("SELECT COUNT(*) FROM user WHERE name = ? AND surname = ?");
		$requser->execute(array($this->data['name'], $this->data['surname']));

		$userinfo = $requser->fetch();
		if ($userinfo['COUNT(*)'] != 1) {
			$this->errors[$field] = "Cet utilisateur n\'exsiste pas !";
			return false;
			return false;
		}
		return true;
	}
	public function passexist(string $field)
	{
		if (empty($this->errors)) {
			$requser = $this->bdd->prepare("SELECT * FROM user WHERE name = ? AND surname = ?");
			$requser->execute(array($this->data['name'], $this->data['surname']));
			$userinfo = $requser->fetch();
			if (empty($userinfo['pass'])) {
				$this->newpass = true;
				return true;
			}
			return true;
		}
	}
	public function passtrue(string $filed)
	{
		if ($this->newpass === true) {
			$sql = "SELECT birth, id, name FROM user WHERE name = ? AND surname = ?";
			$texterror = "La date de naissance, ";
		}else{
			$sql = "SELECT pass, id, name FROM user WHERE name = ? AND surname = ?";
			$texterror = "Le mot de passe, ";
		}

		$requser = $this->bdd->prepare($sql);
		$requser->execute(array($this->data['name'], $this->data['surname']));
		$userinfo = $requser->fetch();

		if ($this->newpass === true) {
			if (is_numeric($this->data[$filed])) {
				if (mb_strlen($this->data[$filed]) === 8) {
					$month = substr($this->data[$filed], 2,-4 );
					$day = substr($this->data[$filed], 0,-6 );
					$year = substr($this->data[$filed], 4);
					if (checkdate($month, $day, $year)) {
						$birth = $year.'-'.$month.'-'.$day;
						if ($birth === $userinfo['birth'] ) {
							session_start();
							$_SESSION['id'] = $userinfo['id'];
							$_SESSION['newpass'] = true;
							return true;
						}
						$this->errors[$filed] = $texterror."ne correspond pas.";
						return false;
					}
					$this->errors[$filed] = $texterror."n'exsiste pas.";
					return false;
				}
			}
			$this->errors[$filed] = $texterror."dois comporter 8 chiffre. Exemple : ".date("dmY.");
			return false;

		}else{
			if (password_verify($this->data[$filed], $userinfo['pass'])) {
				session_start();
				$_SESSION['id'] = $userinfo['id'];
				$_SESSION['name'] = $userinfo['name'];
				return true;
			}
			$this->errors[$filed] = $texterror."ne correspond pas.";

		}


		return false;
	}
	public function insertpassword(string $filed, string $PWD1, string $PWD2)
	{
		if ($this->data[$PWD1] === $this->data[$PWD2]) {
			session_start();
			if (isset($_SESSION['id'], $_SESSION['newpass'])) {
				$password = password_hash($this->data[$PWD2], PASSWORD_BCRYPT);
			 	$sql = "UPDATE `user` SET `pass`= ? WHERE id = ?";
			 	$requser = $this->bdd->prepare($sql);
				$requser->execute(array($password, $_SESSION['id']));
				return true;

			}


			$this->errors[$filed] = "Erreur technique, merci de veuillez contacter un responsable.";
			return false;
		}
		$this->errors[$filed] = "Les champs non pas les même mot de passe.";
		return false;
    }
    

}
function is_connect():bool {
	if (session_status() === PHP_SESSION_NONE) {
			session_start();
	}
	return !empty($_SESSION['name']) and !empty($_SESSION['id']) and !isset($_SESSION['newpass']) ;
}
function force_user_connect():void {
	if(!$this->is_connect()){
			http_response_code(401);
			exit();
	}
}
