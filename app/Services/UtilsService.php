<?php

namespace App\Services;

class UtilsService
{
    
    public function calculatePostRating(array $post): int
    {
        if (!isset($post['title']) || !isset($post['body']) || trim($post['title']) === '' || trim($post['body']) === '') {
            throw new \InvalidArgumentException("Invalid post data provided");
        }

        $wordsInTitle =$this->countWords($post['title']);
        $wordsInBody =$this->countWords($post['body']);
        $rating = $wordsInTitle * 2 + $wordsInBody;
        return $rating;
    }

    private function countWords(string $content): int
    {
        return str_word_count(trim($content));
    }


}