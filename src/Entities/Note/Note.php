<?php 
namespace CleanArchitecture\Entities\Note;

use CleanArchitecture\Entities\User\User;
use CleanArchitecture\Entities\Note\Title;

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
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param Title $title
     * @return self
     */
    public function setTitle(Title $title): self
    {
        $this->title = $title;

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