<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        foreach ($this->getUserData() as [$fullname, $username, $password, $email, $roles]) {
            $user = new User();
            $user->setFullName($fullname);
            $user->setUsername($username);
            $user->setPassword($this->passwordEncoder->encodePassword($user, $password));
            $user->setEmail($email);
            $user->setRoles($roles);

            $manager->persist($user);
            $this->addReference($username, $user);
        }
        for ($i = 22; $i >= 1; $i--) {
            $article = new Article();
            $article->setTitle('News n°'.$i);
            $article->setDescription('Description n°'.$i.'

Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed et risus blandit, pulvinar sem sit amet, vulputate libero. Vivamus blandit viverra urna euismod tincidunt. Quisque tempor erat massa, ut feugiat urna rutrum et. Fusce sagittis dignissim ante vel rutrum. Sed consectetur tortor felis, eget dapibus orci aliquet ullamcorper. Aliquam erat volutpat. Mauris faucibus risus in pharetra consectetur. Quisque congue eleifend leo, et ullamcorper leo interdum at. Phasellus eget mollis est, id pulvinar ipsum. Sed ut volutpat nisi. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Morbi pretium, odio vitae accumsan ultricies, nisl lorem posuere justo, ut aliquam ipsum augue sit amet ligula. Fusce vehicula ante purus, sed porta lorem efficitur vel.

Donec et lacus dui. Vivamus venenatis orci nec velit gravida mattis. Aenean id fermentum ante. Mauris eu mattis nisi. Praesent ligula arcu, suscipit vel est eget, interdum sodales tellus. Nunc tincidunt lectus eu tincidunt volutpat. Nunc eu dignissim leo, sed accumsan tortor. Aenean maximus lectus id neque commodo, non mattis leo tempus. Proin imperdiet, ligula sit amet porta euismod, ex diam venenatis odio, eu tincidunt purus orci sit amet tellus. Donec ornare, lorem eu pulvinar faucibus, mi eros sagittis orci, at malesuada quam ex quis metus. Mauris egestas nibh ut purus porttitor, et consequat lorem fermentum. Ut vitae pharetra nunc. Fusce et efficitur sem, in mattis justo.

Phasellus nec tellus metus. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Donec ultrices, ex ac viverra aliquet, leo elit lobortis diam, hendrerit fermentum augue est non lectus. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Morbi non quam in lacus venenatis eleifend. Fusce vel lorem sit amet eros euismod vehicula sed vitae dolor. Morbi bibendum, nisi eu viverra rutrum, turpis arcu finibus turpis, molestie suscipit nisi metus cursus diam. In eleifend imperdiet tellus a laoreet.

Praesent finibus tempor lacus, non condimentum quam elementum vestibulum. Donec mattis nibh et augue rutrum condimentum. Pellentesque porttitor ipsum dignissim pretium aliquet. Nam facilisis purus ac arcu laoreet aliquam nec ac felis. Aenean tempor ex quis tincidunt suscipit. Aenean quis mi feugiat, gravida massa at, mattis leo. Curabitur eros massa, volutpat ac consectetur ac, faucibus eu est. Quisque scelerisque ligula id sodales mollis. Sed felis turpis, sagittis at dapibus ac, tincidunt id orci. Lorem ipsum dolor sit amet, consectetur adipiscing elit.

Ut id diam eu urna tempor maximus ac ut ligula. Phasellus malesuada imperdiet neque nec congue. Duis feugiat ac nunc a tempor. Fusce ac leo ex. Nulla felis turpis, tempor sed nisl id, convallis sollicitudin sem. Donec luctus diam sit amet semper suscipit. Aenean non magna nisi. Nulla gravida sem metus, eu euismod turpis congue ac. ');

            $date = new \DateTime('now');

            $date->modify('-' . $i . ' month' );

            $article->setPublicationDate($date);


            $manager->persist($article);

        }

        $manager->flush();
    }

    private function getUserData(): array
    {
        return [
            // $userData = [$fullname, $username, $password, $email, $roles];
            ['Jean Pierre', 'admin', 'admin', 'admin@symfony.com', ['ROLE_ADMIN']],

            ['Georges Martin', 'user', 'user', 'user@symfony.com', ['ROLE_USER']],
        ];
    }


}