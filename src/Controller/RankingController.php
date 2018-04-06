<?php

namespace MIAProde\Controller;

class RankingController extends \MIABase\Controller\Api\CrudController
{
    protected $tableName = \MIAProde\Table\RankingTable::class;
    
    /**
     * Configura la query, para casos especiales
     * @param \Zend\Db\Sql\Select $select
     * @return \Zend\Db\Sql\Select
     */
    public function configSelect($select)
    {
        // Obtener parametro del grupo a mostrar
        $groupId = $this->getParam('group_id', 0);
        // Buscamos ese torneo
        $select->where(array('group_id' => $groupId));
        // Join para traer los datos del usuario
        $select->join('mia_user', 'mia_user.id = ranking.user_id', array('firstname', 'photo'), \Zend\Db\Sql\Select::JOIN_LEFT);
        // Configuramos el orden
        $select->order('points DESC');
        // Devolvemos select personalizado
        return $select;
    }
}