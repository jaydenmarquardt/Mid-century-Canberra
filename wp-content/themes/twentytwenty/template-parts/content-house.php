<?php
/**
 * The default template for displaying content
 *
 * Used for both singular and index.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage Twenty_Twenty
 * @since Twenty Twenty 1.0
 */
$entry_header_classes = "";

global $post;

$address = get_field("address", $post);
$beds = get_field("bedrooms", $post);
$baths = get_field("bathrooms", $post);
$land = get_field("land", $post);
$description = get_field("description", $post);
$source = get_field("source", $post);
$significance = get_field("significance", $post);
$acknowledgements = get_field("acknowledgements", $post);
$price = number_format(get_field("value", $post));
$timeline = get_field("time_frame", $post);
$age = explode(" - ", $time_line);
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


?>

<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">

    <header class="entry-header has-text-align-center<?php echo esc_attr( $entry_header_classes ); ?>">

        <div class="entry-header-inner section-inner medium">

            <?php
            /**
             * Allow child themes and plugins to filter the display of the categories in the entry header.
             *
             * @since Twenty Twenty 1.0
             *
             * @param bool   Whether to show the categories in header, Default true.
             */
            $show_categories = apply_filters( 'twentytwenty_show_categories_in_entry_header', true );

            if ( true === $show_categories && has_category() ) {
                ?>

                <div class="entry-categories">
                    <span class="screen-reader-text"><?php _e( 'Categories', 'twentytwenty' ); ?></span>
                    <div class="entry-categories-inner">
                        <?php the_category( ' ' ); ?>
                    </div><!-- .entry-categories-inner -->
                </div><!-- .entry-categories -->

                <?php
            }

            if ( is_singular() ) {
                the_title( '<h1 class="entry-title">', '</h1>' );
            } else {
                the_title( '<h2 class="entry-title heading-size-1"><a href="' . esc_url( get_permalink() ) . '">', '</a></h2>' );
            }




            ?>

            <div class="info uk-flex uk-flex-wrap uk-flex-between uk-flex-center " style="margin:20px auto;max-width: 50% ;color:white;">
                <div title="Price">
                    <i class="fas fa-dollar-sign"></i>
                    <?php echo $price;?>
                </div>
                <div title="Age">
                    <i class="fas fa-hourglass"></i>
                    <?php echo $age;?> - <?php echo $timeline;?>
                </div>
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
        </div><!-- .entry-header-inner -->

    </header><!-- .entry-header -->


    <div class="post-inner <?php echo is_page_template( 'templates/template-full-width.php' ) ? '' : 'thin'; ?> ">


		<div class="entry-content">
            <div class="uk-position-relative full-gal" tabindex="-1" uk-slideshow>

                <ul class="uk-slideshow-items">
                    <?php foreach ($gallery as $gal):?>
                        <li>
                            <div class="gal-image-full  <?php echo JM_UTIL::has_image($post->ID) ? "" : "contain";?>" style="background-image: url(<?php echo is_array( $gal) ? $gal["url"] : $gal;?>)">  </div>
                        </li>
                    <?php endforeach;?>

                </ul>

                <a class="uk-position-bottom-left uk-position-small" href="#" uk-slidenav-previous uk-slideshow-item="previous"></a>
                <a class="uk-position-bottom-right uk-position-small " href="#" uk-slidenav-next uk-slideshow-item="next"></a>

            </div>

            <br>

			<?php
			if ( is_search() || ! is_singular() && 'summary' === get_theme_mod( 'blog_content', 'full' ) ) {
				the_excerpt();
			} else {
				the_content( __( 'Continue reading', 'twentytwenty' ) );
			}
			?>

            <?php if($description):?><h2>Description</h2><p><?php echo $description;?></p><?php endif;?>
            <?php if($significance):?><h2>Significance</h2><p><?php echo $significance;?></p><?php endif;?>
            <?php if($acknowledgements):?><h2>Acknowledgements</h2><p><?php echo $acknowledgements;?></p><?php endif;?>
            <?php if($source):?><h2>Source</h2><p><?php echo $source;?></p><?php endif;?>

		</div><!-- .entry-content -->

	</div><!-- .post-inner -->

	<div class="section-inner">
		<?php

		?>

	</div><!-- .section-inner -->

	<?php

	

	/**
	 *  Output comments wrapper if it's a post, or if comments are open,
	 * or if there's a comment number – and check for password.
	 * */
	if ( ( is_single() || is_page() ) && ( comments_open() || get_comments_number() ) && ! post_password_required() ) {
		?>

		<div class="comments-wrapper section-inner">

			<?php comments_template(); ?>

		</div><!-- .comments-wrapper -->

		<?php
	}
	?>

</article><!-- .post -->
