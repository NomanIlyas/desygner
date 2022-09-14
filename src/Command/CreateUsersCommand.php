<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Entity\User;
use App\Entity\UserImage;
use App\Entity\Tag;
use App\Entity\Image;
use App\Entity\ImageTag;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Ramsey\Uuid\Uuid;

class CreateUsersCommand extends Command
{
    private EntityManagerInterface $em;
    
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(
        EntityManagerInterface $em,
        UserPasswordHasherInterface $passwordHasher
    ) {
        $this->em = $em;
        $this->passwordHasher = $passwordHasher;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('create:data')
            ->setDescription('Create data')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $newUsers = [];
        for ($i = 0; $i < 100; $i++) {
            $user = new User;
            
            $uuid = Uuid::uuid4();

            $user->setEmail("user" . ($i + 1) . "@test.com");
            $user->setFullName("user" . ($i + 1));
            $user->setDisplayName("user" . ($i + 1));
            $user->setRoles(["user"]);
            $user->setTimeZone("UTC");
            $user->setUuid($uuid);
            
            $this->em->persist($user);
            $this->em->flush();

            $user->setPassword($this->passwordHasher->hashPassword($user, "test"));
            $this->em->persist($user); 
            $this->em->flush();
            
            $output->writeln("----User " . ($i + 1) . " created------");

            array_push($newUsers, $user);
        }

        $tags = [];
        for ($i = 1; $i <= 100; $i++) {
            $tag = new Tag;
            $tag->setName("Tag $i");

            $this->em->persist($tag);
            $this->em->flush();

            array_push($tags, $tag);

            $output->writeln("----Tag " . ($i) . " created------");
        }

        $imageIndex = 1;
        $tagId = 0;
        $tag = $tags[0];
        $userId = 0;
        $user = $newUsers[0];

        for ($i = 1; $i <= 10000; $i++) {
            $image = new Image;
            $image->setName("Image$i");
            $image->setSource("/images/image-$imageIndex.jpg");
            $image->addTag($tag);
            $image->setReference("S3 bucket");

            $this->em->persist($image);
            $this->em->flush();

            
            $userImage = new UserImage;
            $userImage->setImage($image);
            $userImage->setUser($user);
            
            $this->em->persist($userImage);
            $this->em->flush();
            
            $output->writeln("----Image " . ($i) . " created------");
            $imageIndex++;

            if ($imageIndex > 10) {
                $imageIndex = 1;
            }

            if ($i % 100 === 0) {
                if ($tagId < count($tags) - 1) {
                    $tagId++;
                    $tag = $tags[$tagId];
                }

                if ($userId < count($newUsers) - 1) {
                    $userId++;
                    $user = $newUsers[$userId];
                }
            }
        }

        return 0;
    }
}