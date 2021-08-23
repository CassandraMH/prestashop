<?php

//nom du fichier controller admin : AdminNomFichierController
require_once(_PS_ROOT_DIR_ . '/modules/cmh_mon_kit_creatif/classes/monkitcreatif.php');

//class Mon_Kit_Creatif
class Cmh_Mon_Kit_Creatif extends Module
{

    //création du constructeur
    public function __construct()
    {

        //nom technique
        $this->name = 'cmh_mon_kit_creatif';

        //nom public, s'affichera dans Presta
        $this->displayName = 'Mon kit creatif';

        //qu'est-ce que c'est comme module
        $this->tab = 'front_office_features';

        //la version du module
        $this->version = '0.1.1';

        //auteur
        $this->author = 'Cassandra Muller-Heyob';

        //description
        $this->description = 'Ce module offre la possibilité de crée sa propre page et de pouvoir configurer son propre kit créatif.';

        //activation de bootstrap
        $this->bootstrap = 'true';

        //appel du constructeur de la classe parent
        parent::__construct();
    }

    //permet d'installer un Hook
    public function install()
    {

        if (!parent::install()
            //registerHook permet d'accrocher notre module à un Hook
            || !$this->registerHook('displayHome')
            || !$this->registerHook('displayMonKitHook')
            || !$this->registerHook('actionFrontControllerSetMedia')
            || !$this->installDb()

                //instllation de 2 Tab car 2 AdminCatalog
            || !$this->installTab('AdminCatalog', 'AdminKitCategories', 'Kit Categorie')
            || !$this->installTab('AdminCatalog', 'AdminKitSousCategories', 'Kit Sub Categorie')) {

            return false;
        } else {
            return $this;
        }

    }

    //ajout d'un nouvel onglet
    //3 paramétres : Controller parent, nom de mon controller, nom public
    public function installTab($parent, $classcontroller, $name)
    {

        $tab = new Tab();
        $tab->id_parent = (int)Tab::getIdFromClassName($parent); //methode qui me renvoie l'id de l'onglet parent en fonction du nom de la class
        $tab->name = array();

        //récupère les langues actives de notre boutique pour lui fournir un nom public pour chaque langue
        foreach (Language::getLanguages(true) as $lang) {

            $tab->name[$lang['id_lang']] = $name;
        }

        $tab->class_name = $classcontroller;
        $tab->module = $this->name;
        $tab->active = 1;

        return $tab->add();

    }

    //permet de désintaller
    public function uninstall() {

        Db::getInstance()->execute('DROP TABLE IF EXISTS '._DB_PREFIX_.'mon_kit_creatif');

        return parent::uninstall();
    }

    //installation d'une BDD
    public function installDb(){

        // class DB stock les informations de connexion bbd
        //getInstance => objet PDO
        // clé primaire doit TOUJOURS ETRE DE FORME id_+nomtable

        //TABLE : CATEGORIE
        $sql = Db::getInstance()->execute('
                CREATE TABLE IF NOT EXISTS '._DB_PREFIX_.'mon_kit_creatif (
                   id_mon_kit_creatif INT UNSIGNED NOT NULL AUTO_INCREMENT,
                    titre TEXT NOT NULL,
                    PRIMARY KEY (id_mon_kit_creatif)                
               ) ENGINE = '._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;'
        );

        //TABLE : SOUS CATEGORIE
        $sql = Db::getInstance()->execute('
                CREATE TABLE IF NOT EXISTS '._DB_PREFIX_.'mon_kit_creatif_subcategorie (
                   id_mon_kit_creatif_subcategorie INT UNSIGNED NOT NULL AUTO_INCREMENT,
                   id_category INT UNSIGNED NOT NULL,
                    titre_category TEXT NOT NULL,
                    image TEXT NOT NULL,
                    PRIMARY KEY (id_mon_kit_creatif_subcategorie)                
               ) ENGINE = '._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;'
        );

        return $sql;
    }

    //methode qui s'exécute sur le hook
    //nom de la méthode : hook + nom du hook
    public function hookDisplayHome()
    {

        //chemin d'un fichier tpl d'un module pour un hook : views/templates/hook/fichier.tpl
        return $this->display(__FILE__, 'pagekit.tpl');

    }

    public function hookDisplayMonKitHook() {

        return $this->hookDisplayHome();
    }

    //méthode qui permet d'inclure des fichiers css/
    public function hookActionFrontControllerSetMedia(){

        $this->context->controller->registerStylesheet(
            'module-cmh_mon_kit_creatif-style', //identifiant
            'modules/'.$this->name.'/views/assets/css/kitcreatif.css' //chemin de la racine
        );

    }
}