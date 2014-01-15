<?php

namespace M2T\Commands;

use Symfony\Component\Console\Input\InputArgument;
use \Queue;

class QueueCollectStats extends BaseCommand {
	
	protected $name = 'm2t:queue_collect_stats';

	protected $description = 'Queues the m2t:stats command which requeues it 30 seconds after it completes';

	public function fire() {
		Queue::push("M2T\Queue\UpdateAllTorrents");
	}

	protected function getArguments() {
		return array();
	}
	
}