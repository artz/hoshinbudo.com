// JavaScript Document


// Promotional Widget
(function($){

var defaultOptions = {
		ui: {
			headings: "hgroup",
			panels: "> div",
			photos: "img"
		},
		names: {
			"class": {
				activeTab: "active-tab",
				activePanel: "active-panel",
				tab: "ul > li"
			},
			data: {
				tabIndex: "tabindex."
			},
			event: {
				tabChange: "tabchange."
			}
		},
		namespace: "promowidget",
		initialTab: 1,
		slideShow: true,
		slideDelay: 3
	};

$.promoWidget = function( customOptions ) {
	
	if ( customOptions.elem ) {
		
		var options = $.extend( true, {}, defaultOptions, customOptions ),
		
			elem = options.elem,
			$promoWidget = $(elem),
			
			namespace = options.namespace,
			ui = options.ui,
			names = options.names,
			
			classNames = names[ "class" ],
			dataNames = names.data,
			eventNames = names.event,
			
			$tabs,
			$panels = ui.$panels = $promoWidget.find( ui.panels ),
			$photos = ui.$photos = $promoWidget.find( ui.photos ),
			
			currentZIndex = 2,
			
			slideInterval,
			
			core = {
				init: function( options ){
					
					var $controls = $("<ul></ul>");
					
					// Create the pointer.
					// http://www.dinnermint.org/blog/css/creating-triangles-in-css/
					ui.$arrow = $("<b class=\"pointer\"></b>").appendTo( $promoWidget );
					
					// We stick the photos into here.
					$photos.each(function(i){
						var photo = this;
						$controls.append( $("<li><img src=\"" + photo.src + "\" alt=\"" + photo.alt + "\" /></li>")
							.data( dataNames.tabIndex + namespace, i ) );
					});
					
					$controls.hide();
					$promoWidget.append( $controls );
					$controls.fadeIn(200);
					
					$promoWidget.delegate( classNames.tab, "mousedown." + namespace, function(){
						clearInterval( slideInterval );
						core.selectTab.call( this );
					});
					
					$tabs = ui.$tabs = $controls.find("li");
					$tabs.css("opacity", .7);
					
					$tabs.eq( options.initialTab - 1 ).trigger("mousedown");
					
					function nextSlide(){
						var activeIndex = ui.$activeTab.data( dataNames.tabIndex + namespace ),
							nextIndex = activeIndex === $tabs.length - 1 ? 0 : activeIndex + 1;
						core.selectTab.call( $tabs.eq( nextIndex )[0] );
					}
					
					if ( options.slideShow ) {
						slideInterval = setInterval( nextSlide, options.slideDelay * 1000 );
					}
					
				},
				selectTab: function(){
					var tabElem = this,
						$tabElem = $(tabElem),
						tabIndex = $tabElem.data( dataNames.tabIndex + namespace ),
						
						$activeTab,
						$activePanel,
						
						$oldActiveTab,
						$oldActivePanel,
						
						$arrow = ui.$arrow,
						$photo = $photos.eq( tabIndex ),
						
						$dataSrc = $photo.attr("data-src");
						
					$oldActiveTab = ui.$activeTab,
					$oldActivePanel = ui.$activePanel;
					
					// Only do this once.
					if ( $dataSrc ) {
						$photo.attr( "src", $dataSrc );
						$photo.removeAttr("data-src");
					}
					
					if ( $oldActiveTab && tabElem === $oldActiveTab[0] ) return;
					
					$activeTab = ui.$activeTab = $tabElem;
					$activePanel = ui.$activePanel = $panels.eq( tabIndex );
					
//					console.log($activeTab.offset().left);
					
					if ( $oldActiveTab ) {
						$arrow.animate({
							left: $activeTab.position().left
						});
						
						$oldActiveTab.animate({"opacity": .7});
						$activeTab.animate({"opacity": 1});
						
						// If we're clicking the currently selected tab, return.
						$oldActiveTab.removeClass( classNames.activeTab );
				//		$oldActivePanel.removeClass( classNames.activePanel );
												
						$activeTab.addClass( classNames.activeTab );
				//		$activePanel.addClass( classNames.activePanel );
						
						$activePanel.css({
							zIndex: currentZIndex++
						}).stop().fadeOut(0).fadeIn();
						
						// Trigger the tabchange event, for the curious.
						$tabElem.trigger( eventNames.tabChange + namespace, [ $promoWidget, options ] );
		
					}
					else {
						$activeTab.css({"opacity": 1});
					}
				}
			};
			
		  // save options on element (expose options externally)
		  $promoWidget.data( "options." + namespace, options );
		  
		  // process core function overrides
		  $.extend( core, options.core );
		
		core.init.call( $promoWidget, options );
		
	} else {
		$.extend( defaultOptions, customOptions );
	}
};

$.fn.promoWidget = function( customOptions ){
	customOptions = customOptions || {};
	return this.each(function(){
		customOptions.elem = this;
		$.promoWidget( customOptions );
	});
};
	
})(jQuery);