<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class GetUserDataFromJsonCommand extends Command
{
    protected static $defaultName = 'app:download-users';
    protected static $defaultDescription = 'Add a short description for your command';

    /**
     * @var EntityManagerInterface
     * */
    private $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $url = 'https://reqres.in/api/users?page=2';
        $jsonContent = file_get_contents($url);
        $jsonContentDecode = json_decode($jsonContent);

        $users = $jsonContentDecode->data;

        $io = new SymfonyStyle($input, $output);
        foreach($users as $item){
            $io->note(sprintf('Create new user, his email is $s', $item->{'email'}));
            $user = new User();
            $user->setIdJson($item->{'id'});
            $user->setFirstName($item->{'first_name'});
            $user->setLastName($item->{'last_name'});
            $user->setEmail($item->{'email'});
            $user->setAvatar($item->{'avatar'});
            $this->em->persist($user);
            $io->note(sprintf('Add new user: %s', $user->getEmail()));

        }
        $this->em->flush();

        $io->success('Success');

        return Command::SUCCESS;
    }
}
