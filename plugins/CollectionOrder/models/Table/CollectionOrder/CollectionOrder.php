<?php
class Table_CollectionOrder_CollectionOrder extends Omeka_Db_Table
{
    /**
     * Fetch the items in order for the specified collection.
     *
     * @param int $collectionId
     * @return array
     */
    public function fetchOrderedCollections($collectionId)
    {
        $collectionTreeTable = $this->getDb()->CollectionTree;
        $collectionTable = $this->getDb()->Collection;
        $collectionOrderTable = $this->getDb()->CollectionOrder_CollectionOrder;
        $sql = "
        SELECT c.*
        FROM $collectionTreeTable AS ct
        RIGHT JOIN $collectionTable AS c
        ON c.id = ct.collection_id
        LEFT JOIN $collectionOrderTable AS co
        ON c.id = co.collection_id
        WHERE ct.parent_collection_id = ?
        ORDER BY ISNULL(co.`order`), co.`order` ASC, c.id DESC";
        return $this->fetchAll($sql, $collectionId);
    }

    /**
     * Refresh the item order for the specified collection.
     *
     * @param int $collectionId
     */
    public function refreshCollectionOrder($collectionId)
    {
        $collectionTable = $this->getDb()->Collection;
        $collectionOrderTable = $this->getDb()->CollectionOrder_CollectionOrder;
        $collectionTreeTable = $this->getDb()->CollectionTree;

        // Delete item orders that are no longer assigned to the specified
        // collection. This is normally done on an item-by-item basis in the
        // after_item_save hook. This step is included in the event that an item
        // changes collection without firing the hook.
        $sql = "
        DELETE FROM $collectionOrderTable
        WHERE parent_collection_id = ?
        AND collection_id NOT IN (
            SELECT c.id
            FROM $collectionTable AS c
            LEFT JOIN $collectionTreeTable AS ct
            ON c.id = ct.collection_id
            WHERE ct.parent_collection_id = ?
        )";
        $this->query($sql, array($collectionId, $collectionId));

        // Refresh the current item order to start with 1 and be sequentially
        // unbroken. This step is necessary in the event that items have been
        // deleted after they were ordered.
        $sql = "SET @order = 0";
        $this->query($sql);

        $sql = "
        UPDATE $collectionOrderTable
        SET `order` = (SELECT @order := @order + 1)
        WHERE parent_collection_id = ?
        ORDER BY `order` ASC";
        $this->query($sql, $collectionId);

        // Get the items in this collection that have not been ordered and order
        // them, starting at the max order + 1 of the previously ordered items.
        $sql = "
        SET @order = IFNULL(
            (SELECT MAX(`order`) FROM $collectionOrderTable WHERE parent_collection_id = ?),
            0
        )";
        $this->query($sql, $collectionId);

        $sql = "
        INSERT INTO $collectionOrderTable (parent_collection_id, collection_id, `order`)
        SELECT s.parent_collection_id, s.id, @order := @order + 1
        FROM (
            SELECT ct.parent_collection_id, c.id
            FROM $collectionTreeTable AS ct
            RIGHT JOIN $collectionTable AS c
            ON c.id = ct.collection_id
            LEFT JOIN $collectionOrderTable AS co
            ON c.id = co.collection_id
            WHERE ct.parent_collection_id = ?
            AND co.id IS NULL
            ORDER BY c.id DESC
        ) AS s";
        $this->query($sql, $collectionId);
    }

    /**
     * Update the item order for the specified collection.
     *
     * @param int $collectionId
     * @param array $items
     */
    public function updateOrder($parentCollectionId, array $collections)
    {
        // Reindex the items array to start at 1.
        $collections = array_combine(range(1, count($collections)), array_values($collections));

        $collectionOrderTable = $this->getDb()->CollectionOrder_CollectionOrder;
        foreach ($collections as $collectionOrder => $collectionId) {
            $sql = "
            UPDATE $collectionOrderTable
            SET `order` = ?
            WHERE parent_collection_id = ?
            AND collection_id = ?";
            $this->query($sql, array($collectionOrder, $parentCollectionId, $collectionId));
        }
    }

    /**
     * Reset the collection item order.
     *
     * @param int $collectionId
     */
    public function resetOrder($collectionId)
    {
        $collectionOrderTable = $this->getDb()->CollectionOrder_CollectionOrder;
        $sql = "DELETE FROM $collectionOrderTable WHERE parent_collection_id = ?";
        $this->query($sql, $collectionId);
    }
}
?>
