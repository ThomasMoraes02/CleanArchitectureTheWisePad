<?php 
namespace CleanArchitecture\Domain\Note;

use CleanArchitecture\Domain\User\User;
use CleanArchitecture\Domain\Note\Title;

class Note
{
    private User $user;

    private Title $title;

    private string $content;

    public function __construct(User $user, Title $title, string $content)
    {
        $this->user = $user;
        $this->title = $title;
        $this->content = $content;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return Title
     */
    public function getTitle(): Title
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return self
     */
    public function setTitle(string $title): self
    {
        $this->title = new Title($title);

        return $this;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     * @return self
     */
    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }
}