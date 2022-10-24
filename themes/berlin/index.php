<?php
$rootCollections = get_db()->getTable('CollectionTree')->getRootCollections();
$collectionTable = get_db()->getTable('Collection');
$arrayCollections = $rootCollections;
   function sort_by_name($a,$b)
   {
       return strip_tags($a["name"]) > strip_tags($b["name"]);
   }
uasort($arrayCollections,"sort_by_name");

//attribution de la vue sur une collection (en l'occurence les actualités de la page d'accueil) pour pouvoir modifier l'URL 
set_current_record('collection', get_record_by_id('collection', '4'));
?>
	<script type='text/javascript' src='//epi-revel.univ-cotedazur.fr/themes/berlin/javascripts/jquery-2.1.0.min.js'></script>
   <script type='text/javascript' src='//epi-revel.univ-cotedazur.fr/themes/berlin/javascripts/berlin.js'></script>

<?php echo head(array('bodyid'=>'home', 'bodyclass' =>'two-col')); ?>

<!-- fil d'ariane -->
<div id=ariane>
    <ul>
      <li><a href= https://epi-revel.univ-cotedazur.fr><img alt="Epi-Revel@Nice" src="https://epi-revel.univ-cotedazur.fr/themes/berlin/images/epirevel_path.gif" width="64" height="10"></a></li>
      <li style="color : black;"><?php echo ' | ';?>
      <li style="float: right; "><?php echo $this->localeSwitcher(); ?></li>
    </ul>
</div>
<div style="border-bottom:solid #2d8463 0.2em;"></div>

<div class="head_pic">
   <a href="https://epi-revel.univ-cotedazur.fr/" ><img src="https://epi-revel.univ-cotedazur.fr/themes/berlin/images/banderole.png" alt="Epi_Revel"></a>
</div>

<div class="head_links">
   <div class="head_links_1">
      <a class="head_links_1_1" href="#" onclick="PublicFront()">» Accueil </a>
      <a class="head_links_1_2" href="#" onclick="ConceptFront()">» Concept </a>
      <a class="head_links_1_3" href="#" onclick="CreditsFront()">» Crédits / Contacts </a>
   </div>


   <div class="head_links_2">
      <div class="head_links_2_1">
         <p>Accès rapide aux actes et aux revues</p>
      </div>
      <div class="head_links_2_2">
         <select class="account_name" name="account_name" onchange="location = this.value;">
            <option></option>
            <?php foreach ($arrayCollections as $rootCollection) { 
               if ($rootCollection['name'] != 'Actualités accueil'){
                    $rootTitle = $rootCollection['name'];
                    $rootId = $rootCollection['id'];?>
                     <option value="https://epi-revel.univ-cotedazur.fr/collections/show/<?php echo $rootId ?>"><?php echo $rootTitle; ?></option>
                  <?php  }} ?>
         </select> 
      </div>
   </div>
</div>

<!-- menu des actualités -->
<div id="main_nav">
    <div id="navEntries">
        <?php foreach ($arrayCollections as $rootCollection) :
            if($rootCollection['name'] == 'Actualités accueil'):?>
                <div class="main_nav_h2_contener">
                <h2 style="font: bold 0.9em Georgia, Times, serif;"><?php echo __('Actualités'); ?></h2>
                </div>
                <ul>
                <?php set_loop_records('items', get_records('Item', array('collection' => '4'))); ?>
                <?php foreach (loop('items') as $actu_item): ?>
                   <?php if(metadata($actu_item,'Collection Name') == ('Actualités accueil' )): ?>   
                    <li style='list-style:none;border-bottom:1px dotted #333; margin: 0.8em 1.25em 0.8em 1.25em;'><?php echo link_to_item(metadata($actu_item, 'display_title'), array('class'=>'permalink', 'style' =>'font-weight: bold;', 'onmouseover' => 'this.style.color="#216a88"', 'onmouseout' => 'this.style.color="#444"')); ?></li>
                    <li class="desc" style='list-style:none; margin: 0.8em 1.25em 0.8em 1.25em;'> <p style='color:#666;'><?php echo short_desc(metadata($actu_item, array('Dublin Core', 'Description'))) ?></p></li>
                    <?php endif ?>
                <?php endforeach ?>
                </ul>
        <?php endif ?>
        <?php endforeach ?>
    </div>
</div>


 			<!-- ================================  CONCEPT  ================================ -->

            <div class="concept">

<h1>&agrave; propos d'Epi-Revel</h1>

<!-- SOMMAIRE -->
<div class="concept_sommaire">
   <ul>
      <li class="concept_sommaire_1"><h2><span>.</span>Informations pratiques</h2>
         <ul>
               <li><h3><a href="#section7" class="h3_a_sommaire">Conditions d'utilisation</a></h3></li>
               <li><h3><a href="#section8" class="h3_a_sommaire">Modalit&eacute;s d'adh&eacute;sion et d'&eacute;dition</a></h3></li>
            </ul>
      </li>
      <li class="concept_sommaire_1"><h2><span>.</span>Epi-Revel, comment et pourquoi</h2>
            <ul>
               <li><h3><a href="#section1" class="h3_a_sommaire">Raisons d'&ecirc;tre et atouts</a></h3></li>
               <li><h3><a href="#section5" class="h3_a_sommaire">Orientations</a></h3></li>
               <li><h3><a href="#section2" class="h3_a_sommaire">Termes de la collaboration avec les revues</a></h3></li>
            </ul>
         </li>
   </ul>

</div>


<br /> <br />

<!-- MAIN -->
<div class="concept_main">

   <h2>Informations pratiques</h2>
   <p>
      Outil d'&eacute;dition &eacute;lectronique simple d'utilisation et site de
      publication en ligne destin&eacute; &agrave; l'ensemble de la communaut&eacute;
      des chercheurs, <a href="https://epi-revel.univ-cotedazur.fr">Epi-Revel</a> s'adresse aussi
      bien aux &eacute;tudiants s'initiant &agrave; la recherche qu'aux
      universitaires &eacute;trangers. Le site est con&ccedil;u en deux volets : un
      c&ocirc;t&eacute; ouvert au public sur lequel les internautes ont acc&egrave;s
      au contenu des revues et colloques, ainsi qu'un espace priv&eacute;
      d'administration gr&acirc;ce auquel l'&eacute;diteur effectue la mise en ligne
      de sa publication. Le site s'appuie en partie sur le logiciel libre <a href="https://omeka.org">Omeka</a>.
      <br />
      <br /> Pour nous contacter, par courriel : <a href="mailto:publications@univ-cotedazur.fr">publications@univ-cotedazur.fr</a><br />
      Coordonn&eacute;es compl&egrave;tes en page : <a
         href="https://epi-revel.univ-cotedazur.fr">Cr&eacute;dits / Contacts</a>
   </p>

   <h3>
      <a name="section7">Conditions d'utilisation</a>
   </h3>

   <h4>D&eacute;finitions l&eacute;gales des usages et fonctionnements du site</h4>
   <p>
      Le site applique les proc&eacute;dures de confidentialit&eacute; en vigueur
      d&eacute;finies par la CNIL : &agrave; l'exception des adresses IP des
      utilisateurs recueillies par l'analyseur statistique, aucune information n'est
      collect&eacute;e &agrave; l'insu des utilisateurs ni aucune information
      personnelle c&eacute;d&eacute;e &agrave; des tiers. <br /> L'exploitation du
      site &agrave; des fins commerciales ou publicitaires est interdite ainsi que
      toute diffusion massive du contenu ou modification des donn&eacute;es sans
      l'accord des auteurs et de l'&eacute;quipe Epi-Revel.
   </p>

   <h4>Respect du droit d'auteur et de la propri&eacute;t&eacute; intellectuelle</h4>
   <p class="separation_apres">
      L'acc&egrave;s aux r&eacute;f&eacute;rences bibliographiques et au texte
      int&eacute;gral, aux outils de recherche ou au feuilletage de l'ensemble des
      revues et colloques est libre, cependant article, recension et autre
      contribution sont couvertes par le droit d'auteur et sont la
      propri&eacute;t&eacute; de leurs auteurs.<br /> Les utilisateurs doivent
      associer &agrave; toute unit&eacute; documentaire les &eacute;l&eacute;ments
      bibliographiques permettant de l'identifier correctement et notamment faire
      mention du nom de l'auteur, du titre de l'article, et de la revue. 
      Ces mentions apparaissent en bas de chaque article consult&eacute; en
      ligne ou sur la premi&egrave;re page des documents sauvegard&eacute;s en format
      PDF sur les postes des utilisateurs et imprim&eacute;s par leur soin.<br /> Le
      site Epi-Revel rel&egrave;ve de la l&eacute;gislation fran&ccedil;aise sur la
      propri&eacute;t&eacute; intellectuelle. Tous les droits de reproduction sur le
      graphisme du site ainsi que sur son contenu &eacute;ditorial sont
      r&eacute;serv&eacute;s par l'&eacute;diteur du site. La reproduction de tout ou
      partie de ce site sur un support &eacute;lectronique quel qu'il soit est
      formellement interdite sauf autorisation expresse.
   </p>

   <h3>
      <a name="section8">Modalit&eacute;s d'adh&eacute;sion et d'&eacute;dition</a>
   </h3>
   <p class="separation_apres">
      Epi-Revel est ouvert aux &eacute;quipes de chercheurs d'Universit&eacute C&ocirc;te d'Azur;
      qui souhaitent publier en ligne leurs revues ou actes
      de colloque. L'équipe du service Productions & publications scientifiques du SCD assure la maintenance du site et son soutien - incluant la
      formation continue aux outils - aux &eacute;quipes de r&eacute;daction dans
      toutes les op&eacute;rations li&eacute;es &agrave;&nbsp;la publication en
      ligne. Proc&eacute;der à l'inscription ISSN de sa revue est fortement recommand&eacute;
      pour compl&eacute;ter son identification. La demande d'attribution incombe aux équipes
      &eacute;ditoriales : acc&eacute;der au site de la <a href="https://www.bnf.fr">BNF</a> et au formulaire ISSN.
   </p>

   <p class="separation_apres">
      Les <strong>m&eacute;tadonn&eacute;es</strong>, informations relatives &agrave;
      un article (titre, nom d'auteur, r&eacute;sum&eacute; / abstract, mots
      cl&eacute;s...) permettent d'identifier l'article et de lui donner une
      meilleure visibilit&eacute;. Plus les m&eacute;tadonn&eacute;es sont
      pr&eacute;cises, plus l'article et donc la revue seront visibles sur les
      moteurs de recherche. Ce sont donc des donn&eacute;es essentielles qu'il
      appartient aux &eacute;diteurs d'ins&eacute;rer &agrave; l'int&eacute;rieur de
      chaque article avant sa mise en ligne.<br /> Nous recommandons aux
      &eacute;quipes &eacute;ditoriales de collecter m&eacute;thodiquement ces
      donn&eacute;es en amont et le cas &eacute;ch&eacute;ant d'envoyer la liste
      suivante aux auteurs afin qu'ils transmettent les donn&eacute;es manquantes :<br />
   <ul>
      <li><h4>- Titre de l'article (+ sous-titre &eacute;ventuel)</h4></li>
      <li><h4>- Nom de l'auteur</h4></li>
      <li><h4>- R&eacute;sum&eacute; et abstract</h4></li>
      <li><h4>- Mots cl&eacute;s et keywords</h4></li>
      <li><h4>- Notice bibliographique</h4></li>
      <li><h4>- Droits d'auteur</h4></li>
      <li><h4>- Date de publication</h4></li>
      <li><h4>- Th&egrave;mes</h4></li>
      <li><h4>- Langue</h4></li>
   </ul>

   </p>


   <h2>Epi-Revel, comment et pourquoi</h2>
   <h3 class="dans_text">
      <a name="section1">Raisons d'&ecirc;tre et atouts</a>
   </h3>
   <p>
   <a href="https://epi-revel.univ-cotedazur.fr">Epi-Revel</a> est né en 2017 sous l'impulsion de la Commission Recherche d’Université 
   Côte d’Azur comme une alternative à la plateforme Revel pour la mise en ligne de revues 
   électroniques et de colloques. <br />
   </p>
   <p>
    Les choix fonctionnels et techniques de la plateforme Revel, lancée en 2004, imposaient 
    des contraintes qui rendaient son appropriation difficile pour les équipes. Le formatage 
    des textes pouvant devenir rédhibitoire particulièrement pour des publications ponctuelles 
    comme les actes de colloques.<br />
    </p>
    <p>
    Le SCD propose avec Epi-Revel un nouveau modèle de publication basé sur l’éditorialisation 
    au-dessus des archives ouvertes avec comme objectif une facilité d’appropriation et une rapidité 
    de mise en ligne.<br />
    </p>
    <p>
    La plateforme fonctionne au-dessus des archives ouvertes, notamment HAL et MédiHAL. Elle moissonne 
    les métadonnées des documents déposés dans les archives sources avec le protocole OAI-PMH et permet 
    aux éditeurs d’organiser sur un plan éditorial leurs publications. <br />
    </p>
    <p>
    Elle est ouverte à toutes les disciplines, et les documents bénéficient de la visibilité internationale 
    et de la pérennité des archives ouvertes sources. L’interface de diffusion participe à la modernisation 
    des méthodes de publication des résultats des équipes de recherche.<br />
    </p>
    <p>
    Elle est placée sous la responsabilité de Sarah Hurter-Savie (directrice du Service Commun de la Documentation), 
    Ghislain Chave (concepteur, administrateur de la plateforme et coordinateur éditorial), Elia Ditmann (développeur informatique), Vincent Lambert (chargé d'édition) et la contribution du Comité des revues pour la validation des 
    évolutions éditoriales et techniques.<br />
   </p>

   <h3>
      <a name="section5">Orientations</a>
   </h3>
   <p>
   Epi-Revel œuvre à la promotion et à la valorisation de la recherche universitaire niçoise par un affichage clair des 
   publications dans toutes les disciplines en offrant : <br \>
   <ul>
      <li><h4>- une visibilité élargie des résultats de la recherche, notamment à l'échelle internationale</h4></li>
      <li><h4>- une interactivité entre auteurs et lecteurs</h4></li>
      <li><h4>- une notoriété accrue pour les publications</h4></li>
   </ul>
   </p>

   <p>Epi-Revel, à l’instar de Revel, joue naturellement un rôle de pépinière assurant le développement des revues jusqu’à 
   leur migration sur une plate-forme nationale et contribue ainsi au maintien d’une expertise éditoriale et technique locale.</p>

   <p>
   Ce rôle est complémentaire de celui des plate-formes nationales. Une équipe dédiée est nécessairement plus opérationnelle pour 
   aider les nouvelles revues à progresser sur le plan éditorial.
   </p>

   <p>
   Impliquée dans l’initiation des jeunes chercheurs aux conditions de la publication scientifique, Epi-Revel envisage d'accueillir 
   d’autres types de publication (journées d’études, cahiers, etc.) et oeuvre activement au développement du dépôt institutionnel 
   d’archives ouvertes <a href="https://hal.univ-cotedazur.fr">HAL - Université Côte d'Azur</a>.
   </p>

   <h3>
      <a name="section2">Termes de la collaboration avec les revues</a>
   </h3>
   <p>
   Le site Epi-Revel respecte l'identité des revues et des colloques et les standards de la publication savante 
   internationale. Le SCD assure l'ergonomie de l'interface de publication, le développement du site, et la formation 
   des équipes éditoriales à l'outil d'édition en ligne.
   </p>
   <p>
   Dans le cadre d'un engagement mutuel, les équipes éditoriales se chargent elles-mêmes de la publication des textes 
   garantissant ainsi l'indépendance de leur politique éditoriale et le choix de leur scénario d'édition. Elles s'engagent 
   à respecter une périodicité annoncée ainsi que l'affichage d'un minimum requis d'informations (comités scientifique et 
   de lecture, politique éditoriale, coordonnées, métadonnées...).
   </p>

</div>

</div> <!--  concept  -->


<!-- ================================  CREDIT / CONTACT  ================================ -->

<div class="credits_contacts">

<h1>Pour nous contacter</h1>
<div class="credit_contact">
   <div class="contact_center">
      <ul>
         <li><h4>
               Courriel : <a href="mailto:bibliotheques-publications@univ-cotedazur.fr">bibliotheques-publications@univ-cotedazur.fr</a>
            </h4></li>
         <li><h4>Téléphone : 04 89 15 13 11	</h4></li>
         <li><h3>Adresse :</h3>
            <ul>
               <li><h4>Service Commun de la Documentation</h4></li>
               <li><h4>Universit&eacute C&ocirc;te d'Azur</h4></li>
               <li><h4>28, avenue Valrose</h4></li>
               <li><h4>Parc Valrose - BP 2053</h4></li>
               <li><h4>06101 Nice cedex 2</h4></li>
            </ul></li>
      </ul>
   </div>
   <div class="clear"></div>
</div>

<h1>L'équipe actuelle</h1>
<div class="credit_contact">
   <ul>
      <li><h3>Sarah Hurter-Savie :</h3>&nbsp;&nbsp;<h4>Directrice du SCD</h4></li>
      <li><h3>Ghislain Chave :</h3>&nbsp;&nbsp;<h4>Responsable, coordination éditoriale et administrateur de la plateforme</h4></li>
      <li><h3>Elia Ditmann :</h3>&nbsp;&nbsp;<h4>Développeur</h4></li>
	  <li><h3>Vincent Lambert :</h3>&nbsp;&nbsp;<h4>Chargé d'édition</h4></li>
   </ul>
</div>

<h1>L'établissement support</h1>
<div class="credit_contact">
   <div class="support_center">
      <p>
        Service Commun de la Documentation
      </p>
      <p>Bibliothèque de Sciences, Parc Valrose</p>
   </div>
   <div class="clear"></div>
</div>

<h1>Les contributeurs d'Epi-Revel' depuis sa création</h1>
<div class="credit_contact">
   <ul>
    <li><h3>Ghislain Chave - </h3>&nbsp;&nbsp;<h4>Concepteur, coordinateur éditorial et administrateur du portail : 2003-</h4></li>
    <li><h3>Elia Ditmann - </h3>&nbsp;&nbsp;<h4>Développeur : 2018-</h4></li>
    <li><h3>Fran&ccedil;ois Gherabi - </h3>&nbsp;&nbsp;<h4>Responsable technique et développeur : 2018-2021</h4></li>
    <li><h3>Cédric Lefevre -</h3>&nbsp;&nbsp;<h4>Développeur : 2017</h4></li>
   </ul>
</div>

</div> <!-- credits_contacts -->



<!-- ============================== PUBLICATIONS PRINCIPALES ====================================-->

<div class="public_site">
   <div style='text-align : center;'>
      <button type="button" class="button" style="padding: 10px 28px;" onclick="TypeRevue()">Revues</button>
      <button type="button" class="button" style="padding: 10px 28px;" onclick="TypeColloque()">Colloques</button>
      <!-- <button type="button" class="button" style="padding: 10px 28px;" onclick="TypeCahier()">Cahiers</button> -->
   </div>
   <ul class="intro" style="padding-left: 0px";>
      <?php foreach ($arrayCollections as $rootCollection) :
         $rootTitle = $rootCollection['name'];
         $rootId = $rootCollection['id'];
         set_current_record('collection', get_record_by_id('collection', $rootId));
         $rootDate = metadata('collection', array('Dublin Core', 'Date'));?>
         <?php if(($rootTitle != 'Actualités') && ($rootTitle != 'Index') && ($rootTitle != 'Présentation')&&($rootTitle != 'Actualités accueil')) : ?>
            <?php foreach(metadata('collection', array('Dublin Core', 'Type'), array('all' => true)) as $type) : ?>
               <?php $classTitle = "none";
               switch(strtolower($type)){
                  case 'revue' :
                     $classTitle = "titre";
                     break;
                  case 'colloque':
                     $classTitle = "titre2";
                     break;
                  case "cahier":
                     $classTitle = "titre3";
                     break;
               }
               ?>
               <li class=<?php echo $classTitle;?>>
                  <div>
                     <h3>
                        <span><img src="https://epi-revel.univ-cotedazur.fr/themes/berlin/images/pucerevel.gif"></span>
                        <a href="#"><?php echo link_to_collection($rootTitle, array(), 'show', $collectionTable->find($rootId)); ?></a>
                     </h3>
                     <h4> <?php if(metadata('collection', array('Dublin Core', 'Subject'))) {echo " | " .  metadata('collection', array('Dublin Core', 'Subject')); }?></h4>
                  </div>
                  <ul style="padding-left: 0px";>
                     <li class="desc">
                        <p><?php echo short_desc(metadata('collection', array('Dublin Core', 'Description'))) ?></p>
                     </li>
                     <!--<li class="date"><?php echo "mis à jour le " . $rootDate; ?></li> -->
                  </ul> 
               </li>
            <?php endforeach?>
         <?php  endif ?>
      <?php endforeach ?>
    </ul>
</div>

<?php echo foot(); ?>
