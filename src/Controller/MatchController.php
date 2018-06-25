<?php

namespace MIAProde\Controller;

class MatchController extends \MIAAuthentication\Controller\AuthCrudController
{
    protected $tableName = \MIAProde\Table\MatchTable::class;
    /**
     * Devuelve los partidos proximos a mostrar en la HOME.
     * @return \Zend\View\Model\JsonModel
     */
    public function nextAction()
    {
        // Obtener grupo
        $group = $this->getGroup();
        // Obtener parametro del torneo a buscar
        $tournamentId = $group->tournament_id;
        // Buscar los proximos partidos
        $result = $this->getTable()->fetchNext($this->getUser()->id, $tournamentId, $group->id);
        // Generamos respuesta
        return $this->executeSuccess($result);
    }
    /**
     * Devuelve el listado de partidos anteriores al enviado
     * @return \Zend\View\Model\JsonModel
     */
    public function listPreviousAction()
    {
        // Obtener grupo
        $group = $this->getGroup();
        // Obtener parametro del torneo a buscar
        $tournamentId = $group->tournament_id;
        // Obtener parametro del partido enviado
        $matchId = $this->getParam('match_id', 0);
        // Buscar partido ingresado
        $match = $this->getTable()->fetchById($matchId);
        // Verificar si existe
        if($match === null){
            return $this->executeSuccess(array());
        }
        // Obtener partidos anteriores
        return $this->executeSuccess($this->getTable()->fetchPreviusByMatch($this->getUser()->id, $match->id, $match->day, $tournamentId, $group->id, 15, $this->getParam('exclude', '')));
    }
    
    public function listAction()
    {
        // Obtener grupo
        $group = $this->getGroup();
        // Obtener parametro del torneo a buscar
        $tournamentId = $group->tournament_id;
        // Buscar el proximo partido
        $nextMatch = $this->getTable()->fetchNext($this->getUser()->id, $tournamentId, $group->id, 1);
        // Verificar si hay proxima fecha
        if(count($nextMatch) > 0){
            // Obtenemos los partidos en el mdio de la lista
            $matches = $this->fetchMiddleList($nextMatch[0], $tournamentId, $group->id);
        }else if(count($nextMatch) == 0){
            // Obtenemos los partidos anteriores
            $matches = $this->fetchOlder($tournamentId, $group->id);
        }
        
        // Generamos respuesta
        return $this->executeSuccess($matches);
    }
    /**
     * Devuelve el listado de partidos siguientes al enviado
     * @return \Zend\View\Model\JsonModel
     */
    public function listNextAction()
    {
        // Obtener grupo
        $group = $this->getGroup();
        // Obtener parametro del torneo a buscar
        $tournamentId = $group->tournament_id;
        // Obtener parametro del partido enviado
        $matchId = $this->getParam('match_id', 0);
        // Buscar partido ingresado
        $match = $this->getTable()->fetchById($matchId);
        // Verificar si existe
        if($match === null){
            return $this->executeSuccess(array());
        }
        // Obtener partidos siguientes
        return $this->executeSuccess($this->getTable()->fetchNextByMatch($this->getUser()->id, $match->id, $match->day, $tournamentId, 15, $this->getParam('exclude', '')));
    }
    /**
     * Busca los partidos anteriores y siguiente al indicado
     * @param array $nextMatch
     * @param int $tournamentId
     * @param int $groupId
     * @return array
     */
    protected function fetchMiddleList($nextMatch, $tournamentId, $groupId)
    {
        // Obtener partidos anteriores
        $previous = $this->getTable()->fetchPreviusByMatch($this->getUser()->id, $nextMatch['id'], $nextMatch['day'], $tournamentId, $groupId);
        // Invertimos el orden del listado
        $previous = array_reverse($previous);
        // Agregar partido al listado
        array_push($previous, $nextMatch);
        // Obtener todos los IDs para excluir.
        $exclude = array();
        foreach($previous as $pre){
            $exclude[] = $pre['id'];
        }
        if(count($exclude) > 0){
            $exclude = implode(',', $exclude);
        }else{
            $exclude = '';
        }
        // Obtenemos partidos proximos
        $next = $this->getTable()->fetchNextByMatch($this->getUser()->id, $nextMatch['id'], $nextMatch['day'], $tournamentId, $groupId, 30, $exclude);
        // Unimos en el listado
        return array_merge($previous, $next);
    }
    /**
     * Busca los ultimos partidos de la liga, si no se encontraron proximos partidos.
     * @param type $tournamentId
     * @param type $groupId
     * @return type
     */
    protected function fetchOlder($tournamentId, $groupId)
    {
        // Obtener partidos anteriores
        $previous = $this->getTable()->fetchPrevious($this->getUser()->id, $tournamentId, $groupId);
        // Invertimos el orden del listado
        return array_reverse($previous);
    }
    /**
     * Obtiene el grupo enviados
     * @return \MIAProde\Entity\Group
     */
    protected function getGroup()
    {
        // Obtener parametro del grupo
        $groupId = $this->getParam('group_id', 0);
        // Buscar grupo
        return $this->getGroupTable()->fetchById($groupId);
    }
    
    /**
     * 
     * @return \MIAProde\Table\GroupTable
     */
    public function getGroupTable()
    {
        return $this->getServiceManager()->get(\MIAProde\Table\GroupTable::class);
    }
}