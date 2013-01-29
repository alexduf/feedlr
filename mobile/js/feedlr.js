// this object is a thin layer on top of Jquery, it's purpose is to 
// unify all the access to Feedlr's API
function FeedlrAPI(url, login, password) {
	this.url = url;
	this.login = login;
	this.password = password;

	// callback may contain success, error or complete attributes
	this.request = function(method, resource, data, callbacks) {

		// TODO get params from config
		var url = this.url;
		var login = this.login;
		var password = this.password;

		var xhr = $.ajax( {
			url : url + resource,
			data : {
				data : data
			},
			beforeSend : function(xhr, settings) {
				// TODO use a less mozilla specific function here
				var auth = 'Basic ' + btoa(login + ':' + password);
				xhr.setRequestHeader('Authorization', auth);
			},
			success : callbacks.success,
			error : callbacks.error,
			complete : callbacks.complete,
			type : method
		});

	}

	this.ping = function() {

		var callbacks = {
			error : function(xhr, response) {
				console.log('error :' + xhr);
				// TODO : show a disconnected icon somewhere
				// deactivate the whole API object
			}
		}

		var data = {
			ping : true
		};

		this.request('GET', "ping", data, callbacks);
	}
}

function FeedlrUI(feedlrAPI) {

	this.feedlrAPI = feedlrAPI;

	this.getList = function() {

		var callbacks = {
			success : function(data) {
				console.log(JSON.stringify(data));
				var postsUL = $('#postsUL');
				for (i=0; i<data.length; i++) {
					var li = $(document.createElement('li'));
					li.html(data[i].title);
					postsUL.append(li);
					
				}
			},
			error : function(xhr, response) {
				console.log('error :' + xhr);
				// TODO : show a disconnected icon somewhere
				// deactivate the whole API object
			}
		}

		this.feedlrAPI.request('GET', "list", null, callbacks);
	}
}

$(document).ready(
		function() {
			var feedlrAPI = new FeedlrAPI('http://localhost/api/index.php?resource=',
					'test', 'test');
			feedlrAPI.ping();
			
			var feedlrUI = new FeedlrUI(feedlrAPI);

			feedlrUI.getList();
		});