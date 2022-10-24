<?php
$collectionTitle = metadata('collection', 'display_title');
if ($collectionTitle != '' && $collectionTitle != __('[Untitled]')) {
    $collectionTitle = ': &quot;' . $collectionTitle . '&quot; ';
} else {
    $collectionTitle = '';
}
$collectionTitle = __('Collection #%s', metadata('collection', 'id')) . $collectionTitle;
$collectionId = metadata('collection', 'id');
$collectionTable = get_db()->getTable('Collection');
$AncestorTree = get_db()->getTable('CollectionTree')->getAncestorTree($collectionId);
$DescendantTree = get_db()->getTable('CollectionTree')->getDescendantTree($collectionId);
$rootCollections = get_db()->getTable('CollectionTree')->getRootCollections();
$AncestorName = "untitled";
$collectionOrder = get_collection_order();
$subCollection = $collectionOrder->fetchOrderedCollections($collectionId);
foreach ($rootCollections as $rootCollection) {
    if (in_array($rootCollection, $AncestorTree)) {
        $AncestorId = $rootCollection['id'];
        $AncestorName = $rootCollection['name'];
        $CollectionTree = get_db()->getTable('CollectionTree')->getDescendantTree($AncestorId);
    }
}
?>
<?php echo head(array('title' => $collectionTitle, 'bodyclass' => 'collections show')); ?>

<section class="seven columns alpha">
    <?php echo flash(); ?>

    <?php echo all_element_texts('collection'); ?>

    <?php if (metadata('collection', 'Total Items') > 0) : ?>
    <h2><?php echo __('Recently Added Items'); ?></h2>
    <ul class="recent-items">
    <?php foreach (loop('items') as $item) : ?>
        <li><span class="date"><?php echo format_date(metadata('item', 'Added')); ?></span><span class="title"> <?php echo link_to_item(); ?></span></li>
    <?php endforeach; ?>
    </ul>
    <?php endif; ?>
<!-- Présentation -->
<?php if ($AncestorTree) : ?>
    <?php $subCollection = $collectionOrder->fetchOrderedCollections($AncestorId); ?>
    <h2><?php echo 'Collection racine : ' . link_to_collection($AncestorName, array(), 'show', $collectionTable->find($AncestorId)); ?></h2>
<?php else :
    $subCollection = $collectionOrder->fetchOrderedCollections($collectionId);
endif; ?>
<?php foreach ($subCollection as $menuCollection) :
    if (metadata(get_record_by_id('collection', $menuCollection['id']), array('Dublin Core', 'Type')) == ('menu')) : ?>
        <div class="main_nav_h2_contener">
            <h2><?php echo __(link_to_collection((metadata(get_record_by_id('collection', $menuCollection['id']), array('Dublin Core', 'Title'))), array(), 'show', $collectionTable->find($menuCollection['id']))); ?></h2>
        </div>
    <?php endif; ?>
<?php endforeach; ?>
        
    <!-- appel du plugin de collection tree pour le menu des textes en integralite-->
    <?php if ($AncestorTree) : ?>
        <?php foreach ($CollectionTree as $co) { ?>
                <?php if ($co['children']) { ?>
                    <div class="main_nav_h2_contener">
                    <h2><?php echo __(link_to_collection(short_string($co['name']), array(), 'show', $collectionTable->find($co['id']))); ?></h2>
                    </div>
                   <?php echo $this->collectionTreeList($co['children'], $co['id']);
                }
            } ?>
        <?php else : ?>
             <?php foreach ($DescendantTree as $co) { ?>
               <?php if ($co['children']) { ?>
                <div class="main_nav_h2_contener">
                <h2><?php echo __(link_to_collection(short_string($co['name']), array(), 'show', $collectionTable->find($co['id']))); ?></h2>
                </div>
                    <?php echo $this->collectionTreeList($co['children'], $co['id']);
                }
            } ?>
        <?php endif; ?>
         <?php echo get_specific_plugin_hook_output('ItemOrder', 'admin_collections_show', array('collection' => $collection, 'view' => $this)); ?>
         <?php echo get_specific_plugin_hook_output('CollectionOrder', 'admin_collections_show', array('collection' => $collection, 'view' => $this)); ?>
</section>

<section class="three columns omega">
    <div id="edit" class="panel">
        <?php if (is_allowed(get_current_record('collection'), 'edit')) : ?>    
            <?php echo link_to_collection(__('Edit'), array('class' => 'big green button'), 'edit'); ?>
        <?php endif; ?>
        <a href="<?php echo html_escape(public_url('collections/show/' . metadata('collection', 'id'))); ?>" class="big blue button" target="_blank"><?php echo __('View Public Page'); ?></a>
        <?php if (is_allowed(get_current_record('collection'), 'delete')) : ?>    
            <?php echo link_to_collection(__('Delete'), array('class' => 'big red button delete-confirm'), 'delete-confirm'); ?>
        <?php endif; ?>
    </div>       
    
    <div class="public-featured panel">
        <p><span class="label"><?php echo __('Public'); ?>:</span> <?php echo ($collection->public) ? __('Yes') : __('No'); ?></p>
        <p><span class="label"><?php echo __('Featured'); ?>:</span> <?php echo ($collection->featured) ? __('Yes') : __('No'); ?></p>
    </div>

    <div class="total-items panel">
        <h4><?php echo __('Total Number of Items'); ?></h4>
        <p><?php echo link_to_items_in_collection(); ?></p>
    </div>

    <div class="contributors panel">
        <h4><?php echo __('Contributors'); ?></h4>
        <ul id="contributor-list">
            <?php if ($collection->hasContributor()) : ?> 
            <li><?php echo metadata('collection', array('Dublin Core', 'Contributor'), array('all' => true, 'delimiter' => '</li><li>')); ?></li>
            <?php else : ?>
            <li><?php echo __('No contributors.'); ?></li>
            <?php endif; ?> 
        </ul>
    </div>

    <div class="panel">
        <h4><?php echo __('Output Formats'); ?></h4>
        <div>
        <?php if ($AncestorTree) : ?>
            <ul id="output-format-list">
                <li><a href="<?php echo html_escape(public_url('collections/show/' . metadata('collection', 'id'))); ?>?output=METScol">Sommaire XML</a></li>
            </ul>
               <?php endif; ?>
        <?php echo output_format_list(); ?></div>
    </div>
    <?php fire_plugin_hook('admin_collections_show_sidebar', array('view' => $this, 'collection' => $collection)); ?>
</section>

<?php echo foot(); ?>
