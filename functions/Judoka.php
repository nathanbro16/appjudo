<?php
/**
 *
 */
class Judoka extends session
{
    private $bdd;
    private $judokas;

    public function __construct($DB)
    {
        $this->bdd = $DB->BD_Connetion();
        $this->list_judoka_user();
    }
    private function list_judoka_user()
    {
        $id = $this->Get_id_User();
        $sql = "SELECT judoka.Name,
        judoka.Surname, 
        judoka.Sexe, 
        judoka.Rank,
        judoka.Year_of_birth 
        FROM `judoka` 
        INNER JOIN link_judoka 
        ON judoka.id = link_judoka.Id_Judoka 
        WHERE link_judoka.Id_User = ? ";
		$statement = $this->bdd->prepare($sql);
        $statement->execute(array($id));
        $judokas = $statement->fetchAll();
        $statement->closeCursor();
        if ($judokas === false) {
			throw new Exception('Aucun résultat n\'a été trouvé');
        }
        $this->judokas = $judokas;

    }
    public function Get_css_navbar():void
    {
        $RankJudo = GetParamsRankJudo();
        $list_judokas = $this->judokas;
        if (count($list_judokas) > 1) {
            if (!isset($_GET['judoka'])) {
                echo $RankJudo[$list_judokas[0]['Rank']]['css'];
            }else{
                foreach ($list_judokas as $judoka) {
                    echo ( $_GET['judoka'] === $judoka['Name'].'-'.$judoka['Surname'] ) ? $RankJudo[$judoka['Rank']]['css'] : null ;
                }
            }
        } else {
            echo $RankJudo[$list_judokas[0]['Rank']]['css'];
        }
    }
    public function Get_list_Judokas($userinfo):void{
        $RankJudo = GetParamsRankJudo();
        $list_judokas = $this->judokas;
        if (count($list_judokas) > 1) {
           if (!isset($_GET['judoka'])) {
               echo '<img src="../grade/'.$RankJudo[$list_judokas[0]['Rank']]['html'].'.png" width="60" height="30" class="d-inline-block align-top" alt="">';
           }else{
               foreach ($list_judokas as $judoka) {
                    echo ( $_GET['judoka'] === $judoka['Name'].'-'.$judoka['Surname'] )  ? '<img src="../grade/'.$RankJudo[$judoka['Rank']]['html'].'.png" width="60" height="30" class="d-inline-block align-top" alt="">' : null ;
               }
           }
           
            echo '<select class="custom-select" id="Judoka">';
                foreach ($list_judokas as $judoka):
                    $select = (isset($_GET['judoka']) and $_GET['judoka'] === $judoka['Name'].'-'.$judoka['Surname'] )  ? 'selected' : '' ; 
                    echo '<option '.$select.' value="'.$judoka['Name'].'-'.$judoka['Surname'].'">'.$judoka['Name'].'-'.$judoka['Surname'].' / '.$this->getnameyear($judoka['Sexe'], $judoka['Year_of_birth']).'</option>
                    ';
                endforeach;
               
            echo'</select>';
        } else {
            if ($list_judokas[0]['Surname'] === $userinfo->getsurname() AND $list_judokas[0]['Name'] === $userinfo->getname() ) {
                ?><h5 class="text-right" style="margin-bottom:0;"> <?=$RankJudo[$list_judokas[0]['Rank']]['name']?> / <?php
            } else {
                ?><img src="../grade/<?=$RankJudo[$list_judokas[0]['Rank']]['html']?>.png" width="60" height="30" class="d-inline-block align-top" alt="">
                <h5 class="text-right" style="margin-bottom:0;">  <?= $list_judokas[0]['Surname'] ?>-<?= $list_judokas[0]['Name'] ?> / <?php
            }
            echo $this->getnameyear($list_judokas[0]['Sexe'], $list_judokas[0]['Year_of_birth']).'</h5>';
        }
    }
    public function getnameyear(string $Sexe, $bithday){
        $d = strtotime($bithday);
	    $old = ((time() - $d) / 3600 / 24 / 365.242);
		$categoryages = GetParamsCategoryAge();
		foreach ($categoryages[$Sexe] as $key => $categoryage) {
			if ($key <= $old) {
				$resultat = $categoryage;
			}
		}
		return $resultat;
	}
  
}



