<?php

namespace MIAProde\Helper;

/**
 * Description of Points
 *
 * @author matiascamiletti
 */
class Points 
{
    /**
     * Puntos por predicción exacta
     */
    static $POINTS_ONE = 3;
    /**
     * Puntos por predicción de ganador y goles / Puntos por predección de empate con diferencia de goles
     */
    static $POINTS_TWO = 2;
    /**
     * Puntos por predicción del ganador
     */
    static $POINTS_THREE = 1;
    /**
     * Almacena Service Manager
     * @var type 
     */
    protected $serviceManager;
    /**
     * Almacena los IDs de los usuarios que acertaron el resultado correctamente
     * @var array
     */
    protected $predictionCorrectUsers = array();
    
    public function __construct($service)
    {
        $this->serviceManager = $service;
    }
    
    /**
     * Funcion que se encarga de buscar si existen predecciones y calcular los puntos
     * @param \Application\Entity\Match $match
     */
    public function calculatePoints($match)
    {
        // Obtener predicciones
        $predictions = $this->getPredictionTable()->fetchAllByMatch($match->id);
        /* @var $stage \MIAProde\Entity\Stage */
        $stage = $this->getStageTable()->fetchById($match->stage_id);
        self::$POINTS_ONE = $stage->max_points;
        self::$POINTS_TWO = 10;
        // Recorremos las predicciones
        foreach($predictions as $prediction){
            /* @var $prediction \MIAProde\Entity\Prediction */
            // Obtener ranking del usuario
            $ranking = $this->getRankingTable()->fetchByGroup($prediction->group_id, $prediction->user_id);
            // Verificar si ya no existe
            if($ranking == null){
                continue;
            }
            // Predicción correcta
            if($match->result_one == $prediction->result_one && $match->result_two == $prediction->result_two){
                // Sumar puntaje
                $ranking->points += self::$POINTS_ONE;
                $prediction->points = self::$POINTS_ONE;
                if($stage->has_penalty == 1 && $match->penalty_one == $prediction->penalty_one && $match->penalty_two == $prediction->penalty_two){
                    $ranking->points += self::$POINTS_ONE;
                    $prediction->points = self::$POINTS_ONE + self::$POINTS_ONE;
                }
                // Agregar el usuario a la lista
                $this->predictionCorrectUsers[] = $prediction->user_id;
            }else if( $match->result_one == $match->result_two && $prediction->result_one == $prediction->result_two ){
                $ranking->points += self::$POINTS_THREE;
                $prediction->points = self::$POINTS_THREE;
                if($stage->has_penalty == 1 && $match->penalty_one == $prediction->penalty_one && $match->penalty_two == $prediction->penalty_two){
                    $ranking->points += self::$POINTS_ONE;
                    $prediction->points = self::$POINTS_THREE + self::$POINTS_ONE;
                }
            }else if( ($match->result_one > $match->result_two && $prediction->result_one > $prediction->result_two) 
                    || ($match->result_two > $match->result_one && $prediction->result_two > $prediction->result_one) ){
                // Sumar puntaje
                $ranking->points += self::$POINTS_THREE;
                $prediction->points = self::$POINTS_THREE;
            }
            // Guardar ranking
            $this->getRankingTable()->save($ranking);
            // Guardar predicción
            $this->getPredictionTable()->save($prediction);
        }
    }
    
    public function sendPredictionCorrect()
    {
        // Verificar si hay que enviar a algun usuario
        if(count($this->predictionCorrectUsers) == 0){
            return false;
        }
        // Buscar todos los usuarios
        $users = $this->getUserTable()->fetchAllByIds($this->predictionCorrectUsers);
        // Recorremos los usuarios en busca de los MIA_IDS
        $miaIds = array();
        foreach($users as $u){
            $miaIds[] = $u->mia_id;
        }
        // Buscamos los tokens
        $tokens = $this->getMobileiaAuth()->getDevicesTokenOnly($miaIds);
        // Enviamos notificación
        $firebaseHelper = new \MIAProde\Helper\FirebaseMessaging($this->getFirebaseMessaging());
        $firebaseHelper->sendPredictionCorrect($tokens);
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
     * @return \MIAProde\Table\RankingTable
     */
    public function getRankingTable()
    {
        return $this->getServiceManager()->get(\MIAProde\Table\RankingTable::class);
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
     * @return \MobileIA\Auth\MobileiaAuth
     */
    protected function getMobileiaAuth()
    {
        return $this->getServiceManager()->get(\MobileIA\Auth\MobileiaAuth::class);
    }
    /**
     * 
     * @return \MIAProde\Table\StageTable
     */
    public function getStageTable()
    {
        return $this->getServiceManager()->get(\MIAProde\Table\StageTable::class);
    }
    /**
     * 
     * @return \MIAFirebase\Messaging
     */
    public function getFirebaseMessaging()
    {
        return $this->getServiceManager()->get(\MIAFirebase\Messaging::class);
    }
    /**
     * 
     * @return \Zend\ServiceManager\ServiceLocatorInterface
     */
    public function getServiceManager()
    {
        return $this->serviceManager;
    }
}