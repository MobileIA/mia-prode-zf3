<?php

namespace MIAProde\Controller;

/**
 * Description of UserController
 *
 * @author matiascamiletti
 */
class UserController extends \MIAAuthentication\Controller\ApiController
{
    /**
     * Metodo que se ejecuta despues de crear/modificar el usuario
     * @param \MIAAuthentication\Entity\User $user
     */
    protected function modelSaved($user)
    {
        // Verificamos si tiene FacebookID
        if($user->facebook_id == ''){
            return;
        }
        // Actualizar ID de los grupos asignados a su facebookID
        $this->getRelationUserTable()->updateByFacebook($user->facebook_id, $user->id);
        // Actualizar iD en el ranking
        $this->getRankingTable()->updateByFacebook($user->facebook_id, $user->id);
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
     * @return \MIAProde\Table\Group\RelationUserTable
     */
    protected function getRelationUserTable()
    {
        return $this->getServiceManager()->get(\MIAProde\Table\Group\RelationUserTable::class);
    }
    /**
     * 
     * @return \MIAProde\Table\GroupTable
     */
    public function getGroupTable()
    {
        return $this->getServiceManager()->get(\MIAProde\Table\GroupTable::class);
    }
    /**
     * 
     * @return \MIAProde\Table\RankingTable
     */
    public function getRankingTable()
    {
        return $this->getServiceManager()->get(\MIAProde\Table\RankingTable::class);
    }
}
