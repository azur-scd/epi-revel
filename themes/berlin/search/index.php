<?php
$pageTitle = __('Search') . ' ' . __('(%s total)', $total_results);
echo head(array('title' => $pageTitle, 'bodyclass' => 'search'));
$searchRecordTypes = get_search_record_types();
set_current_record('collection', get_record_by_id('collection', '4'));
?>
<!-- Affichage du fil d'ariane-->
<!-- le plugin de changement de langues est dans le fil d'ariane-->
<div id=ariane>
    <ul>
    <li><a href="<?php echo WEB_ROOT;?>"><img alt="Epi-Revel@Nice" src="<?php echo WEB_ROOT;?>/themes/berlin/images/epirevel_path.gif" width="64" height="10"></a></li>
    <li style="float: right; "><?php echo $this->localeSwitcher(); ?></li>
    </ul>
</div>
<div style="border-bottom:solid #2d8463 0.2em;"></div>
<div id="search-container" role="search">
    <?php echo search_form(array('show_advanced' => true)); ?>
</div>

<h1><?php echo $pageTitle; ?></h1>
<div class="public_site" style="width:74%;">
<?php echo search_filters(); ?>
<?php if ($total_results): ?>
<?php echo pagination_links(); ?>
<table id="search-results">
    <thead>
        <tr>
            <th style="border-bottom: 1px solid #216a88;"><?php echo __('Résultats');?></th>
        </tr>
    </thead>
    <tbody>
        <?php $filter = new Zend_Filter_Word_CamelCaseToDash; ?>
        <?php foreach (loop('search_texts') as $searchText): ?>
        <?php $record = get_record_by_id($searchText['record_type'], $searchText['record_id']); ?>
        <?php $recordType = $searchText['record_type']; ?>
        <?php set_current_record($recordType, $record); ?>
        <tr class="<?php echo strtolower($filter->filter($recordType)); ?>">
            <td>
                <?php if ($recordImage = record_image($recordType)): ?>
                    <?php echo link_to($record, 'show', $recordImage, array('class' => 'image')); ?>
                <?php endif; ?>
                <a href="<?php echo record_url($record, 'show'); ?>"><?php echo $searchText['title'] ? $searchText['title'] : '[Unknown]'; ?></a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php echo pagination_links(); ?>
<?php else: ?>
<div id="no-results">
    <p><?php echo __('Your query returned no results.');?></p>
</div>
<?php endif; ?>
</div>
<?php echo foot(); ?>
