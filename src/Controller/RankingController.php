<?php

namespace MIAProde\Controller;

class RankingController extends \MIAAuthentication\Controller\AuthCrudController
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
        $select->join('mia_user', 'mia_user.id = ranking.user_id', array('firstname_user' => 'firstname', 'photo'), \Zend\Db\Sql\Select::JOIN_LEFT);
        // Configuramos el orden
        $select->order('points DESC');
        // Devolvemos select personalizado
        return $select;
    }
    /**
     * Configura el servicio para obtener el ranking
     * @param array $data
     * @return array
     */
    public function configListData($data)
    {
        // Obtener usuario logueado
        $userId = $this->getUser()->id;
        // Recorremos los grupos
        for($i = 0; $i < count($data); $i++){
            // Verificamos si el usuario tiene cuenta
            if($data[$i]['user_id'] != null||$data[$i]['user_id'] > 0){
                $data[$i]['firstname'] = $data[$i]['firstname_user'];
            }
            unset($data[$i]['firstname_user']);
            // Verificar si es el usuario logueado
            if($data[$i]['user_id'] == $userId){
               $data[$i]['is_me'] = 1;
            }else{
               $data[$i]['is_me'] = 0; 
            }
        }
        
        return $data;
    }
    
    protected function configAction($action)
    {
        parent::configAction($action);
        if($action instanceof \MIAAuthentication\Action\Api\AuthListAction){
            $action->disableUserSearch();
        }
    }
}