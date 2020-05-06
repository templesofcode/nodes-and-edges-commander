<?php

namespace TemplesOfCode\NodesAndEdges\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use TemplesOfCode\NodesAndEdges\DFS\DSCC;
use TemplesOfCode\NodesAndEdges\Digraph;

/**
 * Class StrongComponentsCommand
 * @package TemplesOfCode\NodesAndEdges\Command
 */
class StrongComponentsCommand extends Command
{
    /**
     * The name of the command (the part after "bin/console")
     * @var string
     */
    protected static $defaultName = 'toc:nae:scc';

    /**
     *
     */
    protected function configure()
    {
        $this
            ->setDescription('Print the strongly connected components of a graph')
            ->setHelp('This command allows you to load a file that contains graph information and prints the strongly connected components of the graph')
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
        $dscc = new DSCC($graph);
        // get the count
        $n = $dscc->count();
        // display it
        $output->writeln(sprintf('%d components', $n));
        // compute list of vertices in each strong component
        $components = array_fill(0, $n, []);
        for ($vertex = 0; $vertex < $graph->getVertices(); $vertex++) {
            // add to the end of the list
            array_push($components[$dscc->id($vertex)], $vertex);
        }
        // print results
        for ($i = 0; $i < $n; $i++) {
            foreach ($components[$i] as $vertex) {
                $output->writeln($vertex . " ");
            }
        }
        //  return success signal
        return 0;
    }
}