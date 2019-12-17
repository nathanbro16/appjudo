<?php
class user extends session
{
	private $grdsite;
	public $name;
  private $bdd;
  private $session;
	private $infouser;

  function __construct ($DB, string $RedirectURL, string $Dir){
		$this->bdd = $DB->BD_Connetion();
    $session = new session();
    $session->force_user_connect($Dir);
    $this->session = $session;

  }
	public function find_user_info() {
    $id = $this->Get_id_User();
		$statement = $this->bdd->prepare("SELECT * FROM user WHERE id = ? ");
		$statement->execute(array($id));
		$statement->setFetchMode(\PDO::FETCH_CLASS, Userinfo::class);
		$result = $statement->fetch();

		$this->infouser = $result;
    if ($result === false) {
			throw new Exception('Aucun résultat n\'a été trouvé');
		}
		return $result;
	}
	public function getgrdjudo(){
		$gradejudo = GetParamsRankJudo();
		$grdinfo = $gradejudo[(7 - 1)];
		return $grdinfo;
	}
	public function getage():int{
	  $d = strtotime($this->infouser->getbirth());
	  return (int) ((time() - $d) / 3600 / 24 / 365.242);
	}
	
	public function getgrdsite()
	{
		return $this->grdsite;
	}
}
/**
 *
 */
class Userinfo
{
	private $name;
	private $surname;
	private $birth;
	private $sexe;
	private $grdsite;

	public function getname():string
  {
		return $this->name;
	}
	public function getsurname():string
  {
    return $this->surname;
  }
  public function getbirth():string
  {
    return $this->birth;
  }
  public function getsexe():string
  {
    return $this->sexe;
  }
  public function getgrdsite():int
  {
    return $this->grdsite;
  }
}
