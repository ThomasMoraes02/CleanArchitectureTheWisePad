<?php 
namespace CleanArchitecture\Entities\Note;

use DomainException;

class Title
{
    private string $title;

    public function __construct(string $title)
    {
        $this->setTitle($title);
    }

    /**
     * Verify Title
     *
     * @param string $title
     * @return void
     */
    private function setTitle(string $title): void
    {
        $title = filter_var($title, FILTER_SANITIZE_SPECIAL_CHARS);
        if(strlen($title) < 10) {
            throw new DomainException("The title must contain at least 10 characters");
        } 
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->title;
    }
}