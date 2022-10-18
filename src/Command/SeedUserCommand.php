<?php

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'user:seed',
    description: 'This command seeds a user',
)]
class SeedUserCommand extends Command
{

    public function __construct(
        protected UserRepository $userRepository,
        protected UserPasswordHasherInterface $passwordHasher
    ) {
        parent::__construct();
        $this->userRepository = $userRepository;
    }


    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $this->seedAdmin();

        $this->seedModerator();

        $io->success('admin and moderator user data seeded successfully!');

        return Command::SUCCESS;
    }

    private function seedAdmin()
    {
        $user = new User;

        $user->setEmail('admin@example.com');
        $user->setRoles(['ROLE_ADMIN']);
        $user->setPassword($this->passwordHasher->hashPassword($user, 'pass123'));

        $this->userRepository->save($user, true);
    }

    private function seedModerator()
    {
        $user = new User;

        $user->setEmail('moderator@example.com');
        $user->setRoles(['ROLE_MODERATOR']);
        $user->setPassword($this->passwordHasher->hashPassword($user, 'pass123'));

        $this->userRepository->save($user, true);
    }
}
