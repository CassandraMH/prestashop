<?php

//nom du fichier et nom de class identique
class SousCategories extends ObjectModel
{

    public $id_mon_kit_creatif_subcategorie;
    public $titre_category;
    public $image;
    public $id_category;

    //tableau de definition de ma class
    public static $definition = array(
        'table' => 'mon_kit_creatif_subcategorie', //nom de la table sans prefix
        'primary' => 'id_mon_kit_creatif_subcategorie', // clé primaire
        'multilang' => false, // pas de champ multilingue
        'fields' => array( // champs de ma tables
            'titre_category' => array(
                'type' => self::TYPE_STRING, //type de donnée (string, int, date, float, bool, etc ...)
                'validate' => 'isCleanHtml', //nom de la méthode de validation de la class Validate
                'required' => true,//besoin de préciser seulement si obligatoire
            ),
            'image' => array(
                'type' => self::TYPE_STRING, //type de donnée (string, int, date, float, bool, etc ...)
            ),
            'id_category' => array(
                'type' => self::TYPE_INT, //type de donnée (string, int, date, float, bool, etc ...)
            )
        )

    );

    //liaison à la bdd
    public static function getSousCategories(){
        $sql = Db::getInstance()->executeS('Select * from '._DB_PREFIX_.'mon_kit_creatif_subcategorie');
        return $sql;
    }

    public static function getSousCategoriesByidCategories($id){
        $sql = Db::getInstance()->executeS('Select * from '._DB_PREFIX_.'mon_kit_creatif_subcategorie WHERE id_category = '.$id);
        return $sql;
    }

    public static function getCategories() {
        $sql = Db::getInstance()->executeS('SELECT * FROM '._DB_PREFIX_.'mon_kit_creatif');
        return $sql;
    }

}