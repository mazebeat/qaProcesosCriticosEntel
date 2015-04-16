<!DOCTYPE html>
<html lang="en">
<head>
	<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css" rel="stylesheet">
	<title>My Blog</title>
</head>
<body ng-app="kettle">
<div class="container">
	<div class="col-md-6 col-md-offset-3" ng-controller="postController" ng-init="getPosts()">

		<h2>Posts</h2>

		<div class="list-group">
			<a href="#" class="list-group-item" ng-repeat="post in posts">
				<h4 class="list-group-item-heading">[[ post.title ]]</h4>

				<p class="list-group-item-text">[[ post.content ]]</p>
			</a>
		</div>

		<div>
			<posts-pagination></posts-pagination>
		</div>

	</div>
</div>
<body>
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.3.2/angular.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.3.2/angular-resource.min.js"></script>
<script src="js/app.js"></script>
</body>
</html>