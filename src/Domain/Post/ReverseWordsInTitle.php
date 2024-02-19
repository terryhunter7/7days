<?php

namespace Domain\Post;

use App\Entity\Post;

class ReverseWordsInTitle
{
    public function reverseWordsInTitle(Post $post): Post
    {
        $titleWords = explode(' ', $post->getTitle());
        $reversedWords = array_reverse($titleWords);
        $newTitle = implode(' ', $reversedWords);
        $post->setTitle($newTitle);

        return $post;
    }
}
