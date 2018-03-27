<?php

namespace MIAProde\Entity;

class Prediction extends \MIABase\Entity\Base implements \Zend\InputFilter\InputFilterAwareInterface
{
    /**
     * @var int
     */
    public $group_id = null;
    
    /**
     * @var int
     */
    public $match_id = null;

    /**
     * @var int
     */
    public $user_id = null;

    /**
     * @var int
     */
    public $result_one = null;

    /**
     * @var int
     */
    public $result_two = null;

    /**
     * @var int
     */
    public $points = null;
    
    /**
     * @var int
     */
    public $penalty_one = null;

    /**
     * @var int
     */
    public $penalty_two = null;
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
        $data['match_id'] = $this->match_id;
        $data['user_id'] = $this->user_id;
        $data['result_one'] = $this->result_one;
        $data['result_two'] = $this->result_two;
        $data['points'] = $this->points;
        $data['penalty_one'] = $this->penalty_one;
        $data['penalty_two'] = $this->penalty_two;
        return $data;
    }

    public function exchangeArray(array $data)
    {
        parent::exchangeArray($data);
        $this->group_id = (!empty($data['group_id'])) ? $data['group_id'] : 0;
        $this->match_id = (!empty($data['match_id'])) ? $data['match_id'] : 0;
        $this->user_id = (!empty($data['user_id'])) ? $data['user_id'] : 0;
        $this->result_one = (!empty($data['result_one'])) ? $data['result_one'] : 0;
        $this->result_two = (!empty($data['result_two'])) ? $data['result_two'] : 0;
        $this->points = (!empty($data['points'])) ? $data['points'] : 0;
        $this->penalty_one = (!empty($data['penalty_one'])) ? $data['penalty_one'] : 0;
        $this->penalty_two = (!empty($data['penalty_two'])) ? $data['penalty_two'] : 0;
    }

    public function exchangeObject($data)
    {
        parent::exchangeObject($data);
        $this->group_id = $data->group_id;
        $this->match_id = $data->match_id;
        $this->user_id = $data->user_id;
        $this->result_one = $data->result_one;
        $this->result_two = $data->result_two;
        $this->points = $data->points;
        $this->penalty_one = $data->penalty_one;
        $this->penalty_two = $data->penalty_two;
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
        $inputFilter = new \Zend\InputFilter\InputFilter();
        $inputFilter->add([
                    'name' => 'match_id',
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
                    'name' => 'result_one',
                    'required' => true,
                    'filters' => [
                        ['name' => \Zend\Filter\ToInt::class],
                    ],
                ]);
        $inputFilter->add([
                    'name' => 'result_two',
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