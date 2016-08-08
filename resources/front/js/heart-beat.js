'use strict';

jQuery(document).ready(function() {
    HeartBeatSearch = new HeartBeatSearchClass();
    HeartBeatSearch.init();
});
var HeartBeatSearch;
var HeartBeatSearchClass = function() {
	this.isLocalStorage;
	this.storageInterface;
}

/**
 * ajax interface heartbeat
 * @return Object {post, actions}
 */
HeartBeatSearchClass.prototype.ajaxInterface = function() {
	return {
		post: function(action, data, callback) {

			data.action = action;

			jQuery.post(
			    HeartBeatOptions.ajaxurl, 
			    data, 
			    function(response){			    	
			    	try {
			    		var responseData = JSON.parse(response);
			    		if (responseData.status == 'OK') {
			    			callback(false, responseData.data);
			    		} else {
			    			callback(new Error(responseData.msg), responseData);
			    		}
			    	} catch (e) {
			    		//alert('invalid server response');
			    	}
			    }
			);

		},

		actions: {
			GET_META: 'heartbeat_front_get_meta',
			GET_INDEX_DATA: 'heartbeat_front_get_index_data'
		}
	}
};

/**
 * Helper for storage
 */
HeartBeatSearchClass.prototype.StorageInterface = function() {
	var storageKeys = {
		meta: 'heartbeat_meta_392432',
		indexes: 'heartbeat_indexes_23742'
	}

	this.meta;
	this.indexes;
	this.SearchEngine;

	//get saved meta
	this.getMeta = function() {
		if (this.meta) {
			return this.meta;
		}
		this.meta = JSON.parse(localStorage.getItem(storageKeys.meta));
		return this.meta;
	}

	//save meta
	this.saveMeta = function(hash) {
		this.meta = {hash: hash};
		localStorage.setItem(storageKeys.meta, JSON.stringify(this.meta));
		return this;
	}

	//save indexes
	this.saveIndexes = function(result) {
		this.saveMeta(result.hash);
		this.indexes = result.indexes;				
		localStorage.setItem(storageKeys.indexes, JSON.stringify(result.indexes));
		return this;
	}

	//get indexes
	this.getIndexes = function() {
		if (this.indexes) {
			return this.indexes;
		}
		//this.indexes = JSON.parse(localStorage.getItem(storageKeys.indexes));
		this.indexes = JSON.parse(localStorage.getItem(storageKeys.indexes));
		return this.indexes;
	}

};

//check if storage is available
HeartBeatSearchClass.prototype.checkStorageAvailability = function() {
    var test = 'testing';
    try {
        localStorage.setItem(test, test);
        localStorage.removeItem(test);
        return true;
    } catch(e) {
        return false;
    }
};

HeartBeatSearchClass.prototype.handleInput = function(hbmdInput) {

	var _self = this;
	hbmdInput.autocomplete({
		delay: 100,
		source: function(request, response) {				
			response(_self.processResult(_self.Search(request.term)));
		},
		focus: function( event, ui ) {			
			hbmdInput.val(ui.item.title);
			return false;
		},
		select: function( event, ui ) {
			window.location.href = ui.item.href;
			return false;
		},	
		create: function() {
            jQuery(this).data('ui-autocomplete')._renderItem = function (ul, item) {            	
            	var thumb = '';
            	if (item.thumb) {
            		thumb += [
            			'<span class="hb-item-thumb-ui">',
            				'<img class="hb-item-thumb" src="' + item.thumb + '" alt="" />',
            			'</span>'
            		].join('');
            	}
            	var html = [
            		'<li class="hb-autocomplete-item-li">',
            			thumb,
            			'<a class="hb-autocomplete-item" href="' + item.href + '">' + item.title + '</a>',
            			'<span class="hb-autocomplete-item-action-ui"><span class="hb-autocomplete-item-action heartbeat-icon-chevron-right"></span></span>',
            		'<li>'
            	].join('');

            	var htmlUI = jQuery(html);
            	htmlUI.css('opacity', 0);
            	console.log(htmlUI.height())
            	htmlUI.stop().animate({
            		opacity: 1
            	}, 200);
            	return htmlUI.appendTo(ul);
            }
           
		}			
	});
};

//search input handler
HeartBeatSearchClass.prototype.startInputHandler = function() {
	var _self = this;
	jQuery('.search-field').each(function(indx) {
	    _self.handleInput(jQuery(this));	
	});
};

//process result
HeartBeatSearchClass.prototype.processResult = function(result) {
	if (_.isArray(result) && result.length == 0) {
		return result;
	}
	var out = [];
	var store = this.storageInterface.getIndexes();


	for (var i = 0; i < result.length; i++) {
		out.push({
			title: store[result[i].ref].t,
			thumb: store[result[i].ref].i,
			href: store[result[i].ref].l
		});
		if (i == HeartBeatOptions.md_max_results) {
			break;
		}
	}
	return out;
};

HeartBeatSearchClass.prototype.Search = function(query) {
	if (!this.SearchEngine) {
		return [];
	}
	return this.SearchEngine.search(query);
};

HeartBeatSearchClass.prototype.startSearchEngine = function() {
	this.log('start engine');
	var store = this.storageInterface.getIndexes();
	
	this.SearchEngine = lunr(function() {
		this.field('t', {boost: 10})
		this.field('tg', {boost: 8})
		this.ref('id')
	});


    for (var key in store) {
        if (store.hasOwnProperty(key)) {
        	var title = store[key].t || '';
        	var tags = store[key].tg || '';
			this.SearchEngine.add({
				id: key,
				t: title,
				tg: tags
			});
        }
    }

    this.startInputHandler();
};

//init with meta
HeartBeatSearchClass.prototype.initWithMeta = function(err, result) {
	if (!err && result) {				
		var currentMeta = this.storageInterface.getMeta();		

		if (_.isNull(currentMeta) || currentMeta.hash != result.hash) {
			this.log('update meta & results');
			this.ajaxInterface().post(this.ajaxInterface().actions.GET_INDEX_DATA, {}, _.bind(function(err, result) {
				if (!err && result) {
					this.storageInterface.saveIndexes(result);
					this.startSearchEngine();
				}
			}, this));
		} else {
			//start search engine
			this.startSearchEngine();
		}
	}
};

//log helper
HeartBeatSearchClass.prototype.log = function(msg) {
	if (!window.console) {
		console = { log: function(){} };
	}
	console.log(msg);
};

//init
HeartBeatSearchClass.prototype.init = function() {
	this.log('init');
	this.isLocalStorage = this.checkStorageAvailability();
	if (!this.isLocalStorage) {
		return;
	}
	this.storageInterface = new this.StorageInterface();

	this.ajaxInterface().post(this.ajaxInterface().actions.GET_META, {}, _.bind(this.initWithMeta, this));

  jQuery('input').blur(function() {
    // check if the input has any value (if we've typed into it)
    if (jQuery(this).val())
      jQuery(this).addClass('used');
    else
      jQuery(this).removeClass('used');
  });

};
