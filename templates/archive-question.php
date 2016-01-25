<?php
/**
 * The template for displaying question archive pages
 *
 * @package DW Question & Answer
 * @since DW Question & Answer 1.4.0
 */
?>
<div class="dwqa-questions-archive">
	<?php do_action( 'dwqa_before_questions_archive' ) ?>
		<div class="dwqa-questions-list">
		<?php do_action( 'dwqa_before_questions_list' ) ?>
		<?php if ( have_posts() ) : ?>
			<?php while ( have_posts() ) : the_post(); ?>
				<?php dwqa_load_template( 'content', 'question' ) ?>
			<?php endwhile; ?>
			<?php the_posts_pagination( array( 'mid_size' => 4 ) ); ?>
		<?php else : ?>
			<?php dwqa_load_template( 'content', 'none' ) ?>
		<?php endif; ?>
		<?php do_action( 'dwqa_after_questions_list' ) ?>
		</div>
	<?php do_action( 'dwqa_after_questions_archive' ); ?>
</div>