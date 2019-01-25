<?php declare(strict_types=1);

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;
use App\Entity\Role;

/**
 * Class SetAdminCommand
 */
class SetAdminCommand extends Command
{
    const USER_EMAIL = 'admin@admin.admin';
    const PASSWORD = 'password';

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * SetAdminCommand constructor.
     *
     * @param EntityManager                $em
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param null                         $name
     */
    public function __construct(EntityManager $em, UserPasswordEncoderInterface $passwordEncoder, $name = null)
    {
        parent::__construct($name);
        $this->em = $em;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setDescription(sprintf('Create base user with email: %s and password: %s', self::USER_EMAIL, self::PASSWORD));
        $this->setName('app:set-admin-user');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int|void|null
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $message = 'Admin user already created';
        $user = $this->em->getRepository(User::class)->findOneByRoleId(Role::ROLE_ADMIN);

        if (!$user) {
            $user = new User();
            $user
                ->setEmail(self::USER_EMAIL)
                ->setSalt('123')
                ->setPassword($this->passwordEncoder->encodePassword($user, self::PASSWORD))
                ->setRoles([$this->em->getReference(Role::class, Role::ROLE_ADMIN)])
                ->setFirstName('Jon')
                ->setLastName('Dou');

            $this->em->persist($user);
            $this->em->flush();

            $message = sprintf('User created email: %s, password:%s', self::USER_EMAIL, self::PASSWORD);
        }

        $output->writeln($message);
    }


}
