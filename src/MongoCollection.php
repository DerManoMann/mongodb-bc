<?php

/**
 * MongoCollection
 */
class MongoCollection {
    protected static $TYPE_MAP = ['root' => 'array', 'document' => 'array', 'array' => 'array'];
    protected $collection;

    public function __construct($collection) {
        $this->collection = $collection;
    }

    public function count(array $query = array(), array $options = array()) {
        return $this->collection->count($query, $options);
    }

    //public array createDBRef ( mixed $document_or_id )
    public function createIndex($keys, array $options = array()) {
        return $this->collection->createIndex($keys, $options);
    }

    //public array deleteIndex ( string|array $keys )
    //public array deleteIndexes ( void )
    //public array distinct ( string $key [, array $query ] )
    //public array drop ( void )

    public function ensureIndex($key, array $options = array()) {
        return $this->collection->createIndex($keys, $options);
    }

    public function find(array $query = array(), array $fields = array()) {
        $docs = [];
        foreach ($this->collection->find($query, ['typeMap' => static::$TYPE_MAP]) as $doc) {
            $doc['_id'] = new MongoId($doc['_id']);
            $docs[] = $doc;
        }

        return new \ArrayIterator($docs);
    }

    public function findAndModify($query, array $update = [], array $fields = [], array $options = []) {
        if (!\MongoDB\is_first_key_operator($update)) {
            $update = ['$set' => $update];
        }

        $doc = (array) $this->collection->findOneAndUpdate($query, $update, array_merge($options, ['typeMap' => static::$TYPE_MAP]));
        $doc['_id'] = new MongoId($doc['_id']);

        return $doc;
    }


    public function findOne(array $query = array(), array $fields = array(), array $options = array()) {
        if (array_key_exists('_id', $query)) {
            $query['_id'] = new \MongoDB\BSON\ObjectID($query['_id']->{'$id'});
        }

        if ($doc = (array) $this->collection->findOne($query, array_merge($options, ['typeMap' => static::$TYPE_MAP]))) {
            $doc['_id'] = new MongoId($doc['_id']);
        }

        return $doc;
    }

    //public MongoCollection __get ( string $name )
    //public array getDBRef ( array $ref )
    //public array getIndexInfo ( void )
    //public string getName ( void )
    //public array getReadPreference ( void )
    //public bool getSlaveOkay ( void )
    //public array getWriteConcern ( void )
    //public array group ( mixed $keys , array $initial , MongoCode $reduce [, array $options = array() ] )

    public function insert(&$document, array $options = array()) {
        $result = $this->collection->insertOne($document, $options);
        $document['_id'] = new MongoId($result->getInsertedId());

        return $document;
    }


    //public array[MongoCommandCursor] parallelCollectionScan ( int $num_cursors )

    public function remove(array $criteria = array(), array $options = array()) {
        if (array_key_exists('_id', $criteria)) {
            $criteria['_id'] = new \MongoDB\BSON\ObjectID($criteria['_id']->{'$id'});
        }

        return $this->collection->deleteMany($criteria, $options);
    }

    public function save($document, array $options = array()) {
        if (array_key_exists('_id', $document)) {
            $_id = $document['_id'];
            $id = new \MongoDB\BSON\ObjectID($document['_id']->{'$id'});
            unset($document['_id']);

            $this->collection->updateOne(['_id' => $id], ['$set' => $document]);

            return array_merge(['_id' => $_id], $document);
        } else {
            $result = $this->collection->insertOne($document, $options);
            $document['_id'] = new MongoId($result->getInsertedId());

            return $document;
        }
    }

    //public bool setReadPreference ( string $read_preference [, array $tags ] )
    //public bool setSlaveOkay ([ bool $ok = true ] )
    //public bool setWriteConcern ( mixed $w [, int $wtimeout ] )
    //static protected string toIndexString ( mixed $keys )

    public function __toString() {
        return $this->collection->__toString();
    }

    //public bool|array update ( array $criteria , array $new_object [, array $options = array() ] )
    //public array validate ([ bool $scan_data = FALSE ] )
}
