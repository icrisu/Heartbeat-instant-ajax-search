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
			CREATE_INDEX: 'heartbeat_create_index',
			GET_META: 'heartbeat_get_index_meta',
			GET_SEARCH_TERMS: 'heartbeat_get_post_types',
			UPDATE_INDEX_TERMS: 'heartbeat_update_index_terms',
			GET_SETTINGS: 'heartbeat_get_settings',
			SET_SETTINGS: 'heartbeat_set_settings'
		}
	}
};

//create new DB index
HeartBeatAdminClass.prototype.createNewDBIndex = function(callback) {
	this.ajaxInterface().post(this.ajaxInterface().actions.CREATE_INDEX, {}, callback);
};

//get index meta
HeartBeatAdminClass.prototype.getIndexMeta = function(callback) {
	this.ajaxInterface().post(this.ajaxInterface().actions.GET_META, {}, callback);
};

//fetch search terms
HeartBeatAdminClass.prototype.fetchSearchTerms = function(callback) {
	this.ajaxInterface().post(this.ajaxInterface().actions.GET_SEARCH_TERMS, {}, callback);
};

//update search terms
HeartBeatAdminClass.prototype.updateSearchTerms = function(terms, callback) {
	this.ajaxInterface().post(this.ajaxInterface().actions.UPDATE_INDEX_TERMS, {terms: terms}, callback);
};

//fetch settings
HeartBeatAdminClass.prototype.fetchSettings = function(callback) {
	this.ajaxInterface().post(this.ajaxInterface().actions.GET_SETTINGS, {}, callback);
};

//save settings
HeartBeatAdminClass.prototype.saveSettings = function(model, callback) {
	this.ajaxInterface().post(this.ajaxInterface().actions.SET_SETTINGS, {model: model}, callback);
};

//simple modal helper
HeartBeatAdminClass.prototype.simpleModal = function(title, content) {	
	jQuery('#hb-modal .hb-modal-header').html(title || 'title');
	jQuery('#hb-modal .hb-modal-content').html(content || 'content');
	jQuery('#hb-modal').openModal();
};

//init views
HeartBeatAdminClass.prototype.initViews = function() {

	var indexModel = new HeartBeatModels.IndexModel();
	this.views['indexOptions'] = new HeartBeatViews.IndexOptions({model: indexModel});
	
	var settingsModel = new HeartBeatModels.SettingsModel();	

	this.views['shortcodes'] = new HeartBeatViews.ShortcodesView({model: settingsModel});

	this.views['settings'] = new HeartBeatViews.SettingsView({model: settingsModel});

	jQuery('.admin-tabs-container').show();
};

//init
HeartBeatAdminClass.prototype.init = function() {
	this.initViews();
};