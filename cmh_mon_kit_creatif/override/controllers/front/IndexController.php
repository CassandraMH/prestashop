<?php


//nom fichier identique que le nom du fichier qu'on override
class IndexController extends IndexControllerCore
{

    public function initContent()
    {
        parent::initContent();
        $this->context->smarty->assign([
            'HOOK_HOME' => Hook::exec('displayHome'),
            'accueil' => 'Hello world'
        ]);
        $this->setTemplate('index');
    }

}