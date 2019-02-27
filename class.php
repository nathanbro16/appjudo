<?php

/**
 * infouser
 */

class user
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
		$gradejudo = GetParamsRankJudo();
		$grdinfo = $gradejudo[($grade - 1)];
		return $grdinfo;
	}
	public function age($date){
	  $d = strtotime($date);
	  return (int) ((time() - $d) / 3600 / 24 / 365.242);
	}
	public function nameyear($age, $sexe){
		$categoryages = GetParamsCategoryAge();
		foreach ($categoryages[$sexe] as $key => $categoryage) {
			if ($key <= $age) {
				$resultat = $categoryage;
			}
		}
		echo $resultat;
		//return 'indisponible';
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
	function __construct($grdsys)
	{
		if ($grdsys >= '4') {
			throw new Exception("Votre grade systeme comporte une erreur.");
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
