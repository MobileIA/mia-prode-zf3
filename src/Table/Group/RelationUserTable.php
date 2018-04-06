<?php

namespace MIAProde\Table\Group;

class RelationUserTable extends \MIABase\Table\Base
{
    protected $tableName = 'group_users';

    protected $entityClass = \MIAProde\Entity\Group\RelationUser::class;
    /**
     * Guardar el usuario en el grupo
     * @param int $groupId
     * @param int $userId
     * @param string $username
     * @param string $phone
     * @param string $facebookId
     * @param int $role
     * @return int
     */
    public function add($groupId, $userId, $username, $phone, $facebookId, $role = \MIAProde\Entity\Group\RelationUser::ROLE_GUEST)
    {
        // Verificar si ya existe el usuario en el grupo
        $row = $this->fetchByUser($groupId, $userId);
        if($row !== null){
            return;
        }
        $entity = new \MIAProde\Entity\Group\RelationUser();
        $entity->group_id = $groupId;
        $entity->user_id = $userId;
        $entity->username = $username;
        $entity->phone = $phone;
        $entity->facebook_id = $facebookId;
        $entity->role = $role;
        return $this->save($entity);
    }
    /**
     * Elimina un usuario de un grupo
     * @param int $groupId
     * @param int $userId
     * @return int
     */
    public function remove($groupId, $userId)
    {
        return $this->tableGateway->delete(array('group_id' => $groupId, 'user_id' => $userId));
    }
    /**
     * Verifica si el usuario existe en el grupo
     * @param int $groupId
     * @param int $userId
     * @return boolean
     */
    public function hasPermission($groupId, $userId)
    {
        // Buscamos el registro
        $row = $this->fetchByUser($groupId, $userId);
        // Verificamos si existe
        if($row === null){
            return false;
        }
        return true;
    }
    /**
     * Verifica si el usuario es administrador del grupo
     * @param int $groupId
     * @param int $userId
     * @return boolean
     */
    public function isAdmin($groupId, $userId)
    {
        // Buscamos el registro
        $row = $this->fetchByUser($groupId, $userId);
        // Verificamos si existe
        if($row === null){
            return false;
        }
        // Verificamos si es admin
        if($row->role == \MIAProde\Entity\Group\RelationUser::ROLE_ADMIN){
            return true;
        }
        return false;
    }
    /**
     * Obtiene el registro si existe del usuario en el grupo
     * @param int $groupId
     * @param int $userId
     * @return \MIAProde\Entity\Group\RelationUser
     */
    public function fetchByUser($groupId, $userId)
    {
        return $this->tableGateway->select(array('group_id' => $groupId, 'user_id' => $userId))->current();
    }
    /**
     * Obtiene un usuario a traves de su Facebook ID
     * @param string $facebookId
     * @return \MIAProde\Entity\Group\RelationUser|null
     */
    public function fetchByFacebook($groupId, $facebookId)
    {
        return $this->tableGateway->select(array('group_id' => $groupId,'facebook_id' => $facebookId))->current();
    }
    /**
     * Obtiene todos los usuarios registrados en el grupo
     * @param int $groupId
     * @return array
     */
    public function fetchAllByGroup($groupId)
    {
        // Crear Select
        $select = $this->tableGateway->getSql()->select();
        // Join para traer los datos del usuario
        $select->join('mia_user', 'mia_user.id = group_users.user_id', array('firstname', 'photo', 'email'), \Zend\Db\Sql\Select::JOIN_LEFT);
        // Buscamos ese torneo
        $select->where(array('group_id' => $groupId));
        // Ejecutar Query
        return $this->executeQuery($select);
    }
    /**
     * Obtiene todos los grupos asignados del usuario
     * @param int $userId
     * @return array
     */
    public function fetchAllByUser($userId)
    {
        return $this->tableGateway->select(array('user_id' => $userId));
    }
}