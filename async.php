<?php
//https://medium.com/async-php/co-operative-php-multitasking-ce4ef52858a0#.594jzvt9z

	$array = ["foo", "bar", "baz"];
	 
	foreach ($array as $key => $value) {
	    print "item: " . $key . "|" . $value . "\n";
	}
	 
	for ($i = 0; $i < count($array); $i++) {
	    print "item: " . $i . "|" . $array[$i] . "\n";
	}

	print is_array($array) ? "yes\n" : "no\n"; // yes

	$document = new DOMDocument();
	$document->loadXML("<div></div>");
	$elements = $document->getElementsByTagName("div");
	print_r($elements); // DOMNodeList Object ( [length] => 1 )
	print ($elements instanceof Traversable) ? "yes\n" : "no\n"; // yes

//	$content = file_get_contents(__FILE__);
//	$lines = explode("\n", $content);
//	foreach ($lines as $i => $line) {
//	    print $i . ". " . $line . "\n";
//	}
	
	// Generator !!!
//	function lines($file) {
//	    $handle = fopen($file, "r");
// 
//		while (!feof($handle)) {
//			yield trim(fgets($handle));
//		}
// 
//		fclose($handle);
//	}
// 
//	foreach (lines(__FILE__) as $i => $line) {
//	    print $i . ". " . $line . "\n";
//	}
 	
//	$generator = call_user_func(function() {
//		yield "foo";
//	});
// 
//	print $generator->current() . "\n"; // foo

//	$generator = call_user_func(function() {
//	    $input = (yield "foo");
//	    print "inside: " . $input . "\n";
//	});
// 
//	print $generator->current() . "\n";
//	 
//	$generator->send("bar");

	$multiply = function ($x, $y) {
	    try {
	        yield $x * $y;
	    } catch (InvalidArgumentException $exception) {
	        print "ERRORS!\n\n";
	    }
	};
	 
	$calculate = function ($op, $x, $y) use ($multiply) {
	    if ($op === "multiply") {
	        $generator = $multiply($x, $y);
	 
	        if (!is_numeric($x) || !is_numeric($y)) {
	            $generator->throw(new InvalidArgumentException());
	        }
	 
	        return $generator->current();
	    }
	};

	print $calculate("multiply", 5, "foo");

	//=====================================
	// COROUTINES !!!
	//=====================================
	class Task
	{
		protected $generator;
		protected $run = false;
 
    	public function __construct(Generator $generator)
    	{
    	    $this->generator = $generator;
    	}
 
    	public function run()
    	{
			if($this->run) {
				$this->generator->next();
			} else {
				$this->generator->current();
			}	
			$this->run = true;
    	}
 
    	public function finished()
    	{
    	    return !$this->generator->valid();
    	}
	}

	class Scheduler
	{
	    protected $queue;
	 
	    public function __construct()
	    {
	        $this->queue = new SplQueue();
	    }
	 
	    public function enqueue(Task $task)
	    {
	        $this->queue->enqueue($task);
	    }
	 
	    public function run()
	    {
	        while (!$this->queue->isEmpty()) {
	            $task = $this->queue->dequeue();
	            $task->run();
	 
	            if (!$task->finished()) {
	                $this->enqueue($task);
	            }
	        }
	    }
	}
	
	$scheduler = new Scheduler();
	 
	$task1 = new Task(call_user_func(function() {
	    for ($i = 0; $i < 3; $i++) {
	        print "task 1: " . $i . "\n";
	        yield;
	    }
	}));
	 
	$task2 = new Task(call_user_func(function() {
	    for ($i = 0; $i < 6; $i++) {
	        print "task 2: " . $i . "\n";
	        yield;
	    }
	}));
	 
	$scheduler->enqueue($task1);
	$scheduler->enqueue($task2);
	 
	$scheduler->run();
?>
