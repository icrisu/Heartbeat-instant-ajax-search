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

	this.removeIndexes = function(toBeRemoved, hash) {
		//console.log('remove here');
		this.getIndexes();	
		//console.log(this.indexes);
		for (var k in toBeRemoved) {
		    if (toBeRemoved.hasOwnProperty(k)) {
		       try {
		       		//console.log(this.indexes[k]);
		       		delete this.indexes[k];
		       } catch(e) {
		       		this.log(e);
		       }
		    }
		}
		//console.log(this.indexes);
		this.saveIndexes({
			hash: hash,
			indexes: this.indexes
		});
	}

	this.addIndexes = function(toBeAdded, hash) {
		this.getIndexes();
		for (var k in toBeAdded) {
		    if (toBeAdded.hasOwnProperty(k)) {
		       try {
		       		this.indexes[k] = toBeAdded[k];
		       } catch(e) {}
		    }
		}
		this.saveIndexes({
			hash: hash,
			indexes: this.indexes
		});
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
	//handle default WP theme search form 
	jQuery('.search-field').each(function(indx) {
	    _self.handleInput(jQuery(this));	
	});

	//handle material design custom form
	jQuery('.hbmd-search-input').each(function(indx) {
	    _self.handleInput(jQuery(this));	
	});

	//handle simple custom form
	jQuery('.hb-search-input').each(function(indx) {
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

/**
 * process result 
 * @param  {object} result 
 * @return {HeartBeatSearchClass}
 */
HeartBeatSearchClass.prototype.processIndexesResult = function(result) {
	if (result.indexes && result.indexes == 'no_change') {
		this.log('do nothing same hash');
		return this;
	}
	if (result.indexes) {
		this.log('save all indexes');
		this.storageInterface.saveIndexes(result);
	} else {
		if (result.removed && result.hash) {
			this.storageInterface.removeIndexes(result.removed, result.hash);
		}
		if (result.added && result.hash) {
			this.storageInterface.addIndexes(result.added, result.hash);
		}	
		this.log('compute result');
	}
	return this;
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
	var meta = this.storageInterface.getMeta();
	var meta = (_.isNull(meta)) ? {} : meta;
	
	this.ajaxInterface().post(this.ajaxInterface().actions.GET_INDEX_DATA, meta, _.bind(function(err, result) {
		if (!err && result) {
			this.processIndexesResult(result).startSearchEngine();			
		}
	}, this));
	
	return;
};
