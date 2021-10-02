<?php
$tableau_provisoir_1=array();
$tableau_provisoir_2=array();
$tableau_final=array();
$tabl_vierge=array();
$tabl_vierge=$_POST;
foreach ($tabl_vierge as $cle => $valeur) {
    echo ' ' . $cle . '  ' . $valeur . "\n" ?> <br /> <?php ; 
}



//CREATION DU TABLEAU DE REFERENCE
$tabloasso=array();
//RECOIS TOUTES LES CLES VALEURES EN INDICE VALEURES
$tabloasso=(array_values($tabl_vierge));
foreach ($tabloasso as $cle => $valo) {
    echo ' ' . $cle . '  ' . $valo . "\n" ?> <br /> <?php ;
}
//Dépile la dernière valeure du tabloasso pour variable code F pour utilisation ecriture fichier texte
$code_F=array_pop($tabloasso);


//compte le nombre de valeurs du tableaux et les divises par 2 car 1 element comprend le code et la quantité
$compteur= (count($tabloasso) / 2);
echo 'nombre d occurences à traiter = ' .$compteur . ' ' ?> <br /> <?php ;

/*if (!empty($tabloasso)){
    echo 'le tableau est PLEIN';
}*/

//Fonction
function BaseDonnee($code,$qte)
    {
        $bdd = new PDO('mysql:host=localhost;dbname=test','root','root', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        $reponse = $bdd->prepare('SELECT code,quantite FROM nomenclature WHERE code_parent = :ref AND Fin_de_Vie IS NULL' );
                $reponse->bindValue('ref',$code);
                $reponse->execute();
                //definition des tableaux
                $tablo1=array();
                $tablo2=array();
                $tableau=array();
                $ind=0;

                while ($donnees = $reponse->fetch())
                    {
                          //affectation code dans tableau 1 et quantite dans tableau 2
                        $tablo1[$ind]=$donnees['code'];
                         //calcul de la quantitée voulue
                        $tablo2[$ind]=(($donnees['quantite'])*$qte);
                          // increment de l indice
                        {
                            $ind=$ind+1;
                        }                                      
                    }
    $tableau = array_combine($tablo1,$tablo2);
                    
                $reponse->closeCursor();
        
        return ($tableau); //indique la valeur a renvoyer              
    }
// Fonction calcul des doublons

function Calcul_Des_Doublons($tab_1,$tab_2)
    {
        foreach ( array_keys ($tab_1+$tab_2)as $key)
        {
            $tab_3[$key] = @($tab_1[$key]+$tab_2[$key]);
        }
        ksort ($tab_3);
        return ($tab_3);

    }

?>  <?php
//essai boucle

    if ($compteur==1){
       /* echo 'traitement1';?> <br /> <?php */
        //depilage des valeures du tableau et mise des valeures dans variables
        $code_indice=array_shift($tabloasso);
        $qte_indice=array_shift($tabloasso);
        $tableau_provisoir_1 = BaseDonnee($code_indice,$qte_indice);
        $tableau_final=$tableau_provisoir_1;
        

    }
    elseif ($compteur==2){
        /*echo 'traitement2';?> <br /> <?php */
        for ($i=0; $i < $compteur; $i++){
            $code_indice=array_shift($tabloasso);
            $qte_indice=array_shift($tabloasso);
            if ($i==0){
                $tableau_provisoir_1 = BaseDonnee($code_indice,$qte_indice);
            }
            elseif ($i==1){
                $tableau_provisoir_2 = BaseDonnee($code_indice,$qte_indice);
            }

        }
        
    $tableau_intermediaire= Calcul_Des_Doublons($tableau_provisoir_1,$tableau_provisoir_2);
    //transfert resultats du tableau intermediaire vers tableau final
    $tableau_final=$tableau_intermediaire;
    //vidage du tableau intermediaire
    foreach ($tableau_intermediaire as $key=>$value){
        unset ($tableau_intermediaire[$key]);
    }

    }

//TRAITEMENT POUR 3 ET PLUS DE CODES
    elseif ($compteur>=3){
        /*echo 'traitement3';?> <br /> <?php */
        for ($i=0; $i < 2; $i++){
            $code_indice=array_shift($tabloasso);
            $qte_indice=array_shift($tabloasso);
            if ($i==0){
                $tableau_provisoir_1 = BaseDonnee($code_indice,$qte_indice);
            }
            elseif ($i==1){
                $tableau_provisoir_2 = BaseDonnee($code_indice,$qte_indice);
            }

        }
        
    $tableau_intermediaire= Calcul_Des_Doublons($tableau_provisoir_1,$tableau_provisoir_2);
    //tant que tableau primaire n'est pas vidé
    while (!empty($tabloasso)){
        $tableau_final=$tableau_intermediaire;
    //vidage du tableau intermediaire
    foreach ($tableau_intermediaire as $key=>$value){
        unset ($tableau_intermediaire[$key]);
    }
    //vidage des 2 tableaux provisoires
    foreach ($tableau_provisoir_1 as $key=>$value){
        unset ($tableau_provisoir_1[$key]);
    }
    foreach ($tableau_provisoir_2 as $key=>$value){
        unset ($tableau_provisoir_2[$key]);
    }
    //Depilage valeures suivantes
    $code_indice=array_shift($tabloasso);
    $qte_indice=array_shift($tabloasso);
    //recherche des valeures dans base de donnee
    $tableau_provisoir_1 = BaseDonnee($code_indice,$qte_indice);
    // calcul des doublons
    $tableau_intermediaire= Calcul_Des_Doublons($tableau_provisoir_1,$tableau_final);

}
$tableau_final=$tableau_intermediaire;
//lecture du nouveau tableau finale

    }

    
/*if (empty($tabloasso)){
    echo 'le tableau est vide maintenant';
}*/
//VIDAGE DES TABLEAUX INTERMEDIAIRES ET PROVISOIRES PAR SECURITE
foreach ($tableau_final as $cle => $valeur) {
    echo ' ' . $cle . '  ' . $valeur . "\n" ?> <br /> <?php ; 
}

        
?>
    <form action="index.php" method="post">
    <p>
    <input type="submit" value="retour" />
    </p>
    </form>
    <form action="fichier.php" method="post">
    <p>
    <input type="submit" value="fichier" />
    </p>
    </form> 
<?php
$indice=(count($tableau_final));
echo '' . $indice . ' articles' ; ?> <br /> <?php ;
//calcul et création dun tableau pour le pas du nombre delements pas de 10

$tableau_10=array();
for ($i=0;$i<$indice;$i++){
    $tableau_10[$i]=$i*10+10;
    if (($tableau_10[$i])==0){
        $tableau_10[$i]=10;
    }
}
foreach ($tableau_10 as $key => $value) {
    echo ' ' . $key . '  ' . $value . "\n" ?> <br /> <?php ; 
}
//ECRITURE DANS FICHIER TEXTE

$premiere_ligne='E;'.$code_F.';97;;1;1;1' ."\r\n";
$fichier = "nomenclature.txt";
$mode="c+b";
$mama=array();
$momo=array();
$ressource = fopen($fichier,$mode);
fwrite($ressource,$premiere_ligne);
$wos=0;
foreach($tableau_final as $assoc => $val){
    $mama=$assoc;
    $momo=$val;
    $texte='L;'.$tableau_10[$wos].';0;'.$mama.';1;1;'.$momo.';1;1'."\r\n" ;
    fwrite($ressource,$texte);
    $wos=$wos+1;
        }
fclose($ressource);



/*$fichier=fopen('nomenclature.txt','c+b');
for ($increment=0;$increment+1<$indice;$increment++){
    $valeur_a_ecrire=$tableau_final[$increment]
    fputs($fichier,$valeur_a_ecrire ."\n");
}*/
//fputs($fichier , "toto \n" );
//fputs($fichier , "tata \n");
//fputs($fichier , "titi \n");
?>
