<?php
/**
 * @package WPCasa-Investim
 */
/*
Plugin Name: WPCasa Investim - Customização
Plugin URI: https://devhouse.com.br/
Description: Plugin desenvolvido especificamente para o site investim.com.br fornecendo customizações para a plataforma WPCasa.
Version: 1.0.0
Author: Leonardo Lima de Vasconcellos
Author URI: https://devhouse.com.br/sobre/
License: GPLv2 or later
Text Domain: wpcasa-investim
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

Copyright 2005-2015 Automattic, Inc.
*/

// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}

/**
 *	load_plugin_textdomain()
 *	
 *	Set up the text domain for the plugin
 *	and load language files.
 *	
 *	@uses	plugin_basename()
 *	@uses	load_plugin_textdomain()
 *	
 *	@since 1.0.0
 */
load_plugin_textdomain( 'wpcasa-investim', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );


/**
 * Detalhes de anúncio customizados para a Investim
 */

$detalhes = array(
	'razao_social' => array(
		'id'			=> 'razao_social',
		'label'			=> __( 'Razão Social', 'wpcasa-investim' ),
		'unit'			=> '',
		'description'	=> '',
		'query_var'		=> 'razao_social',
		'data_compare'	=> 'LIKE',
		'data_type'		=> 'CHAR',
		'dashboard'		=> true,
		'position'		=> 10
	),
	'cnpj' => array(
		'id'			=> 'cnpj',
		'label'			=> __( 'CNPJ', 'wpcasa-investim' ),
		'unit'			=> '',
		'description'	=> '',
		'query_var'		=> 'cnpj',
		'data_compare'	=> 'LIKE',
		'data_type'		=> 'CHAR',
		'dashboard'		=> true,
		'position'		=> 20
	),
	'constituicao' => array(
		'id'			=> 'constituicao',
		'label'			=> __( 'Constituição', 'wpcasa-investim' ),
		'unit'			=> '',
		'data'			=> array( 
			'' 			=> 'Selecione', 
			'simples' 	=> 'Sociedade Simples', 
			'limitada' 	=> 'Sociedade Limitada', 
			'anonima' 	=> 'Sociedade Anônima'
		),
		'description'	=> '',
		'query_var'		=> 'constituicao',
		'data_compare'	=> 'LIKE',
		'data_type'		=> 'CHAR',
		'dashboard'		=> true,
		'position'		=> 30
	),
	'tributacao' => array(
		'id'			=> 'tributacao',
		'label'			=> __( 'Tributação', 'wpcasa-investim' ),
		'unit'			=> '',
		'data'			=> array( 
			''			=> 'Selecione', 
			'simples'	=> 'Simples', 
			'presumido' => 'Lucro Presumido', 
			'real' 		=> 'Lucro Real'
		),
		'description'	=> '',
		'query_var'		=> 'tributacao',
		'data_compare'	=> 'LIKE',
		'data_type'		=> 'CHAR',
		'dashboard'		=> true,
		'position'		=> 40
	),
	'ativos' => array(
		'id'			=> 'ativos',
		'label'			=> __( 'Lista de Ativos e Equipamentos', 'wpcasa-investim' ),
		'unit'			=> '',
		'description'	=> '',
		'query_var'		=> 'ativos',
		'data_compare'	=> 'LIKE',
		'data_type'		=> 'CHAR',
		'dashboard'		=> true,
		'position'		=> 50
	),
	'valor_estoque' => array(
		'id'			=> 'valor_estoque',
		'label'			=> __( 'Valor do Estoque a Preço de Custo', 'wpcasa-investim' ),
		'unit'			=> 'R$',
		'description'	=> 'Sem símbolos de moeda ou separadores de milhares',
		'query_var'		=> 'valor_estoque',
		'data_compare'	=> '>=',
		'data_type'		=> 'numeric',
		'dashboard'		=> true,
		'position'		=> 60
	),
	'produtos' => array(
		'id'			=> 'produtos',
		'label'			=> __( 'Linha de Produtos', 'wpcasa-investim' ),
		'unit'			=> '',
		'description'	=> '',
		'query_var'		=> 'produtos',
		'data_compare'	=> 'LIKE',
		'data_type'		=> 'CHAR',
		'dashboard'		=> true,
		'position'		=> 70
	),
	'servicos' => array(
		'id'			=> 'servicos',
		'label'			=> __( 'Linha de Serviços', 'wpcasa-investim' ),
		'unit'			=> '',
		'description'	=> '',
		'query_var'		=> 'servicos',
		'data_compare'	=> 'LIKE',
		'data_type'		=> 'CHAR',
		'dashboard'		=> true,
		'position'		=> 80
	),
	'fundacao' => array(
		'id'			=> 'fundacao',
		'label'			=> __( 'Ano de Fundação', 'wpcasa-investim' ),
		'unit'			=> '',
		'description'	=> '',
		'query_var'		=> 'fundacao',
		'data_compare'	=> 'LIKE',
		'data_type'		=> 'CHAR',
		'dashboard'		=> true,
		'position'		=> 90
	),
	'motivo_venda' => array(
		'id'			=> 'motivo_venda',
		'label'			=> __( 'Motivo da Venda', 'wpcasa-investim' ),
		'unit'			=> '',
		'description'	=> '',
		'query_var'		=> 'motivo_venda',
		'data_compare'	=> 'LIKE',
		'data_type'		=> 'CHAR',
		'dashboard'		=> true,
		'position'		=> 100
	),
	'vol_vendas_a_1' => array(
		'id'			=> 'vol_vendas_a_1',
		'label'			=> __( 'Volume de Vendas Anuais (último ano)', 'wpcasa-investim' ),
		'unit'			=> 'R$',
		'description'	=> '(3 últimos anos - Sem símbolos de moeda ou separadores de milhares)',
		'query_var'		=> 'vol_vendas_a_1',
		'data_compare'	=> '>=',
		'data_type'		=> 'numeric',
		'dashboard'		=> true,
		'position'		=> 110
	),
	'vol_vendas_a_2' => array(
		'id'			=> 'vol_vendas_a_2',
		'label'			=> __( 'Volume de Vendas Anuais (penúltimo ano)', 'wpcasa-investim' ),
		'unit'			=> 'R$',
		'description'	=> '(3 últimos anos - Sem símbolos de moeda ou separadores de milhares)',
		'query_var'		=> 'vol_vendas_a_2',
		'data_compare'	=> '>=',
		'data_type'		=> 'numeric',
		'dashboard'		=> true,
		'position'		=> 120
	),
	'vol_vendas_a_3' => array(
		'id'			=> 'vol_vendas_a_3',
		'label'			=> __( 'Volume de Vendas Anuais (antepenúltimo ano)', 'wpcasa-investim' ),
		'unit'			=> 'R$',
		'description'	=> '(3 últimos anos - Sem símbolos de moeda ou separadores de milhares)',
		'query_var'		=> 'vol_vendas_a_3',
		'data_compare'	=> '>=',
		'data_type'		=> 'numeric',
		'dashboard'		=> true,
		'position'		=> 130
	),
	'faturamento_mensal' => array(
		'id'			=> 'faturamento_mensal',
		'label'			=> __( 'Faturamento Mensal Médio', 'wpcasa-investim' ),
		'unit'			=> 'R$',
		'description'	=> 'Sem símbolos de moeda ou separadores de milhares',
		'query_var'		=> 'faturamento_mensal',
		'data_compare'	=> '>=',
		'data_type'		=> 'numeric',
		'dashboard'		=> true,
		'position'		=> 140
	),
	'lucro_bruto' => array(
		'id'			=> 'lucro_bruto',
		'label'			=> __( 'Lucro Bruto', 'wpcasa-investim' ),
		'unit'			=> 'R$',
		'description'	=> 'Sem símbolos de moeda ou separadores de milhares',
		'query_var'		=> 'lucro_bruto',
		'data_compare'	=> '>=',
		'data_type'		=> 'numeric',
		'dashboard'		=> true,
		'position'		=> 150
	),
	'margem_lucro' => array(
		'id'			=> 'margem_lucro',
		'label'			=> __( 'Margem de Lucro', 'wpcasa-investim' ),
		'unit'			=> '%',
		'description'	=> '',
		'query_var'		=> 'margem_lucro',
		'data_compare'	=> '>=',
		'data_type'		=> 'numeric',
		'dashboard'		=> true,
		'position'		=> 160
	),
	'lucro_liquido' => array(
		'id'			=> 'lucro_liquido',
		'label'			=> __( 'Lucro Líquido Médio', 'wpcasa-investim' ),
		'unit'			=> 'R$',
		'description'	=> 'Sem símbolos de moeda ou separadores de milhares',
		'query_var'		=> 'lucro_liquido',
		'data_compare'	=> '>=',
		'data_type'		=> 'numeric',
		'dashboard'		=> true,
		'position'		=> 170
	),
	'imovel' => array(
		'id'			=> 'imovel',
		'label'			=> __( 'Imóvel', 'wpcasa-investim' ),
		'unit'			=> '',
		'data'			=> array( 
			'proprio' 	=> 'Próprio', 
			'alugado' 	=> 'Alugado'
		),
		'description'	=> '',
		'query_var'		=> 'imovel',
		'data_compare'	=> '=',
		'data_type'		=> 'CHAR',
		'dashboard'		=> true,
		'position'		=> 180
	),
	'endividamento' => array(
		'id'			=> 'endividamento',
		'label'			=> __( 'Endividamento', 'wpcasa-investim' ),
		'unit'			=> '',
		'description'	=> '',
		'query_var'		=> 'endividamento',
		'data_compare'	=> 'LIKE',
		'data_type'		=> 'CHAR',
		'dashboard'		=> true,
		'position'		=> 190
	),
	'condicoes_venda' => array(
		'id'			=> 'condicoes_venda',
		'label'			=> __( 'Condições de Venda ou de Participação na Empresa', 'wpcasa-investim' ),
		'unit'			=> '',
		'description'	=> '',
		'query_var'		=> 'condicoes_venda',
		'data_compare'	=> 'LIKE',
		'data_type'		=> 'CHAR',
		'dashboard'		=> true,
		'position'		=> 200
	),
	'numero_funcionarios' => array(
		'id'			=> 'numero_funcionarios',
		'label'			=> __( 'Número de Funcionários', 'wpcasa-investim' ),
		'unit'			=> '',
		'description'	=> '',
		'query_var'		=> 'numero_funcionarios',
		'data_compare'	=> '>=',
		'data_type'		=> 'numeric',
		'dashboard'		=> true,
		'position'		=> 210
	),
	'diferencial' => array(
		'id'			=> 'diferencial',
		'label'			=> __( 'Diferencial do Negócio', 'wpcasa-investim' ),
		'unit'			=> '',
		'description'	=> '',
		'query_var'		=> 'diferencial',
		'data_compare'	=> 'LIKE',
		'data_type'		=> 'CHAR',
		'dashboard'		=> true,
		'position'		=> 220
	),
);



add_filter( 'wpsight_details', 'wpsight_details_custom' );
function wpsight_details_custom( $details ) {
	global $detalhes;
	return $detalhes;
}


/**
 * Customização de metaboxes
 */
add_filter( 'wpsight_meta_box_listing_details', 'wpsight_meta_box_listing_details_custom' );
function wpsight_meta_box_listing_details_custom( $meta_box ) {

	$meta_box['fields']['ativos']['type'] = 'textarea';
	$meta_box['fields']['produtos']['type'] = 'textarea';
	$meta_box['fields']['servicos']['type'] = 'textarea';
	$meta_box['fields']['motivo_venda']['type'] = 'textarea';
	$meta_box['fields']['imovel']['type'] = 'radio';
	$meta_box['fields']['endividamento']['type'] = 'textarea';
	$meta_box['fields']['condicoes_venda']['type'] = 'textarea';
	$meta_box['fields']['diferencial']['type'] = 'textarea';

	return $meta_box;

}


/**
 * Customização de medidas
 */
add_filter( 'wpsight_measurements', 'wpsight_measurements_custom' );
function wpsight_measurements_custom( $measurements ) {

	$measurements['R$'] = 'R$';
	$measurements['%'] = '%';

	return $measurements;

}


/**
 * Formatação da exibição dos detalhes do anúncio
 */
add_filter( 'wpsight_get_listing_details', 'wpsight_get_listing_details_custom', 10, 4);
function wpsight_get_listing_details_custom( $listing_details, $post_id, $details, $formatted ) {
	
	if( $formatted == "wpsight-listing-details" ) {
		return parse_wpsight_listing_details( $listing_details, $post_id, $details );
	} elseif ( $formatted == "wpsight-listing-summary" ) {
		return parse_wpsight_listing_summary( $listing_details, $post_id, $details );
	}

}

function parse_wpsight_listing_details( $listing_details, $post_id, $details ) {
	global $detalhes;

	foreach ($details as $key => $value) {
		if ($detalhes[$value]['unit'] == 'R$') {
			$listing_details = formata_valor($listing_details, $value);
		} elseif ($detalhes[$value]['data_type'] == 'CHAR') {
			$listing_details = formata_nova_linha($listing_details, $value);
		}
	}

	return $listing_details;
}

function parse_wpsight_listing_summary( $listing_details, $post_id, $details ) {
	global $detalhes;

	foreach ($details as $key => $value) {
		if ($detalhes[$value]['unit'] == 'R$') {
			$listing_details = formata_valor($listing_details, $value);
		}
	}

	return $listing_details;
}

function resgata_html_detalhe( $listing_details, $campo ) {

	$campo = str_replace( "_", "-", $campo );

	$pattern = '/<span class="listing-' . $campo . '(.*?)"><span class="listing-details-label">(.*?)<\/span>.<span class="listing-details-value">(.*?)<\/span><\/span>/s';
	
	preg_match( $pattern, $listing_details, $matches );

	return $matches;

}

function formata_valor( $listing_details, $campo ) {

	$matches = resgata_html_detalhe( $listing_details, $campo );

	$valor = trim( str_replace( 'R$', '', $matches[3] ) );

	$valor = 'R$ ' . number_format( $valor, 2, ',', '.' );

	$replacement = str_replace( $matches[3], $valor, $matches[0] );

	return str_replace( $matches[0], $replacement, $listing_details );

}

function formata_nova_linha( $listing_details, $campo ) {

	$matches = resgata_html_detalhe( $listing_details, $campo );

	$valor = str_replace( "\n", '<br/>', $matches[3] );

	$replacement = str_replace( $matches[3], $valor, $matches[0] );

	return str_replace( $matches[0], $replacement, $listing_details );

}

add_filter( 'wpsight_get_currency', 'wpsight_get_currency_custom' );
function wpsight_get_currency_custom( $currency_ent  ) {

	if ( $currency_ent == " BRL " ) {
		return " R$ ";
	} else {
		return $currency_ent;
	}

}

add_filter( 'wpsight_get_listing_price', 'wpsight_get_listing_price_custom', 10, 5 );
function wpsight_get_listing_price_custom( $listing_price, $post_id, $before, $after, $args ) {

	$pattern = '/content="(.*?)">(.*?)<\/span>/';
	
	preg_match( $pattern, $listing_price, $matches );

	$valor = number_format( $matches[1], 2, ',', '.' );

	$replacement = str_replace( $matches[2], $valor, $listing_price );

	return str_replace( $matches[2], $valor, $listing_price );

}

add_filter( 'wpsight_meta_boxes', 'wpsight_meta_boxes_custom' );
function wpsight_meta_boxes_custom( $meta_boxes ) {

	$fields = array(
		'responsavel' => array(
			'name'      => __( 'Responável', 'wpcasa' ),
			'id'        => '_contato_responsavel',
			'type'      => 'text',
			'desc'      => '',
			'class'     => '',
			'dashboard'	=> true,
			'priority'  => 10
		),
		'telefone' => array(
			'name'      => __( 'Telefone', 'wpcasa' ),
			'id'        => '_contato_telefone',
			'type'      => 'text',
			'desc'      => '',
			'dashboard'	=> true,
			'priority'  => 20
		),
		'whatsapp' => array(
			'name'      => __( 'Whatsapp', 'wpcasa' ),
			'id'        => '_contato_whatsapp',
			'type'      => 'text',
			'desc'      => '',
			'dashboard'	=> true,
			'priority'  => 30
		),
		'email' => array(
			'name'      => __( 'E-mail', 'wpcasa' ),
			'id'        => '_contato_email',
			'type'      => 'text',
			'desc'      => '',
			'dashboard'	=> true,
			'priority'  => 40
		),
		'skype' => array(
			'name'      => __( 'Skype', 'wpcasa' ),
			'id'        => '_contato_skype',
			'type'      => 'text',
			'desc'      => '',
			'dashboard'	=> true,
			'priority'  => 50
		),
	);

	$meta_boxes["contato"] = array(
		'id'       => 'contato',
		'title'    => __( 'Informações de Contato', 'wpcasa' ),
		'object_types'    => array( 'listing' ),
		'context'  => 'normal',
		'priority' => 'high',
		'fields'   => $fields
	);

	return $meta_boxes;

}