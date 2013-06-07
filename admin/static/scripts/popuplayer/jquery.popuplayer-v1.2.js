/**
 * jQuery popuplayer v1.2
 *
 * Copyright 2012, E-Mail: yanguanwei@qq.com, QQ: 176013294
 * Date: 2012-8-23
 */
;(function($) {
var Popuplayer = function( from, index, element, options ) {
	var self = this;
	
	if ( $('#popuplayer').length > 0 ) {
		this.overlay = $('#popuplayer-overlay');
		this.loadingbox = $('#popuplayer-loading');
		this.container = $('#popuplayer');
		this.wrapper = $('#popuplayer-wrapper');
		this.closebutton = $('#popuplayer-close');
		this.title = $('#popuplayer-title');
		this.outer = $('#popuplayer-outer');
		this.content = $('#popuplayer-content');
		this.tmp = $('#popuplayer-tmp');
		this.control = $('#popuplayer-control');
		this.confirmbutton = $('#popuplayer-confirm');
		this.cancelbutton = $('#popuplayer-cancel');
	} else {
		$('body').append(
			this.overlay = $('<div id="popuplayer-overlay"><div></div></div>').hide(),
			this.loadingbox = $('<div id="popuplayer-loading" />').hide(),
			this.tmp = $('<div id="popuplayer-tmp" />').hide(),
			this.container = $('<div id="popuplayer" />').hide()
		);
		
		this.container.append( 
			this.wrapper = $('<div id="popuplayer-wrapper" />').append(
				this.outer = $('<div id="popuplayer-outer" />').append(
					this.title = $('<div id="popuplayer-title" />').append('<h2 />').hide(),
					this.content = $('<div id="popuplayer-content" />'),
					this.control = $('<div id="popuplayer-control" />').hide()
				)
			),
			this.closebutton = $('<a id="popuplayer-close" />').hover(function() {
				$(this).addClass('hover');			   
			}, function() {
				$(this).removeClass('hover');	
			}).hide()
		);
		
		this.control.append(
			this.confirmbutton = $('<a id="popuplayer-confirm"></a>'),
			this.cancelbutton = $('<a id="popuplayer-cancel"></a>')
		).hide();
		
		this.loadingbox.bind('dblclick', function() {
			if (Popuplayer.current) {
				Popuplayer.current.close();
			}
		});
		
		this.overlay.bind('dblclick', function() {
			if (Popuplayer.current) {
				Popuplayer.current.close();
			}				  
		});
	}
	
	this.from = from;
	this.index = index;
	this.element = element;
	this.listeners = $.extend( true, {}, Popuplayer.listeners );
	this.options = Popuplayer.mergeOptions( this.listeners, {}, Popuplayer.defaults, options );

	this.extendOptions = function( o ) {
		o = $.extend( {}, o );
		if ( o['onInitialize'] ) {
			o.onInitialize.call( this );
			delete o['onInitialize'];
		}
		
		for ( var k in o ) {
			if ( k in options ) {
				delete o[k];
			}
		}
		Popuplayer.mergeOptions( this.listeners, this.options, o );
	};
	
	if ( this.from ) {
		this.from.data('popuplayer', this);
		
		if ( this.options.hasTitle ) {
			this.options.title = this.options.title || this.from.attr('title') || '';
		}
	}
	
	var objectEqual = function ( obj1, obj2 ) {
		for ( var k in obj1 ) {
			if ( !(k in obj2) || obj1[k] !== obj2[k] ) {
				return false;
			}
		}
		return true;
	};
	
	this.open = function() {
		if ( Popuplayer.busy ) return ;
		Popuplayer.busy = true;
		
		if ( Popuplayer.prev && Popuplayer.prev !== this ) {
			if ( !Popuplayer.prev.isClosed )
				Popuplayer.prev.isClosed = true;
			
			if ( !Popuplayer.prev.isCleanuped )
			Popuplayer.prev.cleanup();
		}
		
		this.isClosed = false;
		this.isConstructed = false;
		this.isCleanuped = false;
		
		Popuplayer.current = this;
		
		if ( false !== this.loading() ) {
			if ( null !== this.options.content ) {
				if ( false !== this.loaded(this.options.content) ) {
					return true;
				}
			} else {
				this.isLoading = true;
				if ( false !== this.options.load.call(this) ) {
					return true;
				}
			}
		}
		
		this.isClosed = true;
		this.cleanup();
		Popuplayer.busy = false;
		
		$.popuplayer({
			content: this.errorMessage || '',
			title: '错误',
			isError: true
		});
	};
	
	this.loading = function() {
		if ( false  === Popuplayer.trigger('onLoading', this) )
			return false;
			
		if ( this.options.hasOverlay && !this.overlay.is(':visible') ) {
			var opacity = this.overlay.css('opacity') || 0.5;
			this.overlay
				.css('opacity', 0)
				.show()
				.animate({
					opacity: opacity
				}, 'fast');
		}
		
		var pos = {};
		if ( this.container.is(':visible') ) {
			var offset =  this.content.offset(),
				loadingWidth = this.loadingbox.outerWidth(true),
				loadingHeight = this.loadingbox.outerHeight(true),
				contentWidth = this.content.innerWidth(),
				contentHeight = this.content.innerHeight();
			if ( contentWidth < loadingWidth )
				this.content.width(loadingWidth);
			if ( contentWidth < loadingWidth )
				this.content.width(loadingWidth);
			pos = {
				left: offset.left + (contentWidth  - loadingWidth) * 0.5,
				top	: offset.top + (contentHeight  - loadingHeight) * 0.5
			};
			
			this.zoomFrom();
		} else {
			pos = {
				left : $(window).scrollLeft() + ( $(window).width() - this.loadingbox.outerWidth(true) ) * 0.5,
				top	 : $(window).scrollTop() + ( $(window).height() - this.loadingbox.outerHeight(true) ) * 0.5	 
			};
			this.zoomFrom( 100, 100 );
		}
		
		this.content.empty();
		this.loadingbox.css( pos );
		this.loadingbox.show();
	};
	
	this.loaded = function( content ) {
		if ( this.options.caching )
			this.options.content = content;
		
		if ( this.isClosed )
			return ;
	
		this.isLoading = false;
		
		this.tmp.html( content );
		
		this.toContentWidth = parseInt(this.options.width || this.tmp.width(), 10),
		this.toContentHeight = parseInt(this.options.height || this.tmp.height(), 10);
		
		var offsetWidth = this.toContentWidth - this.fromContentWidth,
			offsetHeight = this.toContentHeight - this.fromContentHeight;
		
		this.toContainerWidth = parseInt(this.fromContainerWidth + offsetWidth, 10);
		this.toContainerHeight = parseInt(this.fromContainerHeight + offsetHeight, 10);
		this.toContainerOuterWidth = parseInt(this.fromContainerOuterWidth + offsetWidth, 10);
		this.toContainerOuterHeight = parseInt(this.fromContainerOuterHeight + offsetHeight, 10);
		
		if ( false  === Popuplayer.trigger('onLoaded', this, content) )
			return false;
			
		this.loadingbox.hide();
		this.show();
	};
	
	this.show = function() {
		this.content.html( this.tmp.html() );
		if ( this.container.is(':visible') ) {
			var from = this.container.offset(),
				to = this.getCenterPosition();
			from.width = this.fromContentWidth;
			from.height = this.fromContentHeight;
			to.width = this.toContentWidth;
			to.height = this.toContentHeight;
			if ( $.isFunction( this.options.transitionResize )) {
				this.options.transitionResize.call( this, from, to );
			} else {
				this.transitionResize( from, to );
			}
		} else {
			if ($.isFunction(this.options.transitionIn)) {
				this.options.transitionIn.call( this );
			} else {
				this.transitionIn();
			}
		}
	};
	
	this.construct = function() {
		if ( this.isConstructed )
			return false;
		
		if ( false  === Popuplayer.trigger('onConstruct', this) )
			return false;
		
		if ( this.options.isError ) {
			this.wrapper.addClass('popuplayer-error');	
		}
		
		if ( this.options.hasTitle ) {
			this.title
				.show()
				.html( $('<h2 />').html(this.options.title) );	
		}

		if ( this.options.hasControl ) {
			this.control.show();
		}
		
		if ( this.options.hasClose ) {
			this.closebutton
				.html(this.options.closeText)
				.show()
				.bind('click.pl', function() {
					self.close();
					return false;
				});	
		}
		
		if ( this.options.hasConfirm ) {
			this.confirmbutton
				.html(this.options.confirmText)
				.appendTo( this.control)
				.show()
				.bind('click.pl', function() {
					self.options.onConfirm.call(self);
					return false;
				});
		}
		
		if ( this.options.hasCancel ) {
			this.cancelbutton
				.html(this.options.cancelText)
				.appendTo( this.control )
				.show()
				.bind('click.pl', function() {
					self.options.onCancel.call(self);
					return false;
				});	
		}
		
		if (this.options.autoResize) {
			$(window).bind('resize.pl', function() {
				self.resize();
			}).bind('scroll.pl', function() {
				self.resize();	
			});	
		}
		
		this.isConstructed = true;
	};
	
	this.finish = function() {
		this.tmp.empty();
		if ( !this.isClosed ) {
			
			this.construct();
			
			Popuplayer.trigger('onShow', this);
			this.container.show();
			Popuplayer.busy = false;
			this.options.onFinish.call(self);
		}
		Popuplayer.prev = this;
	};
	
	this.close = function() {
		if ( this.isClosed )
			return ;
	
		if ( Popuplayer.busy ) {
			if ( this.isLoading ) {
				this.isLoading = false;
			} else {
				setTimeout(function() { self.close();}, 100);
				return ;
			}
			if ( this.loadingbox.is(':visible'))
				this.loadingbox.hide();
		} else {
			Popuplayer.busy = true;
		}
		
		if ( false  === Popuplayer.trigger('onClose', this) ) {
			Popuplayer.busy = false;
			return false;
		}
		
		this.isClosed = true;
		
		if ( $.isFunction(this.options.transitionOut) ) {
			this.options.transitionOut.call(this);
		} else {
			this.transitionOut();
		}
	};
	
	this.abort = function() {
		this.container.hide();
		this.content.empty();
		
		this.cleanup();
		
		if ( this.options.autoResize ) 
			$(window).unbind('resize.pl').unbind('scroll.pl');
		
		if ( this.options.hasOverlay ) {
			this.overlay.fadeOut('fast', function() { Popuplayer.busy = false;});
		} else {
			Popuplayer.busy = false;
		}
	};
	
	this.cleanup = function() {
		this.reset();
		
		Popuplayer.trigger('onCleanup', this);
		
		this.isCleanuped = true;
	};
	
	this.reset = function() {
		if ( this.isCleanuped )
			return false;
		
		this.title
			.add( this.control )
			.add( this.closebutton )
			.add( this.confirmbutton )
			.add( this.cancelbutton )
				.empty().hide();
		
		this.closebutton
			.add( this.confirmbutton )
			.add( this.cancelbutton )
				.unbind('click.pl');
		
		$(window).unbind('resize.pl').unbind('scroll.pl');
		
		this.wrapper
			.removeClass('popuplayer-error');
	};
	
	this.resize = function() {
		if ( Popuplayer.busy )
			return;
			
		var pos = this.getCenterPosition();
		
		this.container
			.stop()
			.animate({
				left	: pos.left,
				top		: pos.top
			}, 200);
	};

	this.zoom = function( width, height ) {
		if ( typeof width !== 'undefined' ) {
			this.content.width(width).height(height);
		}
		
		this.outer.width( this.content.outerWidth(true) ).height( this.content.outerHeight(true) );
		this.wrapper.width( this.outer.outerWidth(true) ).height( this.outer.outerHeight(true) );
		this.container.width( this.wrapper.outerWidth(true) ).height( this.wrapper.outerHeight(true) );
	};
	
	this.zoomFrom = function( width, height ) {
		this.zoom( width, height );
		
		this.fromContentWidth = parseInt(this.content.width(), 10);
		this.fromContentHeight = parseInt(this.content.height(), 10);
		this.fromContainerWidth = parseInt(this.container.width(), 10);
		this.fromContainerHeight = parseInt(this.container.height(), 10);
		this.fromContainerOuterWidth = parseInt(this.container.outerWidth(true), 10);
		this.fromContainerOuterHeight = parseInt(this.container.outerHeight(true), 10);
	};
	
	this.transitionIn = function() {
		this.zoom( this.toContentWidth, this.toContentHeight );
		this.container
			.css( this.getCenterPosition() ).show()
			.fadeIn(this.options.speedIn, this.options.easingIn, function() { self.finish();});
	};
	
	this.transitionResize = function( from, to ) {
		var fx = $.extend($('<div/>')[0], { prop: 0 });
	
		this.content.css('opacity', 0);
		
		var fadeIn = function() {
			self.content
				.css('opacity', 0.5)
				.animate({ opacity: 1 }, self.options.speedResize, self.options.easingResize, function() {
					self.finish();					  
				});	
		};
		
		if ( objectEqual(from, to) ) {
			fadeIn();
		} else {
			$(fx).animate({prop: 1}, {
				duration : this.options.speedResize,
				easing : this.options.easingResize,
				step : function(pos) {
					var params = {
						top		: from.top + (to.top - from.top) * pos,
						left	: from.left + (to.left - from.left) * pos
					};
					var w = from.width + (to.width - from.width) * pos,
						h = from.height + (to.height - from.height) * pos;
					
					self.zoom(w, h);
					self.container.css( params );
				},
				complete : function() { fadeIn();} 
			});
		}
	};
	
	this.transitionOut = function() {
		this.container.fadeOut(this.options.speedOut, this.options.easingOut, function() { 
			self.abort(); 
		});
	};
	
	this.getCenterPosition = function() {
		return {
			left	: parseInt($(window).scrollLeft() + ( $(window).width() - this.toContainerOuterWidth ) * 0.5, 10),
			top		: parseInt($(window).scrollTop() + ( $(window).height() - this.toContainerOuterHeight ) * 0.5, 10),
			width	: this.toContainerWidth,
			height	: this.toContainerHeight
		};
	};
	
	this.error = function( content ) {
		this.errorMessage = content;
		return false;
	};
	
	Popuplayer.trigger('onInitialize', this);
};

Popuplayer.defaults = {
	width			: 0,
	height			: 0,
	hasControl		: false,
	hasConfirm		: true,
	hasCancel		: true,
	hasTitle		: true,
	hasOverlay		: false,
	hasClose		: true,
	caching			: true,
	autoResize		: true,
	isError			: false,
	title			: null,
	content			: null,
	confirmText		: '确定',
	cancelText		: '取消',
	closeText		: '',
	transitionIn	: null,				//当为自定义过渡效果时，此函数有一个参数传递(content)， 必须在效果过渡完后调用this.finish()函数
	easingIn		: 'swing',
	speedIn			: 'normall',
	transitionOut	: null,				//当为自定义过渡效果时，必须在效果过渡完后调用this.abort()函数
	easingOut		: 'swing',
	speedOut		: 'normall',
	easingResize	: 'swing',
	speedResize		: 'normall',
	transitionResize: null,
	onFinish		: function() {},
	load			: function() {},	//载入内容，当内容载入完后，必须要调用this.loaded( content )函数
	onConfirm		: function() { this.close();},
	onCancel		: function() { this.close();}
};

Popuplayer.busy = false;
Popuplayer.prev = null;
Popuplayer.current = null;

Popuplayer.events = ['onInitialize', 'onConstruct', 'onLoading', 'onLoaded', 'onShow', 'onClose', 'onCleanup'];
Popuplayer.listeners = {};

Popuplayer.attchEvent = function( listeners, event, fn ) {
	if ( !listeners[event] ) {
		listeners[event] = [];	
	}
	listeners[event].push( fn );
};

Popuplayer.trigger = function() {
	var rst = true,
		event = arguments[0],
		self = arguments[1],
		args = [];
	
	for (var i = 2; i < arguments.length; i++) {
		args.push(arguments[i]);	
	}
	
	if ( self.listeners[event] ) {
		$.each(self.listeners[event], function(i, e) {
			if ( $.isFunction(e) && false  === e.apply(self, args) ) {
				rst = false;
				return false;	
			}
		});
	}
	
	return rst;
};

Popuplayer.mergeOptions = function() {
	var listeners = arguments[0],
		target = arguments[1],
		tmp = {};
	for (var i = 2; i < arguments.length; i++) {
		var options = arguments[i];
		for (var k in options) {
			if ( -1 !== $.inArray(k, Popuplayer.events) ) {
				Popuplayer.attchEvent( listeners, k, options[k] );
				delete options[k];
			}
		}
		$.extend( tmp, options );
	}
	return $.extend( target, tmp );
};

$.popuplayer = function( element, options ) {
	if (typeof options === 'undefined') {
		options = element;
		element = null;
	}
	
	if ( element ) {
		element.each(function(i) {
			var from = $(this);
			var popup = new Popuplayer( from, i, element, options );
			from
				.unbind('click.pl')
				.bind('click.pl', function(e) {
					popup.open();
					return false;
				});
		});
	} else {
		var popup = new Popuplayer( element, 0, $([]),  options );
		popup.open();
	}
};

$.popuplayer.extendDefaults = function( options ) {
	Popuplayer.mergeOptions( Popuplayer.listeners, Popuplayer.defaults, options );
};

$.popuplayer.extend = function( obj ){
	$.extend(Popuplayer.prototype, obj);
};

$.fn.popuplayer = function( options ) {
	$.popuplayer( $(this), options );
};

})(jQuery);

/**
 * jQuery popuplayer.skin v1.2
 *
 * Copyright 2012, E-Mail: yanguanwei@qq.com, QQ: 176013294
 * Date: 2012-8-23
 */
;(function($) {
		  
var skins = {};

$.popuplayer.extendDefaults({
	skin : 'grey',
	onInitialize: function() {
		if ( skins[ this.options.skin ] ) {
			this.extendOptions( skins[ this.options.skin ] );
		}
	},
	onLoading: function() {
		if (this.container.data('skin') ) {
			if ( this.container.data('skin') !== this.options.skin ) {
				this.container.removeClass( this.container.data('skin') );
			} else {
				return ;	
			}
		}
		var skinClass = 'popuplayer-skin-' + this.options.skin;
		this.container
			.addClass( skinClass )
			.data( 'skin', skinClass );	
	}
});

$.popuplayer.addSkin = function( name, options ) {
	skins[name] = options || {};
};

//add skins
$.popuplayer.addSkin('grey', {
	onLoading: function() {
		var marginTop = marginBottom = 0;
		if ( this.options.hasTitle ) {
			marginTop = this.title.outerHeight(true);
		}
		
		if ( this.options.hasControl ) {
			marginBottom = this.control.outerHeight(true);
		}
		
		this.outer.css( {'padding-top': marginTop, 'padding-bottom': marginBottom});
		this.construct();	
	},
	onCleanup: function() {
		if ( this.options.hasTitle ) {
			this.outer.css('padding-top', 0);
		}
		
		if ( this.options.hasControl ) {
			this.outer.css('padding-bottom', 0);	
		}
	}
});

$.popuplayer.addSkin('fancy', {
	onInitialize: function() {
		if ( this.wrapper.find('.popuplayer-skin-fancy-bg').length == 0 ) {
			this.wrapper.append('<div id="popuplayer-skin-fancy-n" class="popuplayer-skin-fancy-bg"></div><div id="popuplayer-skin-fancy-ne" class="popuplayer-skin-fancy-bg"></div><div id="popuplayer-skin-fancy-e" class="popuplayer-skin-fancy-bg"></div><div id="popuplayer-skin-fancy-se" class="popuplayer-skin-fancy-bg"></div><div id="popuplayer-skin-fancy-s" class="popuplayer-skin-fancy-bg"></div><div id="popuplayer-skin-fancy-sw" class="popuplayer-skin-fancy-bg"></div><div id="popuplayer-skin-fancy-w" class="popuplayer-skin-fancy-bg"></div><div id="popuplayer-skin-fancy-nw" class="popuplayer-skin-fancy-bg"></div>');
		}	
		
		var self = this;
		
		this.transitionIn = function() {
			this.zoom( this.toContentWidth, this.toContentHeight );
			this.container.css( this.getCenterPosition() ).show();
			this.content.hide().fadeIn(this.options.speedIn, this.options.easingIn, function() { 
				self.finish();
			});
		};
		
		this.transitionOut = function() {
			this.outer.fadeOut(this.options.speedOut, this.options.easingOut, function() {
				self.abort();
				self.outer.show();
			});
		};
	},
	onCleanup: function() {
		if ( this.options.hasTitle ) {
			this.title.hide();	
		}
		
		if ( this.options.hasClose ) {
			this.closebutton.hide();	
		}
	},
	onClose: function() {
		this.cleanup();	
	}
	
});

})(jQuery);

/**
 * jQuery popuplayer.loader v1.2
 *
 * Copyright 2012, E-Mail: yanguanwei@qq.com, QQ: 176013294
 * Date: 2012-8-23
 */
;(function($) {
var loader	= {
	'default' : null
};

$.popuplayer.extendDefaults({
	loader: null,
	onInitialize: function() {
		if ( null !== this.options.content )
			return ;
		
		if ( null === this.options.loader )	{
			for ( name in loader) {
				if ( 'default' !== name && loader['default'] !== name ) {
					if ( true === loader[ name ].match.call(this) ) {
						this.options.loader = name;
						break;
					}
				}
			}
			
			if ( null === this.options.loader && null !== loader['default'] ) {
				this.options.loader = loader['default'];
			}
		}
		
		if ( this.options.loader && loader[this.options.loader] ) {
			var o = $.extend( {}, loader[this.options.loader] );
			delete o['match'];
			delete o['load'];
			this.extendOptions( o );
		}
	},
	load: function() {
		if ( null != this.options.loader ) {
			loader[this.options.loader].load.call(this);
		} else {
			this.loaded( "loader hasn't matched!" );
		}
	}
});

$.popuplayer.addLoader = function( name, options, isDefault) {
	loader[ name ] = options;
	if ( isDefault ) loader['default'] = name;
};

$.popuplayer.addLoader('image', {
	match: function() {
		if (this.from) {
			if ( this.from.attr('href').match(/\.(jpg|gif|png|bmp|jpeg)(.*)?$/i) ) {
				return true;
			}
		}
	},
	load: function() {
		var self = this,
			imgPreloader = new Image();
		imgPreloader.onerror = function() {};
		imgPreloader.onload = function() {
			self.loaded( this.orig = $("<img />").attr({
				'id' : 'popuplayer-loader-image',
				'src' : imgPreloader.src,
				'alt' : self.options.title
			}));
		};
		imgPreloader.src = this.from.attr('href');
	}
});

//ajax
$.popuplayer.addLoader('ajax', {
	ajax: {},
	onInitialize: function() {
		if ('success' in this.options.ajax) {
			this.ajaxSuccess = this.options.ajax['success'];
			delete this.options.ajax['success'];
		}
	},
	match: function() {
		
	},
	load: function() {
		var href = this.from.attr('href'),
			self = this;
		this.ajaxLoader = $.ajax($.extend({}, this.options.ajax, {
			url	: href,
			error : function(XMLHttpRequest, textStatus, errorThrown) {
				if ( XMLHttpRequest.status > 0 ) {}
			},
			success : function(data, textStatus, XMLHttpRequest) {
				var o = typeof XMLHttpRequest == 'object' ? XMLHttpRequest : this.ajaxLoader;
				if (o.status == 200) {
					if ( self.ajaxSuccess ) {
						ret = self.ajaxSuccess(href, data, textStatus, XMLHttpRequest);
						if (ret === false) {
								
						} else if (typeof ret == 'string' || typeof ret == 'object') {
								data = ret;
						}
					}
					self.loaded(data);
				}
			}
		}));
	},
	onCleanup: function() {
		if ( this.ajaxLoader ) {
			this.ajaxLoader.abort();	
		}
	}
}, true);

//iframe
$.popuplayer.addLoader('iframe', {
	iframeWidth		: 'auto',
	iframeHeight	: 'auto',
	iframeScrolling	: 'auto',		//auto, no, yes
	match: function() {
		if ( this.from && this.from.hasClass('iframe') ) {
			return true;
		}
	},
	load: function() {
		var href = this.from.attr('href');
		href += (href.indexOf('?') > 0 ? '&' : '?') + '_iframe=1';
		
		var iframe = document.createElement("iframe");
		iframe.id = "popuplayer-loader-iframe";
		iframe.name = "popuplayer-loader-iframe" + new Date().getTime();
		
		this.iframe = $(iframe).attr({
			'frameborder': '0',
			'hspace': '0',
			'scrolling': this.options.iframeScrolling,
			'width': this.options.iframeWidth,
			'height': this.options.iframeHeight,
			'allowtransparency': $.browser.msie ? 'true' : ''
		}); 

		iframe.src = href;
		
		this.loaded(
			this.iframe
		);
	}
});

//inline
$.popuplayer.addLoader('inline', {
	match: function() {
		if ( this.from && this.from.attr('href').indexOf("#") === 0 ) {
			return true;
		}
	},
	onInitialize: function() {
		this.inline = $(this.from.attr('href'));
	},
	onConstruct: function() {
		if ( this.inline.length == 0 ) {
			return this.error( '没有找到ID为' + this.from.attr('href').substr(1) + '的元素块！' );
		}
		
		if ( !this.options.title && this.inline.attr('title') ) {
			this.options.title = this.inline.attr('title');
		}
	},
	load: function() {
		this.loaded( this.inline.show() );	
	}		   
});

})(jQuery);

/**
 * jQuery popuplayer.transition v1.2
 *
 * Copyright 2012, E-Mail: yanguanwei@qq.com, QQ: 176013294
 * Date: 2012-8-23
 */
;(function($) {

var transition = {};

$.popuplayer.extendDefaults({
	transition: null,
	onInitialize: function() {
		if ( this.options.transition !== null && transition[this.options.transition] ) {
			this.extendOptions( transition[this.options.transition] );
		}
	}
});

$.popuplayer.addTransition = function( name, o) {
	transition[name] = o;
};

$.popuplayer.addTransition('elastic', {
	onInitialize: function() {
		if ( !this.from || !this.options.loader || this.options.loader !== 'image' || this.from.find('img').length == 0 ) {
			this.options.transitionIn = this.options.transitionOut = null;
			return ;
		}
		
		this.fx = $.extend($('<div/>')[0], { prop: 0 });
		this.fromImage = this.from.find('img');
		
		this.getFromPosition = function() {
			var fromPosition = this.fromImage.offset();
			
			fromPosition.width = this.fromImage.width();
			fromPosition.height = this.fromImage.height();
			this.zoomFrom( fromPosition.width, fromPosition.height );
			fromPosition.left -= (this.fromContainerOuterWidth - this.fromContentWidth) * 0.5;
			fromPosition.top -= (this.fromContainerOuterHeight - this.fromContentHeight) * 0.5;
			
			return fromPosition;
		};
		
		this.getToPosition = function() {
			var centerPosition = this.getCenterPosition();
			return {
				left	: centerPosition.left,
				top		: centerPosition.top,
				width	: this.toContentWidth,
				height	: this.toContentHeight
			};
		};
		
		this.draw = function(pos) {
			var params = {
				top		: this.fromPosition.top + (this.toPosition.top - this.fromPosition.top) * pos,
				left	: this.fromPosition.left + (this.toPosition.left - this.fromPosition.left) * pos
			};
			
			var w = this.fromPosition.width + (this.toPosition.width - this.fromPosition.width) * pos,
				h = this.fromPosition.height + (this.toPosition.height - this.fromPosition.height) * pos;
			
			this.content.find('#popuplayer-loader-image').width(w).height(h).css('opacity', 0.5 + 0.5 * pos);
			this.zoom(w, h);
			this.container.css( params );
		};
	},
	transitionIn: function() {
		this.fx.prop = 0;
		this.fromPosition = this.getFromPosition();
		this.toPosition = this.getToPosition();
		
		this.container
			.css({
				left	: this.fromPosition.left,
				top		: this.fromPosition.top
			})
			.show();
		this.content.find('img').width(this.fromPosition.width).height(this.fromPosition.height);
		//return this.finish();
		
		var self = this;
		$(this.fx).animate({prop: 1}, {
			 duration : this.options.speedIn,
			 easing : this.options.easingIn,
			 step : function(pos) { self.draw(pos);},
			 complete : function() { self.finish();}
		});		   
	},
	transitionOut: function() {
		this.fx.prop = 1;
		this.fromPosition = this.getFromPosition();
		this.toPosition = this.getToPosition();
		
		var self = this;
		
		$(this.fx).animate({prop: 0}, {
			 duration : this.options.speedOut,
			 easing : this.options.easingOut,
			 step : function(pos) { self.draw(pos);},
			 complete : function() { self.abort(); self.container.css('opacity', 1);}
		});
	}
});

})(jQuery);

/**
 * jQuery popuplayer.drag v1.2
 *
 * Copyright 2012, E-Mail: yanguanwei@qq.com, QQ: 176013294
 * Date: 2012-8-23
 */
;(function($) {

$.popuplayer.extendDefaults({
	draggable: true,
	onConstruct: function() {
		var self = this;
		this.isDragging = false;
		this.dragOffsetX = this.dragOffsetY = 0;
		
		if ( this.options.draggable ) {
			
			this.options.autoResize = false;
			
			this.title.bind('mousedown.pl', function(e) {
				self.isDragging = true;
				var offset = self.container.offset();
				self.dragOffsetX = e.pageX - offset.left;
				self.dragOffsetY = e.pageY - offset.top;
				
				self.container.css('opacity', 0.5);
				
			}).css('cursor', 'move');
			
			this.title.bind('mouseup.pl', function(e) {
				self.isDragging = false;
				self.container.css('opacity', 1);
			});
			
			this.title.bind('mousemove.pl', function(e) {
				if ( self.isDragging ) {
					self.container.css({
						left: e.pageX - self.dragOffsetX,
						top: e.pageY - self.dragOffsetY
					});
				}
			});
		}
	},
	onCleanup: function() {
		if ( this.options.draggable ) {
			this.title
				.unbind('mousedown.pl')
				.unbind('mouseup.pl')
				.unbind('mousemove.pl')
				.css('cursor', 'default');
		}
	}
});

})(jQuery);

/**
 * jQuery popuplayer v1.2
 *
 * Copyright 2012, E-Mail: yanguanwei@qq.com, QQ: 176013294
 * Date: 2012-8-23
 */
;(function($) {
	
$.popuplayer.extendDefaults({
	prevText: '',
	nextText: '',
	onInitialize: function() {
		if ( $('#popuplayer-prev').length == 0 ) {
			this.outer.append(
				this.prevbutton = $('<div id="popuplayer-prev" />').hide(),
				this.nextbutton = $('<div id="popuplayer-next" />').hide()
			);
			
			this.prevbutton
				.add( this.nextbutton )
				.hover(function() {
					$(this).addClass('hover');
					return false;
				}, function() {
					$(this).removeClass('hover');
					return false;
				});
		} else {
			this.prevbutton = $('#popuplayer-prev');
			this.nextbutton = $('#popuplayer-next');	
		}
	},
	onShow: function() {
		var self = this;
		if ( this.element.length > 1 ) {
			
			if ( this.index > 0 ) {
				this.prevbutton
					.show()
					.click(function() {
						if ( self.index > 0 ) {
							self.element.eq(self.index - 1).trigger('click');
						}
						return false;
					});
			}
			
			if ( this.index < this.element.length - 1) {
				this.nextbutton
					.show()
					.click(function() {
						if ( self.index < self.element.length - 1 ) {
							self.element.eq(self.index + 1).trigger('click');
						}
						return false;
					});
			}
		}
	},
	onCleanup: function() {
		this.prevbutton
			.add( this.nextbutton )
			.hide()
			.unbind('click');
	}
});
	
})(jQuery);