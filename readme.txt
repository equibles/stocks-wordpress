=== Equibles Stocks ===
Contributors: equibles
Tags: stocks, stock prices, equibles, stock quotes, stocks api
Requires at least: 5.8
Tested up to: 6.0
Requires PHP: 7.2
Stable tag: 1.0.1
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Equibles Stocks allows to display stock quotes provided by Equibles on your Wordpress website using our shortcodes.


== Description ==
Equibles Stocks allows you to display stock quotes provided by Equibles on your Wordpress website.
After installing the plugin you should register on [Equibles](https://www.equibles.com/) and get your API key for free.
Once you have got your API key you should save it in the plugin options page (Options -> Equibles Stocks).
After that the plugin setup is completed and you can start using the shortcodes provided by this plugin.


=== Examples ===

==== Displaying the daily closing price for a stock ====
    [equibles_stock ticker="AAPL" type="daily_prices" subtype="close"]

    [equibles_stock ticker="AAPL" type="daily_prices" subtype="time" time_format="d/m/Y"]


=== Documentation ===
Is this section we describe the shortcode parameters available.

==== Parameter "ticker" ====
The ticker of a common stock supported by Equibles. Example: AAPL

==== Parameter "type" ====
The type of data to show. The available options are the following.

- daily_prices - Data related to the daily price of the selected ticker (updated every 24 hours).
- intraday_prices - Data related to the intraday price of the selected ticker (updated every minute).
- 52_week - Data related to the 52 week range of the selected stock.

==== Parameter "subtype" ====
The subtype of data to show. This parameter depends on the value of the "type" option.
The available options are:

- daily_prices
  - high - The high value of the daily price candle.
  - low - The low value of the daily price candle.
  - close - The close value of the daily price candle.
  - open - The open value of the daily price candle.
  - time - The time of close of the candle.
  - volume - The amount of shares traded during the period.
  - change - The absolute change in the stock price.
  - change_percentage - The percentage change in the stock price.
- intraday_prices
  - high - The high value of the price candle.
  - low - The low value of the price candle.
  - close - The close value of the price candle.
  - open - The open value of the price candle.
  - time - The time of close of the candle.
  - volume - The amount of shares traded during the period.
  - change - The absolute change in the stock price.
  - change_percentage - The percentage change in the stock price.
- 52_week
  - high - The 52-week high of the selected stock.
  - low - The 52-week low of the selected stock.

==== Parameter "decimal_places" ====
The number of decimal places to show when formatting a number, default: 2.

==== Parameter "decimal_separator" ====
The decimal separator, default: . (dot) .

==== Parameter "thousands_separator" ====
The thousands' separator, default: " " (space).

==== Parameter "time_format" ====
The format of the time as in the PHP format function.

=== Settings page ===
You should save your API in the plugin settings page (Settings -> Equibles Stocks).
You can also enable caching for the API requests. We recommend using a cache time of 15 minutes to improve your site load time.


= Where can I report bugs? =

Report bugs on the [Equibles Stocks GitHub repository](https://github.com/equibles/stocks-wordpress/issues?utm_medium=referral&utm_source=wordpress.org&utm_campaign=wp_org_repo_listing). 


== Installation ==

= Minimum Requirements =

* PHP 7.2 or greater is recommended


= Automatic installation =

Automatic installation is the easiest option -- WordPress will handle the file transfer, and you won’t need to leave your web browser. To do an automatic install of Equibles Stocks, log in to your WordPress dashboard, navigate to the Plugins menu, and click “Add New.”
 
In the search field type “Equibles Stocks,” then click “Search Plugins.” Once you’ve found us, click “Install Now,” and WordPress will take it from there.

= Manual installation =

Manual installation method requires downloading the Equibles Stocks plugin and uploading it to your web server via your favorite FTP application. The WordPress codex contains [instructions on how to do this here](https://wordpress.org/support/article/managing-plugins/#manual-plugin-installation).

= Updating =

Automatic updates should work smoothly, but we still recommend you back up your site.

