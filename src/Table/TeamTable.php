<?php

namespace MIAProde\Table;

class TeamTable extends \MIABase\Table\Base
{
    protected $tableName = 'team';

    protected $entityClass = \MIAProde\Entity\Team::class;
    /**
     * Obtiene equipo por titulo
     * @param string $title
     * @return \Application\Entity\Team|null
     */
    public function fetchByTitle($title)
    {
        return $this->tableGateway->select(array('title' => $title))->current();
    }
    /**
     * Obtiene equipo por externalID
     * @param int $externalId
     * @return \Application\Entity\Team|null
     */
    public function fetchByExternalId($externalId)
    {
        return $this->tableGateway->select(array('external_id' => $externalId))->current();
    }
    /**
     * Obtiene equipo por titulo y pais
     * @param string $title
     * @param int $countryId
     * @return \Application\Entity\Team|null
     */
    public function fetchByTitleAndCountry($title, $countryId)
    {
        return $this->tableGateway->select(array('title' => $title, 'country_id' => $countryId))->current();
    }
}