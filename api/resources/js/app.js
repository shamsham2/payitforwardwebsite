
require('./bootstrap');
window.Vue = require('vue');

import SuiVue from 'semantic-ui-vue'; // https://semantic-ui-vue.github.io/#/
Vue.use(SuiVue);

import Vue from 'vue'



window.addEventListener('load', function () { // felix added this event as was error before as dom not loaded 
	const app = new Vue({
		el: '#app',

		data() {
			return {
				message: "hello",
				categories: [],
				facts: [],
				token: new URL(document.location.href).searchParams.get("token"),
				apiUrl:"https://api.mediafact.co.uk",// 
				apiUrlRemote:"https://api.mediafact.co.uk",// 
				apiUrlLocal: "http://www.local.mediafact.co.uk", // change this depending on env
				kioskApiToken: 'SdfUw4rh8h5hsusdfh334',
				selectedCategory:[],
				selectedFact:[]
		
			}
		},


		components: {
			
		},


		mounted() {
			console.log(window.location.href);
			if(window.location.href.includes('local')){this.apiUrl = this.apiUrlLocal }

			this.getCategoryData();
			//this.setNewFact();
			console.log(222222)
		},


		methods: {
			categoryClick: function (category){
				//alert(category);
				this.selectedCategory = category;
				this.getFactData(category.id);
				this.setNewFact();
			},
			factClicked: function (fact){
				this.selectedFact = fact;
				//alert(fact.title);
			},

			selectLinkMenuItem: function(menuItemName){
				if(menuItemName == this.openLinkMenuItemSelected){
					//this.openLinkMenuItemSelected ='none';
				}else{
					this.openLinkMenuItemSelected = menuItemName;	
				}
			},

			selectMainMenuItem: function(menuItemName){
				if(menuItemName == this.openMainMenuItemSelected){
					//this.openMainMenuItemSelected ='none';
				}else{
					this.openMainMenuItemSelected = menuItemName;	
				}
			},



			logOut: function (page) {
				window.location = this.apiUrl+"/kiosk-admin-logout-do?token=" + this.token;
			},



			searchForTargetUrl: function (targetUrl) {
				let self = this
				if(targetUrl.length > 4){this.target_url = targetUrl;}
				if (this.target_url == '') { alert('please add a url and then try again'); return; }

				axios.get(this.apiUrl+"/admin-data-search-link?target_url=" + encodeURIComponent(this.target_url) + "&token=" + this.token)
					.then(response => {
						console.log(response.data.status);
						if (response.data.status == 'fail') {
							alert('could not find link, try another or make sure your session has not expired.')
						} else {

						}
					}).catch(function (error) {
						alert('could not find link, try another or make sure your session has not expired');
					});;

			},



			editLink: function (targetUrl) {
				this.searchForTargetUrl(targetUrl)
				window.location.href = "#place-edit-link";
			},



			//********************* */
			setNewFact: function () {
				this.selectedFact.id = 0;	
				this.selectedFact.title = "";
				this.selectedFact.key_points = "";
				this.selectedFact.summary = "";
				this.selectedFact.media_type = "";
				this.selectedFact.url = "";	
				this.selectedFact.tags = "";
				console.log(this.selectedFact);	
			},


			createFact: function () {
				let self = this

				axios.post(this.apiUrl+'/admin/create-fact', {
					category_id: this.selectedCategory.id,
					title: this.selectedFact.title, 
					key_points:this.selectedFact.key_points, 
					summary:this.selectedFact.summary, 
					media_type:this.selectedFact.media_type,
					url:this.selectedFact.url,
					tags:this.selectedFact.tags,
					token: this.token
				}).then(function (response) {
					alert("hi five!");
					self.getFactData(self.selectedCategory.id);
				}).catch(function (error) {
					console.log(error);
					alert('something went a wee bit wrong');
				});
			},
			
			updateFact: function () {
				let self = this
				if(this.selectedFact.id == 0){ 
					this.createFact(); 
					this.setNewFact();
					this.getFactData(this.selectedCategory.id);
				}else{

					axios.post(this.apiUrl+'/admin/update-fact', {
						id: this.selectedFact.id, 
						title: this.selectedFact.title, 
						key_points:this.selectedFact.key_points, 
						summary:this.selectedFact.summary, 
						media_type:this.selectedFact.media_type,
						url:this.selectedFact.url,
						tags:this.selectedFact.tags,
						token: this.token
					}).then(function (response) {
						alert(response.data.message);
					}).catch(function (error) {
						console.log(error);
						//alert('something went a wee bit wrong');
					});

				}

			},

			deleteFact: function () {
				let self = this
				if(!confirm("are you sure you want to do delete this")){return ;}

				axios.post(this.apiUrl+'/admin/delete-fact', {
					id: this.selectedFact.id, 
					token: this.token
				}).then(function (response) {
					alert(response.data.message);
					self.setNewFact();
					self.getFactData(self.selectedCategory.id);
				}).catch(function (error) {
					console.log(error);
					alert('something went a wee bit wrong');
				});

			},

			//*************************** */



			validURL: function (str) {
				var res = str.match(/(http(s)?:\/\/.)?(www\.)?[-a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_\+.~#?&//=]*)/g);
				return (res !== null)
			},
			viewLink: function () {
				var encodedUrl = encodeURIComponent(this.resolver_link);
				var url = this.apiUrl+'/serve-page?api_token=' + this.kioskApiToken + '&url=' + encodedUrl;
				window.open(url, '_blank', 'location=yes,height=570,width=520,scrollbars=yes,status=yes');
			},




			/***********/
			/***********/
			getCategoryData: function () {
				axios.get(this.apiUrl+"/admin/contexts?token="+this.token)
					.then(response => {
						console.log(response.data[0]);
						this.categories = response.data;
						
					});
			},

			getFactData: function (context) {
				axios.get(this.apiUrl+"/admin/facts/"+context+"?token="+this.token)
					.then(response => {
						console.log(response.data);
						this.facts = response.data;
					});
			},




			openPdf: function (fileName) {
				window.open(this.apiUrl+'/get-pdf?file_name='+fileName+'.pdf&token='+this.token)
			},

			downloadCSV: function (fileName) {
				window.open(this.apiUrl+'/download-csv?report_name='+fileName+'&token='+this.token)
			},


		}

	});
});

