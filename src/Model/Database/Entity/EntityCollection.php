<?php 

namespace App\Model\Database\Entity;

use App\Model\Database\Entity\Entity;

class EntityCollection
{
    public static function getCollection(array $entityRecords, string $entityName): array
    {
        $entityCollection = array();

        foreach ($entityRecords as $record)
        {

            $entityCollection[] = new $entityName($record);
        }

        return $entityCollection;
    }
}