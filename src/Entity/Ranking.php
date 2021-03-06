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
     * @var string
     */
    public $firstname = '';
    /**
     * @var string
     */
    public $phone = '';
    /**
     * @var string
     */
    public $facebook_id = '';
    /**
     * @var string
     */
    public $photo = '';
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
        $data['firstname'] = $this->firstname;
        $data['phone'] = $this->phone;
        $data['facebook_id'] = $this->facebook_id;
        $data['photo'] = $this->photo;
        return $data;
    }

    public function exchangeArray(array $data)
    {
        parent::exchangeArray($data);
        $this->group_id = (!empty($data['group_id'])) ? $data['group_id'] : 0;
        $this->user_id = (!empty($data['user_id'])) ? $data['user_id'] : 0;
        $this->points = (!empty($data['points'])) ? $data['points'] : 0;
        $this->firstname = (!empty($data['firstname'])) ? $data['firstname'] : '';
        $this->phone = (!empty($data['phone'])) ? $data['phone'] : '';
        $this->facebook_id = (!empty($data['facebook_id'])) ? $data['facebook_id'] : '';
        $this->photo = (!empty($data['photo'])) ? $data['photo'] : '';
    }

    public function exchangeObject($data)
    {
        parent::exchangeObject($data);
        $this->group_id = $data->group_id;
        $this->user_id = $data->user_id;
        $this->points = $data->points;
        $this->firstname = $data->firstname;
        $this->phone = $data->phone;
        $this->facebook_id = $data->facebook_id;
        $this->photo = $data->photo;
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