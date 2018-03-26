<?php

namespace MIAProde\Table;

class StageTable extends \MIABase\Table\Base
{
    protected $tableName = 'stage';

    protected $entityClass = \MIAProde\Entity\Stage::class;
    /**
     * Devuelve todas las etapas del torneo
     * @param int $tournamentId
     * @return array
     */
    public function fetchAllByTournament($tournamentId)
    {
        return $this->tableGateway->select(array('tournament_id' => $tournamentId));
    }
    /**
     * obtener todos las etapas que estan en curso del torneo
     * @return array
     */
    public function fetchAllInProgress($tournamentId)
    {
        // Crear Select
        $select = $this->tableGateway->getSql()->select();
        // Buscamos los torneos en curso
        $select->where->addPredicate(new \Zend\Db\Sql\Predicate\Expression('start <= NOW() AND end >= NOW()'));
        $select->where->addPredicate(new \Zend\Db\Sql\Predicate\Expression('external_id > 0'));
        $select->where->addPredicate(new \Zend\Db\Sql\Predicate\Expression('tournament_id = ?', $tournamentId));
        // Devolvemos informacion
        return $this->executeQuery($select);
    }
}