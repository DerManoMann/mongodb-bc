<?php

/**
 * MongoClient
 */
class MongoClient {
    const RP_PRIMARY = 1;

    protected $manager;
    protected $databaseOptions;

    public function __construct($server, $options, $driverOptions) {
        // handle options...
        $this->databaseOptions = [];
        $managerOptions = [];
        foreach ($options as $key => $value) {
            switch ($key) {
            case 'readPreference':
                $this->databaseOptions[$key] = new \MongoDB\Driver\ReadPreference($value);
                break;
            case 'w':
                $this->databaseOptions['writeConcern'] = new \MongoDB\Driver\WriteConcern($value);
                break;
            case 'ssl':
                $managerOptions['uri.ssl'] = $value;
                break;
            case 'replicaSet':
                $managerOptions['uri.replicaSet'] = $value;
                break;
            }
        }

        $this->manager = new \MongoDB\Driver\Manager('mongodb://'.implode(',', array_map(function ($value) { return $value.':27017'; }, explode(',', $server))), $managerOptions, $driverOptions);
    }

    public function selectDB($database) {
        return new MongoDB(new \MongoDB\Database($this->manager, $database, $this->databaseOptions));
    }
}
