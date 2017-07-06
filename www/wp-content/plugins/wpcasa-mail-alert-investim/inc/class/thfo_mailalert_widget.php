<?php

/**
 * Created by PhpStorm.
 * User: sebastien
 * Date: 27/01/16
 * Time: 17:57
 */
class thfo_mailalert_widget extends WP_Widget {

	function __construct() {
		parent::__construct( 'thfo_mailalert', __('Mail Alert','wpcasa-mail-alert'), array( 'description' => __('Form to add a property search mail alert','wpcasa-mail-alert') ) );
		add_action( 'cmb2_init', array($this, 'investim_investidores_form_register'), 0, 0);
		add_shortcode('pagina_investidores', array($this, 'investim_pagina_investidores'));
	}

	/**
	 * explode a string with multiple value separated by $delimiter
	 * @param $delimiters
	 * @param $string
	 *
	 * @return array|mixed|void
	 */
	public function multiexplode ($delimiters,$string) {

		$ready = str_replace($delimiters, $delimiters[0], $string);
		$launch = explode($delimiters[0], $ready);

		$launch = apply_filters('thfo_mutliexplode', $launch);
		return  $launch;
	}


	/**
	 * Create a front office widget
	 * @param array $args
	 * @param array $instance
	 */

	public function widget( $args, $instance ) {

		echo $args['before_widget'];

		echo $args['before_title'];

		echo apply_filters( 'widget_title', $instance['title'] );

		echo $args['after_title'];

		$prices = get_option('thfo_max_price');
		$prices = $this->multiexplode(array(',',', '), $prices);
		$currency = wpsight_get_currency() ;
		// /**
		//  * Find number of rooms
		//  */

		// $rooms = get_posts(array( 'post_type' => array( 'listing' ), ));
		// //var_dump($rooms);
		// $nb_rooms= array();
		// foreach ( $rooms as $room) {
		// 	//var_dump($room);
		// 	$nb_room =  get_post_meta( $room->ID, '_details_1'  );
		// 	foreach ($nb_room as $room){
		// 		$nb_rooms[] = intval($room);
		// 		//var_dump(intval($room));
		// 	}
		// 	//var_dump( $nb_room);
		// }

		// sort($nb_rooms, SORT_NUMERIC);
		// $nb_rooms = array_unique($nb_rooms);

		// //var_dump($nb_rooms);

		do_action('wpcasama_info');
		?>

		<form action="" method="post">
			<p>
            <div class="wpcasama-widget-field"><label for="thfo_mailalert_name"> <?php _e('Your name', 'wpcasa-mail-alert') ?>*</label>
				<input id="thfo_mailalert_name" name="thfo_mailalert_name" required/>
            </div>
            <div class="wpcasama-widget-field">
				<label for="thfo_mailalert_email"> <?php _e('Your Email', 'wpcasa-mail-alert') ?>*</label>
				<input id="thfo_mailalert_email" name="thfo_mailalert_email" type="email" required/>
            </div>
            <div class="wpcasama-widget-field">
				<label for="thfo_mailalert_phone"> <?php _e('Your Phone number', 'wpcasa-mail-alert') ?></label>
				<input id="thfo_mailalert_phone" name="thfo_mailalert_phone" />
            </div>
            <div class="wpcasama-widget-field">
				<label for="thfo_mailalert_city"> <?php _e('City', 'wpcasa-mail-alert') ?></label>
				<select name="thfo_mailalert_city" required>
					<?php
					$city = get_terms( 'location' );
					foreach ($city as $c){
						$cities = $c->name; ?>
						<option name="thfo_mailalert_city" value="<?php echo $cities ?>"><?php echo $cities ?></option>
					<?php }
					?>
				</select>
            </div>
            <div class="wpcasama-widget-field">
				<label for="thfo_mailalert_min_price"> <?php _e('Minimum Price', 'wpcasa-mail-alert') ?></label>
				<select name="thfo_mailalert_min_price">
					<option name="thfo_mailalert_min_price" value="0">0 <?php  echo $currency ?></option>
					<?php
					foreach ($prices as $price){ ?>

						<option name="thfo_mailalert_min_price" value="<?php echo $price  ?>"><?php echo $price  ?><?php  echo $currency ?></option>
					<?php }
					?>
				</select>
            </div>
            <div class="wpcasama-widget-field">
				<label for="thfo_mailalert_price"> <?php _e('Maximum Price', 'wpcasa-mail-alert') ?></label>
				<select name="thfo_mailalert_price">
					<?php
					foreach ($prices as $price){ ?>
						<option name="thfo_mailalert_price" value="<?php echo $price  ?>"><?php echo $price  ?><?php  echo $currency ?></option>
					<?php }
					?>
					<option name="thfo_mailalert_price" value="more"><?php _e('Infinite', 'wpcasa-mail-alert') ?></option>
				</select>
            </div>
                <?php do_action('wpcasama_end_widget'); ?>
			</p>
			<input name="thfo_mailalert" class="moretag btn btn-primary" type="submit" />
		</form>
<?php
		echo $args['after_widget'];
	}

	/**
	 * Affichage du Widget en BO
	 * @param array $instance
	 */

	public function form($instance)
	{
		$title = isset($instance['title']) ? $instance['title'] : ''; ?>
		<p>
			<label for="<?php echo $this->get_field_name('title'); ?>"> <?php _e('Title:'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>"
			       name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>"/>

		</p>

		<?php
	}




	//////////////// INVESTIM CHANGES /////////////////////////////

	public function investim_investidores_form_register() {

		$fields = array(
			'name' => array(
				'name'      => 'Responsável',
				'id'        => 'investidor_name',
				'type'      => 'text',
				'desc'      => false,
				'default'   => '',
				'attributes'  => array(
					'maxlength'	=> 255,
					'required'	=> 'required',
				),
				'priority'  => 10
			),
			'company' => array(
				'name'      => 'Nome da Empresa',
				'id'        => 'investidor_company',
				'type'      => 'text',
				'desc'      => false,
				'default'   => '',
				'attributes'  => array(
					'maxlength'	=> 255,
					'required'	=> 'required',
				),
				'priority'  => 20
			),
			'email' => array(
				'name'      => 'E-mail',
				'id'        => 'investidor_email',
				'type'      => 'text_email',
				'desc'      => false,
				'default'   => '',
				'attributes'  => array(
					'maxlength'	=> 255,
					'required'	=> 'required',
				),
				'priority'  => 30
			),
			'tel' => array(
				'name'      => 'Telefone',
				'id'        => 'investidor_tel',
				'type'      => 'text',
				'desc'      => false,
				'default'   => '',
				'attributes'  => array(
					'maxlength'	=> 20,
					'required'	=> 'required',
				),
				'priority'  => 40
			),
			'mobile' => array(
				'name'      => 'Celular',
				'id'        => 'investidor_mobile',
				'type'      => 'text',
				'desc'      => false,
				'default'   => '',
				'attributes'  => array(
					'maxlength'	=> 20,
					'required'	=> 'required',
				),
				'priority'  => 50
			),
			'skype' => array(
				'name'      => 'Skype',
				'id'        => 'investidor_skype',
				'type'      => 'text',
				'desc'      => false,
				'default'   => '',
				'attributes'  => array(
					'maxlength'	=> 255,
				),
				'priority'  => 60
			),
			'country' => array(
				'name' 		=> 'País',
				'desc' 		=> 'Digite um valor, caso já exista, selecione na lista',
				'id' 		=> 'investidor_country',
				'type' 		=> 'autocomplete',
				'default'   => '',
				'attributes'  => array(
					'maxlength'	=> 255,
					'required'	=> 'required',
				),
				'priority'	=> 70
			),
			'state' => array(
				'name' 		=> 'Estado',
				'desc' 		=> 'Digite um valor, caso já exista, selecione na lista',
				'id' 		=> 'investidor_state',
				'type' 		=> 'autocomplete',
				'location-parent' 	=> 'country',
				'default'   => '',
				'attributes'  => array(
					'maxlength'	=> 255,
					'required'	=> 'required',
				),
				'priority'	=> 80
			),
			'city' => array(
				'name' 		=> 'Cidade',
				'desc' 		=> 'Digite um valor, caso já exista, selecione na lista',
				'id' 		=> 'investidor_city',
				'type' 		=> 'autocomplete',
				'location-parent'	=> 'state',
				'default'   => '',
				'attributes'  => array(
					'maxlength'	=> 255,
					'required'	=> 'required',
				),
				'priority'	=> 90
			),
			'min_price' => array(
				'name'      => 'Preço Mínimo (R$)',
				'id'        => 'investidor_min_price',
				'type'      => 'text',
				'desc'      => __( 'No currency symbols or thousands separators', 'wpcasa' ),
				'default'   => '',
				'before_field' => 'R$',
				'attributes'  => array(
					'type' 		=> 'number',
					'min'		=> '1',
					'max'		=> '9999999999',
					'maxlength'	=> 10,
					'required'	=> 'required',
				),
				'sanitization_cb'	=> 'absint',
        		'escape_cb'			=> 'absint',
				'priority'  		=> 100
			),
			'max_price' => array(
				'name'      => 'Preço Máximo (R$)',
				'id'        => 'investidor_max_price',
				'type'      => 'text',
				'desc'      => __( 'No currency symbols or thousands separators', 'wpcasa' ),
				'default'   => '',
				'before_field' => 'R$',
				'attributes'  => array(
					'type' 		=> 'number',
					'min'		=> '1',
					'max'		=> '9999999999',
					'maxlength'	=> 10,
					'required'	=> 'required',
				),
				'sanitization_cb'	=> 'absint',
        		'escape_cb'			=> 'absint',
				'priority' 			=> 110
			),
			'third_party_capital' => array(
				'name'      => 'Você está contando com capital de terceiros?',
				'id'        => 'investidor_third_party_capital',
				'type'      => 'radio',
				'options'   => array('1' => 'Sim', '0' => 'Não'),
				'default'   => '0',
				'dashboard' => true,'attributes'  => array(
					'required'	=> 'required',
				),
				'priority'  => 120
			),
			'prefered_city' => array(
				'name'      => 'Cidade Preferencial',
				'id'        => 'investidor_prefered_city',
				'type'      => 'text',
				'desc'      => false,
				'default'   => '',
				'attributes'  => array(
					'maxlength'	=> 255,
					'required'	=> 'required',
				),
				'priority'  => 130
			),
			'sector' => array(
				'name'      => 'Setor de Atividade do seu interesse',
				'id'        => 'investidor_sector',
				'type'      => 'text',
				'desc'      => false,
				'default'   => '',
				'attributes'  => array(
					'maxlength'	=> 255,
				),
				'priority'  => 140
			),
			'description' => array(
				'name'      => __( 'Description', 'wpcasa' ),
				'id'        => 'investidor_description',
				'type'      => 'textarea',
				'desc'      => false,
				'default'   => '',
				'attributes'  => array(
					'maxlength'	=> 16383,
				),
				'priority'  => 150
			),
		);

		$meta_box = array(
			'id'           => 'investidor',
			'title'        => 'Investidor',
			'object_types' => array( 'investidor' ),
			'context'      => 'normal',
			'priority'     => 'high',
			'hookup'       => false,
			'save_fields'  => false,
			'fields'       => $fields
		);

		$cmb = new_cmb2_box($meta_box);
	}


	public function investim_pagina_investidores() {

		// Current user
		$user_id = get_current_user_id();

		// Use ID of metabox in wds_frontend_form_register
		$metabox_id = 'investidor';

		// since post ID will not exist yet, just need to pass it something
		$object_id  = 'fake-oject-id';

		// Get CMB2 metabox object
		$cmb = cmb2_get_metabox( $metabox_id, $object_id );

		// Get $cmb object_types
		$post_types = $cmb->prop( 'object_types' );

		// Parse attributes. These shortcode attributes can be optionally overridden.
		$atts = shortcode_atts( array(
			'post_author' => $user_id ? $user_id : 1, // Current user, or admin
			'post_status' => 'pending',
			'post_type'   => reset( $post_types ), // Only use first object_type in array
		), $atts, 'cmb-frontend-form' );

		// Initiate our output variable
		$output = '';

		// Handle form saving (if form has been submitted)
		$new_id = $this->investim_save_investidores( $cmb, $atts );

		if ( $new_id ) {

			if ( is_wp_error( $new_id ) ) {

				// If there was an error with the submission, add it to our ouput.
				$output .= '<h3>' . sprintf( __( 'Erro ao gravar informações: %s', 'wds-post-submit' ), '<strong>'. $new_id->get_error_message() .'</strong>' ) . '</h3>';

			} else {

				// Add notice of submission
				$output .= '<div class="bs-callout bs-callout-info"><h3>' . sprintf( __( 'Obrigado <em>%s</em>, sua solicitação será analisada pelos nossos atendentes e em breve entraremos em contato!', 'wds-post-submit' ), esc_html( $new_id ) ) . '</h3></div>';
				return $output;
			}

		}

		$action = get_permalink() . '?type=investidor';

		// Get our form
		$output .= cmb2_get_metabox_form( $cmb, $object_id, array( 
			'form_format' => '<form action="' . $action . '" class="cmb-form wpsight-dashboard-form" method="post" id="%1$s" enctype="multipart/form-data" encoding="multipart/form-data"><input type="hidden" name="object_id" value="%2$s">%3$s<input type="submit" name="submit-submission" value="%4$s" class="button"></form>',
			'save_button' => 'Enviar'
		) );

		return $output;
	}


	public function investim_save_investidores( $cmb, $post_data = array() ) {
		global $wpdb;

		// If no form submission, bail
		if ( empty( $_POST ) ) {
			return false;
		}

		// check required $_POST variables and security nonce
		if (
			! isset( $_POST['object_id'], $_POST[ $cmb->nonce() ] )
			|| ! wp_verify_nonce( $_POST[ $cmb->nonce() ], $cmb->nonce() )
		) {
			return new WP_Error( 'security_fail', __( 'Verificação de segurança falhou.' ) );
		}

		// Fetch sanitized values
		$sanitized_values = $cmb->get_sanitized_values( $_POST );

		$values = array();

		foreach ($sanitized_values as $key => $value) {
			$values[str_replace('investidor_', '', $key)] = $value;
		}
		
		$success = $wpdb->insert("{$wpdb->prefix}wpcasama_mailalert", $values);

		//If we hit a snag, update the user
		if ( is_wp_error( $success ) ) {
			return $new_submission_id;
		} else {
			return $values['name'];
		}
		
	}

}