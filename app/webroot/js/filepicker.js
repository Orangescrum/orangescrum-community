/**!
 * Google Drive File Picker Example
 * By Daniel Lo Nigro (http://dan.cx/)
 */
(function() {
	/**
	 * Initialise a Google Driver file picker
	 */
	var FilePicker = window.FilePicker = function(options) {
		// Config
		this.apiKey = options.apiKey;
		this.clientId = options.clientId;
		
		// Elements
		this.buttonEl = options.buttonEl;
		
		// Events
		this.onSelect = options.onSelect;
		this.buttonEl.addEventListener('click', this.open.bind(this));		
	
		// Disable the button until the API loads, as it won't work properly until then.
		this.buttonEl.disabled = true;

		// Load the drive API
		gapi.client.setApiKey(this.apiKey);
		gapi.client.load('drive', 'v2', this._driveApiLoaded.bind(this));
		google.load('picker', '1', { callback: this._pickerApiLoaded.bind(this) });
	}

	FilePicker.prototype = {
		/**
		 * Open the file picker.
		 */
		open: function() {
			// Check if the user has already authenticated
			var token = gapi.auth.getToken();
			if (token) {
				this._showPicker();
			} else {
				// The user has not yet authenticated with Google
				// We need to do the authentication before displaying the Drive picker.
				this._doAuth(false, function() { this._showPicker(); }.bind(this));
			}
		},
		
		/**
		 * Show the file picker once authentication has been done.
		 * @private
		 */
		_showPicker: function() {
			var accessToken = gapi.auth.getToken().access_token;
			this.picker = new google.picker.PickerBuilder().
					enableFeature(google.picker.Feature.NAV_HIDDEN).
					enableFeature(google.picker.Feature.MULTISELECT_ENABLED).
					addViewGroup(
					    new google.picker.ViewGroup(google.picker.ViewId.DOCS).
					    addView(google.picker.ViewId.DOCUMENTS).
					    addView(google.picker.ViewId.PRESENTATIONS).
					    addView(new google.picker.DocsUploadView())
					).
					setAppId(this.clientId).
					setOAuthToken(accessToken).
					setCallback(this._pickerCallback.bind(this)).
					build().
					setVisible(true);
		},
		
		/**
		 * Called when a file has been selected in the Google Drive file picker.
		 * @private
		 */
		_pickerCallback: function(data) {
			if (data[google.picker.Response.ACTION] == google.picker.Action.PICKED) {
			    var myToken = gapi.auth.getToken();
			    var body = {'value': '','type': 'anyone','role': 'reader'};
			    var fileId;
			    
			    for(var i in data.docs){
				fileId = data.docs[i].id;

				//Set public permission.
				request = gapi.client.drive.permissions.insert({
				  'fileId': fileId,
				  'resource': body
				});
				request.execute(function(resp) { 
				    //console.log(resp);
				});

				//Get file details
				request = gapi.client.drive.files.get({
				    fileId: fileId,
				    Authorization: 'Bearer ' + myToken.access_token
				});
				request.execute(this._fileGetCallback.bind(this),function(resp) { 
				    console.log(resp);
				});
			    }
			}
		},
		/**
		 * Called when file details have been retrieved from Google Drive.
		 * @private
		 */
		_fileGetCallback: function(file) {
			if (this.onSelect) {
				this.onSelect(file);
			}
		},
		
		/**
		 * Called when the Google Drive file picker API has finished loading.
		 * @private
		 */
		_pickerApiLoaded: function() {
			this.buttonEl.disabled = false;
		},
		
		/**
		 * Called when the Google Drive API has finished loading.
		 * @private
		 */
		_driveApiLoaded: function() {
			this._doAuth(true);
		},
		
		/**
		 * Authenticate with Google Drive via the Google JavaScript API.
		 * @private
		 */
		_doAuth: function(immediate, callback) {	
			gapi.auth.authorize({
				client_id: this.clientId + '.apps.googleusercontent.com',
				scope: 'https://www.googleapis.com/auth/drive',
				immediate: immediate,
				approval_prompt:'force'
			}, callback);
		}
	};
}());