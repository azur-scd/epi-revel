<?php
$collectionTitle = metadata('collection', 'display_title');
$collectionId = metadata('collection', 'id');
$recentId = null;
if (isset($recent_col)){
    $recentId = metadata($recent_col, 'id');
} 
$collectionTable = get_db()->getTable('Collection');
$DescendantTree = get_db()->getTable('CollectionTree')->getDescendantTree($collectionId);
$RecentDescendantTree = get_db()->getTable('CollectionTree')->getDescendantTree($recentId);
$AncestorTree = get_db()->getTable('CollectionTree')->getAncestorTree($collectionId);
$AncestorId = null;
$rootCollections = get_db()->getTable('CollectionTree')->getRootCollections();
$AncestorTitle = '[Untitled]';
foreach ($rootCollections as $rootCollection) {
    if(in_array($rootCollection, $AncestorTree)){
$AncestorTitle = $rootCollection['name'];
$AncestorId = $rootCollection['id'];
}}
?>

<?php echo head(array('title'=> $collectionTitle, 'bodyclass' => 'collections show')); ?>


<div id=ariane>
    <ul>
    <li><a href= http://134.59.6.81/omeka><img alt="Epi-Revel@Nice" src="http://134.59.6.81/omeka/themes/berlin/images/epirevel_path.gif" width="64" height="10"></a></li>
    <?php if ($AncestorTree):?>
    <li><?php echo ' | ' . link_to_collection(short_string($AncestorTitle), array(), 'show', $collectionTable->find($AncestorId)) . ' | ' . link_to_collection(short_string($collectionTitle), array(), 'show', $collectionTable->find($collectionId)); ?></li>
<?php else: ?>
    <li><?php echo ' | ' . link_to_collection(short_string($collectionTitle), array(), 'show', $collectionTable->find($collectionId)); ?></li>
<?php endif; ?>
    </ul>
</div>


<div id="main_nav">
    <div id="navEntries">
        <div class="main_nav_h2_contener">
            <h2><?php echo __('Présentation'); ?></h2>
        </div>
        <ul>
            <li></li>
        </ul>
        <div class="main_nav_h2_contener">
            <h2><?php echo __('Actualités'); ?></h2>
        </div>
        <ul>
            <li></li>
        </ul>
        <div class="main_nav_h2_contener">
            <h2><?php echo __('Index'); ?></h2>
        </div>
        <ul>
            <li></li>
        </ul>
        <!-- <h2><?php echo __('Textes en intégralité'); ?></h2>
         <?php
             echo $this->collectionTreeListChild($DescendantTree);
        ?> -->
</div>
</div>

 <?php   if (!$AncestorTree) :?>

<!--  Si la collection n'a pas de parent : on affiche le sujet. Il s'agit de la page d'accueil de la collection donc on affiche sa description, la dernière publication et son contenu-->
<div id="header-title">
<h1><?php echo $collectionTitle; ?><?php echo __(' | '); ?><?php echo metadata('collection', array('Dublin Core', 'Subject')); ?></h1>
</div>
<div id="collection-items">
<div class="sommaire">
    <div class="sommaire_item">
        <div>
            <p>
            <?php echo metadata('collection', array('Dublin Core', 'Description')); ?>
            </p>
        </div>
</div>
    </div>
    </div>


<div id="collection-items">
    <div class="sommaire_titre">
    <?php if (isset($recent_col)): ?>
        <h1><?php echo __("Dernière publication en ligne"); ?></h1>
        </div>
    <div class="sommaire_header">
    <h1><?php echo metadata($recent_col, array('Dublin Core', 'Title')); ?></h1>
    <p> <?php if(metadata($recent_col, array('Dublin Core', 'Contributor'))){ echo 'Sous la direction de ' ;} ?><?php echo metadata($recent_col, array('Dublin Core', 'Contributor')); ?>  </p>
    <p class="sommaire_header_date"> 
   
            <?php echo metadata($recent_col, array('Dublin Core', 'Date')); ?> 
        </p>
    </div>

    <?php if (metadata($recent_col, array('Dublin Core', 'Description'))): ?>
<div class="sommaire">
    <div class="sommaire_item">
        <div>
            <p>
            <?php echo metadata($recent_col, array('Dublin Core', 'Description')); ?>
            </p>
        </div>
    </div>
</div>
<?php endif; ?>
</div>


<div id="collection-items">
    <div class="sommaire">
    <div class="sommaire_item">
    <?php foreach($RecentDescendantTree as $recentCollec):?>
        <h3><span class="item-name"><?php echo ($recentCollec['name']);?></span></h3>        
        <?php if (metadata($recent_col, 'total_items') > 0): ?>
            <?php foreach (loop('items') as $item): ?>
            <?php if(metadata('item','Collection Id') == $recentCollec['id']): ?>          
                <?php $itemTitle = metadata('item', 'display_title'); ?>
                <div class="item entry">
                <p><span class="sommaire_auteur"><?php echo reverse_string(preg_replace('/,/', ' ', metadata('item', array('Dublin Core','Creator'))));?></span><?php echo ' : ' . link_to_item($itemTitle, array('class'=>'permalink')); ?></p>
                <?php if (metadata('item', 'has thumbnail')): ?>
                    <div class="item-img">
                    <?php echo link_to_item(item_image(null, array('alt' => $itemTitle))); ?>
                    </div>
                <?php endif; ?>
                </div>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php else: ?>
            <p><?php echo __("There are currently no items within this collection."); ?></p>
        <?php endif; ?>
    <?php endforeach;?>
</div>
</div>
</div>

<?php endif; ?>







<!--  Si la collection a un parent : il s'agit d'une sous collection et donc on affiche le sommaire et le contenu de la collection-->
<?php else: ?>
<div id="header-title">
<h1><?php echo $AncestorTitle; ?><?php echo __(' | '); ?><?php echo $collectionTitle; ?></h1>
</div>

<div id="collection-items">
<div class="sommaire_header">
    <!-- <h1><?php echo link_to_items_browse(__('Items in the %s Collection', $collectionTitle), array('collection' => metadata('collection', 'id'))); ?></h1> -->
    <p> <?php if(metadata('collection', array('Dublin Core', 'Contributor'))){ echo 'Sous la direction de ' ;} ?><?php echo metadata('collection', array('Dublin Core', 'Contributor')); ?>  </p>
    <p class="sommaire_header_date"> 
   
            <?php echo metadata('collection', array('Dublin Core', 'Date')); ?> 
        </p>
    </div>

<?php if (metadata('collection', array('Dublin Core', 'Description'))): ?>
<div class="sommaire">
    <div class="sommaire_item">
        <div>
            <p>
            <?php echo metadata('collection', array('Dublin Core', 'Description')); ?>
            </p>
        </div>
    </div>
</div>
<?php endif; ?>
</div>


<div id="collection-items">
    <div class="sommaire_titre">
    <h1><?php echo __("Sommaire"); ?></h1>
    <?php echo '<br>'; ?>
</div>
    <div class="sommaire">
    <div class="sommaire_item">
    <?php foreach($DescendantTree as $collec):?>
        <h3><span class="item-name"><?php echo ($collec['name']);?></span></h3>        
        <?php if (metadata('collection', 'total_items') > 0): ?>
            <?php foreach (loop('items') as $item): ?>
            <?php if(metadata('item','Collection Id') == $collec['id']): ?>          
                <?php $itemTitle = metadata('item', 'display_title'); ?>
                <div class="item entry">
                <p><span class="sommaire_auteur"><?php echo reverse_string(preg_replace('/,/', ' ', metadata('item', array('Dublin Core','Creator'))));?></span><?php echo ' : ' . link_to_item($itemTitle, array('class'=>'permalink')); ?></p>
                <?php if (metadata('item', 'has thumbnail')): ?>
                    <div class="item-img">
                    <?php echo link_to_item(item_image(null, array('alt' => $itemTitle))); ?>
                    </div>
                <?php endif; ?>
                </div>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php else: ?>
            <p><?php echo __("There are currently no items within this collection."); ?></p>
        <?php endif; ?>
    <?php endforeach;?>
</div>
</div>
</div><!-- end collection-items -->


<?php endif; ?>

<?php fire_plugin_hook('public_collections_show', array('view' => $this, 'collection' => $collection)); ?>

<?php echo foot(); ?>
