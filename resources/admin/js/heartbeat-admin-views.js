'use strict';

var HeartBeatViews = {

	//index options view
	IndexOptions: function() {
		var view = Backbone.View.extend({

			events: {
				'click .indexDbBtn': 'indexDbHandler'
			},

			el: '#index-options',

			initialize: function() {
				console.log('view init');
			},

			render: function() {
				console.log('render');
				console.log(this.$el);
			},

			indexDbHandler: function(event) {
				event.preventDefault();
				HeartBeatAdmin.createNewDBIndex();
			}
		});
		return new view();
	}
};