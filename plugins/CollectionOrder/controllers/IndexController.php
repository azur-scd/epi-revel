<?php
class CollectionOrder_IndexController extends Omeka_Controller_AbstractActionController
{
    public function init()
    {
        $this->_helper->db->setDefaultModelName('CollectionOrder_CollectionOrder');
    }

    public function indexAction()
    {
        $db = $this->_helper->db;

        // Set the collection.
        $collection = $db->getTable('Collection')->find($this->_getParam('parent_collection_id'));

        // Refresh the collection items order and set the ordered items.
        $collectionOrderTable = $db->getTable('CollectionOrder_CollectionOrder');
        $collectionOrderTable->refreshCollectionOrder($this->_getParam('parent_collection_id'));
        $subCollections = $collectionOrderTable->fetchOrderedCollections($this->_getParam('parent_collection_id'));

        $this->view->assign('collection', $collection);
        $this->view->assign('subCollections', $subCollections);
    }

    /**
     * Order the items.
     */
    public function updateOrderAction()
    {
        // Allow only AJAX requests.
        if (!$this->getRequest()->isXmlHttpRequest()) {
            $this->_helper->redirector->gotoUrl('/');
        }

        // Update the item orders.
        $this->_helper->db->getTable('CollectionOrder_CollectionOrder')->updateOrder($this->_getParam('parent_collection_id'), $this->_getParam('collections'));
        $this->_helper->json(true);
    }

    /**
     * Reset the order.
     */
    public function resetOrderAction()
    {
        $this->_helper->db->getTable('CollectionOrder_CollectionOrder')->resetOrder($this->_getParam('parent_collection_id'));
        $this->_helper->flashMessenger('The collections have been reset to their default order.', 'success');
        $this->_helper->redirector->gotoUrl('/collections/show/' . $this->_getParam('parent_collection_id'));
    }
}
