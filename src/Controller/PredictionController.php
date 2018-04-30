<?php

namespace MIAProde\Controller;

/**
 * Description of SocketController
 *
 * @author matiascamiletti
 */
class PredictionController extends \MIAAuthentication\Controller\AuthCrudController
{
    /**
     * API para guardar una nueva prediccón de un partido
     * @return \Zend\View\Model\JsonModel
     */
    public function sendAction()
    {
        // Verificamos los parametros requeridos
        $this->checkRequiredParams(array('match_id', 'result_one', 'result_two', 'group_id'));
        // Obtenemos Grupo
        $groupId = $this->getParam('group_id', 0);
        // Verificar si tenemos permisos para predecir en este grupo
        if(!$this->getRelationUserTable()->hasPermission($groupId, $this->getUser()->id)){
            return $this->executeError(\MIABase\Controller\Api\Error::REQUIRED_PARAMS);
        }
        // Obtenemos partido a predecir
        $matchId = $this->getParam('match_id', 0);
        // Buscamos el partido en la DB
        $match = $this->getMatchTable()->fetchById($matchId);
        // Verificamos si existe
        if($match === null){
            return $this->executeError(\MIABase\Controller\Api\Error::REQUIRED_PARAMS);
        }
        // Verificamos si el partido ya comenzo
        if($match->status != \MIAProde\Entity\Match::STATUS_PENDING){
            return $this->executeError(\MIABase\Controller\Api\Error::INVALID_CONFIGURATION);
        }
        // Guardamos nueva predicción
        $this->getPredictionTable()->update($groupId, $matchId, $this->getUser()->id, $this->getParam('result_one', 0), $this->getParam('result_two', 0), $this->getParam('penalty_one', 0), $this->getParam('penalty_two', 0));
        // Devolvemos respuesta correcta
        return $this->executeSuccess(true);
    }
    /**
     * 
     * @return \MIAProde\Table\MatchTable
     */
    public function getMatchTable()
    {
        return $this->getServiceManager()->get(\MIAProde\Table\MatchTable::class);
    }
    /**
     * 
     * @return \MIAProde\Table\PredictionTable
     */
    public function getPredictionTable()
    {
        return $this->getServiceManager()->get(\MIAProde\Table\PredictionTable::class);
    }
    /**
     * 
     * @return \MIAProde\Table\Group\RelationUserTable
     */
    protected function getRelationUserTable()
    {
        return $this->getServiceManager()->get(\MIAProde\Table\Group\RelationUserTable::class);
    }
}