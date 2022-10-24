jQuery(document).ready(function () {
    var $ = jQuery;


    $(".relVocabShowHideBtn").click(function(e) {
        e.preventDefault();
        var curVocab = $(this).data("vocab");
        var rowClass = "relVocab_"+curVocab;
        $("."+rowClass).toggle();
    });

    var allShowHide = false;
    $(".relVocabRow").hide();

    var colspan = $(".relVocabHead th").first().attr('colSpan');
    $("#relVocabTable tbody").prepend(
        "<tr><th colspan='"+colspan+"'>"+
        "<a href='#' id='relVocabShowHideAllBtn'>["+relVocabShowHideAll+"]</a>"+
        "</th></tr>"
    );

    $("#relVocabShowHideAllBtn").click(function(e){
        e.preventDefault();
        allShowHide = !allShowHide;
        if (allShowHide) { $(".relVocabRow").show() } else { $(".relVocabRow").hide(); }
    });
});
