<?php

	// make an associative array of senders we know, indexed by phone number
	$people = array(
		"+18162000044"=>"Sarah W",
		"+18162000144"=>"GGCS",
		"+18168638838"=>"Kelsey K",
		"+13163049410"=>"Sam B",
    "+16609094530"=>"Chris W",
    "+19134862938"=>"Erin M"
	);

	// if the sender is known, then greet them by name
	// otherwise, consider them just another monkey
	if(!$name = $people[$_REQUEST['From']]) {
		$name = "friend";
	}

	// now greet the sender
	header("content-type: text/xml");
	echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
?>
<Response>
	<Sms><?php echo $name ?>, thanks for testing this for me!</Sms>
</Response>
