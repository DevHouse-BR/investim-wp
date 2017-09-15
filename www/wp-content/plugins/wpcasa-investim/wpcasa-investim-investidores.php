<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function init_investidor_roles() {
	global $wp_roles;

	if ( class_exists( 'WP_Roles' ) && ! isset( $wp_roles ) )
		$wp_roles = new WP_Roles();

	if ( is_object( $wp_roles ) ) {

		$role = array(
			'id'   => 'listing_investidor',
			'name' => _x( 'Investidor', 'agent role', 'wpcasa' ),
			'caps' => array(
				'read'                 => true,
				'upload_files'         => true,
				'edit_listing'         => true,
				'read_listing'         => true,
				'delete_listing'       => true,
				'edit_listings'        => true,
				'delete_listings'      => true,
				'edit_listings'        => true,
				'assign_listing_terms' => true
			)
		);
		
		add_role( $role['id'], $role['name'], $role['caps'] );
		
		/**
		 * Add level_1 to caps to show custom roles in author dropdown
		 * @see https://core.trac.wordpress.org/ticket/16841
		 */
		$user_role = get_role( $role['id'] );				
		$user_role->add_cap( 'level_1' );
	}

}


add_filter( 'wpsight_dashboard_register_data', 'wpsight_dashboard_register_data_custom' );
function wpsight_dashboard_register_data_custom( $new_user  ) {

	$post = get_post(url_to_postid( "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] ));

	if ( $post->post_name == "registrar-investidor" ) {
		$new_user['role'] = 'listing_investidor';
	}

	return $new_user;
}


add_shortcode( 'investim_pagina_registrar_investidor', 'investim_pagina_registrar_investidor' );
function investim_pagina_registrar_investidor() {

	ob_start();
	wpsight_get_template( 'account-register.php', null, WPSIGHT_DASHBOARD_PLUGIN_DIR . 'templates' );
 	$register_template = ob_get_clean(); 
 	echo str_replace( "Você já está logado", "Você já está logado
 		<script type=\"text/javascript\">
 			setTimeout(function() {
 				location = '" . site_url() . "/painel-de-controle/compre-uma-empresa/';
 			}, 2000);
 		</script>", $register_template );
}

add_action( 'cmb2_init', 'investim_novo_investidor_form_register' );
function investim_novo_investidor_form_register() {

	$fields = array(
		'user' => array(
			'name'      => 'Usuário',
			'id'        => 'investidor_user',
			'type'      => 'hidden',
			'desc'      => false,
			'default'   => get_current_user_id(),
			'priority'  => 09
		),
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
			'location-parent' 	=> 'investidor_country',
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
			'location-parent'	=> 'investidor_state',
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
			'before_field' => '',
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
			'before_field' => '',
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
		'enable' => array(
			'name'      => 'Ativo',
			'id'        => 'investidor_enable',
			'type'      => 'hidden',
			'desc'      => false,
			'default'   => '1',
			'priority'  => 160
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

add_shortcode( 'investim_pagina_investidores', 'investim_pagina_investidores' );
function investim_pagina_investidores() {

	if ( ! is_user_logged_in() ) {
		ob_start();
		wpsight_get_template( 'access-no.php', null, WPSIGHT_DASHBOARD_PLUGIN_DIR . '/templates' );
		echo str_replace( "/registrar/", "/registrar-investidor/", ob_get_clean() );
		return;
	}

	// Current user
	$user_id = get_current_user_id();

	$fields = array(
		'user' => get_current_user_id(),
		'name' => "",
		'company' => "",
		'tel' => "",
		'mobile' => "",
		'skype' => "",
		'country' => "",
		'state' => "",
		'city' => "",
		'min_price' => "0",
		'max_price' => "0",
		'third_party_capital' => "0",
		'prefered_city' => "",
		'sector' => "",
		'description' => "",
		'enable' => "",
	);

	$investidor = already_investidor( $user_id );

	if ( $investidor ) {
		$fields = $investidor;
	}

	// Handle form saving (if form has been submitted)
	$new_id = investim_save_investidores( $investidor );

	if ( $new_id ) {

		// Initiate our output variable
		$output = '';

		if ( is_wp_error( $new_id ) ) {

			// If there was an error with the submission, add it to our ouput.
			$output .= '<div class="bs-callout bs-callout-danger"><h3>' . sprintf( __( 'Erro ao gravar informações: <em>%s</em>', 'wds-post-submit' ), esc_html( $new_id->get_error_message() ) ) . '</h3></div>';
			return $output;

		} else {

			// Add notice of submission
			$output .= '<div class="bs-callout bs-callout-info"><h3>' . sprintf( __( 'Obrigado <em>%s</em>, você receberá notificações sobre novas empresas a venda dentro do seu perfil!', 'wds-post-submit' ), esc_html( $new_id ) ) . '</h3></div>';
			return $output;

		}

	}

	echo '
	<form action="https://www.investim.com.br/painel-de-controle/compre-uma-empresa/?type=investidor" class="cmb-form wpsight-dashboard-form" method="post" id="investidor" enctype="multipart/form-data" encoding="multipart/form-data">
	';
	wp_nonce_field( 'form_investidor', 'investidor_check' );

	echo '<!-- Begin CMB2 Fields -->
	<div class="cmb2-wrap form-table"><div id="cmb2-metabox-investidor" class="cmb2-metabox cmb-field-list"><div class="cmb-row cmb-type-text cmb2-id-investidor-name table-layout">
	<div class="cmb-th">
	<label for="investidor_name">Responsável</label>
	</div>
		<div class="cmb-td">
	<input type="text" class="form-control" name="investidor_name" id="investidor_name" value="' . $fields["name"] . '" maxlength="255" required="required"/>
		</div>
	</div><div class="cmb-row cmb-type-text cmb2-id-investidor-company table-layout">
	<div class="cmb-th">
	<label for="investidor_company">Nome da Empresa</label>
	</div>
		<div class="cmb-td">
	<input type="text" class="form-control" name="investidor_company" id="investidor_company" value="' . $fields["company"] . '" maxlength="255" required="required"/>
		</div>
	</div><div class="cmb-row cmb-type-text cmb2-id-investidor-tel table-layout">
	<div class="cmb-th">
	<label for="investidor_tel">Telefone</label>
	</div>
		<div class="cmb-td">
	<input type="text" class="form-control" name="investidor_tel" id="investidor_tel" value="' . $fields["tel"] . '" maxlength="20" required="required"/>
		</div>
	</div><div class="cmb-row cmb-type-text cmb2-id-investidor-mobile table-layout">
	<div class="cmb-th">
	<label for="investidor_mobile">Celular</label>
	</div>
		<div class="cmb-td">
	<input type="text" class="form-control" name="investidor_mobile" id="investidor_mobile" value="' . $fields["mobile"] . '" maxlength="20" required="required"/>
		</div>
	</div><div class="cmb-row cmb-type-text cmb2-id-investidor-skype table-layout">
	<div class="cmb-th">
	<label for="investidor_skype">Skype</label>
	</div>
		<div class="cmb-td">
	<input type="text" class="form-control" name="investidor_skype" id="investidor_skype" value="' . $fields["skype"] . '" maxlength="255"/>
		</div>
	</div><div class="cmb-row cmb-type-autocomplete cmb2-id-investidor-country">
	<div class="cmb-th">
	<label for="investidor_country">País</label>
	</div>
		<div class="cmb-td">
	<input type="hidden" class=" form-control" name="investidor_country" id="investidor_country" value="' . $fields["country"] . '" maxlength="255" required="required"/>
		<div class="input-group">
			<input type="text" aria-label="..." class="form-control location-autocomplete" data-parent="" size="50" id="investidor-country" value="' . $fields["country"] . '" />
			<div class="input-group-btn">
				<button type="button" class="btn btn-default dropdown-toggle"> <span class="caret"></span></button>
			</div>
		</div>
		
		<p class="cmb2-metabox-description">Digite um valor, caso já exista, selecione na lista</p>
		</div>
	</div><div class="cmb-row cmb-type-autocomplete cmb2-id-investidor-state">
	<div class="cmb-th">
	<label for="investidor_state">Estado</label>
	</div>
		<div class="cmb-td">
	<input type="hidden" class=" form-control" name="investidor_state" id="investidor_state" value="' . $fields["state"] . '" maxlength="255" required="required"/>
		<div class="input-group">
			<input type="text" aria-label="..." class="form-control location-autocomplete" data-parent="investidor_country" size="50" id="investidor-state" value="' . $fields["state"] . '" />
			<div class="input-group-btn">
				<button type="button" class="btn btn-default dropdown-toggle"> <span class="caret"></span></button>
			</div>
		</div>
		
		<p class="cmb2-metabox-description">Digite um valor, caso já exista, selecione na lista</p>
		</div>
	</div><div class="cmb-row cmb-type-autocomplete cmb2-id-investidor-city">
	<div class="cmb-th">
	<label for="investidor_city">Cidade</label>
	</div>
		<div class="cmb-td">
	<input type="hidden" class=" form-control" name="investidor_city" id="investidor_city" value="' . $fields["city"] . '" maxlength="255" required="required"/>
		<div class="input-group">
			<input type="text" aria-label="..." class="form-control location-autocomplete" data-parent="investidor_state" size="50" id="investidor-city" value="' . $fields["city"] . '" />
			<div class="input-group-btn">
				<button type="button" class="btn btn-default dropdown-toggle"> <span class="caret"></span></button>
			</div>
		</div>
		
		<p class="cmb2-metabox-description">Digite um valor, caso já exista, selecione na lista</p>
		</div>
	</div><div class="cmb-row cmb-type-text cmb2-id-investidor-min-price table-layout">
	<div class="cmb-th">
	<label for="investidor_min_price">Preço Mínimo (R$)</label>
	</div>
		<div class="cmb-td">
	<input type="number" class="form-control" name="investidor_min_price" id="investidor_min_price" value="' . $fields["min_price"] . '" min="1" max="9999999999" maxlength="10" required="required"/>
	<p class="cmb2-metabox-description">Sem símbolos de moeda ou separadores de milhares</p>

		</div>
	</div><div class="cmb-row cmb-type-text cmb2-id-investidor-max-price table-layout">
	<div class="cmb-th">
	<label for="investidor_max_price">Preço Máximo (R$)</label>
	</div>
		<div class="cmb-td">
	<input type="number" class="form-control" name="investidor_max_price" id="investidor_max_price" value="' . $fields["max_price"] . '" min="1" max="9999999999" maxlength="10" required="required"/>
	<p class="cmb2-metabox-description">Sem símbolos de moeda ou separadores de milhares</p>

		</div>
	</div><div class="cmb-row cmb-type-radio cmb2-id-investidor-third-party-capital">
	<div class="cmb-th">
	<label for="investidor_third_party_capital">Você está contando com capital de terceiros?</label>
	</div>
		<div class="cmb-td">
	<ul class="radio radio-primary">
		<li>
			<input type="radio" class="cmb2-option" name="investidor_third_party_capital" id="investidor_third_party_capital1" value="1" required="required" ' . ($fields["third_party_capital"] ? 'checked="checked"' : '') . ' />
			<label for="investidor_third_party_capital1">Sim</label>
		</li>
		<li>
			<input type="radio" class="cmb2-option" name="investidor_third_party_capital" id="investidor_third_party_capital2" value="0"  required="required" ' . ($fields["third_party_capital"] ? '' : 'checked="checked"') . ' />
			<label for="investidor_third_party_capital2">Não</label>
		</li>
	</ul>
		</div>
	</div><div class="cmb-row cmb-type-text cmb2-id-investidor-prefered-city table-layout">
	<div class="cmb-th">
	<label for="investidor_prefered_city">Cidade Preferencial</label>
	</div>
		<div class="cmb-td">
	<input type="text" class="form-control" name="investidor_prefered_city" id="investidor_prefered_city" value="' . $fields["prefered_city"] . '" maxlength="255" required="required"/>
		</div>
	</div><div class="cmb-row cmb-type-text cmb2-id-investidor-sector table-layout">
	<div class="cmb-th">
	<label for="investidor_sector">Setor de Atividade do seu interesse</label>
	</div>
		<div class="cmb-td">
	<input type="text" class="form-control" name="investidor_sector" id="investidor_sector" value="' . $fields["sector"] . '" maxlength="255"/>
		</div>
	</div><div class="cmb-row cmb-type-textarea cmb2-id-investidor-description">
	<div class="cmb-th">
	<label for="investidor_description">Descrição</label>
	</div>
		<div class="cmb-td">
	<textarea class="form-control" name="investidor_description" id="investidor_description" cols="60" rows="10" maxlength="16383">' . $fields["description"] . '</textarea>
		</div>
	</div></div></div>
	<input type="hidden" class=" form-control" name="investidor_user" id="investidor_user" value="' . $fields["user"] . '"/>
	<input type="hidden" class=" form-control" name="investidor_enable" id="investidor_enable" value="1"/>
	<!-- End CMB2 Fields -->
	<input type="submit" name="submit-submission" value="Enviar" class="button btn btn-primary btn-lg"></form>
	';

}

function already_investidor( $userID ) {
	global $wpdb;
	$investidor = $wpdb->get_row( "SELECT * FROM {$wpdb->prefix}wpcasama_mailalert WHERE user = $userID", ARRAY_A );

	return $investidor;
}


function investim_save_investidores( $update = false ) {
	global $wpdb;

	// Get CMB2 metabox object
	$cmb = cmb2_get_metabox( 'investidor', 'fake-object-id' );

	// If no form submission, bail
	if ( empty( $_POST ) ) {
		return false;
	}

	// check required $_POST variables and security nonce
	if ( ! wp_verify_nonce( $_POST[ 'investidor_check' ], 'form_investidor' ) ) {
		return new WP_Error( 'security_fail', __( 'Verificação de segurança falhou.' ) );
	}

	// Fetch sanitized values
	$sanitized_values = $cmb->get_sanitized_values( $_POST );

	$values = array();

	foreach ($sanitized_values as $key => $value) {
		$values[str_replace('investidor_', '', $key)] = $value;
	}

	list( $values["country"], $values["state"], $values["city"]  ) = investim_get_location_name_or_keep_name(
		sanitize_text_field( $values["country"] ),
		sanitize_text_field( $values["state"] ),
		sanitize_text_field( $values["city"] )
	);
	
	if ( $update ) {
		$success = $wpdb->update("{$wpdb->prefix}wpcasama_mailalert", $values, array( 'user' => $update['user'] ));
	} else {
		$success = $wpdb->insert("{$wpdb->prefix}wpcasama_mailalert", $values);
	}

	//If we hit a snag, update the user
	if ( $wpdb->last_error !== '' ) {
		return new WP_Error( 'broke', $wpdb->last_error );
	} else {
		investidor_send_mail($values);
		return $values['name'];
	}
	
}

function investim_get_location_name_or_keep_name( $country, $state, $city ) {

	// types can be id or name. id represents terms already in the database and name a new term to be added.
	list( $countryType, $country ) = explode( '|', $country );
	list( $stateType, $state ) = explode( '|', $state );
	list( $cityType, $city ) = explode( '|', $city );

	if ($countryType == "id") {
		$term_exist = get_term( $country, 'location');
		if ($term_exist) {
			$country = $term_exist->name;
		} else {
			$country = "";
		}
	}

	if ($stateType == "id") {
		$term_exist = get_term( $state, 'location');
		if ($term_exist) {
			$state = $term_exist->name;
		} else {
			$state = "";
		}
	}

	if ($cityType == "id") {
		$term_exist = get_term( $city, 'location');
		if ($term_exist) {
			$city = $term_exist->name;
		} else {
			$city = "";
		}
	}

	return  array( $country, $state, $city );

}

function investidor_send_mail( $values ){

	$admins = get_users(array("role" => "administrator"));

	$recipient = "";
	foreach ($admins as $key => $user) {
		$recipient .= $user->data->user_email . ",";
	}

	$sender_mail = get_option('thfo_newsletter_sender_mail');
	if ( empty($sender_mail)){
		$sender_mail = get_option('admin_email');
	}

	$sender = get_option('thfo_newsletter_sender');
	$subject = get_option('thfo_newsletter_object');
	
	$content = "";
	$img= get_option('empathy-setting-logo');
	if ($img) {
		$content .= '<img src="' . $img . '" alt="logo" /><br />';
	}
	$content .= get_option('thfo_newsletter_content');
	
	$content .= 'Nome: ' . $values['name'] . '<br />';
	$content .= 'Empresa: ' . $values['company'] . '<br />';
	$content .= 'Email: ' . $values['email'] . '<br />';
	$content .= 'Tel: ' . $values['tel'] . '<br />';
	$content .= 'Cel: ' . $values['mobile'] . '<br />';
	$content .= 'Skype: ' . $values['skype'] . '<br />';
	$content .= 'País: ' . $values['country'] . '<br />';
	$content .= 'Estado: ' . $values['state'] . '<br />';
	$content .= 'Cidade: ' . $values['city'] . '<br />';
	$content .= 'Preço Mínimo: ' . $values['min_price'] . '<br />';
	$content .= 'Preço Máximo: ' . $values['max_price'] . '<br />';
	$content .= 'Capital Terceiros: ' . $values['third_party_capital'] . '<br />';
	$content .= 'Cidade Preferencial: ' . $values['prefered_city'] . '<br />';
	$content .= 'Setor: ' . $values['sector'] . '<br />';
	$content .= 'Descrição: ' . $values['description'] . '<br />';

	$content .= get_option('thfo_newsletter_footer');

	$headers[] = 'Content-Type: text/html; charset=UTF-8';
	$headers[] = 'From:'.$sender.'<'.$sender_mail.'>';

	$result = wp_mail($recipient, $subject, $content, $headers);

}

//add_action( 'init', 'process_investidor_register_form', 9999 );
function process_investidor_register_form() {

	if ( ! isset( $_POST['registrar_investidor'] ) || ! get_option( 'users_can_register' ) )
		return;
	
	$_SESSION['registration'] = array();

	if ( empty( $_POST['name'] ) || empty( $_POST['email'] ) ) {
		$_SESSION['messages'][] = array( 'danger', __( 'Username and e-mail are required.', 'wpcasa-dashboard' ) );
		wp_redirect( $_SERVER['HTTP_REFERER'] );
		exit();
	}

	$user_id = username_exists( $_POST['name'] );

	if ( ! empty( $user_id ) ) {
		$_SESSION['messages'][] = array( 'danger', __( 'Username already exists.', 'wpcasa-dashboard' ) );
		$_SESSION['registration']['email'] = $_POST['email'];
		wp_redirect( $_SERVER['HTTP_REFERER'] );
		exit();
	}

	$user_id = email_exists( $_POST['email'] );

	if ( ! empty( $user_id ) ) {
		$_SESSION['messages'][] = array( 'danger', __( 'Email already exists.', 'wpcasa-dashboard' ) );
		$_SESSION['registration']['name'] = $_POST['name'];
		wp_redirect( $_SERVER['HTTP_REFERER'] );
		exit();
	}

	if ( $_POST['password'] != $_POST['password_retype'] ) {
		$_SESSION['messages'][] = array( 'danger', __( 'Passwords must be same.', 'wpcasa-dashboard' ) );
		$_SESSION['registration']['name'] = $_POST['name'];
		$_SESSION['registration']['email'] = $_POST['email'];
		wp_redirect( $_SERVER['HTTP_REFERER'] );
		exit();
	}

	$terms_id = wpsight_get_option( 'dashboard_terms' );

	if ( $terms_id && empty( $_POST['agree_terms'] ) ) {
		$_SESSION['messages'][] = array( 'danger', __( 'Please agree to our terms &amp; conditions.', 'wpcasa-dashboard' ) );
		$_SESSION['registration']['name'] = $_POST['name'];
		$_SESSION['registration']['email'] = $_POST['email'];
		wp_redirect( $_SERVER['HTTP_REFERER'] );
		exit();
	}
	
	$is_recaptcha = WPSight_Dashboard_Recaptcha::is_recaptcha_enabled();
	$is_recaptcha_valid = array_key_exists( 'g-recaptcha-response', $_POST ) ? WPSight_Dashboard_Recaptcha::is_recaptcha_valid( $_POST['g-recaptcha-response'] ) : false;
	
	if ( $is_recaptcha && ! $is_recaptcha_valid ) {
		$_SESSION['messages'][] = array( 'danger', __( 'The captcha input was incorrect.', 'wpcasa-dashboard' ) );
		$_SESSION['registration']['name'] = $_POST['name'];
		$_SESSION['registration']['email'] = $_POST['email'];
		wp_redirect( $_SERVER['HTTP_REFERER'] );
		exit();
	}
	
	unset( $_SESSION['registration'] );

	$user_login = sanitize_user( $_POST['name'], true );
	
	$new_user = array(
		'user_login' 	=> $user_login,
		'user_pass'  	=> $_POST['password'],
		'user_email' 	=> $_POST['email'],
		'role'       	=> 'listing_investidor'
	);
	
	// Finally create user
	$user_id = wp_insert_user( apply_filters( 'wpsight_dashboard_register_data', $new_user ) );

	if ( is_wp_error( $user_id ) ) {
		$_SESSION['messages'][] = array( 'danger', $user_id->get_error_message() );
		wp_redirect( site_url() );
		exit();
	}
	
	wp_new_user_notification( $user_id, null, 'both' );

	$_SESSION['messages'][] = array(
		'success',
		__( 'You have been successfully registered.', 'wpcasa-dashboard' ),
	);
	
	$user = get_user_by( 'login', $user_login );
	$log_in_after_registration = apply_filters( 'wpsight_dashboard_log_in_after_registration', true );

	// automatic user log in

	if ( $user && $log_in_after_registration ) {
		wp_set_current_user( $user->ID, $user_login );
		wp_set_auth_cookie( $user->ID, true, is_ssl() );
		do_action( 'wp_login', $user_login );
	}

	// registration page

	$registration_page = wpsight_get_option( 'dashboard_register' );
	$registration_page_url = $registration_page ? get_permalink( $registration_page ) : site_url();

	// after register page

	$after_register_page = wpsight_get_option( 'dashboard_register_after' );
	$after_register_page_url = $after_register_page ? get_permalink( $after_register_page ) : site_url();

	// if user registers at registration page, redirect him to after register page. Otherwise, redirect him back to previous URL.

	$protocol = is_ssl() ? 'https://' : 'http://';
	$current_url = $protocol . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];

	$after_register_url = $current_url == $registration_page_url ? $after_register_page_url : $current_url;

	wp_redirect( $after_register_url );
	exit();

}