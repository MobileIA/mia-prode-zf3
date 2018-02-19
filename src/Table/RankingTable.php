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
    public function add($groupId, $userId, $firstname = '', $phone = '', $facebookId = '', $photo = '')
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
        $ranking->points = 0;
        $ranking->firstname = $firstname;
        $ranking->phone = $phone;
        $ranking->facebook_id = $facebookId;
        $ranking->photo = $photo;
        // Guardar en la DB
        return $this->save($ranking);
    }
    /**
     * Obtiene los primeros puestos del ranking
     * @param int $groupId
     * @return array
     */
    public function fetchTop($groupId)
    {
        // Crear Select
        $select = $this->tableGateway->getSql()->select();
        // Buscamos ese torneo
        $select->where(array('group_id' => $groupId));
        // Join para traer los datos del usuario
        $select->join('mia_user', 'mia_user.id = ranking.user_id', array('firstname', 'photo'));
        // Configuramos el orden
        $select->order('points DESC');
        // traemos los primeros 3
        $select->limit(3);
        $result = $this->tableGateway->getSql()->prepareStatementForSqlObject($select)->execute();
        return $result->getResource()->fetchAll();
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
}