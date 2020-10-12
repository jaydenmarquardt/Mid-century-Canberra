<?php
$archive_title    = '';
$archive_subtitle = '';

if(! have_posts() ) {
    $archive_title = __( 'Nothing Found', 'twentytwenty' );
} elseif ( ! is_home() ) {
    $archive_title    = get_the_archive_title();
    $archive_subtitle = get_the_archive_description();
}

if ( $archive_title || $archive_subtitle ) {
    ?>

    <header class="archive-header has-text-align-center header-footer-group">

        <div class="archive-header-inner section-inner medium">

            <?php if ( $archive_title ) { ?>
                <h1 class="archive-title"><?php echo wp_kses_post( $archive_title ); ?></h1>
            <?php } ?>

            <?php if ( $archive_subtitle ) { ?>
                <div class="archive-subtitle section-inner thin max-percentage intro-text"><?php echo wp_kses_post( wpautop( $archive_subtitle ) ); ?></div>
            <?php } ?>

        </div><!-- .archive-header-inner -->

    </header><!-- .archive-header -->

    <?php
}

if ( have_posts() ) {

    $i = 0;

    while ( have_posts() ) {
        $i++;

        the_post();

        get_template_part( 'template-parts/post', get_post_type() );

    }
}

get_template_part( 'template-parts/pagination' ); ?>