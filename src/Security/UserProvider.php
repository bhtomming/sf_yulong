<?php
/**
 * Created by PhpStorm.
 * User: 烽行天下
 * Date: 2019/2/17
 * Time: 12:00
 * Site: http://www.drupai.com
 */

namespace App\Security;



use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProvider implements UserProviderInterface
{
    private $entityManager;



    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Loads the user for the given username.
     *
     * This method must throw UsernameNotFoundException if the user is not
     * found.
     *
     * @param string $username The username
     *
     * @return UserInterface
     *
     * @throws UsernameNotFoundException if the user is not found
     */
    public function loadUserByUsername($username)
    {
        $user = $this->findOneUserBy($username);
        if(!$user){
            throw new UsernameNotFoundException(
                sprintf(
                    'User with "%s" username does not exist.',
                    $username
                )
            );
        }
        return $user;
    }

    /**
     * Refreshes the user.
     *
     * It is up to the implementation to decide if the user data should be
     * totally reloaded (e.g. from the database), or if the UserInterface
     * object can just be merged into some internal array of users / identity
     * map.
     *
     * @return UserInterface
     *
     * @throws UnsupportedUserException  if the user is not supported
     * @throws UsernameNotFoundException if the user is not found
     */
    public function refreshUser(UserInterface $user)
    {
        assert($user instanceof User);
        if(null === $relodedUser = $this->findOneUserBy(['id' => $user->getId()])){
            throw new UsernameNotFoundException(
                sprintf(
                    'User with id %s is not be reload',
                            $user->getId()
                    )
            );
        }
        return $relodedUser;
    }

    /**
     * Whether this provider supports the given user class.
     *
     * @param string $class
     *
     * @return bool
     */
    public function supportsClass($class)
    {
        return $class === User::class;
    }

    public function findOneUserBy(array $options): ? User
    {
        return $this->entityManager->getRepository(User::class)->findOneBy($options);
    }


}