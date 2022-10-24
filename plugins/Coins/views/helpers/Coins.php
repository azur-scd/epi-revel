<?php
/**
 * COinS
 *
 * @copyright Copyright 2007-2012 Roy Rosenzweig Center for History and New Media
 * @license http://www.gnu.org/licenses/gpl-3.0.txt GNU GPLv3
 */

/**
 * @package Coins\View\Helper
 */
class Coins_View_Helper_Coins extends Zend_View_Helper_Abstract
{
    /**
     * Return a COinS span tag for every passed item.
     *
     * @param array|Item An array of item records or one item record.
     * @return string
     */
    public function coins($items)
    {
        if (!is_array($items)) {
            return $this->_getCoins($items);
        }

        $coins = '';
        foreach ($items as $item) {
            $coins .= $this->_getCoins($item);
            release_object($item);
        }
        return $coins;
    }

    /**
     * Build and return the COinS span tag for the specified item.
     *
     * @param Item $item
     * @return string
     */
    protected function _getCoins(Item $item)
    {
        $coins = '';
        $collectionId = metadata($item, 'Collection Id');// id de la collection
        $AncestorTree = get_db()->getTable('CollectionTree')->getAncestorTree($collectionId); //l'arbre des collections parentes
        $rootCollections = get_db()->getTable('CollectionTree')->getRootCollections(); //collection racine
        $finalType = 'journalArticle';
        $rootTypes = null;
        foreach ($rootCollections as $rootCollection) {
            if(in_array($rootCollection, $AncestorTree)){
                $RootId = $rootCollection['id'];
                $rootTypes = metadata(get_record_by_id('collection', $RootId), array('Dublin Core','Type'), array('all' => true));
        }}
        if($rootTypes != null){
        foreach($rootTypes as $rootType){
        if($rootType == 'colloque' || $rootType == 'Colloque'){
                $finalType = 'conferencePaper';
            }
        elseif($rootType == 'revue' || $rootType == 'Revue'){
            $finalType = 'journalArticle';
        }}}
        

        $coins .= 'ctx_ver=Z39.88-2004';
        $coins .= '&rft_val_fmt=info:ofi/fmt:kev:mtx:dc';
        $coins .= '&rfr_id=info:sid/omeka.org:generator';
        
        // Set the Dublin Core elements that don't need special processing.
        $elementNames = array('Publisher', 'Contributor',
                              'Date', 'Format', 'Language', 'Coverage',
                              'Rights', 'Relation');
        foreach ($elementNames as $elementName) {
            $elementText = $this->_getElementText($item, $elementName);
            if (false === $elementText) {
                continue;
            }

            $elementName = strtolower($elementName);
            $coins .= '&rft.'. $elementName .'=' . $elementText;
        }

        // Set the title key from Dublin Core:title.
        $title = $this->_getElementText($item, 'Title');
        if (false === $title || '' == trim($title)) {
            $title = '[unknown title]';
        }
        $coins .= '&rft.title='. str_replace("&amp;", "et", $title);

        // Set the description key from Dublin Core:description.
        $description = $this->_getElementText($item, 'Description');
        if (false === $description) {
            return;
        }
        $coins .= '&rft.description='. str_replace("&", "et", $description);
        
        $subjectItem = metadata($item, array('Dublin Core', 'Subject'), array('all' => true));  //liste des auteurs
        foreach ($subjectItem as $subject): 
            $coins .= '&rft.subject='. $subject;
        endforeach; 

        // Set the description key from Dublin Core:creator.
        $auteursItem = metadata($item, array('Dublin Core', 'Creator'), array('all' => true));  //liste des auteurs
        foreach ($auteursItem as $auteurItem): 
            $coins .= '&rft.creator='. $auteurItem;
        endforeach; 

        $sourceItem = metadata($item, array('Dublin Core', 'Source'), array('all' => true));  //liste des sources
        $countSource = 1;
        foreach ($sourceItem as $source): 
            if($countSource == 1){
                $coins .= '&rft.source='. $source;
            }
            else {
                if(!strpos($source, "http" !== false)){
                $coins .= '&rft.volume='. $source;
                }
            }
            $countSource = 2;
        endforeach; 

        // Set the type key from item type, map to Zotero item types.
        $itemTypeName = metadata($item, 'item type name');
        switch ($itemTypeName) {
            case 'Oral History':
                $type = 'interview';
                break;
            case 'Moving Image':
                $type = 'videoRecording';
                break;
            case 'Sound':
                $type = 'audioRecording';
                break;
            case 'Email':
                $type = 'email';
                break;
            case 'Website':
                $type = 'webpage';
                break;
            case 'Text':
            case 'Document':
                $type = 'document';
                break;
            default:
                if ($itemTypeName) {
                    $type = $itemTypeName;
                } else {
                    $type = $this->_getElementText($item, 'Type');
                }
        }
        $coins .= '&rft.rights=openAccess';
        $coins .= '&rft.type='. $finalType;
        

        // Set the identifier key as the absolute URL of the current page.
        $coins .= '&rft.identifier='. absolute_url();
        $coins = str_replace('%', '%25', $coins);
        $coins = str_replace("&#039;", "'", $coins);
        $coins = str_replace("&quot;", "'", $coins);
        

        // Build and return the COinS span tag.
        $coinsSpan = '<span class="Z3988" title="';
        $coinsSpan .= html_escape($coins);
        $coinsSpan .= '"></span>';
        return $coinsSpan;
    }

    /**
     * Get the unfiltered element text for the specified item.
     *
     * @param Item $item
     * @param string $elementName
     * @return string|bool
     */
    protected function _getElementText(Item $item, $elementName)
    {
        $elementText = metadata(
            $item,
            array('Dublin Core', $elementName),
            array('no_filter' => true, 'no_escape' => true, 'snippet' => 500)
        );
        return $elementText;
    }
}
