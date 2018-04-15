<?php

namespace MIAProde\Controller;

/**
 * Description of StandingController
 *
 * @author matiascamiletti
 */
class StandingController extends \MIAAuthentication\Controller\AuthCrudController
{
    public function indexAction()
    {
        // Obtenemos torneo
        $tournamentId = $this->getParam('tournament_id', 0);
        // Obtenr todos los stages
        $stages = $this->getStageTable()->fetchAllByTournament($tournamentId);
        // Variable que almacena la informacion
        $data = array();
        // Recorremos los stages
        foreach($stages as $stage){
            if($stage->has_penalty == 1){
                $data[] = array('title' => $stage->title, 'has_penalty' => $stage->has_penalty, 'matches' => $this->getMatchTable()->fetchAllByStage($stage->id), 'list' => array());
            }else{
                $data[] = array('title' => $stage->title, 'has_penalty' => $stage->has_penalty, 'list' => $this->getStandingTable()->fetchAllByStage($stage->id), 'matches' => array());
            }
        }
        // Creamos respuesta
        return $this->executeSuccess($data);
    }
    /**
     * 
     * @return \MIAProde\Table\StandingTable
     */
    public function getStandingTable()
    {
        return $this->getServiceManager()->get(\MIAProde\Table\StandingTable::class);
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
     * @return \MIAProde\Table\TournamentTable
     */
    public function getTournamentTable()
    {
        return $this->getServiceManager()->get(\MIAProde\Table\TournamentTable::class);
    }
    /**
     * 
     * @return \MIAProde\Table\StageTable
     */
    public function getStageTable()
    {
        return $this->getServiceManager()->get(\MIAProde\Table\StageTable::class);
    }
}