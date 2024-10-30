<?php
/**
** A base module for the following types of tags:
** 	[calculator]  # calculator
**/

/* form_tag handler */

add_action( 'wpcf7_init', 'OCCF7CAL_add_form_tag_calculator', 10, 0 );

function OCCF7CAL_add_form_tag_calculator() {
	wpcf7_add_form_tag( array( 'calculator', 'calculator*' ),
		'OCCF7CAL_calculator_form_tag_handler', array( 'name-attr' => true) );
}

function OCCF7CAL_calculator_form_tag_handler( $tag ) {
	if ( empty( $tag->name ) ) {
		return '';
	}
	
	$validation_error = wpcf7_get_validation_error( $tag->name );
	$class = wpcf7_form_controls_class( $tag->type );
	$class .= ' wpcf7-validates-as-calculator';

	$atts = array();
	$atts['class'] = $tag->get_class_option( $class );
	$atts['id'] = $tag->get_id_option();
	$atts['readonly'] = 'readonly';
	
	if ( $tag->has_option( 'readonly' ) ) {
		$atts['readonly'] = 'readonly';
	}

	$value = (string) reset( $tag->values );

	if ( $tag->has_option( 'placeholder' ) or $tag->has_option( 'watermark' ) ) {
		$atts['placeholder'] = $value;
		$value = '';
	}

	$value = $tag->get_default_option( $value );
	$value = wpcf7_get_hangover( $tag->name, $value );
	

	
	
	
	$atts['class'] .= " occf7cal-total";

	$atts['value'] = 0;

	$atts['type'] = 'text';

	$atts['name'] = $tag->name;

	$atts['prefix'] = $tag->get_option( 'Prefix' )[0];

	$atts['precision'] = $tag->get_option( 'Precision' )[0];

	$atts = wpcf7_format_atts( $atts );

	
	$html = sprintf(
	'<span class="wpcf7-form-control-wrap %1$s"><input %2$s %4$s />%3$s</span>',
	sanitize_html_class( $tag->name ), $atts, $validation_error, 'data-formulas="'.$value.'"' );
	return $html;
}



/* Tag generator */

add_action( 'wpcf7_admin_init', 'OCCF7CAL_add_tag_generator_calculator', 18, 0 );

function OCCF7CAL_add_tag_generator_calculator() {
	$tag_generator = WPCF7_TagGenerator::get_instance();
	$tag_generator->add( 'calculator', __( 'calculator', 'contact-form-7' ),
		'OCCF7CAL_tag_generator_calculator' );
}

function OCCF7CAL_tag_generator_calculator( $contact_form, $args = '' ) {
	$args = wp_parse_args( $args, array() );
	$wpcf7_contact_form = WPCF7_ContactForm::get_current();
	$contact_form_tags = $wpcf7_contact_form->scan_form_tags();
	$type = 'calculator';

?>
<div class="control-box">
	<fieldset>
		<table class="form-table">
			<tbody>

				<tr>
					<th scope="row">
						<label for="<?php echo esc_attr( $args['content'] . '-name' ); ?>"><?php echo esc_html( __( 'Name', 'contact-form-7' ) ); ?>
						</label>
					</th>
					<td>
						<input type="text" name="name" class="tg-name oneline" id="<?php echo esc_attr( $args['content'] . '-name' ); ?>" />
					</td>
				</tr>

				<tr>
					<th scope="row">
						<label for="<?php echo esc_attr( $args['content'] . '-values' ); ?>"><?php echo esc_html( __( 'Formulas', 'contact-form-7' ) ); ?>
						</label>
					</th>
					<td>
							<?php 
							   $occf7cal_tag = array();
								foreach ($contact_form_tags as $contact_form_tag) {
									if ( $contact_form_tag['type'] == 'number' || $contact_form_tag['type'] == 'number*' || $contact_form_tag['type'] == 'radio' || $contact_form_tag['type'] == 'select' || $contact_form_tag['type'] == 'select*' || $contact_form_tag['type'] == 'text*' || $contact_form_tag['type'] == 'text' || $contact_form_tag['type'] == 'checkbox' || $contact_form_tag['type'] == 'checkbox*'){
										$occf7cal_tag[] = $contact_form_tag['name'];
									}
								} 
							?>
						<p><span><strong><u>Field Name</u></strong></span><br>	
						<?php echo implode(' , ', $occf7cal_tag); ?></p>
						<textarea rows="3" class="large-text code" name="values" id="<?php echo esc_attr( $args['content'] . '-values' ); ?>"></textarea> <br>
								<?php _e( 'Ex: ( number-253 + number-254 ) / 2 + radio-708 + checkbox-708', 'contact-form-7' ); ?> <br>
								<?php _e( 'Ex: sqrt(number-253) % number-254', 'contact-form-7' ); ?> <br>
								<?php _e( 'Ex: number-254 ** checkbox-708', 'contact-form-7' ); ?>
						<!-- <strong>number-253, number-254, radio-708, checkbox-708</strong> is name field -->
					</td>
				</tr>

				<tr>
					<th scope="row">
						<label for="<?php echo esc_attr( $args['content'] . '-id' ); ?>"><?php echo esc_html( __( 'Prefix', 'contact-form-7' ) ); ?>
						</label>
					</th>
					<td>
						<input type="text" name="Prefix" class="Prefixvalue oneline option" id="<?php echo esc_attr( $args['content'] . '-Prefix' ); ?>" />
					</td>
				</tr>

				<tr>
					<th scope="row">
						<label for="<?php echo esc_attr( $args['content'] . '-Precision' ); ?>"><?php echo esc_html( __( 'Result Precision (digits after decimal point)', 'contact-form-7' ) ); ?>
						</label>
					</th>
					<td>
						<input type="number" name="Precision" class="Precision oneline option" id="<?php echo esc_attr( $args['content'] . '-Precision' ); ?>"  min="0"/>
					</td>
				</tr>

				<tr>
					<th scope="row">
						<label for="<?php echo esc_attr( $args['content'] . '-id' ); ?>"><?php echo esc_html( __( 'Id attribute', 'contact-form-7' ) ); ?>
						</label>
					</th>
					<td>
						<input type="text" name="id" class="idvalue oneline option" id="<?php echo esc_attr( $args['content'] . '-id' ); ?>" />
					</td>
				</tr>

				<tr>
					<th scope="row">
						<label for="<?php echo esc_attr( $args['content'] . '-class' ); ?>"><?php echo esc_html( __( 'Class attribute', 'contact-form-7' ) ); ?>
						</label>
					</th>
					<td>
						<input type="text" name="class" class="classvalue oneline option" id="<?php echo esc_attr( $args['content'] . '-class' ); ?>" />
					</td>
				</tr>

			</tbody>
		</table>
	</fieldset>
</div>

<div class="insert-box">
	<input type="text" name="<?php echo $type; ?>" class="tag code" readonly="readonly" onfocus="this.select()" />

	<div class="submitbox">
	<input type="button" class="button button-primary insert-tag" value="<?php echo esc_attr( __( 'Insert Tag', 'contact-form-7' ) ); ?>" />
	</div>

	<br class="clear" />

	<p class="description mail-tag"><label for="<?php echo esc_attr( $args['content'] . '-mailtag' ); ?>"><?php echo sprintf( esc_html( __( "To use the value input through this field in a mail field, you need to insert the corresponding mail-tag (%s) into the field on the Mail tab.", 'contact-form-7' ) ), '<strong><span class="mail-tag"></span></strong>' ); ?><input type="text" class="mail-tag code hidden" readonly="readonly" id="<?php echo esc_attr( $args['content'] . '-mailtag' ); ?>" /></label></p>
</div>
<?php
}
