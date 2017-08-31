<?php

/**
 * Created by PhpStorm.
 * User: sebastien
 * Date: 28/01/16
 * Time: 22:30
 */
class thfo_mailalert_admin_menu {
	function __construct() {
		add_filter('set-screen-option', array($this, 'investidor_table_set_option'), 10, 3);
		add_action('admin_menu', array($this, 'thfo_admin_menu'));
		add_action('admin_menu', array($this, 'thfo_delete_subscriber'));
		add_action('admin_init', array($this, 'register_settings'));
	}

	public function thfo_admin_menu(){
		$hook = add_menu_page(
			__('Mail Alert', 'wpcasa-mail-alert'),
			__('Mail Alert', 'wpcasa-mail-alert'),
			'manage_options',
			'wpcasa-mail-alert', 
			array($this, 'thfo_menu_html'),
			WPCASAMA_PLUGIN_PATH . '/assets/img/icon.png'
		);
		add_action( "load-$hook", array($this, 'add_options') );
		

		add_submenu_page('wpcasa-mail-alert',__('Mail Settings', 'wpcasa-mail-alert'),__('Mail Settings', 'wpcasa-mail-alert'),'manage_options', 'thfo-mailalert-mail-settings', array($this,'menu_html'));
		add_submenu_page('wpcasa-mail-alert',__('General Options', 'wpcasa-mail-alert'),__('General Options', 'wpcasa-mail-alert'),'manage_options', 'thfo_mailalert_options', array($this,'general_html'));
	}

	public function general_html(){
		    echo '<h1>' . get_admin_page_title() . '</h1>';

		?>
		<form method="post" action="options.php">
			<?php settings_fields('thfo_newsletter_options') ?>
			<?php do_settings_sections('thfo_general_options') ?>
			<?php submit_button(__('Save')); ?>


		</form>
	<?php }


	public function menu_html()
	{
		echo '<h1>'.get_admin_page_title().'</h1>'; ?>

		<form method="post" action="options.php">
			<?php settings_fields('thfo_newsletter_settings') ?>
			<?php do_settings_sections('thfo_newsletter_settings') ?>
			<?php submit_button(__('Save')); ?>


		</form>

		<?php
	}

	public function register_settings()
	{
		/* Mail Settings */
		add_settings_section('thfo_newsletter_section', __('Outgoing parameters','wpcasa-mail-alert'), array($this, 'section_html'), 'thfo_newsletter_settings');

		register_setting('thfo_newsletter_settings', 'thfo_newsletter_sender');
		register_setting('thfo_newsletter_settings', 'thfo_newsletter_sender_mail');
		register_setting('thfo_newsletter_settings', 'thfo_newsletter_object');
        register_setting('thfo_newsletter_settings', 'thfo_newsletter_content');
		register_setting('thfo_newsletter_settings', 'thfo_newsletter_footer');
		register_setting('thfo_newsletter_settings', 'empathy-setting-logo');

		add_settings_field('thfo_newsletter_sender', __('Sender','wpcasa-mail-alert'), array($this, 'sender_html'), 'thfo_newsletter_settings', 'thfo_newsletter_section');
		add_settings_field('empathy-setting-logo', __('Header picture','wpcasa-mail-alert'), array($this, 'media_html'), 'thfo_newsletter_settings', 'thfo_newsletter_section');
		add_settings_field('thfo_newsletter_footer', __('footer','wpcasa-mail-alert'), array($this, 'footer_html'), 'thfo_newsletter_settings', 'thfo_newsletter_section');
		add_settings_field('thfo_newsletter_sender_mail', __('email','wpcasa-mail-alert'), array($this, 'sender_mail_html'), 'thfo_newsletter_settings', 'thfo_newsletter_section');
		add_settings_field('thfo_newsletter_object', __('Assunto','wpcasa-mail-alert'), array($this, 'object_html'), 'thfo_newsletter_settings', 'thfo_newsletter_section');
		add_settings_field('thfo_newsletter_content', __('Content','wpcasa-mail-alert'), array($this, 'content_html'), 'thfo_newsletter_settings', 'thfo_newsletter_section');

		/* General options*/
		add_settings_section('thfo_newsletter_option_section', __('General Options','wpcasa-mail-alert'), array($this, 'general_section_html'), 'thfo_general_options');

		register_setting('thfo_newsletter_options', 'thfo_unsubscribe_page');
		register_setting('thfo_newsletter_options', 'thfo_thanks_page');
		register_setting('thfo_newsletter_options', 'thfo_max_price');


		add_settings_field('thfo_unsubscribe_page', __('Unsubscribe Page','wpcasa-mail-alert'), array($this, 'option_html'), 'thfo_general_options', 'thfo_newsletter_option_section');
		add_settings_field('thfo_max_price', __('Maximum Price','wpcasa-mail-alert'), array($this, 'thfo_max_price'), 'thfo_general_options', 'thfo_newsletter_option_section');


	}

	public function thfo_max_price(){
		$max_price = get_option('thfo_max_price')?>
		<?php do_action('wpcasama_pro_before_max_price_option'); ?>
		<input name="thfo_max_price" id="thfo_max_price" type="text" value="<?php if ( !empty($max_price)) : echo $max_price; endif ?>">
		<p><?php _e('Please enter maximum price separated by a comma','wpcasa-mail-alert') ?></p>
        <?php do_action('wpcasama_pro_after_max_price_option'); ?>
        <?php

		/**
		 * Promo for Pro Version on free one.
         * If Pro -> More options!
		 */

        $url = 'https://www.thivinfo.com/downloads/wpcasa-mail-alert-pro/';
        $value = '<div class="wpcasama-promo"> ';
        $value .= sprintf( wp_kses( __( 'More options in the PRO Version from only 39,99€ per year <a href="%s">Here</a>.', 'wpcasa-mail-alert' ), array(  'a' => array( 'href' => array() ) ) ), esc_url( $url ) );
        $value .= '</div>';
        $value = apply_filters( 'link_to_pro_version', $value );
        echo $value;

        ?>

	<?php }

	public function general_section_html(){
		echo '<p>'.__('Select your options','wpcasa-mail-alert').'</p>';
	}

	public function option_html(){
		$pages = get_pages(array( 'post_publish' => 'publish'));
		//var_dump($pages);
		?>
		<?php do_action('wpcasama_pro_before_unsubscribe_page'); ?>
		<select name='thfo_unsubscribe_page' id='thfo_unsubscribe_page'>
			<?php foreach ( $pages as $page ) { ?>

				<option name='thfo_unsubscribe_page' id='thfo_unsubscribe_page' value='<?php echo $page->post_name; ?>'
				<?php
				$unsubscribe = get_option('thfo_unsubscribe_page');
				if ( ! empty ($unsubscribe) && $unsubscribe === $page->post_name ){
					echo "selected";
				}
				?> > <?php echo $page->post_title; ?> </option>

			<?php } ?>
		</select>
		<?php
        echo '<p>' . __('Use the [thfo_mailalert_unsubscribe] shortcode on the selected page', 'wpcasa-mail-alert') . '</p>';
        do_action('wpcasama_pro_after_unsubscribe_page'); ?>
	<?php }


	public function media_html(){ ?>
		<input type="text" name="empathy-setting-logo" id="empathy-setting-logo" value="<?php echo  esc_attr(get_option( 'empathy-setting-logo' )) ; ?>">
		<a class="button" onclick="upload_image('empathy-setting-logo');"><?php _e('Upload', 'wpcasa-mail-alert') ?></a>
		<script>
			var uploader;
			function upload_image(id) {
				//console.log(id);

				//Extend the wp.media object
				uploader = wp.media.frames.file_frame = wp.media({
					title: 'Choose Image',
					button: {
						text: 'Choose Image'
					},
					multiple: false
				});

				//When a file is selected, grab the URL and set it as the text field's value
				uploader.on('select', function() {
					attachment = uploader.state().get('selection').first().toJSON();
					var url = attachment['url'];
					jQuery('#'+id).val(url);
				});

				//Open the uploader dialog
				uploader.open();
			}
		</script>
	<?php }

	public function section_html()

	{

		echo '<p>'.__('Advise about outgoing parameters.','wpcasa-mail-alert').'</p>';

	}

	public function footer_html(){
		?>
		<textarea name="thfo_newsletter_footer"><?php echo get_option('thfo_newsletter_footer')?></textarea>

		<?php
	}

	public function sender_html()
	{?>
		<input type="text" name="thfo_newsletter_sender" value="<?php echo get_option('thfo_newsletter_sender')?>"/>
		<?php
	}

	public function sender_mail_html()
	{?>
		<input type="email" name="thfo_newsletter_sender_mail" value="<?php echo get_option('thfo_newsletter_sender_mail')?>"/>
		<?php
	}


	public function object_html()

	{?>

		<input type="text" name="thfo_newsletter_object" value="<?php echo get_option('thfo_newsletter_object')?>"/>
		<?php


	}


	public function content_html()

	{
		wp_editor(get_option('thfo_newsletter_content'),'thfo_newsletter_content' );
	}

	public function process_action()
	{

		if (isset($_POST['send_newsletter'])) {

			$this->send_newsletter();

		}

	}

	public function add_options() {
		global $wp_list_table;

		$option = 'per_page';
		$args = array(
			'label' => 'Número de itens por página',
			'default' => 20,
			'option' => 'investidores_per_page'
		);
		add_screen_option( $option, $args );

		$wp_list_table = new Investidores_List_Table();
	}

	public function investidor_table_set_option($status, $option, $value) {
		return $value;
	}

	public function  thfo_menu_html(){
		global $wp_list_table;

		echo '<div class="wrap"><h1 class="wp-heading-inline">' . get_admin_page_title() . '</h1>';
		echo '<form id="investidores-filter" method="get">';
		echo '<input type="hidden" name="page" value="wpcasa-mail-alert" />';

		$wp_list_table->prepare_items();
		$wp_list_table->search_box('Pesquisar Investidores', 'search_id');
		$wp_list_table->display();

		echo '</form></div>';
		?>
		<script type="text/javascript">
			jQuery('a.delete-investidor').click(function(event){
				if(!confirm("Deseja excluir este investidor?")){
					event.preventDefault();
				}
			});
		</script>
		<?php
	}

	public function thfo_delete_subscriber(){
		if (isset($_GET['action']) && $_GET['action'] == 'delete'){
			global $wpdb;
			$investidor = $_GET['investidor'];
			
			if(is_array($investidor)){
				$investidor = implode( ',', array_map( 'absint', $investidor ) );
				$wpdb->query( "DELETE FROM {$wpdb->prefix}wpcasama_mailalert WHERE ID IN($investidor)" );
			} else {
				$wpdb->delete("{$wpdb->prefix}wpcasama_mailalert", array('id' => $investidor));
			}
		}
	}

}