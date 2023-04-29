<?php 
namespace CleanArchitecture\Test\Domain\Note;

use CleanArchitecture\Domain\Email;
use CleanArchitecture\Domain\Note\Note;
use CleanArchitecture\Domain\Note\Title;
use CleanArchitecture\Domain\User\User;
use CleanArchitecture\Infraestructure\Encoder\EncoderArgonII;
use PHPUnit\Framework\TestCase;

class NoteTest extends TestCase
{
    public function testCreateNote()
    {
        $user = new User("Thomas Moraes", new Email("thomas@gmail.com"), new EncoderArgonII("123456"));

        $note = new Note($user, new Title("Nova Nota"), "Conteudo da Nota");

        $this->assertEquals("Nova Nota", $note->getTitle());
        $this->assertEquals("Conteudo da Nota", $note->getContent());
        $this->assertEquals("thomas@gmail.com", $note->getUser()->getEmail());
    }
}