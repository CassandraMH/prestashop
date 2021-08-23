<?php

//chemin vers mes class
require_once(_PS_ROOT_DIR_ . '/modules/cmh_mon_kit_creatif/classes/monkitcreatif.php');
require_once(_PS_ROOT_DIR_ . '/modules/cmh_mon_kit_creatif/classes/souscategories.php');

//nom de la class [NomTechniqueModule][NomFichier]ModuleFrontController
class Cmh_Mon_Kit_CreatifPageMonKitCreatifModuleFrontController extends ModuleFrontController{
    public function initContent(){

        parent::initContent();

        $categories = MonKitCreatif::getCategories();
        $sub_category = SousCategories::getSousCategories();

        $this->context->smarty->assign(array(
            'titre' => $categories,
            'titre_category' => $sub_category,
        ));

        //settemplater permet d'appeler le fichier tpl
        /* chemin du fichier tpl d'un frontcontroller : module:[nommodule]/views/templates/front/[nomfichier].tpl */
        $this->setTemplate('module:cmh_mon_kit_creatif/views/templates/front/pagekit.tpl');

    }

    //SendMail
    //methode qui recupère les informations envoyées depuis le controller : Ici pour les emails
    public function postProcess(){

        if(Tools::isSubmit('bouton_kit')){

            //A faire par email
            Mail::Send(
                (int)(Configuration::get('PS_LANG_DEFAULT')), // defaut language id
                'contact', // email template file to be use
                'Module Installation', // email subject
                array(
                    '{email}' => Configuration::get('PS_SHOP_EMAIL'), // sender email address
                    '{message}' => 'Nous vous remercions pour la commande et nous accusons bonne réception de votre commande pour notre Kit Créatif. Nous vous enverrons sous 24h le devis de votre Kit Créatif. Bonne journée, L\'équipe de Creativity.' // email content
                ),
                Configuration::get('PS_SHOP_EMAIL'), // receiver email address
                //{email}; //receiver name
                //{email}; //from email address
                //{shop_name}; //from name
            );


            //redirect : redirection en indiquant l'URL
            //getPageLink : méthode pour créer des liens vers les pages de notre template (accueil, catégorie, produit, connexion, tunnel de commande ....)
            Tools::redirect($this->context->link->getPageLink('Index'));
        }


    }

    //inclure fichier css
    public function setMedia(){

        parent::setMedia();

        $this->context->controller->addCSS(_MODULE_DIR_.$this->module->name.'/views/assets/css/front.css');


    }

}