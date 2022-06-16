<?php

/*
Plugin Name: Equibles Stocks
Plugin URI: https://www.equibles.com/api/pricing
Description: A brief description of the Plugin.
Version: 1.0
Author: Equibles
Author URI: https://www.equibles.com/
License: GPL2
*/

use Carbon_Fields\Carbon_Fields;
use Carbon_Fields\Container;
use Carbon_Fields\Field\Field;
use EquiblesStocks\ApiException;
use GuzzleHttp\HandlerStack;
use Kevinrob\GuzzleCache\CacheMiddleware;
use Kevinrob\GuzzleCache\Storage\FlysystemStorage;
use Kevinrob\GuzzleCache\Strategy\GreedyCacheStrategy;
use League\Flysystem\Adapter\Local;

require_once('vendor/autoload.php');


// Equibles Stocks settings page
function equibles_stocks_carbon_fields(): void {
	Carbon_Fields::boot();
}

add_action('after_setup_theme', 'equibles_stocks_carbon_fields');

function equibles_stocks_settings_page(): void {
	Container::make( 'theme_options', __( 'Equibles Stocks' ) )
	         ->set_page_parent( 'options-general.php' )
	         ->add_fields( array(
		         Field::make( 'text', 'equibles_stocks_api_key', 'API Key' )
		              ->set_attribute( 'maxLength', 128 ),
                 Field::make( 'text', 'equibles_stocks_cache_time', 'How long should the API requests be cached. We recommend to use a cache time of 15 minutes to improve the page load time. Use 0 to disable cache. ' )
                     ->set_attribute('min', 0)->set_attribute('type', 'number'),
	         ));
}
add_action('carbon_fields_register_fields', 'equibles_stocks_settings_page');


// Daily stock prices shortcode
function equibles_stock($attrs): ?string {
    static $stockData = null;
	$default = array(
		'ticker' => null,
		'type' => 'daily_prices',
		'subtype' => 'close',
		'time_format' => '',
		'decimal_places' => $attrs["subtype"] == "volume" ? 0 : 2,
		'decimal_separator' => '.',
		'thousands_separator' => ' ',
	);
	$options = shortcode_atts($default, $attrs);

    $cache_time = (int) carbon_get_theme_option('equibles_stocks_cache_time');


	// Configure Guzzle cache plugin
	if($cache_time > 0){
		$stack = HandlerStack::create();
		$stack->push(
			new CacheMiddleware(
				new GreedyCacheStrategy(
					new FlysystemStorage(
						new Local(sys_get_temp_dir())
					),
                    $cache_time * 60
				)
			), 'equibles_stock'
		);
		$httpClient = new GuzzleHttp\Client(['handler' => $stack]);
	}else{
		$httpClient = new GuzzleHttp\Client();
	}

	// Configure API key authorization
	$config = EquiblesStocks\Configuration::getDefaultConfiguration();
	$pricesClient = new EquiblesStocks\Clients\PricesApi($httpClient, $config);
	$apiKey = carbon_get_theme_option('equibles_stocks_api_key');


	try {
        if($stockData == null) {
            $result = $pricesClient->summary($apiKey, $options["ticker"]);
            if ($result->getCount() <= 0) {
                return "-";
            }
            $stockData = $result->getResults();
        }


		if($options["type"] === "daily_prices") {
            if ( $options["subtype"] === "change_percentage" ) {
                return equibles_number_format( $stockData->getLatestCompletedTradingDayChangePercentage(), $options);
            }
            if ( $options["subtype"] === "change" ) {
                return equibles_number_format( $stockData->getLatestCompletedTradingDayChange(), $options);
            }
			if ( $options["subtype"] === "high" ) {
				return equibles_number_format( $stockData->getLatestCompletedTradingDayHigh(), $options);
			}
			if ( $options["subtype"] === "low" ) {
				return equibles_number_format( $stockData->getLatestCompletedTradingDayLow(), $options);
			}
			if ( $options["subtype"] === "close" ) {
				return equibles_number_format( $stockData->getLatestCompletedTradingDayClose(), $options);
			}
			if ( $options["subtype"] === "open" ) {
				return equibles_number_format( $stockData->getLatestCompletedTradingDayOpen(), $options);
			}
			if ( $options["subtype"] === "volume" ) {
				return equibles_number_format( $stockData->getLatestCompletedTradingDayVolume(), $options );
			}
			if ( $options["subtype"] === "time" ) {
				return $stockData->getLatestCompletedTradingDayTime()->format( $options['time_format'] );
			}
			return "Invalid value in option subtype";
		}

		if($options["type"] === "intraday_prices"){
            if ( $options["subtype"] === "change_percentage" ) {
                return equibles_number_format( $stockData->getTodayChangePercentage(), $options);
            }
            if ( $options["subtype"] === "change" ) {
                return equibles_number_format( $stockData->getTodayChange(), $options);
            }
			if ( $options["subtype"] === "high" ) {
				return equibles_number_format( $stockData->getTodayHigh(), $options);
			}
			if ( $options["subtype"] === "low" ) {
				return equibles_number_format( $stockData->getTodayLow(), $options);
			}
			if ( $options["subtype"] === "close" || $options["subtype"] === "current_price" ) {
				return equibles_number_format( $stockData->getCurrentPrice(), $options);
			}
			if ( $options["subtype"] === "open" ) {
				return equibles_number_format( $stockData->getTodayOpen(), $options);
			}
			if ( $options["subtype"] === "volume" ) {
				return equibles_number_format( $stockData->getTodayVolume(), $options );
			}
			if ( $options["subtype"] === "time" ) {
				return $stockData->getTodayTime()->format( $options['time_format'] );
			}
			return "Invalid value in option subtype";
		}

		if($options["type"] === "52_week"){
			if ( $options["subtype"] === "high" ) {
				return equibles_number_format( $stockData->getFiftyTwoWeekHigh(), $options);
			}
			if ( $options["subtype"] === "low" ) {
				return equibles_number_format( $stockData->getFiftyTwoWeekLow(), $options);
			}
			return "Invalid value in option subtype";
		}

		return "Invalid value in option type";
	} catch ( ApiException $e ) {
		return json_decode( $e->getResponseBody() )->ErrorMessage;
	} catch ( Exception $e ) {
		return "Error while performing the request.";
	}
}

function equibles_number_format($number, $options) : ?string {
	$decimal_places = $options['decimal_places'] ?? 0;
	$decimal_separator = $options['decimal_separator'] ?? '.';
	$thousands_separator = $options['thousands_separator'] ?? ',';
	$number = round($number, $decimal_places);
	return number_format($number, $decimal_places, $decimal_separator, $thousands_separator);
}

add_shortcode('equibles_stock', 'equibles_stock');

