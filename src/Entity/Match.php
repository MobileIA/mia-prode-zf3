<?php

namespace MIAProde\Entity;

class Match extends \MIABase\Entity\Base implements \Zend\InputFilter\InputFilterAwareInterface
{
    const STATUS_PENDING = 0;
    const STATUS_IN_PROGRESS = 1;
    const STATUS_ENDED = 2;
    const STATUS_IN_PENALTY = 3;
    
    /**
     * @var int
     */
    public $stage_id = null;

    /**
     * @var \Datetime
     */
    public $day = null;

    /**
     * @var int
     */
    public $team_one_id = null;

    /**
     * @var int
     */
    public $result_one = null;

    /**
     * @var int
     */
    public $team_two_id = null;

    /**
     * @var int
     */
    public $result_two = null;
    
    /**
     * @var int
     */
    public $penalty_one = null;

    /**
     * @var int
     */
    public $penalty_two = null;

    /**
     * @var int
     */
    public $status = null;
    /**
     * @var int
     */
    public $external_id = null;
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
        $data['stage_id'] = $this->stage_id;
        $data['day'] = $this->day;
        $data['team_one_id'] = $this->team_one_id;
        $data['result_one'] = $this->result_one;
        $data['team_two_id'] = $this->team_two_id;
        $data['result_two'] = $this->result_two;
        $data['penalty_one'] = $this->penalty_one;
        $data['penalty_two'] = $this->penalty_two;
        $data['status'] = $this->status;
        $data['external_id'] = $this->external_id;
        return $data;
    }

    public function exchangeArray(array $data)
    {
        parent::exchangeArray($data);
        $this->stage_id = (!empty($data['stage_id'])) ? $data['stage_id'] : 0;
        $this->day = (!empty($data['day'])) ? $data['day'] : '';
        $this->team_one_id = (!empty($data['team_one_id'])) ? $data['team_one_id'] : 0;
        $this->result_one = (!empty($data['result_one'])) ? $data['result_one'] : 0;
        $this->team_two_id = (!empty($data['team_two_id'])) ? $data['team_two_id'] : 0;
        $this->result_two = (!empty($data['result_two'])) ? $data['result_two'] : 0;
        $this->penalty_one = (!empty($data['penalty_one'])) ? $data['penalty_one'] : 0;
        $this->penalty_two = (!empty($data['penalty_two'])) ? $data['penalty_two'] : 0;
        $this->status = (!empty($data['status'])) ? $data['status'] : 0;
        $this->external_id = (!empty($data['external_id'])) ? $data['external_id'] : 0;
    }

    public function exchangeObject($data)
    {
        parent::exchangeObject($data);
        $this->stage_id = $data->stage_id;
        $this->day = $data->day;
        $this->team_one_id = $data->team_one_id;
        $this->result_one = $data->result_one;
        $this->team_two_id = $data->team_two_id;
        $this->result_two = $data->result_two;
        $this->penalty_one = $data->penalty_one;
        $this->penalty_two = $data->penalty_two;
        $this->status = $data->status;
        $this->external_id = $data->external_id;
    }

    public function getInputFilter()
    {
        if ($this->inputFilter) {
            return $this->inputFilter;
        }
                
        $inputFilter = new \Zend\InputFilter\InputFilter();
        $inputFilter->add([
                    'name' => 'stage_id',
                    'required' => true,
                    'filters' => [
                        ['name' => \Zend\Filter\ToInt::class],
                    ],
                ]);
        $inputFilter->add([
                    'name' => 'day',
                    'required' => true
                ]);
        $inputFilter->add([
                    'name' => 'team_one_id',
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
                    'name' => 'team_two_id',
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
                    'name' => 'status',
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