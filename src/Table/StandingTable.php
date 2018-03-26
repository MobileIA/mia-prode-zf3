<?php

namespace MIAProde\Table;

class StandingTable extends \MIABase\Table\Base
{
    protected $tableName = 'standing';

    protected $entityClass = \MIAProde\Entity\Standing::class;
    /**
     * 
     * @param \MIAProde\Entity\Standing $standing
     * @return int
     */
    public function add($standing)
    {
        // Verificar si ya existe el usuario en el ranking
        $row = $this->fetchByTeam($standing->stage_id, $standing->team_id);
        if($row !== null){
            $standing->id = $row->id;
        }
        return $this->save($standing);
    }
    /**
     * Obtener la tabla de posiciones de la liga
     * @param int $stageId
     * @return array
     */
    public function fetchAllByStage($stageId)
    {
        // Crear Select
        $select = $this->tableGateway->getSql()->select();
        // Buscamos ese torneo
        $select->where(array('stage_id' => $stageId));
        // Join para traer los datos del usuario
        $select->join('team', 'team.id = standing.team_id', array('title', 'title_short', 'photo'));
        // Configuramos el orden
        $select->order('standing.position ASc');
        // Ejecutar query
        return $this->executeQuery($select);
    }
    
    /**
     * 
     * @param int $stageId
     * @param int $teamId
     * @return \MIAProde\Entity\Standing|null
     */
    public function fetchByTeam($stageId, $teamId)
    {
        return $this->tableGateway->select(array('stage_id' => $stageId, 'team_id' => $teamId))->current();
    }
}