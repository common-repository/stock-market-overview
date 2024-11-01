<?php

class Widget_Stockdio_Market_Overview extends WP_Widget {
 
  public function __construct() {
      $widget_ops = array('classname' => 
		'Widget_Stockdio_Market_Overview', 
		'description' => 'At-a-glance display of stock market, with categories for Equities, Indices, Commodities and Currencies.' );
      parent::__construct('Widget_Stockdio_Market_Overview', 'Stock Market Overview', $widget_ops);
  }
    
  function widget($args, $instance) {
    // PART 1: Extracting the arguments + getting the values
    extract($args, EXTR_SKIP);
    $title = !isset($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
	$exchange= !isset($instance['exchange']) ? '' : $instance['exchange'];
    $equities = !isset($instance['equities']) ? '' : $instance['equities'];	
	$indices= !isset($instance['indices']) ? '' : $instance['indices'];	
	$commodities= !isset($instance['commodities']) ? '' : $instance['commodities'];	
	$currencies= !isset($instance['currencies']) ? '' : $instance['currencies'];	
	$width = !isset($instance['width']) ? '' : $instance['width'];
	$height = !isset($instance['height']) ? '' : $instance['height'];	
	$includechart= (isset($instance['includechart']) AND $instance['includechart'] == 1) ? "true" : "false";
   
   $logomaxwidth= !isset($instance['logomaxwidth']) ? '' : $instance['logomaxwidth'];	
   $logomaxheight= !isset($instance['logomaxheight']) ? '' : $instance['logomaxheight'];	
   $culture= !isset($instance['culture']) ? '' : $instance['culture'];	
    // Before widget code, if any
    echo (isset($before_widget)?$before_widget:'');

	//echo $output;	
	echo stockdio_market_overview_func( array( 
									'title' => $title , 
									'stockexchange' => $exchange, 
									'equities' => $equities,
									'indices' => $indices,
									'commodities' => $commodities,
									'currencies' => $currencies,
									'width' => $width, 
									'includechart' => $includechart,
									'height' => $height,
									'includechart' => $includechart,
									'logomaxwidth' => $logomaxwidth,
									'logomaxheight' => $logomaxheight,
									'culture' => $culture
								));
   
    // After widget code, if any  
    echo (isset($after_widget)?$after_widget:'');
  }

  public function GetFromOptions($options, $value){
	  if (array_key_exists($value, $options))
		  return $options[$value];
	  else
		  return '';
  }
  
  public function form( $instance ) {
   
	$stockdio_market_overview_options = get_option( 'stockdio_market_overview_options' );
	//$default_title = GetFromOptions($stockdio_market_overview_options,'default_title');
     // PART 1: Extract the data from the instance variable
     $instance = wp_parse_args( (array) $instance, array( 
		 'title' => array_key_exists('default_title',$stockdio_market_overview_options)?$stockdio_market_overview_options ['default_title']:'',
		 'exchange' => array_key_exists('default_exchange',$stockdio_market_overview_options)?$stockdio_market_overview_options ['default_exchange']:'',
		 'equities' =>array_key_exists('default_equities',$stockdio_market_overview_options)?$stockdio_market_overview_options ['default_equities']:'',
		 'indices' => array_key_exists('default_indices',$stockdio_market_overview_options)?$stockdio_market_overview_options ['default_indices']:'',
		 'commodities' => array_key_exists('default_commodities',$stockdio_market_overview_options)?$stockdio_market_overview_options ['default_commodities']:'',
		 'currencies' => array_key_exists('default_currencies',$stockdio_market_overview_options)?$stockdio_market_overview_options ['default_currencies']:'',
		 'width' => array_key_exists('default_width',$stockdio_market_overview_options)?$stockdio_market_overview_options ['default_width']:'',
		 'height' => array_key_exists('default_height',$stockdio_market_overview_options)?$stockdio_market_overview_options ['default_height']:'',
		 'includechart' => array_key_exists('default_includeChart',$stockdio_market_overview_options)?$stockdio_market_overview_options ['default_includeChart']:'',
		 'logomaxwidth' => array_key_exists('default_logoMaxWidth',$stockdio_market_overview_options)?$stockdio_market_overview_options ['default_logoMaxWidth']:'',
		 'logomaxheight' => array_key_exists('default_logoMaxHeight',$stockdio_market_overview_options)?$stockdio_market_overview_options ['default_logoMaxHeight']:'',
		 'culture' => array_key_exists('default_culture',$stockdio_market_overview_options)?$stockdio_market_overview_options ['default_culture']:''
		 
	 ) );
	 
	 extract($instance);
   
     // PART 2-3: Display the fields
     ?>
	 
	<p>
		<label for="<?php echo $this->get_field_id('exchange'); ?>">Exchange:</label>
		 <select name="<?php echo $this->get_field_name('exchange'); ?>" id="<?php echo $this->get_field_id('exchange'); ?>">		
			<option value="" <?php if ( $exchange == '' ) echo 'selected="selected"'; ?>>None</option> 
			<option value="Forex" <?php if ( $exchange == 'Forex' ) echo 'selected="selected"'; ?>>Currencies Trading</option>
			<option value="Commodities" <?php if ( $exchange == 'Commodities' ) echo 'selected="selected"'; ?>>Commodities Trading</option>
			<option value="USA" <?php if ( $exchange == 'USA' ) echo 'selected="selected"'; ?>>USA Equities and ETFs</option>
			<option value="OTCMKTS" <?php if ( $exchange == 'OTCMKTS' ) echo 'selected="selected"'; ?>>USA OTC Markets</option>
			<option value="OTCBB" <?php if ( $exchange == 'OTCBB' ) echo 'selected="selected"'; ?>>USA OTC Bulletin Board</option>
			<option value="LSE" <?php if ( $exchange == 'LSE' ) echo 'selected="selected"'; ?>>London Stock Exchange</option>
			<option value="TSE" <?php if ( $exchange == 'TSE' ) echo 'selected="selected"'; ?>>Tokyo Stock Exchange</option>
			<option value="HKSE" <?php if ( $exchange == 'HKSE' ) echo 'selected="selected"'; ?>>Hong Kong Stock Exchange</option>
			<option value="SSE" <?php if ( $exchange == 'SSE' ) echo 'selected="selected"'; ?>>Shanghai Stock Exchange</option>
			<option value="SZSE" <?php if ( $exchange == 'SZSE' ) echo 'selected="selected"'; ?>>Shenzhen Stock Exchange</option>
			<option value="FWB" <?php if ( $exchange == 'FWB' ) echo 'selected="selected"'; ?>>Deutsche Börse Frankfurt</option>
			<option value="XETRA" <?php if ( $exchange == 'XETRA' ) echo 'selected="selected"'; ?>>XETRA</option>
			<option value="AEX" <?php if ( $exchange == 'AEX' ) echo 'selected="selected"'; ?>>Euronext Amsterdam</option>
			<option value="BEX" <?php if ( $exchange == 'BEX' ) echo 'selected="selected"'; ?>>Euronext Brussels</option>
			<option value="PEX" <?php if ( $exchange == 'PEX' ) echo 'selected="selected"'; ?>>Euronext Paris</option>
			<option value="LEX" <?php if ( $exchange == 'LEX' ) echo 'selected="selected"'; ?>>Euronext Lisbon</option>
			<option value="CHIX" <?php if ( $exchange == 'CHIX' ) echo 'selected="selected"'; ?>>Australia Chi-X</option>
			<option value="TSX" <?php if ( $exchange == 'TSX' ) echo 'selected="selected"'; ?>>Toronto Stock Exchange</option>
			<option value="TSXV" <?php if ( $exchange == 'TSXV' ) echo 'selected="selected"'; ?>>TSX Venture Exchange</option>
			<option value="CSE" <?php if ( $exchange == 'CSE' ) echo 'selected="selected"'; ?>>Canadian Securities Exchange</option>
			<option value="NEO" <?php if ( $exchange == 'NEO' ) echo 'selected="selected"'; ?>>NEO Exchange</option>
			<option value="SIX" <?php if ( $exchange == 'SIX' ) echo 'selected="selected"'; ?>>SIX Swiss Exchange</option>
			<option value="KRX" <?php if ( $exchange == 'KRX' ) echo 'selected="selected"'; ?>>Korean Stock Exchange</option>
			<option value="Kosdaq" <?php if ( $exchange == 'Kosdaq' ) echo 'selected="selected"'; ?>>Kosdaq Stock Exchange</option>
			<option value="OMXS" <?php if ( $exchange == 'OMXS' ) echo 'selected="selected"'; ?>>NASDAQ OMX Stockholm</option>
			<option value="OMXC" <?php if ( $exchange == 'OMXC' ) echo 'selected="selected"'; ?>>NASDAQ OMX Copenhagen</option>
			<option value="OMXH" <?php if ( $exchange == 'OMXH' ) echo 'selected="selected"'; ?>>NASDAQ OMX Helsinky</option>
			<option value="OMXI" <?php if ( $exchange == 'OMXI' ) echo 'selected="selected"'; ?>>NASDAQ OMX Iceland</option>
			<option value="BSE" <?php if ( $exchange == 'BSE' ) echo 'selected="selected"'; ?>>Bombay Stock Exchange</option>
			<option value="NSE" <?php if ( $exchange == 'NSE' ) echo 'selected="selected"'; ?>>India NSE</option>
			<option value="BME" <?php if ( $exchange == 'BME' ) echo 'selected="selected"'; ?>>Bolsa de Madrid</option>
			<option value="JSE" <?php if ( $exchange == 'JSE' ) echo 'selected="selected"'; ?>>Johannesburg Stock Exchange</option>	
			<option value="TWSE" <?php if ( $exchange == 'TWSE' ) echo 'selected="selected"'; ?>>Taiwan Stock Exchange</option>
			<option value="BIT" <?php if ( $exchange == 'BIT' ) echo 'selected="selected"'; ?>>Borsa Italiana</option>
			<option value="MOEX" <?php if ( $exchange == 'MOEX' ) echo 'selected="selected"'; ?>>Moscow Exchange</option>
			<option value="Bovespa" <?php if ( $exchange == 'Bovespa' ) echo 'selected="selected"'; ?>>Bovespa Sao Paulo Stock Exchange</option>
			<option value="NZX" <?php if ( $exchange == 'NZX' ) echo 'selected="selected"'; ?>>New Zealand Exchange</option>	
			<option value="ISE" <?php if ( $exchange == 'ISE' ) echo 'selected="selected"'; ?>>Irish Stock Exchange</option>
			<option value="SGX" <?php if ( $exchange == 'SGX' ) echo 'selected="selected"'; ?>>Singapore Exchange</option>	
			<option value="TADAWUL" <?php if ( $exchange == 'TADAWUL' ) echo 'selected="selected"'; ?>>Tadawul Saudi Stock Exchange</option>		
			<option value="TASE" <?php if ( $exchange == 'TASE' ) echo 'selected="selected"'; ?>>Tel Aviv Stock Exchange</option>			
			<option value="KLSE" <?php if ( $exchange == 'KLSE' ) echo 'selected="selected"'; ?>>Bursa Malaysia</option>	
			<option value="IDX" <?php if ( $exchange == 'IDX' ) echo 'selected="selected"'; ?>>Indonesia Stock Exchange</option>		
			<option value="BMV" <?php if ( $exchange == 'BMV' ) echo 'selected="selected"'; ?>>Bolsa Mexicana de Valores</option>
			<option value="OSE" <?php if ( $exchange == 'OSE' ) echo 'selected="selected"'; ?>>Oslo Stock Exchange</option>		
			<option value="BCBA" <?php if ( $exchange == 'BCBA' ) echo 'selected="selected"'; ?>>Bolsa de Comercio de Buenos Aires</option>			
			<option value="SET" <?php if ( $exchange == 'SET' ) echo 'selected="selected"'; ?>>Stock Exchange of Thailand</option>		
			<option value="VSE" <?php if ( $exchange == 'VSE' ) echo 'selected="selected"'; ?>>Vienna Stock Exchange</option>		
			<option value="BCS" <?php if ( $exchange == 'BCS' ) echo 'selected="selected"'; ?>>Bolsa de Comercio de Santigo</option>		
			<option value="BIST" <?php if ( $exchange == 'BIST' ) echo 'selected="selected"'; ?>>Borsa Istanbul</option>	
			<option value="OMXT" <?php if ( $exchange == 'OMXT' ) echo 'selected="selected"'; ?>>NASDAQ OMX Tallinn</option>	
			<option value="OMXR" <?php if ( $exchange == 'OMXR' ) echo 'selected="selected"'; ?>>NASDAQ OMX Riga</option>	
			<option value="OMXV" <?php if ( $exchange == 'OMXV' ) echo 'selected="selected"'; ?>>NASDAQ OMX Vilnius</option>	
			<option value="PSE" <?php if ( $exchange == 'PSE' ) echo 'selected="selected"'; ?>>Philippine Stock Exchange</option>
			<option value="ADX" <?php if ( $exchange == 'ADX' ) echo 'selected="selected"'; ?>>Abu Dhabi Securities Exchange</option>
			<option value="DFM" <?php if ( $exchange == 'DFM' ) echo 'selected="selected"'; ?>>Dubai Financial Market</option>
			<option value="BVC" <?php if ( $exchange == 'BVC' ) echo 'selected="selected"'; ?>>Bolsa de Valores de Colombia</option>
			<option value="NGSE" <?php if ( $exchange == 'NGSE' ) echo 'selected="selected"'; ?>>Nigerian Stock Exchange</option>				
			<option value="QSE" <?php if ( $exchange == 'QSE' ) echo 'selected="selected"'; ?>>Qatar Stock Exchange</option>	
			<option value="TPEX" <?php if ( $exchange == 'TPEX' ) echo 'selected="selected"'; ?>>Taipei Exchange</option>	
			<option value="BVL" <?php if ( $exchange == 'BVL' ) echo 'selected="selected"'; ?>>Bolsa de Valores de Lima</option>	
			<option value="EGX" <?php if ( $exchange == 'EGX' ) echo 'selected="selected"'; ?>>The Egyptian Exchange</option>	
			<option value="ASE" <?php if ( $exchange == 'ASE' ) echo 'selected="selected"'; ?>>Athens Stock Exchange</option>	
			<option value="NASE" <?php if ( $exchange == 'NASE' ) echo 'selected="selected"'; ?>>Nairobi Securities Exchange</option>	
			<option value="HNX" <?php if ( $exchange == 'HNX' ) echo 'selected="selected"'; ?>>Hanoi Stock Exchange</option>	
			<option value="HOSE" <?php if ( $exchange == 'HOSE' ) echo 'selected="selected"'; ?>>Hochiminh Stock Exchange</option>	
			<option value="BCPP" <?php if ( $exchange == 'BCPP' ) echo 'selected="selected"'; ?>>Prague Stock Exchange</option>					
			<option value="AMSE" <?php if ( $exchange == 'AMSE' ) echo 'selected="selected"'; ?>>Amman Stock Exchange</option>		
		 </select>
	</p>
	  
	 <!-- PART 3: Widget Equities field START -->
     <p>
      <label for="<?php echo $this->get_field_id('equities'); ?>">Equities: </label>
        <input class="widefat" id="<?php echo $this->get_field_id('equities'); ?>" 
               name="<?php echo $this->get_field_name('equities'); ?>" type="text" 
               value="<?php echo esc_attr($equities); ?>" />
      
      </p>
      <!-- Widget Equities field END -->
	  
	  	 <!-- PART 3: Widget Indices field START -->
     <p>
      <label for="<?php echo $this->get_field_id('indices'); ?>">Indices: </label>
        <input class="widefat" id="<?php echo $this->get_field_id('indices'); ?>" 
               name="<?php echo $this->get_field_name('indices'); ?>" type="text" 
               value="<?php echo esc_attr($indices); ?>" />
      
      </p>
      <!-- Widget Indices field END -->
	  
	  	 <!-- PART 3: Widget Commodities field START -->
     <p>
      <label for="<?php echo $this->get_field_id('commodities'); ?>">Commodities: </label>
        <input class="widefat" id="<?php echo $this->get_field_id('commodities'); ?>" 
               name="<?php echo $this->get_field_name('commodities'); ?>" type="text" 
               value="<?php echo esc_attr($commodities); ?>" />
      
      </p>
      <!-- Widget Commodities field END -->
	  
	  	 <!-- PART 3: Widget Currencies field START -->
     <p>
      <label for="<?php echo $this->get_field_id('currencies'); ?>">Currencies: </label>
        <input class="widefat" id="<?php echo $this->get_field_id('currencies'); ?>" 
               name="<?php echo $this->get_field_name('currencies'); ?>" type="text" 
               value="<?php echo esc_attr($currencies); ?>" />
      
      </p>
      <!-- Widget Currencies field END -->
	  
	       <!-- PART 3: Widget Width field START -->
     <p>
      <label for="<?php echo $this->get_field_id('width'); ?>">Width: </label>
        <input class="widefat" id="<?php echo $this->get_field_id('width'); ?>" 
               name="<?php echo $this->get_field_name('width'); ?>" type="text" 
               value="<?php echo esc_attr($width); ?>" />      
      </p>
      <!-- Widget Width field END -->
	  
	       <!-- PART 3: Widget Height field START -->
     <p>
      <label for="<?php echo $this->get_field_id('height'); ?>">Height: </label>
        <input class="widefat" id="<?php echo $this->get_field_id('height'); ?>" 
               name="<?php echo $this->get_field_name('height'); ?>" type="text" 
               value="<?php echo esc_attr($height); ?>" />      
      </p>
      <!-- Widget Height field END -->
	  
     <!-- PART 2: Widget Title field START -->
     <p>
      <label for="<?php echo $this->get_field_id('title'); ?>">Title: </label>
        <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" 
               name="<?php echo $this->get_field_name('title'); ?>" type="text" 
               value="<?php echo esc_attr($title); ?>" />
      
      </p>
      <!-- Widget Title field END -->
   

     <!-- PART 2: Widget Include Chart field START -->
     <p>      
        <input id="<?php echo $this->get_field_id('includechart'); ?>" 
               name="<?php echo $this->get_field_name('includechart'); ?>" type="checkbox" 
               value="1" 
			   <?php if($includechart) echo ' checked="checked"'; ?> />
		<label for="<?php echo $this->get_field_id('includechart'); ?>">Include Chart: </label>      
      </p>
      <!-- Widget Title field END -->   
	  
	<!-- PART 3: Widget Logo Maximum Height field START -->
     <p>
      <label for="<?php echo $this->get_field_id('logomaxheight'); ?>">Logo Maximum Height: </label>
        <input class="widefat" id="<?php echo $this->get_field_id('logomaxheight'); ?>" 
               name="<?php echo $this->get_field_name('logomaxheight'); ?>" type="text" 
               value="<?php echo esc_attr($logomaxheight); ?>" />      
      </p>
      <!-- Widget Logo Maximum Height field END -->
	  
	  <!-- PART 3: Widget Logo Maximum Width field START -->
     <p>
      <label for="<?php echo $this->get_field_id('logomaxwidth'); ?>">Logo Maximum Width </label>
        <input class="widefat" id="<?php echo $this->get_field_id('logomaxwidth'); ?>" 
               name="<?php echo $this->get_field_name('logomaxwidth'); ?>" type="text" 
               value="<?php echo esc_attr($logomaxwidth); ?>" />      
      </p>
      <!-- Widget Logo Maximum Width field END -->
	  
	  	<p>
		<label for="<?php echo $this->get_field_id('culture'); ?>">Culture:</label>
		 <select name="<?php echo $this->get_field_name('culture'); ?>" id="<?php echo $this->get_field_id('culture'); ?>">		
			<option value="" <?php if ( $culture== '' ) echo 'selected="selected"'; ?>>None</option> 		
			<option value="English-US" <?php if ( $culture== 'English-US' ) echo 'selected="selected"'; ?>>English-US</option> 
			<option value="English-UK" <?php if ( $culture== 'English-UK' ) echo 'selected="selected"'; ?>>English-UK</option> 
			<option value="English-Canada" <?php if ( $culture== 'English-Canada"' ) echo 'selected="selected"'; ?>>English-Canada</option> 
			<option value="English-Australia" <?php if ( $culture == 'English-Australia' ) echo 'selected="selected"'; ?>>English-Australia</option> 
			<option value="Spanish-Spain" <?php if ( $culture == 'Spanish-Spain' ) echo 'selected="selected"'; ?>>Spanish-Spain</option> 
			<option value="Spanish-Mexico" <?php if ( $culture == 'Spanish-Mexico' ) echo 'selected="selected"'; ?>>Spanish-Mexico</option> 
			<option value="Spanish-LatinAmerica" <?php if ( $culture == 'Spanish-LatinAmerica' ) echo 'selected="selected"'; ?>>Spanish-LatinAmerica</option> 
			<option value="French-France" <?php if ( $culture == 'French-France' ) echo 'selected="selected"'; ?>>French-France</option> 
			<option value="French-Canada" <?php if ( $culture == 'French-Canada' ) echo 'selected="selected"'; ?>>French-Canada</option> 
			<option value="French-Belgium" <?php if ( $culture == 'French-Belgium' ) echo 'selected="selected"'; ?>>French-Belgium</option> 
			<option value="French-Switzerland" <?php if ( $culture == 'French-Switzerland' ) echo 'selected="selected"'; ?>>French-Switzerland</option> 
			<option value="Italian-Italy" <?php if ( $culture == 'Italian-Italy' ) echo 'selected="selected"'; ?>>Italian-Italy</option> 
			<option value="Italian-Switzerland" <?php if ( $culture == 'Italian-Switzerland' ) echo 'selected="selected"'; ?>>Italian-Switzerland</option> 
			<option value="German-Germany" <?php if ( $culture == 'German-Germany' ) echo 'selected="selected"'; ?>>German-Germany</option> 
			<option value="German-Switzerland" <?php if ( $culture == 'German-Switzerland' ) echo 'selected="selected"'; ?>>German-Switzerland</option> 
			<option value="Portuguese-Brasil" <?php if ( $culture == 'Portuguese-Brasil' ) echo 'selected="selected"'; ?>>Portuguese-Brasil</option> 
			<option value="Portuguese-Portugal" <?php if ( $culture == 'Portuguese-Portugal' ) echo 'selected="selected"'; ?>>Portuguese-Portugal</option> 
			<option value="Dutch-Netherlands" <?php if ( $culture == 'Dutch-Netherlands' ) echo 'selected="selected"'; ?>>Dutch-Netherlands</option> 
			<option value="Dutch-Belgium" <?php if ( $culture == 'Dutch-Belgium' ) echo 'selected="selected"'; ?>>Dutch-Belgium</option> 
			<option value="SimplifiedChinese-China" <?php if ( $culture == 'SimplifiedChinese-China' ) echo 'selected="selected"'; ?>>SimplifiedChinese-China</option> 
			<option value="SimplifiedChinese-HongKong" <?php if ($culture == 'SimplifiedChinese-HongKong') echo 'selected="selected"'; ?>>SimplifiedChinese-HongKong</option> 	
			<option value="TraditionalChinese-HongKong" <?php if ($culture == 'TraditionalChinese-HongKong') echo 'selected="selected"'; ?>>TraditionalChinese-HongKong</option> 	
			<option value="Japanese" <?php if ( $culture == 'Japanese' ) echo 'selected="selected"'; ?>>Japanese</option> 
			<option value="Korean" <?php if ( $culture == 'Korean' ) echo 'selected="selected"'; ?>>Korean</option> 
			<option value="Russian" <?php if ( $culture == 'Russian' ) echo 'selected="selected"'; ?>>Russian</option> 	
			<option value="Polish" <?php if ( $culture == 'Polish' ) echo 'selected="selected"'; ?>>Polish</option> 			
			<option value="Turkish" <?php if ( $culture == 'Turkish' ) echo 'selected="selected"'; ?>>Turkish</option> 	
			<option value="Arabic" <?php if ( $culture == 'Arabic' ) echo 'selected="selected"'; ?>>Arabic</option> 			
			<option value="Hebrew" <?php if ( $culture == 'Hebrew' ) echo 'selected="selected"'; ?>>Hebrew</option> 
			<option value="Swedish" <?php if ( $culture == 'Swedish' ) echo 'selected="selected"'; ?>>Swedish</option> 
			<option value="Danish" <?php if ( $culture == 'Danish' ) echo 'selected="selected"'; ?>>Danish</option> 
			<option value="Finnish" <?php if ( $culture == 'Finnish' ) echo 'selected="selected"'; ?>>Finnish</option> 
			<option value="Norwegian" <?php if ( $culture == 'Norwegian' ) echo 'selected="selected"'; ?>>Norwegian</option> 
			<option value="Icelandic" <?php if ( $culture == 'Icelandic' ) echo 'selected="selected"'; ?>>Icelandic</option> 
			<option value="Greek" <?php if ( $culture == 'Greek' ) echo 'selected="selected"'; ?>>Greek</option> 
			<option value="Czech" <?php if ( $culture == 'Czech' ) echo 'selected="selected"'; ?>>Czech</option> 
			<option value="Thai" <?php if ( $culture == 'Thai' ) echo 'selected="selected"'; ?>>Thai</option> 
			<option value="Vietnamese" <?php if ( $culture == 'Vietnamese' ) echo 'selected="selected"'; ?>>Vietnamese</option> 
			<option value="Hindi" <?php if ( $culture == 'Hindi' ) echo 'selected="selected"'; ?>>Hindi</option> 
			<option value="Indonesian" <?php if ( $culture == 'Indonesian' ) echo 'selected="selected"'; ?>>Indonesian</option> 
		 </select>
	</p>
	  	  
     <?php
   
  }
 
  function update($new_instance, $old_instance) {
    $instance = $old_instance;
    $instance['title'] = $new_instance['title'];
	$instance['exchange'] = $new_instance['exchange'];
    $instance['equities'] = $new_instance['equities'];
	$instance['indices'] = $new_instance['indices'];
	$instance['commodities'] = $new_instance['commodities'];
	$instance['currencies'] = $new_instance['currencies'];
	$instance['width'] = $new_instance['width'];
	$instance['height'] = $new_instance['height'];
	$instance['includechart'] = (isset($new_instance['includechart']) AND $new_instance['includechart'] == 1) ? 1 : 0;
	$instance['culture'] = $new_instance['culture'];
	$instance['logomaxwidth'] = $new_instance['logomaxwidth'];
	$instance['logomaxheight'] = $new_instance['logomaxheight'];
    return $instance;
  }
  
 
}

//add_action( 'widgets_init', create_function('', 'return register_widget("Widget_Stockdio_Market_Overview");') );
add_action( 'widgets_init', function() {
	return register_widget("Widget_Stockdio_Market_Overview");
});

add_action('admin_print_styles', 'stockdio_market_overview_widget_admin_styles');

function stockdio_market_overview_widget_admin_styles() {
  ?>
  <style>
	#available-widgets-list [class*=widget_stockdio_market_overview] .widget-title:before{
	  content: "\61";
	  font-family: "stockdio-font" !important;
	}
  </style>
  <?php
}

?>