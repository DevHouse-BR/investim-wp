<?php 

class Investidores_List_Table extends WP_List_Table {

	public function __construct() {
		parent::__construct( array(
			'singular'=> 'investidor',
			'plural' => 'investidores',
			'ajax'   => false
		));
	}

	function no_items() {
		_e( 'Nenhum investidor cadastrado.' );
	}

	public function get_columns() {
		return array(
			'cb'					=> '<input type="checkbox" />',
			'name'					=> 'Name',
			'email'					=> 'Email',
			'tel'					=> 'Telefone',
			'mobile'				=> 'Celular',
			'skype'					=> 'Skype',
			'min_price'				=> 'Preço Mínimo',
			'max_price'				=> 'Preço Máximo',
			'country'				=> 'País',
			'state'					=> 'Estado',
			'city'					=> 'Cidade',
			'third_party_capital'	=> 'Capital Terceiros',
			'prefered_city'			=> 'Cidade Preferida',
			'sector'				=> 'Setor',
			'description'			=> 'Descrição',
		);
	}

	public function get_hidden_columns() {
		$hidden = get_user_option( 'managetoplevel_page_wpcasa-mail-alertcolumnshidden' );
		if($hidden) return $hidden;
		else return array();
	}

	public function get_sortable_columns() {
		return $sortable = array(
			'name'		=> array('name', false),
			'max_price'	=> array('max_price', false)
		);
	}

	public function column_default( $item, $column_name ) {
		switch( $column_name ) { 
			case "min_price": 
				return "R$ " . number_format( $item->min_price, 2, ',', '.' );
			case "max_price": 
				return "R$ " . number_format( $item->max_price, 2, ',', '.' );
			case "third_party_capital": 
				return $item->third_party_capital ? 'Sim' : 'Não';
			default:
				return $item->$column_name;
		}
	}

	protected function column_cb( $item ) {
		return sprintf(
        	'<input type="checkbox" name="investidor[]" value="%s" />', $item->id
        );
	}

	function column_name($item) {
		$actions = array(
			//'edit'		=> sprintf( '<a href="?page=%s&action=%s&investidor=%s">Edit</a>',$_REQUEST['page'],'edit', $item->id ),
			'delete'		=> sprintf( '<a class="delete-investidor" href="?page=%s&action=%s&investidor=%s">Delete</a>',$_REQUEST['page'],'delete', $item->id ),
			//'delete'	=> sprintf( '<a  href="?page=%s&delete=yes&id=%s">Delete</a>',$_REQUEST['page'], $item->id ),
		);

		return sprintf('%1$s %2$s', $item->name, $this->row_actions($actions) );
	}

	function get_bulk_actions() {
		$actions = array(
			'delete'    => 'Delete'
		);
		return $actions;
	}

	public function prepare_items() {
		global $wpdb;

		$action = $this->current_action();

		$query = "SELECT * FROM {$wpdb->prefix}wpcasama_mailalert";

		// Search parameters
		$search = !empty($_GET["s"]) ? mysql_real_escape_string($_GET["s"]) : '';
		if ( !empty($search) ) { 
			$query .= " WHERE name LIKE '%" . $search . "%'"; 
		} 

		// Ordering parameters
		$orderby = !empty($_GET["orderby"]) ? mysql_real_escape_string($_GET["orderby"]) : 'ASC';
		$order = !empty($_GET["order"]) ? mysql_real_escape_string($_GET["order"]) : '';
		if(!empty($orderby) & !empty($order)){ 
			$query .= ' ORDER BY ' . $orderby . ' ' . $order; 
		}

		// Pagination parameters
		$totalitems = $wpdb->query($query);
		$perpage = $this->get_items_per_page('investidores_per_page', 20);
		$paged = !empty($_GET["paged"]) ? mysql_real_escape_string($_GET["paged"]) : '';
		
		// Page Number
		if ( (empty($paged)) || (!is_numeric($paged)) || ($paged <= 0) ) { 
			$paged = 1; 
		} 
		
		$totalpages = ceil($totalitems / $perpage); 
		
		if ( (!empty($paged)) && (!empty($perpage)) ) { 
			$offset = ($paged - 1) * $perpage;
			$query .= ' LIMIT ' . (int) $offset . ',' . (int) $perpage; 
		} 

		// Register the pagination
		$this->set_pagination_args( array(
			"total_items"	=> $totalitems,
			"total_pages"	=> $totalpages,
			"per_page"		=> $perpage
		));

		// Register the Columns
		$columns = $this->get_columns();
		$hidden = $this->get_hidden_columns();
		$sortable = $this->get_sortable_columns();
		$this->_column_headers = array($columns, $hidden, $sortable);

		// Fetch the items
		$this->items = $wpdb->get_results($query);
	}

}