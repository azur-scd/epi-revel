<script type="text/javascript" async
  src="//epi-revel.univ-cotedazur.fr/application/libraries/Mathjax/MathJax.js?config=TeX-MML-AM_CHTML" async>
</script>

<?php
$themeColor = get_color($collection); // la couleur utilisée pour les éléments du thème de la page 
$collectionTitle = metadata('collection', array('Dublin Core', 'Title')); // titre de la collection
$collectionId = metadata('collection', 'id'); // id de la collection
$langage = get_html_lang(); //récupere la langue courrante (fr ou en_US)
$collectionTable = get_collection_table();
$DescendantTree = get_descendant_tree($collectionId); // l'arbre de sous-collections
$AncestorTree = get_ancestor_tree($collectionId); //l'arbre des collections parentes
$AncestorId = null;
$rootCollections = get_root_collection(); // l'arbre de la collection racine
$AncestorTitle = '[Untitled]'; // nom de la collection parente
$collectionOrder = get_collection_order();
$subCollection = $collectionOrder->fetchOrderedCollections($collectionId); //affichage des collections dans le bon ordre (menu)
$superSubCollection = $collectionOrder->fetchOrderedCollections($collectionId); //affichage des sous-collections dans le bon ordre (sommaire)
$typeCollection = metadata('collection', array('Dublin Core', 'Type'), array('all' => true));
$type1 = 'vide';
$type2 = 'vide';
$nomListe ="Numéros";
foreach ($typeCollection as $type){
    if ($type == 'thematique' || $type == 'thématique'){
        $type1 = 'thematique';
        $nomListe = "Rubriques";
    }
    if($type == 'numero' || $type =='numéro'){
        $type1 = 'numero';
        $nomListe ="Numéros";
    }
    if($type == 'colloque' || $type =='Colloque'){
        $type2 = 'colloque';
    }
    if($type == 'cahier' || $type =='Cahier' || $type =='revue' || $type =='Revue'){
        $type2 = 'autre';
    }
}
//sélection de la sous-collection à afficher pour le dernier numéro
$mostRecentId = get_most_recent_id($collectionId);
$imgCount = 0;
 if($mostRecentId != $collectionId){
$getMostRecent = get_record_by_id('collection', $mostRecentId);
}
$fetchMostRecent = $collectionOrder->fetchOrderedCollections($mostRecentId);
//liste des contenus les plus récents
foreach ($rootCollections as $rootCollection) {
    if(in_array($rootCollection, $AncestorTree)){
        $AncestorTitle = $rootCollection['name'];
        $AncestorId = $rootCollection['id'];
        $CollectionTree = get_descendant_tree($AncestorId);
        $subCollection = $collectionOrder->fetchOrderedCollections($AncestorId);
        set_current_record('collection', get_record_by_id('collection', $AncestorId));
        $themeColor = get_color('collection');
        set_current_record('collection', get_record_by_id('collection', $collectionId));
}}

$loopTree = $DescendantTree;
if($AncestorTree){
    $loopTree = $CollectionTree;
}
?>

<?php echo head(array('title'=> $collectionTitle, 'bodyclass' => 'collections show')); ?>

<div id="header-title">
<?php if ($AncestorTree) : 
    echo ancestor_header_image($AncestorId);
    else : echo theme_header_image();
    endif;?>
</div>

<?php   if (!$AncestorTree) :?>
    <!--  Si la collection n'a pas de parent : on affiche le sujet. Il s'agit de la page d'accueil de la collection donc on affiche sa description, la dernière publication et son contenu-->
    <div id="header-title">
        <h1 style="background:<?php echo $themeColor ?>;">
        <?php echo link_to_collection($collectionTitle, array('style' => 'color :white', 'onmouseover' => 'this.style.color="lightgrey"', 'onmouseout' => 'this.style.color="white"'), 'show', $collectionTable->find($collectionId)); ?>
        <?php if (metadata('collection', array('Dublin Core', 'Subject'))):?><?php echo __(' | '); ?>
            <?php echo metadata('collection', array('Dublin Core', 'Subject')); ?>
        <?php endif; ?>
        </h1>
    </div>
    
    <div id="collection-items">
        <div class="sommaire">
            <div class="sommaire_item">
                <div>
                    <p><?php echo metadata('collection', array('Dublin Core', 'Description')); ?></p>
                </div>
            </div>
        </div>
    </div>
<!-- affichage des pages d'auteurs / publication / mots clés -->
<div class="categories">
    <?php foreach ($subCollection as $menuCollection) :
        if(metadata(get_record_by_id('collection', $menuCollection['id']), array('Dublin Core','Type')) == ('menu')) : ?>
            <?php if ($AncestorTree) :
                foreach (loop('items', get_records('Item', array('collection' => $menuCollection['id']))) as $menuItem):
                    if(metadata(get_collection_for_item($menuItem), 'id') == $menuCollection['id']):      
                        $itemTitle = metadata($menuItem, array('Dublin Core', 'Title')); ?>
                        <a href="<?php echo WEB_ROOT;?><?php echo record_url($menuItem);?>" class="btn btn-info-big" style="background-color:<?php echo $themeColor ;?>"><?php echo $itemTitle;?></a>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php else: ?>
                <?php foreach (loop('items') as $item):
                    if(metadata(get_collection_for_item($item), 'id') == $menuCollection['id']):         
                        $itemTitle = metadata('item', array('Dublin Core', 'Title')); ?>
                        <a href="<?php echo WEB_ROOT;?><?php echo record_url($item);?>" class="btn btn-info-big" style="background-color:<?php echo $themeColor ;?>"><?php echo $itemTitle;?></a>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        <?php endif;?>
    <?php endforeach;?>
</div>
<!-- affichage des vignettes de numéros / publications -->
<div id="collection-items-grey">
    <div class="sommaire">
        <?php foreach ($loopTree as $co) :
            if($co['children']): ?>
                <h3><?php echo $nomListe; ?></h3>
                <?php echo $this->collectionTreeListChild($co['children'], $co['id']);?>
            <?php endif;?>
        <?php endforeach;?>
    </div>
</div>
<?php else: ?>
    <div id="header-title">
        <h1 style="background:<?php echo $themeColor ?>;">
        <?php echo link_to_collection($AncestorTitle, array('style' => 'color :white', 'onmouseover' => 'this.style.color="lightgrey"', 'onmouseout' => 'this.style.color="white"'), 'show', $collectionTable->find($AncestorId)); ?>
        <?php echo __(' | '); ?><?php echo $collectionTitle; ?></h1>
    </div>

    <div id="collection-items">
        <div class="sommaire_header">
            <?php if(metadata('collection', array('Dublin Core', 'Coverage'))): ?>
                <img src="<?php echo metadata('collection', array('Dublin Core', 'Coverage'));?>" alt="cover">
            <?php endif;?>
            <div class="collection_description">
                <h1><?php echo $collectionTitle; ?></h1>
                <p><?php if(metadata('collection', array('Dublin Core', 'Contributor'))){ echo 'Sous la direction de ' ;} ?>
                <?php echo metadata('collection', array('Dublin Core', 'Contributor')); ?>
                </p>
                <?php if (metadata('collection', array('Dublin Core', 'Description'))): ?>
                    <p><?php echo metadata('collection', array('Dublin Core', 'Description')); ?></p>
                <?php endif; ?>
                <p class="sommaire_header_date"> 
                <?php echo metadata('collection', array('Dublin Core', 'Date')); ?> 
                </p>
            </div>
        </div>
    </div>
<?php endif;?>
<?php if ($type1 == 'thematique') :?>
    <!-- Type de collection 'thematique' : on affiche les derniers contenus  -->
    <div id="collection-items">
        <div class="sommaire_titre">
            <?php if (isset($recent_col)): ?>
                <h1><?php echo __("Dernières publications en ligne"); ?></h1>
            <?php endif;?>
        </div>
    </div>
    <div id="collection-items-grey">
        <div class="sommaire">
            <div class="sommaire_item">
                <!-- affichage des items (avec ou sans sous-collections) -->
                <?php if (metadata('collection', 'total_items') > 0): ?>
                    <?php $items = get_records('Item', array('sort_field' => 'added', 'sort_dir' => 'd', 'collection' => $collectionId), 15); ?>
                    <?php foreach ($items as $item): ?>
                        <?php set_current_record('item', $item);?>
                        <?php $getCollection = get_collection_for_item($item);?>
                        <?php $auteurStr = get_auteur_str($item);?>
                        <?php $typesItem = metadata($item, array('Dublin Core', 'Type'), array('all' => true)); ?>
                        <?php if(metadata($getCollection, array('Dublin Core','Type')) != ('menu')): ?>    
                            <?php if ($imgCount == 0 ) : ?>  
                                <div class="item entry">
                                    <?php echo files_for_item(array('imageSize' => 'fullsize')); ?>
                                </div>   
                                <?php $imgCount = 1 ;?>
                            <?php endif;?>
                            <?php $itemTitle = metadata('item', array('Dublin Core', 'Title')); ?>
                            <div class="item entry">
                                <p><span class="sommaire_auteur"><?php echo $auteurStr;?></span>
                                <span style="font-style: italic; color: #717171;"><?php if(nombre_auteurs($item) > 4){echo ' et al.';} ?></span>
                                <?php if(metadata('item', array('Dublin Core','Creator'))){ echo ' : ' ;} 
                                echo link_to_item($itemTitle, array('class'=>'permalink', 'onmouseover' => 'this.style.color="' . $themeColor . '"', 'onmouseout' => 'this.style.color="#444"')); 
                                echo item_type($typesItem)?></p>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p><?php echo __("Il n'y a actuellement aucun document dans cette collection."); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php else : ?>
    <!-- Type de collection 'numero' : on affiche le dernier numero paru  -->
    <?php $currentId = $mostRecentId; ?>
    <?php $currentCollection = $fetchMostRecent;?>
    <div id="collection-items">
        <?php if($type1 == 'numero') : ?>
            <div class="sommaire_titre">
                <?php if (isset($recent_col)): ?>                
                        <?php if ($type2 == 'colloque') :?>
                            <h1><?php echo __("Dernière édition en ligne"); ?></h1>
                        <?php elseif ($type2 == 'autre'): ?>
                            <h1><?php echo __("Dernier numéro en ligne"); ?></h1>
                        <?php endif;?>
                <?php endif;?>
            </div>
            <div class="sommaire_header">
                <?php if($currentId != $collectionId) : ?>
                    <p style="padding-left: 0.5em;">
                    <?php if(metadata($getMostRecent, array('Dublin Core', 'Coverage'))): ?>
                        <img src="<?php echo metadata($getMostRecent, array('Dublin Core', 'Coverage'));?>" alt="cover">
                    <?php endif;?>
                    <div class="collection_description">
                    <h1><?php echo metadata($getMostRecent, array('Dublin Core', 'Title')); ?></h1>
                        <?php if(metadata($getMostRecent, array('Dublin Core', 'Contributor'))) :?>
                            <p><?php echo 'Sous la direction de ' . metadata($getMostRecent, array('Dublin Core', 'Contributor')); ?></p>
                        <?php endif;?>
                        <?php if (metadata($getMostRecent, array('Dublin Core', 'Description'))): ?>
                            <p><?php echo metadata($getMostRecent, array('Dublin Core', 'Description')); ?></p>
                        <?php endif; ?>
                        <p class="sommaire_header_date"><?php echo metadata($getMostRecent, array('Dublin Core', 'Date')); ?></p>
                    </div>
                <?php endif;?>
            </div>
        <?php elseif($type1 == 'vide') : ?>
            <?php $currentId = $collectionId; ?>
            <?php $currentCollection = $superSubCollection;?>
        <?php endif;?>
    </div>
    <div id="collection-items-grey">
        <div class="sommaire">
           <div class="sommaire_item">
            <!-- affichage des items sans sous-collection -->
                <?php set_current_record('collection', get_record_by_id('collection', $currentId)); ?>
                <?php if (metadata('collection', 'total_items') > 0): ?>
                    <?php foreach (loop('items') as $item): ?>
                        <?php if( metadata('item','Collection Id') == $currentId) : ?>  
                            <?php if ($imgCount == 0 ) : ?>  
                                <div class="item entry">
                                    <?php echo files_for_item(array('imageSize' => 'fullsize')); ?>
                                </div>      
                                <?php $imgCount = 1 ;?>
                            <?php endif;?>
                            <?php $itemTitle = metadata('item', array('Dublin Core', 'Title')); ?>
                            <?php $typesItem = metadata($item, array('Dublin Core', 'Type'), array('all' => true)); ?> 
                            <?php $auteurStr = get_auteur_str($item);?> 
                            <div class="item entry">
                                <p>
                                <span class="sommaire_auteur"><?php echo $auteurStr;?></span>
                                <span style="font-style: italic; color: #717171;"><?php if(nombre_auteurs($item) > 4){echo ' et al.';} ?></span>
                                <?php if(metadata('item', array('Dublin Core','Creator'))){ echo ' : ' ;} 
                                echo link_to_item($itemTitle, array('class'=>'permalink', 'onmouseover' => 'this.style.color="' . $themeColor . '"', 'onmouseout' => 'this.style.color="#444"'));
                                echo item_type($typesItem)?></p>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p><?php echo __("Il n'y a actuellement aucun document dans cette collection."); ?></p>
                <?php endif; ?>
            <!-- affichage des items avec sous-collection -->
                <?php foreach($currentCollection as $collec):?>
                    <?php if (metadata(get_record_by_id('collection', $collec['id']), array('Dublin Core','Type')) != 'menu') : ?>
                        <?php if (get_record_by_id('collection', $collec['id'])) : ?>
                            <h3 style="border-bottom-color:<?php echo $themeColor ?>;">
                            <span style="border-bottom-color:<?php echo $themeColor ?>;" class="item-name">
                            <?php echo (metadata(get_record_by_id('collection', $collec['id']), array('Dublin Core','Title')));?></span>
                            </h3>        
                        <?php endif;?>
                        <?php if (metadata('collection', 'total_items') > 0): ?>
                            <?php foreach (loop('items') as $item): ?>
                                <?php if(metadata('item','Collection Id') == $collec['id']): ?>   
                                    <?php $typesItem = metadata($item, array('Dublin Core', 'Type'), array('all' => true)); ?>    
                                    <?php $auteursItem = metadata($item, array('Dublin Core', 'Creator'), array('all' => true)); ?>
                                    <?php $auteurStr = get_auteur_str($item);?> 
                                    <?php if ($imgCount == 0 ) : ?>  
                                        <div class="item entry">
                                            <?php echo files_for_item(array('imageSize' => 'fullsize')); ?>
                                        </div>    
                                        <?php $imgCount = 1 ;?>
                                    <?php endif;?>
                                    <?php $itemTitle = metadata('item', array('Dublin Core', 'Title')); ?>
                                    <div class="item entry">
                                        <p><span class="sommaire_auteur"><?php echo $auteurStr;?></span>
                                        <span style="font-style: italic; color: #717171;"><?php if(nombre_auteurs($item) > 4){echo ' et al.';} ?></span>
                                        <?php if(metadata('item', array('Dublin Core','Creator'))){ echo ' : ' ;} 
                                        echo link_to_item($itemTitle, array('class'=>'permalink', 'onmouseover' => 'this.style.color="' . $themeColor . '"', 'onmouseout' => 'this.style.color="#444"'));
                                        echo item_type($typesItem)?></p>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>    
                        <!-- affichage des sous-sous-collections -->
                            <?php $SousCollectionTree = get_descendant_tree($collec['id']); ?>
                            <?php $sousCols = $collectionOrder->fetchOrderedCollections($collec['id']); ?>
                            <?php foreach($sousCols as $sousCol) : ?>
                                <?php $sousColTitle = metadata(get_record_by_id('collection', $sousCol['id']), array('Dublin Core','Title')); ?>
                                <div class="item entry">
                                    <h3 style="margin-left:25px; border-bottom-color:<?php echo $themeColor ?>;">
                                    <span style="border-bottom-color:<?php echo $themeColor ?>;" class="item-name">
                                    <?php echo link_to_collection($sousColTitle, array('class'=>'permalink', 'onmouseover' => 'this.style.color="' . $themeColor . '"', 'onmouseout' => 'this.style.color="#444"'), 'show', $collectionTable->find($sousCol['id'])); ?>
                                    </h3>
                                    <!-- affichage des items des sous-sous-collections, à commenter si on vent encore les supprimer-->
                                    <?php foreach (loop('items') as $item): ?>
                                        <?php if(metadata('item','Collection Id') == $sousCol['id']): ?> 
                                            <?php $typesItem = metadata($item, array('Dublin Core', 'Type'), array('all' => true)); ?>    
                                            <?php $auteursItem = metadata($item, array('Dublin Core', 'Creator'), array('all' => true)); ?>
                                            <?php $auteurStr = get_auteur_str($item);?> 
                                            <?php $itemTitle = metadata('item', array('Dublin Core', 'Title')); ?>
                                            <div class="item entry">
                                                <p style='margin-left: 25px;'>
                                                <span class="sommaire_auteur"><?php echo $auteurStr;?></span>
                                                <span style="font-style: italic; color: #717171;"><?php if(nombre_auteurs($item) > 4){echo ' et al.';} ?></span>
                                                <?php if(metadata('item', array('Dublin Core','Creator'))){ echo ' : ' ;} 
                                                echo link_to_item($itemTitle, array('class'=>'permalink', 'onmouseover' => 'this.style.color="' . $themeColor . '"', 'onmouseout' => 'this.style.color="#444"'));
                                                echo item_type($typesItem)?></p>
                                            </div>
                                        <?php endif?>
                                    <?php endforeach;?>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p><?php echo __("Il n'y a actuellement aucun document dans cette collection."); ?></p>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php endforeach;?>
                <?php set_current_record('collection', get_record_by_id('collection', $collectionId)); ?>
            </div>
        </div>
    </div>
<?php endif; ?>
<!-- Fin des types de collection -->
<?php echo foot(); ?>