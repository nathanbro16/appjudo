<?php
/**
 * 
 */
class inscripevent 
{
	private $bdd;
	private $idevent;	
	function __construct($idevent = null , $DB)
	{
		if ($idevent == null) {
			throw new Exception("Vous devez présier un id d'un évènement.");
		}
		$this->bdd = $DB->BD_Connetion();
		$this->idevent = $idevent;
	}
	public function validins()
	{
		$statement = $this->bdd->prepare('SELECT * FROM events WHERE id = ? LIMIT 1');
		$statement->execute(array($this->idevent));
		$contenu = $statement->fetch();
		if ($contenu === false) {
			throw new Exception("Cet évènement n'exsite pas.");
		}
		if ($contenu['inscription'] <= 0) {
			throw new Exception("Il n'est pas paussible de s'inscrire a cet évènement.");
		}
		return $contenu;

	}
	public function getjobs()
	{
		$sql = "SELECT events.eventname, Jobs_Events.name, Jobs_Events.id, Jobs_Events.Max_person, Jobs_Events.Max_old FROM Jobs_Events INNER JOIN events ON  events.id = Jobs_Events.id_Event WHERE events.id = $this->idevent";
		$statement = $this->bdd->query($sql);
		return $statement->fetchAll();
	}
	public function getperson($idjobs)
	{
		$sql = "SELECT * FROM `Person_Events` WHERE id_Jobs = $idjobs";
		$statement = $this->bdd->query($sql);
		$getperson = $statement->fetchAll();

		return count($getperson);
	}
	public function age($date){
		if ($date === '0000-00-00') {
			$date = date('Y-m-d');
		}
	  	$d = strtotime($date);
	  	return (int) ((time() - $d) / 3600 / 24 / 365.242);
	}
	public function create($data, $id){
		$statement = $this->bdd->prepare('INSERT INTO `Person_Events`(`id_User`, `id_Jobs`) VALUES (?, ?)');
		return $statement->execute(array($data['selectjob'], $id));
	}

}

/**
 * 
 */
class Validator
{
	private $data;
	public $errors = [];
	public $nameerror = [];
	private $bdd;
	private $infouser;

	protected function validates(array $data, \PDO $bdd, user $infouser){
		$this->errors = [];
		$this->data = $data;
		$this->bdd = $bdd;
		$this->infouser = $infouser;

	}
	public function validate(string $field, string $method, ...$parameters)
	{
		$this->nameerror[] = $field;
		if (empty($this->data[$field])) {
			$this->errors[$field] = "le champs $field n\'est pas remplis";

		}else{
			call_user_func([$this, $method], $field, ...$parameters);
		}
	}
	public function jobsexiste(string $field):bool
	{
		$sql = "SELECT * FROM `Jobs_Events` WHERE id = ".$this->data[$field]." LIMIT 1";
		$statement = $this->bdd->query($sql);
		$verfify = $statement->fetch();
		if ($verfify === false) {
			$this->errors[$field] = "ce jobs n'exsite pas";
			return false;
		}
		return true;

	}
	public function usernotjobs(string $field):bool
	{
		$sql = "SELECT * FROM `Person_Events` WHERE id_User = ?, id_Jobs = ? ";
		//$statement = $this->bdd->query($sql);
		$statement = $this->bdd->prepare($sql);
		$statement->execute(array($this->infouser->getiduser(), $this->data[$field]));
		$userexist = $statement->fetchAll();
		if ($userexist != true) {
			$this->errors[$field] = "Vous êtes déja inscrit a cette évènement";
			return false;
		}
		return true;
	}

}
/**
 * @param array $data
 * @return array / bool
 *
 */
class insValidator extends Validator
{
	public function validates(array $data, \PDO $bdd, user $infouser){
		parent::validates($data, $bdd, $infouser);
		$this->validate('selectjob', 'jobsexiste');
		$this->validate('selectjob', 'usernotjobs');
		$this->validate('selectjob', 'usernotjobs');
		return $this->errors;
	}

}
