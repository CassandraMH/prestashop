<?php

//nom du fichier controller admin : AdminNomFichierController
require_once(_PS_ROOT_DIR_ . '/modules/cmh_mon_kit_creatif/classes/monkitcreatif.php');

//nom de la class identique au nom du fichier
class AdminKitCategoriesController extends ModuleAdminController {

    public function __construct(){

        $this->table = 'mon_kit_creatif'; //nom de la table sans prefix
        $this->className = 'MonKitCreatif'; //nom de ma classe du module

        parent::__construct(); //appel du constructeur parent pour pouvoir gérer les traductions

        $this->fields_list = array(

            'id_mon_kit_creatif' => [
                'title' => $this->l('ID') // nom de ma colonne
            ],
            'titre' => [
                'title' => $this->l('Text'), // nom de ma colonne
                'class' => 'rte',
                'autoload_rte' => 'true'
            ],
        );

        $this->bootstrap = true;

        //ajout des boutons d'action
        $this->addRowAction('edit');
        $this->addRowAction('delete');

    }

    //methode pour le formulaire d'edition
    public function renderForm(){

        $this->fields_form = array(
            'legend' => [
                'title' => 'Ajout / modification de mon premier module'
            ],
            'input' => [
                array(
                    'type' => 'textarea',
                    'label' => $this->module->l('Title'),
                    'name' => 'titre',
                    'required' => true
                ),
            ],
            'submit' => [
                'title' => $this->l('Save')
            ],
        );

        return parent::renderForm();
    }


}