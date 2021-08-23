<?php

//class Inforomation_Footer
class Cmh_Information_Footer extends Module {

    //création du constructeur
    public function __construct(){

        //nom technique
        $this->name = 'cmh_information_footer';

        //nom public, s'affichera dans Presta
        $this->displayName = 'Module Informations Footer';

        //qu'est-ce que c'est comme module
        $this->tab = 'front_office_features';

        //la version du module
        $this->version = '0.1.2';

        //auteur
        $this->author = 'Cassandra Muller-Heyob';

        //description
        $this->description = 'Ce module permet de mettre un bloc d\'horaires dans le footer.';

        //activation de bootstrap
        $this->bootstrap = 'true';

        //appel du constructeur de la class parent
        parent::__construct();
    }

    //permet d'installer un Hook + Db
    public function install(){

        if(!parent::install()
            //registerHook permet d'accrocher notre module à un Hook
            //mise en place du hook dans le footer + liaison au css
            || !$this->registerHook('displayFooter')
            || !$this->registerHook('actionFrontControllerSetMedia')
            || !$this->installDb()){

            return false;
        }
        else{
            return true;
        }

    }

    //permet de désintaller
    public function uninstall() {

            // supprime la ligne dans ps_configuration en fonction du nom
            Configuration::deleteByName('HORAIRE');

            // table information_footer
            Db::getInstance()->execute('DROP TABLE IF EXISTS '._DB_PREFIX_.'information_footer');

        return parent::uninstall();
    }

    //création de la page de configuration
    //function qui signale à Presta que le module est configurable
    //récupère les informations envoyées par le formulaire
    public function getContent(){

        //variable pour afficher les notifications
        $output = NULL;

        //permet de savoir si un formulaire est envoyée
        if(Tools::isSubmit('submit_cmhinformationfooter')){

            //récupère les valeurs envoyés pour les horaires
            $horaire = Tools::getValue('HORAIRE');

            //faire des tests de données avec la class Validate
                //création ou mise à jour d'un champ de la table configuration
                //2 paramètres: le nom du champ name, sa valeur
                Db::getInstance()->insert('information_footer', array(
                    'horaire' => $horaire, // psql évite les injections SQL
                ));

                $output .= $this->displayConfirmation('La valeur est bien enregistrée');
        }

        return $output.$this->displayForm();
    }

    //methode qui permet de crée un formulaire via Helperform
    public function displayForm(){

        //tableau qui contient les informations du formulaire
        $form_configuration['0']['form']=[

            'legend' => [
                'title' => 'Configuration des horaires d\'ouverture dans le footer',
            ],
            'input' => [
                [
                    //choix du textaea pour les horaires car </br>
                    'type' => 'textarea',
                    'label' => $this->l('Les horaires :'),
                    'tinymce' => true,
                    'required' => true,
                    'name' => 'HORAIRE',
                    'class' => 'rte',
                    'autoload_rte' => true,
                ],
            ],
            //sauvegarde des données
            'submit' => [
                'title'=> $this->l('Save'),
                'class' => 'btn btn-default pull-right'
            ]

        ];

            //création d'un formulaire
            $helper = new HelperForm();

            $helper->module = $this; //instance de notre module
            $helper->controller = $this->name; //nom technique
            $helper->token = Tools::getAdminTokenLite('AdminModules'); //clée de sécurité propre à notre session

            //genère le lien de l'action du formulaire, la page qui va traité les informations
            $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
            $helper->default_form_language = (int)Configuration::get('PS_LANG_DEFAULT');
            $helper->title = $this->displayName;

            //genère l'attribut name du bouton action
            $helper->submit_action = 'submit_cmhinformationfooter';

        return $helper->generateForm($form_configuration);

    }

    //installation d'une BDD
    public function installDb(){

            // class DB stock les informations de connexion bbd
            //getInstance => objet PDO
            // clé primaire doit TOUJOURS ETRE DE FORME id_+nomtable
            $sql = Db::getInstance()->execute('
                    CREATE TABLE IF NOT EXISTS '._DB_PREFIX_.'information_footer (
                        id_information_footer INT UNSIGNED NOT NULL AUTO_INCREMENT, 
                        horaire TEXT NOT NULL,
                        PRIMARY KEY (id_information_footer)                
                   ) ENGINE = '._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;'
            );

        return $sql;

    }

    //methode qui s'exécute sur le hook
    //nom de la méthode : hook + nom du hook
    public function hookDisplayFooter() {

        //test des horaires avec les valeurs
        $horaire = Db::getInstance()->getValue('SELECT horaire FROM '._DB_PREFIX_.'information_footer WHERE id_information_footer = 7');

            //le context est un registre qui stock les informations essentielles et qui est disponible sur toutes les pages
            $this->context->smarty->assign(array(
                'texte_horaire' => $horaire,
            ));

        //chemin d'un fichier tpl d'un module pour un hook : views/templates/hook/fichier.tpl
        return $this->display(__FILE__, 'information_footer.tpl');

    }

    //méthode qui permet d'inclure des fichiers css/
    public function hookActionFrontControllerSetMedia() {

        $this->context->controller->registerStylesheet(
            'module-cmh_information_footer-style', //identifiant
            'modules/'.$this->name.'/views/assets/css/informationfooter.css' //chemin
        );

    }

}