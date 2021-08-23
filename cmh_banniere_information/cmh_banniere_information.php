<?php

//class Banniere_Information
class Cmh_Banniere_Information extends Module
{

    //création du constructeur
    public function __construct()
    {

        //nom technique
        $this->name = 'cmh_banniere_information';

        //nom public, s'affichera dans Presta
        $this->displayName = 'Module de banniere information';

        //qu'est-ce que c'est comme module
        $this->tab = 'front_office_features';

        //la version du module
        $this->version = '0.1.2';

        //auteur
        $this->author = 'Cassandra Muller-Heyob';

        //description
        $this->description = 'Ce module permet d\'avoir une banniere contenant des informations sur l\'entreprise.';

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
            || !$this->installDb()){


            return false;
        } else {
            return true;
        }

    }

    //permet de désintaller
    public function uninstall()
    {

            //supprime la ligne dans ps_configuration en fonction du nom
            Configuration::deleteByName('CHAMP_IMAGE_LIVRAISON');
            Configuration::deleteByName('CHAMP_IMAGE_PAIEMENT');
            Configuration::deleteByName('CHAMP_IMAGE_FIDELITE');
            Configuration::deleteByName('CHAMP_IMAGE_GARANTIE');

            // table banniere_information
            Db::getInstance()->execute('DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'banniere_information');

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
        if (Tools::isSubmit('submit_cmhbanniereinformation')) {


            //récupère $_FILES['name']
            $image_livraison = Tools::getValue('CHAMP_IMAGE_LIVRAISON');
            $image_paiement = Tools::getValue('CHAMP_IMAGE_PAIEMENT');
            $image_fidelite = Tools::getValue('CHAMP_IMAGE_FIDELITE');
            $image_garantie = Tools::getValue('CHAMP_IMAGE_GARANTIE');



            //pour l'image de livraison
            if (!move_uploaded_file($_FILES['CHAMP_IMAGE_LIVRAISON']['tmp_name'], dirname(__FILE__) . '/views/assets/img/' . $image_livraison)) {

                    $output .= $this->displayError($this->l('Erreur lors du transfert du fichier'));

            } else {
                Configuration::updateValue('CHAMP_IMAGE_LIVRAISON', $image_livraison);
                    $output .= $this->displayConfirmation('Transfert réussi');
            }

            //pour l'image de paiement
            if (!move_uploaded_file($_FILES['CHAMP_IMAGE_PAIEMENT']['tmp_name'], dirname(__FILE__) . '/views/assets/img/' . $image_paiement)) {

                     $output .= $this->displayError($this->l('Erreur lors du transfert du fichier'));

            } else {
                Configuration::updateValue('CHAMP_IMAGE_PAIEMENT', $image_paiement);
                    $output .= $this->displayConfirmation('Transfert réussi');
            }

            //pour l'image de fidelité
            if (!move_uploaded_file($_FILES['CHAMP_IMAGE_FIDELITE']['tmp_name'], dirname(__FILE__) . '/views/assets/img/' . $image_fidelite)) {

                $output .= $this->displayError($this->l('Erreur lors du transfert du fichier'));

            } else {
                Configuration::updateValue('CHAMP_IMAGE_FIDELITE', $image_fidelite);
                    $output .= $this->displayConfirmation('Transfert réussi');
            }

            //pour l'image de garantie
            if (!move_uploaded_file($_FILES['CHAMP_IMAGE_GARANTIE']['tmp_name'], dirname(__FILE__) . '/views/assets/img/' . $image_garantie)) {

                $output .= $this->displayError($this->l('Erreur lors du transfert du fichier'));

            } else {
                Configuration::updateValue('CHAMP_IMAGE_GARANTIE', $image_garantie);
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
                'title' => 'Configuration de la banniere',
            ],
            'input' => [
                // 4 file pour les images
                [
                    'type' => 'file', //fichier
                    'label' => $this->l('Image'),
                    'name' => 'CHAMP_IMAGE_LIVRAISON',

                ],
                [
                    'type' => 'file', //fichier
                    'label' => $this->l('Image'),
                    'name' => 'CHAMP_IMAGE_PAIEMENT',

                ],
                [
                    'type' => 'file', //fichier
                    'label' => $this->l('Image'),
                    'name' => 'CHAMP_IMAGE_FIDELITE',

                ],
                [
                    'type' => 'file', //fichier
                    'label' => $this->l('Image'),
                    'name' => 'CHAMP_IMAGE_GARANTIE',

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
            $helper->submit_action = 'submit_cmhbanniereinformation';

        return $helper->generateForm($form_configuration);

    }

    //installation d'une BDD
    public function installDb()
    {

            // class DB stock les informations de connexion bbd
            //getInstance => objet PDO
            // clé primaire doit TOUJOURS ETRE DE FORME id_+nomtable
            $sql = Db::getInstance()->execute('
                    CREATE TABLE IF NOT EXISTS ' . _DB_PREFIX_ . 'banniere_information (
                        id_banniere_information INT UNSIGNED NOT NULL AUTO_INCREMENT,
                        image_livraison TEXT NOT NULL,
                        image_paiement TEXT NOT NULL,
                        image_fidelite TEXT NOT NULL,
                        image_garantie TEXT NOT NULL,
                        PRIMARY KEY (id_banniere_information)                
                   ) ENGINE = ' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;'
            );

        return $sql;
    }

    //methode qui s'exécute sur le hook
    //nom de la méthode : hook + nom du hook
    public function hookDisplayHome()
    {

        $image_livraison = Configuration::get('CHAMP_IMAGE_LIVRAISON');
        $image_paiement = Configuration::get('CHAMP_IMAGE_PAIEMENT');
        $image_fidelite = Configuration::get('CHAMP_IMAGE_FIDELITE');
        $image_garantie = Configuration::get('CHAMP_IMAGE_GARANTIE');


        // le context est un register qui stock les informations essentielles et qui est disponible sur toutes les pages
        $this->context->smarty->assign(array(
            //nom de la variable et valeur
            'image_livraison' => $image_livraison,
            'image_paiement' => $image_paiement,
            'image_fidelite' => $image_fidelite,
            'image_garantie' => $image_garantie,


        ));

        //chemin d'un fichier tpl d'un module pour un hook : views/templates/hook/fichier.tpl
        return $this->display(__FILE__, 'banniere_information.tpl');

    }


    //méthode qui permet d'inclure des fichiers css
    public function hookActionFrontControllerSetMedia()
    {

        $this->context->controller->registerStylesheet(
            'module-cmh_banniere_information-style', //identifiant
            'modules/' . $this->name . '/views/assets/css/banniere_information.css' //chemin
        );

    }

}

