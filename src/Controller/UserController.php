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
        // TODO: Buscar si este usuario ya pertenece a un grupo
    }
}
