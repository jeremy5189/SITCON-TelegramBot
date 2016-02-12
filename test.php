<?php

$regex = '/([^\\.#]|^)(s[Ii][Tt][Cc][Oo][Nn]|[sS]i[Tt][Cc][Oo][Nn]|[sS][Ii]t[Cc][Oo][Nn]|[sS][Ii][Tt]c[Oo][Nn]|[sS][Ii][Tt][Cc]o[Nn]|[sS][Ii][Tt][Cc][Oo]n)([^a-zA-Z.]|$)/';

$case = array(
	'SITCON' => 0,
	'sitcon' => 1,
	'sitCOn' => 1,
	'sitcon SITCON' => 1,
	'SITCON sitcon' => 1,
	'fjeaioewSITCONfajewi' => 0,
	'fjiawejfiawejsiconfajewiofjeiow' => 0,
	'今年sitcon也是要在irc的#sitcon上嗎？' => 1,
	'今年SITCON也是要在irc的#sitcon上嗎？' => 0,
);

foreach( $case as $message => $expect ) {
	echo $message . " : " . ( preg_match($regex, $message) == $expect ? 'pass' : 'fail' ) . "\n";
}
	
	
