// usage: log('inside coolFunc',this,arguments);
// paulirish.com/2009/log-a-lightweight-wrapper-for-consolelog/
window.log = function(){
  log.history = log.history || [];   // store logs to an array for reference
  log.history.push(arguments);
  if(this.console){
    console.log( Array.prototype.slice.call(arguments) );
  }
};

/*
 * Easync
 * http://www.artzstudio.com/easync/
 *
 * Developed by: 
 * - Dave Artz http://www.artzstudio.com/
 *
 * Copyright (c) 2010
 * Not yet licensed cuz I lack free time.
 *
 * Easync is a script inspired by Steve Souders' ControlJS
 * with underpinnings of my other library, A.getJS.
 * The goal of Easync is to be a simple way to do 
 * asynchronous script loading without thinking too hard.
 * 
 * @author        Dave Artz
 * @copyright     (c) 2010 Dave Artz
 *
 * Design Decisions
 * - Defer goes to window.load.
 * - Protects against the same script being cached twice.
 * - Protects against the same script being executed twice.
 *
 */
(function( window, document, Easync ) {

var scripts = document.getElementsByTagName("script"),
	scriptsLength = 0,
	
	bodyPollTimeout = 250,
	scriptPollTimeout = 500,
	
	scriptPoller = setInterval( pollScripts, scriptPollTimeout ),
	
	strReadyState = "readyState",
	strOnReadyStateChange = "onreadystatechange",
	strOnLoad = "onload",
	strOnError = "onerror",
	strImage = "img",
	strComplete = "complete",
	strCache = "cache",
	strGetAttribute = "getAttribute",
	strDataSrc = "data-src",
	
	scriptLoading = {},
	scriptCached = {},
	scriptExecuted = {},
	
	execQueue = [],
	deferQueue = [],
	
	isWindowLoaded = document[ strReadyState ] == strComplete,

	bodyElem,
	
	// If Opera or IE, we use an image to cache 
	// the script; everyone else gets an object.
	isIE = /*@cc_on!@*/0,
	cacheElem = window.opera || isIE ? strImage : "object",
	
	// Check if a string has a non-whitespace character in it
	rnotwhite = /\S/;

function globalEval( data ){
	if ( data && rnotwhite.test(data) ) {
		// Inspired by code by Andrea Giammarchi
		// http://webreflection.blogspot.com/2007/08/global-scope-evaluation-and-dom.html
		var script = document.createElement("script");

		// script.type = "text/javascript";

		if ( isIE ) {
			script.text = data;
		} else {
			script.appendChild( document.createTextNode( data ) );
		}

		// Use insertBefore instead of appendChild to circumvent an IE6 bug.
		// This arises when a base node is used (#2709).
		bodyElem.appendChild( script );
	}
}

function getObject( elem, url, callback ) {

	var	obj = document.createElement( elem ),
		done = 0,
		isImage = elem == strImage;
	
	// Attach handlers for all browsers
	obj[ strOnLoad ] = obj[ strOnError ] = obj[ strOnReadyStateChange ] = function(){

		if ( !done && ( isImage || !obj[ strReadyState ] ||
				obj[ strReadyState ] == "loaded" || obj[ strReadyState ] == strComplete) ) {

			// Tell global scripts object this script has loaded.
			// Set scriptDone to prevent this function from being called twice.
			done = 1;

			callback && callback( url );
			
			// Handle memory leak in IE
			obj[ strOnLoad ] = obj[ strOnReadyStateChange ] = null;
			
			// This causes a spinny after window.load fires.
		//	bodyElem.removeChild( obj );
		}
	};
	
	// Source must be set after the events 
	// are attached for images to work.
	obj.src = obj.data = url;

	if ( !isImage ) {
		obj.width = obj.height = 0;
		bodyElem.appendChild( obj );
	}
}

// This function aggressively downloads any scripts 
// in the exececution queue.
function cacheScript( script ) {

	// We support using the normal "src" attribute or 
	// the data-src attribute for people concerned about 
	// Firefox downloading things immediately when a 
	// deferred fetch is intended.
	var url = script.src || script[ strGetAttribute ]( strDataSrc );
	
	// If we have an external script we can fetch,
	// otherwise it's an inline script we can ignore.
	// We use some basic protection to prevent scripts
	// from being requested twice.
	if ( url && !scriptLoading[ url ] ) {
		// log( "Caching script: " + url );
		scriptLoading[ url ] = 1;
		getObject( cacheElem, url, function( url ){
			// log( "Script cached: " + url );
			// Remember this script is cached.
			scriptCached[ url ] = 1;
			// Trigger script execution.
			executeScripts();
		});
	}

}

function executeScripts() {
	
	var	script = execQueue[0],
		url;
	
	function shiftScripts(){
		// log("Shifting script: " + ( script.src || ( script[ strGetAttribute ] && script[ strGetAttribute ]( strDataSrc ) ) || "Inline Script" ) );
		execQueue.shift();
		// One down, let's do this again shall we?
		executeScripts();
	}
	
	if ( script ) {
		// If we are not simply caching (preload), we need to execute it.
		if ( script.type != strCache ) {
			// We have a url, let's see if we can fetch it.
			if ( url = script.src || ( script[ strGetAttribute ] && script[ strGetAttribute ]( strDataSrc ) ) ) {
				// If this URL has been cached, we can execute it.
				if ( scriptCached[ url ] && !scriptExecuted[ url ]) {
					// log("Executing script: " + url + "");
					scriptExecuted[ url ] = 1;
					getObject( "script", url, shiftScripts );
				// Come back again latorz.
				} else {
					return;
				}
			// We have an inline script, eval it.	
			} else if ( script.text ) {
			// log("Executing script: Inline Script");
				globalEval( script.text );
				shiftScripts();
			// Artz: See if anyone wants this. See Easync Option 1.	
			// We have a function delivered via Easync() API.
			}/* else if ( script.f ) {
				script.f();
			}*/
		} else {
			shiftScripts();
		}
	}
}

function pollScripts() {
	
	// Only check if we have a body element.
	// Don't rest until we do!
	if ( bodyElem || (bodyElem = document.body) ) {
		
		// log("<i style=\"color:#666\">pollScripts(): Checking for new scripts (" + document.readyState +")</i>");
		var newLength = scripts.length;
		
		if ( newLength != scriptsLength ) {
			
			for ( 	var i = scriptsLength,
					l = newLength,
					script,
					type; i < l; i++ ) {
							
				script = scripts[i];
				type = script.type;
		
				if ( type == "async" || type == strCache ) {
					
					// Add them to the deferred execution queue.
					if ( script.defer ) {
						
						deferQueue.push( script );
						
					// Add them to the immediate execution queue.
					} else {
						execQueue.push( script );
						cacheScript ( script );	
					}
				}
			}	
			// Update our understanding of the number of scripts in the document.
			scriptsLength = newLength;
		}
		
		// If the window has loaded, merge our deferred queue now.
		if ( isWindowLoaded ) {
			// log("Clearing deferred queue.");
			// Load any deferred scripts into the execQueue.
			execQueue = execQueue.concat( deferQueue );
			// Run through our deferred queue and cache scripts now.
			for ( i = 0, l = deferQueue.length; i < l; i++ ) {
				cacheScript( deferQueue[i] );
			}
		}
		
		// Execute any scripts that happen to be waiting.
		executeScripts();
		
	} else {
		// log("No body element yet.");
		setTimeout( pollScripts, bodyPollTimeout );
	}
}

function windowLoad(){
	
	// log("Window has loaded.");
	
	// Window has loaded; we no longer need to poll.
	clearInterval( scriptPoller ); 
	
	// Remember we are finished collecting scripts.
	isWindowLoaded = 1;
	
	// Check one last time for good luck.
	pollScripts();
	
	// log("Stopped checking for scripts.");
	
//	EasyncTest();
	
}

// log("Started checking for scripts.");

if ( isWindowLoaded ) {
	windowLoad();
} else {
	window[ strOnLoad ] = windowLoad;
	pollScripts();
}

// If there were any inline scripts preceeding
// external scripts, this will kick them off.


/*
function EasyncTest(){
	var myColor = "blue";
	A.log("<b style=\"color:red\">Testing Easync().");
	Easync(
		"http://www.artzstudio.com/files/pejs/test.js?num=8", 
		function(){ A.log("<i style=\"color:" + myColor + "\">Papas fritas! Papas fritas! Papas fritas!</i>"); }, 
		"http://www.artzstudio.com/files/pejs/test.js?num=9"
	);	
	Easync("http://www.artzstudio.com/files/pejs/test.js?num=10");	
}
*/
// Artz: See if anyone finds this valuable.
// May go against our "Easy" goal.
/*
// Easync Option 1
window.Easync = function() {

	for ( var args = arguments,
			arg,
			argsLength = arguments.length,
			fauxScript,
			i = 0; i < argsLength; i++ ) {
				
		fauxScript = {};
		arg = args[i];

		if ( typeof arg == "string" ) {
			fauxScript.src = arg;
			cacheScript( fauxScript );		
		} else if ( typeof arg == "function" ) {
			fauxScript.f = arg;
			executeScripts();
		}
		execQueue.push( fauxScript );
	}
};
*/
// Artz: We could do this instead, and be butt simpler.
/*
// Easync Option 2
window.Easync = function(url, callback){
	getObject( "script", url, callback );
};
*/
// Artz: This would be the most useful pattern to me.
// Easync Option 3
if ( Easync ) {
	window[ Easync ] = function( url, callback ) {
		if ( scriptExecuted[ url ] ) {
			// Script is already cached and executed, just do the callback.
			callback && callback();
		} else {
			getObject( "script", url, callback );
		}
	};
}

})( this, document, "Easync" );