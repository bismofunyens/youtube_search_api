<?php
	session_start();
	require_once 'GoogleAPI/vendor/autoload.php';
	require_once ($_SERVER["DOCUMENT_ROOT"].'/keyword_search/API/src/Google_Client.php');
  require_once ($_SERVER["DOCUMENT_ROOT"].'/keyword_search/API/src/contrib/Google_YouTubeService.php');
	if (!isset($_SESSION['access_token'])) {
		header('Location: login.php');
		exit();
	}
	if (!file_exists('GoogleAPI/vendor/autoload.php')) {
		throw new \Exception('please run "composer require google/apiclient:~2.0" in "' . __DIR__ .'"');
	  }
	if(isset($_GET['q'])) {
		$DEVELOPER_KEY = 'AIzaSyAD8yXSRHZGkKpkoedSewjWSTZDDCWqhlo';

		$client = new Google_Client();
		$client->setDeveloperKey($DEVELOPER_KEY);
	  
		$youtube = new Google_YoutubeService($client);
	  
		try {
		  
		  $searchResponse = $youtube->search->listSearch('snippet', 
		  array('maxResults' => 25, 'q' => $_GET['q'], 'type' => 'video'));
	  
		  $videos = '';
	  
	  
		  foreach ($searchResponse['items'] as $searchResult) {
			switch ($searchResult['id']['kind']) {
			  case 'youtube#video':
				$videos .= sprintf('<li>%s (%s)</li>', $searchResult['snippet']['title'],
				  $searchResult['id']['videoId']."<img src=https://img.youtube.com/vi/".$searchResult['id']['videoId']."/hqdefault.jpg>");
				break;
			  case 'youtube#channel':
				$channels .= sprintf('<li>%s (%s)</li>', $searchResult['snippet']['title'],
				  $searchResult['id']['channelId']);
				break;
			 }
		  }
	  
		 } catch (Google_ServiceException $e) {
		  $htmlBody .= sprintf('<p>A service error occurred: <code>%s</code></p>',
			htmlspecialchars($e->getMessage()));
		} catch (Google_Exception $e) {
		  $htmlBody .= sprintf('<p>An client error occurred: <code>%s</code></p>',
			htmlspecialchars($e->getMessage()));
		}
	  }
	
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport"
	      content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Login With Google</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">
	<link rel="stylesheet" href='css/style.css'>
</head>
<body>
<div class="container" style="margin-top: 100px">
	<div class="row">
		<div class="col-md-3">
			<img style="width: 100%;" src="<?php echo $_SESSION['picture'] ?>">
			<a href='logout.php'><input type="submit" style="width: 100%;" value="Log Out" class="btn btn-danger"></a>
		</div>

		<div class="col-md-5">
			<table class="table table-hover table-bordered">
				<tbody>
					<tr>
						<td>First Name</td>
						<td><?php echo $_SESSION['givenName'] ?></td>
					</tr>
					<tr>
						<td>Last Name</td>
						<td><?php echo $_SESSION['familyName'] ?></td>
					</tr>
					<tr>
						<td>Email</td>
						<td><?php echo $_SESSION['email'] ?></td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
	<h2>Search Live Stream</h2>
        <div class="search-form-container">
            <form id="keywordForm" method="GET" action="">
                <div class="input-row">
                    Search Keyword : <input class="input-field" type="search" id="q" name="q"  placeholder="Enter Search Keyword">
                </div>

                <input class="btn-submit"  type="submit" name="submit" value="Search">
			</form>
        </div>
        <?php

					echo $videos;

					?>
</div>
</body>
</html>