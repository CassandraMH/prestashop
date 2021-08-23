<?php

//nom du fichier et nom de class identique
class MonKitCreatif extends ObjectModel
{

    public $id_mon_kit_creatif;
    public $titre;

    //tableau de definition de ma class
    public static $definition = array(
        'table' => 'mon_kit_creatif', //nom de la table sans prefix
        'primary' => 'id_mon_kit_creatif', // clé primaire
        'multilang' => false, // pas de champ multilingue
        'fields' => array( // champs de ma tables
            'titre' => array(
                'type' => self::TYPE_STRING, //type de donnée (string, int, date, float, bool, etc ...)
                'validate' => 'isCleanHtml', //nom de la méthode de validation de la class Validate
                'required' => true,//besoin de préciser seulement si obligatoire

            ),

        )

    );

    //liaison à la table
    public static function getCategories()
    {
        $sql = Db::getInstance()->executeS('Select * from ' . _DB_PREFIX_ . 'mon_kit_creatif');
        return $sql;
    }

}


