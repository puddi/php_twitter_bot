<?php
	$DELETE_AFTER_POST = true;
	
	function http_error($code, $status, $message) {
		header("HTTP/1.1 $code $status");
		header("Content-type: text/plain");
		die($message);
	}
	
	require_once('codebird.php');
	\Codebird\Codebird::setConsumerKey('YOUR CONSUMER KEY', 'YOUR PRIVATE CONSUMER KEY');

	$cb = \Codebird\Codebird::getInstance();

	$cb->setToken('YOUR TOKEN', 'YOUR PRIVATE TOKEN');
	
	$tweetsFile = '../txt/tweets.txt';
	$pastTweetsFile = '../txt/oldTweets.txt';
	$tweets = explode("\n\n", file_get_contents($tweetsFile));
	
	$tweet = NULL;
	
	if (isset($_REQUEST['action'])) {
		$action = $_REQUEST['action'];
		switch ($action) {
			case 'publishAtIndex':
				if (!isset($_REQUEST['index'])) {
					http_error(400, 'Invalid Request', 'must specify an index');
				}
				if (!is_numeric($_REQUEST['index'])) {
					http_error(400, 'Invalid Request', 'index, if specified, must be a numeric value');
				}
				$tweet = $tweets[intval($_REQUEST['index'])];
				break;
		}
	} else {
		for ($i = 0; $i < count($tweets); $i++) {
			if (strlen($tweets[$i]) < 140 && strlen($tweets[$i]) > 0) {
				$tweet = $tweets[$i];
				break;
			}
		}
	}
	if ($tweet != NULL) {
		$reply = $cb->statuses_update("status=" . $tweet);
		print_r($tweet);
		if ($DELETE_AFTER_POST) {
			file_put_contents($pastTweetsFile, file_get_contents($pastTweetsFile) . $tweet . "\n\n");
			unset($tweets[array_search($tweet, $tweets)]);
			file_put_contents($tweetsFile, implode("\n\n", $tweets));	
		}
	}
?>