<?php
/**
 * Widget Form
 *
 * Builds out the html for the widget settings form.
 *
 * @uses Advanced_Categories_Widget_Fields::build_field_{name-of-field}() to generate the individual form fields.
 * @uses Advanced_Categories_Widget_Fields::load_fieldset() to output the actual fieldsets.
 * @uses Advanced_Categories_Widget_Fields::build_section_header() to output the header for each form section.
 *
 * @package Advanced_Categories_Widget
 *
 * @since 1.0
 */
?>

<div class="widgin-widget-form">

	<div class="widgin-section">

		<?php echo Advanced_Categories_Widget_Fields::build_section_header( $fieldset = 'general', $title = 'General Settings', $instance, $this ); ?>

		<fieldset data-fieldset-id="general" class="widgin-settings widgin-fieldset settings-general">

			<legend class="screen-reader-text"><span><?php _e('General Settings') ?></span></legend>

			<?php
			$_general_fields =  array(
				'title'     => Advanced_Categories_Widget_Fields::build_field_title( $instance, $this ),
				'orderby'   => Advanced_Categories_Widget_Fields::build_field_orderby( $instance, $this ),
				'order'     => Advanced_Categories_Widget_Fields::build_field_order( $instance, $this ),
			);
			$general_fields = apply_filters( 'acatw_form_fields_general', $_general_fields, $instance, $this );

			Advanced_Categories_Widget_Fields::load_fieldset( 'general', $general_fields, $instance, $this );
			?>

		</fieldset>

	</div><!-- /.widgin-section -->


	<div class="widgin-section">

		<?php echo Advanced_Categories_Widget_Fields::build_section_header( $fieldset = 'filters', $title = 'Filters', $instance, $this ); ?>

		<fieldset data-fieldset-id="filters" class="widgin-settings widgin-fieldset settings-filters">

			<legend class="screen-reader-text"><span><?php _e('Filters') ?></span></legend>

			<?php
			$_intro = __( 'Use the following fields to limit your list to certain categories.' );
			$intro = apply_filters( 'acatw_intro_text_filters', $_intro );
			?>

			<div class="description widgin-description">
				<?php echo wpautop( $intro ); ?>
			</div>

			<?php
			$_filters_fields =  array(
				'tax_term' => Advanced_Categories_Widget_Fields::build_field_tax_term( $instance, $this ),
			);
			$filters_fields = apply_filters( 'acatw_form_fields_filters', $_filters_fields, $instance, $this );

			Advanced_Categories_Widget_Fields::load_fieldset( 'filters', $filters_fields, $instance, $this );
			?>

		</fieldset>

	</div><!-- /.widgin-section -->


	<div class="widgin-section">

		<?php echo Advanced_Categories_Widget_Fields::build_section_header( $fieldset = 'thumbnails', $title = 'Category Thumbnail', $instance, $this ); ?>

		<fieldset data-fieldset-id="thumbnails" class="widgin-settings widgin-fieldset settings-thumbnails">

			<legend class="screen-reader-text"><span><?php _e('Category Thumbnail') ?></span></legend>

			<?php $thumb_compatible = Advanced_Categories_Widget_Utils::is_category_thumbnail_compatible(); ?>

			<?php if( $thumb_compatible ) : ?>

				<?php
				$_intro = __( "If you choose to display a thumbnail of each category&#8217;s featured image, you can either select from an image size already registered with your site, or set a custom size." );
				$intro = apply_filters( 'acatw_intro_text_thumbnails', $_intro );
				?>

				<div class="description widgin-description">
					<?php echo wpautop( $intro ); ?>
				</div>

				<?php
				$_thumbnail_fields =  array(
					'show_thumb'   => Advanced_Categories_Widget_Fields::build_field_show_thumb( $instance, $this ),
					'thumb_size'   => Advanced_Categories_Widget_Fields::build_field_thumb_size( $instance, $this ),
					'thumb_custom' => Advanced_Categories_Widget_Fields::build_field_thumb_custom( $instance, $this ),
				);
				$thumbnail_fields = apply_filters( 'acatw_form_fields_thumbnails', $_thumbnail_fields, $instance, $this );

				Advanced_Categories_Widget_Fields::load_fieldset( 'thumbnails', $thumbnail_fields, $instance, $this );
				?>

			<?php else : ?>

				<?php
				$_install_url = add_query_arg( 's', 'Advanced+Term+Images', admin_url( 'plugin-install.php?tab=search' ) );
				$_intro = sprintf( 'The Advanced Categories Widget is compatible with the <b>%1$s</b> plugin to display featured images for category terms.  It appears the <b>%1$s</b> plugin is not installed on your site.  Please install this plugin to enable compatibility.',
					sprintf( '<a href="%1$s">%2$s</a>',
						esc_url( $_install_url ),
						'Advanced Term Images'
					)
				); ?>

				<div class="description widgin-description">
					<?php echo wpautop( $_intro ); ?>
				</div>

			<?php endif; ?>

		</fieldset>

	</div><!-- /.widgin-section -->


	<div class="widgin-section">

		<?php echo Advanced_Categories_Widget_Fields::build_section_header( $fieldset = 'excerpts', $title = 'Category Description', $instance, $this ); ?>

		<fieldset data-fieldset-id="excerpts" class="widgin-settings widgin-fieldset settings-excerpts">

			<legend class="screen-reader-text"><span><?php _e('Category Description') ?></span></legend>

			<?php
			$_excerpt_fields =  array(
				'show_desc'   => Advanced_Categories_Widget_Fields::build_field_show_desc( $instance, $this ),
				'desc_length' => Advanced_Categories_Widget_Fields::build_field_desc_length( $instance, $this ),
			);
			$excerpt_fields = apply_filters( 'acatw_form_fields_excerpts', $_excerpt_fields, $instance, $this );

			Advanced_Categories_Widget_Fields::load_fieldset( 'excerpts', $excerpt_fields, $instance, $this );
			?>
		</fieldset>

	</div><!-- /.widgin-section -->


	<div class="widgin-section">

		<?php echo Advanced_Categories_Widget_Fields::build_section_header( $fieldset = 'meta', $title = 'Category Meta', $instance, $this ); ?>

		<fieldset data-fieldset-id="meta" class="widgin-settings widgin-fieldset settings-meta">

			<legend class="screen-reader-text"><span><?php _e('Category Meta') ?></span></legend>

			<?php
			$_excerpt_fields =  array(
				'show_count'  => Advanced_Categories_Widget_Fields::build_field_show_count( $instance, $this ),
			);
			$excerpt_fields = apply_filters( 'acatw_form_fields_meta', $_excerpt_fields, $instance, $this );

			Advanced_Categories_Widget_Fields::load_fieldset( 'meta', $excerpt_fields, $instance, $this );
			?>
		</fieldset>

	</div><!-- /.widgin-section -->


	<div class="widgin-section">

		<?php echo Advanced_Categories_Widget_Fields::build_section_header( $fieldset = 'format', $title = 'Format', $instance, $this ); ?>

		<fieldset data-fieldset-id="format" class="widgin-settings widgin-fieldset settings-format">

			<legend class="screen-reader-text"><span><?php _e('Format') ?></span></legend>

			<?php
			$_format_fields =  array(
				'list_style'  => Advanced_Categories_Widget_Fields::build_field_list_style( $instance, $this ),
			);
			$format_fields = apply_filters( 'acatw_form_fields_format', $_format_fields, $instance, $this );

			Advanced_Categories_Widget_Fields::load_fieldset( 'format', $format_fields, $instance, $this );
			?>
		</fieldset>

	</div><!-- /.widgin-section -->


	<div class="widgin-section">

		<?php echo Advanced_Categories_Widget_Fields::build_section_header( $fieldset = 'layout', $title = 'Styles & Layout', $instance, $this ); ?>

		<fieldset data-fieldset-id="layout" class="widgin-settings widgin-fieldset settings-layout">

			<legend class="screen-reader-text"><span><?php _e('Styles & Layout') ?></span></legend>

			<?php
			$_intro = __( 'Selecting the Default Styles option below will give you a quick start to styling your category widget.  Additionally, the widget has a number of classes available to further customize its appearance to match your theme.' );
			$intro = apply_filters( 'acatw_intro_text_layout', $_intro );
			?>

			<div class="description widgin-description">
				<?php echo wpautop( $intro ); ?>
			</div>

			<?php
			$_layout_fields =  array(
				'css_default' => Advanced_Categories_Widget_Fields::build_field_css_default( $instance, $this ),
			);
			$layout_fields = apply_filters( 'acatw_form_fields_layout', $_layout_fields, $instance, $this );

			Advanced_Categories_Widget_Fields::load_fieldset( 'layout', $layout_fields, $instance, $this );
			?>
		</fieldset>

	</div><!-- /.widgin-section -->

</div>