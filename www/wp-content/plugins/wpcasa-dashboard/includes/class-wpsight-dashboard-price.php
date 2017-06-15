<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

/**
 * Class WPSight_Dashboard_Price
 */
class WPSight_Dashboard_Price {
	
	/**
	 *	Initialize class
	 */
	public static function init() {
	}

	/**
	 *	get_listing_price()
	 *
	 *	Get the price amount of a specific listing.
	 *	
	 *	@access	public
	 *	@param	integer	$listing_id
	 *	@uses	get_post_meta()
	 *	@return	bool|float
	 *
	 *	@since	1.1.0
	 */
	public static function get_listing_price( $listing_id ) {

		$price = get_post_meta( $listing_id, 'price', true );

		if ( empty( $price ) || ! is_numeric( $price ) )
			return false;

		return $price;

	}

	/**
	 *	get_price()
	 *
	 *	Return the formatted price with currency and prefix + suffix.
	 *	
	 *	@access	public
	 *	@param	integer	$post_id
	 *	@uses	get_the_id()
	 *	@uses	get_post_meta()
	 *	@return	bool|string
	 *
	 *	@since	1.1.0
	 */
	public static function get_price( $post_id = false ) {

		if ( false === $post_id )
			$post_id = get_the_id();

		$custom = get_post_meta( $post_id, 'price_custom', true );

		if ( ! empty( $custom ) )
			return $custom;

		$price = get_post_meta( $post_id, 'price', true );

		if ( empty( $price ) || ! is_numeric( $price ) )
			return false;

		$price = self::format_price( $price );

		$prefix = get_post_meta( $post_id, 'price_before', true );
		$suffix = get_post_meta( $post_id, 'price_after', true );

		if ( ! empty( $prefix ) )
			$price = $prefix . ' ' . $price;

		if ( ! empty( $suffix ) )
			$price = $price .  ' ' . $suffix;

		return $price;

	}

    /**
	 *	get_price_in_cents()
	 *
     *	Calculate a price in cents.
     *	
     *	@access	public
     *	@param	$amount
     *	@return	integer
     */
    public static function get_price_in_cents( $amount ) {
        return intval( $amount * 100 );
    }

	/**
	 *	format_price()
	 *
	 *	Format price with current currency.
	 *	
	 *	@access	public
	 *	@param	$amount
	 *	@uses	self::current_currency()
	 *	@return	bool|string
	 *
	 *	@since	1.1.0
	 */
	public static function format_price( $amount ) {

		if ( ! isset( $amount ) )
			echo $amount . ' is not set';

		if ( ! isset( $amount ) || ! is_numeric( $amount ) )
			return false;

		$currency = self::current_currency();

		$amount = $amount * $currency['rate'];

		$amount_parts_dot = explode( '.', $amount );
		$amount_parts_col = explode( ',', $amount );

		if ( count( $amount_parts_dot ) > 1 || count( $amount_parts_col ) > 1 ) {
			$decimals = $currency['money_decimals'];
		} else {
			$decimals = 0;
		}

		$dec_point = $currency['money_dec_point'];
		$thousands_separator = $currency['money_thousands_separator'];
		$thousands_separator = str_replace("space", " ", $thousands_separator );

		$amount = number_format( $amount, $decimals, $dec_point, $thousands_separator );

		$currency_symbol = $currency['symbol'];
		$currency_show_symbol_after = $currency['show_symbol_after'];
		
		$space = $currency['symbol_space'] ? ' ' : '';

		if ( ! empty( $currency_symbol ) ) {
			if ( $currency_show_symbol_after ) {
				$price = $amount . $space . $currency_symbol;
			} else {
				$price = $currency_symbol . $space . $amount;
			}
		} else {
			$price = $amount;
		}

		return $price;

	}

	/**
	 *	current_currency()
	 *
	 *	Return current currency.
	 *	
	 *	@access public
	 *	@uses	wpsight_get_option()
	 *	@uses	wpsight_get_currency_abbr()
	 *	@uses	wpsight_get_currency()
	 *	@return string
	 *
	 *	@since	1.1.0
	 */
	public static function current_currency() {
		
		$currency = array(
			'code'						=> wpsight_get_currency_abbr( wpsight_get_option( 'currency' ) ),
			'symbol'					=> wpsight_get_currency(),
			'symbol_space'				=> false,
			'show_symbol_after'			=> 'after' == wpsight_get_option( 'currency_symbol' ) ? true : false,
			'rate'						=> 1,
			'money_decimals'			=> 0,
			'money_dec_point'			=> 'comma' == wpsight_get_option( 'currency_separator' ) ? '.' : ',',
			'money_thousands_separator'	=> 'comma' == wpsight_get_option( 'currency_separator' ) ? ',' : '.'
		);

		return apply_filters( 'wpsight_dashboard_current_currency', $currency );

	}

	/**
	 *	default_currency()
	 *
	 *	Return default currency.
	 *	
	 *	@access	public
	 *	@uses	self::default_currency_code()
	 *	@uses	self::default_currency_symbol()
	 *	@return	array
	 *
	 *	@since	1.1.0
	 */
	public static function default_currency() {
		return array(
			'code'		=> self::default_currency_code(),
			'symbol'	=> self::default_currency_symbol(),
			'rate'		=> 1
		);
	}

	/**
	 *	default_currency_code()
	 *
	 *	Return currency code of the default currency.
	 *	
	 *	@access	public
	 *	@uses	self::current_currency()
	 *	@return	string
	 *
	 *	@since	1.1.0
	 */
	public static function default_currency_code() {
		
		$current_currency = self::current_currency();

		if ( ! empty( $current_currency ) && is_array( $current_currency ) ) {			
			$currency_code = $current_currency['code'];
		} else {
			$currency_code = 'USD';
		}

		return $currency_code;

	}

	/**
	 *	default_currency_symbol()
	 *
	 *	Return the symbol of the default currency.
	 *	
	 *	@access	public
	 *	@uses	self::current_currency()
	 *	@return	string
	 */
	public static function default_currency_symbol() {
		
		$current_currency = self::current_currency();

		if ( ! empty( $current_currency ) && is_array( $current_currency ) ) {
			$currency_symbol = $current_currency['symbol'];
		} else {
			$currency_symbol = '$';
		}

		return $currency_symbol;

	}

}

WPSight_Dashboard_Price::init();
