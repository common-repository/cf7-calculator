<?php
/**
** A base module for the following types of tags:
** 	[rangeslider]  # rangeslider
**/

/* form_tag handler */

add_action( 'wpcf7_init', 'OCCF7CAL_add_form_tag_calrangeslider', 10, 0 );

function OCCF7CAL_add_form_tag_calrangeslider() {
	wpcf7_add_form_tag( array( 'rangeslider', 'rangeslider*' ),
		'OCCF7CAL_rangeslider_form_tag_handler', array( 'name-attr' => true) );
}

function OCCF7CAL_rangeslider_form_tag_handler( $tag ) {
	if ( empty( $tag->name ) ) {
		return '';
	}
	
	$validation_error = wpcf7_get_validation_error( $tag->name );
	$class = wpcf7_form_controls_class( $tag->type );
	$class .= ' wpcf7-validates-as-rangeslider';
    $atts = array();
	$atts['class'] = $tag->get_class_option( $class );
	$atts['id'] = $tag->get_id_option();
	$atts['readonly'] = 'readonly';
	
	if ( $tag->has_option( 'readonly' ) ) {
		$atts['readonly'] = 'readonly';
	}

	// $value = (string) reset( $tag->values );

	// if ( $tag->has_option( 'placeholder' ) or $tag->has_option( 'watermark' ) ) {
	// 	$atts['placeholder'] = $value;
	// 	$value = '';
	// }

	// $value = $tag->get_default_option( $value );
	// $value = wpcf7_get_hangover( $tag->name, $value );
	 
     
	
	$atts['class'] .= " occf7cal_slider";

	$atts['value'] = $tag->get_option( 'min' )[0];

	$atts['type'] = 'hidden';

	$atts['name'] = $tag->name;

	//$atts['step'] = $tag->get_option( 'step' )[0];

	//$atts['min'] = $tag->get_option( 'min' )[0];

	//$atts['max'] = $tag->get_option( 'max' )[0];

    //$atts['prefix'] = $tag->get_option( 'Prefix' )[0];

	//$atts['prefixpos'] = $tag->get_option( 'calslider' )[0];
	
	//$atts['caltoltip'] = $tag->get_option( 'caltoltip' )[0];
  
    //$atts['color'] = $tag->get_option( 'color' )[0];
        
	$atts = wpcf7_format_atts( $atts );

	$attsa['step'] = $tag->get_option( 'step' )[0];

	$attsa['min'] = $tag->get_option( 'min' )[0];

	$attsa['max'] = $tag->get_option( 'max' )[0];

    $attsa['prefix'] = $tag->get_option( 'Prefix' )[0];

	$attsa['prefixpos'] = $tag->get_option( 'calslider' )[0];
	
	$attsa['caltoltip'] = $tag->get_option( 'caltoltip' )[0];
  
    $attsa['color'] = $tag->get_option( 'color' )[0];

    $attsa = wpcf7_format_atts( $attsa );

	$html = sprintf(
	'<div class="occf7cal_slider_div" id="occf7cal_slider_div" %4$s><span class="wpcf7-form-control-wrap %1$s"><input %2$s />%3$s</span></div>',
	sanitize_html_class( $tag->name ), $atts, $validation_error, $attsa);
	return $html;
} 
add_action( 'wpcf7_admin_init', 'OCCF7CAL_add_tag_generator_rangeslider', 18, 0 );

function OCCF7CAL_add_tag_generator_rangeslider() {
	$tag_generator = WPCF7_TagGenerator::get_instance();
	$tag_generator->add( 'rangeslider', __( 'rangeslider', 'contact-form-7' ),
		'OCCF7CAL_tag_generator_rangeslider' );
}

function OCCF7CAL_tag_generator_rangeslider( $contact_form, $args = '' ) {
	$args = wp_parse_args( $args, array() );
	$wpcf7_contact_form = WPCF7_ContactForm::get_current();
	$contact_form_tags = $wpcf7_contact_form->scan_form_tags();
	$type = 'rangeslider';

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
						<label for="<?php echo esc_attr( $args['content'] . '-step' ); ?>"><?php echo esc_html( __( 'Step', 'contact-form-7' ) ); ?>
						</label>
					</th>
					<td>
						<input type="number" name="step" min="1" value="1" class="stepvalue oneline option" id="<?php echo esc_attr( $args['content'] . '-step' ); ?>" />
					</td>
				</tr>
				<tr>
					<th scope="row"><?php echo esc_html( __( 'Range', 'contact-form-7' ) ); ?></th>
						<td>
							<fieldset>
							<legend class="screen-reader-text"><?php echo esc_html( __( 'Range', 'contact-form-7' ) ); ?></legend>
							<label>
							<?php echo esc_html( __( 'Min', 'contact-form-7' ) ); ?>
							<input type="number" name="min" min="1" value="1" class="numeric option" />
							</label>
							&ndash;
							<label>
							<?php echo esc_html( __( 'Max', 'contact-form-7' ) ); ?>
							<input type="number" name="max" min="1" value="100" class="numeric option" />
							</label>
							</fieldset>
						</td>
				</tr>

				<tr>
					<th scope="row">
						<label for="<?php echo esc_attr( $args['content'] . '-prefix' ); ?>"><?php echo esc_html( __( 'Prefix', 'contact-form-7' ) ); ?>
						</label>
					</th>
					<td>
						<input type="text" name="Prefix" class="Prefixvalue oneline option" id="<?php echo esc_attr( $args['content'] . '-Prefix' ); ?>" />
						<?php echo esc_html( __( 'Use this prefix of the value', 'contact-form-7' ) ); ?>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="<?php echo esc_attr( $args['content'] . '-prefixposition' ); ?>">
							<?php echo esc_html( __( 'Prefix Position', 'contact-form-7' ) ); ?>
						</label>
					</th>
					<td>
						<label><input type="radio" name="calslider" value="left" class="option" checked="checked" /> <?php echo esc_html( __( 'left', 'contact-form-7' ) ); ?></label>
						<label><input type="radio" name="calslider" value="right" class="option" /> <?php echo esc_html( __( 'right', 'contact-form-7' ) ); ?></label>
						
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="<?php echo esc_attr( $args['content'] . '-color' ); ?>"><?php echo esc_html( __( 'color', 'contact-form-7' ) ); ?>
						</label>
					</th>
					<td>
					<input type="color" name="color" class="oneline option" id="<?php echo esc_attr( $args['content'] . '-color' ); ?>" />
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="<?php echo esc_attr( $args['content'] . '-tooltipposition' ); ?>">
							<?php echo esc_html( __( 'Tooltip Position', 'contact-form-7' ) ); ?>
						</label>
					</th>
					<td>
						<label><input type="radio" name="caltoltip" value="left" class="option" /> <?php echo esc_html( __( 'left', 'contact-form-7' ) ); ?></label>
						<label><input type="radio" name="caltoltip" value="right" class="option" /> <?php echo esc_html( __( 'right', 'contact-form-7' ) ); ?></label>
						<label><input type="radio" name="caltoltip" value="top" class="option" checked="checked" /> <?php echo esc_html( __( 'top', 'contact-form-7' ) ); ?></label>
						<label><input type="radio" name="caltoltip" value="bottom" class="option" /> <?php echo esc_html( __( 'bottom', 'contact-form-7' ) ); ?></label>
						
						
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
	<input type="text" name="<?php echo $type; ?>" class="tag code" readonly="readonly" onfocus="this.select()"/>

	<div class="submitbox">
	<input type="button" class="button button-primary insert-tag" value="<?php echo esc_attr( __( 'Insert Tag', 'contact-form-7' ) ); ?>" />
	</div>

	<br class="clear" />

	<p class="description mail-tag"><label for="<?php echo esc_attr( $args['content'] . '-mailtag' ); ?>"><?php echo sprintf( esc_html( __( "To use the value input through this field in a mail field, you need to insert the corresponding mail-tag (%s) into the field on the Mail tab.", 'contact-form-7' ) ), '<strong><span class="mail-tag"></span></strong>' ); ?><input type="text" class="mail-tag code hidden" readonly="readonly" id="<?php echo esc_attr( $args['content'] . '-mailtag' ); ?>" /></label></p>
</div>
<?php
}


