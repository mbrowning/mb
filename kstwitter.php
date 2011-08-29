<?php
require('simplepie.inc');
function kstwitter() {

  $kstwitfeed= new SimplePie;
  $kstwitfeed->enable_cache(false);
  $kstwitfeed->set_feed_url('http://twitter.com/statuses/user_timeline/220906762.rss');
  $kstwitfeed->handle_content_type();
  $kstwitfeed->force_fsockopen();
  $kstwitfeed->init();

  if($kstwitfeed->error()) {
  /*If there's an error message, spit it out and stop*/
  print "<p class=\"tweet\">".$kstwitfeed->error()."</p>
  <p class=\"tweet-info\">
  <strong>We are doomed.</strong>
  Appease the Twitter gods.</p>";
	}
	
	else { 
	/*Otherwise, attempt to get the tweet, etc.*/  
	/*Get some stuff, but not @replies */
		//for($i=0; $i < 5; $i++) {
		$i = 0;
		$ksgotone = 'no';
	while($ksgotone=='no') {
	  if($item=$kstwitfeed->get_item($i)) {
		$tweet = substr($item->get_title(), 13);
		$tweet = substr(addslashes(html_entity_decode($item->get_title())), 13);
		$tweetdate = $item->get_date('F j');
		$tweetday = $item->get_date('j');
		$ksd = date('j');
		  if ($tweetday==$ksd) { $tweetdate = "today"; }
		  else if ($tweetday==($ksd-1)) { $tweetdate = "yesterday"; }
		  else { $tweetdate = "On " . $tweetdate; }
		$tweettime = $item->get_date('g\:i a');
		$ksgotone = 'yes';
	  }
	  else {
		$tweet = "Epic Twitter Fail.";
		$tweetdate = "Angry Twitter Gods";
		$tweettime = "this moment<!--".$i."-->";
		$ksgotone = 'yes';
	  }
	}
	$twsearch = array(
	  '%((www\.|(http|https)+\:\/\/)[_.a-zA-Z0-9-]+\.[a-zA-Z0-9\/_:@=.+?,##\%&~-]*[^.|\'|\# |!|\(|?|,| |>|<|;|\)])%',
	  '|@([\w_]+)|',
	  '|#([\w_]+)|'
	);
	$twreplace = array(
	  '<a href="$1">$1</a>',
	  '<a href="http://twitter.com/$1">@$1</a>',
	  '<a href="http://twitter.com/search?q=%23$1">#$1</a>'
	);
	$tweet = preg_replace($twsearch, $twreplace, $tweet);
	/*Print it out*/
	print "<p class=\"tweet\">".fancytext($tweet)."</p>
	  <p class=\"tweet-info\">— ".$tweetdate." at ".$tweettime."</a></p>";
	}
}

function fancytext($text) {
  $simpfound = array(' \\\'', '\\\'', ' \"', '\" ', '\"');
  //Fix them with
  $simpfixed = array(' ‘', '’', ' “', '” ', '“');
  $fancysafe = str_replace($simpfound, $simpfixed, $text);
  return $fancysafe;
}

?>