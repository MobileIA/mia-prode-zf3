<?php

namespace MIAProde\Table;

class PredictionTable extends \MIABase\Table\Base
{
    protected $tableName = 'prediction';

    protected $entityClass = \MIAProde\Entity\Prediction::class;
    /**
     * Guarda una nueva predicción
     * @param int $groupId
     * @param int $matchId
     * @param int $userId
     * @param int $resultOne
     * @param int $resultTwo
     * @return int
     */
    public function update($groupId, $matchId, $userId, $resultOne, $resultTwo)
    {
        // Buscar si ya existe
        $entity = $this->fetchByMatch($groupId, $matchId, $userId);
        // Si no existe creamos una nueva
        if($entity === null){
            $entity = new \MIAProde\Entity\Prediction();
            $entity->group_id = $groupId;
            $entity->match_id = $matchId;
            $entity->user_id = $userId;
            $entity->points = -1;
        }
        // Configuramos nuevos parametros
        $entity->result_one = $resultOne;
        $entity->result_two = $resultTwo;
        // Guardamos
        return $this->save($entity);
    }
    /**
     * Obtiene la predición del partido del usuario
     * @param int $groupId
     * @param int $matchId
     * @param int $userId
     * @return \MIAProde\Entity\Prediction|null
     */
    public function fetchByMatch($groupId, $matchId, $userId)
    {
        return $this->tableGateway->select(array('group_id' => $groupId, 'match_id' => $matchId, 'user_id' => $userId))->current();
    }
    /**
     * Obtener todos las predicciones de un partido
     * @param int $matchId
     * @return array
     */
    public function fetchAllByMatch($matchId)
    {
        return $this->tableGateway->select(array('match_id' => $matchId));
    }
}