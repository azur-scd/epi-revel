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
class IndexController extends Omeka_Controller_AbstractActionController
{
    protected $_browseRecordsPerPage = self::RECORDS_PER_PAGE_SETTING;

    public function indexAction()
    {
        $this->view->collections = $this->_helper->db->getTable('Collection')->findBy(array('featured' => 1));//, $this->_getBrowseRecordsPerPage());
        $this->_helper->viewRenderer->renderScript('index.php');
        //$this->view->collections = $this->_helper->db->getTable('Collection')->findBy(array('featured' => 1), $this->_getBrowseRecordsPerPage());
    }
}
