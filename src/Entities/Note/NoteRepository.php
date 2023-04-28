<?php 
namespace CleanArchitecture\Entities\Note;

use CleanArchitecture\Entities\Note\Note;
use CleanArchitecture\Entities\User\User;
use CleanArchitecture\Entities\Note\Title;

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
     * Get All Notes From User
     *
     * @param User $user
     * @return array
     */
    public function getAllNotesFromUser(User $user): array;
}