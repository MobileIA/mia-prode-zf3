<?php

namespace MIAProde\Table;

class TeamTable extends \MIABase\Table\Base
{
    protected $tableName = 'team';

    protected $entityClass = \MIAProde\Entity\Team::class;
    /**
     * Obtiene equipo por titulo
     * @param string $title
     * @return \Application\Entity\Team|null
     */
    public function fetchByTitle($title)
    {
        return $this->tableGateway->select(array('title' => $title))->current();
    }
}