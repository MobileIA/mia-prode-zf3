<?php

namespace MIAProde\Table;

class MatchTable extends \MIABase\Table\Base
{
    protected $tableName = 'match';

    protected $entityClass = \MIAProde\Entity\Match::class;
    /**
     * Obtiene partido por su ExternalID
     * @param int $externalId
     * @return \Application\Entity\Match|null
     */
    public function fetchByExternalId($externalId)
    {
        return $this->tableGateway->select(array('external_id' => $externalId))->current();
    }
    /**
     * Obtiene los partidos siguientes al ingresado
     * @param int $matchId
     * @param string $day
     * @param int $tournamentId
     * @param int $groupId
     * @param int $limit
     * @return array
     */
    public function fetchNextByMatch($userId, $matchId, $day, $tournamentId, $groupId, $limit = 10, $exclude = '')
    {
        // Crear Select
        $select = $this->createBaseSelect($userId, $tournamentId, $groupId);
        // Buscamos solo los anteriores al partido
        $select->where->addPredicate(new \Zend\Db\Sql\Predicate\Expression('match.day >= ?', $day));
        // No devolver el match indicado
        $select->where->addPredicate(new \Zend\Db\Sql\Predicate\Expression('match.id <> ?', $matchId)); 
        // Verificar si se incluyo excluidos
        if($exclude != ''){
            $select->where->addPredicate(new \Zend\Db\Sql\Predicate\Expression('match.id NOT IN ('.$exclude.')')); 
        }
        // Seteamos el limite
        $select->limit($limit);
        // Configuramos el orden
        $select->order('day ASC');
        // Ejecutar Query
        return $this->executeQuery($select);
    }
    /**
     * Obtiene los partidos previos al ingresado
     * @param int $matchId
     * @param string $day
     * @param int $tournamentId
     * @param int $groupId
     * @param int $limit
     * @return array
     */
    public function fetchPreviusByMatch($userId, $matchId, $day, $tournamentId, $groupId, $limit = 10, $exclude = '')
    {
        // Crear Select
        $select = $this->createBaseSelect($userId, $tournamentId, $groupId);
        // Buscamos solo los anteriores al partido
        $select->where->addPredicate(new \Zend\Db\Sql\Predicate\Expression('match.day <= ?', $day));
        // No devolver el match indicado
        $select->where->addPredicate(new \Zend\Db\Sql\Predicate\Expression('match.id <> ?', $matchId)); 
        // Verificar si se incluyo excluidos
        if($exclude != ''){
            $select->where->addPredicate(new \Zend\Db\Sql\Predicate\Expression('match.id NOT IN ('.$exclude.')')); 
        }
        // Seteamos el limite
        $select->limit($limit);
        // Configuramos el orden
        $select->order('day DESC');
        // Ejecutar Query
        return $this->executeQuery($select);
    }
    /**
     * Obtenemos los proximos partidos a partir de la fecha actual
     * @param int $tournamentId
     * @param int $groupId
     * @param int $limit
     * @return array
     */
    public function fetchNext($userId, $tournamentId, $groupId, $limit = 3)
    {
        // Crear Select
        $select = $this->createBaseSelect($userId, $tournamentId, $groupId);
        // Buscamos solo los nuevos
        $select->where->addPredicate(new \Zend\Db\Sql\Predicate\Expression('(match.day >= NOW()) OR (match.status = '.\MIAProde\Entity\Match::STATUS_IN_PROGRESS.')'));
        // Configuramos el orden
        $select->order('day ASC');
        // traemos los primeros 3
        $select->limit($limit);
        // Ejecutar Query
        return $this->executeQuery($select);
    }
    /**
     * Obtiene los ultimos partidos de la liga
     * @param int $userId
     * @param int $tournamentId
     * @param int $groupId
     * @param int $limit
     * @return array
     */
    public function fetchPrevious($userId, $tournamentId, $groupId, $limit = 10)
    {
        // Crear Select
        $select = $this->createBaseSelect($userId, $tournamentId, $groupId);
        // Seteamos el limite
        $select->limit($limit);
        // Configuramos el orden
        $select->order('day DESC');
        // Ejecutar Query
        return $this->executeQuery($select);
    }
    /**
     * Obtiene los partidos que se jugaran dentro de una hora.
     * @return array
     */
    public function fetchInOneHour($tournamentId)
    {
        // Crear Select
        $select = $this->tableGateway->getSql()->select();
        // Join para traer los datos del Stage
        $select->join('stage', 'stage.id = match.stage_id', array());
        // Buscamos ese torneo
        $select->where(array('stage.tournament_id' => $tournamentId));
        // Buscamos el día actual
        $select->where->addPredicate(new \Zend\Db\Sql\Predicate\Expression('day >= NOW() AND day <= DATE_ADD(NOW(), INTERVAL 1 HOUR)'));
        // Ejecutar Query
        return $this->executeQuery($select);
    }
    /**
     * 
     * @param int $stageId
     * @return array
     */
    public function fetchAllByStage($stageId)
    {
        // Crear Select
        $select = $this->tableGateway->getSql()->select();
        // Buscamos ese torneo
        $select->where(array('match.stage_id' => $stageId));
        // Join para traer los datos de los equipos
        $select->join('team', 'team.id = match.team_one_id', array('title_one' => 'title', 'title_short_one' => 'title_short', 'photo_one' => 'photo'));
        $select->join(array('team2' => 'team'), 'team2.id = match.team_two_id', array('title_two' => 'title', 'title_short_two' => 'title_short', 'photo_two' => 'photo'));
        // Configuramos el orden
        $select->order('day ASC');
        // Ejecutar Query
        return $this->executeQuery($select);
    }
    /**
     * Genera el select en comun, con los datos necesarios
     * @param int $tournamentId
     * @param int $groupId
     * @return \Zend\Db\Sql\Select
     */
    protected function createBaseSelect($userId, $tournamentId, $groupId)
    {
        // Crear Select
        $select = $this->tableGateway->getSql()->select();
        // Buscamos ese torneo
        $select->where(array('stage.tournament_id' => $tournamentId));
        // Join para traer los datos del Stage
        $select->join('stage', 'stage.id = match.stage_id', array('has_penalty','max_points'));
        // Join para traer los datos de los equipos
        $select->join('team', 'team.id = match.team_one_id', array('title_one' => 'title', 'title_short_one' => 'title_short', 'photo_one' => 'photo'));
        $select->join(array('team2' => 'team'), 'team2.id = match.team_two_id', array('title_two' => 'title', 'title_short_two' => 'title_short', 'photo_two' => 'photo'));
        // Join para traer la predicción del usuario
        $select->join('prediction', new \Zend\Db\Sql\Predicate\Expression('(prediction.match_id = match.id AND prediction.user_id = ' . $userId . ' AND prediction.group_id = '.$groupId.')'), array('predicted_one' => 'result_one', 'predicted_two' => 'result_two', 'predicted_penalty_one' => 'penalty_one', 'predicted_penalty_two' => 'penalty_two', 'points'), \Zend\Db\Sql\Select::JOIN_LEFT);
        // Devolvemos select
        return $select;
    }
}