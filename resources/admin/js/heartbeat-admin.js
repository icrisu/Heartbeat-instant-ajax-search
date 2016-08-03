'use strict';

jQuery(document).ready(function() {
    HeartBeatAdmin = new HeartBeatAdminClass();
    HeartBeatAdmin.init();
});

var HeartBeatAdmin;
var HeartBeatAdminClass = function() {
	this.views = {};
}

/**
 * ajax interface heartbeat wp implementation
 * @return Object {post, actions}
 */
HeartBeatAdminClass.prototype.ajaxInterface = function() {
	return {
		post: function(action, data, callback) {

			data.action = action;

			jQuery.post(
			    ajaxurl, 
			    data, 
			    function(response){
			    	try {
			    		var responseData = JSON.parse(response);
			    		if (responseData.status == 'OK') {
			    			callback(responseData);
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
			CREATE_INDEX: 'heartbeat_create_index'
		}
	}
};

//create new DB index
HeartBeatAdminClass.prototype.createNewDBIndex = function() {
	console.log('create new DB index');
	this.ajaxInterface().post(this.ajaxInterface().actions.CREATE_INDEX, {hello: 'test'}, function(err, result) {
		console.log(result);
	});
};

//init views
HeartBeatAdminClass.prototype.initViews = function() {
	this.views['indexOptions'] = new HeartBeatViews.IndexOptions();
};

//init
HeartBeatAdminClass.prototype.init = function() {
	this.initViews();
};