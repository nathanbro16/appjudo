<?php
class user
{
    private $id;
	private $grdsite;
	public $name;
    private $bdd;

    function __construct (\PDO $bdd, string $RedirectURL){
        $this->ini_bdd($bdd);
        $this->force_user_connect($RedirectURL);
        $this->ini_user();

    }
    private function is_connect():bool {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return !empty($_SESSION['name']) and !empty($_SESSION['id']) and !isset($_SESSION['newpass']) ;
    }
    public function force_user_connect():void {
        if(!$this->is_connect()){
            http_response_code(401);
            exit();
        }
    }
    private function ini_bdd(\PDO $bdd){
        $this->bdd = $bdd;
    }
    public function ini_user(){
        $this->id = $_SESSION['id'];
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
