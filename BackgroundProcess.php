<?php
// https://florian.ec/articles/running-background-processes-in-php/
class BackgroundProcess {
	private $command;
	private $pid;

	public function __construct($command) {
		$this->command = $command;
	}

	public function run($outputFile = '/dev/null') {
		$this->pid = shell_exec(sprintf(
			"%s > %s 2>&1 & echo $!",  // 'echo $!' return the pid 
									// WILL NOT WORK IN WINDOWS
			$this->command,
			$outputFile
		));
	}

	public function isRunning() {
		try {
			$result = shell_exec(sprintf('ps %d', $this->pid));
			print("pid=[$this->pid]\n$result");
			if (count(preg_split("/\n/", $result)) > 2) {
				return true;
			}
		} catch(Exception $ex) {}

		return false;	
	}

	public function getPid() {
		return $this->pid;
	}

	public function testrun($outputFile = '/dev/null') {
		$line = sprintf(
			'%s > %s 2>&1 & echo $!',  // 'echo $!' return the pid
			$this->command,
			$outputFile
		);

		print($line);
	}
}

//==============================================================================
// Test code
//==============================================================================
//$output = shell_exec('ls -lart');
//echo "<pre>$output</pre>";
$process = new BackgroundProcess('sleep 5');
$process->run();

echo sprintf('Crunching numbers in process %d', $process->getPid());
while ($process->isRunning()) {
	echo '.';
	sleep(1);
}
echo "\nDone.\n";

