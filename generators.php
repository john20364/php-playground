<?php
//	// Generator
//	function xrange($start, $end, $step = 1) {
//		for ($i = $start; $i <= $end; $i += $step) {
//			yield $i;
//		}
//	}
//
//$range = xrange(1, 1000000);
//var_dump($range);
//var_dump($range instanceof Iterator);

//	foreach (xrange(1, 1000000) as $num) {
//		echo $num, "\n";
//	}

//function logger($filename) {
//	$filehandle = fopen($filename, 'a');
//	while (true) {
//		fwrite($filehandle, yield . "\n");
//	}
//}
//
//$logger = logger(__DIR__ . '/log');
//$logger->send("Foo");
//$logger->send("Bar");

function gen() {
	$ret = (yield 'yield1');
	var_dump($ret);
	$ret = (yield 'yield1');
	var_dump($ret);
}

$gen = gen();

var_dump($gen->current());
var_dump($gen->send('ret1'));
var_dump($gen->send('ret2'));
?>
