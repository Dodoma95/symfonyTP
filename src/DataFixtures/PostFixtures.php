<?php


namespace App\DataFixtures;


use App\Entity\Post;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class PostFixtures extends Fixture implements DependentFixtureInterface
{


    /**
     * @inheritDoc
     */
    public function getDependencies()
    {
        return [
            UserFixtures::class
        ];
    }

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();
        $numberOfUsers = 4;

        for($i=1; $i <= 100; $i++){
            $post = new Post();
            $post   ->setMessage($faker->text(mt_rand(300, 1500)))
                    ->setCreateAt($faker->dateTimeThisDecade)
                    ->setUser($this->getReference("user_". mt_rand(1, $numberOfUsers)));

            $this->addReference("post_$i", $post);
            $manager->persist($post);
        }
        $manager->flush();
    }
}