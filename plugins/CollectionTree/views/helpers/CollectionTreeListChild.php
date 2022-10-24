<?php
/**
 * Collection Tree
 *
 * @copyright Copyright 2007-2012 Roy Rosenzweig Center for History and New Media
 * @license http://www.gnu.org/licenses/gpl-3.0.txt GNU GPLv3
 */

/**
 * @package CollectionTree\View\Helper
 */
class CollectionTree_View_Helper_CollectionTreeListChild extends Zend_View_Helper_Abstract
{
    /**
     * Recursively build a nested HTML unordered list from the provided
     * collection tree.
     *
     * @see CollectionTreeTable::getCollectionTree()
     * @see CollectionTreeTable::getAncestorTree()
     * @see CollectionTreeTable::getDescendantTree()
     * @param array $collectionTree
     * @param bool $linkToCollectionShow
     * @return string
     */
    
    public function collectionTreeListChild($collectionTree, $AncestorId = 0, $linkToCollectionShow = true)
    {
        if (!$collectionTree) {
            return;
        }
        $collectionTable = get_db()->getTable('Collection');
        $collectionOrder = get_db()->getTable('CollectionOrder_CollectionOrder');
        $subCollection = $collectionOrder->fetchOrderedCollections($AncestorId);

        $html = '<div class="public_site">';
        $html .= '<ul class="intro" style="padding: 0;">';
        $html .= '<div class="row">';
        foreach ($subCollection as $collection) {
            if($collection['public'] == 1) {
                $html .= '<div class="col-md-6 titre">';
                $html .= '<li class="titre">';
                // No link to current collection.
                if ($linkToCollectionShow && !isset($collection['current']) && isset($collection['id'])) {
                    $getRecord = get_record_by_id('collection', $collection['id']);
                    $html .= '<div class="intro_img" style="display: flex;">';
                    if(metadata($getRecord, array('Dublin Core', 'Coverage'))){
                        $html .= '<a href="' . WEB_ROOT . '/collections/show/' . $collection["id"] .'" style="white-space:inherit;"><img src="' . metadata($getRecord, array('Dublin Core', 'Coverage')) . '"></a>';
                    }
                    $html .= '<div class="collection_description">' . link_to_collection(null, array(), 'show', $collectionTable->find($collection['id'])) . '<p>' . metadata($getRecord, array('Dublin Core', 'Contributor')) . '</p><p>' . metadata($getRecord, array('Dublin Core', 'Date')) . '</p></div>';
                    $html .= '</div>';
                }
                // No link to private parent collection.
                elseif (!isset($collection['id'])) {
                    $html .= __('[Unavailable]');
                }
                // Link to current collection.
                else {
                    $html .= empty($collection['name']) ? __('[Untitled]') : $collection['name'];
                }
                $html .= '</li>';
                $html .= '</div>';
            }
        }
        $html .= '</div>';
        $html .= '</ul>';
        $html .= '</div>';
        return $html;
    }
      
}
