{extends file="page.tpl"}

{block name="page_content"}
    <!-- Premier bloc d'information-->
    <div class="police">
        <h1>Mon Kit Créatif</h1>
    </div>

        <div id="container">
            <div class="row">
                <!-- Image du kit -->
                <div class="col-sm">
                    <img src="{$urls.base_url}/modules/cmh_mon_kit_creatif/views/assets/img/photo_illustration.jpg" alt="Responsive Image" class="img-fluid-classe"/>
                </div>
                <!-- Description -->
                <div class="col-sm">
                    <span><p>Vous n’avez pas d’idée pour votre prochain cadeau d’anniversaire, ou souhaitez-vous faire plaisir à un de vos proches ?</br>
                        </br>
                    Mon Kit Créatif est un kit de création à la main, avec des produits de qualité et qui fournit plusieurs thématiques destinés aux enfants de l’âge de 3 ans à l’âge de 10 ans.</br>
                        </br>
                    Nous sommes une entreprise dans une démarche de zéro déchet, donc tous nos produits sont recyclables à 100%. Chaque paquet est fait à la main, par nos soins.</br>
                        </br>
                    Mon kit créatif a un prix qui varie entre 15E et 25E. Pour connaître le prix, une fois votre sélection faite, cliquer sur le bouton envoyez. Vous recevrais alors un devis avec le prix indiquer. Une adresse sera alors disponible dans le devis pour y répondre, si oui ou non vous voulez confirmer votre commande.</br>
                        </br>
                    Le paquet est composée d’un seul choix possible pour chaque catégorie.</br>
                        </br>
                    Le paquet créatif est constitué de :</br>
                       *	Une notice des objets que vous auriez besoin</br>
                       *	Le choix de l’emballage de votre paquet</br>
                       *	Un set de 20 feuilles pour faire de l’origami, et son patron</br>
                       *	Un kit de Mosaïque sur les animaux de la jungle</br>
                       *	Un kit pour crée son animal en feutrine</br>
                       *	Une pochette de stickers</p></span>
                </div>
            </div>
        </div>

        <!-- Container article -->
            <div class="container-article">
                    {if isset($titre)}
                <div class="row">
                        <form type="POST">
                            {foreach from=$titre item="category"}
                                <!-- Titre -->
                                <div class="titre">
                                    <h2>{$category.titre}</h2>
                                </div>
                                    <!-- Intérieur du kit -->
                                    <div class="colcategory justify-content-center">
                                    {assign var=sub_categoryall value=SousCategories::getSousCategoriesByidCategories($category.id_mon_kit_creatif)}
                                        {if isset($sub_categoryall)}
                                            {foreach from=$sub_categoryall item="sub_category"}
                                               <div class="col-3 justify-content-center">
                                                   <img src="{$urls.base_url}/modules/cmh_mon_kit_creatif/views/assets/img/{$sub_category.image}"/>
                                                   <label name="{$sub_category.titre_category}">{$sub_category.titre_category}</label></br>
                                                   <input type="radio" id="{$sub_category.titre_category}" name="{$category.titre}" value="{$sub_category.titre_category}" required/>
                                               </div>
                                            {/foreach}
                                        {/if}
                                    </div>
                            {/foreach}
                            <!-- Bouton d'envoie -->
                            <button class="btn-envoyer btn-info" name="bouton_kit">Envoyez</button>
                        </form>
                    {/if}
                </div>
            </div>



{/block}



