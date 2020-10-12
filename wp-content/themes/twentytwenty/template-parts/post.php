<?php
$link = get_the_permalink();
if(is_admin())$link="#";
?>


<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">

   <div class="post-base post-general">

       <div class="image <?php echo JM_UTIL::has_image(get_the_ID(), "post-thumb") ? "" : "contain";?>" style="background-image: url(<?php echo JM_UTIL::get_image(get_the_ID(), "post-thumb");?>)"> </div>
       <div class="content">
           <h3><?php echo get_the_title();?></h3>
           <p><?php echo get_the_excerpt();?></p>
       </div>
       <a class="big-clicker" href="<?php echo $link;?>"></a>
       <div class="cta">

           <a class="button" href="<?php echo $link;?>">Read More</a>

       </div>


   </div>


</article>
