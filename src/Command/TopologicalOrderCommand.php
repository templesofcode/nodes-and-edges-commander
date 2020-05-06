<?php


namespace TemplesOfCode\NodesAndEdges\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use TemplesOfCode\NodesAndEdges\DFS\TopologicalOrder;
use TemplesOfCode\NodesAndEdges\Digraph;

/**
 * Class TopologicalOrderCommand
 * @package TemplesOfCode\NodesAndEdges\Command
 */
class TopologicalOrderCommand extends Command
{
    /**
     * The name of the command (the part after "bin/console")
     *
     * @var string
     */
    protected static $defaultName = 'toc:nae:topological-order';

    /**
     *
     */
    protected function configure()
    {
        $this
            ->setDescription('Print the ordered vertices of the given draft')
            ->setHelp('This command allows you to load a file that contains graph information and print the ordered vertices')
            ->addArgument(
                'file',
                InputArgument::REQUIRED,
                'The full path of the graph file'
            );
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // scope in the argument
        $file = $input->getArgument('file');
        // build the graph
        $graph = Digraph::fromFile($file);
        // run it
        $diTopological = new TopologicalOrder($graph);
        // check for order
        if ($diTopological->hasOrder()) {
            // get the order
            $order = $diTopological->order();
            // iterate over the set
            foreach ($order as $vertex) {
                // print it
                $output->writeln($vertex);
            }
        } else {
            // no order
            $output->writeln('No order in G');
        }
        //  return success signal
        return 0;
    }
}