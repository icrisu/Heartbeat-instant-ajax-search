'use strict';

var HeartBeatViews = {

	//index options view
	IndexOptions: function(options) {
		var view = Backbone.View.extend({

			events: {
				'click .indexDbBtn': 'indexDbHandler',
				'click .search-term-checkbox': 'checkTermHandler',
			},

			el: '#index-options',

			renderPartMetaInfo: function() {
				if (this.model.get('timestamp') === 'no_time') {
					this.$el.find('.heartbead-meta-info-ui2').html('There is no index available yet, select post types you want to index than click the "Index Db Now" button.');
				} else {
					this.$el.find('.heartbead-meta-info-ui2').html('Last index created on ' + this.model.get('formatedDate'));
				}
			},

			//rende choose search term component
			renderPartChooseTerms: function() {
				var terms = this.model.get('terms');
				var availableTerms = this.model.get('availableTerms');

				var allAvailableHTML = '';
				if (availableTerms && _.isArray(availableTerms)) {
					for (var i = 0; i < availableTerms.length; i++) {
						var isSelected = this.model.isTermInTerms(availableTerms[i].key);
						var isCheckedHTML = isSelected ? ' checked="checked"' : '';
						var out = [
							'<li class="collection-item">',
								'<input type="checkbox" data-termkey="' + availableTerms[i].key + '" data-termlabel="' + availableTerms[i].label + '" class="search-term-checkbox" id="' + availableTerms[i].key + i + '"' + isCheckedHTML + ' />',
								'<label for="' + availableTerms[i].key + i + '">' + availableTerms[i].label + '</label>',
							'</li>'						
						].join('');
						allAvailableHTML += out;
					}
					this.$el.find('.heartbeat-search-terms').html(jQuery(allAvailableHTML));
				}

			},

			checkTermHandler: function(event) {
		        var target = jQuery(event.target);
		        var selected = target.is(':checked');
		        this.model.syncTerm(target.data().termkey, target.data().termlabel, selected);
			},

			initialize: function(options) {
				this.model.on('change:timestamp', _.bind(this.renderPartMetaInfo, this));
				this.model.on('change:availableTerms', _.bind(this.renderPartChooseTerms, this));
				this.model.fetchMeta();
				this.model.fetchSearchTerms();	
			},

			render: function() {
				this.renderMetaInfo();
			},

			removeIndexProgress: function() {
				this.$el.find('.indexDbBtn').removeClass('disabled');
				this.$el.find('.progress-index').hide();
			},

			indexDbHandler: function(event) {
				event.preventDefault();

				var indxBtn = this.$el.find('.indexDbBtn');
				if (indxBtn.hasClass('disabled')) {
					return;
				}

				var terms = this.model.get('terms');
				if (terms && _.isArray(terms) && terms.length != 0) {
					indxBtn.addClass('disabled');
					this.$el.find('.progress-index').show();

					HeartBeatAdmin.createNewDBIndex(_.bind(function(err, result) {
						if (err) {
							alert(err);
						}
						this.model.fetchMeta();
						_.delay(_.bind(this.removeIndexProgress, this), 3000);
					}, this));
				} else {
					HeartBeatAdmin.simpleModal('Choose post type', 'Please choose at least one post type to index.');
				}								
			}
		});
		return new view(options);
	},

	//Settings  view
	SettingsView: function(options) {
		var view = Backbone.View.extend({

			events: {
				'click .saveSettingsBtn' : 'saveSettings'
			},

			el: '#settings',

			renderMaxResult: function() {
				this.$el.find('.hb_max_results').val(this.model.get('maxResults'));
			},

			renderCustomCSS: function() {
				document.getElementById('hb_custom_css').value = this.model.get('customCSS');
			},			

			initialize: function() {
				this.model.on('change:maxResults', _.bind(this.renderMaxResult, this));
				this.model.on('change:customCSS', _.bind(this.renderCustomCSS, this));
				this.model.fetchData();
			},

			saveSettings: function() {
				this.model.set('maxResults', this.$el.find('.hb_max_results').val());
				this.model.set('customCSS', document.getElementById('hb_custom_css').value);
				this.model.saveData();
			}
		});
		return new view(options);
	},

	//Shortcodes  view
	ShortcodesView: function(options) {
		var view = Backbone.View.extend({

			events: {
				'click .hb-activate-integration-cb': 'checkActivateHandler',
				'click .saveSettingsBtn' : 'saveSettings'
			},

			el: '#shortcodes',

			renderActivation: function() {
				var isActivated = this.model.get('isNativeIntegration');
				var html = (isActivated == 'true') ? ' checked="checked"' : '';
				this.$el.find('.hb-activate-integration').html([
			      '<input type="checkbox" id="hb-cb-integrate" class="hb-activate-integration-cb"' + html + ' />',
			      '<label for="hb-cb-integrate">Activate/Deactivate</label>'
				].join(''));
			},

			renderNativeSelector: function() {
				var nativeSelector = this.model.get('nativeSelector');
				this.$el.find('.hb-jq-selector-ui').html([
					'<input id="hb-jq-selector" type="text" class="validate" placeholder="jQuery selector" value="' + nativeSelector + '">'					
				].join(''));				
			},

			initialize: function() {
				this.renderActivation();
				this.renderNativeSelector();
				this.model.on('change:isNativeIntegration', _.bind(this.renderActivation, this));
				this.model.on('change:nativeSelector', _.bind(this.renderNativeSelector, this));				
			},

			checkActivateHandler: function(event) {
		        var target = jQuery(event.target);
		        var selected = target.is(':checked');
		        this.model.set('isNativeIntegration', selected);
		        this.model.saveData();
			},			

			saveSettings: function() {
				this.model.set('nativeSelector', this.$el.find('#hb-jq-selector').val());
				this.model.saveData();
			}
		});
		return new view(options);
	}	


};