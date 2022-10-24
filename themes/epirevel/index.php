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
	<script type='text/javascript' src='<?php echo WEB_ROOT;?>/themes/epirevel/javascripts/jquery-2.1.0.min.js'></script>
   <script type='text/javascript' src='<?php echo WEB_ROOT;?>/themes/epirevel/javascripts/epirevel.js'></script>

<?php echo head(array('bodyid'=>'home', 'bodyclass' =>'two-col')); ?>

<!-- ============================== PUBLICATIONS PRINCIPALES ====================================-->
<div class="categories">
   <button type="button" class="btn btn-info-big" onclick="TypeRevue()" autofocus><?php echo autre_traduction('Revues');?></button>
   <button type="button" class="btn btn-info-big"  onclick="TypeColloque()"><?php echo autre_traduction('Colloques');?></button>
   <!-- <button type="button" class="button" style="padding: 10px 28px;" onclick="TypeCahier()">Cahiers</button> -->
</div>

<div class="public_site">
   <ul class="intro" style="padding-left: 0px";>
      <div class="row">
         <?php foreach ($arrayCollections as $rootCollection) :
            $rootTitle = $rootCollection['name'];
            $rootId = $rootCollection['id'];
            set_current_record('collection', get_record_by_id('collection', $rootId));
            $rootDate = metadata('collection', array('Dublin Core', 'Date'));
            $rootTypes = metadata('collection', array('Dublin Core', 'Type'), array('all' => true));?>
            <?php if(($rootTitle != 'Actualités') && ($rootTitle != 'Index') && ($rootTitle != 'Présentation') && ($rootTitle != 'Actualités accueil') && $rootTitle && $rootTypes) : ?>
               <?php foreach($rootTypes as $type) : ?>
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
               <?php endforeach?>
               <div class="col-md-6 <?php echo $classTitle;?>">
                  <li class=<?php echo $classTitle;?>>
                     <div class="intro_img">
                     <?php $cover = metadata('collection', array('Dublin Core', 'Coverage'));
                        if($cover) : ?>
                        <a href="<?php echo $cover;?>" style="white-space:inherit; float: left; margin-bottom: 10px;"><?php echo ancestor_header_image($rootId);?></a>
                        <?php else : ?>
                           <a href="<?php echo WEB_ROOT;?>/collections/show/<?php echo $rootId ;?>" style="white-space:inherit; float: left; margin-bottom: 10px;"><?php echo ancestor_header_image($rootId);?></a>
                        <?php endif;?>
                        <div class="intro_description">
                           <h3><a href="#"><?php echo link_to_collection($rootTitle, array(), 'show', $collectionTable->find($rootId)); ?></a></h3>
                           <h4><?php if(metadata('collection', array('Dublin Core', 'Subject'))) {echo metadata('collection', array('Dublin Core', 'Subject')); }?></h4>
                        </div>
                     </div>
                     <ul style="padding-left: 0px";>
                        <li class="desc">
                           <p><?php echo short_desc(metadata('collection', array('Dublin Core', 'Description')));?></p>
                        </li>
                     </ul> 
                  </li>  
               </div>
            <?php  endif ?>
         <?php endforeach ?>
      </div>
   </ul>
</div>

<?php echo foot(); ?>
