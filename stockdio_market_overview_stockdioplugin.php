<?php
/*
	Plugin Name: Stock Market Overview
	Plugin URI: http://www.stockdio.com
	Description: At-a-glance display of stock market, with categories for Equities, Indices, Commodities and Currencies. Supports over 65 world exchanges.
	Author: Stockdio
	Version: 1.6.16
	Author URI: http://www.stockdio.com
*/
//set up the admin area options page
define('stockdio_overview_version','1.6.16');
define( 'stockdio_market_overview__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
class StockdioMarketOverviewSettingsPage
{
		public static function get_page_url( $page = 'config' ) {

		$args = array( 'page' => 'stockdio-market-overview-settings-config' );

		$url = add_query_arg( $args, class_exists( 'Jetpack' ) ? admin_url( 'admin.php' ) : admin_url( 'options-general.php' ) );

		return $url;
	}
	public static function view( $name) {
		$file = stockdio_market_overview__PLUGIN_DIR . $name . '.php';
		include( $file );
	}
	
	public static function display_admin_alert() {
		self::view( 'stockdio_market_overview_activate_plugin_admin' );
	}
	public static function display_settings_alert() {
		self::view( 'stockdio_market_overview_activate_plugin_settings' );
	}
	
	public static function stockdio_market_overview_display_notice() {
		global $hook_suffix;
		$stockdio_market_overview_options = get_option( 'stockdio_market_overview_options' );
		$api_key = $stockdio_market_overview_options['api_key'];
		/*print $hook_suffix;*/
		if (($hook_suffix == 'plugins.php' || in_array( $hook_suffix, array( 'jetpack_page_stockdio-market-overview-key-config', 'settings_page_stockdio-market-overview-key-config', 'settings_page_stockdio-market-overview-settings-config', 'jetpack_page_stockdio-market-overview-settings-config' ))) && empty($api_key))
		{
			if ($hook_suffix == 'plugins.php')
				self::display_admin_alert();
			else
				self::display_settings_alert();
		}
		
	}
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $stockdio_market_overview_options;

    /**
     * Start up
     */
    public function __construct()
    {
		
        add_action( 'admin_menu', array( $this, 'stockdio_market_overview_add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'stockdio_market_overview_page_init' ) );
		add_action( 'admin_notices', array( $this, 'stockdio_market_overview_display_notice' ) );
		add_action('admin_head', 'stockdio_market_overview_stockdio_js');
		add_action('admin_head', 'stockdio_market_overview_charts_button');
    }
	
    /**
     * Add options page
     */
    public function stockdio_market_overview_add_plugin_page()
    {
        // This page will be under "Settings"
        add_options_page(
            'Stock Market Overview Settings', 
            'Stock Market Overview', 
            'manage_options', 
            'stockdio-market-overview-settings-config', 
            array( $this, 'stockdio_market_overview_create_admin_page' )
        );
    }

    /**
     * Options page callback
     */
    public function stockdio_market_overview_create_admin_page()
    {
        // Set class property
        $this->options = get_option( 'stockdio_market_overview_options' );
        ?>
</link>

<div class="wrap">
  <h2>Stock Market Overview Settings</h2>
  <div class="stockdio_market_overview_form">
    <form method="post" action="options.php">
      <?php
					// This prints out all hidden setting fields
					settings_fields( 'stockdio_market_overview_option_group' );   
					do_settings_sections( 'stockdio-market-overview-settings-config' );
					submit_button(); 
				?>
    </form>
  </div>
</div>
<?php
    }


    /**
     * Register and add settings
     */
    public function stockdio_market_overview_page_init()
    {        
		$stockdio_market_overview_options = get_option( 'stockdio_market_overview_options' );
		$api_key = $stockdio_market_overview_options['api_key'];
		//delete_option( 'stockdio_market_overview_options'  );
		register_setting(
			'stockdio_market_overview_option_group', // Option group
			'stockdio_market_overview_options', // Option name
			array( $this, 'stockdio_market_overview_sanitize' ) // stockdio_market_overview_sanitize
		);
		
		if (empty($api_key)) {
			add_settings_section(
				'setting_section_id', // ID
				'', // Title
				array( $this, 'stockdio_market_overview_print_section_empty_app_key_info' ), // Callback
				'stockdio-market-overview-settings-config' // Page
			);  

			add_settings_field(
				'api_key', // ID
				'App-Key', // Title 
				array( $this, 'stockdio_market_overview_api_key_callback' ), // Callback
				'stockdio-market-overview-settings-config', // Page
				'setting_section_id' // Section        
			);  
		}
		else {
			add_settings_section(
				'setting_section_id', // ID
				'', // Title
				array( $this, 'stockdio_market_overview_print_section_info' ), // Callback
				'stockdio-market-overview-settings-config' // Page
			);  

			add_settings_field(
				'api_key', // ID
				'Api Key', // Title 
				array( $this, 'stockdio_market_overview_api_key_callback' ), // Callback
				'stockdio-market-overview-settings-config', // Page
				'setting_section_id' // Section        
			);  

			add_settings_field(
				'default_exchange', // ID
				'Exchange', // Title 
				array( $this, 'stockdio_market_overview_exchange_callback' ), // Callback
				'stockdio-market-overview-settings-config', // Page
				'setting_section_id' // Section           
			);
			
			
			
			add_settings_field(
				'default_includeEquities', // ID
				'Include Equities', // Title 
				array( $this, 'stockdio_market_overview_includeEquities_callback' ), // Callback
				'stockdio-market-overview-settings-config', // Page
				'setting_section_id' // Section           
			);
			add_settings_field(
				'default_equities', // ID
				'Equities', // Title 
				array( $this, 'stockdio_market_overview_equities_callback' ), // Callback
				'stockdio-market-overview-settings-config', // Page
				'setting_section_id' // Section           
			);
			add_settings_field(
				'default_includeIndices', // ID
				'Include Indices', // Title 
				array( $this, 'stockdio_market_overview_includeIndices_callback' ), // Callback
				'stockdio-market-overview-settings-config', // Page
				'setting_section_id' // Section           
			);
			add_settings_field(
				'default_indices', // ID
				'Indices', // Title 
				array( $this, 'stockdio_market_overview_indices_callback' ), // Callback
				'stockdio-market-overview-settings-config', // Page
				'setting_section_id' // Section           
			);
			add_settings_field(
				'default_includeCommodities', // ID
				'Include Commodities', // Title 
				array( $this, 'stockdio_market_overview_includeCommodities_callback' ), // Callback
				'stockdio-market-overview-settings-config', // Page
				'setting_section_id' // Section           
			);
			add_settings_field(
				'default_commodities', // ID
				'Commodities', // Title 
				array( $this, 'stockdio_market_overview_commodities_callback' ), // Callback
				'stockdio-market-overview-settings-config', // Page
				'setting_section_id' // Section           
			);
			add_settings_field(
				'default_includeCurrencies', // ID
				'Include Currencies', // Title 
				array( $this, 'stockdio_market_overview_includeCurrencies_callback' ), // Callback
				'stockdio-market-overview-settings-config', // Page
				'setting_section_id' // Section           
			);
			add_settings_field(
				'default_currencies', // ID
				'Currencies', // Title 
				array( $this, 'stockdio_market_overview_currencies_callback' ), // Callback
				'stockdio-market-overview-settings-config', // Page
				'setting_section_id' // Section           
			);
			
			add_settings_field(
				'default_allowSort', // ID
				'Allow Sort', // Title 
				array( $this, 'stockdio_market_overview_allowSort_callback' ), // Callback
				'stockdio-market-overview-settings-config', // Page
				'setting_section_id' // Section           
			);
					
			
			add_settings_field(
				'default_width', // ID
				'Width', // Title 
				array( $this, 'stockdio_market_overview_width_callback' ), // Callback
				'stockdio-market-overview-settings-config', // Page
				'setting_section_id' // Section           
			);
			
			add_settings_field(
				'default_height', // ID
				'Height', // Title 
				array( $this, 'stockdio_market_overview_height_callback' ), // Callback
				'stockdio-market-overview-settings-config', // Page
				'setting_section_id' // Section           
			);
			
			add_settings_field(
				'default_title', // ID
				'Title', // Title 
				array( $this, 'stockdio_market_overview_title_callback' ), // Callback
				'stockdio-market-overview-settings-config', // Page
				'setting_section_id' // Section           
			);  
			
			add_settings_field(
				'default_includeChart', // ID
				'Include Chart', // Title 
				array( $this, 'stockdio_market_overview_includeChart_callback' ), // Callback
				'stockdio-market-overview-settings-config', // Page
				'setting_section_id' // Section           
			);	
			
			add_settings_field(
				'default_chartHeight', // ID
				'Chart Height', // Title 
				array( $this, 'stockdio_market_overview_chartHeight_callback' ), // Callback
				'stockdio-market-overview-settings-config', // Page
				'setting_section_id' // Section           
			);
			
			add_settings_field(
				'default_includeLogo', // ID
				'Include Logo', // Title 
				array( $this, 'stockdio_market_overview_includeLogo_callback' ), // Callback
				'stockdio-market-overview-settings-config', // Page
				'setting_section_id' // Section           
			);

			add_settings_field(
				'default_logoMaxHeight', // ID
				'Logo Maximum Height', // Title 
				array( $this, 'stockdio_market_overview_logoMaxHeight_callback' ), // Callback
				'stockdio-market-overview-settings-config', // Page
				'setting_section_id' // Section           
			);

			add_settings_field(
				'default_logoMaxWidth', // ID
				'Logo Maximum Width ', // Title 
				array( $this, 'stockdio_market_overview_logoMaxWidth_callback' ), // Callback
				'stockdio-market-overview-settings-config', // Page
				'setting_section_id' // Section           
			);
			
			
			add_settings_field(
				'default_includeEquitiesSymbol', // ID
				'Include Equities Symbol', // Title 
				array( $this, 'stockdio_market_overview_includeEquitiesSymbol_callback' ), // Callback
				'stockdio-market-overview-settings-config', // Page
				'setting_section_id' // Section           
			);
			add_settings_field(
				'default_includeEquitiesName', // ID
				'Include Equities Name', // Title 
				array( $this, 'stockdio_market_overview_includeEquitiesName_callback' ), // Callback
				'stockdio-market-overview-settings-config', // Page
				'setting_section_id' // Section           
			);
			add_settings_field(
				'default_includeIndicesSymbol', // ID
				'Include Indices Symbol', // Title 
				array( $this, 'stockdio_market_overview_includeIndicesSymbol_callback' ), // Callback
				'stockdio-market-overview-settings-config', // Page
				'setting_section_id' // Section           
			);
			add_settings_field(
				'default_includeIndicesName', // ID
				'Include Indices Name', // Title 
				array( $this, 'stockdio_market_overview_includeIndicesName_callback' ), // Callback
				'stockdio-market-overview-settings-config', // Page
				'setting_section_id' // Section           
			);
			add_settings_field(
				'default_includeCommoditiesSymbol', // ID
				'Include Commodities Symbol', // Title 
				array( $this, 'stockdio_market_overview_includeCommoditiesSymbol_callback' ), // Callback
				'stockdio-market-overview-settings-config', // Page
				'setting_section_id' // Section           
			);
			add_settings_field(
				'default_includeCommoditiesName', // ID
				'Include Commodities Name', // Title 
				array( $this, 'stockdio_market_overview_includeCommoditiesName_callback' ), // Callback
				'stockdio-market-overview-settings-config', // Page
				'setting_section_id' // Section           
			);
			add_settings_field(
				'default_includeCurrenciesSymbol', // ID
				'Include Currencies Symbol', // Title 
				array( $this, 'stockdio_market_overview_includeCurrenciesSymbol_callback' ), // Callback
				'stockdio-market-overview-settings-config', // Page
				'setting_section_id' // Section           
			);
			add_settings_field(
				'default_includeCurrenciesName', // ID
				'Include Currencies Name', // Title 
				array( $this, 'stockdio_market_overview_includeCurrenciesName_callback' ), // Callback
				'stockdio-market-overview-settings-config', // Page
				'setting_section_id' // Section           
			);			
			
			
			
			add_settings_field(
				'default_includePrice', // ID
				'Include Price', // Title 
				array( $this, 'stockdio_market_overview_includePrice_callback' ), // Callback
				'stockdio-market-overview-settings-config', // Page
				'setting_section_id' // Section           
			);
			add_settings_field(
				'default_includeChange', // ID
				'Include Change', // Title 
				array( $this, 'stockdio_market_overview_includeChange_callback' ), // Callback
				'stockdio-market-overview-settings-config', // Page
				'setting_section_id' // Section           
			);
			add_settings_field(
				'default_includePercentChange', // ID
				'Include Percent Change', // Title 
				array( $this, 'stockdio_market_overview_includePercentChange_callback' ), // Callback
				'stockdio-market-overview-settings-config', // Page
				'setting_section_id' // Section           
			);
			add_settings_field(
				'default_includeTrend', // ID
				'Include Trend', // Title 
				array( $this, 'stockdio_market_overview_includeTrend_callback' ), // Callback
				'stockdio-market-overview-settings-config', // Page
				'setting_section_id' // Section           
			);
			add_settings_field(
				'default_includeVolume', // ID
				'Include Volume', // Title 
				array( $this, 'stockdio_market_overview_includeVolume_callback' ), // Callback
				'stockdio-market-overview-settings-config', // Page
				'setting_section_id' // Section           
			);
			add_settings_field(
				'default_showHeader', // ID
				'Show Header', // Title 
				array( $this, 'stockdio_market_overview_showHeader_callback' ), // Callback
				'stockdio-market-overview-settings-config', // Page
				'setting_section_id' // Section           
			);	
			
			add_settings_field(
				'default_culture', // ID
				'Culture', // Title 
				array( $this, 'stockdio_market_overview_culture_callback' ), // Callback
				'stockdio-market-overview-settings-config', // Page
				'setting_section_id' // Section           
			);			
					
			add_settings_field(
				'default_motif', // ID
				'Motif', // Title 
				array( $this, 'stockdio_market_overview_motif_callback' ), // Callback
				'stockdio-market-overview-settings-config', // Page
				'setting_section_id' // Section           
			);
			
			add_settings_field(
				'default_palette', // ID
				'Palette', // Title 
				array( $this, 'stockdio_market_overview_palette_callback' ), // Callback
				'stockdio-market-overview-settings-config', // Page
				'setting_section_id' // Section           
			);		
			
			add_settings_field(
				'default_font', // ID
				'Font', // Title 
				array( $this, 'stockdio_market_overview_font_callback' ), // Callback
				'stockdio-market-overview-settings-config', // Page
				'setting_section_id' // Section           
			);	
			
			add_settings_field(
				'booleanIniCheck', // ID
				'booleanIniCheck', // Title 
				array( $this, 'stockdio_market_overview_booleanIniCheck_callback' ), // Callback
				'stockdio-market-overview-settings-config', // Page
				'setting_section_id' // Section           
			);	
			
			add_settings_field(
				'default_displayPrices', // ID
				'Display Prices', // Title 
				array( $this, 'stockdio_market_overview_displayPrices_callback' ), // Callback
				'stockdio-market-overview-settings-config', // Page
				'setting_section_id' // Section           
			);		
			
			add_settings_field(
				'default_allowPeriodChange', // ID
				'Allow Period Change', // Title 
				array( $this, 'stockdio_market_overview_allowPeriodChange_callback' ), // Callback
				'stockdio-market-overview-settings-config', // Page
				'setting_section_id' // Section           
			);		

			add_settings_field(
				'default_intraday', // ID
				'Intraday', // Title 
				array( $this, 'stockdio_market_overview_intraday_callback' ), // Callback
				'stockdio-market-overview-settings-config', // Page
				'setting_section_id' // Section           
			);				
			add_settings_field(
				'intradayCheck', // ID
				'', // Title 
				array( $this, 'stockdio_market_overview_intradayCheck_callback' ), // Callback
				'stockdio-market-overview-settings-config', // Page
				'setting_section_id' // Section           
			);				
			add_settings_field(
				'default_days', // ID
				'Days', // Title 
				array( $this, 'stockdio_market_overview_days_callback' ), // Callback
				'stockdio-market-overview-settings-config', // Page
				'setting_section_id' // Section           
			);	
			add_settings_field(
				'default_loadDataWhenVisible', // ID
				'Load Data When Visible', // Title 
				array( $this, 'stockdio_market_overview_loadDataWhenVisible_callback' ), // Callback
				'stockdio-market-overview-settings-config', // Page
				'setting_section_id' // Section           
			); 					
		}
		
		$plugin_data = get_plugin_data( __FILE__ );
		$plugin_version = $plugin_data['Version'];
		$css_address=plugin_dir_url( __FILE__ )."assets/stockdio-wp.css";
		wp_register_script("customAdminCss",$css_address );
		wp_enqueue_style("customAdminCss", $css_address, array(), $plugin_version, false);
		
		$css_tinymce_button_address=plugin_dir_url( __FILE__ )."assets/stockdio-tinymce-button.css";
		wp_register_script("custom_tinymce_button_Css",$css_tinymce_button_address );
		wp_enqueue_style("custom_tinymce_button_Css", $css_tinymce_button_address, array(), $plugin_version, false);
		
		wp_enqueue_script('jquery');

		$version = stockdio_overview_version;
		$js_sortable=plugin_dir_url( __FILE__ )."assets/Sortable.min.js";
		wp_register_script("StockdioSortableJS",$js_sortable, null, $version, false );
		wp_enqueue_script('StockdioSortableJS');

		$js_address=plugin_dir_url( __FILE__ )."assets/stockdio-wp.js";
		wp_register_script("customStockdioJs",$js_address, null, $version, false );
		wp_enqueue_script('customStockdioJs');
		
		$js_addressSearch=plugin_dir_url( __FILE__ )."assets/stockdio_search.js";
		$css_addressSearch=plugin_dir_url( __FILE__ ).'assets/stockdio_search.css?v='.$version;
		if (!function_exists( 'register_block_type')) {
			wp_register_script("customStockdioSearchJS",$js_addressSearch, array( ), $version, false );			
			wp_enqueue_style( 'customStockdioSearchStyles',$css_addressSearch , array() );

			$css_addressSearchOldVersion=plugin_dir_url( __FILE__ ).'assets/stockdio_search_old_version.css?v='.$version;
			wp_enqueue_style( 'customStockdioSearchStylesOldVersion',$css_addressSearchOldVersion , array() );
		}
		else{
			//wp_register_script("customStockdioSearchJS",$js_addressSearch, array( ), $version, false );	
			wp_enqueue_style( 'customStockdioSearchStyles',$css_addressSearch , array( 'wp-components' ) );	
			wp_register_script("customStockdioSearchJS",$js_addressSearch, array( 'wp-api', 'wp-i18n', 'wp-components', 'wp-element' ), $version, false );
		}
		wp_enqueue_script('customStockdioSearchJS');
    }

	public function stockdio_market_overview_sanitize( $input )
    {
        $new_input = array();

        if( isset( $input['api_key'] ) )
            $new_input['api_key'] = esc_attr(sanitize_text_field($input['api_key'] ));
		if( isset( $input['default_exchange'] ) )
            $new_input['default_exchange'] = esc_attr(sanitize_text_field($input['default_exchange'] ));
		
		if( isset( $input['default_includeEquities'] ) )
            $new_input['default_includeEquities'] = esc_attr(sanitize_text_field($input['default_includeEquities'] ));	
		if( isset( $input['default_equities'] ) )
            $new_input['default_equities'] = $input['default_equities'];	
		if( isset( $input['default_includeIndices'] ) )
            $new_input['default_includeIndices'] = esc_attr(sanitize_text_field($input['default_includeIndices'] ));	
		if( isset( $input['default_indices'] ) )
            $new_input['default_indices'] = $input['default_indices'] ;	
		if( isset( $input['default_includeCommodities'] ) )
            $new_input['default_includeCommodities'] = esc_attr(sanitize_text_field($input['default_includeCommodities'] ));	
		if( isset( $input['default_commodities'] ) )
            $new_input['default_commodities'] = $input['default_commodities'] ;	
		if( isset( $input['default_includeCurrencies'] ) )
            $new_input['default_includeCurrencies'] = esc_attr(sanitize_text_field($input['default_includeCurrencies'] ));	
		if( isset( $input['default_currencies'] ) )
            $new_input['default_currencies'] = $input['default_currencies'] ;	
		
		if( isset( $input['default_loadDataWhenVisible'] ) )
            $new_input['default_loadDataWhenVisible'] = esc_attr(sanitize_text_field($input['default_loadDataWhenVisible'] ));
		
		if( isset( $input['default_culture'] ) )
            $new_input['default_culture'] = esc_attr(sanitize_text_field($input['default_culture'] ));
		
		
		
		if( isset( $input['default_allowSort'] ) )
            $new_input['default_allowSort'] = esc_attr(sanitize_text_field($input['default_allowSort'] ));
		
		if( isset( $input['default_width'] ) )
            $new_input['default_width'] = esc_attr(sanitize_text_field($input['default_width'] ));
		if( isset( $input['default_height'] ) )
            $new_input['default_height'] = esc_attr(sanitize_text_field($input['default_height'] ));
		if( isset( $input['default_font'] ) )
            $new_input['default_font'] = esc_attr(sanitize_text_field($input['default_font'] ));				
		if( isset( $input['default_motif'] ) )
            $new_input['default_motif'] = esc_attr(sanitize_text_field($input['default_motif'] ));
		if( isset( $input['default_palette'] ) )
            $new_input['default_palette'] = esc_attr(sanitize_text_field($input['default_palette'] ));
		
		if( isset( $input['default_title'] ) )
            $new_input['default_title'] = $input['default_title'];
		
		if( isset( $input['booleanIniCheck'] ) )
            $new_input['booleanIniCheck'] = esc_attr(sanitize_text_field($input['booleanIniCheck'] ));
		if( isset( $input['default_includeLogo'] ) )
            $new_input['default_includeLogo'] = esc_attr(sanitize_text_field($input['default_includeLogo'] ));
		
		if( isset( $input['default_logoMaxHeight'] ) )
            $new_input['default_logoMaxHeight'] = esc_attr(sanitize_text_field($input['default_logoMaxHeight'] ));
		if( isset( $input['default_logoMaxWidth'] ) )
            $new_input['default_logoMaxWidth'] = esc_attr(sanitize_text_field($input['default_logoMaxWidth'] ));
		
		if( isset( $input['default_includeEquitiesSymbol'] ) )
            $new_input['default_includeEquitiesSymbol'] = esc_attr(sanitize_text_field($input['default_includeEquitiesSymbol'] ));
		if( isset( $input['default_includeIndicesName'] ) )
            $new_input['default_includeIndicesName'] = esc_attr(sanitize_text_field($input['default_includeIndicesName'] ));
		
		if( isset( $input['default_includeEquitiesName'] ) )
            $new_input['default_includeEquitiesName'] = esc_attr(sanitize_text_field($input['default_includeEquitiesName'] ));
		if( isset( $input['default_includeIndicesSymbol'] ) )
            $new_input['default_includeIndicesSymbol'] = esc_attr(sanitize_text_field($input['default_includeIndicesSymbol'] ));
		if( isset( $input['default_includeIndicesName'] ) )
            $new_input['default_includeIndicesName'] = esc_attr(sanitize_text_field($input['default_includeIndicesName'] ));
		
		if( isset( $input['default_includeCommoditiesSymbol'] ) )
            $new_input['default_includeCommoditiesSymbol'] = esc_attr(sanitize_text_field($input['default_includeCommoditiesSymbol'] ));
		if( isset( $input['default_includeCommoditiesName'] ) )
            $new_input['default_includeCommoditiesName'] = esc_attr(sanitize_text_field($input['default_includeCommoditiesName'] ));
		
		if( isset( $input['default_includeCurrenciesSymbol'] ) )
            $new_input['default_includeCurrenciesSymbol'] = esc_attr(sanitize_text_field($input['default_includeCurrenciesSymbol'] ));
		if( isset( $input['default_includeCurrenciesName'] ) )
            $new_input['default_includeCurrenciesName'] = esc_attr(sanitize_text_field($input['default_includeCurrenciesName'] ));
		
		if( isset( $input['default_includePrice'] ) )			
            $new_input['default_includePrice'] = esc_attr(sanitize_text_field($input['default_includePrice'] ));
		if( isset( $input['default_includeChange'] ) )
            $new_input['default_includeChange'] = esc_attr(sanitize_text_field($input['default_includeChange'] ));
		if( isset( $input['default_includePercentChange'] ) )
            $new_input['default_includePercentChange'] = esc_attr(sanitize_text_field($input['default_includePercentChange'] ));		
		if( isset( $input['default_includeTrend'] ) )
            $new_input['default_includeTrend'] = esc_attr(sanitize_text_field($input['default_includeTrend'] ));
		if( isset( $input['default_includeVolume'] ) )
            $new_input['default_includeVolume'] = esc_attr(sanitize_text_field($input['default_includeVolume'] ));
		if( isset( $input['default_showHeader'] ) )
            $new_input['default_showHeader'] = esc_attr(sanitize_text_field($input['default_showHeader'] ));		
		
		if( isset( $input['default_includeChart'] ) )
            $new_input['default_includeChart'] = esc_attr(sanitize_text_field($input['default_includeChart'] ));		
		if( isset( $input['default_chartHeight'] ) )
            $new_input['default_chartHeight'] = esc_attr(sanitize_text_field($input['default_chartHeight'] ));		
		if( isset( $input['default_allowPeriodChange'] ) )
            $new_input['default_allowPeriodChange'] = esc_attr(sanitize_text_field($input['default_allowPeriodChange'] ));		
		if( isset( $input['default_intraday'] ) )
			$new_input['default_intraday'] = esc_attr(sanitize_text_field($input['default_intraday'] ));	
		if( isset( $input['intradayCheck'] ) )
			$new_input['intradayCheck'] = esc_attr(sanitize_text_field($input['intradayCheck'] ));		
		if( isset( $input['default_days'] ) )
            $new_input['default_days'] = esc_attr(sanitize_text_field($input['default_days'] ));		
		if( isset( $input['default_displayPrices'] ) )
            $new_input['default_displayPrices'] = esc_attr(sanitize_text_field($input['default_displayPrices'] ));		

        return $new_input;
    }
	

	/**	
     * Print the Section text when app key is empty
     */
    public function stockdio_market_overview_print_section_empty_app_key_info()
    {
        print '<br>
		Enter your app-key here. For more information go to <a href="http://www.stockdio.com/wordpress?wp=1" target="_blank">http://www.stockdio.com/wordpress</a>.
		</p>';
    }
	
    /** 
     * Print the Section text
     */
    public function stockdio_market_overview_print_section_info()
    {
        print '<br/><i>For more information on this plugin, please visit <a href="http://www.stockdio.com/wordpress?wp=1" target="_blank">http://www.stockdio.com/wordpress</a>.</i>';
    }

    /** 
     * Get the settings option array and print one of its values
     */
     public function stockdio_market_overview_api_key_callback()
    {
        printf(
            '<input type="text" id="api_key" name="stockdio_market_overview_options[api_key]" value="%s" />',
            isset( $this->options['api_key'] ) ? esc_attr( $this->options['api_key']) : ''
        );

    }

	
	public function stockdio_market_overview_title_callback()
    {
    	if( empty( $this->options['default_title'] ) )
            $this->options['default_title'] = '' ;
        printf(
            '<input type="text" id="default_title" name="stockdio_market_overview_options[default_title]" value="%s" style="width:300px;" />		
			<p class="description" id="tagline-description">Allows to specify a title to the list, e.g. Market Overview (optional).</p>
			',
            isset( $this->options['default_title'] ) ? esc_attr( $this->options['default_title']) : ''
        );
    }
	
	public function stockdio_market_overview_exchange_callback()
        {
		if( empty( $this->options['default_exchange'] ) )
            $this->options['default_exchange'] = '' ;
        printf(
			'<label id="default_exchange_label" style="font-weight:bold"></label>
			<a href="#" onclick="stockdio_open_exchange_modal(this)" value="Search">Click here to select your exchange</a>		
			<select style="display:none" name="stockdio_market_overview_options[default_exchange]" id="default_exchange">		
			    <option value="" selected="selected">None</option> 
				<option value="Forex">Currencies Trading</option>
				<option value="Commodities">Commodities Trading</option>
				<option value="USA">USA Equities and ETFs</option>
				<option value="OTCMKTS" >USA OTC Markets</option>
				<option value="OTCBB" >USA OTC Bulletin Board</option>
				<option value="LSE" >London Stock Exchange</option>
				<option value="TSE" >Tokyo Stock Exchange</option>
				<option value="HKSE">Hong Kong Stock Exchange</option>
				<option value="SSE">Shanghai Stock Exchange</option>
				<option value="SZSE">Shenzhen Stock Exchange</option>
				<option value="FWB">Deutsche Börse Frankfurt</option>
				<option value="XETRA">XETRA</option>
				<option value="AEX">Euronext Amsterdam</option>
				<option value="BEX">Euronext Brussels</option>
				<option value="PEX">Euronext Paris</option>
				<option value="LEX">Euronext Lisbon</option>
				<option value="CHIX">Australia Chi-X</option>
				<option value="TSX">Toronto Stock Exchange</option>
				<option value="TSXV">TSX Venture Exchange</option>
				<option value="CSE">Canadian Securities Exchange</option>
				<option value="NEO">NEO Exchange</option>
				<option value="SIX">SIX Swiss Exchange</option>
				<option value="KRX">Korean Stock Exchange</option>
				<option value="Kosdaq">Kosdaq Stock Exchange</option>
				<option value="OMXS">NASDAQ OMX Stockholm</option>
				<option value="OMXC">NASDAQ OMX Copenhagen</option>
				<option value="OMXH">NASDAQ OMX Helsinky</option>
				<option value="OMXI">NASDAQ OMX Iceland</option>
				<option value="BSE">Bombay Stock Exchange</option>
				<option value="NSE">India NSE</option>
				<option value="BME">Bolsa de Madrid</option>
				<option value="JSE">Johannesburg Stock Exchange</option>	
				<option value="TWSE">Taiwan Stock Exchange</option>
				<option value="BIT">Borsa Italiana</option>
				<option value="MOEX">Moscow Exchange</option>
				<option value="Bovespa">Bovespa Sao Paulo Stock Exchange</option>
				<option value="NZX">New Zealand Exchange</option>	
				<option value="ISE">Irish Stock Exchange</option>
				<option value="SGX">Singapore Exchange</option>	
				<option value="TADAWUL">Tadawul Saudi Stock Exchange</option>	
				<option value="WSE">Warsaw Stock Exchange</option>	
				<option value="TASE">Tel Aviv Stock Exchange</option>			
				<option value="KLSE">Bursa Malaysia</option>	
				<option value="IDX">Indonesia Stock Exchange</option>		
				<option value="BMV">Bolsa Mexicana de Valores</option>
				<option value="OSE">Oslo Stock Exchange</option>		
				<option value="BCBA">Bolsa de Comercio de Buenos Aires</option>			
				<option value="SET">Stock Exchange of Thailand</option>		
				<option value="VSE">Vienna Stock Exchange</option>		
				<option value="BCS">Bolsa de Comercio de Santigo</option>		
				<option value="BIST">Borsa Istanbul</option>	
				<option value="OMXT">NASDAQ OMX Tallinn</option>	
				<option value="OMXR">NASDAQ OMX Riga</option>	
				<option value="OMXV">NASDAQ OMX Vilnius</option>	
				<option value="PSE">Philippine Stock Exchange</option>
				<option value="ADX">Abu Dhabi Securities Exchange</option>
				<option value="DFM">Dubai Financial Market</option>
				<option value="BVC">Bolsa de Valores de Colombia</option>
				<option value="NGSE">Nigerian Stock Exchange</option>				
				<option value="QSE">Qatar Stock Exchange</option>	
				<option value="TPEX">Taipei Exchange</option>	
				<option value="BVL">Bolsa de Valores de Lima</option>	
				<option value="EGX">The Egyptian Exchange</option>	
				<option value="ASE">Athens Stock Exchange</option>	
				<option value="NASE">Nairobi Securities Exchange</option>	
				<option value="HNX">Hanoi Stock Exchange</option>	
				<option value="HOSE">Hochiminh Stock Exchange</option>	
				<option value="BCPP">Prague Stock Exchange</option>					
				<option value="AMSE">Amman Stock Exchange</option>		
             </select>
			 <p class="description" id="tagline-description">The exchange market the symbols belong to (optional). If not specified, NYSE/NASDAQ will be used by default. For a list of available exchanges please visit <a href="http://www.stockdio.com/exchanges?wp=1" target="_blank">http://www.stockdio.com/exchanges.</a></p>
				<script>document.getElementById("default_exchange").value = "'.$this->options['default_exchange'].'";
				jQuery("#default_exchange_label").text(jQuery("#default_exchange option:selected").text() + " (Exchange code: " + jQuery("#default_exchange").val() +  ")" );
				</script>
			 ',
    		'default_exchange'
    		);
    }


	public function stockdio_market_overview_includeEquities_callback()
    {
		if( !isset( $this->options['booleanIniCheck'] ) ){
			 $this->options['default_includeEquities']=1;
		}	
        printf(
            '<input type="checkbox" id="default_includeEquities" name="stockdio_market_overview_options[default_includeEquities]" value="%s" '. checked( isset($this->options['default_includeEquities'])?$this->options['default_includeEquities']:0,1, false ) .' />			
			<p class="description" id="tagline-description">Allows to include/exclude the Equities category.</p>
			',
            isset( $this->options['default_includeEquities'] ) ? $this->options['default_includeEquities'] : 1
        );		
    }	
	public function stockdio_market_overview_includeIndices_callback()
    {
		if( !isset( $this->options['booleanIniCheck'] ) ){
			 $this->options['default_includeIndices']=1;
		}	
        printf(
            '<input type="checkbox" id="default_includeIndices" name="stockdio_market_overview_options[default_includeIndices]" value="%s" '. checked( isset($this->options['default_includeIndices'])?$this->options['default_includeIndices']:0,1, false ) .' />			
			<p class="description" id="tagline-description">Allows to include/exclude the Indices category.</p>
			',
            isset( $this->options['default_includeIndices'] ) ? $this->options['default_includeIndices'] : 1
        );		
    }
	public function stockdio_market_overview_includeCommodities_callback()
    {
		if( !isset( $this->options['booleanIniCheck'] ) ){
			 $this->options['default_includeCommodities']=1;
		}	
        printf(
            '<input type="checkbox" id="default_includeCommodities" name="stockdio_market_overview_options[default_includeCommodities]" value="%s" '. checked( isset($this->options['default_includeCommodities'])?$this->options['default_includeCommodities']:0,1, false ) .' />			
			<p class="description" id="tagline-description">Allows to include/exclude the Commodities category.</p>
			',
            isset( $this->options['default_includeCommodities'] ) ? $this->options['default_includeCommodities'] : 1
        );		
    }	
	public function stockdio_market_overview_includeCurrencies_callback()
    {
		if( !isset( $this->options['booleanIniCheck'] ) ){
			 $this->options['default_includeCurrencies']=1;
		}	
        printf(
            '<input type="checkbox" id="default_includeCurrencies" name="stockdio_market_overview_options[default_includeCurrencies]" value="%s" '. checked( isset($this->options['default_includeCurrencies'])?$this->options['default_includeCurrencies']:0,1, false ) .' />			
			<p class="description" id="tagline-description">Allows to include/exclude the Currencies category.</p>
			',
            isset( $this->options['default_includeCurrencies'] ) ? $this->options['default_includeCurrencies'] : 1
        );		
    }	

	public function stockdio_market_overview_equities_callback()
    {
    	if( empty( $this->options['default_equities'] ) )
            $this->options['default_equities'] = '' ;
        printf(
			'<label id="default_equities_label" style="max-width: 1000px;display: block;overflow: hidden;overflow-wrap: break-word;font-weight:bold;margin-bottom: 10px">'.(isset( $this->options['default_equities'] ) ? esc_attr( $this->options['default_equities']) : '').'</label>
			<a href="#" onclick="stockdio_open_search_modal(this, \'equities\')" value="Search">Click here to set your custom equities</a>
			<input style="display:none" type="text" id="default_equities" name="stockdio_market_overview_options[default_equities]" value="%s" style="width:300px;" />		
			<p class="description" id="tagline-description">One or more valid stock symbols from the given exchange, separated by a semicolon (;). To include a symbol from a different exchange, this must be specified as a prefix and separated from the symbol with a colon (:), for example LSE:VOD. If not specified, a default list of equities for the given exchange will be used.</p>
			',
            isset( $this->options['default_equities'] ) ? esc_attr( $this->options['default_equities']) : ''
        );
    }
	public function stockdio_market_overview_indices_callback()
    {
    	if( empty( $this->options['default_indices'] ) )
            $this->options['default_indices'] = '' ;
        printf(
			'<label id="default_indices_label" style="max-width: 1000px;display: block;overflow: hidden;overflow-wrap: break-word;font-weight:bold;margin-bottom: 10px">'.(isset( $this->options['default_indices'] ) ? esc_attr( $this->options['default_indices']) : '').'</label>
			<a href="#" onclick="stockdio_open_search_modal(this, \'indices\')" value="Search">Click here to set your custom indices</a>
			<input style="display:none" type="text" id="default_indices" name="stockdio_market_overview_options[default_indices]" value="%s" style="width:300px;" />		
			<p class="description" id="tagline-description">One or more valid index symbols, separated by a semicolon (;). For a list of valid indices, visit <a href="http://www.stockdio.com/indices?wp=1" target="_blank">http://www.stockdio.com/indices.</a> If not specified, a default list of indices related to the given exchange will be used.</p>
			',
            isset( $this->options['default_indices'] ) ? esc_attr( $this->options['default_indices']) : ''
        );
    }
	public function stockdio_market_overview_commodities_callback()
    {
    	if( empty( $this->options['default_commodities'] ) )
            $this->options['default_commodities'] = '' ;
        printf(
			'<label id="default_commodities_label" style="max-width: 1000px;display: block;overflow: hidden;overflow-wrap: break-word;font-weight:bold;margin-bottom: 10px">'.(isset( $this->options['default_commodities'] ) ? esc_attr( $this->options['default_commodities']) : '').'</label>
			<a href="#" onclick="stockdio_open_search_modal(this, \'commodities\')" value="Search">Click here to set your custom commodities</a>
			<input style="display:none" type="text" id="default_commodities" name="stockdio_market_overview_options[default_commodities]" value="%s" style="width:300px;" />		
			<p class="description" id="tagline-description">One or more valid commodities, separated by a semicolon (;). For a list of valid commodities, visit <a href="http://www.stockdio.com/commodities?wp=1" target="_blank">http://www.stockdio.com/commodities.</a> If not specified, a default list of commodities will be used.</p>
			',
            isset( $this->options['default_commodities'] ) ? esc_attr( $this->options['default_commodities']) : ''
        );
    }
	public function stockdio_market_overview_currencies_callback()
    {
    	if( empty( $this->options['default_currencies'] ) )
            $this->options['default_currencies'] = '' ;
        printf(
			'<label id="default_currencies_label" style="max-width: 1000px;display: block;overflow: hidden;overflow-wrap: break-word;font-weight:bold;margin-bottom: 10px">'.(isset( $this->options['default_currencies'] ) ? esc_attr( $this->options['default_currencies']) : '').'</label>
			<a href="#" onclick="stockdio_open_search_modal(this, \'currencies\')" value="Search">Click here to set your custom currencies</a>
			<input style="display:none"  type="text" id="default_currencies" name="stockdio_market_overview_options[default_currencies]" value="%s" style="width:300px;" />		
			<p class="description" id="tagline-description">One or more valid currency pairs, in the format currency-base/currency-target (e.g. EUR/USD), separated by a semicolon (;). For a list of valid currencies, visit <a href="http://www.stockdio.com/currencies.?wp=1" target="_blank">http://www.stockdio.com/currencies.</a> If not specified, a default list of currency pairs for the given exchange will be used.</p>
			',
            isset( $this->options['default_currencies'] ) ? esc_attr( $this->options['default_currencies']) : ''
        );
    }

	public function stockdio_market_overview_culture_callback()
        {
		if( empty( $this->options['default_culture'] ) )
            $this->options['default_culture'] = '' ;
        printf(
            '<select name="stockdio_market_overview_options[default_culture]" id="default_culture">		
			    <option value="" selected="selected">None</option> 
				<option value="English-US">English-US</option> 
				<option value="English-UK">English-UK</option> 
				<option value="English-Canada">English-Canada</option> 
				<option value="English-Australia">English-Australia</option> 
				<option value="Spanish-Spain">Spanish-Spain</option> 
				<option value="Spanish-Mexico">Spanish-Mexico</option> 
				<option value="Spanish-LatinAmerica">Spanish-LatinAmerica</option> 
				<option value="French-France">French-France</option> 
				<option value="French-Canada">French-Canada</option> 
				<option value="French-Belgium">French-Belgium</option> 
				<option value="French-Switzerland">French-Switzerland</option> 
				<option value="Italian-Italy">Italian-Italy</option> 
				<option value="Italian-Switzerland">Italian-Switzerland</option> 
				<option value="German-Germany">German-Germany</option> 
				<option value="German-Switzerland">German-Switzerland</option> 
				<option value="Portuguese-Brasil">Portuguese-Brasil</option> 
				<option value="Portuguese-Portugal">Portuguese-Portugal</option> 
				<option value="Dutch-Netherlands">Dutch-Netherlands</option> 
				<option value="Dutch-Belgium">Dutch-Belgium</option> 
				<option value="SimplifiedChinese-China">SimplifiedChinese-China</option> 
				<option value="SimplifiedChinese-HongKong">SimplifiedChinese-HongKong</option> 	
				<option value="TraditionalChinese-HongKong">TraditionalChinese-HongKong</option>				
				<option value="Japanese">Japanese</option> 
				<option value="Korean">Korean</option> 
				<option value="Russian">Russian</option> 	
				<option value="Polish">Polish</option>				
				<option value="Turkish">Turkish</option>		
				<option value="Arabic">Arabic</option>		
				<option value="Hebrew">Hebrew</option>	
				<option value="Swedish">Swedish</option>	
				<option value="Danish">Danish</option>	
				<option value="Finnish">Finnish</option>	
				<option value="Norwegian">Norwegian</option>	
				<option value="Icelandic">Icelandic</option>	
				<option value="Greek">Greek</option>	
				<option value="Czech">Czech</option>	
				<option value="Thai">Thai</option>	
				<option value="Vietnamese">Vietnamese</option>	
				<option value="Hindi">Hindi</option>	
				<option value="Indonesian">Indonesian</option>				
             </select>
			 <p class="description" id="tagline-description">Allows to specify a combination of language and country settings, used to display texts and to format numbers and dates (e.g. Spanish-Spain). For a list of available culture combinations please visit <a href="http://www.stockdio.com/cultures?wp=1" target="_blank">http://www.stockdio.com/cultures.</p>
			 <script>document.getElementById("default_culture").value = "'.$this->options['default_culture'].'";</script>
			 ',
    		'default_culture'
    		);
    }
	
	public function stockdio_market_overview_loadDataWhenVisible_callback()
    {	
        printf(
            '<input type="checkbox" id="default_loadDataWhenVisible" name="stockdio_market_overview_options[default_loadDataWhenVisible]" value="%s" '. checked( isset($this->options['default_loadDataWhenVisible'])? $this->options['default_loadDataWhenVisible']: 0 ,1, false ) .' />			
			<p class="description" id="tagline-description">Allows to fetch the data and display the visualization only when it becomes visible on the page, in order to avoid using calls (requests) when they are not needed. This is particularly useful when the visualization is not visible on the page by default, but it becomes visible as result of a user interaction (e.g. clicking on an element, etc.). It is also useful when using the same visualization multiple times on a page for different devices (e.g. using one instance of the plugin for mobile and another one for desktop). We recommend not using this by default but only on scenarios as those described above, as it may provide the end user with a small delay to display the visualization.</p>
			',
            isset( $this->options['default_loadDataWhenVisible'] ) && $this->options['default_loadDataWhenVisible'] != 0 ? $this->options['default_loadDataWhenVisible'] : 1
        );	
    }
	
	public function stockdio_market_overview_width_callback()
    {
    	if( empty( $this->options['default_width'] ) )
            $this->options['default_width'] = '100%' ;
        printf(
            '<input type="text" id="default_width" name="stockdio_market_overview_options[default_width]" value="%s" />
			<p class="description" id="tagline-description">Width of the list in either px or %% ( default: 100%%).</p>
			',
            isset( $this->options['default_width'] ) ? esc_attr( $this->options['default_width']) : ''
        );
    }
	
	public function stockdio_market_overview_allowSort_callback()
    {
		if( !isset( $this->options['booleanIniCheck'] ) ){
			 $this->options['default_allowSort']=1;
		}	
        printf(
            '<input type="checkbox" id="default_allowSort" name="stockdio_market_overview_options[default_allowSort]" value="%s" '. checked( isset($this->options['default_allowSort'])?$this->options['default_allowSort']:0,1, false ) .' />			
			<p class="description" id="tagline-description">Allows to Sort.</p>
			',
            isset( $this->options['default_allowSort'] ) ? $this->options['default_allowSort'] : 1
        );		
    }
	
	
    public function stockdio_market_overview_height_callback()
    {
    	if( empty( $this->options['default_height'] ) )
            $this->options['default_height'] = '' ;
        printf(
            '<input type="text" id="default_height" name="stockdio_market_overview_options[default_height]" value="%s" />
			<p class="description" id="tagline-description">Height of the list in pixels. If not defined, height is calculated automatically.</p>
			',
            isset( $this->options['default_height'] ) ? esc_attr( $this->options['default_height']) : ''
        );
    }
	
	public function stockdio_market_overview_booleanIniCheck_callback()
    {
		 printf('<input style="display:none" type="text" id="booleanIniCheck" name="stockdio_market_overview_options[booleanIniCheck]" value="1" />');
		printf('<div class="stockdio_hidden_setting" style="display:none"></div><script>jQuery(function () {jQuery(".stockdio_hidden_setting").parent().parent().hide()});</script> ');
		$this->options['booleanIniCheck'] = "1";
    }

	
	public function stockdio_market_overview_includeChart_callback()
    {
        printf(
            '<input type="checkbox" id="default_includeChart" name="stockdio_market_overview_options[default_includeChart]" value="%s" '. checked( isset($this->options['default_includeChart'])?$this->options['default_includeChart']:0,1, false ) .' />			
			<p class="description" id="tagline-description">Allows to include an interactive chart along with the list.</p>
			',
			isset( $this->options['default_includeChart'] ) && $this->options['default_includeChart'] != 0 ? $this->options['default_includeChart'] : 1
        );		
    }	
	
	public function stockdio_market_overview_chartHeight_callback()
    {
    	if( empty( $this->options['default_chartHeight'] ) )
            $this->options['default_chartHeight'] = '200px' ;
        printf(
            '<input type="text" id="default_chartHeight" name="stockdio_market_overview_options[default_chartHeight]" value="%s" />
			<p class="description" id="tagline-description">Height of the chart in pixels (default: 200px)</p>
			',
            isset( $this->options['default_chartHeight'] ) ? esc_attr( $this->options['default_chartHeight']) : ''
        );
    }
	
	
	public function stockdio_market_overview_includeLogo_callback()
    {
		if( !isset( $this->options['booleanIniCheck'] ) ){
			 $this->options['default_includeLogo']=1;
		}	
        printf(
            '<input type="checkbox" id="default_includeLogo" name="stockdio_market_overview_options[default_includeLogo]" value="%s" '. checked( isset($this->options['default_includeLogo'])?$this->options['default_includeLogo']:0,1, false ) .' />			
			<p class="description" id="tagline-description">Allows to include/exclude a column with the stock logo, if available. Use includeLogo=false to hide the logo (optional).</p>
			',
            isset( $this->options['default_includeLogo'] ) ? $this->options['default_includeLogo'] : 1
        );		
    }	

    public function stockdio_market_overview_logoMaxHeight_callback()
    {
    	if( empty( $this->options['default_logoMaxHeight'] ) )
            $this->options['default_logoMaxHeight'] = '20px' ;
        printf(
            '<input type="text" id="default_logoMaxHeight" name="stockdio_market_overview_options[default_logoMaxHeight]" value="%s" />
			<p class="description" id="tagline-description">Specify the maximum height allowed for the logo. The height may be smaller than the maximum, depending on the logo width, as it maintains the logo\'s aspect ratio.</p>
			',
            isset( $this->options['default_logoMaxHeight'] ) ? esc_attr( $this->options['default_logoMaxHeight']) : ''
        );
    }
	
	    public function stockdio_market_overview_logoMaxWidth_callback()
    {
    	if( empty( $this->options['default_logoMaxWidth'] ) )
            $this->options['default_logoMaxWidth'] = '90px' ;
        printf(
            '<input type="text" id="default_logoMaxWidth" name="stockdio_market_overview_options[default_logoMaxWidth]" value="%s" />
			<p class="description" id="tagline-description">Specify the maximum width allowed for the logo. The width may be smaller than the maximum, depending on the logo height, as it maintains the logo\'s aspect ratio.</p>
			',
            isset( $this->options['default_logoMaxWidth'] ) ? esc_attr( $this->options['default_logoMaxWidth']) : ''
        );
    }

	public function stockdio_market_overview_includeEquitiesSymbol_callback()
    {
		if( !isset( $this->options['booleanIniCheck'] ) ){
			 $this->options['default_includeEquitiesSymbol']=1;
		}	
        printf(
            '<input type="checkbox" id="default_includeEquitiesSymbol" name="stockdio_market_overview_options[default_includeEquitiesSymbol]" value="%s" '. checked( isset($this->options['default_includeEquitiesSymbol'])?$this->options['default_includeEquitiesSymbol']:0,1, false ) .' />			
			<p class="description" id="tagline-description">Allows to include/exclude a column with the symbol in the Equities category. Use includeEquitiesSymbol=false to hide the symbol (optional).</p>
			',
            isset( $this->options['default_includeEquitiesSymbol'] ) ? $this->options['default_includeEquitiesSymbol'] : 1
        );
    }	
	public function stockdio_market_overview_includeEquitiesName_callback()
    {
        printf(
            '<input type="checkbox" id="default_includeEquitiesName" name="stockdio_market_overview_options[default_includeEquitiesName]" value="%s" '. checked( isset($this->options['default_includeEquitiesName'])?$this->options['default_includeEquitiesName']:0,1, false ) .' />			
			<p class="description" id="tagline-description">Allows to include/exclude a column with the name in the Equities category. Use includeEquitiesName=false to hide the name (optional).</p>
			',
            isset( $this->options['default_includeEquitiesName'] ) ? $this->options['default_includeEquitiesName'] : 1
        );
    }	
	
	public function stockdio_market_overview_includeIndicesSymbol_callback()
    {
        printf(
            '<input type="checkbox" id="default_includeIndicesSymbol" name="stockdio_market_overview_options[default_includeIndicesSymbol]" value="%s" '. checked( isset($this->options['default_includeIndicesSymbol'])?$this->options['default_includeIndicesSymbol']:0,1, false ) .' />			
			<p class="description" id="tagline-description">Allows to include/exclude a column with the symbol in the Indices category. Use includeIndicesSymbol=false to hide the symbol (optional).</p>
			',
            isset( $this->options['default_includeIndicesSymbol'] ) ? $this->options['default_includeIndicesSymbol'] : 1
        );
    }	
	public function stockdio_market_overview_includeIndicesName_callback()
    {
		if( !isset( $this->options['booleanIniCheck'] ) ){
			 $this->options['default_includeIndicesName']=1;
		}	
        printf(
            '<input type="checkbox" id="default_includeIndicesName" name="stockdio_market_overview_options[default_includeIndicesName]" value="%s" '. checked( isset($this->options['default_includeIndicesName'])?$this->options['default_includeIndicesName']:0,1, false ) .' />			
			<p class="description" id="tagline-description">Allows to include/exclude a column with the name in the Indices category. Use includeIndicesName=false to hide the name (optional).</p>
			',
            isset( $this->options['default_includeIndicesName'] ) ? $this->options['default_includeIndicesName'] : 1
        );
    }	

	public function stockdio_market_overview_includeCommoditiesSymbol_callback()
    {
        printf(
            '<input type="checkbox" id="default_includeCommoditiesSymbol" name="stockdio_market_overview_options[default_includeCommoditiesSymbol]" value="%s" '. checked( isset($this->options['default_includeCommoditiesSymbol'])?$this->options['default_includeCommoditiesSymbol']:0,1, false ) .' />			
			<p class="description" id="tagline-description">Allows to include/exclude a column with the symbol in the Commodities category. Use includeCommoditiesSymbol=false to hide the symbol (optional).</p>
			',
            isset( $this->options['default_includeCommoditiesSymbol'] ) ? $this->options['default_includeCommoditiesSymbol'] : 1
        );
    }	
	public function stockdio_market_overview_includeCommoditiesName_callback()
    {
		if( !isset( $this->options['booleanIniCheck'] ) ){
			 $this->options['default_includeCommoditiesName']=1;
		}	
        printf(
            '<input type="checkbox" id="default_includeCommoditiesName" name="stockdio_market_overview_options[default_includeCommoditiesName]" value="%s" '. checked( isset($this->options['default_includeCommoditiesName'])?$this->options['default_includeCommoditiesName']:0,1, false ) .' />			
			<p class="description" id="tagline-description">Allows to include/exclude a column with the name in the Commodities category. Use includeCommoditiesName=false to hide the name (optional).</p>
			',
            isset( $this->options['default_includeCommoditiesName'] ) ? $this->options['default_includeCommoditiesName'] : 1
        );
    }	

	public function stockdio_market_overview_includeCurrenciesSymbol_callback()
    {
		if( !isset( $this->options['booleanIniCheck'] ) ){
			 $this->options['default_includeCurrenciesSymbol']=1;
		}	
        printf(
            '<input type="checkbox" id="default_includeCurrenciesSymbol" name="stockdio_market_overview_options[default_includeCurrenciesSymbol]" value="%s" '. checked( isset($this->options['default_includeCurrenciesSymbol'])?$this->options['default_includeCurrenciesSymbol']:0,1, false ) .' />			
			<p class="description" id="tagline-description">Allows to include/exclude a column with the symbol in the Currencies category. Use includeCurrenciesSymbol=false to hide the symbol (optional).</p>
			',
            isset( $this->options['default_includeCurrenciesSymbol'] ) ? $this->options['default_includeCurrenciesSymbol'] : 1
        );
    }	
	public function stockdio_market_overview_includeCurrenciesName_callback()
    {
        printf(
            '<input type="checkbox" id="default_includeCurrenciesName" name="stockdio_market_overview_options[default_includeCurrenciesName]" value="%s" '. checked( isset($this->options['default_includeCurrenciesName'])?$this->options['default_includeCurrenciesName']:0,1, false ) .' />			
			<p class="description" id="tagline-description">Allows to include/exclude a column with the name in the Currencies category. Use includeCurrenciesName=false to hide the name (optional).</p>
			',
            isset( $this->options['default_includeCurrenciesName'] ) ? $this->options['default_includeCurrenciesName'] : 1
        );
    }		

	
	public function stockdio_market_overview_includePrice_callback()
    {
		if( !isset( $this->options['booleanIniCheck'] ) ){
			 $this->options['default_includePrice']=1;
		}			
        printf(
            '<input type="checkbox" id="default_includePrice" name="stockdio_market_overview_options[default_includePrice]" value="%s" '. checked( isset($this->options['default_includePrice'])?$this->options['default_includePrice']:0,1, false ) .' />			
			<p class="description" id="tagline-description">Allows to include/exclude a column with the latest stock price. Use includePrice=false to hide the stock price (optional).</p>
			',
            isset( $this->options['default_includePrice'] ) ? $this->options['default_includePrice'] : 1
        );
    }	
	public function stockdio_market_overview_includeChange_callback()
    {
		if( !isset( $this->options['booleanIniCheck'] ) ){
			 $this->options['default_includeChange']=1;
		}
        printf(
            '<input type="checkbox" id="default_includeChange" name="stockdio_market_overview_options[default_includeChange]" value="%s" '. checked( isset($this->options['default_includeChange'])?$this->options['default_includeChange']:0,1, false ) .' />			
			<p class="description" id="tagline-description">Allows to include/exclude a column with the stock price change. Use includeChange=false to hide the price change (optional).</p>
			',
            isset( $this->options['default_includeChange'] ) ? $this->options['default_includeChange'] : 1
        );
    }	
	public function stockdio_market_overview_includePercentChange_callback()
    {
		if( !isset( $this->options['booleanIniCheck'] ) ){
			 $this->options['default_includePercentChange']=1;
		}
        printf(
            '<input type="checkbox" id="default_includePercentChange" name="stockdio_market_overview_options[default_includePercentChange]" value="%s" '. checked( isset($this->options['default_includePercentChange'])?$this->options['default_includePercentChange']:0,1, false ) .' />			
			<p class="description" id="tagline-description"> Allows to include/exclude a column with the stock price percentual change. Use includePercentChange=false to hide the price percent change (optional).</p>
			',
            isset( $this->options['default_includePercentChange'] ) ? $this->options['default_includePercentChange'] : 1
        );
    }	
	public function stockdio_market_overview_includeTrend_callback()
    {
		if( !isset( $this->options['booleanIniCheck'] ) ){
			 $this->options['default_includeTrend']=1;
		}
        printf(
            '<input type="checkbox" id="default_includeTrend" name="stockdio_market_overview_options[default_includeTrend]" value="%s" '. checked( isset($this->options['default_includeTrend'])?$this->options['default_includeTrend']:0,1, false ) .' />			
			<p class="description" id="tagline-description">Allows to include/exclude a column with the stock price trend icon (up/down/neutral). Use includeTrend=false to hide the trend icon (optional).</p>
			',
            isset( $this->options['default_includeTrend'] ) ? $this->options['default_includeTrend'] : 1
        );
    }	
	public function stockdio_market_overview_includeVolume_callback()
    {
		if( !isset( $this->options['booleanIniCheck'] ) ){
			 $this->options['default_includeVolume']=0;
		}
        printf(
            '<input type="checkbox" id="default_includeVolume" name="stockdio_market_overview_options[default_includeVolume]" value="%s" '. checked( isset($this->options['default_includeVolume'])?$this->options['default_includeVolume']:0,1, false ) .' />			
			<p class="description" id="tagline-description">Allows to include/exclude a column with the latest volume. By default, volume is not visible. Use includeVolume=true to show it (optional).</p>
			',
            isset( $this->options['default_includeVolume'] ) ? $this->options['default_includeVolume'] : 1
        );
    }	
	public function stockdio_market_overview_showHeader_callback()
    {
        printf(
            '<input type="checkbox" id="default_showHeader" name="stockdio_market_overview_options[default_showHeader]" value="%s" '. checked( isset($this->options['default_showHeader'])?$this->options['default_showHeader']:0,1, false ) .' />			
			<p class="description" id="tagline-description">Allows to display the list header. Use showHeader=false to hide it (optional).</p>
			',
            isset( $this->options['default_showHeader'] ) ? $this->options['default_showHeader'] : 1
        );
    }	
	
	public function stockdio_market_overview_font_callback()
    {
    	if( empty( $this->options['default_font'] ) )
            $this->options['default_font'] = '' ;
        printf(
            '<input type="text" id="default_font" name="stockdio_market_overview_options[default_font]" value="%s" />
			<p class="description" id="tagline-description">Allows to specify the font that will be used to render the chart. Multiple fonts may be specified separated by comma, e.g. Lato,Helvetica,Arial.</p>
			',
            isset( $this->options['default_font'] ) ? esc_attr( $this->options['default_font']) : ''
        );
    }
	
		
	public function stockdio_market_overview_palette_callback()
        {
		if( empty( $this->options['default_palette'] ) )
            $this->options['default_palette'] = '' ;
        printf(
            '<select name="stockdio_market_overview_options[default_palette]" id="default_palette">
			    <option value="" selected="selected">None</option>
				<option value="Aurora">Aurora</option>
				<option value="Block">Block</option>
				<option value="Brown-Sugar">Brown-Sugar</option>
				<option value="Eggplant">Eggplant</option>
				<option value="Excite-Bike">Excite-Bike</option>
				<option value="Financial-Light" >Financial-Light</option>
				<option value="Healthy">Healthy</option>
				<option value="High-Contrast">High-Contrast</option>
				<option value="Humanity">Humanity</option>
				<option value="Lilacs-in-Mist">Lilacs-in-Mist</option>
				<option value="Mesa">Mesa</option>
				<option value="Modern-Business">Modern-Business</option>
				<option value="Mint-Choc">Mint-Choc</option>
				<option value="Pastels">Pastels</option>
				<option value="Relief">Relief</option>
				<option value="Whitespace">Whitespace</option>			 
             </select>
			 <p class="description" id="tagline-description">Includes a set of consistent colors used for the visualization. Most palette colors can be overridden with specific colors for several features such as border, background, labels, etc. For more info, please visit <a href="http://www.stockdio.com/palettes?wp=1" target="_blank">http://www.stockdio.com/palettes</a> </p>
			 <script>document.getElementById("default_palette").value = "'.$this->options['default_palette'].'";</script>
			 ',
    		'default_palette'
    		);
    }

	public function stockdio_market_overview_motif_callback()
        {
		if( empty( $this->options['default_motif'] ) )
            $this->options['default_motif'] = '' ;			
        printf(
            '<select name="stockdio_market_overview_options[default_motif]" id="default_motif">			
				<option value="" selected="selected">None</option>
				<option value="Aurora">Aurora</option>
				<option value="Blinds">Blinds</option>
				<option value="Block">Block</option>
				<option value="Face">Face</option>
				<option value="Financial" >Financial</option>
				<option value="Glow">Glow</option>
				<option value="Healthy">Healthy</option>
				<option value="Hook">Hook</option>
				<option value="Lizard">Lizard</option>
				<option value="Material">Material</option>
				<option value="Relief">Relief</option>
				<option value="Semantic">Semantic</option>
				<option value="Topbar">Topbar</option>
				<option value="Tree">Tree</option>
				<option value="Whitespace">Whitespace</option>
				<option value="Wireframe">Wireframe</option>
             </select>
			 <p class="description" id="tagline-description">Design used to display the visualization with a specific aesthetics, including borders and styles, among other elements. For more info, please visit <a href="http://www.stockdio.com/motifs?wp=1" target="_blank">http://www.stockdio.com/motifs</a></p>
			 <script>document.getElementById("default_motif").value = "'.$this->options['default_motif'].'";</script>			 
			 ',
    		'default_motif'
    		);
    }
		

	public function stockdio_market_overview_displayPrices_callback()
        {
		if( empty( $this->options['default_displayPrices'] ) )
            $this->options['default_displayPrices'] = 'Lines' ;
        printf(
            '<select name="stockdio_market_overview_options[default_displayPrices]" id="default_displayPrices">		
			    <option value="" selected="selected">None</option> 
				<option value="OHLC">OHLC</option> 		
				<option value="HLC">HLC</option> 		
				<option value="Candlestick">Candlestick</option> 		
				<option value="Lines">Lines</option> 		
				<option value="Area">Area</option> 		
             </select>
			 <p class="description" id="tagline-description">Allows to specify how to display the prices on the chart.</p>
			 <script>document.getElementById("default_displayPrices").value = "'.$this->options['default_displayPrices'].'";</script>
			 ',
    		'default_displayPrices'
    		);
    }	
	
	public function stockdio_market_overview_days_callback()
    {
    	if( empty( $this->options['default_days'] ) )
            $this->options['default_days'] = '' ;
        printf(
            '<input type="text" id="default_days" name="stockdio_market_overview_options[default_days]" value="%s" />
			<p class="description" id="tagline-description">Specify the number of days for the period to display in the chart (if enabled). If not specified, its default value is 365 days.</p>
			',
            isset( $this->options['default_days'] ) ? esc_attr( $this->options['default_days']) : ''
        );
    }
	
	public function stockdio_market_overview_allowPeriodChange_callback()
    {
		if( !isset( $this->options['booleanIniCheck'] ) ){
			 $this->options['default_allowPeriodChange']=1;
		}	
        printf(
            '<input type="checkbox" id="default_allowPeriodChange" name="stockdio_market_overview_options[default_allowPeriodChange]" value="%s" '. checked( $this->options['default_allowPeriodChange'],1, false ) .' />			
			<p class="description" id="tagline-description">Provides a UI to allow the end user to select the period for the data to be displayed.</p>
			',
            isset( $this->options['default_allowPeriodChange'] ) ? $this->options['default_allowPeriodChange'] : 1
        );
    }
	
	public function stockdio_market_overview_intraday_callback()
    {	
		if( !isset( $this->options['intradayCheck'] ) ){
			 $this->options['default_intraday']=1;
		}	
        printf(
            '<input type="checkbox" id="default_intraday" name="stockdio_market_overview_options[default_intraday]" value="%s" '. checked( $this->options['default_intraday'],1, false ) .' />			
			<p class="description" id="tagline-description">If enabled (true), auto refresh intraday delayed data will be used if available for the exchange. For a list of exchanges with intraday data available, <a href="http://www.stockdio.com/exchanges.?wp=1" target="_blank">http://www.stockdio.com/exchanges.</a>',
            isset( $this->options['default_intraday'] ) ? $this->options['default_intraday'] : 1
        );
    }
	
	public function stockdio_market_overview_intradayCheck_callback()
    {
		$this->options['intradayCheck'] = "1";
		 printf('<input style="display:none" type="text" id="intradayCheck" name="stockdio_market_overview_options[intradayCheck]" value="1" />');
		printf('<div class="stockdio_hidden_setting" style="display:none"></div><script>jQuery(function () {jQuery(".stockdio_hidden_setting").parent().parent().hide()});</script> ');
    }	
		
}


if( is_admin() )
    $stockdio_market_overview_settings_page = new StockdioMarketOverviewSettingsPage();

add_action('wp_print_scripts', 'enqueueMarketOverviewAssets');

//Add the shortcode
add_shortcode( 'stock-market-overview', 'stockdio_market_overview_func' );

//widget
require_once( dirname(__FILE__) . "/stockdio_market_overview_widget.php"); 

/**
 * Block Initializer.
 */
if (function_exists( 'register_block_type')) {
	require_once(plugin_dir_path( __FILE__ ) . 'src/init.php');
}

remove_action( 'wp_head', 'stockdio_referrer_header_metadata', 0 );
add_action( 'wp_head', 'stockdio_referrer_header_metadata', 0 );
if ( ! function_exists( 'stockdio_referrer_header_metadata' ) ) {
	function stockdio_referrer_header_metadata() {	
	try {
		$useragent = isset($_SERVER['HTTP_USER_AGENT'])? $_SERVER['HTTP_USER_AGENT']: '';
		if (false || (!empty($useragent) && ( (strpos($useragent, "Safari") !== false && strpos($useragent, "Chrome") === false) ||strpos($useragent, "Opera Mini") !== false ))) {
	  ?>
		<meta name="referrer" content="no-referrer-when-downgrade">
	  <?php
	  
	}
		
	} catch (Exception $e) {
	}	
}
}

function enqueueMarketOverviewAssets()
{	
	//$plugin_data = get_plugin_data( __FILE__ );
	//$plugin_version = $plugin_data['Version'];
	//stockdio_overview_version
	//$version = date_timestamp_get(date_create());
	$version = stockdio_overview_version;
	$js_address=plugin_dir_url( __FILE__ )."assets/stockdio-wp.js";
	wp_register_script("customStockdioJs",$js_address, array(),$version, false );
	wp_enqueue_script('customStockdioJs');
}

//Execute the shortcode with $atts arguments
function stockdio_market_overview_func( $atts ) {
	//make array of arguments and give these arguments to the shortcode
    $a = shortcode_atts( array(
		'title' => '',
		'stockexchange' => '',
		'exchange' => '',
		'includeequities' => '',
		'includeindices' => '',
		'includecommodities' => '',
		'includecurrencies' => '',
		'equities' => '',
		'indices' => '',
		'commodities' => '',
		'currencies' => '',

		'culture' => '',
		'loaddatawhenvisible' => '',	
		'width'	=> '',
		'height'	=> '',			
		'font'	=> '',	
		'motif'	=> '',
		'palette'	=> '',
		'logomaxheight'	=> '',
		'logomaxwidth'	=> '',
		'includelogo' => '',

		'includeequitiessymbol' => '',
		'includeequitiesname' => '',
		
		'includeindicesymbol' => '',
		'includeincidesname' => '',
		
		'includecommoditiessymbol' => '',
		'includecommoditiesname' => '',
		
		'includecurrenciessymbol' => '',
		'includecurrenciesname' => '',
		
		'includeprice' => '',
		'includechange' => '',
		'includepercentchange' => '',
		'includetrend' => '',
		'includevolume' => '',
		'showheader' => '',
		'includechart' => '',
		'chartheight' => '',
		'displayprices' => '',
		'days' => '',
		'allowperiodchange' => '',
		'intraday' => '',
		'allowsort' =>'',

		'bordercolor'	=> '',
		'backgroundcolor'	=> '',
		'captioncolor'	=> '',
		'titlecolor'	=> '',
		'pricecolor'	=> '',
		'volumecolor'	=> '',		
		'labelscolor'	=> '',		
		'interlacedcolor'	=> '',						
		'axeslinescolor'	=> '',
		'positivecolor'	=> '',
		'positivetextcolor'	=> '',
		'negativecolor'	=> '',
		'negativetextcolor'	=> '',
		'periodscolor'	=> '',
		'periodsbackgroundcolor'	=> '',
		'headercolor'	=> '',
		'headerbackgroundcolor'	=> '',		
		'tooltipscolor'	=> '',
		'tooltipstextcolor'	=> '',
    ), $atts );


    //create variables from arguments array
	extract($a);

	$width = esc_attr(sanitize_text_field($width));
	$height = esc_attr(sanitize_text_field($height));
	
	if (!empty($exchange) && empty($stockexchange)){
		$stockexchange = $exchange;
	}

	//assign settings values to $stockdio_market_overview_options
  	$stockdio_market_overview_options = get_option( 'stockdio_market_overview_options' );
	
	 $api_key = '';
	if (isset($stockdio_market_overview_options['api_key']))
		$api_key = $stockdio_market_overview_options['api_key'];

	$extraSettings = '';
	
	stockdio_market_overview_get_param_value('culture', $culture, 'string' , $extraSettings, $stockdio_market_overview_options, '');
	stockdio_market_overview_get_param_value('font', $font, 'string' , $extraSettings, $stockdio_market_overview_options, '');
	stockdio_market_overview_get_param_value('palette', $palette, 'string' , $extraSettings, $stockdio_market_overview_options, '');
	stockdio_market_overview_get_param_value('motif', $motif, 'string' , $extraSettings, $stockdio_market_overview_options, '');
	stockdio_market_overview_get_param_value('title', $title, 'string' , $extraSettings, $stockdio_market_overview_options, '');	
	stockdio_market_overview_get_param_value('stockExchange', $stockexchange, 'string' , $extraSettings, $stockdio_market_overview_options, '');
		
	stockdio_market_overview_get_param_value('equities', $equities, 'string' , $extraSettings, $stockdio_market_overview_options, '');
	stockdio_market_overview_get_param_value('indices', $indices, 'string' , $extraSettings, $stockdio_market_overview_options, '');
	stockdio_market_overview_get_param_value('commodities', $commodities, 'string' , $extraSettings, $stockdio_market_overview_options, '');
	stockdio_market_overview_get_param_value('currencies', $currencies, 'string' , $extraSettings, $stockdio_market_overview_options, '');
	
	stockdio_market_overview_get_param_value('logoMaxHeight', $logomaxheight, 'string' , $extraSettings, $stockdio_market_overview_options, '');
	stockdio_market_overview_get_param_value('logoMaxWidth', $logomaxwidth, 'string' , $extraSettings, $stockdio_market_overview_options, '');
	
	stockdio_market_overview_get_param_value('includeEquities', $includeequities, 'bool' , $extraSettings, $stockdio_market_overview_options, '1');
	stockdio_market_overview_get_param_value('includeIndices', $includeindices, 'bool' , $extraSettings, $stockdio_market_overview_options, '1');
	stockdio_market_overview_get_param_value('includeCommodities', $includecommodities, 'bool' , $extraSettings, $stockdio_market_overview_options, '1');
	stockdio_market_overview_get_param_value('includeCurrencies', $includecurrencies, 'bool' , $extraSettings, $stockdio_market_overview_options, '1');
	
	stockdio_market_overview_get_param_value('includeLogo', $includelogo, 'bool' , $extraSettings, $stockdio_market_overview_options, '1');
	

	stockdio_market_overview_get_param_value('includeEquitiesSymbol', $includeequitiessymbol, 'bool' , $extraSettings, $stockdio_market_overview_options, '1');
	stockdio_market_overview_get_param_value('includeEquitiesName', $includeequitiesname, 'bool' , $extraSettings, $stockdio_market_overview_options, '0');
	stockdio_market_overview_get_param_value('includeIndiceSymbol', $includeindicesymbol, 'bool' , $extraSettings, $stockdio_market_overview_options, '0');
	stockdio_market_overview_get_param_value('includeIncidesName', $includeincidesname, 'bool' , $extraSettings, $stockdio_market_overview_options, '1');
	stockdio_market_overview_get_param_value('includeCommoditiesSymbol', $includecommoditiessymbol, 'bool' , $extraSettings, $stockdio_market_overview_options, '0');
	stockdio_market_overview_get_param_value('includeCommoditiesName', $includecommoditiesname, 'bool' , $extraSettings, $stockdio_market_overview_options, '1');
	stockdio_market_overview_get_param_value('includeCurrenciesSymbol', $includecurrenciessymbol, 'bool' , $extraSettings, $stockdio_market_overview_options, '1');
	stockdio_market_overview_get_param_value('includeCurrenciesName', $includecurrenciesname, 'bool' , $extraSettings, $stockdio_market_overview_options, '0');
		
	stockdio_market_overview_get_param_value('allowSort', $allowsort, 'bool' , $extraSettings, $stockdio_market_overview_options, '1');
	stockdio_market_overview_get_param_value('includePrice', $includeprice, 'bool' , $extraSettings, $stockdio_market_overview_options, '1');
	stockdio_market_overview_get_param_value('includeChange', $includechange, 'bool' , $extraSettings, $stockdio_market_overview_options, '1');
	stockdio_market_overview_get_param_value('includePercentChange', $includepercentchange, 'bool' , $extraSettings, $stockdio_market_overview_options, '1');
	stockdio_market_overview_get_param_value('includeTrend', $includetrend, 'bool' , $extraSettings, $stockdio_market_overview_options, '1');
	stockdio_market_overview_get_param_value('includeVolume', $includevolume, 'bool' , $extraSettings, $stockdio_market_overview_options, '0');
	stockdio_market_overview_get_param_value('showHeader', $showheader, 'bool' , $extraSettings, $stockdio_market_overview_options, '0');

		//colors:
		stockdio_market_overview_get_param_value('borderColor', $bordercolor, 'color' , $extraSettings, $stockdio_market_overview_options, '');
		stockdio_market_overview_get_param_value('backgroundColor', $backgroundcolor, 'color' , $extraSettings, $stockdio_market_overview_options, '');
		stockdio_market_overview_get_param_value('captionColor', $captioncolor, 'color' , $extraSettings, $stockdio_market_overview_options, '');
		stockdio_market_overview_get_param_value('titleColor', $titlecolor, 'color' , $extraSettings, $stockdio_market_overview_options, '');
		stockdio_market_overview_get_param_value('priceColor', $pricecolor, 'color' , $extraSettings, $stockdio_market_overview_options, '');
		stockdio_market_overview_get_param_value('axesLinesColor', $axeslinescolor, 'color' , $extraSettings, $stockdio_market_overview_options, '');
		stockdio_market_overview_get_param_value('positiveColor', $positivecolor, 'color' , $extraSettings, $stockdio_market_overview_options, '');
		stockdio_market_overview_get_param_value('positiveTextColor', $positivetextcolor, 'color' , $extraSettings, $stockdio_market_overview_options, '');
		stockdio_market_overview_get_param_value('negativeColor', $negativecolor, 'color' , $extraSettings, $stockdio_market_overview_options, '');
		stockdio_market_overview_get_param_value('negativeTextColor', $negativetextcolor, 'color' , $extraSettings, $stockdio_market_overview_options, '');
		stockdio_market_overview_get_param_value('periodsColor', $periodscolor, 'color' , $extraSettings, $stockdio_market_overview_options, '');
		stockdio_market_overview_get_param_value('periodsBackgroundColor', $periodsbackgroundcolor, 'color' , $extraSettings, $stockdio_market_overview_options, '');
		stockdio_market_overview_get_param_value('tooltipsColor', $tooltipscolor, 'color' , $extraSettings, $stockdio_market_overview_options, '');
		stockdio_market_overview_get_param_value('tooltipsTextColor', $tooltipstextcolor, 'color' , $extraSettings, $stockdio_market_overview_options, '');

		//stockdio_market_overview_get_param_value('volumeColor', $volumecolor, 'color' , $extraSettings, $stockdio_market_overview_options, '');
		stockdio_market_overview_get_param_value('labelsColor', $labelscolor, 'color' , $extraSettings, $stockdio_market_overview_options, '');
		stockdio_market_overview_get_param_value('interlacedColor', $interlacedcolor, 'color' , $extraSettings, $stockdio_market_overview_options, '');
		stockdio_market_overview_get_param_value('headerColor', $headercolor, 'color' , $extraSettings, $stockdio_market_overview_options, '');
		stockdio_market_overview_get_param_value('headerBackgroundColor', $headerbackgroundcolor, 'color' , $extraSettings, $stockdio_market_overview_options, '');
		
	
	$showChart = true;
	
	$default_includeChart ='';
	$initCheck = array_key_exists('booleanIniCheck',$stockdio_market_overview_options)? $stockdio_market_overview_options['booleanIniCheck'] == '1' : false;
	if (isset($stockdio_market_overview_options['default_includeChart']))
		$default_includeChart = $stockdio_market_overview_options['default_includeChart'];
	
	$default_intraday = "true";
	if ($stockdio_market_overview_options['default_intraday'] == 0 && $stockdio_market_overview_options['intradayCheck']!= "") 
			$default_intraday = "false";
	
	if (empty($includechart))
		$includechart=$default_includeChart;
	
	$link = 'https://api.stockdio.com/visualization/financial/charts/v1/marketoverview';
	
	//return "<strong>".$default_includeChart."</strong>";
	
	if ($includechart=="1" ||$includechart=="true" || $title != "" || (isset($default_title) && $default_title != ""))
		$showBorderAndTitle = '&showBorderAndTitle=true';
	else
		$showBorderAndTitle = '&showBorderAndTitle=false';
	$extraSettings .= $showBorderAndTitle;	
	
	if ($includechart=="1" ||$includechart=="true"){
		$link = 'https://api.stockdio.com/visualization/financial/charts/v1/marketoverviewchart';
		stockdio_market_overview_get_param_value('displayPrices', $showheader, 'string' , $extraSettings, $stockdio_market_overview_options, '');
		stockdio_market_overview_get_param_value('days', $days, 'string' , $extraSettings, $stockdio_market_overview_options, '');
		stockdio_market_overview_get_param_value('allowPeriodChange', $allowperiodchange, 'bool' , $extraSettings, $stockdio_market_overview_options, '1');
		
		$default_chartHeight = '';
		if (isset($stockdio_market_overview_options['default_chartHeight']))
			$default_chartHeight = $stockdio_market_overview_options['default_chartHeight'];
		if (empty($chartheight))
			$chartheight =$default_chartHeight;
		if (strpos($chartheight, 'px') !== FALSE && strpos($chartheight, '%') !== FALSE) 
			$chartheight =$chartheight.'px';
		if (empty($chartheight))
			$chartheight='200px';
		$extraSettings .= '&chartHeight='.$chartheight;		
	}
	
	
	
	$default_width = '';
	if (isset($stockdio_market_overview_options['default_width']))
		$default_width = $stockdio_market_overview_options['default_width'];
	
	$default_height = '';
	if (isset($stockdio_market_overview_options['default_height']))
		$default_height = $stockdio_market_overview_options['default_height'];
	
	if (empty($width))
		$width =$default_width;
	if (empty($width))
		$width ='100%';
	if (strpos($width, 'px') !== FALSE && strpos($width, '%') !== FALSE) 
		$width =$width.'px';
	$extraSettings .= '&width='.urlencode('100%');	
		
	$iframeHeight = '';
	if (empty($height))
		$height =$default_height;
	if (strpos($height, 'px') !== FALSE && strpos($height, '%') !== FALSE) 
		$height =$height.'px';
	if (!empty($height)){
		$extraSettings .= '&height='.urlencode($height);
		$iframeHeight=' height="'.$height.'" ';
	}
	
	if (empty($intraday))
		$intraday=$default_intraday;
	if ($intraday == "1")
		$intraday = 'true';
	if ($intraday == "0")
		$intraday = 'false';
	$extraSettings .= '&intraday='.$intraday;

	$iframe_id= str_replace("{","",strtolower(getSMOGUID()));
	$iframe_id= str_replace("}","",$iframe_id);
	$extraSettings .= '&onload='.$iframe_id;
	
	  //make main html output  		  
	$default_loadDataWhenVisible = "true";
	if (!array_key_exists('default_loadDataWhenVisible',$stockdio_market_overview_options) || (array_key_exists('default_loadDataWhenVisible',$stockdio_market_overview_options) && $stockdio_market_overview_options['default_loadDataWhenVisible'] == 0) )
			$default_loadDataWhenVisible = "false";
	  
	 if (empty($loaddatawhenvisible))
		$loaddatawhenvisible=$default_loadDataWhenVisible;
	  
	$src = 'src';
	if ($loaddatawhenvisible == "1" || $loaddatawhenvisible == "true") 
		$src = 'iframesrc';		
	
	$output = '<iframe referrerpolicy="no-referrer-when-downgrade" id="'.$iframe_id.'" frameBorder="0" class="stockdio_market_overview" scrolling="no" width="'.$width.'" '.$iframeHeight.' '.$src.'="'.$link.'?app-key='.$api_key.'&wp=1&addVolume=false&showUserMenu=false'.$extraSettings.'"></iframe>';  		
  	//return completed string
  	return $output;

}	

	function getSMOGUID(){
		if (function_exists('com_create_guid')){
			return com_create_guid();
		}
		else {
			//mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
			$charid = strtoupper(md5(uniqid(rand(), true)));
			$hyphen = chr(45);// "-"
			$uuid = chr(123)// "{"
				.substr($charid, 0, 8).$hyphen
				.substr($charid, 8, 4).$hyphen
				.substr($charid,12, 4).$hyphen
				.substr($charid,16, 4).$hyphen
				.substr($charid,20,12)
				.chr(125);// "}"
			return $uuid;
		}
	}

	function stockdio_market_overview_get_param_value($varname, $var, $type, &$extraSettings, $stockdio_market_overview_options, $defaultvalue){

		$default ='';
		$defaultName ='default_'.$varname;
		$initCheck = array_key_exists('booleanIniCheck',$stockdio_market_overview_options)? $stockdio_market_overview_options['booleanIniCheck'] == '1' : false;
		if ($varname=="stockExchange")
			$defaultName='default_exchange';			
		if (isset($stockdio_market_overview_options[$defaultName]))
			$default = $stockdio_market_overview_options[$defaultName];
		if ($type == "string" || $type == "color"){
			if (empty($var))
				$var=$default;
			if (empty($var) && $defaultvalue!="")
				$var=$defaultvalue;
			if (empty($var) && $varname=="palette")
				$var='Financial-Light';				
			if (empty($var) && $varname=="motif")
				$var='Financial';						
			if (!empty($var))	{
				if ($varname=='logoMaxWidth' || $varname=='logoMaxHeight')
					$var =str_replace('px','',$var);
				$var = urlencode($var);
				if ($type == "color"){
					$var =str_replace('#','',$var);	
					$var =str_replace('%23','',$var);	
					$var =str_replace(' ','',$var);	
					$var =str_replace('+','',$var);	
				}
				$extraSettings .= '&'.$varname.'='.$var;			
			}
		}
		else {
			if ($type == "bool"){
				if (empty($var))
					$var=$default;

				if (!$initCheck && empty($var) && $defaultvalue!="")
					$var=$defaultvalue;
					
				if ($var=="1"||$var=="true") 
					$extraSettings .= '&'.$varname.'=true';		
				else
					$extraSettings .= '&'.$varname.'=false';						
			}
		}
	}

    /** 
     * ShortCode editor button
     */
	function stockdio_market_overview_register_button( $buttons ) {
		if (!array_key_exists("stockdio_charts_button", $buttons)) {
			array_push( $buttons, "|", "stockdio_charts_button" );	   
		}
		return $buttons;
	}	 
	function stockdio_market_overview_add_plugin( $plugin_array ) {
		if (!array_key_exists("stockdio_charts_button", $plugin_array)) {
			$plugin_data = get_plugin_data( __FILE__ );
			$plugin_version = $plugin_data['Version'];
			$plugin_array['stockdio_charts_button'] = plugin_dir_url( __FILE__ ).'assets/stockdio-charts-shortcode.js?ver='.$plugin_version;
			add_filter( 'mce_buttons', 'stockdio_market_overview_register_button' );	  			
		}
	   return $plugin_array;
	}	
	function stockdio_market_overview_charts_button() {
	   if ( current_user_can('edit_posts') && current_user_can('edit_pages') ) {
		  add_filter( 'mce_external_plugins', 'stockdio_market_overview_add_plugin' );		  		  
	   }
	}	
    /**
     * Intialize global variables
     */
    function stockdio_market_overview_stockdio_js(){ 
	$stockdio_market_overview_options = get_option( 'stockdio_market_overview_options' );
	?>
		<script>
			var stockdio_market_overview_settings = <?php echo json_encode( $stockdio_market_overview_options ); ?>;	
			jQuery(function () {				
				setDefaultValue = function(o,n,v){
					if (typeof o == 'undefined' || o[n]==null || o[n]=='')
						o[n] = v;
				}				
				setDefaultValue(stockdio_market_overview_settings,"default_height", '100%');
				setDefaultValue(stockdio_market_overview_settings, "default_width", '100%');
				
				setDefaultValue(stockdio_market_overview_settings, "default_logoMaxHeight", '20px');
				setDefaultValue(stockdio_market_overview_settings, "default_logoMaxWidth", '90px');
				
				if (pagenow == "settings_page_stockdio-market-overview-settings-config") {
					jQuery("#a_show_appkey_input").click(function(e){ 
						e.preventDefault();
						jQuery(".stockdio_register_mode").hide();
						jQuery(".stockdio_market_overview_form").show();
					});
					jQuery("#a_show_register_form").click(function(e){ 
						e.preventDefault();
						jQuery(".stockdio_register_mode").show();
						jQuery(".stockdio_market_overview_form").hide();
					});				
					var eventMethod = window.addEventListener ? "addEventListener" : "attachEvent";
					var eventer = window[eventMethod];
					var messageEvent = eventMethod == "attachEvent" ? "onmessage" : "message";

								
					if (jQuery("#api_key").val()== ""){					
						if (typeof stockdio_historical_charts_settings != 'undefined' && typeof stockdio_historical_charts_settings.api_key != 'undefined' && stockdio_historical_charts_settings.api_key != "") {
							jQuery("#api_key").val(stockdio_historical_charts_settings.api_key);
							jQuery("#a_show_appkey_input").click();
						}
						else{
							if (typeof stockdio_quotes_board_settings  != 'undefined' && typeof stockdio_quotes_board_settings.api_key != 'undefined' && stockdio_quotes_board_settings.api_key != "") {
								jQuery("#api_key").val(stockdio_quotes_board_settings.api_key);
								jQuery("#a_show_appkey_input").click();
							}
							else{
								if (typeof stockdio_news_board_settings != 'undefined' && typeof stockdio_news_board_settings.api_key != 'undefined' && stockdio_news_board_settings.api_key != "") {
									jQuery("#api_key").val(stockdio_news_board_settings.api_key);
									jQuery("#a_show_appkey_input").click();
								}
								else{
									if (typeof stockdio_ticker_settings != 'undefined' && typeof stockdio_ticker_settings.api_key != 'undefined' && stockdio_ticker_settings.api_key != "") {
										jQuery("#api_key").val(stockdio_ticker_settings.api_key);
										jQuery("#a_show_appkey_input").click();
									}
									else{
										
									}									
								}								
							}
						}
						if (jQuery("#default_exchange").length <= 0 && jQuery("#api_key").val()!= "" && jQuery("#api_key").val().length == 32) {
							jQuery("#submit").click();
						}
					}
				}
			

			});		
			var stockdio_market_overview=1;
			
		</script><?php
	}
	
	//register_activation_hook(__FILE__, 'stockdio_market_overview_my_plugin_activate');
	//add_action('admin_init', 'stockdio_market_overview_my_plugin_redirect');
	 
	function stockdio_market_overview_my_plugin_activate() {
		add_option('stockdio_market_overview_my_plugin_do_activation_redirect', true);
	}
	 
	function stockdio_market_overview_my_plugin_redirect() {
		if (get_option('stockdio_market_overview_my_plugin_do_activation_redirect', false)) {
			delete_option('stockdio_market_overview_my_plugin_do_activation_redirect');
			if(!isset($_GET['activate-multi']))
			{
				wp_redirect("options-general.php?page=stockdio-market-overview-settings-config");
			}
		}
	}
?>