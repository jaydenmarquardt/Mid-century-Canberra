<?php
global $post;

$houses = get_field("houses", $post);
$age = get_field("dob", $post);
$age = intval(date("Y"))-intval($age[1]);


global $architect_to_text;
$link = get_the_permalink();
if(is_admin())$link="#";

if(!$architect_to_text)$architect_to_text = "View Architect";
?>


<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">

    <div class="post-base post-architect">

        <div class="image  uk-position-relative <?php echo JM_UTIL::has_image(get_the_ID()) ? "" : "contain";?>" style="background-image: url(<?php echo JM_UTIL::get_image(get_the_ID());?>)">

            <div class="age"> <i class="fas fa-hourglass"></i> <?php echo $age;?> yrs</div>

        </div>
        <div class="content">
            <h3><?php echo get_the_title();?></h3>
            <p><?php echo wp_trim_words(get_the_content(), 100);?></p>
            <div title="Houses">
                <i class="fas fa-home"></i>
                <?php echo count($houses);?>
            </div>
            <div class="houses">

            </div>
        </div>
        <a class="big-clicker" href="<?php echo $link;?>"></a>
        <div class="cta">

            <a class="button" href="<?php echo $link;?>"><?php echo $architect_to_text;?></a>

        </div>

    </div>


</article>
