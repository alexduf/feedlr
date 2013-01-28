function Feedlr() {
	
	function request(resource, data) {
	
		var url = "http://localhost/api/index.php?resource=";
		var user = 'test';
		var password = 'test';
		
		// TODO use a less mozilla specific here
		var auth = 'Basic ' + btoa(user + ':' + password);
	
		var request = new XMLHttpRequest();
		request.open("GET", url + resource, false);
		request.setRequestHeader('Authorization', auth);
		request.send();
	
		var data = {};
		var text = request.responseText;
		if (text != null && text != '') {
			var data = JSON.parse(request.responseText); 
		}
		
		return {
			status: request.status,
			data: data
			};
	
	}
	
	function isLogged() {
		var ret = request("ping");
		if (ret.status == 200) {
			return true;
		} else {
			return false;
		}
	}
} 