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
			class: {
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
		initialTab: 1
	};

$.promoWidget = function( customOptions ) {
	
	if ( customOptions.elem ) {
		
		var options = $.extend( true, {}, defaultOptions, customOptions ),
		
			elem = options.elem,
			$promoWidget = $(elem),
			
			namespace = options.namespace,
			ui = options.ui,
			names = options.names,
			
			classNames = names.class,
			dataNames = names.data,
			eventNames = names.event,
			
			$tabs,
			$panels = ui.$panels = $promoWidget.find( ui.panels ),
			$photos = ui.$photos = $promoWidget.find( ui.photos ),
			
			core = {
				init: function( options ){
					
					var $controls = $("<ul></ul>");
					
					// We stick the photos into here.
					$photos.each(function(i){
						var photo = this;
						$controls.append( $("<li><img src=\"" + photo.src + "\" alt=\"" + photo.alt + "\" /></li>")
							.data( dataNames.tabIndex + namespace, i ) );
					});
					
					$promoWidget.append( $controls );
					
					$promoWidget.delegate( classNames.tab, "mousedown." + namespace, function(){
						core.selectTab.call( this );
					});
					
					$tabs = ui.$tabs = $controls.find("li");
					
					$tabs.eq( options.initialTab - 1 ).trigger("mousedown");
				},
				selectTab: function(){
					var tabElem = this,
						$tabElem = $(tabElem),
						tabIndex = $tabElem.data( dataNames.tabIndex + namespace ),
						
						$activeTab,
						$activePanel,
						
						$oldActiveTab,
						$oldActivePanel;

					$oldActiveTab = ui.$activeTab,
					$oldActivePanel = ui.$activePanel;
									
					if ( $oldActiveTab ) {
						
						// If we're clicking the currently selected tab, return.
						if ( tabElem == $oldActiveTab[0] ) return;
						
						// Remove the current active tab's status.
						$activeTab.removeClass( classNames.activeTab );
						
//						$activePanel.removeClass( classNames.activePanel );
						// If the active tab hasn't faded in yet, make sure it is.
						$activePanel.css({
								position: "absolute",
								zIndex: 1,
								opacity: 1
							});
					}
					
					// Set the new active tab and panel.
					$activeTab = $tabElem;
					$activePanel = $panels.eq( tabIndex );
					
					// Save the new active tab and add the class.
					ui.$activeTab = $activeTab.addClass( classNames.activeTab );
					
					// ui.$activePanel = $activePanel.addClass( classNames.activePanel );
					
					ui.$activePanel = $activePanel.css({
							position: "relative",
							zIndex: 2
						}).fadeIn(1000, function(){
							$oldActivePanel && $oldActivePanel.fadeOut(0).css({
								position: "absolute",
								zIndex: 0
							});
						});
					// Trigger the tabchange event, for the curious.
					$tabElem.trigger( eventNames.tabChange + namespace, [ $promoWidget, options ] );
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