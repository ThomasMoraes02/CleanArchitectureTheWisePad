<?php 
namespace CleanArchitecture\Infraestructure\Repositories\Mongo;

use MongoDB\Client;
use MongoDB\Database;
use MongoDB\Operation\FindOneAndUpdate;

class MongoDBConnection
{
    private Client $client;

    private Database $database;

    public function __construct()
    {
        $this->client = new Client();
        $this->database = $this->client->selectDatabase(MONGODB_CONNECT['database']);
    }

    /**
     * Get Client
     *
     * @return Client
     */
    public function getClient(): Client
    {
        return $this->client;
    }

    /**
     * Get Database
     *
     * @return Database
     */
    public function getDatabase(): Database
    {
        return $this->database;
    }

     /**
     * Generate Next ID
     *
     * @return string
     */
    public function getNextId(string $counterCollection): string
    {
        $counters = $this->getDatabase()->selectCollection($counterCollection);

        $result = $counters->findOneAndUpdate(
            ["id", "id"],
            ['$inc' => ['seq' => 1]],
            ['upsert' => true, 'projection' => [ 'seq' => 1 ],'returnDocument' => FindOneAndUpdate::RETURN_DOCUMENT_AFTER]
        );
        
        return $result["seq"];
    }
}