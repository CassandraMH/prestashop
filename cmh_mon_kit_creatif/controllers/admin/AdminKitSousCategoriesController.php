<?php

//nom du fichier controller admin : AdminNomFichierController
require_once(_PS_ROOT_DIR_.'/modules/cmh_mon_kit_creatif/classes/souscategories.php');

//nom de la class identique au nom du fichier
class AdminKitSousCategoriesController extends ModuleAdminController
{

    public function __construct()
    {

        $this->table = 'mon_kit_creatif_subcategorie'; //nom de la table sans prefix
        $this->className = 'SousCategories'; //nom de ma classe du module

        parent::__construct(); //appel du constructeur parent pour pouvoir gérer les traductions

        $this->fields_list = array(

            'id_sub_categorie' => [
                'title' => $this->l('ID') // nom de ma colonne
            ],
            'id_category' => [
                'title' => $this->l('categorie'), // nom de ma colonne
                'callback' => 'callbackcategory',
            ],
            'titre_category' => [
                'title' => $this->l('Text') // nom de ma colonne
            ],
            'image' => [
                'title' => $this->l('image'), // nom de ma colonne
                'callback' => 'getImages'
            ],

        );

        $this->bootstrap = true;

        //ajout des boutons d'action
        $this->addRowAction('edit');
        $this->addRowAction('delete');
        $this->addRowAction('views');

    }

    //methode pour le formulaire d'edition
    public function renderForm()
    {

        $categories = SousCategories::getCategories();

        if($this->object->image){

            $image = '../modules/'.$this->module->name.'/views/assets/img/'.$this->object->image.'';
        }
        else{
            $image = null;
        }

        $this->fields_form = array(
            'legend' => [
                'title' => 'Ajout / modification de mes sous catégories et de ses images'
            ],
            'input' => [
                array(
                    'type' => 'select',
                    'label' => $this->module->l('Categorie'),
                    'name' => 'id_category',
                    'required' => true,
                    'options' => array(
                        'query' => $categories,
                        'id' => 'id_mon_kit_creatif',
                        'name' => 'titre'
                    )
                ),
                array(
                    'type' => 'textarea',
                    'label' => $this->module->l('Title'),
                    'name' => 'titre_category',
                    'required' => true
                ),
                array(
                    'type' => 'file',
                    'label' => $this->module->l('Image'),
                    'name' => 'image',
                    'image' => '<img src="'.$image.'"/>'
                ),
            ],
            'submit' => [
                'title' => $this->l('Save')
            ],
        );

        return parent::renderForm();
    }

    //callback
    public function callbackcategory($cellule, $row){

        $sql = Db::getInstance()->getValue('SELECT titre from  '._DB_PREFIX_.'mon_kit_creatif WHERE id_mon_kit_creatif = '.$cellule);
        return $sql;
    }


    //callback permet de modifier le return du champ. Toujours écrit nomducallbackdansfiledslist
    public function GetImages($cellule,$ligne){

        if($cellule){

            return '<img src="../modules/'.$this->module->name.'/views/assets/img/'.$cellule.'" />';

        }
        else {

            return '-';
        }
    }


    //AfterUpdate
    public function AfterUpdate($object){

        move_uploaded_file($_FILES['image']['tmp_name'], _PS_ROOT_DIR_.'/modules/'.$this->module->name.'/views/assets/img/'.$object->image);

    }

    //BeforeUpdate
    public function BeforeAdd($object){

        move_uploaded_file($_FILES['image']['tmp_name'], _PS_ROOT_DIR_.'/modules/'.$this->module->name.'/views/assets/img/'.$object->image);

    }


}