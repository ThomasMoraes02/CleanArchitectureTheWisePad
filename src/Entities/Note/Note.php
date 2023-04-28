<?php 
namespace CleanArchitecture\Entities\Note;

use CleanArchitecture\Entities\Note\Title;

class Note
{
    private Title $title;

    private string $content;

    public function __construct(Title $title, string $content)
    {
        $this->title = $title;
        $this->content = $content;
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