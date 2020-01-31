<?php
/**
 * @package Unlimited Elements
 * @author UniteCMS.net
 * @copyright (C) 2017 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */
defined('ELEMENTOR_WIDGET_PRO_INC') or die('Restricted access');


class UniteFontManagerUC{
		
	private static $brandIcons;
	
	
	/**
	 * get arr icons
	 */
	private function fetchIconsFromCss_getArrIcons(){
		
		$filename = "fontawesome-all.css";
		
		$pathCssFile = GlobalsUC::$pathLibrary."font-awesome5/css/{$filename}";
		UniteFunctionsUC::validateFilepath($pathCssFile,"css file");
		$content = file_get_contents($pathCssFile);
		
		$arrLines = explode("\n", $content);
		$arrIcons = array();
		
		foreach($arrLines as $line){
			
			if(strpos($line, ":before {") === false)
				continue;
			
			$line = str_replace(":before {", "", $line);
			$line = str_replace(".fa-", "", $line);
			$line = trim($line);
			
			$icon = $line;
			$arrIcons[] = $icon;
		}
		
		return($arrIcons);
	}
	
	/**
	 * get icons array from svg
	 */
	private function fetchIconsFromCss_getArrIconsFromSvg($type){
		
		switch($type){
			
			case "brand":
				$filename = "fa-brands-400.svg";
			break;
			case "regular":
				$filename = "fa-regular-400.svg";
			break;
			case "light":
				$filename = "fa-light-300.svg";				
			break;
			default:
				UniteFunctionsUC::throwError("Wrong icons type: $type");
			break;
		}
		
		
		$pathCssFile = GlobalsUC::$pathLibrary."font-awesome5/css/{$filename}";
		UniteFunctionsUC::validateFilepath($pathCssFile,"css file");
		$content = file_get_contents($pathCssFile);
		
		$arrLines = explode("\n", $content);
		$arrIcons = array();
		
		foreach($arrLines as $line){
			
			if(strpos($line, "<glyph glyph-name=") === false)
				continue;
				
			$line = str_replace("<glyph glyph-name=", "", $line);
			$line = str_replace('"', "", $line);
							
			$line = trim($line);
			
			$icon = $line;
			$arrIcons[$icon] = true;
		}
		
		
		return($arrIcons);
	}
	
	
	
	/**
	 * print brand icons from file
	 */
	private function printBrandIcons($arrBrandIcons){
		
		$arrBrandIcons = array_keys($arrBrandIcons);
		
		$this->printIcons($arrBrandIcons);
	}
	
	
	/**
	 * print icons in textarea
	 */
	private function printIcons($arrIcons){
		
		$strJson = json_encode($arrIcons);
		
		?>
		<textarea style="width:800px;height:300px;"><?php echo esc_html($strJson)?></textarea>
			
		<?php 
	}
	
	
	/**
	 * fetch icons list from css file
	 */
	private function fetchIconsFromCss(){
				
		$arrIcons = $this->fetchIconsFromCss_getArrIcons();
		
		$arrBrandIcons = $this->fetchIconsFromCss_getArrIconsFromSvg("brand");
		$arrRegularIcons = $this->fetchIconsFromCss_getArrIconsFromSvg("regular");
		$arrLightIcons = $this->fetchIconsFromCss_getArrIconsFromSvg("light");
		
		//$this->printBrandIcons($arrBrandIcons);exit();
		
		$arrAllIcons = array();
		
		foreach($arrIcons as $key=>$icon){
			
			//add brand and solid
			$isBrand = isset($arrBrandIcons[$icon]);
			if($isBrand){
				$fullIcon = "fab fa-".$icon;
				$arrAllIcons[] = $fullIcon;
			}else
				$arrAllIcons[] = $icon;
			
			//add regular
			$isRegular = isset($arrRegularIcons[$icon]);
			if($isRegular == true){
				unset($arrRegularIcons[$icon]);
				$fullIcon = "far fa-".$icon;
				$arrAllIcons[] = $fullIcon;
			}
			
			//add light
			$isLight = isset($arrLightIcons[$icon]);
			if($isLight == true){
				unset($arrLightIcons[$icon]);
				$fullIcon = "fal fa-".$icon;
				$arrAllIcons[] = $fullIcon;
			}
			
		}
		
		
		$this->printIcons($arrAllIcons);
	}
	
	
	/**
	 * fetch font awsome icons array from site
	 */
	private function fa_fetchArrIconsFromSite(){
		// Array for the results
		$results = array();
		
		
		// Load page
		$html = file_get_contents( 'http://fontawesome.io/icons/' );
		
		// Validate page content
		if ( strpos( $html, 'section id="web-application"' ) !== false ) {
		
			// Get all sections
			if ( preg_match_all( '/<section.+>(.+)<\/section>/iUs', $html, $m ) ) {
		
				$sections = $m[1];
		
				foreach ( $sections as $section_html ) {
		
					// Get section title
					if ( preg_match( '/<h2 class="page-header">(.+)<\/h2>/iU', $section_html, $m ) ) {
						$section_title = trim( $m[1] );
		
						// Get all fonts of this section
						if ( $section_title ) {
							if ( preg_match_all( '/<\/span>([-a-z0-9]+).*<\/a><\/div>/i', $section_html, $m ) ) {
								$results[ $section_title ] = $m[1];
							}
						}
					}
				}
			}
		}
		
		$total = array();
		foreach($results as $cat){
			foreach($cat as $icon){
				$total[] = $icon;
			}
		}
		
		dmp("original:");
		dmp($results);
		
		dmp("total icons:");
		dmp($total);
		exit();
		
		/*
		$json = json_encode($total);
		
		$fp = fopen("icons.json","w+");
		fwrite($fp,$json);
		fclose($fp);
		*/
	}
	
	
	/**
	 * get icons array
	 */
	public static function fa_getJsonIcons(){
		
		$jsonIconsNew = '["address-book","address-book-o","address-card","address-card-o","bandcamp","bath","bathtub","drivers-license","drivers-license-o","eercast","envelope-open","envelope-open-o","etsy","free-code-camp","grav","handshake-o","id-badge","id-card","id-card-o","imdb","linode","meetup","microchip","podcast","quora","ravelry","s15","shower","snowflake-o","superpowers","telegram","thermometer","thermometer-0","thermometer-1","thermometer-2","thermometer-3","thermometer-4","thermometer-empty","thermometer-full","thermometer-half","thermometer-quarter","thermometer-three-quarters","times-rectangle","times-rectangle-o","user-circle","user-circle-o","user-o","vcard","vcard-o","window-close","window-close-o","window-maximize","window-minimize","window-restore","wpexplorer"]';
		
		$jsonIcons = '["adjust","american-sign-language-interpreting","anchor","archive","area-chart","arrows","arrows-h","arrows-v","asl-interpreting","assistive-listening-systems","asterisk","at","audio-description","automobile","balance-scale","ban","bank","bar-chart","bar-chart-o","barcode","bars","battery","battery-0","battery-1","battery-2","battery-3","battery-4","battery-empty","battery-full","battery-half","battery-quarter","battery-three-quarters","bed","beer","bell","bell-o","bell-slash","bell-slash-o","bicycle","binoculars","birthday-cake","blind","bluetooth","bluetooth-b","bolt","bomb","book","bookmark","bookmark-o","braille","briefcase","bug","building","building-o","bullhorn","bullseye","bus","cab","calculator","calendar","calendar-check-o","calendar-minus-o","calendar-o","calendar-plus-o","calendar-times-o","camera","camera-retro","car","caret-square-o-down","caret-square-o-left","caret-square-o-right","caret-square-o-up","cart-arrow-down","cart-plus","cc","certificate","check","check-circle","check-circle-o","check-square","check-square-o","child","circle","circle-o","circle-o-notch","circle-thin","clock-o","clone","close","cloud","cloud-download","cloud-upload","code","code-fork","coffee","cog","cogs","comment","comment-o","commenting","commenting-o","comments","comments-o","compass","copyright","creative-commons","credit-card","credit-card-alt","crop","crosshairs","cube","cubes","cutlery","dashboard","database","deaf","deafness","desktop","diamond","dot-circle-o","download","edit","ellipsis-h","ellipsis-v","envelope","envelope-o","envelope-square","eraser","exchange","exclamation","exclamation-circle","exclamation-triangle","external-link","external-link-square","eye","eye-slash","eyedropper","fax","feed","female","fighter-jet","file-archive-o","file-audio-o","file-code-o","file-excel-o","file-image-o","file-movie-o","file-pdf-o","file-photo-o","file-picture-o","file-powerpoint-o","file-sound-o","file-video-o","file-word-o","file-zip-o","film","filter","fire","fire-extinguisher","flag","flag-checkered","flag-o","flash","flask","folder","folder-o","folder-open","folder-open-o","frown-o","futbol-o","gamepad","gavel","gear","gears","gift","glass","globe","graduation-cap","group","hand-grab-o","hand-lizard-o","hand-paper-o","hand-peace-o","hand-pointer-o","hand-rock-o","hand-scissors-o","hand-spock-o","hand-stop-o","hard-of-hearing","hashtag","hdd-o","headphones","heart","heart-o","heartbeat","history","home","hotel","hourglass","hourglass-1","hourglass-2","hourglass-3","hourglass-end","hourglass-half","hourglass-o","hourglass-start","i-cursor","image","inbox","industry","info","info-circle","institution","key","keyboard-o","language","laptop","leaf","legal","lemon-o","level-down","level-up","life-bouy","life-buoy","life-ring","life-saver","lightbulb-o","line-chart","location-arrow","lock","low-vision","magic","magnet","mail-forward","mail-reply","mail-reply-all","male","map","map-marker","map-o","map-pin","map-signs","meh-o","microphone","microphone-slash","minus","minus-circle","minus-square","minus-square-o","mobile","mobile-phone","money","moon-o","mortar-board","motorcycle","mouse-pointer","music","navicon","newspaper-o","object-group","object-ungroup","paint-brush","paper-plane","paper-plane-o","paw","pencil","pencil-square","pencil-square-o","percent","phone","phone-square","photo","picture-o","pie-chart","plane","plug","plus","plus-circle","plus-square","plus-square-o","power-off","print","puzzle-piece","qrcode","question","question-circle","question-circle-o","quote-left","quote-right","random","recycle","refresh","registered","remove","reorder","reply","reply-all","retweet","road","rocket","rss","rss-square","search","search-minus","search-plus","send","send-o","server","share","share-alt","share-alt-square","share-square","share-square-o","shield","ship","shopping-bag","shopping-basket","shopping-cart","sign-in","sign-language","sign-out","signal","signing","sitemap","sliders","smile-o","soccer-ball-o","sort","sort-alpha-asc","sort-alpha-desc","sort-amount-asc","sort-amount-desc","sort-asc","sort-desc","sort-down","sort-numeric-asc","sort-numeric-desc","sort-up","space-shuttle","spinner","spoon","square","square-o","star","star-half","star-half-empty","star-half-full","star-half-o","star-o","sticky-note","sticky-note-o","street-view","suitcase","sun-o","support","tablet","tachometer","tag","tags","tasks","taxi","television","terminal","thumb-tack","thumbs-down","thumbs-o-down","thumbs-o-up","thumbs-up","ticket","times","times-circle","times-circle-o","tint","toggle-down","toggle-left","toggle-off","toggle-on","toggle-right","toggle-up","trademark","trash","trash-o","tree","trophy","truck","tty","tv","umbrella","universal-access","university","unlock","unlock-alt","unsorted","upload","user","user-plus","user-secret","user-times","users","video-camera","volume-control-phone","volume-down","volume-off","volume-up","warning","wheelchair","wheelchair-alt","wifi","wrench","hand-o-down","hand-o-left","hand-o-right","hand-o-up","ambulance","subway","train","genderless","intersex","mars","mars-double","mars-stroke","mars-stroke-h","mars-stroke-v","mercury","neuter","transgender","transgender-alt","venus","venus-double","venus-mars","file","file-o","file-text","file-text-o","cc-amex","cc-diners-club","cc-discover","cc-jcb","cc-mastercard","cc-paypal","cc-stripe","cc-visa","google-wallet","paypal","bitcoin","btc","cny","dollar","eur","euro","gbp","gg","gg-circle","ils","inr","jpy","krw","rmb","rouble","rub","ruble","rupee","shekel","sheqel","try","turkish-lira","usd","won","yen","align-center","align-justify","align-left","align-right","bold","chain","chain-broken","clipboard","columns","copy","cut","dedent","files-o","floppy-o","font","header","indent","italic","link","list","list-alt","list-ol","list-ul","outdent","paperclip","paragraph","paste","repeat","rotate-left","rotate-right","save","scissors","strikethrough","subscript","superscript","table","text-height","text-width","th","th-large","th-list","underline","undo","unlink","angle-double-down","angle-double-left","angle-double-right","angle-double-up","angle-down","angle-left","angle-right","angle-up","arrow-circle-down","arrow-circle-left","arrow-circle-o-down","arrow-circle-o-left","arrow-circle-o-right","arrow-circle-o-up","arrow-circle-right","arrow-circle-up","arrow-down","arrow-left","arrow-right","arrow-up","arrows-alt","caret-down","caret-left","caret-right","caret-up","chevron-circle-down","chevron-circle-left","chevron-circle-right","chevron-circle-up","chevron-down","chevron-left","chevron-right","chevron-up","long-arrow-down","long-arrow-left","long-arrow-right","long-arrow-up","backward","compress","eject","expand","fast-backward","fast-forward","forward","pause","pause-circle","pause-circle-o","play","play-circle","play-circle-o","step-backward","step-forward","stop","stop-circle","stop-circle-o","youtube-play","500px","adn","amazon","android","angellist","apple","behance","behance-square","bitbucket","bitbucket-square","black-tie","buysellads","chrome","codepen","codiepie","connectdevelop","contao","css3","dashcube","delicious","deviantart","digg","dribbble","dropbox","drupal","edge","empire","envira","expeditedssl","fa","facebook","facebook-f","facebook-official","facebook-square","firefox","first-order","flickr","font-awesome","fonticons","fort-awesome","forumbee","foursquare","ge","get-pocket","git","git-square","github","github-alt","github-square","gitlab","gittip","glide","glide-g","google","google-plus","google-plus-circle","google-plus-official","google-plus-square","gratipay","hacker-news","houzz","html5","instagram","internet-explorer","ioxhost","joomla","jsfiddle","lastfm","lastfm-square","leanpub","linkedin","linkedin-square","linux","maxcdn","meanpath","medium","mixcloud","modx","odnoklassniki","odnoklassniki-square","opencart","openid","opera","optin-monster","pagelines","pied-piper","pied-piper-alt","pied-piper-pp","pinterest","pinterest-p","pinterest-square","product-hunt","qq","ra","rebel","reddit","reddit-alien","reddit-square","renren","resistance","safari","scribd","sellsy","shirtsinbulk","simplybuilt","skyatlas","skype","slack","slideshare","snapchat","snapchat-ghost","snapchat-square","soundcloud","spotify","stack-exchange","stack-overflow","steam","steam-square","stumbleupon","stumbleupon-circle","tencent-weibo","themeisle","trello","tripadvisor","tumblr","tumblr-square","twitch","twitter","twitter-square","usb","viacoin","viadeo","viadeo-square","vimeo","vimeo-square","vine","vk","wechat","weibo","weixin","whatsapp","wikipedia-w","windows","wordpress","wpbeginner","wpforms","xing","xing-square","y-combinator","y-combinator-square","yahoo","yc","yc-square","yelp","yoast","youtube","youtube-square","h-square","hospital-o","medkit","stethoscope","user-md"]';
				
		$jsonIconsFA5 = '["fab fa-500px","fab fa-accessible-icon","fab fa-accusoft","address-book","far fa-address-book","fal fa-address-book","address-card","far fa-address-card","fal fa-address-card","adjust","far fa-adjust","fal fa-adjust","fab fa-adn","fab fa-adversal","fab fa-affiliatetheme","alarm-clock","far fa-alarm-clock","fal fa-alarm-clock","fab fa-algolia","align-center","far fa-align-center","fal fa-align-center","align-justify","far fa-align-justify","fal fa-align-justify","align-left","far fa-align-left","fal fa-align-left","align-right","far fa-align-right","fal fa-align-right","allergies","far fa-allergies","fal fa-allergies","fab fa-amazon","fab fa-amazon-pay","ambulance","far fa-ambulance","fal fa-ambulance","american-sign-language-interpreting","far fa-american-sign-language-interpreting","fal fa-american-sign-language-interpreting","fab fa-amilia","anchor","far fa-anchor","fal fa-anchor","fab fa-android","fab fa-angellist","angle-double-down","far fa-angle-double-down","fal fa-angle-double-down","angle-double-left","far fa-angle-double-left","fal fa-angle-double-left","angle-double-right","far fa-angle-double-right","fal fa-angle-double-right","angle-double-up","far fa-angle-double-up","fal fa-angle-double-up","angle-down","far fa-angle-down","fal fa-angle-down","angle-left","far fa-angle-left","fal fa-angle-left","angle-right","far fa-angle-right","fal fa-angle-right","angle-up","far fa-angle-up","fal fa-angle-up","fab fa-angrycreative","fab fa-angular","fab fa-app-store","fab fa-app-store-ios","fab fa-apper","fab fa-apple","fab fa-apple-pay","archive","far fa-archive","fal fa-archive","arrow-alt-circle-down","far fa-arrow-alt-circle-down","fal fa-arrow-alt-circle-down","arrow-alt-circle-left","far fa-arrow-alt-circle-left","fal fa-arrow-alt-circle-left","arrow-alt-circle-right","far fa-arrow-alt-circle-right","fal fa-arrow-alt-circle-right","arrow-alt-circle-up","far fa-arrow-alt-circle-up","fal fa-arrow-alt-circle-up","arrow-alt-down","far fa-arrow-alt-down","fal fa-arrow-alt-down","arrow-alt-from-bottom","far fa-arrow-alt-from-bottom","fal fa-arrow-alt-from-bottom","arrow-alt-from-left","far fa-arrow-alt-from-left","fal fa-arrow-alt-from-left","arrow-alt-from-right","far fa-arrow-alt-from-right","fal fa-arrow-alt-from-right","arrow-alt-from-top","far fa-arrow-alt-from-top","fal fa-arrow-alt-from-top","arrow-alt-left","far fa-arrow-alt-left","fal fa-arrow-alt-left","arrow-alt-right","far fa-arrow-alt-right","fal fa-arrow-alt-right","arrow-alt-square-down","far fa-arrow-alt-square-down","fal fa-arrow-alt-square-down","arrow-alt-square-left","far fa-arrow-alt-square-left","fal fa-arrow-alt-square-left","arrow-alt-square-right","far fa-arrow-alt-square-right","fal fa-arrow-alt-square-right","arrow-alt-square-up","far fa-arrow-alt-square-up","fal fa-arrow-alt-square-up","arrow-alt-to-bottom","far fa-arrow-alt-to-bottom","fal fa-arrow-alt-to-bottom","arrow-alt-to-left","far fa-arrow-alt-to-left","fal fa-arrow-alt-to-left","arrow-alt-to-right","far fa-arrow-alt-to-right","fal fa-arrow-alt-to-right","arrow-alt-to-top","far fa-arrow-alt-to-top","fal fa-arrow-alt-to-top","arrow-alt-up","far fa-arrow-alt-up","fal fa-arrow-alt-up","arrow-circle-down","far fa-arrow-circle-down","fal fa-arrow-circle-down","arrow-circle-left","far fa-arrow-circle-left","fal fa-arrow-circle-left","arrow-circle-right","far fa-arrow-circle-right","fal fa-arrow-circle-right","arrow-circle-up","far fa-arrow-circle-up","fal fa-arrow-circle-up","arrow-down","far fa-arrow-down","fal fa-arrow-down","arrow-from-bottom","far fa-arrow-from-bottom","fal fa-arrow-from-bottom","arrow-from-left","far fa-arrow-from-left","fal fa-arrow-from-left","arrow-from-right","far fa-arrow-from-right","fal fa-arrow-from-right","arrow-from-top","far fa-arrow-from-top","fal fa-arrow-from-top","arrow-left","far fa-arrow-left","fal fa-arrow-left","arrow-right","far fa-arrow-right","fal fa-arrow-right","arrow-square-down","far fa-arrow-square-down","fal fa-arrow-square-down","arrow-square-left","far fa-arrow-square-left","fal fa-arrow-square-left","arrow-square-right","far fa-arrow-square-right","fal fa-arrow-square-right","arrow-square-up","far fa-arrow-square-up","fal fa-arrow-square-up","arrow-to-bottom","far fa-arrow-to-bottom","fal fa-arrow-to-bottom","arrow-to-left","far fa-arrow-to-left","fal fa-arrow-to-left","arrow-to-right","far fa-arrow-to-right","fal fa-arrow-to-right","arrow-to-top","far fa-arrow-to-top","fal fa-arrow-to-top","arrow-up","far fa-arrow-up","fal fa-arrow-up","arrows","far fa-arrows","fal fa-arrows","arrows-alt","far fa-arrows-alt","fal fa-arrows-alt","arrows-alt-h","far fa-arrows-alt-h","fal fa-arrows-alt-h","arrows-alt-v","far fa-arrows-alt-v","fal fa-arrows-alt-v","arrows-h","far fa-arrows-h","fal fa-arrows-h","arrows-v","far fa-arrows-v","fal fa-arrows-v","assistive-listening-systems","far fa-assistive-listening-systems","fal fa-assistive-listening-systems","asterisk","far fa-asterisk","fal fa-asterisk","fab fa-asymmetrik","at","far fa-at","fal fa-at","fab fa-audible","audio-description","far fa-audio-description","fal fa-audio-description","fab fa-autoprefixer","fab fa-avianex","fab fa-aviato","fab fa-aws","backward","far fa-backward","fal fa-backward","badge","far fa-badge","fal fa-badge","badge-check","far fa-badge-check","fal fa-badge-check","balance-scale","far fa-balance-scale","fal fa-balance-scale","ban","far fa-ban","fal fa-ban","band-aid","far fa-band-aid","fal fa-band-aid","fab fa-bandcamp","barcode","far fa-barcode","fal fa-barcode","barcode-alt","far fa-barcode-alt","fal fa-barcode-alt","barcode-read","far fa-barcode-read","fal fa-barcode-read","barcode-scan","far fa-barcode-scan","fal fa-barcode-scan","bars","far fa-bars","fal fa-bars","baseball","far fa-baseball","fal fa-baseball","baseball-ball","far fa-baseball-ball","fal fa-baseball-ball","basketball-ball","far fa-basketball-ball","fal fa-basketball-ball","basketball-hoop","far fa-basketball-hoop","fal fa-basketball-hoop","bath","far fa-bath","fal fa-bath","battery-bolt","far fa-battery-bolt","fal fa-battery-bolt","battery-empty","far fa-battery-empty","fal fa-battery-empty","battery-full","far fa-battery-full","fal fa-battery-full","battery-half","far fa-battery-half","fal fa-battery-half","battery-quarter","far fa-battery-quarter","fal fa-battery-quarter","battery-slash","far fa-battery-slash","fal fa-battery-slash","battery-three-quarters","far fa-battery-three-quarters","fal fa-battery-three-quarters","bed","far fa-bed","fal fa-bed","beer","far fa-beer","fal fa-beer","fab fa-behance","fab fa-behance-square","bell","far fa-bell","fal fa-bell","bell-slash","far fa-bell-slash","fal fa-bell-slash","bicycle","far fa-bicycle","fal fa-bicycle","fab fa-bimobject","binoculars","far fa-binoculars","fal fa-binoculars","birthday-cake","far fa-birthday-cake","fal fa-birthday-cake","fab fa-bitbucket","fab fa-bitcoin","fab fa-bity","fab fa-black-tie","fab fa-blackberry","blanket","far fa-blanket","fal fa-blanket","blind","far fa-blind","fal fa-blind","fab fa-blogger","fab fa-blogger-b","fab fa-bluetooth","fab fa-bluetooth-b","bold","far fa-bold","fal fa-bold","bolt","far fa-bolt","fal fa-bolt","bomb","far fa-bomb","fal fa-bomb","book","far fa-book","fal fa-book","book-heart","far fa-book-heart","fal fa-book-heart","bookmark","far fa-bookmark","fal fa-bookmark","bowling-ball","far fa-bowling-ball","fal fa-bowling-ball","bowling-pins","far fa-bowling-pins","fal fa-bowling-pins","box","far fa-box","fal fa-box","box-alt","far fa-box-alt","fal fa-box-alt","box-check","far fa-box-check","fal fa-box-check","box-fragile","far fa-box-fragile","fal fa-box-fragile","box-full","far fa-box-full","fal fa-box-full","box-heart","far fa-box-heart","fal fa-box-heart","box-open","far fa-box-open","fal fa-box-open","box-up","far fa-box-up","fal fa-box-up","box-usd","far fa-box-usd","fal fa-box-usd","boxes","far fa-boxes","fal fa-boxes","boxes-alt","far fa-boxes-alt","fal fa-boxes-alt","boxing-glove","far fa-boxing-glove","fal fa-boxing-glove","braille","far fa-braille","fal fa-braille","briefcase","far fa-briefcase","fal fa-briefcase","briefcase-medical","far fa-briefcase-medical","fal fa-briefcase-medical","browser","far fa-browser","fal fa-browser","fab fa-btc","bug","far fa-bug","fal fa-bug","building","far fa-building","fal fa-building","bullhorn","far fa-bullhorn","fal fa-bullhorn","bullseye","far fa-bullseye","fal fa-bullseye","burn","far fa-burn","fal fa-burn","fab fa-buromobelexperte","bus","far fa-bus","fal fa-bus","fab fa-buysellads","calculator","far fa-calculator","fal fa-calculator","calendar","far fa-calendar","fal fa-calendar","calendar-alt","far fa-calendar-alt","fal fa-calendar-alt","calendar-check","far fa-calendar-check","fal fa-calendar-check","calendar-edit","far fa-calendar-edit","fal fa-calendar-edit","calendar-exclamation","far fa-calendar-exclamation","fal fa-calendar-exclamation","calendar-minus","far fa-calendar-minus","fal fa-calendar-minus","calendar-plus","far fa-calendar-plus","fal fa-calendar-plus","calendar-times","far fa-calendar-times","fal fa-calendar-times","camera","far fa-camera","fal fa-camera","camera-alt","far fa-camera-alt","fal fa-camera-alt","camera-retro","far fa-camera-retro","fal fa-camera-retro","capsules","far fa-capsules","fal fa-capsules","car","far fa-car","fal fa-car","caret-circle-down","far fa-caret-circle-down","fal fa-caret-circle-down","caret-circle-left","far fa-caret-circle-left","fal fa-caret-circle-left","caret-circle-right","far fa-caret-circle-right","fal fa-caret-circle-right","caret-circle-up","far fa-caret-circle-up","fal fa-caret-circle-up","caret-down","far fa-caret-down","fal fa-caret-down","caret-left","far fa-caret-left","fal fa-caret-left","caret-right","far fa-caret-right","fal fa-caret-right","caret-square-down","far fa-caret-square-down","fal fa-caret-square-down","caret-square-left","far fa-caret-square-left","fal fa-caret-square-left","caret-square-right","far fa-caret-square-right","fal fa-caret-square-right","caret-square-up","far fa-caret-square-up","fal fa-caret-square-up","caret-up","far fa-caret-up","fal fa-caret-up","cart-arrow-down","far fa-cart-arrow-down","fal fa-cart-arrow-down","cart-plus","far fa-cart-plus","fal fa-cart-plus","fab fa-cc-amazon-pay","fab fa-cc-amex","fab fa-cc-apple-pay","fab fa-cc-diners-club","fab fa-cc-discover","fab fa-cc-jcb","fab fa-cc-mastercard","fab fa-cc-paypal","fab fa-cc-stripe","fab fa-cc-visa","fab fa-centercode","certificate","far fa-certificate","fal fa-certificate","chart-area","far fa-chart-area","fal fa-chart-area","chart-bar","far fa-chart-bar","fal fa-chart-bar","chart-line","far fa-chart-line","fal fa-chart-line","chart-pie","far fa-chart-pie","fal fa-chart-pie","check","far fa-check","fal fa-check","check-circle","far fa-check-circle","fal fa-check-circle","check-square","far fa-check-square","fal fa-check-square","chess","far fa-chess","fal fa-chess","chess-bishop","far fa-chess-bishop","fal fa-chess-bishop","chess-bishop-alt","far fa-chess-bishop-alt","fal fa-chess-bishop-alt","chess-board","far fa-chess-board","fal fa-chess-board","chess-clock","far fa-chess-clock","fal fa-chess-clock","chess-clock-alt","far fa-chess-clock-alt","fal fa-chess-clock-alt","chess-king","far fa-chess-king","fal fa-chess-king","chess-king-alt","far fa-chess-king-alt","fal fa-chess-king-alt","chess-knight","far fa-chess-knight","fal fa-chess-knight","chess-knight-alt","far fa-chess-knight-alt","fal fa-chess-knight-alt","chess-pawn","far fa-chess-pawn","fal fa-chess-pawn","chess-pawn-alt","far fa-chess-pawn-alt","fal fa-chess-pawn-alt","chess-queen","far fa-chess-queen","fal fa-chess-queen","chess-queen-alt","far fa-chess-queen-alt","fal fa-chess-queen-alt","chess-rook","far fa-chess-rook","fal fa-chess-rook","chess-rook-alt","far fa-chess-rook-alt","fal fa-chess-rook-alt","chevron-circle-down","far fa-chevron-circle-down","fal fa-chevron-circle-down","chevron-circle-left","far fa-chevron-circle-left","fal fa-chevron-circle-left","chevron-circle-right","far fa-chevron-circle-right","fal fa-chevron-circle-right","chevron-circle-up","far fa-chevron-circle-up","fal fa-chevron-circle-up","chevron-double-down","far fa-chevron-double-down","fal fa-chevron-double-down","chevron-double-left","far fa-chevron-double-left","fal fa-chevron-double-left","chevron-double-right","far fa-chevron-double-right","fal fa-chevron-double-right","chevron-double-up","far fa-chevron-double-up","fal fa-chevron-double-up","chevron-down","far fa-chevron-down","fal fa-chevron-down","chevron-left","far fa-chevron-left","fal fa-chevron-left","chevron-right","far fa-chevron-right","fal fa-chevron-right","chevron-square-down","far fa-chevron-square-down","fal fa-chevron-square-down","chevron-square-left","far fa-chevron-square-left","fal fa-chevron-square-left","chevron-square-right","far fa-chevron-square-right","fal fa-chevron-square-right","chevron-square-up","far fa-chevron-square-up","fal fa-chevron-square-up","chevron-up","far fa-chevron-up","fal fa-chevron-up","child","far fa-child","fal fa-child","fab fa-chrome","circle","far fa-circle","fal fa-circle","circle-notch","far fa-circle-notch","fal fa-circle-notch","clipboard","far fa-clipboard","fal fa-clipboard","clipboard-check","far fa-clipboard-check","fal fa-clipboard-check","clipboard-list","far fa-clipboard-list","fal fa-clipboard-list","clock","far fa-clock","fal fa-clock","clone","far fa-clone","fal fa-clone","closed-captioning","far fa-closed-captioning","fal fa-closed-captioning","cloud","far fa-cloud","fal fa-cloud","cloud-download","far fa-cloud-download","fal fa-cloud-download","cloud-download-alt","far fa-cloud-download-alt","fal fa-cloud-download-alt","cloud-upload","far fa-cloud-upload","fal fa-cloud-upload","cloud-upload-alt","far fa-cloud-upload-alt","fal fa-cloud-upload-alt","fab fa-cloudscale","fab fa-cloudsmith","fab fa-cloudversify","club","far fa-club","fal fa-club","code","far fa-code","fal fa-code","code-branch","far fa-code-branch","fal fa-code-branch","code-commit","far fa-code-commit","fal fa-code-commit","code-merge","far fa-code-merge","fal fa-code-merge","fab fa-codepen","fab fa-codiepie","coffee","far fa-coffee","fal fa-coffee","cog","far fa-cog","fal fa-cog","cogs","far fa-cogs","fal fa-cogs","columns","far fa-columns","fal fa-columns","comment","far fa-comment","fal fa-comment","comment-alt","far fa-comment-alt","fal fa-comment-alt","comment-alt-check","far fa-comment-alt-check","fal fa-comment-alt-check","comment-alt-dots","far fa-comment-alt-dots","fal fa-comment-alt-dots","comment-alt-edit","far fa-comment-alt-edit","fal fa-comment-alt-edit","comment-alt-exclamation","far fa-comment-alt-exclamation","fal fa-comment-alt-exclamation","comment-alt-lines","far fa-comment-alt-lines","fal fa-comment-alt-lines","comment-alt-minus","far fa-comment-alt-minus","fal fa-comment-alt-minus","comment-alt-plus","far fa-comment-alt-plus","fal fa-comment-alt-plus","comment-alt-slash","far fa-comment-alt-slash","fal fa-comment-alt-slash","comment-alt-smile","far fa-comment-alt-smile","fal fa-comment-alt-smile","comment-alt-times","far fa-comment-alt-times","fal fa-comment-alt-times","comment-check","far fa-comment-check","fal fa-comment-check","comment-dots","far fa-comment-dots","fal fa-comment-dots","comment-edit","far fa-comment-edit","fal fa-comment-edit","comment-exclamation","far fa-comment-exclamation","fal fa-comment-exclamation","comment-lines","far fa-comment-lines","fal fa-comment-lines","comment-minus","far fa-comment-minus","fal fa-comment-minus","comment-plus","far fa-comment-plus","fal fa-comment-plus","comment-slash","far fa-comment-slash","fal fa-comment-slash","comment-smile","far fa-comment-smile","fal fa-comment-smile","comment-times","far fa-comment-times","fal fa-comment-times","comments","far fa-comments","fal fa-comments","comments-alt","far fa-comments-alt","fal fa-comments-alt","compass","far fa-compass","fal fa-compass","compress","far fa-compress","fal fa-compress","compress-alt","far fa-compress-alt","fal fa-compress-alt","compress-wide","far fa-compress-wide","fal fa-compress-wide","fab fa-connectdevelop","container-storage","far fa-container-storage","fal fa-container-storage","fab fa-contao","conveyor-belt","far fa-conveyor-belt","fal fa-conveyor-belt","conveyor-belt-alt","far fa-conveyor-belt-alt","fal fa-conveyor-belt-alt","copy","far fa-copy","fal fa-copy","copyright","far fa-copyright","fal fa-copyright","couch","far fa-couch","fal fa-couch","fab fa-cpanel","fab fa-creative-commons","credit-card","far fa-credit-card","fal fa-credit-card","credit-card-blank","far fa-credit-card-blank","fal fa-credit-card-blank","credit-card-front","far fa-credit-card-front","fal fa-credit-card-front","cricket","far fa-cricket","fal fa-cricket","crop","far fa-crop","fal fa-crop","crosshairs","far fa-crosshairs","fal fa-crosshairs","fab fa-css3","fab fa-css3-alt","cube","far fa-cube","fal fa-cube","cubes","far fa-cubes","fal fa-cubes","curling","far fa-curling","fal fa-curling","cut","far fa-cut","fal fa-cut","fab fa-cuttlefish","fab fa-d-and-d","fab fa-dashcube","database","far fa-database","fal fa-database","deaf","far fa-deaf","fal fa-deaf","fab fa-delicious","fab fa-deploydog","fab fa-deskpro","desktop","far fa-desktop","fal fa-desktop","desktop-alt","far fa-desktop-alt","fal fa-desktop-alt","fab fa-deviantart","diagnoses","far fa-diagnoses","fal fa-diagnoses","diamond","far fa-diamond","fal fa-diamond","fab fa-digg","fab fa-digital-ocean","fab fa-discord","fab fa-discourse","dna","far fa-dna","fal fa-dna","fab fa-dochub","fab fa-docker","dollar-sign","far fa-dollar-sign","fal fa-dollar-sign","dolly","far fa-dolly","fal fa-dolly","dolly-empty","far fa-dolly-empty","fal fa-dolly-empty","dolly-flatbed","far fa-dolly-flatbed","fal fa-dolly-flatbed","dolly-flatbed-alt","far fa-dolly-flatbed-alt","fal fa-dolly-flatbed-alt","dolly-flatbed-empty","far fa-dolly-flatbed-empty","fal fa-dolly-flatbed-empty","donate","far fa-donate","fal fa-donate","dot-circle","far fa-dot-circle","fal fa-dot-circle","dove","far fa-dove","fal fa-dove","download","far fa-download","fal fa-download","fab fa-draft2digital","fab fa-dribbble","fab fa-dribbble-square","fab fa-dropbox","fab fa-drupal","dumbbell","far fa-dumbbell","fal fa-dumbbell","fab fa-dyalog","fab fa-earlybirds","fab fa-edge","edit","far fa-edit","fal fa-edit","eject","far fa-eject","fal fa-eject","fab fa-elementor","ellipsis-h","far fa-ellipsis-h","fal fa-ellipsis-h","ellipsis-h-alt","far fa-ellipsis-h-alt","fal fa-ellipsis-h-alt","ellipsis-v","far fa-ellipsis-v","fal fa-ellipsis-v","ellipsis-v-alt","far fa-ellipsis-v-alt","fal fa-ellipsis-v-alt","fab fa-ember","fab fa-empire","envelope","far fa-envelope","fal fa-envelope","envelope-open","far fa-envelope-open","fal fa-envelope-open","envelope-square","far fa-envelope-square","fal fa-envelope-square","fab fa-envira","eraser","far fa-eraser","fal fa-eraser","fab fa-erlang","fab fa-ethereum","fab fa-etsy","euro-sign","far fa-euro-sign","fal fa-euro-sign","exchange","far fa-exchange","fal fa-exchange","exchange-alt","far fa-exchange-alt","fal fa-exchange-alt","exclamation","far fa-exclamation","fal fa-exclamation","exclamation-circle","far fa-exclamation-circle","fal fa-exclamation-circle","exclamation-square","far fa-exclamation-square","fal fa-exclamation-square","exclamation-triangle","far fa-exclamation-triangle","fal fa-exclamation-triangle","expand","far fa-expand","fal fa-expand","expand-alt","far fa-expand-alt","fal fa-expand-alt","expand-arrows","far fa-expand-arrows","fal fa-expand-arrows","expand-arrows-alt","far fa-expand-arrows-alt","fal fa-expand-arrows-alt","expand-wide","far fa-expand-wide","fal fa-expand-wide","fab fa-expeditedssl","external-link","far fa-external-link","fal fa-external-link","external-link-alt","far fa-external-link-alt","fal fa-external-link-alt","external-link-square","far fa-external-link-square","fal fa-external-link-square","external-link-square-alt","far fa-external-link-square-alt","fal fa-external-link-square-alt","eye","far fa-eye","fal fa-eye","eye-dropper","far fa-eye-dropper","fal fa-eye-dropper","eye-slash","far fa-eye-slash","fal fa-eye-slash","fab fa-facebook","fab fa-facebook-f","fab fa-facebook-messenger","fab fa-facebook-square","fast-backward","far fa-fast-backward","fal fa-fast-backward","fast-forward","far fa-fast-forward","fal fa-fast-forward","fax","far fa-fax","fal fa-fax","female","far fa-female","fal fa-female","field-hockey","far fa-field-hockey","fal fa-field-hockey","fighter-jet","far fa-fighter-jet","fal fa-fighter-jet","file","far fa-file","fal fa-file","file-alt","far fa-file-alt","fal fa-file-alt","file-archive","far fa-file-archive","fal fa-file-archive","file-audio","far fa-file-audio","fal fa-file-audio","file-check","far fa-file-check","fal fa-file-check","file-code","far fa-file-code","fal fa-file-code","file-edit","far fa-file-edit","fal fa-file-edit","file-excel","far fa-file-excel","fal fa-file-excel","file-exclamation","far fa-file-exclamation","fal fa-file-exclamation","file-image","far fa-file-image","fal fa-file-image","file-medical","far fa-file-medical","fal fa-file-medical","file-medical-alt","far fa-file-medical-alt","fal fa-file-medical-alt","file-minus","far fa-file-minus","fal fa-file-minus","file-pdf","far fa-file-pdf","fal fa-file-pdf","file-plus","far fa-file-plus","fal fa-file-plus","file-powerpoint","far fa-file-powerpoint","fal fa-file-powerpoint","file-times","far fa-file-times","fal fa-file-times","file-video","far fa-file-video","fal fa-file-video","file-word","far fa-file-word","fal fa-file-word","film","far fa-film","fal fa-film","film-alt","far fa-film-alt","fal fa-film-alt","filter","far fa-filter","fal fa-filter","fire","far fa-fire","fal fa-fire","fire-extinguisher","far fa-fire-extinguisher","fal fa-fire-extinguisher","fab fa-firefox","first-aid","far fa-first-aid","fal fa-first-aid","fab fa-first-order","fab fa-firstdraft","flag","far fa-flag","fal fa-flag","flag-checkered","far fa-flag-checkered","fal fa-flag-checkered","flask","far fa-flask","fal fa-flask","fab fa-flickr","fab fa-flipboard","fab fa-fly","folder","far fa-folder","fal fa-folder","folder-open","far fa-folder-open","fal fa-folder-open","font","far fa-font","fal fa-font","fab fa-font-awesome","fab fa-font-awesome-alt","fab fa-font-awesome-flag","fab fa-fonticons","fab fa-fonticons-fi","football-ball","far fa-football-ball","fal fa-football-ball","football-helmet","far fa-football-helmet","fal fa-football-helmet","forklift","far fa-forklift","fal fa-forklift","fab fa-fort-awesome","fab fa-fort-awesome-alt","fab fa-forumbee","forward","far fa-forward","fal fa-forward","fab fa-foursquare","fragile","far fa-fragile","fal fa-fragile","fab fa-free-code-camp","fab fa-freebsd","frown","far fa-frown","fal fa-frown","futbol","far fa-futbol","fal fa-futbol","gamepad","far fa-gamepad","fal fa-gamepad","gavel","far fa-gavel","fal fa-gavel","gem","far fa-gem","fal fa-gem","genderless","far fa-genderless","fal fa-genderless","fab fa-get-pocket","fab fa-gg","fab fa-gg-circle","gift","far fa-gift","fal fa-gift","fab fa-git","fab fa-git-square","fab fa-github","fab fa-github-alt","fab fa-github-square","fab fa-gitkraken","fab fa-gitlab","fab fa-gitter","glass-martini","far fa-glass-martini","fal fa-glass-martini","fab fa-glide","fab fa-glide-g","globe","far fa-globe","fal fa-globe","fab fa-gofore","golf-ball","far fa-golf-ball","fal fa-golf-ball","golf-club","far fa-golf-club","fal fa-golf-club","fab fa-goodreads","fab fa-goodreads-g","fab fa-google","fab fa-google-drive","fab fa-google-play","fab fa-google-plus","fab fa-google-plus-g","fab fa-google-plus-square","fab fa-google-wallet","graduation-cap","far fa-graduation-cap","fal fa-graduation-cap","fab fa-gratipay","fab fa-grav","fab fa-gripfire","fab fa-grunt","fab fa-gulp","h-square","far fa-h-square","fal fa-h-square","h1","far fa-h1","fal fa-h1","h2","far fa-h2","fal fa-h2","h3","far fa-h3","fal fa-h3","fab fa-hacker-news","fab fa-hacker-news-square","hand-heart","far fa-hand-heart","fal fa-hand-heart","hand-holding","far fa-hand-holding","fal fa-hand-holding","hand-holding-box","far fa-hand-holding-box","fal fa-hand-holding-box","hand-holding-heart","far fa-hand-holding-heart","fal fa-hand-holding-heart","hand-holding-seedling","far fa-hand-holding-seedling","fal fa-hand-holding-seedling","hand-holding-usd","far fa-hand-holding-usd","fal fa-hand-holding-usd","hand-holding-water","far fa-hand-holding-water","fal fa-hand-holding-water","hand-lizard","far fa-hand-lizard","fal fa-hand-lizard","hand-paper","far fa-hand-paper","fal fa-hand-paper","hand-peace","far fa-hand-peace","fal fa-hand-peace","hand-point-down","far fa-hand-point-down","fal fa-hand-point-down","hand-point-left","far fa-hand-point-left","fal fa-hand-point-left","hand-point-right","far fa-hand-point-right","fal fa-hand-point-right","hand-point-up","far fa-hand-point-up","fal fa-hand-point-up","hand-pointer","far fa-hand-pointer","fal fa-hand-pointer","hand-receiving","far fa-hand-receiving","fal fa-hand-receiving","hand-rock","far fa-hand-rock","fal fa-hand-rock","hand-scissors","far fa-hand-scissors","fal fa-hand-scissors","hand-spock","far fa-hand-spock","fal fa-hand-spock","hands","far fa-hands","fal fa-hands","hands-heart","far fa-hands-heart","fal fa-hands-heart","hands-helping","far fa-hands-helping","fal fa-hands-helping","hands-usd","far fa-hands-usd","fal fa-hands-usd","handshake","far fa-handshake","fal fa-handshake","handshake-alt","far fa-handshake-alt","fal fa-handshake-alt","hashtag","far fa-hashtag","fal fa-hashtag","hdd","far fa-hdd","fal fa-hdd","heading","far fa-heading","fal fa-heading","headphones","far fa-headphones","fal fa-headphones","heart","far fa-heart","fal fa-heart","heart-circle","far fa-heart-circle","fal fa-heart-circle","heart-square","far fa-heart-square","fal fa-heart-square","heartbeat","far fa-heartbeat","fal fa-heartbeat","hexagon","far fa-hexagon","fal fa-hexagon","fab fa-hips","fab fa-hire-a-helper","history","far fa-history","fal fa-history","hockey-puck","far fa-hockey-puck","fal fa-hockey-puck","hockey-sticks","far fa-hockey-sticks","fal fa-hockey-sticks","home","far fa-home","fal fa-home","home-heart","far fa-home-heart","fal fa-home-heart","fab fa-hooli","hospital","far fa-hospital","fal fa-hospital","hospital-alt","far fa-hospital-alt","fal fa-hospital-alt","hospital-symbol","far fa-hospital-symbol","fal fa-hospital-symbol","fab fa-hotjar","hourglass","far fa-hourglass","fal fa-hourglass","hourglass-end","far fa-hourglass-end","fal fa-hourglass-end","hourglass-half","far fa-hourglass-half","fal fa-hourglass-half","hourglass-start","far fa-hourglass-start","fal fa-hourglass-start","fab fa-houzz","fab fa-html5","fab fa-hubspot","i-cursor","far fa-i-cursor","fal fa-i-cursor","id-badge","far fa-id-badge","fal fa-id-badge","id-card","far fa-id-card","fal fa-id-card","id-card-alt","far fa-id-card-alt","fal fa-id-card-alt","image","far fa-image","fal fa-image","images","far fa-images","fal fa-images","fab fa-imdb","inbox","far fa-inbox","fal fa-inbox","inbox-in","far fa-inbox-in","fal fa-inbox-in","inbox-out","far fa-inbox-out","fal fa-inbox-out","indent","far fa-indent","fal fa-indent","industry","far fa-industry","fal fa-industry","industry-alt","far fa-industry-alt","fal fa-industry-alt","info","far fa-info","fal fa-info","info-circle","far fa-info-circle","fal fa-info-circle","info-square","far fa-info-square","fal fa-info-square","fab fa-instagram","fab fa-internet-explorer","inventory","far fa-inventory","fal fa-inventory","fab fa-ioxhost","italic","far fa-italic","fal fa-italic","fab fa-itunes","fab fa-itunes-note","jack-o-lantern","far fa-jack-o-lantern","fal fa-jack-o-lantern","fab fa-jenkins","fab fa-joget","fab fa-joomla","fab fa-js","fab fa-js-square","fab fa-jsfiddle","key","far fa-key","fal fa-key","keyboard","far fa-keyboard","fal fa-keyboard","fab fa-keycdn","fab fa-kickstarter","fab fa-kickstarter-k","fab fa-korvue","lamp","far fa-lamp","fal fa-lamp","language","far fa-language","fal fa-language","laptop","far fa-laptop","fal fa-laptop","fab fa-laravel","fab fa-lastfm","fab fa-lastfm-square","leaf","far fa-leaf","fal fa-leaf","leaf-heart","far fa-leaf-heart","fal fa-leaf-heart","fab fa-leanpub","lemon","far fa-lemon","fal fa-lemon","fab fa-less","level-down","far fa-level-down","fal fa-level-down","level-down-alt","far fa-level-down-alt","fal fa-level-down-alt","level-up","far fa-level-up","fal fa-level-up","level-up-alt","far fa-level-up-alt","fal fa-level-up-alt","life-ring","far fa-life-ring","fal fa-life-ring","lightbulb","far fa-lightbulb","fal fa-lightbulb","fab fa-line","link","far fa-link","fal fa-link","fab fa-linkedin","fab fa-linkedin-in","fab fa-linode","fab fa-linux","lira-sign","far fa-lira-sign","fal fa-lira-sign","list","far fa-list","fal fa-list","list-alt","far fa-list-alt","fal fa-list-alt","list-ol","far fa-list-ol","fal fa-list-ol","list-ul","far fa-list-ul","fal fa-list-ul","location-arrow","far fa-location-arrow","fal fa-location-arrow","lock","far fa-lock","fal fa-lock","lock-alt","far fa-lock-alt","fal fa-lock-alt","lock-open","far fa-lock-open","fal fa-lock-open","lock-open-alt","far fa-lock-open-alt","fal fa-lock-open-alt","long-arrow-alt-down","far fa-long-arrow-alt-down","fal fa-long-arrow-alt-down","long-arrow-alt-left","far fa-long-arrow-alt-left","fal fa-long-arrow-alt-left","long-arrow-alt-right","far fa-long-arrow-alt-right","fal fa-long-arrow-alt-right","long-arrow-alt-up","far fa-long-arrow-alt-up","fal fa-long-arrow-alt-up","long-arrow-down","far fa-long-arrow-down","fal fa-long-arrow-down","long-arrow-left","far fa-long-arrow-left","fal fa-long-arrow-left","long-arrow-right","far fa-long-arrow-right","fal fa-long-arrow-right","long-arrow-up","far fa-long-arrow-up","fal fa-long-arrow-up","loveseat","far fa-loveseat","fal fa-loveseat","low-vision","far fa-low-vision","fal fa-low-vision","luchador","far fa-luchador","fal fa-luchador","fab fa-lyft","fab fa-magento","magic","far fa-magic","fal fa-magic","magnet","far fa-magnet","fal fa-magnet","male","far fa-male","fal fa-male","map","far fa-map","fal fa-map","map-marker","far fa-map-marker","fal fa-map-marker","map-marker-alt","far fa-map-marker-alt","fal fa-map-marker-alt","map-pin","far fa-map-pin","fal fa-map-pin","map-signs","far fa-map-signs","fal fa-map-signs","mars","far fa-mars","fal fa-mars","mars-double","far fa-mars-double","fal fa-mars-double","mars-stroke","far fa-mars-stroke","fal fa-mars-stroke","mars-stroke-h","far fa-mars-stroke-h","fal fa-mars-stroke-h","mars-stroke-v","far fa-mars-stroke-v","fal fa-mars-stroke-v","fab fa-maxcdn","fab fa-medapps","fab fa-medium","fab fa-medium-m","medkit","far fa-medkit","fal fa-medkit","fab fa-medrt","fab fa-meetup","meh","far fa-meh","fal fa-meh","mercury","far fa-mercury","fal fa-mercury","microchip","far fa-microchip","fal fa-microchip","microphone","far fa-microphone","fal fa-microphone","microphone-alt","far fa-microphone-alt","fal fa-microphone-alt","microphone-slash","far fa-microphone-slash","fal fa-microphone-slash","fab fa-microsoft","minus","far fa-minus","fal fa-minus","minus-circle","far fa-minus-circle","fal fa-minus-circle","minus-hexagon","far fa-minus-hexagon","fal fa-minus-hexagon","minus-octagon","far fa-minus-octagon","fal fa-minus-octagon","minus-square","far fa-minus-square","fal fa-minus-square","fab fa-mix","fab fa-mixcloud","fab fa-mizuni","mobile","far fa-mobile","fal fa-mobile","mobile-alt","far fa-mobile-alt","fal fa-mobile-alt","mobile-android","far fa-mobile-android","fal fa-mobile-android","mobile-android-alt","far fa-mobile-android-alt","fal fa-mobile-android-alt","fab fa-modx","fab fa-monero","money-bill","far fa-money-bill","fal fa-money-bill","money-bill-alt","far fa-money-bill-alt","fal fa-money-bill-alt","moon","far fa-moon","fal fa-moon","motorcycle","far fa-motorcycle","fal fa-motorcycle","mouse-pointer","far fa-mouse-pointer","fal fa-mouse-pointer","music","far fa-music","fal fa-music","fab fa-napster","neuter","far fa-neuter","fal fa-neuter","newspaper","far fa-newspaper","fal fa-newspaper","fab fa-nintendo-switch","fab fa-node","fab fa-node-js","notes-medical","far fa-notes-medical","fal fa-notes-medical","fab fa-npm","fab fa-ns8","fab fa-nutritionix","object-group","far fa-object-group","fal fa-object-group","object-ungroup","far fa-object-ungroup","fal fa-object-ungroup","octagon","far fa-octagon","fal fa-octagon","fab fa-odnoklassniki","fab fa-odnoklassniki-square","fab fa-opencart","fab fa-openid","fab fa-opera","fab fa-optin-monster","fab fa-osi","outdent","far fa-outdent","fal fa-outdent","fab fa-page4","fab fa-pagelines","paint-brush","far fa-paint-brush","fal fa-paint-brush","fab fa-palfed","pallet","far fa-pallet","fal fa-pallet","pallet-alt","far fa-pallet-alt","fal fa-pallet-alt","paper-plane","far fa-paper-plane","fal fa-paper-plane","paperclip","far fa-paperclip","fal fa-paperclip","parachute-box","far fa-parachute-box","fal fa-parachute-box","paragraph","far fa-paragraph","fal fa-paragraph","paste","far fa-paste","fal fa-paste","fab fa-patreon","pause","far fa-pause","fal fa-pause","pause-circle","far fa-pause-circle","fal fa-pause-circle","paw","far fa-paw","fal fa-paw","fab fa-paypal","pen","far fa-pen","fal fa-pen","pen-alt","far fa-pen-alt","fal fa-pen-alt","pen-square","far fa-pen-square","fal fa-pen-square","pencil","far fa-pencil","fal fa-pencil","pencil-alt","far fa-pencil-alt","fal fa-pencil-alt","pennant","far fa-pennant","fal fa-pennant","people-carry","far fa-people-carry","fal fa-people-carry","percent","far fa-percent","fal fa-percent","fab fa-periscope","person-carry","far fa-person-carry","fal fa-person-carry","person-dolly","far fa-person-dolly","fal fa-person-dolly","person-dolly-empty","far fa-person-dolly-empty","fal fa-person-dolly-empty","fab fa-phabricator","fab fa-phoenix-framework","phone","far fa-phone","fal fa-phone","phone-plus","far fa-phone-plus","fal fa-phone-plus","phone-slash","far fa-phone-slash","fal fa-phone-slash","phone-square","far fa-phone-square","fal fa-phone-square","phone-volume","far fa-phone-volume","fal fa-phone-volume","fab fa-php","fab fa-pied-piper","fab fa-pied-piper-alt","fab fa-pied-piper-pp","piggy-bank","far fa-piggy-bank","fal fa-piggy-bank","pills","far fa-pills","fal fa-pills","fab fa-pinterest","fab fa-pinterest-p","fab fa-pinterest-square","plane","far fa-plane","fal fa-plane","plane-alt","far fa-plane-alt","fal fa-plane-alt","play","far fa-play","fal fa-play","play-circle","far fa-play-circle","fal fa-play-circle","fab fa-playstation","plug","far fa-plug","fal fa-plug","plus","far fa-plus","fal fa-plus","plus-circle","far fa-plus-circle","fal fa-plus-circle","plus-hexagon","far fa-plus-hexagon","fal fa-plus-hexagon","plus-octagon","far fa-plus-octagon","fal fa-plus-octagon","plus-square","far fa-plus-square","fal fa-plus-square","podcast","far fa-podcast","fal fa-podcast","poo","far fa-poo","fal fa-poo","portrait","far fa-portrait","fal fa-portrait","pound-sign","far fa-pound-sign","fal fa-pound-sign","power-off","far fa-power-off","fal fa-power-off","prescription-bottle","far fa-prescription-bottle","fal fa-prescription-bottle","prescription-bottle-alt","far fa-prescription-bottle-alt","fal fa-prescription-bottle-alt","print","far fa-print","fal fa-print","procedures","far fa-procedures","fal fa-procedures","fab fa-product-hunt","fab fa-pushed","puzzle-piece","far fa-puzzle-piece","fal fa-puzzle-piece","fab fa-python","fab fa-qq","qrcode","far fa-qrcode","fal fa-qrcode","question","far fa-question","fal fa-question","question-circle","far fa-question-circle","fal fa-question-circle","question-square","far fa-question-square","fal fa-question-square","quidditch","far fa-quidditch","fal fa-quidditch","fab fa-quinscape","fab fa-quora","quote-left","far fa-quote-left","fal fa-quote-left","quote-right","far fa-quote-right","fal fa-quote-right","racquet","far fa-racquet","fal fa-racquet","ramp-loading","far fa-ramp-loading","fal fa-ramp-loading","random","far fa-random","fal fa-random","fab fa-ravelry","fab fa-react","fab fa-readme","fab fa-rebel","rectangle-landscape","far fa-rectangle-landscape","fal fa-rectangle-landscape","rectangle-portrait","far fa-rectangle-portrait","fal fa-rectangle-portrait","rectangle-wide","far fa-rectangle-wide","fal fa-rectangle-wide","recycle","far fa-recycle","fal fa-recycle","fab fa-red-river","fab fa-reddit","fab fa-reddit-alien","fab fa-reddit-square","redo","far fa-redo","fal fa-redo","redo-alt","far fa-redo-alt","fal fa-redo-alt","registered","far fa-registered","fal fa-registered","fab fa-rendact","fab fa-renren","repeat","far fa-repeat","fal fa-repeat","repeat-1","far fa-repeat-1","fal fa-repeat-1","repeat-1-alt","far fa-repeat-1-alt","fal fa-repeat-1-alt","repeat-alt","far fa-repeat-alt","fal fa-repeat-alt","reply","far fa-reply","fal fa-reply","reply-all","far fa-reply-all","fal fa-reply-all","fab fa-replyd","fab fa-resolving","retweet","far fa-retweet","fal fa-retweet","retweet-alt","far fa-retweet-alt","fal fa-retweet-alt","ribbon","far fa-ribbon","fal fa-ribbon","road","far fa-road","fal fa-road","rocket","far fa-rocket","fal fa-rocket","fab fa-rocketchat","fab fa-rockrms","route","far fa-route","fal fa-route","rss","far fa-rss","fal fa-rss","rss-square","far fa-rss-square","fal fa-rss-square","ruble-sign","far fa-ruble-sign","fal fa-ruble-sign","rupee-sign","far fa-rupee-sign","fal fa-rupee-sign","fab fa-safari","fab fa-sass","save","far fa-save","fal fa-save","scanner","far fa-scanner","fal fa-scanner","scanner-keyboard","far fa-scanner-keyboard","fal fa-scanner-keyboard","scanner-touchscreen","far fa-scanner-touchscreen","fal fa-scanner-touchscreen","fab fa-schlix","fab fa-scribd","scrubber","far fa-scrubber","fal fa-scrubber","search","far fa-search","fal fa-search","search-minus","far fa-search-minus","fal fa-search-minus","search-plus","far fa-search-plus","fal fa-search-plus","fab fa-searchengin","seedling","far fa-seedling","fal fa-seedling","fab fa-sellcast","fab fa-sellsy","server","far fa-server","fal fa-server","fab fa-servicestack","share","far fa-share","fal fa-share","share-all","far fa-share-all","fal fa-share-all","share-alt","far fa-share-alt","fal fa-share-alt","share-alt-square","far fa-share-alt-square","fal fa-share-alt-square","share-square","far fa-share-square","fal fa-share-square","shekel-sign","far fa-shekel-sign","fal fa-shekel-sign","shield","far fa-shield","fal fa-shield","shield-alt","far fa-shield-alt","fal fa-shield-alt","shield-check","far fa-shield-check","fal fa-shield-check","ship","far fa-ship","fal fa-ship","shipping-fast","far fa-shipping-fast","fal fa-shipping-fast","shipping-timed","far fa-shipping-timed","fal fa-shipping-timed","fab fa-shirtsinbulk","shopping-bag","far fa-shopping-bag","fal fa-shopping-bag","shopping-basket","far fa-shopping-basket","fal fa-shopping-basket","shopping-cart","far fa-shopping-cart","fal fa-shopping-cart","shower","far fa-shower","fal fa-shower","shuttlecock","far fa-shuttlecock","fal fa-shuttlecock","sign","far fa-sign","fal fa-sign","sign-in","far fa-sign-in","fal fa-sign-in","sign-in-alt","far fa-sign-in-alt","fal fa-sign-in-alt","sign-language","far fa-sign-language","fal fa-sign-language","sign-out","far fa-sign-out","fal fa-sign-out","sign-out-alt","far fa-sign-out-alt","fal fa-sign-out-alt","signal","far fa-signal","fal fa-signal","fab fa-simplybuilt","fab fa-sistrix","sitemap","far fa-sitemap","fal fa-sitemap","fab fa-skyatlas","fab fa-skype","fab fa-slack","fab fa-slack-hash","sliders-h","far fa-sliders-h","fal fa-sliders-h","sliders-h-square","far fa-sliders-h-square","fal fa-sliders-h-square","sliders-v","far fa-sliders-v","fal fa-sliders-v","sliders-v-square","far fa-sliders-v-square","fal fa-sliders-v-square","fab fa-slideshare","smile","far fa-smile","fal fa-smile","smile-plus","far fa-smile-plus","fal fa-smile-plus","smoking","far fa-smoking","fal fa-smoking","fab fa-snapchat","fab fa-snapchat-ghost","fab fa-snapchat-square","snowflake","far fa-snowflake","fal fa-snowflake","sort","far fa-sort","fal fa-sort","sort-alpha-down","far fa-sort-alpha-down","fal fa-sort-alpha-down","sort-alpha-up","far fa-sort-alpha-up","fal fa-sort-alpha-up","sort-amount-down","far fa-sort-amount-down","fal fa-sort-amount-down","sort-amount-up","far fa-sort-amount-up","fal fa-sort-amount-up","sort-down","far fa-sort-down","fal fa-sort-down","sort-numeric-down","far fa-sort-numeric-down","fal fa-sort-numeric-down","sort-numeric-up","far fa-sort-numeric-up","fal fa-sort-numeric-up","sort-up","far fa-sort-up","fal fa-sort-up","fab fa-soundcloud","space-shuttle","far fa-space-shuttle","fal fa-space-shuttle","spade","far fa-spade","fal fa-spade","fab fa-speakap","spinner","far fa-spinner","fal fa-spinner","spinner-third","far fa-spinner-third","fal fa-spinner-third","fab fa-spotify","square","far fa-square","fal fa-square","square-full","far fa-square-full","fal fa-square-full","fab fa-stack-exchange","fab fa-stack-overflow","star","far fa-star","fal fa-star","star-exclamation","far fa-star-exclamation","fal fa-star-exclamation","star-half","far fa-star-half","fal fa-star-half","fab fa-staylinked","fab fa-steam","fab fa-steam-square","fab fa-steam-symbol","step-backward","far fa-step-backward","fal fa-step-backward","step-forward","far fa-step-forward","fal fa-step-forward","stethoscope","far fa-stethoscope","fal fa-stethoscope","fab fa-sticker-mule","sticky-note","far fa-sticky-note","fal fa-sticky-note","stop","far fa-stop","fal fa-stop","stop-circle","far fa-stop-circle","fal fa-stop-circle","stopwatch","far fa-stopwatch","fal fa-stopwatch","fab fa-strava","street-view","far fa-street-view","fal fa-street-view","strikethrough","far fa-strikethrough","fal fa-strikethrough","fab fa-stripe","fab fa-stripe-s","fab fa-studiovinari","fab fa-stumbleupon","fab fa-stumbleupon-circle","subscript","far fa-subscript","fal fa-subscript","subway","far fa-subway","fal fa-subway","suitcase","far fa-suitcase","fal fa-suitcase","sun","far fa-sun","fal fa-sun","fab fa-superpowers","superscript","far fa-superscript","fal fa-superscript","fab fa-supple","sync","far fa-sync","fal fa-sync","sync-alt","far fa-sync-alt","fal fa-sync-alt","syringe","far fa-syringe","fal fa-syringe","table","far fa-table","fal fa-table","table-tennis","far fa-table-tennis","fal fa-table-tennis","tablet","far fa-tablet","fal fa-tablet","tablet-alt","far fa-tablet-alt","fal fa-tablet-alt","tablet-android","far fa-tablet-android","fal fa-tablet-android","tablet-android-alt","far fa-tablet-android-alt","fal fa-tablet-android-alt","tablet-rugged","far fa-tablet-rugged","fal fa-tablet-rugged","tablets","far fa-tablets","fal fa-tablets","tachometer","far fa-tachometer","fal fa-tachometer","tachometer-alt","far fa-tachometer-alt","fal fa-tachometer-alt","tag","far fa-tag","fal fa-tag","tags","far fa-tags","fal fa-tags","tape","far fa-tape","fal fa-tape","tasks","far fa-tasks","fal fa-tasks","taxi","far fa-taxi","fal fa-taxi","fab fa-telegram","fab fa-telegram-plane","fab fa-tencent-weibo","tennis-ball","far fa-tennis-ball","fal fa-tennis-ball","terminal","far fa-terminal","fal fa-terminal","text-height","far fa-text-height","fal fa-text-height","text-width","far fa-text-width","fal fa-text-width","th","far fa-th","fal fa-th","th-large","far fa-th-large","fal fa-th-large","th-list","far fa-th-list","fal fa-th-list","fab fa-themeisle","thermometer","far fa-thermometer","fal fa-thermometer","thermometer-empty","far fa-thermometer-empty","fal fa-thermometer-empty","thermometer-full","far fa-thermometer-full","fal fa-thermometer-full","thermometer-half","far fa-thermometer-half","fal fa-thermometer-half","thermometer-quarter","far fa-thermometer-quarter","fal fa-thermometer-quarter","thermometer-three-quarters","far fa-thermometer-three-quarters","fal fa-thermometer-three-quarters","thumbs-down","far fa-thumbs-down","fal fa-thumbs-down","thumbs-up","far fa-thumbs-up","fal fa-thumbs-up","thumbtack","far fa-thumbtack","fal fa-thumbtack","ticket","far fa-ticket","fal fa-ticket","ticket-alt","far fa-ticket-alt","fal fa-ticket-alt","times","far fa-times","fal fa-times","times-circle","far fa-times-circle","fal fa-times-circle","times-hexagon","far fa-times-hexagon","fal fa-times-hexagon","times-octagon","far fa-times-octagon","fal fa-times-octagon","times-square","far fa-times-square","fal fa-times-square","tint","far fa-tint","fal fa-tint","toggle-off","far fa-toggle-off","fal fa-toggle-off","toggle-on","far fa-toggle-on","fal fa-toggle-on","trademark","far fa-trademark","fal fa-trademark","train","far fa-train","fal fa-train","transgender","far fa-transgender","fal fa-transgender","transgender-alt","far fa-transgender-alt","fal fa-transgender-alt","trash","far fa-trash","fal fa-trash","trash-alt","far fa-trash-alt","fal fa-trash-alt","tree","far fa-tree","fal fa-tree","tree-alt","far fa-tree-alt","fal fa-tree-alt","fab fa-trello","triangle","far fa-triangle","fal fa-triangle","fab fa-tripadvisor","trophy","far fa-trophy","fal fa-trophy","trophy-alt","far fa-trophy-alt","fal fa-trophy-alt","truck","far fa-truck","fal fa-truck","truck-container","far fa-truck-container","fal fa-truck-container","truck-couch","far fa-truck-couch","fal fa-truck-couch","truck-loading","far fa-truck-loading","fal fa-truck-loading","truck-moving","far fa-truck-moving","fal fa-truck-moving","truck-ramp","far fa-truck-ramp","fal fa-truck-ramp","tty","far fa-tty","fal fa-tty","fab fa-tumblr","fab fa-tumblr-square","tv","far fa-tv","fal fa-tv","tv-retro","far fa-tv-retro","fal fa-tv-retro","fab fa-twitch","fab fa-twitter","fab fa-twitter-square","fab fa-typo3","fab fa-uber","fab fa-uikit","umbrella","far fa-umbrella","fal fa-umbrella","underline","far fa-underline","fal fa-underline","undo","far fa-undo","fal fa-undo","undo-alt","far fa-undo-alt","fal fa-undo-alt","fab fa-uniregistry","universal-access","far fa-universal-access","fal fa-universal-access","university","far fa-university","fal fa-university","unlink","far fa-unlink","fal fa-unlink","unlock","far fa-unlock","fal fa-unlock","unlock-alt","far fa-unlock-alt","fal fa-unlock-alt","fab fa-untappd","upload","far fa-upload","fal fa-upload","fab fa-usb","usd-circle","far fa-usd-circle","fal fa-usd-circle","usd-square","far fa-usd-square","fal fa-usd-square","user","far fa-user","fal fa-user","user-alt","far fa-user-alt","fal fa-user-alt","user-circle","far fa-user-circle","fal fa-user-circle","user-md","far fa-user-md","fal fa-user-md","user-plus","far fa-user-plus","fal fa-user-plus","user-secret","far fa-user-secret","fal fa-user-secret","user-times","far fa-user-times","fal fa-user-times","users","far fa-users","fal fa-users","fab fa-ussunnah","utensil-fork","far fa-utensil-fork","fal fa-utensil-fork","utensil-knife","far fa-utensil-knife","fal fa-utensil-knife","utensil-spoon","far fa-utensil-spoon","fal fa-utensil-spoon","utensils","far fa-utensils","fal fa-utensils","utensils-alt","far fa-utensils-alt","fal fa-utensils-alt","fab fa-vaadin","venus","far fa-venus","fal fa-venus","venus-double","far fa-venus-double","fal fa-venus-double","venus-mars","far fa-venus-mars","fal fa-venus-mars","fab fa-viacoin","fab fa-viadeo","fab fa-viadeo-square","vial","far fa-vial","fal fa-vial","vials","far fa-vials","fal fa-vials","fab fa-viber","video","far fa-video","fal fa-video","video-plus","far fa-video-plus","fal fa-video-plus","video-slash","far fa-video-slash","fal fa-video-slash","fab fa-vimeo","fab fa-vimeo-square","fab fa-vimeo-v","fab fa-vine","fab fa-vk","fab fa-vnv","volleyball-ball","far fa-volleyball-ball","fal fa-volleyball-ball","volume-down","far fa-volume-down","fal fa-volume-down","volume-mute","far fa-volume-mute","fal fa-volume-mute","volume-off","far fa-volume-off","fal fa-volume-off","volume-up","far fa-volume-up","fal fa-volume-up","fab fa-vuejs","warehouse","far fa-warehouse","fal fa-warehouse","warehouse-alt","far fa-warehouse-alt","fal fa-warehouse-alt","watch","far fa-watch","fal fa-watch","fab fa-weibo","weight","far fa-weight","fal fa-weight","fab fa-weixin","fab fa-whatsapp","fab fa-whatsapp-square","wheelchair","far fa-wheelchair","fal fa-wheelchair","whistle","far fa-whistle","fal fa-whistle","fab fa-whmcs","wifi","far fa-wifi","fal fa-wifi","fab fa-wikipedia-w","window","far fa-window","fal fa-window","window-alt","far fa-window-alt","fal fa-window-alt","window-close","far fa-window-close","fal fa-window-close","window-maximize","far fa-window-maximize","fal fa-window-maximize","window-minimize","far fa-window-minimize","fal fa-window-minimize","window-restore","far fa-window-restore","fal fa-window-restore","fab fa-windows","wine-glass","far fa-wine-glass","fal fa-wine-glass","won-sign","far fa-won-sign","fal fa-won-sign","fab fa-wordpress","fab fa-wordpress-simple","fab fa-wpbeginner","fab fa-wpexplorer","fab fa-wpforms","wrench","far fa-wrench","fal fa-wrench","x-ray","far fa-x-ray","fal fa-x-ray","fab fa-xbox","fab fa-xing","fab fa-xing-square","fab fa-y-combinator","fab fa-yahoo","fab fa-yandex","fab fa-yandex-international","fab fa-yelp","yen-sign","far fa-yen-sign","fal fa-yen-sign","fab fa-yoast","fab fa-youtube","fab fa-youtube-square"]';
		
		$version = EWPHelper::getGeneralSetting("ewp_font_awesome_version");
				
		if($version == "fa5")
			return($jsonIconsFA5);
		
		
		return($jsonIcons);
	}
	
	
	/**
	 * get brand icons
	 */
	private static function fa_getArrBrandIcons(){
		
		if(!empty(self::$brandIcons))
			return(self::$brandIcons);
			
		$jsonIcons = '["500px","accessible-icon","accusoft","adn","adversal","affiliatetheme","algolia","amazon-pay","amazon","amilia","android","angellist","angrycreative","angular","app-store-ios","app-store","apper","apple-pay","apple","asymmetrik","audible","autoprefixer","avianex","aviato","aws","bandcamp","behance-square","behance","bimobject","bitbucket","bitcoin","bity","black-tie","blackberry","blogger-b","blogger","bluetooth-b","bluetooth","btc","buromobelexperte","buysellads","cc-amazon-pay","cc-amex","cc-apple-pay","cc-diners-club","cc-discover","cc-jcb","cc-mastercard","cc-paypal","cc-stripe","cc-visa","centercode","chrome","cloudscale","cloudsmith","cloudversify","codepen","codiepie","connectdevelop","contao","cpanel","creative-commons","css3-alt","css3","cuttlefish","d-and-d","dashcube","delicious","deploydog","deskpro","deviantart","digg","digital-ocean","discord","discourse","dochub","docker","draft2digital","dribbble-square","dribbble","dropbox","drupal","dyalog","earlybirds","edge","elementor","ember","empire","envira","erlang","ethereum","etsy","expeditedssl","facebook-f","facebook-messenger","facebook-square","facebook","firefox","first-order","firstdraft","flickr","flipboard","fly","font-awesome-alt","font-awesome-flag","font-awesome","fonticons-fi","fonticons","fort-awesome-alt","fort-awesome","forumbee","foursquare","free-code-camp","freebsd","get-pocket","gg-circle","gg","git-square","git","github-alt","github-square","github","gitkraken","gitlab","gitter","glide-g","glide","gofore","goodreads-g","goodreads","google-drive","google-play","google-plus-g","google-plus-square","google-plus","google-wallet","google","gratipay","grav","gripfire","grunt","gulp","hacker-news-square","hacker-news","hips","hire-a-helper","hooli","hotjar","houzz","html5","hubspot","imdb","instagram","internet-explorer","ioxhost","itunes-note","itunes","jenkins","joget","joomla","js-square","js","jsfiddle","keycdn","kickstarter-k","kickstarter","korvue","laravel","lastfm-square","lastfm","leanpub","less","line","linkedin-in","linkedin","linode","linux","lyft","magento","maxcdn","medapps","medium-m","medium","medrt","meetup","microsoft","mix","mixcloud","mizuni","modx","monero","napster","nintendo-switch","node-js","node","npm","ns8","nutritionix","odnoklassniki-square","odnoklassniki","opencart","openid","opera","optin-monster","osi","page4","pagelines","palfed","patreon","paypal","periscope","phabricator","phoenix-framework","php","pied-piper-alt","pied-piper-pp","pied-piper","pinterest-p","pinterest-square","pinterest","playstation","product-hunt","pushed","python","qq","quinscape","quora","ravelry","react","readme","rebel","red-river","reddit-alien","reddit-square","reddit","rendact","renren","replyd","resolving","rocketchat","rockrms","safari","sass","schlix","scribd","searchengin","sellcast","sellsy","servicestack","shirtsinbulk","simplybuilt","sistrix","skyatlas","skype","slack-hash","slack","slideshare","snapchat-ghost","snapchat-square","snapchat","soundcloud","speakap","spotify","stack-exchange","stack-overflow","staylinked","steam-square","steam-symbol","steam","sticker-mule","strava","stripe-s","stripe","studiovinari","stumbleupon-circle","stumbleupon","superpowers","supple","telegram-plane","telegram","tencent-weibo","themeisle","trello","tripadvisor","tumblr-square","tumblr","twitch","twitter-square","twitter","typo3","uber","uikit","uniregistry","untappd","usb","ussunnah","vaadin","viacoin","viadeo-square","viadeo","viber","vimeo-square","vimeo-v","vimeo","vine","vk","vnv","vuejs","weibo","weixin","whatsapp-square","whatsapp","whmcs","wikipedia-w","windows","wordpress-simple","wordpress","wpbeginner","wpexplorer","wpforms","xbox","xing-square","xing","y-combinator","yahoo","yandex-international","yandex","yelp","yoast","youtube-square","youtube"]';
		
		$arrIcons = UniteFunctionsUC::jsonDecode($jsonIcons);
		self::$brandIcons = UniteFunctionsUC::arrayToAssoc($arrIcons);
		
		return(self::$brandIcons);
	}
	
	
	/**
	 * convert icon to fa5
	 */
	public static function fa_convertIconTo5($icon){
		
		$iconName = str_replace("fa fa-", "", $icon);
		
		if(empty($iconName))
			return($icon);
		
		//change the -o suffix to regular type
		if(strpos($iconName, "-o") !== false){
			$iconName = str_replace("-o", "", $iconName);
			$icon = "far fa-".$iconName;
			
			return($icon);
		}
		
		
		//rename changed names
		switch($iconName){
			case "picture":
				return("fa fa-image");
			break;
			case "close":
				return("fa fa-times");
			break;
		}
		
		//return the branded
		$arrBrandIcons = self::fa_getArrBrandIcons();
		
		if(isset($arrBrandIcons[$iconName])){
			$icon = "fab fa-".$iconName;
			return($icon);
		}
		
		return($icon);
	}
	
	
	/**
	 * convert icon to fa5
	 */
	public static function fa_convertIconTo4($icon){
		
		if(empty($icon))
			return("");
		
		//check if already 4 format
		if(strpos($icon, "fa fa-") !== false)
			return($icon);
		
		//no fal
		$icon = str_replace("fal fa-", "fa fa-", $icon);
			
		//no far
		$icon = str_replace("far fa-", "fa fa-", $icon);
		
		//in far - add the -o at the end
		/*
		if(strpos($icon, "far fa-") === 0){
			$icon = str_replace("far fa-", "fa fa-", $icon);
			$icon .= "-o";
		}
		*/

		//special icons
		$icon = str_replace("fa-close", "fa-times", $icon);
		$icon = str_replace("fa-image", "fa-picture-o", $icon);
		
		//branded icons
		$icon = str_replace("fab fa-", "fa fa-", $icon);
		
		return($icon);
	}
	
	
	/**
	 * convert icons to current type
	 */
	public static function fa_convertIcon($icon){
		
		$faVersion = EWPHelper::getGeneralSetting("ewp_font_awesome_version");
		
		if($faVersion == "fa5"){
			$icon = self::fa_convertIconTo5($icon);
			return($icon);
		}
		
		//fa4
		$icon = self::fa_convertIconTo4($icon);
		
		return($icon);
	}
	
	
	/**
	 * convert icons array to 4 only if setting selected
	 */
	public static function fa_maybeConvertArrIconsTo4($arrIcons){
		
		//convert to 4
		$faVersion = EWPHelper::getGeneralSetting("ewp_font_awesome_version");
		
		if($faVersion == "fa4")
			foreach($arrIcons as $key=>$icon)
				$arrIcons[$key] = UniteFontManagerUC::fa_convertIconTo4($icon);
		
		return($arrIcons);
		
	}
		
	/**
	 * get some icon from array 
	 * throw error if not exists
	 */
	public static function getIcon($name, $arrIcons){
		
		$icon = UniteFunctionsUC::getVal($arrIcons, $name);
		
		if(empty($icon)){
			$strIcons = print_r($arrIcons, true);
			UniteFunctionsUC::throwError("Icon $name not found. there are the icons: $strIcons");
		}
		
		return($icon);
		
	}
	
	/**
	 * fetch icons
	 */
	public function fetchIcons(){
		
		$this->fetchIconsFromCss();
	
	}
	
}