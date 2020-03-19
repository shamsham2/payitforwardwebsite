<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <title>Mediafact Admin</title>
    <link rel="stylesheet" href="js/semantic/dist/semantic.min.css">
    <link rel="stylesheet" href="css/app.css">
    <script type = "text/javascript" src = "js/app.js" ></script> 
    <script src="js/semantic/dist/semantic.min.js"></script>
	<meta name="viewport" content="width=device-width, maximum-scale=1, user-scalable=no" />
</head>

<body>
<div id='app'>
<div  api-url="http://local.mediafact.co.uk" ></div>

@verbatim

	<div class="header_container">
		
		<div class="" style="float:left; padding:20px; width: 100%; font-size:large; color:white;">ADMIN</div>
		
		<div class="logo" >		
			<div class="ui basic grey button" style="float:right; margin-top:22px; color:white;" v-on:click="logOut()">Sign Out</div>
		</div>
		
	</div>

		
	<div class="main_container">


			<h1>Topics</h1>
			<div class="ui segments " style="height:200px; overflow-y:scroll;"> 
				<div class="ui joined segment hover-background" v-for="category in categories" v-on:click="categoryClick(category)" >	
						{{category.title}}
				</div>
			</div>
						


			<h1>Facts</h1>
			<h3>{{selectedCategory.title}}</h3>
			<div class="ui segments " style="height:400px; overflow-y:scroll;"> 
				<div class="ui joined segment hover-background" v-for="fact in facts" v-on:click="factClicked(fact)" >	
						{{fact.title}}
				</div>
			</div>

			

			




			<div v-if="selectedFact.id > 0" class="ui button" v-on:click="setNewFact()" v-if="selectedCategory.title" >Create New Fact</div>
			
</br>
</br>
<h3>{{selectedFact.title}}</h3>

			<div class="ui segment" v-if="selectedCategory.id" >
				fact id: {{selectedFact.id}} </br></br>
				<div class="ui form">

					<div class="field">
						<label>Title</label>
						<input type="text" name="title" placeholder="Title" v-model="selectedFact.title" size="60">
					</div>

					<div class="field">
						<label>Key Points</label>
						<textarea v-model="selectedFact.key_points" ></textarea>
					</div>

					<div class="field">
						<label>Summary</label>
						<textarea v-model="selectedFact.summary"></textarea>
					</div>

					<div class="field">
						<label>Link</label>
						<input type="text" name="link" placeholder="Link" v-model="selectedFact.url" size="60">
					</div>

					<div class="field">
						<label>Tags (comma,seperated,please)</label>
						<textarea v-model="selectedFact.tags" rows="3" ></textarea>
					</div>

					<!--
					<select>
						<option value="news">news</option>
						<option value="video">video</option>
						<option value="data">data</option>
						<option value="social_media">social media</option>
					</select> 
					-->

					</br></br>
					<div v-if="selectedFact.id > 0" class="ui  red button" v-on:click="deleteFact()">delete</div>
					<div v-if="selectedFact.id < 1"class="ui  green button" v-on:click="updateFact()">Create</div>
					<div v-if="selectedFact.id > 0" class="ui  green button" v-on:click="updateFact()">Save</div>
				</div>

			</div>


			</br></br>




	</div>



	
</br></br></br>
</div>
</div>
@endverbatim
</body>
</html>
