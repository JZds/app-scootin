<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class MultiBotCommand extends Command
{
    protected function configure()
    {
        parent::configure();
        $this
            ->setName('multi:bot:execute')
            ->setDescription('Run multiple bots')
            ->addArgument('botCount', InputArgument::REQUIRED, 'Specify bot amount');
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
   {
       $colors = ['red', 'green', 'yellow', 'blue', 'magenta', 'cyan', 'white'];
       $runningProcesses = [];
       for ($i = 0; $i < $input->getArgument('botCount'); $i++) {
           $process = new Process(['bin/console', 'bot:execute'], getcwd());
           $process->start();
           $runningProcesses[$colors[array_rand($colors)]] = $process;
       }

       while (true) {
           /** @var Process $runningProcess */
           foreach ($runningProcesses as $color => $runningProcess) {
               $output->write(PHP_EOL . '<fg=' . $color . '>' . $runningProcess->getOutput() . '</>');
               if (!$runningProcess->isRunning()) {
                   $runningProcess->start();
               }
               sleep(1);
           }
       }

       return Command::SUCCESS;
   }
}
