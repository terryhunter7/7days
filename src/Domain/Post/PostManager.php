<?php

namespace Domain\Post;

use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;

class PostManager
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function addPost(string $title, string $content)
    {
        $post = new Post();
        $post->setTitle($title);
        $post->setContent($content);
        $this->em->persist($post);
        $this->em->flush();
    }

    public function findPost(int $id): Post
    {
        $postRepository = $this->em->getRepository(Post::class);

        return $postRepository->findOneBy(['id' => $id]);
    }
}
