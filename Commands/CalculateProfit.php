<?php
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use application\core\Helper;

class CalculateProfit extends Command
{
    protected $commandName = 'app:profit';
    protected $commandDescription = "Calculate total profit in AUD";

    protected $commandArgumentName = "percentage";
    protected $commandArgumentDescription = "How much percentage of profit you want to count?";

    protected function configure()
    {
        $this->setName($this->commandName)
            ->setDescription($this->commandDescription)
            ->addArgument(
                $this->commandArgumentName,
                InputArgument::OPTIONAL,
                $this->commandArgumentDescription
            );
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $profitInPercentage = $input->getArgument($this->commandArgumentName);
        if (!$profitInPercentage) {
            $profitInPercentage = 15;
        }
        $csv = Helper::csvToArray('conversion.csv');
        array_shift($csv);
        $indexedArray = array_reduce($csv, 'array_merge', array());
        $updatedArray = array_map(array(__CLASS__, 'audAmounts'), array_filter($indexedArray, array(__CLASS__, 'audAmounts')));
        $totalProfit = round(($profitInPercentage / 100) * array_sum($updatedArray), 2); // 15% profit
        $totalProfit = $totalProfit.' AUD';
        $output->writeln($totalProfit);
    }

    public function audAmounts($value)
    {
        if (stripos($value, 'AUD') !== FALSE) {
            $amount = explode(" ", $value, 2)[0];
            return $amount;
        }
    }
}