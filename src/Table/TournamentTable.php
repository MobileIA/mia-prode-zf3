<?php

namespace MIAProde\Table;

class TournamentTable extends \MIABase\Table\Base
{
    protected $tableName = 'tournament';

    protected $entityClass = \MIAProde\Entity\Tournament::class;
    /**
     * Obtiene equipo por titulo
     * @param string $title
     * @return \MIAProde\Entity\Tournament|null
     */
    public function fetchByTitle($title)
    {
        return $this->tableGateway->select(array('title' => $title))->current();
    }
    /**
     * obtener todos los torneos que estan en curso
     * @return array
     */
    public function fetchAllInProgress()
    {
        // Crear Select
        $select = $this->tableGateway->getSql()->select();
        // Buscamos los torneos en curso
        $select->where->addPredicate(new \Zend\Db\Sql\Predicate\Expression('start <= NOW() AND end >= NOW()'));
        $select->where->addPredicate(new \Zend\Db\Sql\Predicate\Expression('external_id > 0'));
        // Devolvemos informacion
        return $this->executeQuery($select);
    }
}