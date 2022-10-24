if (!Epirevel) {
    var Epirevel = {};
}

(function ($) {    
    Epirevel.dropDown = function(){
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
    $("div.credits_contacts").css("display", "inherit");
 } 
 
 //affichage des revues
 function TypeRevue() { 
    $("li.titre").css("display", "initial");
    $("li.titre2").css("display", "none");
    $("li.titre3").css("display", "none");
    $("div.titre").css("display", "flex");
    $("div.titre2").css("display", "none");
    $("div.titre3").css("display", "none");
 } 
 
 //affichage des colloques
 function TypeColloque() { 
    $("li.titre").css("display", "none");
    $("li.titre2").css("display", "initial");
    $("li.titre3").css("display", "none");
    $(".col-md-6.titre").css("display", "none");
    $(".col-md-6.titre2").css("display", "flex");
    $(".col-md-6.titre3").css("display", "none");
 } 
 
 //affichage des cahiers
 function TypeCahier() { 
    $("li.titre").css("display", "none");
    $("li.titre2").css("display", "none");
    $("li.titre3").css("display", "initial");
    $(".col-md-6.titre").css("display", "none");
    $(".col-md-6.titre2").css("display", "none");
    $(".col-md-6.titre3").css("display", "flex");
 } 
 $(document).ready(function () {

   $('#sidebarCollapse').on('click', function () {
       $('#sidebar').toggleClass('active');
   });
   $('#sidebarClose').on('click', function () {
      $('#sidebar').toggleClass('active');
  });
  

});