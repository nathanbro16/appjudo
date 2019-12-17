<?php
class AuthentValidator extends Authent
{

	/**
	* @param $data formulaire
	*/
	public function validatesconnect(array $data,object $db):array{
		parent::validates($data, $db);
		$this->validate('login', 'separator', '-'); 
		if (empty($this->errors)) $this->validate('login', 'UserExist');
		$this->validate('Password', 'PasswordExist');
		if (empty($this->errors)) $this->validate('Password', 'PasswordIsValid');
		$return['errors'] = $this->errors;
		if (empty($this->errors)) $return['newpass'] = $this->newpass;
		if (empty($this->errors)) $return['infouser']['name'] = $this->data['name'];
		if (empty($this->errors)) $return['infouser']['surname'] = $this->data['surname'];
		return $return;
	}
	/**
	* @param $data formulaire
	*/
	public function validatesnewpassword(array $data,object $db):array{
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

	protected function validates(array $data,object $db){
		$this->errors = [];
		$this->data = $data;
		$this->bdd = $db->BD_Connetion();


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
	*@param $separator font separator,
	*@return boolean 
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
	public function UserExist(string $field)
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
	public function PasswordExist(string $field)
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
	private function BirthYear(string $field){
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

	}
	public function PasswordIsValid(string $filed)
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
			$this->BirthYear($filed);
		}else{
			if (password_verify($this->data[$filed], $userinfo['pass'])) {
				$session = new session();
				$session->Init_User_Session($userinfo['id'], $userinfo['name']);
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
/**
 *
 */
class session
{
	private $Time_Connect;

	private function is_activate()
	{
		if (session_status() === PHP_SESSION_NONE) {
			session_start();
		}
	}
	private function is_connect():bool
	{
		$this->is_activate();
		return !empty($_SESSION['user']) and $this->Time_User_Connect_Is_Valid() ;
	}
	public function force_user_connect(string $Dir):void
	{
		if(!$this->is_connect()){
				http_response_code(401);
				require_once $Dir.'error/401.php';
				die();
		}
	}
	protected function Get_id_User()
	{
		return $_SESSION['user']['id'];
	}
	/**
	 * initialization form user session 
	 * @param id user 
	 * @param name user
	 */
	public function Init_User_Session($id_user, $name){
		$this->is_activate();
		$_SESSION['user']['id'] = $id_user;
		$_SESSION['user']['name'] = $name;
		$_SESSION['user']['date'] = new DateTime();
	}
	/**
	 * initialization form session password
	 * @param id user
	 */
	public function Init_Password($id_user){
		$_SESSION['Password']['id'] = $id_user;
		$_SESSION['Password']['date'] = new DateTime();
	}
	/**
	 * Verify time connect not exeed 
	 * @return boolean
	 */
	public function Time_User_Connect_Is_Valid():bool{
		$Time_Current = new DateTime();
		$Time_Authentication = clone $_SESSION['user']['date'] ;
		$Time_Authentication->add(new DateInterval('PT30M'));
		return ($Time_Authentication < $Time_Current ) ? false : true ;
	}

}

