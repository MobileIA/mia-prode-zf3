<?php

namespace MIAProde\Entity;

class Ranking extends \MIABase\Entity\Base implements \Zend\InputFilter\InputFilterAwareInterface
{
    /**
     * @var int
     */
    public $group_id = null;
    /**
     * @var int
     */
    public $user_id = null;
    /**
     * @var int
     */
    public $points = null;
    /**
     *
     * @var boolean
     */
    protected $hasCreatedAt = false;
    /**
     *
     * @var boolean
     */
    protected $hasUpdatedAt = false;

    public function toArray()
    {
        $data = parent::toArray();
        $data['group_id'] = $this->group_id;
        $data['user_id'] = $this->user_id;
        $data['points'] = $this->points;
        return $data;
    }

    public function exchangeArray(array $data)
    {
        parent::exchangeArray($data);
        $this->group_id = (!empty($data['group_id'])) ? $data['group_id'] : 0;
        $this->user_id = (!empty($data['user_id'])) ? $data['user_id'] : 0;
        $this->points = (!empty($data['points'])) ? $data['points'] : 0;
    }

    public function exchangeObject($data)
    {
        parent::exchangeObject($data);
        $this->group_id = $data->group_id;
        $this->user_id = $data->user_id;
        $this->points = $data->points;
    }

    public function getInputFilter()
    {
        if ($this->inputFilter) {
            return $this->inputFilter;
        }
                
        $inputFilter = new \Zend\InputFilter\InputFilter();
        $inputFilter->add([
                    'name' => 'group_id',
                    'required' => true,
                    'filters' => [
                        ['name' => \Zend\Filter\ToInt::class],
                    ],
                ]);
        $inputFilter->add([
                    'name' => 'user_id',
                    'required' => true,
                    'filters' => [
                        ['name' => \Zend\Filter\ToInt::class],
                    ],
                ]);
        $inputFilter->add([
                    'name' => 'points',
                    'required' => true,
                    'filters' => [
                        ['name' => \Zend\Filter\ToInt::class],
                    ],
                ]);
        $this->inputFilter = $inputFilter;
        return $this->inputFilter;
    }

    public function setInputFilter(\Zend\InputFilter\InputFilterInterface $inputFilter)
    {
        throw new DomainException(sprintf(
                    '%s does not allow injection of an alternate input filter',
                    __CLASS__
                ));
    }
}