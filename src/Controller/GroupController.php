<?php

namespace MIAProde\Controller;

/**
 * Description of GroupController
 *
 * @author matiascamiletti
 */
class GroupController extends \MIAAuthentication\Controller\AuthCrudController
{
    protected $tableName = \MIAProde\Table\GroupTable::class;
    /**
     * Almacena instancia del helper
     * @var \MIAProde\Helper\FirebaseMessaging
     */
    protected $firebaseHelper;
    
    /**
     * API para la creación de un grupo
     * @return \Zend\View\Model\JsonModel
     */
    public function addAction()
    {
        // Verificamos los parametros requeridos
        $this->checkRequiredParams(array('title', 'tournament_id', 'contacts'));
        // TODO: Verificar si el usuario puede crear nuevos torneoss
        // Creamos el grupo
        $group = $this->createGroup()->toArray();
        // Iniciamos firebase
        $this->firebaseHelper = new \MIAProde\Helper\FirebaseMessaging($this->getFirebaseMessaging());
        // Procesamos los contactos
        $this->processContacts($group['id']);
        // Guardar usuario creador en el grupo
        $this->getRelationUserTable()->add($group['id'], $this->getUser()->id, $this->getUser()->firstname, $this->getUser()->phone, $this->getUser()->facebook_id, \MIAProde\Entity\Group\RelationUser::ROLE_ADMIN);
        // Agregamos usuario creador al ranking
        $this->getRankingTable()->add($group['id'], $this->getUser()->id);
        // Buscamos a los usuarios del grupo
        $group['contacts'] = $this->getRelationUserTable()->fetchAllByGroup($group['id']);
        // Devolvemos respuesta correcta
        return $this->executeSuccess($group);
    }
    /**
     * Creamos el grupo con los parametros enviados
     * @return \Application\Entity\Group
     */
    protected function createGroup()
    {
        $entity = new \MIAProde\Entity\Group();
        $entity->tournament_id = $this->getParam('tournament_id', -1);
        $entity->user_id = $this->getUser()->id;
        $entity->title = $this->getParam('title', '');
        $this->getTable()->save($entity);
        return $entity;
    }
    /**
     * Funcion que se encarga de guardar los contactos del grupo
     * @param int $groupId
     */
    protected function processContacts($groupId)
    {
        // Obtenemos los contactos enviados
        $contacts = $this->getParam('contacts', array());
        // Almacena IDs para enviar notificacion
        $miaIds = array();
        // Recorremos los contactos
        for($i = 0; $i < count($contacts); $i++){
            // Contacto
            $c = $contacts[$i];
            // Verificar si se ingreso el FacebookID
            if($c->facebook == ''){
                continue;
            }
            // Buscar usuario
            $user = $this->getUserTable()->fetchByFacebook($c->facebook);
            // Verificar si existe
            if($user == null){
                $userId = 0;
                $firstname = $c->firstname;
            }else{
                $userId = $user->id;
                $firstname = $user->firstname;
                $miaIds[] = $user->mia_id;
            }
            // Agregar usuario al ranking
            $this->getRankingTable()->add($groupId, $userId, $firstname, '', $c->facebook);
            // Guardar usuario en el grupo
            $this->getRelationUserTable()->add($groupId, $userId, $c->firstname, '', $c->facebook);
        }
        // Enviar notificacion
        if(count($miaIds) > 0){
            // Enviar notificaciones, buscamos los tokens
            $tokens = $this->getMobileiaAuth()->getDevicesTokenOnly($miaIds);
            // Enviamos notificación
            $this->firebaseHelper->sendNewGroup($tokens);
        }
    }
    
    /**
     * Configura la query, para el servicio que obtiene todos los grupos del usuario
     * @param \Zend\Db\Sql\Select $select
     * @return \Zend\Db\Sql\Select
     */
    public function configSelect($select)
    {
        // Obtenemos el ID del usuario
        $userId = $this->getUser()->id;
        // Join para traer los datos del usuario
        $select->join('group_users', 'group_users.group_id = groups.id', array('role'));
        // Buscar los grupos del usuario
        $select->where->addPredicate(new \Zend\Db\Sql\Predicate\Expression('group_users.user_id = ?', $userId));
        // Configuramos el orden
        $select->order('groups.id ASC');
        // Devolvemos select personalizado
        return $select;
    }
    /**
     * Configura el servicio para obtener todos los grupos del usuario
     * @param array $data
     * @return array
     */
    public function configListData($data)
    {
        // Recorremos los grupos
        for($i = 0; $i < count($data); $i++){
            // Buscamos a los usuarios del grupo
            $data[$i]['contacts'] = $this->getRelationUserTable()->fetchAllByGroup($data[$i]['id']);
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
    /**
     * API para eliminar un usuario de un grupo
     * @return \Zend\View\Model\JsonModel
     */
    public function removeUserAction()
    {
        // Verificamos los parametros requeridos
        $this->checkRequiredParams(array('group_id', 'user_id'));
        // Obtener parametro del grupo a mostrar
        $groupId = $this->getParam('group_id', 0);
        $userId = $this->getParam('user_id', 0);
        // Verificar si es administrador del grupo
        if(!$this->getRelationUserTable()->isAdmin($groupId, $this->getUser()->id)){
            return $this->executeError(\MIABase\Controller\Api\Error::INVALID_ACCESS_TOKEN);
        }
        // Buscamos usuario a eliminar
        $user = $this->getUserTable()->fetchById($userId);
        // Verificamos si existe
        if($user === null){
            return $this->executeError(\MIABase\Controller\Api\Error::REQUIRED_PARAMS);
        }
        // Eliminar usuario de la DB
        $this->getRelationUserTable()->remove($groupId, $userId);
        // Iniciamos firebase
        $this->firebaseHelper = new \MIAProde\Helper\FirebaseMessaging($this->getFirebaseMessaging());
        // Enviar notificaciones, buscamos los tokens
        $tokens = $this->getMobileiaAuth()->getDevicesTokenOnly(array($user->mia_id));
        // Enviamos notificación
        $this->firebaseHelper->sendRemovedGroup($tokens, $groupId);
        // Devolvemos respuesta correcta
        return $this->executeSuccess(true);
    }
    
    /**
     * 
     * @return \MIAAuthentication\Table\UserTable
     */
    protected function getUserTable()
    {
        return $this->getServiceManager()->get(\MIAAuthentication\Table\UserTable::class);
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
     * @return \MIAProde\Table\RankingTable
     */
    public function getRankingTable()
    {
        return $this->getServiceManager()->get(\MIAProde\Table\RankingTable::class);
    }
    /**
     * 
     * @return \MIAFirebase\Messaging
     */
    public function getFirebaseMessaging()
    {
        return $this->getServiceManager()->get(\MIAFirebase\Messaging::class);
    }
}
