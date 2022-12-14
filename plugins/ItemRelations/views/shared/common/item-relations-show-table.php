<?php
$provideRelationComments = get_option('item_relations_provide_relation_comments');
$colspan = ($provideRelationComments ? 4 : 3);
$lastVocab = -1;
$thisItemId = $item-> id;

$relVocabShowHide = __("Afficher / Masquer");
$relVocabShowHideAll = __("Afficher / Masquer");
?>
<script type='text/javascript'>
    var relVocabShowHide = <?php echo json_encode($relVocabShowHide); ?>;
    var relVocabShowHideAll = <?php echo json_encode($relVocabShowHideAll); ?>;
</script>
<?php
  if (get_option('item_relations_tables_collapsible')) {
    echo js_tag('item-relations-vocab-toggle');
  }
?>
<table id='relVocabTable'><tbody>
<?php
foreach ($allRelations as $relation) {
    if ($lastVocab != $relation["vocabulary_id"]) {
        $lastVocab = $relation["vocabulary_id"];
        echo "<tr class='relVocabHead' data-vocab='$lastVocab'><th colspan='$colspan'></th></tr>";
    }
    echo "<tr class='relVocabRow relVocab_$lastVocab'>";
    echo "<td>"
        . ($relation['subject_item_id'] == $thisItemId
            ? __('')
            : "<a href='" . url('items/show/' . $relation['subject_item_id']) . "'>" . $relation['subject_item_title'] . "</a>"
        )
        . '</td>';
    echo "<td><strong>" . $relation['relation_text'] . "</strong></td>";
    echo "<td>"
        . ($relation['object_item_id'] == $thisItemId
            ? __('')
            : "<a href='" . url('items/show/' . $relation['object_item_id']) . "'>" . $relation['object_item_title'] . "</a>"
        )
        . '</td>';
    if ($provideRelationComments) {
        echo "<td>(" . $relation['relation_comment'] . ")</td>";
    }
    echo '</tr>';
} # foreach
?>
</tbody></table>
