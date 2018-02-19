<?php

namespace MIAProde\Table;

class GroupTable extends \MIABase\Table\Base
{
    protected $tableName = 'groups';

    protected $entityClass = \MIAProde\Entity\Group::class;
    /**
     * Funcion para agregar un nuevo grupo
     * @param int $tournmentId
     * @param int $userId
     * @param string $title
     * @return int
     */
    public function add($tournmentId, $userId, $title)
    {
        $entity = new \MIAProde\Entity\Group();
        $entity->tournament_id = $tournmentId;
        $entity->user_id = $userId;
        $entity->title = $title;
        return $this->save($entity);
    }
}