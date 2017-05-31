<?php
/**
 * Header template.
 *
 * @package WPCasa Madrid
 */
global $post, $wpsight_query; ?>

<header class="site-header site-header-archive_title" role="banner" itemscope itemtype="http://schema.org/WPHeader">
	<div class="site-header-inner">
		
		<div class="header-title">
			<div class="container">
			
				<div class="header-title-inner">
				
					<div class="header-title-title">
						<?php the_archive_title( '<h1 class="page-title">', '</h1>' ); ?>
						<?php the_archive_description( '<div class="taxonomy-description">', '</div>' ); ?>
					</div>
					
					<div class="header-title-right">				
						<?php wpsight_orderby(); ?>
					</div>
				
				</div>

			</div>
		</div><!-- .header-title -->

	</div><!-- .site-header-inner -->
</header><!-- .site-header -->

<?php get_template_part( 'template-parts/header', 'widgets' ); ?>
