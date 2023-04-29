<?php 
namespace CleanArchitecture\Test\Infraestructure\Repositories;

use CleanArchitecture\Application\Exceptions\NoteNotFound;
use PHPUnit\Framework\TestCase;
use CleanArchitecture\Domain\Email;
use CleanArchitecture\Domain\Note\Note;
use CleanArchitecture\Domain\Note\Title;
use CleanArchitecture\Domain\User\User;
use CleanArchitecture\Infraestructure\Encoder\EncoderArgonII;
use CleanArchitecture\Infraestructure\Repositories\NoteRepositoryMemory;

use function PHPUnit\Framework\assertEmpty;

class NoteRepositoryMemoryTest extends TestCase
{
    private User $user;

    private NoteRepositoryMemory $noteRepository;

    protected function setUp(): void
    {
        $this->user = new User("Thomas Moraes", new Email("thomas@gmail.com"), new EncoderArgonII("123456"));
        $this->noteRepository = new NoteRepositoryMemory;
    }

    public function testAddNote()
    {
        $note = new Note($this->user, new Title("Titulo"), "Conteudo da Nota");
        $this->noteRepository->add($note);

        $this->assertNotEmpty($this->noteRepository);
    }

    public function testFindNoteByIdAndTitle()
    {
        $note = new Note($this->user, new Title("Titulo"), "Conteudo da Nota");
        $this->noteRepository->add($note);

        $this->assertNotEmpty($this->noteRepository->findById(0));
        
        $this->assertNotEmpty($this->noteRepository->findyByTitle(new Title("Titulo")));

        $note = new Note($this->user, new Title("Titulo 2"), "Conteudo da Nota 2");
        $this->noteRepository->add($note);

        $this->assertEquals(2, count($this->noteRepository->findAllNotesFrom($this->user->getEmail())));
    }

    public function testDeleteNote()
    {
        $this->expectException(NoteNotFound::class);

        $note = new Note($this->user, new Title("Titulo"), "Conteudo da Nota");
        $this->noteRepository->add($note);

        $this->noteRepository->delete(0);

        $this->noteRepository->findById(0);
    }

    public function testUpdateNote()
    {
        $note = new Note($this->user, new Title("Titulo"), "Conteudo da Nota");
        $this->noteRepository->add($note);

        $data = [
            "Title" => "Novo Titulo",
            "Content" => "Novo Conteudo"
        ];

        $this->noteRepository->update(0, $data);

        $note = $this->noteRepository->findById(0);

        $this->assertEquals("Novo Titulo", $note->getTitle());
        $this->assertEquals("Novo Conteudo", $note->getContent());
    }
}