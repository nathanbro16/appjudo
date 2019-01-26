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
		if (empty($this->errors)) $this->validate('Password', 'passexist');
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
<<<<<<< HEAD
	*@param $filed champ,
=======
	*@param $filed champ, 
>>>>>>> ce8e5abd6feb77ad1c50a1f78fb45be4c08f1711
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
<<<<<<< HEAD

=======
				
>>>>>>> ce8e5abd6feb77ad1c50a1f78fb45be4c08f1711
	}
	public function userexist(string $field)
	{
		$requser = $this->bdd->prepare("SELECT COUNT(*) FROM user WHERE name = ? AND surname = ?");
		$requser->execute(array($this->data['name'], $this->data['surname']));

		$userinfo = $requser->fetch();
		if ($userinfo['COUNT(*)'] != 1) {
			$this->errors[$field] = "Cet utilisateur n\'exsiste pas !";
<<<<<<< HEAD
			return false;
=======
			return false; 
>>>>>>> ce8e5abd6feb77ad1c50a1f78fb45be4c08f1711
		}
		return true;
	}
	public function passexist(string $filed)
	{
		$requser = $this->bdd->prepare("SELECT * FROM user WHERE name = ? AND surname = ?");
		$requser->execute(array($this->data['name'], $this->data['surname']));
		$userinfo = $requser->fetch();
		if (empty($userinfo['pass'])) {
			$this->newpass = true;
<<<<<<< HEAD
			return true;
=======
			return true; 
>>>>>>> ce8e5abd6feb77ad1c50a1f78fb45be4c08f1711
		}
		return true;
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
<<<<<<< HEAD

=======
			
>>>>>>> ce8e5abd6feb77ad1c50a1f78fb45be4c08f1711
		}else{
			if (password_verify($this->data[$filed], $userinfo['pass'])) {
				session_start();
				$_SESSION['id'] = $userinfo['id'];
				$_SESSION['name'] = $userinfo['name'];
				return true;
			}
			$this->errors[$filed] = $texterror."ne correspond pas.";

		}
<<<<<<< HEAD

=======
		
>>>>>>> ce8e5abd6feb77ad1c50a1f78fb45be4c08f1711
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
<<<<<<< HEAD

=======
		 	
>>>>>>> ce8e5abd6feb77ad1c50a1f78fb45be4c08f1711
			$this->errors[$filed] = "Erreur technique, merci de veuillez contacter un responsable.";
			return false;
		}
		$this->errors[$filed] = "Les champs non pas les même mot de passe.";
		return false;
	}

/*
	public function exeval()
	{
		$requser = $this->bdd->prepare("SELECT *, COUNT(*) FROM user WHERE name = ? AND pass = ? AND surname = ?");
		$requser->execute(array($this->name, $this->pass, $this->surname));
		$userinfo = $requser->fetch();
		if ($userinfo['COUNT(*)'] == 1) {
			session_start();
			$_SESSION['id'] = $userinfo['id'];
			$_SESSION['name'] = $userinfo['name'];
			return true;
		}
	}
*/

}
/**
 * infouser
 */
<<<<<<< HEAD
class user
=======
class user 
>>>>>>> ce8e5abd6feb77ad1c50a1f78fb45be4c08f1711
{
	private $id;
	private $grdsite;
	public $name;
	private $bdd;
	function __construct($id = null, $bdd){
		if ($id === null) {
			throw new Exception("Vous êtes déconnectée.");
		}
		$this->id = $id;
		$this->bdd = $bdd;
	}
	public function infosuer(){
		$id = $this->id;
		$requser = $this->bdd->prepare("SELECT * FROM user WHERE id = ?");
		$requser->execute(array($id));
		$userinfo = $requser->fetch();
		$this->grdsite = $userinfo['grdsite'];
		return $userinfo;
	}
	public function grdjudo($grade){
		$gradejudo = array(
			array('name' => 'blanche'),
			array('name' => 'blanche - jaune'),
			array('name' => 'jaune'),
			array('name' => 'jaune - orange'),
			array('name' => 'orange'),
			array('name' => 'orange - verte'),
			array('name' => 'verte'),
			array('name' => 'verte - bleu'),
			array('name' => 'bleu'),
			array('name' => 'Maron'),
			array('name' => '1<SUP>er</SUP> Dan'),
			array('name' => '2<SUP>ème</SUP> Dan'),
			array('name' => '3<SUP>ème</SUP> Dan'),
			array('name' => '4<SUP>ème</SUP> Dan'),
			array('name' => '5<SUP>ème</SUP> Dan'),
			array('name' => '6<SUP>ème</SUP> Dan'),
			array('name' => '7<SUP>ème</SUP> Dan'),
			array('name' => '8<SUP>ème</SUP> Dan'),
			array('name' => '9<SUP>ème</SUP> Dan'),
			array('name' => '10<SUP>ème</SUP> Dan'),
		);
		$grdinfo = $gradejudo[($grade - 1)];
		$grdinfo['html'] = $grade;
<<<<<<< HEAD
		return $grdinfo;
	}
	public function age($date){
=======
		//$reqgrdju = $this->bdd->prepare("SELECT * FROM grdjudo WHERE id = ?");
		//$reqgrdju->execute(array($grade));
		//$grdinfo = $reqgrdju->fetch();
		return $grdinfo;
	}
	public function age($date){

>>>>>>> ce8e5abd6feb77ad1c50a1f78fb45be4c08f1711
	  $d = strtotime($date);
	  return (int) ((time() - $d) / 3600 / 24 / 365.242);
	}
	public function nameyear($age, $sexe){
<<<<<<< HEAD
		$categoryage = array(
			'Male' =>
			array(
				array('5' => 'Mini poussins'),
				array('7' => 'Poussins'),
				array('9' => 'Pupilles'),
				array('11' => 'Benjamins'),
				array('13' => 'Minimes'),
				array('15' => 'Cadets'),
				array('18' => 'Juniors'),
				array('21' => 'Seniors'),
			),
			'female' =>
			array(
				array('5' => 'Mini poussins'),
				array('7' => 'Poussins'),
				array('9' => 'Pupilles'),
				array('11' => 'Benjamines'),
				array('13' => 'Minimes'),
				array('15' => 'Cadettes'),
				array('18' => 'Juniors'),
				array('21' => 'Seniors'),
			),
		);

		
=======

		if ($age <= '5') {
			echo "Mini poussins";

		} elseif ($age <='7') {
			echo "poussins";
		} elseif ($age <='9') {
			echo "pupilles";
		}elseif ($age <='11') {
			echo "benjamins";
		} elseif ($age <='13') {
			echo "minimes";		
		}elseif ($age <= '18') {
			if ($sexe == "male") {
				echo "CADET";
			}elseif ($sexe == 'female') {
				echo "CADETTE";
			}else{
				echo "CADET(TE)";
			}
		}
>>>>>>> ce8e5abd6feb77ad1c50a1f78fb45be4c08f1711
	}
	public function getgrdsite()
	{
		return $this->grdsite;
	}
	public function getiduser()
	{
		return $this->id;
	}
}
/**
 * droit de l'utilisateur
 */
class gradesysteme
{
	private $grdsys;
<<<<<<< HEAD

=======
	
>>>>>>> ce8e5abd6feb77ad1c50a1f78fb45be4c08f1711
	function __construct($grdsys)
	{
		if ($grdsys >= '4') {
			throw new Exception("Votre grade systeme comporte une erreur.");
<<<<<<< HEAD

=======
			
>>>>>>> ce8e5abd6feb77ad1c50a1f78fb45be4c08f1711
		}
		$this->grdsys = $grdsys;
	}

	public function admincalendar()
	{
		if ($this->grdsys >= '1') {
			return true;
		}
	}
}
