var url = '';
var check;

jQuery(document).ready(function() {
	jQuery('.rmp-image-link a, .rmp-text-link a').bind('click', function(event) {
		event.preventDefault();
		url = jQuery.url(jQuery(this).attr('href'));
		title = jQuery(this).attr('title');
		dimensions = jQuery.parseJSON(jQuery(this).attr('rel').replace(/'/g, "\""));
		jQuery.ajax({
			type: 'POST',
		    url: RMPAjax.ajaxurl,
		    data: 
			    {
			        action : 'rmp-load-player',
			        file : url.param('file'),
			        width : url.param('width'),
			        height : url.param('height'),
			        description : url.param('description')
			    },
		    success: function( response ) {
		    	jQuery.fancybox ({
		    		content:		response,
		    		title:			title,
		    		autoScale:		false,
		    		autoDimensions:	false,
		    		overlayShow:	true,
		    		overlayOpacity:	0.8,
		    		overlayColor:	"#666",
		    		titlePosition:	"over",
		    		transitionIn: 	"elastic",
		    		transitionOut:	"none",
		    		width:			dimensions.width,
		    		height:			dimensions.height,
		    		padding:		0,
		    		margin:			0,
		    		scrolling:		"no",
		    		onComplete: function() {
		    			jQuery("#fancybox-title").css({'top':'-38px', 'bottom':'auto', 'font-weight': 'bold'});
		    			jQuery("#fancybox-close").css({'top':'-45px'});
		    			jQuery("#rmp-player-container").css({'width':dimensions.width, 'height':dimensions.height});
		    			jQuery("#fancybox-content").css({'background-color':'black'});
		    			//jQuery.fancybox.resize();
		    		}
		    	});
			}
		});			
	});

	//Popup image specific js
	jQuery('.rmp-images-container ul li a img').load(function() {
		var width = jQuery(this).width();
		var height = jQuery(this).height();
		var p_width = jQuery(this).parent().parent().innerWidth();
		var p_height = jQuery(this).parent().parent().innerHeight();

		jQuery(this).css({
			'margin-left': Math.floor((p_width - width) / 2),
            'margin-top': Math.floor((p_height - height) / 2),
		});
	});
	// TODO:FIX stupid hack.  find a way to get rid of this
	// works on single post page without this
	//only fails in the_loop	
	jQuery('.rmp-images-container ul li a img').trigger('load');
});


// JQuery URL Parser plugin - https://github.com/allmarkedup/jQuery-URL-Parser
// Written by Mark Perkins, mark@allmarkedup.com
// License: http://unlicense.org/ (i.e. do what you want with it!)

;(function($, undefined) {
    
    var tag2attr = {
        a       : 'href',
        img     : 'src',
        form    : 'action',
        base    : 'href',
        script  : 'src',
        iframe  : 'src',
        link    : 'href'
    },
    
	key = ["source","protocol","authority","userInfo","user","password","host","port","relative","path","directory","file","query","fragment"], // keys available to query

	aliases = { "anchor" : "fragment" }, // aliases for backwards compatability

	parser = {
		strict  : /^(?:([^:\/?#]+):)?(?:\/\/((?:(([^:@]*):?([^:@]*))?@)?([^:\/?#]*)(?::(\d*))?))?((((?:[^?#\/]*\/)*)([^?#]*))(?:\?([^#]*))?(?:#(.*))?)/,  //less intuitive, more accurate to the specs
		loose   :  /^(?:(?![^:@]+:[^:@\/]*@)([^:\/?#.]+):)?(?:\/\/)?((?:(([^:@]*):?([^:@]*))?@)?([^:\/?#]*)(?::(\d*))?)(((\/(?:[^?#](?![^?#\/]*\.[^?#\/.]+(?:[?#]|$)))*\/?)?([^?#\/]*))(?:\?([^#]*))?(?:#(.*))?)/ // more intuitive, fails on relative paths and deviates from specs
	},

	querystring_parser = /(?:^|&|;)([^&=;]*)=?([^&;]*)/g, // supports both ampersand and semicolon-delimted query string key/value pairs

	fragment_parser = /(?:^|&|;)([^&=;]*)=?([^&;]*)/g; // supports both ampersand and semicolon-delimted fragment key/value pairs

	function parseUri( url, strictMode )
	{
		var str = decodeURI( url ),
		    res   = parser[ strictMode || false ? "strict" : "loose" ].exec( str ),
		    uri = { attr : {}, param : {}, seg : {} },
		    i   = 14;

		while ( i-- )
		{
			uri.attr[ key[i] ] = res[i] || "";
		}

		// build query and fragment parameters

		uri.param['query'] = {};
		uri.param['fragment'] = {};

		uri.attr['query'].replace( querystring_parser, function ( $0, $1, $2 ){
			if ($1)
			{
				uri.param['query'][$1] = $2;
			}
		});

		uri.attr['fragment'].replace( fragment_parser, function ( $0, $1, $2 ){
			if ($1)
			{
				uri.param['fragment'][$1] = $2;
			}
		});

		// split path and fragement into segments

        uri.seg['path'] = uri.attr.path.replace(/^\/+|\/+$/g,'').split('/');
        
        uri.seg['fragment'] = uri.attr.fragment.replace(/^\/+|\/+$/g,'').split('/');
        
        // compile a 'base' domain attribute
        
        uri.attr['base'] = uri.attr.host ? uri.attr.protocol+"://"+uri.attr.host + (uri.attr.port ? ":"+uri.attr.port : '') : '';
        
		return uri;
	};

	function getAttrName( elm )
	{
		var tn = elm.tagName;
		if ( tn !== undefined ) return tag2attr[tn.toLowerCase()];
		return tn;
	}

	$.fn.url = function( strictMode )
	{
	    var url = '';

	    if ( this.length )
	    {
	        url = $(this).attr( getAttrName(this[0]) ) || '';
	    }

        return $.url( url, strictMode );
	};

	$.url = function( url, strictMode )
	{
	    if ( arguments.length === 1 && url === true )
        {
            strictMode = true;
            url = undefined;
        }
        
        strictMode = strictMode || false;
        url = url || window.location.toString();
        	    	            
        return {
            
            data : parseUri(url, strictMode),
            
            // get various attributes from the URI
            attr : function( attr )
            {
                attr = aliases[attr] || attr;
                return attr !== undefined ? this.data.attr[attr] : this.data.attr;
            },
            
            // return query string parameters
            param : function( param )
            {
                return param !== undefined ? this.data.param.query[param] : this.data.param.query;
            },
            
            // return fragment parameters
            fparam : function( param )
            {
                return param !== undefined ? this.data.param.fragment[param] : this.data.param.fragment;
            },
            
            // return path segments
            segment : function( seg )
            {
                if ( seg === undefined )
                {
                    return this.data.seg.path;                    
                }
                else
                {
                    seg = seg < 0 ? this.data.seg.path.length + seg : seg - 1; // negative segments count from the end
                    return this.data.seg.path[seg];                    
                }
            },
            
            // return fragment segments
            fsegment : function( seg )
            {
                if ( seg === undefined )
                {
                    return this.data.seg.fragment;                    
                }
                else
                {
                    seg = seg < 0 ? this.data.seg.fragment.length + seg : seg - 1; // negative segments count from the end
                    return this.data.seg.fragment[seg];                    
                }
            }
            
        };
        
	};

})(jQuery);