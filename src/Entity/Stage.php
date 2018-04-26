<?php

namespace MIAProde\Entity;

/**
 * Description of Stage
 *
 * @author matiascamiletti
 */
class Stage extends \MIABase\Entity\Base implements \Zend\InputFilter\InputFilterAwareInterface
{
    /**
     * @var int
     */
    public $tournament_id = null;
    
    /**
     * @var string
     */
    public $title = null;
    
    /**
     * @var int
     */
    public $num = null;

    /**
     * @var string
     */
    public $start = null;

    /**
     * @var string
     */
    public $end = null;

    /**
     * @var int
     */
    public $has_penalty = null;
    
    /**
     * @var int
     */
    public $max_points = 5;
    public $max_points_two = 5;
    public $max_points_three = 5;
    
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
        $data['tournament_id'] = $this->tournament_id;
        $data['title'] = $this->title;
        $data['num'] = $this->num;
        $data['start'] = $this->start;
        $data['end'] = $this->end;
        $data['external_id'] = $this->external_id;
        $data['has_penalty'] = $this->has_penalty;
        $data['max_points'] = $this->max_points;
        $data['max_points_two'] = $this->max_points_two;
        $data['max_points_three'] = $this->max_points_three;
        return $data;
    }

    public function exchangeArray(array $data)
    {
        parent::exchangeArray($data);
        $this->tournament_id = (!empty($data['tournament_id'])) ? $data['tournament_id'] : 0;
        $this->title = (!empty($data['title'])) ? $data['title'] : '';
        $this->num = (!empty($data['num'])) ? $data['num'] : 0;
        $this->start = (!empty($data['start'])) ? $data['start'] : '';
        $this->end = (!empty($data['end'])) ? $data['end'] : '';
        $this->external_id = (!empty($data['external_id'])) ? $data['external_id'] : 0;
        $this->has_penalty = (!empty($data['has_penalty'])) ? $data['has_penalty'] : 0;
        $this->max_points = (!empty($data['max_points'])) ? $data['max_points'] : 5;
        $this->max_points_two = (!empty($data['max_points_two'])) ? $data['max_points_two'] : 5;
        $this->max_points_three = (!empty($data['max_points_three'])) ? $data['max_points_three'] : 5;
    }

    public function exchangeObject($data)
    {
        parent::exchangeObject($data);
        $this->tournament_id = $data->tournament_id;
        $this->title = $data->title;
        $this->num = $data->num;
        $this->start = $data->start;
        $this->end = $data->end;
        $this->external_id = $data->external_id;
        $this->has_penalty = $data->has_penalty;
        $this->max_points = $data->max_points;
        $this->max_points_two = $data->max_points_two;
        $this->max_points_three = $data->max_points_three;
    }

    public function getInputFilter()
    {
        if ($this->inputFilter) {
            return $this->inputFilter;
        }
                
        $inputFilter = new \Zend\InputFilter\InputFilter();
        $inputFilter->add([
                    'name' => 'title',
                    'required' => true,
                    'filters' => [
                        ['name' => \Zend\Filter\StripTags::class],
                        ['name' => \Zend\Filter\StringTrim::class],
                    ],
                    'validators' => [
                        [
                            'name' => \Zend\Validator\StringLength::class,
                            'options' => [
                                'encoding' => 'UTF-8',
                                'min' => 1,
                                'max' => 100,
                            ],
                        ],
                    ],
                ]);
        $inputFilter->add([
                    'name' => 'start',
                    'required' => true,
                    'filters' => [
                        ['name' => \Zend\Filter\StripTags::class],
                        ['name' => \Zend\Filter\StringTrim::class],
                    ],
                    'validators' => [
                        [
                            'name' => \Zend\Validator\StringLength::class,
                            'options' => [
                                'encoding' => 'UTF-8',
                                'min' => 1,
                                'max' => 100,
                            ],
                        ],
                    ],
                ]);
        $inputFilter->add([
                    'name' => 'end',
                    'required' => false,
                    'filters' => [
                        ['name' => \Zend\Filter\StripTags::class],
                        ['name' => \Zend\Filter\StringTrim::class],
                    ],
                    'validators' => [
                        [
                            'name' => \Zend\Validator\StringLength::class,
                            'options' => [
                                'encoding' => 'UTF-8',
                                'min' => 1,
                                'max' => 100,
                            ],
                        ],
                    ],
                ]);
        $inputFilter->add([
                    'name' => 'external_id',
                    'required' => true,
                    'filters' => [
                        ['name' => \Zend\Filter\ToInt::class],
                    ],
                ]);
        $inputFilter->add([
                    'name' => 'country_id',
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