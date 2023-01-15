<?php

namespace App\Services;

class UtilsService
{
    
    /**
    * Calculate the rating for a post based on the number of words in its title and body
    *
    * @param array $post
    * @return int
    * @throws \InvalidArgumentException if the post data is invalid
    */
    public function calculatePostRating(array $post): int
    {
        if (!isset($post['title']) || !isset($post['body']) || trim($post['title']) === '' || trim($post['body']) === '') {
            throw new \InvalidArgumentException("Invalid post data provided");
        }
        // count the number of words in the title and the body 
        $wordsInTitle =$this->countWords($post['title']);
        $wordsInBody =$this->countWords($post['body']);
        
        // calculate the rating
        $rating = $wordsInTitle * 2 + $wordsInBody;
        return $rating;
    }

    /**
     * count the number of words in a string
     *
     * @param string $content
     * @return int
     */
    private function countWords(string $content): int
    {
        return str_word_count(trim($content));
    }


}