<?php

class Doctrine_Template_Signable extends Doctrine_Template
{
    protected $_options = array('createdby' =>  array('name'          =>  'created_by',
                                                      'alias'         =>  null,
                                                      'type'          =>  'integer',
                                                      'length'        =>  4,
                                                      'disabled'      =>  false,
                                                      'expression'    =>  false,
                                                      'options'       =>  array()),
                                'updatedby' =>  array('name'          =>  'updated_by',
                                                      'alias'         =>  null,
                                                      'type'          =>  'integer',
                                                      'length'        =>  4,
                                                      'disabled'      =>  false,
                                                      'expression'    =>  false,
                                                      'onInsert'      =>  true,
                                                      'options'       =>  array()));

    public function setTableDefinition()
    {
        if( ! $this->_options['createdby']['disabled']) {
            $name = $this->_options['createdby']['name'];
            if ($this->_options['createdby']['alias']) {
                $name .= ' as ' . $this->_options['createdby']['alias'];
            }
            $this->hasColumn(
              $name,
              $this->_options['createdby']['type'],
              $this->_options['updatedby']['length'],
              $this->_options['createdby']['options']
            );
        }

        if( ! $this->_options['updatedby']['disabled']) {
            $name = $this->_options['updatedby']['name'];
            if ($this->_options['updatedby']['alias']) {
                $name .= ' as ' . $this->_options['updatedby']['alias'];
            }
            $this->hasColumn(
              $name,
              $this->_options['updatedby']['type'],
              $this->_options['updatedby']['length'],
              $this->_options['updatedby']['options']
            );
        }

        $this->hasOne('sfGuardUser as CreatedByUser', array('local' => 'created_by',
                                                      'foreign' => 'id',
                                                      'onDelete' => 'set null'));

        $this->hasOne('sfGuardUser as UpdatedByUser', array('local' => 'updated_by',
                                                        'foreign' => 'id',
                                                        'onDelete' => 'set null'));

        $this->addListener(new Doctrine_Template_Listener_Signable($this->_options));
    }
}
