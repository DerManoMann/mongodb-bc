<?php

/**
 * MongoId
 */
class MongoId {
    protected $objectId;

    public function __construct ($objectId) {
        $this->objectId = $objectId;
        $this->{'$id'} = (string) $objectId;
    }

    public static function isValid ($value) {
        return true;
    }
}
