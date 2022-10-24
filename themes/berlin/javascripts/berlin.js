if (!Berlin) {
    var Berlin = {};
}

(function ($) {    
    Berlin.dropDown = function(){
        var dropdownMenu = $('#mobile-nav');
        dropdownMenu.prepend('<a href="#" class="menu">Menu</a>');
        //Hide the rest of the menu
        $('#mobile-nav .navigation').hide();

        //function the will toggle the menu
        $('.menu').click(function() {
            $("#mobile-nav .navigation").slideToggle();
        });
    };
})(jQuery)

//Scripts permettant de choisir la page à afficher 
//affichage de la page d'accueil
function PublicFront() { 
    $("div.public_site").css("display", "inherit");
    $("div.concept").css("display", "none");
    $("div.credits_contacts").css("display", "none");
 } 
 
 //affichage de la page concept
 function ConceptFront() { 
    $("div.public_site").css("display", "none");
    $("div.concept").css("display", "inherit");
    $("div.credits_contacts").css("display", "none");
 } 
 
 //affichage de la page crédits / contacts
 function CreditsFront() { 
    $("div.public_site").css("display", "none");
    $("div.concept").css("display", "none");
    $("div.credits_contacts").css("display", "contents");
 } 
 
 //affichage des revues
 function TypeRevue() { 
    $("li.titre").css("display", "contents");
    $("li.titre2").css("display", "none");
    $("li.titre3").css("display", "none");
 } 
 
 //affichage des colloques
 function TypeColloque() { 
    $("li.titre").css("display", "none");
    $("li.titre2").css("display", "contents");
    $("li.titre3").css("display", "none");
 } 
 
 //affichage des cahiers
 function TypeCahier() { 
    $("li.titre").css("display", "none");
    $("li.titre2").css("display", "none");
    $("li.titre3").css("display", "contents");
 } 