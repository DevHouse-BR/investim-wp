
<?php if( isset( $fields[ $field ]['data'] ) && is_array( $fields[ $field ]['data'] ) ) : ?>

<div class="listings-search-field listings-search-field-<?php echo esc_attr( $fields[ $field ]['type'] ); ?> listings-search-field-<?php echo esc_attr( $field ); ?> <?php echo esc_attr( $class ); ?>">
	
	<?php if( ! empty( $fields[ $field ]['label'] ) ) : ?>
	<label class="checkboxgroup" for="<?php echo esc_attr( $field ); ?>"><?php echo esc_attr( $fields[ $field ]['label'] ); ?></label>
	<?php endif; ?>
	
	<?php foreach( $fields[ $field ]['data'] as $k => $v ) : ?>	
		<?php
			if( is_array( $field_value ) ) {
				$field_option_key = array_search( $k, $field_value );
				$field_option_value = $field_option_key !== false ? $field_value[ $field_option_key ] : false;
			} else {
				$field_option_value = $field_value;
			}
		?>
		<div class="checkbox checkbox-primary">
			<input type="checkbox" id="<?php echo esc_attr( $field ); ?>-<?php echo esc_attr( $k ); ?>" name="<?php echo esc_attr( $field ); ?>[<?php echo esc_attr( $k ); ?>]" value="<?php echo esc_attr( $k ); ?>"<?php checked( $k, $field_option_value ); ?> class="styled">
			<label for="<?php echo esc_attr( $field ); ?>-<?php echo esc_attr( $k ); ?>"><?php echo esc_attr( $v ); ?></label>
		</div>
	<?php endforeach; ?>

</div><!-- .listings-search-field-<?php echo esc_attr( $field ); ?> -->

<?php endif; ?>
