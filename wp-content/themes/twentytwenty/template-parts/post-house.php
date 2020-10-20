<?php
global $post;

$address = get_field("address", $post);
$beds = get_field("bedrooms", $post);
$baths = get_field("bathrooms", $post);
$land = get_field("land", $post);
$description = $description ? wp_trim_words(get_field("description", $post), 20) : get_the_excerpt($post->ID);
$price = number_format(get_field("value", $post));
$age = explode(" - ", get_field("time_frame", $post));
$age = intval(date("Y"))-intval($age[1]);

$gallery = get_field("gallery", $post);
$gallery = array_splice($gallery, 0, 3);

if(!$gallery)
{
    $gallery = [];
}
if(!count($gallery))
{

    $gallery[] = JM_UTIL::get_image($post->ID, "post-thumb");
}

global $house_to_text;
$link = get_the_permalink();
if(is_admin())$link="#";
global $is_loacate;
if(!$house_to_text)$house_to_text = "View House";
?>


<article <?php post_class(); ?> id="post-<?php the_ID(); ?>" >

   <div class="post-base post-house" >

       <div class="uk-position-relative" tabindex="-1" uk-slideshow>

           <ul class="uk-slideshow-items">
               <?php foreach ($gallery as $gal):?>
                   <li>
                       <div class="gal-image  <?php echo JM_UTIL::has_image($post->ID) ? "" : "contain";?>" style="background-image: url(<?php echo is_array( $gal) ? $gal["sizes"]["post-thumb"] : $gal;?>)">  </div>
                   </li>
               <?php endforeach;?>

           </ul>
           <div class="age"> <i class="fas fa-hourglass"></i> <?php echo $age;?> yrs</div>
           <div class="price"> <i class="fas fa-dollar-sign"></i> <?php echo $price;?> </div>
           <a class="uk-position-bottom-left uk-position-small" href="#" uk-slidenav-previous uk-slideshow-item="previous"></a>
           <a class="uk-position-bottom-right uk-position-small " href="#" uk-slidenav-next uk-slideshow-item="next"></a>

       </div>
       <div class="content">
           <h3><?php echo get_the_title();?></h3>
           <?php if(!$is_loacate):?><p class="desc"><?php echo $description;?></p><?php endif;;?>
           <p> <i class="fas fa-map-marker" style="margin-right: 10px"> </i> <?php echo $address["address"];?></p>
           <div class="info">
               <div title="Bedrooms">
                   <i class="fas fa-bed"></i>
                   <?php echo $beds;?>
               </div>
               <div title="Bathrooms">
                   <i class="fas fa-bath"></i>
                   <?php echo $baths;?>
               </div>
               <div title="Land size">
                   <i class="fas fa-ruler-combined"></i>
                   <?php echo $land;?>
               </div>
           </div>
       </div>
       <a class="big-clicker" href="<?php echo $link;?>"></a>
       <div class="cta">

           <a class="button" href="<?php echo $link;?>"><?php echo $house_to_text;?></a>

       </div>


   </div>


</article>
