<?php
	function wash($string) {
		$string = str_replace('>', '&gt;', $string);
		$string = str_replace('<', '&lt;', $string);
		$string = str_replace("\n", '<br>', $string);
		return $string;
	}
	
	function http_error($code, $status, $message) {
		header("HTTP/1.1 $code $status");
		header("Content-type: text/plain");
		die($message);
	}
	
	$tweetsFile = 'txt/tweets.txt';
	
	if (isset($_REQUEST['action'])) {
		$action = $_REQUEST['action'];
		switch ($action) {
			case 'add': 
				if (!isset($_REQUEST['tweet'])) {
					http_error(400, 'Invalid Request', 'must specify a tweet');
				}
				$tweetsFileContents = file_get_contents($tweetsFile);
				if (isset($_REQUEST['index'])) {
					if (!is_numeric($_REQUEST['index'])) {
						http_error(400, 'Invalid Request', 'index, if specified, must be a numeric value');
					}
					$tweets = explode("\n\n", $tweetsFileContents);
					array_splice($tweets, $_REQUEST['index'], 0, $_REQUEST['tweet']);
					file_put_contents($tweetsFile, implode("\n\n", $tweets));
				} else {
					file_put_contents($tweetsFile, $tweetsFileContents . "\n\n" . $_REQUEST['tweet']);
				}
				break;
			
			case 'shift':
				if (!isset($_REQUEST['from'])) {
					http_error(400, 'Invalid Request', 'must specify what index to shift');
				} else if (!isset($_REQUEST['to'])) {
					http_error(400, 'Invalid Request', 'must specify where to shift to');
				}
				$tweetsFileContents = file_get_contents($tweetsFile);
				$tweets = explode("\n\n", $tweetsFileContents);
				$temp = array_splice($tweets, intval($_REQUEST['from']), 1);
				array_splice($tweets, intval($_REQUEST['to']), 0, $temp);
				file_put_contents($tweetsFile, implode("\n\n", $tweets));
				break;
			
			case 'delete':
				if (!isset($_REQUEST['index'])) {
					http_error(400, 'Invalid Request', 'must specify what to delete');
				}
				$tweetsFileContents = file_get_contents($tweetsFile);
				$tweets = explode("\n\n", $tweetsFileContents);
				array_splice($tweets, $_REQUEST['index'], 1);
				file_put_contents($tweetsFile, implode("\n\n", $tweets));
				break;
		}
	} else {
		$tweets = explode("\n\n", file_get_contents('txt/tweets.txt'));
?>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="css/styles.css">
		<script src="js/jquery-2.0.3.min.js"></script>
		<script src="js/script.js"></script>
	</head>

	<body>
		<h2>control panel</h2>
		<p class="info">tweets in queue: <span class="queueCount"><?= count($tweets) ?></span></p>
		<div class="inputContainerDiv">
			<textarea cols="60" rows="6" class="inputArea"></textarea>
			<br>
			<button type="button" class="addToQueue">add to queue at</button>
			<input type="text" class="queueIndex" min="1" placeholder="<?= count($tweets) + 1 ?>">
			<button type="button" class="publishNow">publish now</button>
			<input type="text" class="charCount" name="charCount" disabled placeholder="0">
		</div>
		<hr>
		<h3>tweet queue</h3>
		<div class="tweetQueue">
			<?php for ($i = 0; $i < count($tweets); $i++) { ?>
				<div class="tweet">
					<p class="tweetNo"><?= $i + 1 ?></p>
					<p class="tweetText"><?= wash($tweets[$i]) ?></p>
					<button type="button" class="publishFromQueue">publish now</button>
					<button type="button" class="deleteFromQueue">delete from queue</button>
					<button type="button" class="shiftTo">shift to:</button>
					<input type="text" class="shiftNumber" placeholder="1">
				</div>
				<hr>
			<?php } ?>
		</div>
	</body>
</html>
<?php 
}
?>