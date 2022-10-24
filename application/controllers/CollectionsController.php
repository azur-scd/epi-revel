<?php
/**
 * Omeka
 *
 * @copyright Copyright 2007-2012 Roy Rosenzweig Center for History and New Media
 * @license http://www.gnu.org/licenses/gpl-3.0.txt GNU GPLv3
 */

/**
 * @package Omeka\Controller
 */
class CollectionsController extends Omeka_Controller_AbstractActionController
{
    protected $_autoCsrfProtection = true;

    public $contexts = array('show' => array('omeka-xml', 'omeka-json'));

    protected $_browseRecordsPerPage = self::RECORDS_PER_PAGE_SETTING;

    public function init()
    {
        $this->_helper->db->setDefaultModelName('Collection');
    }

    /**
     * The browse collections action.
     *
     */
    public function browseAction()
    {
        if (!$this->_getParam('sort_field')) {
            $this->_setParam('sort_field', 'added');
            $this->_setParam('sort_dir', 'd');
        }

        parent::browseAction();
    }

    /**
     * The show collection action
     *
     */
    public function showAction(){
        parent::showAction();
        $this->view->col_tmp = $this->_helper->db->getDb()->fetchAll("SELECT omeka_collections.id id FROM omeka_collections
        INNER JOIN omeka_collection_trees ON omeka_collection_trees.collection_id = omeka_collections.id
        WHERE omeka_collection_trees.parent_collection_id = ? ORDER BY omeka_collections.added DESC LIMIT 1", $this->view->collection->id);

        $this->view->col_menu = $this->_helper->db->getDb()->fetchAll("SELECT omeka_collection_trees.parent_collection_id id FROM omeka_collection_trees
        INNER JOIN omeka_collections ON omeka_collection_trees.collection_id = omeka_collections.id
        WHERE omeka_collections.id = ? ORDER BY omeka_collections.added DESC LIMIT 1", $this->view->collection->id);


        if ($this->view->col_tmp){
         $this->view->recent_col = $this->_helper->db->getTable('Collection')->find($this->view->col_tmp[0]['id']);
         $this->view->subitems = $this->_helper->db->getTable('Item')->findBy(
            array('collection' => $this->view->col_tmp[0]['id']), $this->_getBrowseRecordsPerPage());
        }
        $this->view->items = $this->_helper->db->getTable('Item')->findBy(
            array('collection' => $this->view->collection->id), $this->_getBrowseRecordsPerPage());

            

        if ($this->view->col_menu[0]['id']){
        $this->view->items = $this->_helper->db->getTable('Item')->findBy(
        array('collection' => $this->view->col_menu[0]['id']), $this->_getBrowseRecordsPerPage());
   }
    }

    /**
     * The add collection action
     *
     */
    public function addAction()
    {
        // Get all the element sets that apply to the item.
        $this->view->elementSets = $this->_getCollectionElementSets();
        parent::addAction();
    }

    /**
     * The edit collection action
     *
     */
    public function editAction()
    {
        // Get all the element sets that apply to the item.
        $this->view->elementSets = $this->_getCollectionElementSets();
        parent::editAction();
    }

    protected function _getAddSuccessMessage($collection)
    {
        $collectionTitle = $this->_getElementMetadata($collection, 'Dublin Core', 'Title');
        if ($collectionTitle != '') {
            return __('The collection "%s" was successfully added!', $collectionTitle);
        } else {
            return __('The collection #%s was successfully added!', strval($collection->id));
        }
    }

    protected function _getEditSuccessMessage($collection)
    {
        $collectionTitle = $this->_getElementMetadata($collection, 'Dublin Core', 'Title');
        if ($collectionTitle != '') {
            return __('The collection "%s" was successfully changed!', $collectionTitle);
        } else {
            return __('The collection #%s was successfully changed!', strval($collection->id));
        }
    }

    protected function _getDeleteSuccessMessage($collection)
    {
        $collectionTitle = $this->_getElementMetadata($collection, 'Dublin Core', 'Title');
        if ($collectionTitle != '') {
            return __('The collection "%s" was successfully deleted!', $collectionTitle);
        } else {
            return __('The collection #%s was successfully deleted!', strval($collection->id));
        }
    }

    protected function _getDeleteConfirmMessage($collection)
    {
        $collectionTitle = $this->_getElementMetadata($collection, 'Dublin Core', 'Title');
        if ($collectionTitle != '') {
            return __('This will delete the collection "%s" and its associated metadata. '
                 . 'This will not delete any items in this collection, but will '
                 . 'delete the reference to this collection in each item.', $collectionTitle);
        } else {
            return __('This will delete the collection #%s and its associated metadata. '
                 . 'This will not delete any items in this collection, but will '
                 . 'delete the reference to this collection in each item.', strval($collection->id));
        }
    }

    protected function _getElementMetadata($collection, $elementSetName, $elementName)
    {
        $m = new Omeka_View_Helper_Metadata;
        return strip_formatting($m->metadata($collection, array($elementSetName, $elementName)));
    }

    /**
     * Gets the element sets for the 'Collection' record type.
     *
     * @return array The element sets for the 'Collection' record type
     */
    protected function _getCollectionElementSets()
    {
        return $this->_helper->db->getTable('ElementSet')->findByRecordType('Collection');
    }
}
