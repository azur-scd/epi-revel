<?php

function getAuthor($item)
{
    $auteursItem = metadata($item, array('Dublin Core', 'Creator'), array('all' => true));  //liste des auteurs
    $auteurStr = '';
    $auteurCount = 0; 
    //création de string des auteurs avec la bonne mise en page des noms
    foreach ($auteursItem as $auteurItem) :
        $auteurStr = $auteurStr . clear_auteurs($auteurItem);
    $auteurCount = $auteurCount + 1;
    if ($auteurCount < sizeof($auteursItem)) {
        $auteurStr = $auteurStr . ', ';
    }
    endforeach;
    return $auteurStr;
}

function returnTag($item)
{
    $tagId = metadata($item, array('Dublin Core', 'Subject'), array('all' => true));
    $tagList = null;
    $tagCount = 0;
    $arraySize = sizeof($tagId);
    $fullTag = null;
    foreach ($tagId as $tag) {
        if (strpos($tag, ']')) {
            $arraySize = $arraySize - 1;
        }
    }
    foreach ($tagId as $tag) {
        if (!strpos($tag, ']')) {
            $tagList = $tag;
            $tagCount = $tagCount + 1;
            $fullTag = $fullTag . $tagList;
        }
        if ($tagCount < $arraySize) {
            $fullTag = $fullTag . ', ';
        }
    }

    return $fullTag;
}

function buttonPdf($item)
{
    $pdfId = metadata($item, array('Dublin Core', 'Identifier'), array('all' => true));
    $pdfLink = null;
    foreach ($pdfId as $pdf) {
        if (strpos($pdf, '.pdf')) {
            $pdfLink = $pdf;
        }
    }
    return $pdfLink;
}

function buttonCycnos($item)
{
    $pathDir = APP_DIR . '/../cycnos';
    $pathFile = WEB_ROOT . '/cycnos';
    $files = scandir($pathDir);
    $pdfLink = null;
    $itemId = metadata('item', 'Id');
    foreach ($files as $file) {
        if ((string)$file == (string)$itemId . '.pdf') {
            $pdfLink = $pathFile . '/' . $file;
            break;
        }
    }
    return $pdfLink;
}

function buttonVideo($item)
{
    $videoId = metadata($item, array('Dublin Core', 'Identifier'), array('all' => true));
    $videoLink = null;
    foreach ($videoId as $video) {
        if (strpos($video, '.mp')) {
            $videoLink = $video;
        }
    }
    return $videoLink;
}

function buttonSource($item)
{
    $sourceId = metadata($item, array('Dublin Core', 'Source'), array('all' => true));
    $sourceLink = null;
    $urlCount = 0;
    foreach ($sourceId as $source) {
        if (strpos($source, '/') && !strpos($source, ' ') && $urlCount == 0) {
            $sourceLink = $source;
            $urlCount = $urlCount + 1;
        }
    }
    return $sourceLink;
}

//crée une liste pour la page des mots clés
function motsCles($item, $subCollection)
{
    $arrayMots = array();
    foreach ($subCollection as $subCol) {
        foreach (loop('items', get_records('Item', array('collection' => $subCol['id']), 'all')) as $subitems) {
            $tempMots = (metadata($subitems, array('Dublin Core', 'Subject'), array('all' => true)));
            foreach ($tempMots as $mot)
                if (!strpos($mot, ']')) {
                $arrayMots[] = $mot;
            }
        }
        if (metadata($subitems, 'has tags')) {
            $tempTags = explode(",", tag_string($subitems));
            foreach ($tempTags as $tag) {
                $arrayMots[] = $tag;
            }
        }
    }
    $arrayMots = array_unique($arrayMots);
    sort($arrayMots);
    return $arrayMots;
}

//crée une liste pour la page des auteurs
function listeAuteurs($item, $subCollection)
{
    $arrayAuteurs = array();
    foreach ($subCollection as $subCol) {
        foreach (loop('items', get_records('Item', array('collection' => $subCol['id']), 'all')) as $subitems) {
            $tempAuteurs = (metadata($subitems, array('Dublin Core', 'Creator'), array('all' => true)));
            foreach ($tempAuteurs as $creator) {
                $arrayAuteurs[] = $creator;
            }
        }
    }
    $arrayAuteurs = array_unique($arrayAuteurs);
    sort($arrayAuteurs);
    return $arrayAuteurs;
}

function clear_br($string)
{
    $final_string = str_replace('<br', ' ', $string);
    $final_string = str_replace('br/>', ' ', $final_string);
    $final_string = str_replace('/>', ' ', $final_string);
    return $final_string;
}


function clear_doi($string)
{
    $final_string = str_replace('DOI', '', $string);
    $final_string = str_replace(':', '', $final_string);
    $final_string = str_replace(' ', '', $final_string);
    return $final_string;
}

function reverse_string($string)
{
    $array = explode(' ', $string);
    $final_string = null;
    $count = 0;
    foreach ($array as $word) {
        if ($count == 0) {
            $final_string = $word . '' . $final_string;
        } else {
            $final_string = $word . ' ' . $final_string;
        }
        $count = 1;
    }
    $final_string = clear_br($final_string);
    return $final_string;
}

function short_string($string)
{
    $array = explode(' ', $string);
    $final_string = null;
    foreach ($array as $word) {
        if (strlen($final_string) < 25) {
            $final_string .= $word . ' ';
        }
    }
    if (strlen($final_string) >= 25) {
        $final_string .= '...';
    }
    return $final_string;
}

function short_desc($string)
{
    $array = explode(' ', $string);
    $final_string = null;
    foreach ($array as $word) {
        if (strlen($final_string) < 350) {
            $final_string .= $word . ' ';
        }
    }
    if (strlen($final_string) >= 350) {
        $final_string .= '...';
    }
    return $final_string;
}

function index_auteurs($auteurs, $lettre)
{
    $output = '';
    foreach ($auteurs as $tempAuteur) {
        if ($tempAuteur[0] == $lettre) {
            $tempRecherche = explode(',', $tempAuteur);
            $output .= "<li>";
            $output .= "<a href='http://epi-revel.univ-cotedazur.fr/search?query=$tempRecherche[0]&submit_search=Recherche'>";
            $output .= (reverse_string(preg_replace('/,/', ' ', $tempAuteur)));
            $output .= "</a>";
            $output .= "</li>";
        }
    }
    echo $output;
}

function index_mots($mots, $lettre)
{
    $output = '';
    foreach ($mots as $tempMot) {
        if ($tempMot[0] == $lettre) {
            $output .= "<li>";
            $output .= "<a href='http://epi-revel.univ-cotedazur.fr/search?query=$tempMot&submit_search=Recherche'>";
            $output .= $tempMot;
            $output .= "</a>";
            $output .= "</li>";
        }
    }
    echo $output;
}

function type_traduction($type)
{
    $output = $type;
    $type = strtolower($type);
    if ($type == 'journal articles') {
        $output = 'Article dans une revue';
    }
    if ($type == 'journal article') {
        $output = 'Article dans une revue';
    }
    if ($type == 'conference papers') {
        $output = 'Communication dans un congrès';
    }
    if ($type == 'conference paper') {
        $output = 'Communication dans un congrès';
    }
    if ($type == 'preprint') {
        $output = 'Pré-publication';
    }
    if ($type == 'poster communication') {
        $output = 'Poster';
    }
    if ($type == 'poster communications') {
        $output = 'Poster';
    }
    if ($type == 'report') {
        $output = 'Rapport';
    }
    if ($type == 'picture') {
        $output = 'Image';
    }
    if ($type == 'pictures') {
        $output = 'Image';
    }
    if ($type == 'video') {
        $output = 'Vidéo';
    }
    if ($type == 'videos') {
        $output = 'Vidéo';
    }
    if ($type == 'map') {
        $output = 'Carte';
    }
    if ($type == 'audio') {
        $output = 'Son';
    }
    if ($type == 'books') {
        $output = 'Ouvrage';
    }
    if ($type == 'book sections') {
        $output = "Chapitre d'ouvrage";
    }
    if ($type == 'directions of work or proceedings') {
        $output = "Direction d'ouvrage, Actes, Dossiers";
    }
    if ($type == 'patents') {
        $output = 'Brevet';
    }
    if ($type == 'other publications') {
        $output = 'Autres publications';
    }
    if ($type == 'theses') {
        $output = 'Thèse';
    }
    if ($type == 'lectures') {
        $output = 'Cours';
    }
    if ($type == 'preprints, working papers, ...') {
        $output = 'Pré-publication, document de travail';
    }
    return $output;
}

function clear_feed($string)
{
    $final_string = implode("/", array_slice(explode("/", $string), 0, 3));
    return $final_string;
}

function clear_auteurs($auteur)
{
    if (strpos($auteur, ',') !== false) {
        $tempAuteur = explode(',', $auteur);
        return $tempAuteur[1] . ' ' . $tempAuteur[0];
    } else {
        return $auteur;
    }
}

function get_color($collection)
{
    $themeColor = '#216a88';
    if (metadata($collection, array('Dublin Core', 'Format'))) {
        $themeColor = metadata($collection, array('Dublin Core', 'Format'));
    }
    return $themeColor;
}

function get_collection_table()
{
    $collectionTable = get_db()->getTable('Collection');
    return $collectionTable;
}

function get_ancestor_id($id)
{
    $get_collection = get_db()->getTable('CollectionTree')->getCollection($id);
    return $get_collection['parent_collection_id'];
}

function get_ancestor_tree($id)
{
    $AncestorTree = get_db()->getTable('CollectionTree')->getAncestorTree($id);
    return $AncestorTree;
}

function get_descendant_tree($id)
{
    $DescendantTree = get_db()->getTable('CollectionTree')->getDescendantTree($id);
    return $DescendantTree;
}

function get_collection_tree($id)
{
    $FullTree = get_db()->getTable('CollectionTree')->getCollectionTree($id);
    return $FullTree;
}

function get_ancestor_title($id)
{
    $AncestorTitle = null;
    $AncestorCollection = get_db()->getTable('CollectionTree')->getCollection($id);
    if ($AncestorCollection['name'] != null) {
        $AncestorTitle = $AncestorCollection['name'];
    }
    return $AncestorTitle;
}

function get_root_collection()
{
    $rootCollection = get_db()->getTable('CollectionTree')->getRootCollections();
    return $rootCollection;
}

function get_collection_order()
{
    $order = get_db()->getTable('CollectionOrder_CollectionOrder');
    return $order;
}

function get_most_recent_id($id)
{
    $mostRecentId = $id;
    $mostRecent = null;
    $collectionOrder = get_collection_order();
    $DescendantTree = get_descendant_tree($id);
    foreach ($DescendantTree as $Tree) {
        if ($Tree['children']) {
            $subCollections = $collectionOrder->fetchOrderedCollections($Tree['id']);
            foreach ($subCollections as $subCollection) {
                if ($subCollection['public'] == 1 && $subCollection['added'] >= $mostRecent['added'] && metadata(get_record_by_id('collection', $subCollection['id']), array('Dublin Core', 'Type')) != ('menu')) {
                    $mostRecent = get_record_by_id('collection', $subCollection['id']);
                    $mostRecentId = $mostRecent['id'];
                }
            }
        }
    }
    return $mostRecentId;
}
function get_auteur_str($item)
{
    $auteursItem = metadata($item, array('Dublin Core', 'Creator'), array('all' => true));
    $array_size = sizeof($auteursItem);
    $auteurStr = '';
    $auteurCount = 0;
    foreach ($auteursItem as $auteurItem) {
        if ($auteurCount < 4) {
            $auteurStr = $auteurStr . clear_auteurs($auteurItem);
            $auteurCount = $auteurCount + 1;
            if (($auteurCount < $array_size) && ($auteurCount < 4)) {
                $auteurStr = $auteurStr . ', ';
            }
        }
    }
    return $auteurStr;
}

function nombre_auteurs($item)
{
    $auteursItem = metadata($item, array('Dublin Core', 'Creator'), array('all' => true));
    return sizeof($auteursItem);
}

function item_type($type)
{
    $output = '';
    $output .= '<span class="sommaire_type">';
    $output .= "\t";
    foreach ($type as $typeItem) {
        if (strpos($typeItem, '/') == false) {
            if (get_html_lang() == 'fr') {
                $output .= type_traduction($typeItem);
            } else {
                $output .= $typeItem;
            }
        }
    }
    $output .= '</span>';
    echo $output;
}
?>