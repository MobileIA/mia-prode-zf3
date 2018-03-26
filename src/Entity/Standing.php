<?php

namespace MIAProde\Entity;

class Standing extends \MIABase\Entity\Base implements \Zend\InputFilter\InputFilterAwareInterface
{
    /**
     * @var int
     */
    public $stage_id = null;

    /**
     * @var int
     */
    public $team_id = null;

    /**
     * @var int
     */
    public $played = null;

    /**
     * @var int
     */
    public $win = null;

    /**
     * @var int
     */
    public $draw = null;

    /**
     * @var int
     */
    public $lost = null;

    /**
     * @var int
     */
    public $goal_f = null;

    /**
     * @var int
     */
    public $goal_c = null;

    /**
     * @var int
     */
    public $goal_diff = null;

    /**
     * @var int
     */
    public $points = null;

    /**
     * @var int
     */
    public $position = null;
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
        $data['team_id'] = $this->team_id;
        $data['played'] = $this->played;
        $data['win'] = $this->win;
        $data['draw'] = $this->draw;
        $data['lost'] = $this->lost;
        $data['goal_f'] = $this->goal_f;
        $data['goal_c'] = $this->goal_c;
        $data['goal_diff'] = $this->goal_diff;
        $data['points'] = $this->points;
        $data['position'] = $this->position;
        return $data;
    }

    public function exchangeArray(array $data)
    {
        parent::exchangeArray($data);
        $this->stage_id = (!empty($data['stage_id'])) ? $data['stage_id'] : 0;
        $this->team_id = (!empty($data['team_id'])) ? $data['team_id'] : 0;
        $this->played = (!empty($data['played'])) ? $data['played'] : 0;
        $this->win = (!empty($data['win'])) ? $data['win'] : 0;
        $this->draw = (!empty($data['draw'])) ? $data['draw'] : 0;
        $this->lost = (!empty($data['lost'])) ? $data['lost'] : 0;
        $this->goal_f = (!empty($data['goal_f'])) ? $data['goal_f'] : 0;
        $this->goal_c = (!empty($data['goal_c'])) ? $data['goal_c'] : 0;
        $this->goal_diff = (!empty($data['goal_diff'])) ? $data['goal_diff'] : 0;
        $this->points = (!empty($data['points'])) ? $data['points'] : 0;
        $this->position = (!empty($data['position'])) ? $data['position'] : 0;
    }

    public function exchangeObject($data)
    {
        parent::exchangeObject($data);
        $this->stage_id = $data->stage_id;
        $this->team_id = $data->team_id;
        $this->played = $data->played;
        $this->win = $data->win;
        $this->draw = $data->draw;
        $this->lost = $data->lost;
        $this->goal_f = $data->goal_f;
        $this->goal_c = $data->goal_c;
        $this->goal_diff = $data->goal_diff;
        $this->points = $data->points;
        $this->position = $data->position;
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
                    'name' => 'team_id',
                    'required' => true,
                    'filters' => [
                        ['name' => \Zend\Filter\ToInt::class],
                    ],
                ]);
        $inputFilter->add([
                    'name' => 'played',
                    'required' => true,
                    'filters' => [
                        ['name' => \Zend\Filter\ToInt::class],
                    ],
                ]);
        $inputFilter->add([
                    'name' => 'win',
                    'required' => true,
                    'filters' => [
                        ['name' => \Zend\Filter\ToInt::class],
                    ],
                ]);
        $inputFilter->add([
                    'name' => 'draw',
                    'required' => true,
                    'filters' => [
                        ['name' => \Zend\Filter\ToInt::class],
                    ],
                ]);
        $inputFilter->add([
                    'name' => 'lost',
                    'required' => true,
                    'filters' => [
                        ['name' => \Zend\Filter\ToInt::class],
                    ],
                ]);
        $inputFilter->add([
                    'name' => 'goal_f',
                    'required' => true,
                    'filters' => [
                        ['name' => \Zend\Filter\ToInt::class],
                    ],
                ]);
        $inputFilter->add([
                    'name' => 'goal_c',
                    'required' => true,
                    'filters' => [
                        ['name' => \Zend\Filter\ToInt::class],
                    ],
                ]);
        $inputFilter->add([
                    'name' => 'goal_diff',
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
        $inputFilter->add([
                    'name' => 'position',
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