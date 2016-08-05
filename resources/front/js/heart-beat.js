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
			    HeartBeatAjax.url, 
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

HeartBeatSearchClass.prototype.startSearchEngine = function() {
	this.log('start engine');
	console.log(this.storageInterface.getIndexes());
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
};

//compression algorithm
var HeartBeatCompressLZW = {
    compress: function (uncompressed) {
        "use strict";
        // Build the dictionary.
        var i,
            dictionary = {},
            c,
            wc,
            w = "",
            result = [],
            dictSize = 256;
        for (i = 0; i < 256; i += 1) {
            dictionary[String.fromCharCode(i)] = i;
        }
 
        for (i = 0; i < uncompressed.length; i += 1) {
            c = uncompressed.charAt(i);
            wc = w + c;
            //Do not use dictionary[wc] because javascript arrays 
            //will return values for array['pop'], array['push'] etc
           // if (dictionary[wc]) {
            if (dictionary.hasOwnProperty(wc)) {
                w = wc;
            } else {
                result.push(dictionary[w]);
                // Add wc to the dictionary.
                dictionary[wc] = dictSize++;
                w = String(c);
            }
        }
 
        // Output the code for w.
        if (w !== "") {
            result.push(dictionary[w]);
        }
        return result;
    },
 
 
    decompress: function (compressed) {
        "use strict";
        // Build the dictionary.
        var i,
            dictionary = [],
            w,
            result,
            k,
            entry = "",
            dictSize = 256;
        for (i = 0; i < 256; i += 1) {
            dictionary[i] = String.fromCharCode(i);
        }
 
        w = String.fromCharCode(compressed[0]);
        result = w;
        for (i = 1; i < compressed.length; i += 1) {
            k = compressed[i];
            if (dictionary[k]) {
                entry = dictionary[k];
            } else {
                if (k === dictSize) {
                    entry = w + w.charAt(0);
                } else {
                    return null;
                }
            }
 
            result += entry;
 
            // Add w+entry[0] to the dictionary.
            dictionary[dictSize++] = w + entry.charAt(0);
 
            w = entry;
        }
        return result;
    }
}