<?php

global $wp_query;

$archive_title = sprintf(
    '%1$s %2$s',
    '<span class="color-accent">' . __( 'Search:', 'twentytwenty' ) . '</span>',
    '&ldquo;' . get_search_query() . '&rdquo;'
);

if ( $wp_query->found_posts ) {
    $archive_subtitle = sprintf(
    /* translators: %s: Number of search results. */
        _n(
            'We found %s result for your search.',
            'We found %s results for your search.',
            $wp_query->found_posts,
            'twentytwenty'
        ),
        number_format_i18n( $wp_query->found_posts )
    );
} else {
    $archive_subtitle = __( 'We could not find any results for your search. You can give it another try through the search form below.', 'twentytwenty' );
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
echo "<div class='uk-container uk-container-center uk-margin-medium'>";

if ( have_posts() ) {

    $i = 0;

    echo "<div class='uk-child-width-1-3@m uk-child-width-1-1@s uk-grid-margin' uk-grid  uk-height-match=\"target: .post-base > .content;\">";
    while ( have_posts() ) {
        $i++;
        the_post();

        get_template_part( 'template-parts/post', get_post_type() );

    }
    echo '</div>';
} else{
    ?>

    <div class="no-search-results-form section-inner thin">

        <?php
        get_search_form(
            array(
                'label' => __( 'search again', 'twentytwenty' ),
            )
        );
        ?>

    </div>

    <?php
}
?>

<?php get_template_part( 'template-parts/pagination' );


echo '</div>';


?>


