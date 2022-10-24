<?php
/**
* ItemOrderPlugin class - represents the Item Order plugin
*
* @copyright Copyright 2008-2013 Roy Rosenzweig Center for History and New Media
* @license http://www.gnu.org/licenses/gpl-3.0.txt GNU GPLv3
* @package ItemOrder
*/

/** Path to plugin directory */
defined('COLLECTION_ORDER_PLUGIN_DIRECTORY')
    or define('COLLECTION_ORDER_PLUGIN_DIRECTORY', dirname(__FILE__));

/**
 * Item Order plugin.
 */
class CollectionOrderPlugin extends Omeka_Plugin_AbstractPlugin
{
    /**
     * @var array Hooks for the plugin.
     */
    protected $_hooks = array(
        'install',
        'uninstall',
        'upgrade',
        'define_acl',
        'after_save_collection',
        'after_delete_collection',
        'collections_browse_sql',
        'admin_collections_show',
    );

    /**
     * @var array Filters for the plugin.
     */
    protected $_filters = array(
        'collections_browse_default_sort',
    );

    /**
     * @var array Options and their default values.
     */
    protected $_options = array();

    /**
     * Install the plugin.
     */
    public function hookInstall()
    {
        $sql = "
        CREATE TABLE IF NOT EXISTS {$this->_db->CollectionOrder_CollectionOrder} (
            id int(10) unsigned NOT NULL AUTO_INCREMENT,
            collection_id int(10) unsigned NOT NULL,
            parent_collection_id int(10) unsigned NOT NULL,
            `order` int(10) unsigned NOT NULL,
            PRIMARY KEY (id),
            KEY `collection_id_order` (`collection_id`,`order`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
        $this->_db->query($sql);
    }

    /**
     * Uninstall the plugin.
     */
    public function hookUninstall()
    {
        $sql = "DROP TABLE IF EXISTS {$this->_db->CollectionOrder_CollectionOrder}";
        $this->_db->query($sql);
    }

    /**
     * Upgrade the plugin
     */
    public function hookUpgrade($args)
    {
        $oldVersion = $args['old_version'];
        $newVersion = $args['new_version'];
        $db = $this->_db;

        if (version_compare($oldVersion, '2.0-dev', '<=')) {
            $sql = "ALTER TABLE `{$db->prefix}collection_orders` RENAME TO `{$this->_db->CollectionOrder_CollectionOrder}` ";
            $db->query($sql);
            $sql = "ALTER TABLE `{$this->_db->CollectionOrder_CollectionOrder}` ADD INDEX `item_id_order` (`collection_id`,`order`) ";
            $db->query($sql);
        }
    }

    /**
     * Define the ACL.
     *
     * @param array $args
     */
    public function hookDefineAcl($args)
    {
        $acl = $args['acl']; // get the Zend_Acl
        $acl->addResource('CollectionOrder_Index');
    }

    /**
     * After save item hook.
     *
     * @param array $args
     */
     public function hookAfterSaveCollection($args)
     {
        // Delete the item order if the collection ID has changed.
        $collection = $args['record'];
        if ($collection->parent_collection_id) {
            $sql = "
            DELETE FROM {$this->_db->CollectionOrder_CollectionOrder}
            WHERE parent_collection_id != ?
            AND collection_id = ?";
            $this->_db->query($sql, array($collection->parent_collection_id, $collection->id));
        } else {
            $this->hookAfterDeleteCollection($args);
        }
     }

     /**
      * After delete item hook.
      *
      * @param array $args
      */
     public function hookAfterDeleteCollection($args)
     {
        // Delete the item order if the item was deleted.
        $collection = $args['record'];
        $sql = "
        DELETE FROM {$this->_db->CollectionOrder_CollectionOrder}
        WHERE collection_id = ?";
        $this->_db->query($sql, $collection->id);
    }

    /**
     * Hooks into items_browse_sql
     *
     * @param array $args
     */
    public function hookCollectionsBrowseSql($args)
    {
        $db = $this->_db;
        $select = $args['select'];
        $params = $args['params'];

        // Order the items whil:e browsing by collection.

        // Do not filter if not browsing by collection.
        if (!isset($params['collection'])) {
            return;
        }

        // Do not filter if sorting by browse table header.
        if (isset($params['sort_field'])) {
            return;
        }

        // Order the collection items by 1) whether an item order exists, 2) the
        // item order, 3) the item ID.
        $select->joinLeft(array('collection_order_collection_orders' => $db->CollectionOrder_CollectionOrder), 'collections.id = collection_order_collection_orders.collection_id', array())
               ->reset('order')
               ->order(array(
                   'ISNULL(collection_order_collection_orders.order)',
                   'collection_order_collection_orders.order ASC',
                   'collections.id DESC'
               ));
    }

    /**
     * Admin collection show content hook.
     *
     * @param array $args
     */
    public function hookAdminCollectionsShow($args)
    {
        $collection = $args['collection'];
?>
<div id="collection_order_admin_collection_show">
<h2>Collection Order</h2>
<p><a href="<?php echo url('collection-order', array('parent_collection_id' => $collection->id)); ?>">Order collections in this collection.</a></p>
<form action="<?php echo url('collection-order/index/reset-order', array('parent_collection_id' => $collection->id)); ?>" method="post">
    <input type="submit" name="collection_order_reset" value="Reset collections to their default order" style="float: none; margin: 0;" />
</form>
</div>
<?php
    }

    /**
     * Ignore the items/browse default sort if a collection was specified in the
     * routing or GET params.
     *
     * @param array $sort
     * @param array $args
     * @return array|null
     */
    public function filterCollectionsBrowseDefaultSort($sort, $args)
    {
        if (empty($args['params']['collection'])) {
            return $sort;
        } else {
            return null;
        }
    }
}
