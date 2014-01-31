$(document).ready(function() {
	GLOBAL_RELOAD = true;

	$('.inputArea').bind('input propertychange', function(e) {
		$('.charCount').val($(this).val().length);
	});
	$('.addToQueue').click(function(e) {
		if ($('.inputArea').val().length != 0) {
			if ($('.queueIndex').val().length != 0) {
				$.ajax('index.php', {
					data: {
						action: 'add',
						tweet: $('.inputArea').val(),
						index: parseInt($('.queueIndex').val()) - 1
					},
					success: reload,
					error: ajaxError
				});
			} else {
				$.ajax('index.php', {
					data: {
						action: 'add',
						tweet: $('.inputArea').val()
					},
					success: reload,
					error: ajaxError
				});
			}
		}
	});
	$('.shiftTo').click(function(e) {
		$this = $(this);
		$.ajax('index.php', {
			data: {
				action: 'shift',
				from: parseInt($this.siblings('.tweetNo').text()) - 1,
				to: ($this.next().val().length == 0 ? 0 : parseInt($this.next().val()) - 1)
			},
			success: reload,
			error: ajaxError
		});
	});
	
	$('.deleteFromQueue').click(function(e) {
		$this = $(this);
		$.ajax('index.php', {
			data: {
				action: 'delete',
				index: parseInt($this.siblings('.tweetNo').text()) - 1
			},
			success: reload,
			error: ajaxError
		});
	});
	
	$('.publishFromQueue').click(function(e) {
		$this = $(this);
		$.ajax('php/postTweet.php', {
			data: {
				action: 'publishAtIndex',
				index: parseInt($this.siblings('.tweetNo').text()) - 1
			},
			success: reload,
			error: ajaxError
		});
	});
	
	$('.publishNow').click(function(e) {
		$.ajax('index.php', {
			data: {
				action: 'add',
				tweet: $('.inputArea').val()
			},
			success: function() {
				$.ajax('php/postTweet.php', {
					data: {
						action: 'publishAtIndex',
						index: parseInt($('.queueCount').text())
					},
					success: reload,
					error: ajaxError
				});
			},
			error: ajaxError
		});
	});
	
	function reload() {
		if (GLOBAL_RELOAD) {
			location.reload();
		}
	}
	
	function ajaxError(jqxhr, type, error) {
		var msg = "An Ajax error occurred!\n\n";
		if (type == 'error') {
			if (jqxhr.readyState == 0) {
				// Request was never made - security block?
				msg += "Looks like the browser security-blocked the request.";
			} else {
				// Probably an HTTP error.
				msg += 'Error code: ' + jqxhr.status + "\n" + 
							 'Error text: ' + error + "\n" + 
							 'Full content of response: \n\n' + jqxhr.responseText;
			}
		} else {
			msg += 'Error type: ' + type;
			if (error != "") {
				msg += "\nError text: " + error;
			}
		}
		alert(msg);
	}
});