<?php

//class Banniere_Bulles
class Cmh_Banniere_Bulles extends Module
{

    //création du constructeur
    public function __construct()
    {

        //nom technique
        $this->name = 'cmh_banniere_bulles';

        //nom public, s'affichera dans Presta
        $this->displayName = 'Module de bulles d\'informations ';

        //qu'est-ce que c'est comme module
        $this->tab = 'front_office_features';

        //la version du module
        $this->version = '0.1.2';

        //auteur
        $this->author = 'Cassandra Muller-Heyob';

        //description
        $this->description = 'Ce module permet de pouvoir crée une bannière et d\'illustrées la page d\'accueil.';

        //activation de bootstrap
        $this->bootstrap = 'true';

        //appel du constructeur de la classe parent
        parent::__construct();
    }

    //permet d'installer un Hook + Db
    public function install()
    {

        if (!parent::install()
            //registerHook permet d'accrocher notre module à un Hook
            || !$this->registerHook('displayHome')
            || !$this->registerHook('actionFrontControllerSetMedia')
            || !$this->installDb()) {


            return false;
        } else {
            return true;
        }
    }

    //permet de désintaller
    public function uninstall()
    {

            //supprime la ligne dans ps_configuration en fonction du nom
            Configuration::deleteByName('CHAMP_IMAGE_BOUTIQUE');
            Configuration::deleteByName('CHAMP_IMAGE_ATELIERS');
            Configuration::deleteByName('CHAMP_IMAGE_KIT');

            // table banniere_bulles
            Db::getInstance()->execute('DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'banniere_bulles');

        return parent::uninstall();
    }

    //création de la page de configuration

    //function qui signale à Presta que le module est configurable
    //récupère les informations envoyées par le formulaire
    public function getContent()
    {

        //variable pour afficher les notifications
        $output = NULL;

        //permet de savoir si un formulaire est envoyée
        if (Tools::isSubmit('submit_cmhbannierebulles')) {

            $image_boutique = Tools::getValue('CHAMP_IMAGE_BOUTIQUE');

            $image_atelier = Tools::getValue('CHAMP_IMAGE_ATELIERS');

            $image_kit = Tools::getValue('CHAMP_IMAGE_KIT');

            //Tools::dieObject($image);

            //pour l'image
            if (!move_uploaded_file($_FILES['CHAMP_IMAGE_BOUTIQUE']['tmp_name'], dirname(__FILE__) . '/views/assets/img/' . $image_boutique)) {

                $output .= $this->displayError($this->l('Erreur lors du transfert du fichier'));
            } else {
                Configuration::updateValue('CHAMP_IMAGE_BOUTIQUE', $image_boutique);
                $output .= $this->displayConfirmation('Transfert réussi');
            }

            //pour l'image
            if (!move_uploaded_file($_FILES['CHAMP_IMAGE_ATELIERS']['tmp_name'], dirname(__FILE__) . '/views/assets/img/' . $image_atelier)) {

                $output .= $this->displayError($this->l('Erreur lors du transfert du fichier'));
            } else {
                Configuration::updateValue('CHAMP_IMAGE_ATELIERS', $image_atelier);
                $output .= $this->displayConfirmation('Transfert réussi');
            }

            //pour l'image
            if (!move_uploaded_file($_FILES['CHAMP_IMAGE_KIT']['tmp_name'], dirname(__FILE__) . '/views/assets/img/' . $image_kit)) {

                $output .= $this->displayError($this->l('Erreur lors du transfert du fichier'));
            } else {
                Configuration::updateValue('CHAMP_IMAGE_KIT', $image_kit);
                $output .= $this->displayConfirmation('Transfert réussi');
            }


        }

        return $output . $this->displayForm();
    }

    //methode qui permet de crée un formulaire via Helperform
    public function displayForm()
    {

        //tableau qui contient les informations du formulaire
        $form_configuration['0']['form'] = [

            'legend' => [
                'title' => 'Configuration du module de block d\'information',
            ],
            'input' => [
                // 3 file pour les images
                [
                    'type' => 'file', //fichier
                    'label' => $this->l('Image'),
                    'name' => 'CHAMP_IMAGE_BOUTIQUE',
                ],
                [
                    'type' => 'file', //fichier
                    'label' => $this->l('Image'),
                    'name' => 'CHAMP_IMAGE_ATELIERS',
                ],
                [
                    'type' => 'file', //fichier
                    'label' => $this->l('Image'),
                    'name' => 'CHAMP_IMAGE_KIT',
                ],
            ],
            //sauvegarde des données
            'submit' => [
                'title' => $this->l('Save'),
                'class' => 'btn btn-default pull-right'
            ]

        ];

            //création d'un formulaire
            $helper = new HelperForm();

            $helper->module = $this; //instance de notre module
            $helper->controller = $this->name; //nom technique
            $helper->token = Tools::getAdminTokenLite('AdminModules'); //clée de sécurité propre à notre session

            //genère le lien de l'action du formulaire, la page qui va traité les informations
            $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name;
            $helper->default_form_language = (int)Configuration::get('PS_LANG_DEFAULT');
            $helper->title = $this->displayName;

            //genère l'attribut name du bouton action
            $helper->submit_action = 'submit_cmhbannierebulles';

        return $helper->generateForm($form_configuration);

    }

    //installation d'une BDD
    public function installDb()
    {

            // class DB stock les informations de connexion bbd
            //getInstance => objet PDO
            // clé primaire doit TOUJOURS ETRE DE FORME id_+nomtable
            $sql = Db::getInstance()->execute('
                    CREATE TABLE IF NOT EXISTS ' . _DB_PREFIX_ . 'banniere_bulles (
                        id_block_information INT UNSIGNED NOT NULL AUTO_INCREMENT,
                        image_boutique TEXT NOT NULL,
                        image_atelier TEXT NOT NULL,
                        image_kit TEXT NOT NULL,
                        PRIMARY KEY (id_block_information)                
                   ) ENGINE = ' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;'
            );

        return $sql;
    }

    //methode qui s'exécute sur le hook
    //nom de la méthode : hook + nom du hook
    public function hookDisplayHome()
    {

            $image_boutique = Configuration::get('CHAMP_IMAGE_BOUTIQUE');
            $image_atelier = Configuration::get('CHAMP_IMAGE_ATELIERS');
            $image_kit = Configuration::get('CHAMP_IMAGE_KIT');


            // le context est un register qui stock les informations essentielles et qui est disponible sur toutes les pages
            $this->context->smarty->assign(array(
                //nom de la variable et valeur
                'image_boutique' => $image_boutique,
                'image_atelier' => $image_atelier,
                'image_kit' => $image_kit,

            ));

        //chemin d'un fichier tpl d'un module pour un hook : views/templates/hook/fichier.tpl
        return $this->display(__FILE__, 'banniere.tpl');

    }

    //méthode qui permet d'inclure des fichiers css
    public function hookActionFrontControllerSetMedia()
    {

        $this->context->controller->registerStylesheet(
            'module-cmh_banniere_bulles-style', //identifiant
            'modules/' . $this->name . '/views/assets/css/banniere_bulles.css' //chemin
        );

    }

}