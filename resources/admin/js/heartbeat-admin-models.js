'use strict';

var HeartBeatModels = {

	//index options view
	IndexModel: function(data) {
		var model = Backbone.Model.extend({

			defaults: {
				timestamp: null,
				hash: null,
				availableTerms: null,
				terms: []
			},

			initialize: function() {
			},

			fetchMeta: function() {
				HeartBeatAdmin.getIndexMeta(_.bind(function(err, result) {
					this.set('formatedDate', result.formatedDate);
					this.set('timestamp', result.timestamp);
				}, this));				
			},

			fetchSearchTerms: function() {
				HeartBeatAdmin.fetchSearchTerms(_.bind(function(err, result) {
					this.set('terms', result.terms);
					this.set('availableTerms', result.availableTerms);		
				}, this));				
			},

			//check if term is within selected array
			isTermInTerms: function(termKey) {
				var out = false;
				var terms = this.get('terms');
				if (terms && _.isArray(terms)) {
					for (var i = 0; i < terms.length; i++) {
						if (terms[i].key == termKey) {
							out = true;
							break;
						}
					}
				}
				return out;
			},

			//add term
			addTerm: function(term) {
				var terms = this.get('terms');
				terms.push(term);
				this.set('terms', terms);
				this.serverUpdateTerms();
			},

			//remove term
			removeTerm: function(term) {
				var terms = this.get('terms');
				for (var i = 0; i < terms.length; i++) {
					if (terms[i].key == term.key) {
						terms.splice(i, 1);
						this.set('terms', terms);
						this.serverUpdateTerms();
						break;
					}
				}
			},

			//update terms on server
			serverUpdateTerms: function() {
				HeartBeatAdmin.updateSearchTerms(this.get('terms'), _.bind(function(err, result) {
					Materialize.toast('Saved!', 3000);					
				}, this));
			},

			//add or remove term
			syncTerm: function(termKey, termLabel, selected) {
				var termExists = this.isTermInTerms(termKey);				
				if (selected) {
					//add term
					if (!termExists) {
						this.addTerm({key: termKey, label: termLabel});
					}
				} else {
					//remove term
					if (termExists) {
						this.removeTerm({key: termKey, label: termLabel});
					}
				}
			}		
		});
		return new model(data);
	}
};