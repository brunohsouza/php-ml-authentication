<?php

namespace App\Command;

use App\Service\AccessService;
use App\Service\CountryIpService;
use App\Service\SuspectService;
use App\Service\UserService;
use Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use App\Controller\UserController;
use Symfony\Component\DependencyInjection\ContainerInterface;

class AuthCommandController extends Command
{
    private $userService;

    private $input;

    private $output;

    private $container;

    private $em;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct();
        $this->container = $container;
        $this->em = $this->container->get('doctrine')->getManager();
        $this->userService= new UserService($this->em);
    }

    public function configure(): void
    {
        $this->setName('mlauth')
            ->setDescription('Application to check authentications in different places')
            ->setHelp('-h');
        $this->addArgument('action', InputArgument::OPTIONAL, 'The name of the action');
        $this->addOption('load', 'l', InputArgument::OPTIONAL, 'The name of the dataset [access, user]');
        $this->addOption('actions', 'x', InputOption::VALUE_OPTIONAL, 'Choose the action: ');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;
        $helper = $this->getHelper('question');
        $actionAnswer = $input->getOption('actions');
        $loadOption = $input->getOption('load');

        if (is_null($actionAnswer) && is_null($loadOption)) {
            $operation = new Question('Type the name of action you wanna do now: ');
            $actionAnswer = $helper->ask($this->input, $this->output, $operation);
        }

        if ($loadOption) {
            $this->{$loadOption}();
        }

        if ($actionAnswer === 'load' || $loadOption) {
            $datasetQuestion = new Question('Which dataset do you wanna load?: ');
            $datasetAnswer = $helper->ask($this->input, $this->output, $datasetQuestion);
            $this->{$datasetAnswer}();
        }

        if ($actionAnswer === 'login') {
            $loginQuestion = new Question('Type your username: ');
            $loginAnswer = $helper->ask($this->input, $this->output, $loginQuestion);
            $passwordQuestion = new Question('Type your password: ');
            $passwordQuestion->setHidden(true);
            $passwordAnswer = $helper->ask($this->input, $this->output, $passwordQuestion);
        }

        if (method_exists($this, strtolower($actionAnswer))) {
            $this->{$actionAnswer}();
            $output->writeln('This operation takes too much time... Please, go take a coffee and be patience');
        } else if ($actionAnswer === 'login') {
            $this->login($loginAnswer, $passwordAnswer);
        } else {
            $this->output->writeln('Invalid command.');
        }
    }

    public function user()
    {
        try {
            $accuracy = $this->userService->loadDataset();
            $this->showOutput('Accuracy (k=%s): %.02f%% correct: %s', $accuracy[0], $accuracy[1], $accuracy[2] . PHP_EOL);
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
    }

    public function access()
    {
        try {
            $accessService = new AccessService($this->em);
            $accuracy = $accessService->generateDatasetAccess();
            $this->showOutput('Accuracy (k=%s): %.02f%% correct: %s', $accuracy[0], $accuracy[1], $accuracy[2] . PHP_EOL);
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
    }

    public function lastAccess()
    {
        $accessService = new AccessService($this->em);
        $accessService->getAccuracyAccess();
    }

    public function getDistance()
    {
        $accessService = new AccessService($this->em);
        $accessService->getUserAccess();
    }

    public function trainDataset()
    {
        $suspectService = new SuspectService();
        $suspectService->trainDataset();
    }
}