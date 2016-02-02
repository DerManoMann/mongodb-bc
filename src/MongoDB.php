<?php

/**
 * MongoDB
 */
class MongoDB {
    protected $database;

    public function __construct($database) {
        $this->database = $database;
    }

    public function __debugInfo() {
        return $this->database->__debugInfo();
    }

    public function __get($collectionName) {
        return $this->database->__get($collectionName);
    }

    public function __toString() {
        $this->database->__toString();
    }

    public function command($command, array $options = []) {
        return $this->database->command($command, $options);
    }

    public function createCollection($collectionName, array $options = []) {
        return $this->database->command($collectionName, $options);
    }

    public function drop(array $options = []) {
        return $this->database->drop($options);
    }

    public function dropCollection($collectionName, array $options = []) {
        return $this->database->dropCollection($collectionName, $options);
    }

    public function getDatabaseName() {
        return $this->database->getDatabaseName();
    }

    public function listCollections(array $options = []) {
        return $this->getDatabaseName($options);
    }

    public function selectCollection($collectionName, array $options = []) {
        return new MongoCollection($this->database->selectCollection($collectionName, $options));
    }

    public function withOptions(array $options = []) {
        return $this->database->withOptions($options);
    }
}
