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
        $entity->is_closed = 0;
        return $this->save($entity);
    }
    /**
     * Query para exportar la información de los grupos.
     * @return array
     */
    public function fetchAllForExport()
    {
        // Crear Select
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('id', 'title', 'participantes' => new \Zend\Db\Sql\Predicate\Expression('(SELECT COUNT(*) FROM group_users WHERE group_users.group_id = groups.id)'), 'points' => new \Zend\Db\Sql\Predicate\Expression('(SELECT SUM(points) FROM ranking WHERE ranking.group_id = groups.id)')));
        // Join para traer los datos del Stage
        $select->join('mia_user', 'mia_user.id = groups.user_id', array('firstname', 'lastname'));
        // Configuramos el orden
        $select->order('title ASC');
        // Ejecutamos query
        return $this->executeQuery($select);
    }
}