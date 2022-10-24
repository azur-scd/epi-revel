
<div class="main_nav_h2_contener">
<h2><?php echo __('Rubriques'); ?></h2>
</div>

<?php
foreach ($collection_tree as $collection) {
    if($collection['children']){
    echo $this->collectionTreeListChild($collection['children'], $collection['id']);
} 
}?>