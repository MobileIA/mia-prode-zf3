<?php

namespace MIAProde\Table;

class RankingTable extends \MIABase\Table\Base
{
    protected $tableName = 'ranking';

    protected $entityClass = \MIAProde\Entity\Ranking::class;
    /**
     * Agrega el usuario del grupo en el ranking
     * @param int $groupId
     * @param int $userId
     * @param string $firstname
     * @param string $phone
     * @param string $facebookId
     * @param string $photo
     * @return int
     */
    public function add($groupId, $userId, $firstname = '', $phone = '', $facebookId = '', $photo = '', $points = 0)
    {
        // Verificar si ya existe el usuario en el ranking
        $row = $this->fetchByGroup($groupId, $userId);
        if($row !== null){
            return;
        }
        // Crear registro en el ranking
        $ranking = new \MIAProde\Entity\Ranking();
        $ranking->group_id = $groupId;
        $ranking->user_id = $userId;
        $ranking->points = $points;
        $ranking->firstname = $firstname;
        $ranking->phone = $phone;
        $ranking->facebook_id = $facebookId;
        $ranking->photo = $photo;
        // Guardar en la DB
        return $this->save($ranking);
    }
    /**
     * Actualiza el ID del usuario a traves de su FacebookID
     * @param int $facebookId
     * @param int $userId
     * @return int
     */
    public function updateByFacebook($facebookId, $userId)
    {
        return $this->tableGateway->update(array('user_id' => $userId), array('facebook_id' => $facebookId));
    }
    /**
     * Obtiene los primeros puestos del ranking
     * @param int $groupId
     * @return array
     */
    public function fetchTop($groupId, $limit = 3)
    {
        // Crear Select
        $select = $this->tableGateway->getSql()->select();
        // Buscamos ese torneo
        $select->where(array('group_id' => $groupId));
        // Join para traer los datos del usuario
        $select->join('mia_user', 'mia_user.id = ranking.user_id', array('firstname', 'lastname', 'email', 'photo'));
        // Configuramos el orden
        $select->order('points DESC');
        // traemos los primeros 3
        $select->limit($limit);
        $result = $this->tableGateway->getSql()->prepareStatementForSqlObject($select)->execute();
        return $result->getResource()->fetchAll();
    }
    /**
     * Obtiene los ultimos diez puestos del ranking
     * @param int $groupId
     * @return array
     */
    public function fetchLast($groupId)
    {
        // Crear Select
        $select = $this->tableGateway->getSql()->select();
        // Buscamos ese torneo
        $select->where(array('group_id' => $groupId));
        // Join para traer los datos del usuario
        $select->join('mia_user', 'mia_user.id = ranking.user_id', array('firstname', 'photo'));
        // Configuramos el orden
        $select->order('points ASC');
        // traemos los primeros 3
        $select->limit(10);
        // Ejecutar query
        return $this->executeQuery($select);
    }
    /**
     * Devuelve el puntaje del ultimo jugador del ranking
     * @param int $groupId
     * @return int
     */
    public function getMinPointsInGroup($groupId)
    {
        // Obtener los ultimos puestos del ranking
        $ranking = $this->fetchLast($groupId);
        // Verificar si hay algun en el grupo
        if(count($ranking) == 0){
            return 0;
        }
        // Devolver puntos del ultimo jugador del ranking
        return $ranking[0]['points'];
    }
    /**
     * Obtiene el usuario del grupo si existe
     * @param int $groupId
     * @param int $userId
     * @return \Application\Entity\Ranking
     */
    public function fetchByGroup($groupId, $userId)
    {
        return $this->tableGateway->select(array('group_id' => $groupId, 'user_id' => $userId))->current();
    }
    /**
     * Elimina un usuario del ranking
     * @param int $groupId
     * @param int $userId
     * @return int
     */
    public function remove($groupId, $userId)
    {
        return $this->tableGateway->delete(array('group_id' => $groupId, 'user_id' => $userId));
    }
    /**
     * Eliminar un usuario del ranking a traves de su nombre
     * @param int $groupId
     * @param string $firstname
     * @return int
     */
    public function removeByName($groupId, $firstname)
    {
        return $this->tableGateway->delete(array('group_id' => $groupId, 'user_id' => 0, 'firstname' => $firstname));
    }
}