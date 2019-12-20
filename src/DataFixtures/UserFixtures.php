<?php


namespace App\DataFixtures;


use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Mime\Encoder\EncoderInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{

    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    /**
     * UserFixtures constructor.
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }


    private function createUser($name, $firstName, $order){
        $user = new User();
        $user   ->setName($name)->setFirstName($firstName)
                ->setPwd($this->encoder->encodePassword($user, '123'))
                ->setEmail("$name@gmail.com");
        $this->addReference("user_$order", $user);
        return $user;
    }

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {
        $manager->persist($this->createUser("Hoarau", "Fabrice", 1));
        $manager->persist($this->createUser("Sores", "Alexandre", 2));
        $manager->persist($this->createUser("Rousseau", "Thomas", 3));
        $manager->persist($this->createUser("Marquet", "Damien", 4));
        $manager->flush();
    }
}