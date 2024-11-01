=== Stock Market Overview ===
Contributors: stockdio
Tags: stocks, ticker, quote, finance, quotes, stock, financial, index, indices, market, list, overview, commodity, commodities, currency, currencies, forex, foreign exchange, equity, equities
License: See www.stockdio.com/wordpress for details.
Requires at least: 3.1
Tested up to: 6.6
Stable tag: 1.6.16
At-a-glance display of stock market, with categories for Equities, Indices, Commodities and Currencies. Supports over 65 world exchanges.

== Description ==

Stockdio's Stock Market Overview contain a plugin and a widget that provide the means to display a categorized list of equities, market indices, currencies and commodities with their prices and variations. Over 65 different stock exchanges and a large number of market indices, currencies and commodities are supported. Optionally, an interactive price chart can be included with the list.

If you're using the standard Gutenberg editor, the easiest way to include this plugin on your page is using the Stock Market Overview block, which is included in the Stockdio Financial Visualizations category.

If you're using a different editor o prefer to use the shortcode, below is a sample to help you start. Please be aware that most of the parameters listed below are optional, and are also available through the plugin's settings page. Any parameter you include in the shortcode will overwrite the parameter used in the settings page.

`[stock-market-overview stockExchange="NYSENasdaq" width="100%" palette="financial-light"]`

This plugin is part of the Stockdio Financial Widgets, which also includes the following plugins:

* [Stockdio Historical Chart](https://wordpress.org/plugins/stockdio-historical-chart/).
* [Stock Quotes List](https://wordpress.org/plugins/stock-quotes-list/).
* [Stock Market News](https://wordpress.org/plugins/stock-market-news/).
* [Stock Market Ticker](https://wordpress.org/plugins/stock-market-ticker/).
* [Economic & Market News](https://wordpress.org/plugins/economic-market-news/).

The following parameters are supported in the shortcode and also available through the plugin's settings page:

**stockExchange**: The exchange market the symbols belong to (optional). If not specified, NYSE/NASDAQ will be used by default. For a list of available exchanges please visit www.stockdio.com/exchanges.

**includeEquities**: If enabled (true), the Equities category will be included in the list (optional).

**equities**: A list of one or more valid stock symbols from the given exchange, separated by a semicolon (;), e.g. AAPL;MSFT;GOOG;LSE:VOD. To include a symbol from a different exchange, this must be specified as a prefix and separated from the symbol with a colon (:), for example LSE:VOD. If not specified, a default list of equities for the given exchange will be used (optional).

**includeIndices**: If enabled (true), the Indices category will be included in the list (optional).

**indices**: A list of one or more valid index symbols, separated by a semicolon (;), e.g. SPX;DJI;IXIC. For a list of valid indices, visit http://www.stockdio.com/indices. If not specified, a default list of indices related to the given exchange will be used (optional).

**includeCommodities**: If enabled (true), the Commodities category will be included in the list (optional).

**commodities**: One or more valid commodities, separated by a semicolon (;), e.g. GC;SI;NG. For a list of valid commodities, visit http://www.stockdio.com/commodities. If not specified, a default list of commodities will be used (optional).

**includeCurrencies**: If enabled (true), the Currencies category will be included in the list (optional).

**currencies**: One or more valid currency pairs, in the format currency-base/currency-target, separated by a semicolon (;), e.g. EUR/USD;GBP/USD;USD/CAD. For a list of valid currencies, visit http://www.stockdio.com/currencies. If not specified, a default list of currency pairs for the given exchange will be used (optional).

**width**: Width of the list in either px or % (default: 100%).

**height**: Height of the list in pixels. If not specified, the list height will be calculated automatically.

**title**: Allows to specify a title for the list, e.g. Market Overview (optional).

**intraday**: If enabled (true), auto refresh intraday delayed data will be used if available for the exchange. For a list of exchanges with intraday data available, please visit http://www.stockdio.com/exchanges.

**includeChart**: Allows to include an interactive chart along with the list (optional).

**chartHeight**: Height of the chart in pixels (default: 200px).

**includeLogo**: Allows to include/exclude a column with the stock logo or index country flag, if available. Use includeLogo=false to hide the logo (optional).

**logoMaxHeight**: Specify the maximum height allowed for the logo. The height may be smaller than the maximum, depending on the logo width, as it maintains the logo's aspect ratio (optional).

**logoMaxWidth**: Specify the maximum width allowed for the logo. The width may be smaller than the maximum, depending on the logo height, as it maintains the logo's aspect ratio (optional).

**includeEquitiesSymbol**: Allows to include/exclude a column with the stock symbol in the Equities category. Use includeEquitiesSymbol=false to hide the symbol (optional).

**includeEquitiesName**: Allows to include/exclude a column with the stock name in the Equities category. Use includeEquitiesName=true to show the name (optional).

**includeIndicesSymbol**: Allows to include/exclude a column with the index symbol in the Indices category. Use includeIndicesSymbol=true to show the symbol (optional).

**includeIndicesName**: Allows to include/exclude a column with the index name in the Indices category. Use includeIndicesName=false to hide the name (optional).

**includeCommoditiesSymbol**: Allows to include/exclude a column with the commodity symbol in the Commodities category. Use includeCommoditiesSymbol=true to show the symbol (optional).

**includeCommoditiesName**: Allows to include/exclude a column with the commodity name in the Commodities category. Use includeCommoditiesName=false to hide the name (optional).

**includeCurrenciesSymbol**: Allows to include/exclude a column with the currency pair symbol in the Currencies category. Use includeCurrenciesSymbol=false to hide the symbol (optional).

**includeCurrenciesName**: Allows to include/exclude a column with the currency pair name in the Currencies category. Use includeCurrenciesName=true to show the name (optional).

**includePrice**: Allows to include/exclude a column with the latest stock price. Use includePrice=false to hide the stock price (optional).

**includeChange**: Allows to include/exclude a column with the stock price change. Use includeChange=false to hide the price change (optional).

**includePercentChange**: Allows to include/exclude a column with the stock price percentual change. Use includePercentChange=false to hide the price percent change (optional).

**includeTrend**: Allows to include/exclude a column with the stock price trend icon (up/down/neutral). Use includeTrend=false to hide the trend icon (optional).

**includeVolume**: Allows to include/exclude a column with the latest volume. By default, volume is not visible. Use includeVolume=true to show it (optional).

**showHeader**: Allows to display the list header. Use showHeader=false to hide it (optional).

**showCurrency**: Allows to display the currency symbol next to the price, depending on the culture settings.

**allowSort**: If enabled (true), it allows the end user to sort the data by any of the fields, by clicking on the header, if this is visible.

**culture**: Allows to specify a combination of language and country settings, used to display texts and to format numbers and dates, e.g. Spanish-Spain (optional). For a list of available culture combinations please visit http://www.stockdio.com/cultures.

**motif**: Design used to display the visualization with specific aesthetics, including borders and styles, among other elements (optional). For a list of available motifs please visit www.stockdio.com/motifs.

**palette**: Includes a set of consistent colors used for the visualization (optional). For a list of available palettes please visit www.stockdio.com/palettes.

**font**: Allows to specify the font that will be used to render the chart. Multiple fonts may be specified separated by comma, e.g. Lato,Helvetica,Arial (optional).

**displayPrices**: Allows to specify how to display the prices on the chart (if enabled), using one of the following options (default: Line):

* Line
* Candlestick
* Area
* OHLC
* HLC

**allowPeriodChange**: If enabled (true), it provides a UI to allow the end user to select the period for the data to be displayed in the chart. This UI is enabled by default.

**days**: Allows to specify the number of days for the period to display in the chart (if enabled). If not specified, its default value is 365 days.

**loadDataWhenVisible**: Allows to fetch the data and display the visualization only when it becomes visible on the page, in order to avoid using calls (requests) when they are not needed. This is particularly useful when the visualization is not visible on the page by default, but it becomes visible as result of a user interaction (e.g. clicking on an element, etc.). It is also useful when using the same visualization multiple times on a page for different devices (e.g. using one instance of the plugin for mobile and another one for desktop). We recommend not using this by default but only on scenarios as those described above, as it may provide the end user with a small delay to display the visualization (optional).

== Installation ==

1. Upload the `StockdioPlugin` folder to your `/wp-content/plugins/` directory.

2. Activate the "Stock Market Overview" plugin in your WordPress administration interface.

3. If you want to change the preset defaults, go to the Stock Market Overview settings page.

4. If you're using the standard Gutenberg editor, add a Stock Market Overview block from the Stockdio Financial Visualizations category and configure it using the settings sidebar.

5. If you prefer to use the shortcode, insert the `[stock-market-overview]` shortcode into your post content, customizing it with the appropriate parameters. You also have the option to use the Stock Market Overview widget included when you install the plugin.

6. For ease of use, a Stockdio icon is available in the toolbar of the HTML editor for certain versions of WordPress (see screenshots for details).

== Frequently Asked Questions ==

= How do I integrate the Stockdio Stock Market Overview in my page? =

There are three options to integrate it: a. Using the Stock Market Overview block, b. Using the short code, or c. Through the use of the widget in your sidebars.

= How do I know if the Stock Exchange I need is supported by Stockdio? =

Stockdio supports over 65 different world exchanges. For a list of all exchanges currently supported, please visit [www.stockdio.com/exchanges](http://www.stockdio.com/exchanges). If the stock exchange you're looking for is not in the list, please contact us to info@stockdio.com. Once you have found in the list the stock exchange you need, you must pass the corresponding Exchange Symbol using the stockExchange parameter.

= How do I specify the symbols to display? =

You can specify as many symbols as you want, from the selected exchange, separated by semi-colon (;). If any of the symbols you want to display does not show up, you can go to [http://finance.stockdio.com](http://finance.stockdio.com) to verify if the symbol is currently available in Stockdio. If the symbol you require is missing, please contact us at info@stockdio.com.

= Can I combine more than one stock exchange on the same list? =

Yes. The exchange you define in the stockExchange parameter will be the default stock exchange to be used. However, if you want to include symbols from a different exchange, you must prefix the symbol with the exchange code and a colon (:). For example, if you want to include two symbols from NYSE/Nasdaq but additionally include one symbol from London Stock Exchange, you would specify stockExchange="NYSENasdaq", and equities="AAPL;MSFT;LSE:BAG".

= Can I include one or more market indices in the stock market overview? =

Yes, you can include them in the indices parameter. For example, use SPX for S&P 500 or DJI for the Dow Jones. For a complete list of indices currently supported, please visit [www.stockdio.com/indices](http://www.stockdio.com/indices)

= Can I include Commodities in the overview? =

Yes, you can include them using the commodities parameter, separated by semi-colon (;). For example, use GC;SI;CL for Gold, Silver and Crude Oil. For a complete list of commodities currently supported by Stockdio, please visit [www.stockdio.com/commodities](http://www.stockdio.com/commodities)

= Can I include Currencies? =

Yes, you can include one or more currency pairs in the currencies parameter, separated by semi-colon (;). For example, use EUR/USD;USD/JPY;GBP/USD for Euro vs. USD, USD vs. Japanese Yen and British Pound vs. USD. For a complete list of currencies currently supported by Stockdio, please visit [www.stockdio.com/currencies](http://www.stockdio.com/currencies)

= I would like to specify a custom name for a given symbol. Can I do that? =

Yes, we understand there are several scenarios in which you may want to display your own name, such as if you would like to display a commodities ticker in your own language. This can be easily done by specifying your custom name between parenthesis, right after you have specified the symbol. For example, you can display the commodities in Spanish specifying the following in the commodities parameter: GC(Oro);SI(Plata);CL(Petr�leo Crudo). This works for any symbol, index, commodity or currency pair.

= Can I specify the numbers and dates format used in my country/region? =

Yes, Stockdio supports a number of cultures, used to properly display numbers and dates. For a complete list of cultures currently supported by Stockdio, please visit [www.stockdio.com/cultures](http://www.stockdio.com/cultures).

= Can I specify my own colors for the market overview? =

Yes, this plugin is provided with a number of predefined color palettes, for ease of use. For a complete list of color palettes currently supported by Stockdio, please visit [www.stockdio.com/palettes](http://www.stockdio.com/palettes). However, if you need specific color customization, you can use the Stock Market Overview block, or you can use the Stockdio iframe available at [http://services.stockdio.com](http://services.stockdio.com), which supports more options.

= Is the list data real-time or delayed? =

In most cases the data is delayed but the delay time may vary between 1 minute and 20 minutes, depending on the exchange. For details of intraday delay time for each exchange please visit [www.stockdio.com/exchanges](http://www.stockdio.com/exchanges).

= The company logo for one of the symbols is not correct or updated, can this be fixed? =

Sure! Simply contact us to info@stockdio.com with the correct or updated logo and we will update it, once it has been verified.

= Can I place more than one overview on the same page? =

Yes. By default, all lists will use the values specified in the plugin settings page. However, any of these values can be overridden using the appropriate shortcode parameter. Each shortcode can be customized entirely independent.

= How can I contact Stockdio if something is not working as expected? =

Simply send an email to info@stockdio.com with your question and we will reply as soon as possible.

== Screenshots ==

1. Example of stock market overview used to display a NYSE/Nasdaq.

2. Example of stock market overview, with interactive chart.

3. Example of stock market overview using Face motif and Humanity palette, using German-Germany culture.

4. Example of stock market overview using Material motif and Whitespace palette, using Spanish-Spain culture.

5. Example of quotes board using Semantic motif and High-Contrast palette, using French-Canada culture.

6. Stockdio Historical Chart is also available as a complement to the Stock Market Overview. 

7. Stockdio Stock Quotes List is also available as a complement to the Stock Market Overview. 

8. Stockdio Stock Market News is also available as a complement to the Stock Market Overview.

9. Stockdio Stock Market Ticker is also available as a complement to the Stock Market Overview.

10. Settings page.

11. Stockdio toolbar integration with easy to use dialog.

12. Stock Market Overview widget dialog.

13. Stock Market Overview block as part of the Stockdio Financial Visualizations category.

14. Stock Market Overview block sidebar settings.

== Changelog ==
= 1.6.16 =
Release date: July 18, 2024

* Fixes issue with block editor.

= 1.6.15 =
Release date: May 29, 2024

* Fixes stock search issues.

= 1.6.14 =
Release date: April 29, 2024

* Fixes issue with Stock Exchange in Settings page.

= 1.6.13 =
Release date: March 07, 2024

* Fixes vulnerability issue.

= 1.6.12 =
Release date: March 05, 2024

* Fixes vulnerability issue.

= 1.6.11 =
Release date: February 02, 2024
* Minor bug fixes.

= 1.6.10 =
Release date: November 01, 2023

* Fixes vulnerability issue.

= 1.6.9 =
Release date: March 30, 2023

* Minor bug fixes.

= 1.6.7 =
Release date: May 24, 2022

* Minor bug fixes.

= 1.6.6 =
Release date: June 29, 2021

* Minor bug fixes.

= 1.6.5 =
Release date: May 03, 2021

* Minor bug fixes.

= 1.6.4 =
Release date: January 27, 2021

* Minor bug fixes to properly support compatibility with legacy versions of WordPress.

= 1.6.3 =
Release date: January 24, 2021

* Minor block bug fixes and enhancements.

= 1.6.2 =
Release date: January 19, 2021

* Minor block bug fixes and enhancements.

= 1.6.1 =
Release date: January 14, 2021

* Addition of wizard to easily support selection of symbols.
* Minor bug fixes and security enhancements.

= 1.5.2 =
Release date: June 19, 2020

Bug Fixes:

* Minor block bug fixes and enhancements.

= 1.5.1 =
Release date: June 18, 2020

* Addition of the Stock Market Overview block for easy configuration in the standard Gutenberg editor.

= 1.4.16 =
Release date: May 7, 2020

* Change to support referrals on certain browsers

= 1.14.15 =
Release date: April 02, 2020

* Support for new culture: Traditional Chinese

= 1.4.14 =
Release date: December 09, 2019

* Fixes issue with Load Data When Visible setting.

= 1.4.13=
Release date: August 16, 2019

* Support for NEO Exchange (NEO).

= 1.4.12=
Release date: May 23, 2019

* Include Equities Name was not being saved in Settings page.

= 1.4.11 =
Release date: January 31, 2019

* Fixes issue with deprecated functions.

= 1.4.10 =
Release date: October 24, 2018

* Fixes issue with ticker auto calculated height.

= 1.4.8 =
Release date: October 03, 2018

* Support for new cultures: Turkish, Arabic, Hebrew, Swedish, Danish, Finnish, Norwegian, Icelandic, Greek, Czech, Thai, Vietnamese, Hindi, Indonesian

= 1.4.8 =
Release date: June 05, 2018
 
New features:
 
* Support for ability load data only when the visualization becomes visible. Please refer to the documentation for details.

= 1.4.6 =
Release date: May 14, 2018

* Fixes issue with deprecated functions.

= 1.4.4 =
Release date: November 30, 2017

* Support for WordPress 4.9

= 1.4.3 =
Release date: August 3, 2017

Bug Fixes:

* Fixes an issue that might cause some visualizations to appear cut off.

= 1.4.2 =
Release date: August 2, 2017

* Enhancements on mobile display.

= 1.4.1 =
Release date: June 21, 2017

Bug Fixes:

* Some properties in Settings page and shortcode were not being honored during plugin rendering.

= 1.4 =
Release date: June 12, 2017

* Support for BATS ETF (included in the NYSENasdaq stockExchange category).

= 1.3 =
Release date: June 12, 2017
* Support for BATS ETF (included in the NYSENasdaq stockExchange category).

= 1.2 =
Release date: May 25, 2017

* Support for Canadian Securities Exchange (CSE).
* Support for new language and culture: Polish-Poland.
* Fixes a bug that ignored the stock exchange specified in the plugin.

= 1.1 =
Release date: May 16, 2017

New features:

* Ability to show currency symbol next to the price.
* End user can now sort the list by any displayed field (e.g. symbol, name, price, percent change, etc.)
* Stock Market Overview Widget is now available along with the plugin, for even easier integration.

= 1.0 =
* Initial version. Release date: March 20, 2017

== Upgrade Notice ==
