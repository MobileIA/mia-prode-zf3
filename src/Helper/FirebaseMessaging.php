<?php

namespace MIAProde\Helper;

/**
 * Description of FirebaseMessaging
 *
 * @author matiascamiletti
 */
class FirebaseMessaging 
{
    
    const TYPE_CHANGE_MATCH = 1;
    const TYPE_MATCHES_NOW = 2;
    const TYPE_PREDICTION_CORRECT = 3;
    const TYPE_NEW_GROUP = 4;
    const TYPE_REMOVED_GROUP = 5;
    const TYPE_LEAVE_GROUP = 6;
    const TYPE_CUSTOM_NOTIFICATION = 7;
    
    /**
     *
     * @var \MIAFirebase\Messaging
     */
    protected $service;
    /**
     * 
     * @param \MIAFirebase\Messaging $firebase
     */
    public function __construct($firebase)
    {
        $this->service = $firebase;
    }
    /**
     * Enviar notificacion cuando se crea un nuevo grupo
     * @param array $tokens
     * @return type
     */
    public function sendNewGroup($tokens)
    {
        return $this->service->sendToDevices($tokens, self::TYPE_NEW_GROUP);
    }
    /**
     * 
     * @param type $tokens
     * @param type $groupId
     * @return type
     */
    public function sendRemovedGroup($tokens, $groupId)
    {
        return $this->service->sendToDevices($tokens, self::TYPE_REMOVED_GROUP, array('group_id' => $groupId));
    }
    /**
     * 
     * @param array $tokens
     * @param array $group
     * @param string $firstname
     * @return type
     */
    public function sendLeaveGroup($tokens, $group, $firstname)
    {
        return $this->service->sendToDevices($tokens, self::TYPE_LEAVE_GROUP, array('group' => $group, 'firstname' => $firstname));
    }
    /**
     * Envia una notificacion personalizada a todos los usuarios
     * @param string $title
     * @param string $message
     */
    public function sendNotification($title, $message)
    {
        return $this->service->sendToTopic('allusers', self::TYPE_CUSTOM_NOTIFICATION, array('title' => $title, 'message' => $message));
    }
    /**
     * Envia notificacion de que se acerto exactamente el resultado
     * @param array $tokens
     * @return type
     */
    public function sendPredictionCorrect($tokens)
    {
        return $this->service->sendToDevices($tokens, self::TYPE_PREDICTION_CORRECT, array());
    }
    /**
     * Informa de que se actualizo un partido
     * @param array $match
     * @return type
     */
    public function sendToMatch($match)
    {
        return $this->service->sendToTopic('updateMatch', self::TYPE_CHANGE_MATCH, array('match' => $match));
    }
}