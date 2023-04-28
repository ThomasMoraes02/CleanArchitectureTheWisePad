<?php 
namespace CleanArchitecture\Domain\Note;

use CleanArchitecture\Domain\Email;
use CleanArchitecture\Domain\Note\Note;
use CleanArchitecture\Domain\Note\Title;

interface NoteRepository
{
    /**
     * Add Note to Repository
     *
     * @param Note $note
     * @return void
     */
    public function add(Note $note): void;

    /**
     * Find Note By Id
     *
     * @param string $id
     * @return Note
     */
    public function findById(string $id): Note;

    /**
     * Find Note By Title
     *
     * @param Title $title
     * @return Note
     */
    public function findyByTitle(Title $title): Note;

    /**
     * Update Note By Id
     *
     * @param string $id
     * @param array $data
     * @return void
     */
    public function update(string $id, array $data): void;

    /**
     * Delete Note By Id
     *
     * @param string $id
     * @return void
     */
    public function delete(string $id): void;

    /**
     * Find All Notes From User
     *
     * @param Email $email
     * @param integer $page
     * @param integer $per_page
     * @return array
     */
    public function findAllNotesFrom(Email $email, int $page = 0, int $per_page = 0): array;
}