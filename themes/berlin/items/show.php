<?php 
$langage = get_html_lang(); //récupere la langue courrante (fr ou en_US)
$collectionId = metadata('item', 'Collection Id');// id de la collection
$ItemId = metadata('item', 'Id'); // id de l'item
$collectionTable = get_collection_table();
$AncestorTree = get_ancestor_tree($collectionId); //l'arbre des collections parentes
$AncestorId = get_ancestor_id($collectionId); //id de la collection parente
$AncestorTitle = get_ancestor_title($AncestorId); // nom de la collection parente
$rootCollections = get_root_collection(); //collection racine
$RootTitle = '[Untitled]'; // nom de la collection racine
$RootId = $collectionId; // id de la collection racine
$Author = ' '; // auteur de l'item
$collectionOrder = get_collection_order();
$subCollection = $collectionOrder->fetchOrderedCollections($collectionId);
foreach ($rootCollections as $rootCollection) {
    if (in_array($rootCollection, $AncestorTree)) {
        $RootTitle = $rootCollection['name'];
        $RootId = $rootCollection['id'];
        $subCollection = $collectionOrder->fetchOrderedCollections($RootId);
        $MenuTree = get_descendant_tree($RootId);
        if (metadata('item', array('Dublin Core', 'Creator'))) {
            $Author = getAuthor($item);
        }
    }
}
set_current_record('collection', get_record_by_id('collection', $RootId));
$themeColor = get_color('collection');
set_current_record('collection', get_record_by_id('collection', $collectionId));

echo head(array('title' => metadata('item', array('Dublin Core', 'Title')), 'bodyclass' => 'items show')); ?>

   <!-- Création des identifiants (doi et hal) -->
   <?php $doi_id = metadata($item, array('Dublin Core', 'Identifier'), array('all' => true));
    $doi_link = '#';
    foreach ($doi_id as $doi) {
        if (strpos($doi, 'DOI') !== false) {
            $doi_link = clear_br($doi);
            ?>
            <?php 
        }
    } ?>

    <?php $hal_id = metadata($item, array('Dublin Core', 'Identifier'), array('all' => true));
    $hal_link = '#';
    foreach ($hal_id as $hal) {
        if (strpos($hal, 'HAL') !== false) {
            $hal_link = clear_br($hal);
            ?>
            <?php 
        }
    } ?>

<!-- Affichage du fil d'ariane-->
<!-- le plugin de changement de langues est dans le fil d'ariane-->
<div id=ariane>
    <ul>
    <li><a href="<?php echo WEB_ROOT; ?>"><img alt="Epi-Revel@Nice" src="<?php echo WEB_ROOT; ?>/themes/berlin/images/epirevel_path.gif" width="64" height="10"></a></li>
        <li><?php echo ' | ';
            if ($RootTitle != '[Untitled]') {
                echo link_to_collection(short_string($RootTitle), array(), 'show', $collectionTable->find($RootId)) . ' | ';
            } ?>
        <?php if ($RootTitle != $AncestorTitle && $RootTitle != '[Untitled]') : ?>
            <?php echo link_to_collection(short_string($AncestorTitle), array(), 'show', $collectionTable->find($AncestorId)) . ' | ' . link_to_collection(short_string(metadata('item', 'Collection Name')), array(), 'show', $collectionTable->find($AncestorId)) . ' | ' ?>
        <?php else : echo link_to_collection(short_string(metadata('item', 'Collection Name')), array(), 'show', $collectionTable->find($collectionId)) . ' | ' ?>
        <?php endif ?>
        <?php echo link_to_item(short_string(metadata('item', 'display_title'))); ?>
        </li>
    <li style="float: right; "><?php echo $this->localeSwitcher(); ?></li>
    </ul>
</div>

<div style="border-bottom:solid #2d8463 0.2em;"></div>

<div id="search-container" role="search">
    <?php echo search_form(array('show_advanced' => true)); ?>
</div>

<!-- Affichage de l'image de haut de page-->
<div id="header-title">
<?php if ($AncestorTree) :
    echo ancestor_header_image($RootId);
else : echo theme_header_image();
endif; ?>
</div>

<div id="main_nav">
    <div id="navEntries">
    <?php if ($AncestorTree) : ?>
        <?php foreach ($subCollection as $menuCollection) : ?>
            <?php if (metadata(get_record_by_id('collection', $menuCollection['id']), array('Dublin Core', 'Type')) == ('menu')) : ?>
                <div class="main_nav_h2_contener">
                <h2><?php echo __(metadata(get_record_by_id('collection', $menuCollection['id']), array('Dublin Core', 'Title'))); ?></h2>
                </div>
                <ul>
                <?php foreach (loop('items', get_records('Item', array('collection' => $menuCollection['id']))) as $subitems_menu) : ?>
                    <?php if (metadata($subitems_menu, 'Collection Name') == (metadata(get_record_by_id('collection', $menuCollection['id']), array('Dublin Core', 'Title')))) : ?>          
                    <?php $itemTitle = metadata($subitems_menu, 'display_title'); ?>
                    <li><?php echo link_to_item($itemTitle, array('class' => 'permalink')); ?></li>
                    <?php endif; ?>
                <?php endforeach; ?>
                <?php set_current_record('item', get_record_by_id('item', $ItemId)); ?>
                </ul>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endif; ?>

        <?php if ($RootId != $collectionId) : ?>
        
        <?php
        foreach ($MenuTree as $co) {
            if ($co['children']) { ?>
                <div class="main_nav_h2_contener">
                <h2><?php echo __($co['name']); ?></h2>
                </div>
                   <?php echo $this->collectionTreeListChild($co['children'], $co['id']);
                }
            } ?>
        <?php endif; ?>
<!-- Affichage du doi et de l'id hal-->
        <?php if ($doi_link != '#' || $hal_link != '#') : ?>
        <div class="main_nav_h2_contener">
        <h2><?php echo __('Identifiants'); ?></h2>
        </div>
        <ul>
        <?php if ($doi_link != '#') : ?>
        <li style='list-style:none;'><a href='http://dx.doi.org/<?php echo clear_doi($doi_link); ?>'><?php echo $doi_link; ?></a></li>
        <?php endif; ?>
        <?php if ($hal_link != '#') : ?>
        <li style='list-style:none;'><?php echo $hal_link; ?></li>
        <?php endif; ?>
        </ul>
        <?php endif; ?>

    </div>       
</div>

<!-- Si le 'type' de l'item n'est pas 'rubrique', on affiche la page normalement -->
<?php if (metadata(get_current_record('collection', get_record_by_id('collection', $AncestorId)), array('Dublin Core', 'Type')) != 'menu') : ?>

<div id="primary">
    <div id="header-title">
        <h1 style="background:<?php echo $themeColor ?>;">
        <?php if ($RootTitle != '[Untitled]') {
            echo link_to_collection($RootTitle, array('style' => 'color :white', 'onmouseover' => 'this.style.color="lightgrey"', 'onmouseout' => 'this.style.color="white"'), 'show', $collectionTable->find($RootId)) . ' | ';
        } ?>
        <?php if ($RootTitle != $AncestorTitle && $RootTitle != '[Untitled]') :
            echo link_to_collection($AncestorTitle, array('style' => 'color :white', 'onmouseover' => 'this.style.color="lightgrey"', 'onmouseout' => 'this.style.color="white"'), 'show', $collectionTable->find($AncestorId)) . ' | ' ?>  
        <?php endif; ?>
        <?php echo link_to_collection_for_item(null, array('style' => 'color :white', 'onmouseover' => 'this.style.color="lightgrey"', 'onmouseout' => 'this.style.color="white"')); ?>
        </h1>
    </div>
    <p class="title_auteur">
    <?php if (metadata('item', array('Dublin Core', 'Creator'))) {
        echo $Author;
    } ?>
    </p>
    <p class="title">
    <?php echo metadata('item', array('Dublin Core', 'Title')); ?>
    </p>
    <!-- boutons d'acces aux documents -->
    <?php if (buttonPdf($item) != null) : ?>
        <a class="PDF" href="<?php echo buttonPdf($item); ?>">Lire ce document</a>
    <?php endif; ?>

    <!-- bouton pour cycnos -->
    <?php if (buttonCycnos($item) != null) : ?>
        <a class="PDF" href="<?php echo buttonCycnos($item); ?>">Lire ce document</a>
    <?php endif; ?>

    <?php if (buttonVideo($item) != null) : ?>
        <a class="PDF" href="<?php echo buttonVideo($item); ?>">Voir cette vidéo</a>
    <?php endif; ?>
   
    <?php if (buttonSource($item) != null) : ?>
        <a class="PDF" href="<?php echo buttonSource($item); ?>">Page de ce document</a>
    <?php endif; ?>
    
<!-- Items metadata -->
    <div id="item-citation" style='padding: 5px;'></div>
    <div id="item-citation">
        <?php $soustitre= metadata('Item', array('Dublin Core', 'Title'), array('all' => true));
        if(sizeof($soustitre) > 1) : ?>
            <div class="element-text">
                <span class="sommaire_auteur">
                    <?php if ($langage == 'fr') {
                        echo 'Titre alternatif : ';
                    }?>
                </span>
            </div>
            <div class="element-text" style='margin-bottom: 1em;'>
                <?php echo $soustitre[1] ; ?>
            </div>
        <?php endif;?>
        <div class="element-text">
            <span class="sommaire_auteur">
                <?php if ($langage == 'fr') {
                    echo 'Résumé : ';
                } else {
                    echo 'Abstract : ';
                } ?>
            </span>
        </div>
        <?php $descriptons = metadata('Item', array('Dublin Core', 'Description'), array('all' => true)); ?>
        <?php foreach($descriptons as $description) : ?>
            <div class="element-text" style='margin-bottom: 1em;'>
                <?php echo $description ; ?>
            </div>
        <?php endforeach ;?>
        <div class="element-text">
            <?php if (returnTag($item) != null) : ?> 
                <span class="sommaire_auteur">
                    <?php if ($langage == 'fr') {
                        echo 'Mots-clés : ';
                    } else {
                        echo 'Keywords : ';
                    } ?>
                </span> 
                <?php echo returnTag($item); ?>
            <?php endif; ?>
            <?php if (returnTag($item) == null) {
                echo __('');
            } ?>
        </div>

        <?php if ($doi_link != '#') : ?>
            <div id="item-citation">
                <div class="element-text">
                <span class="sommaire_auteur"><?php echo 'DOI : ' ?></span> 
                <a href='http://dx.doi.org/<?php echo clear_doi($doi_link); ?>' style='color: #333333;text-decoration: none;'><?php echo str_replace('DOI: ', '', $doi_link); ?></a>
                </div>
            </div>
        <?php endif; ?>       

        <?php if ($hal_link != '#') : ?>
            <div id="item-citation">
                <div class="element-text">
                <span class="sommaire_auteur"><?php echo 'HAL : ' ?></span> 
                <?php echo $hal_link; ?>
            </div>
        </div>
        <?php endif; ?>

<!-- les champs inutiles sont cachés par le css avec dublin-core-title et autres; à modifier si besoin -->

     <!-- The following prints a list of all tags associated with the item -->
        <?php if (metadata('item', 'has tags')) : ?>
            <div id="item-tags" class="element">
                <div class="element-text"><span class="sommaire_auteur"><?php echo __('Mots-clés : ') ?></span> <?php echo tag_string('item'); ?></div>
            </div>
        <?php endif; ?>
        <?php if (metadata('item', array('Dublin Core', 'Date'))) : ?>
                <div class="element-text"><span class="sommaire_auteur"><?php if ($langage == 'fr') {
                                                                            echo 'Date de publication : ';
                                                                        } else {
                                                                            echo 'Published : ';
                                                                        } ?></span> <?php echo metadata('item', array('Dublin Core', 'Date')); ?></div>
        <?php endif; ?>
        <div id="item-tags" class="element">
            <?php foreach (metadata('item', array('Dublin Core', 'Type'), array('all' => true)) as $typeItem) : ?> 
                <?php if (strpos($typeItem, '/') == false) : ?>
                    <div class="element-text"><span class="sommaire_auteur"><?php if ($langage == 'fr') {
                                                                                echo 'Type de document : ';
                                                                            } else {
                                                                                echo 'Document Type : ';
                                                                            } ?></span> <?php if ($langage == 'fr') {
                                                                        echo (type_traduction($typeItem));
                                                                    } else {
                                                                        echo ($typeItem);
                                                                    } ?> 
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
    
    <!-- The following prints a citation for this item. -->
    <div id="item-citation" class="element">
        <h3><?php echo __('Citation'); ?></h3>
        <div class="element-text">
        <?php if (strlen($Author) >= 2) echo $Author . ', ' . '«' ?>
        <?php echo metadata('item', array('Dublin Core', 'Title'));
        if ($RootTitle != '[Untitled]') echo '&nbsp;», ' . "<i>" . $RootTitle . "</i>";
        /* if ($RootTitle != $AncestorTitle && $RootTitle != '[Untitled]' && ($AncestorTitle == "Textes en intégralité" || $AncestorTitle == "Numéros")) echo ', ' . metadata('item', 'Collection Name');
        if ($RootTitle != $AncestorTitle && $RootTitle != '[Untitled]' && $AncestorTitle != "Textes en intégralité" && $AncestorTitle != "Numéros") echo ', ' . $AncestorTitle; */
        if (metadata('item', array('Dublin Core', 'Date'))) echo ', ' . (metadata('item', array('Dublin Core', 'Date')));
        if (buttonSource($item) != null) echo ". URL : " . "<a href=" . buttonSource($item) . ">" . buttonSource($item) . "</a>";
        if (buttonSource($item) == null) echo ". URL : " . "<a href=" . "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . ">" . "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . "</a>";
        if ($doi_link != '#') echo ", " . $doi_link; ?>
        </div>
    </div>

       <?php fire_plugin_hook('public_items_show', array('view' => $this, 'item' => $item)); ?>
       
</div> <!-- End of Primary. -->

<?php elseif ((metadata('item', array('Dublin Core', 'Type')) == "Blog") || (metadata('item', array('Dublin Core', 'Type')) == "blog")) : ?>

<?php $feed = metadata('item', array('Dublin Core', 'Source')); ?>

<div id="primary">
    <div id="header-title">
        <h1 style="background:<?php echo $themeColor ?>;">
        <?php if ($RootTitle != '[Untitled]') {
            echo link_to_collection($RootTitle, array('style' => 'color :white', 'onmouseover' => 'this.style.color="lightgrey"', 'onmouseout' => 'this.style.color="white"'), 'show', $collectionTable->find($RootId)) . ' | ';
        } ?>
        <?php if ($RootTitle != $AncestorTitle && $RootTitle != '[Untitled]') : ?>
        <?php echo $AncestorTitle . ' | ' ?>  <?php endif ?><?php echo link_to_collection_for_item(null, array('style' => 'color :white', 'onmouseover' => 'this.style.color="lightgrey"', 'onmouseout' => 'this.style.color="white"'), 'show', $collectionTable->find($RootId)); ?>
        </h1>
    </div>
    <p class="title_auteur">
    <?php if (metadata('item', array('Dublin Core', 'Creator'))) {
        echo $Author;
    } ?>
    </p>
    <p class="title">
    <?php echo metadata('item', array('Dublin Core', 'Title')); ?>
    <?php if ($feed != null) : ?>
    <div class="element-text">
         <a href=<?php echo clear_feed($feed); ?>><?php echo clear_feed($feed); ?> </a>
         </div>
        <?php endif; ?>
    </p>

    <div id="collection-items">
        <div class="sommaire" style="border:white;">
            <div class="sommaire_item" style="background:white;">
            <p>
            <?php echo metadata('Item', array('Dublin Core', 'Description')); ?>
            <?php if (metadata('item', 'has files')) {
                echo files_for_item();
            } ?>
            </p>
            </div>
        </div>
    </div>
                

    <?php if ($feed != null) : ?>
        <div id="item-citation" class="element">
            <h3><?php echo __('Derniers articles'); ?></h3>
            <div class="element-text">
                <a rel="external" class="button" href=<?php echo $feed; ?>>S’abonner à ce flux</a>
                <?php $num = 15; //nombre d'entrées du feed à afficher
                try {
                    $feed = Zend_Feed_Reader::import($feed);
                } catch (Zend_Feed_Exception $e) {
                    echo '<p>Feed not available.</p>';
                    return;
                }
                $posts = 0;
                foreach ($feed as $entry) {
                    if (++$posts > $num) break;
                    $title = $entry->getTitle();
                    $link = $entry->getLink();
                    $description = $entry->getDescription();
                    echo "<p><a href=\"$link\">$title</a></p>"
                        . "<p>$description <a href=\"$link\">...more</a></p>";
                } ?>
            </div>
        </div>
    <?php endif; ?>
</div> <!-- End of Primary. -->

<!-- Si le 'type' de l'item est 'auteurs' ou 'Auteurs', on affiche l'index des auteurs de la collection -->
<?php elseif ((metadata('item', array('Dublin Core', 'Title')) == "Auteurs") || (metadata('item', array('Dublin Core', 'Title')) == "auteurs")) : ?>

<div id="primary">
    <div id="header-title">
        <h1 style="background:<?php echo $themeColor ?>;">
        <?php if ($RootTitle != '[Untitled]') {
            echo link_to_collection($RootTitle, array('style' => 'color :white', 'onmouseover' => 'this.style.color="lightgrey"', 'onmouseout' => 'this.style.color="white"'), 'show', $collectionTable->find($RootId)) . ' | ';
        } ?>
        <?php if ($RootTitle != $AncestorTitle && $RootTitle != '[Untitled]') : ?>
        <?php echo $AncestorTitle . ' | ' ?>  <?php endif ?><?php echo link_to_collection_for_item(null, array('style' => 'color :white', 'onmouseover' => 'this.style.color="lightgrey"', 'onmouseout' => 'this.style.color="white"'), 'show', $collectionTable->find($RootId)); ?>
        </h1>
    </div>
    <p class="title">
    <?php echo ('Auteurs'); ?>
    </p>
    <ul class="index_alpha" style="background:<?php echo $themeColor ?>;">     
        <?php foreach (range('A', 'Y') as $char) : ?>                 
                <li><a href="#<?php echo $char; ?>"><?php echo $char; ?></a>-</li>                                                    
        <?php endforeach; ?>
        <li><a href="#Z"><?php echo 'Z'; ?></a></li>
    </ul>

    <div class="index">
        <?php foreach (range('A', 'Z') as $char) : ?>                 
        <h2><a name="<?php echo $char; ?>"><?php echo $char; ?></a></h2>
            <ul class="index_liste_entrees">
            <?php echo index_auteurs(listeAuteurs($item, $subCollection), $char); ?>
            </ul>                                                   
        <?php endforeach; ?>
    </div>

</div> <!-- End of Primary. -->

<!-- Si le 'type' de l'item est 'mots-clés' ou 'Mots-clés', on affiche l'index des mots clés de la collection -->
<?php elseif ((metadata('item', array('Dublin Core', 'Title')) == "Mots-clés") || (metadata('item', array('Dublin Core', 'Title')) == "mots-clés")) : ?>

<div id="primary">
    <div id="header-title">
        <h1 style="background:<?php echo $themeColor ?>;">
        <?php if ($RootTitle != '[Untitled]') {
            echo link_to_collection($RootTitle, array('style' => 'color :white', 'onmouseover' => 'this.style.color="lightgrey"', 'onmouseout' => 'this.style.color="white"'), 'show', $collectionTable->find($RootId)) . ' | ';
        } ?>
        <?php if ($RootTitle != $AncestorTitle && $RootTitle != '[Untitled]') {
            echo $AncestorTitle . ' | ';
        }
        echo link_to_collection_for_item(null, array('style' => 'color :white', 'onmouseover' => 'this.style.color="lightgrey"', 'onmouseout' => 'this.style.color="white"'), 'show', $collectionTable->find($RootId)); ?>
        </h1>
    </div>
    <p class="title_auteur">
    <?php if (metadata('item', array('Dublin Core', 'Creator'))) {
        echo $Author;
    } ?>
    </p>
    <p class="title">
    <?php echo ('Mots-clés'); ?>
    </p>
    <ul class="index_alpha" style="background:<?php echo $themeColor ?>;">     
        <?php foreach (range('A', 'Y') as $char) : ?>                 
                <li><a href="#<?php echo $char; ?>"><?php echo $char; ?></a>-</li>                                                    
        <?php endforeach; ?>
        <li><a href="#Z"><?php echo 'Z'; ?></a></li>
    </ul>

    <div class="index">
        <?php foreach (range('A', 'Z') as $char) : ?>                 
        <h2><a name="<?php echo $char; ?>"><?php echo $char; ?></a></h2>
            <ul class="index_liste_entrees">
            <?php echo index_mots(motsCles($item, $subCollection), $char); ?>
            </ul>                                                   
        <?php endforeach; ?>
    </div>

</div> <!-- End of Primary. -->


<!-- Si le 'type' de l'item est 'rubrique', on affiche juste la description de l'item -->
<?php else : ?>

<div id="primary">
    <div id="header-title">
        <h1 style="background:<?php echo $themeColor ?>;">
        <?php if ($RootTitle != '[Untitled]') {
            echo link_to_collection($RootTitle, array('style' => 'color :white', 'onmouseover' => 'this.style.color="lightgrey"', 'onmouseout' => 'this.style.color="white"'), 'show', $collectionTable->find($RootId)) . ' | ';
        } ?>
        <?php if ($RootTitle != $AncestorTitle && $RootTitle != '[Untitled]') : ?>
        <?php echo $AncestorTitle . ' | ' ?>  <?php endif ?><?php echo link_to_collection_for_item(null, array('style' => 'color :white', 'onmouseover' => 'this.style.color="lightgrey"', 'onmouseout' => 'this.style.color="white"'), 'show', $collectionTable->find($RootId)); ?>
        </h1>
    </div>
    <p class="title_auteur">
    <?php if (metadata('item', array('Dublin Core', 'Creator'))) {
        echo $Author;
    } ?>
    </p>
    <p class="title">
    <?php echo metadata('item', array('Dublin Core', 'Title')); ?>
    </p>
    <!-- boutons d'acces aux documents -->
    <?php if (buttonPdf($item) != null) : ?>
        <a class="PDF" href="<?php echo buttonPdf($item); ?>">Lire ce document</a>
    <?php endif; ?>

    <!-- bouton pour cycnos -->
    <?php if (buttonCycnos($item) != null) : ?>
        <a class="PDF" href="<?php echo buttonCycnos($item); ?>">Lire ce document</a>
    <?php endif; ?>

    <?php if (buttonVideo($item) != null) : ?>
        <a class="PDF" href="<?php echo buttonVideo($item); ?>">Voir cette vidéo</a>
    <?php endif; ?>
   
    <?php if (buttonSource($item) != null) : ?>
        <a class="PDF" href="<?php echo buttonSource($item); ?>">Page de ce document</a>
    <?php endif; ?>
    
    <div id="collection-items">
        <div class="sommaire" style="border:white;">
            <div class="sommaire_item" style="background:white;">
                <p>
                <?php echo metadata('Item', array('Dublin Core', 'Description')); ?>
                <?php if (metadata('item', 'has files')) {
                    echo files_for_item();
                } ?>
                </p>
            </div>
        </div>
    </div>

</div> <!-- End of Primary. -->

<?php endif; ?>

 <?php echo foot(); ?>