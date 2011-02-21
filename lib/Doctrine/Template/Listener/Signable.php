<?php

class Doctrine_Template_Listener_Signable extends Doctrine_Record_Listener
{
	protected $_options = array();

	public function __construct(array $options)
	{
		$this->_options = $options;
	}

	public function preInsert(Doctrine_Event $event)
	{
		if ( ! $this->_options['createdby']['disabled']) {
			$createdbyName = $event->getInvoker()->getTable()->getFieldName($this->_options['createdby']['name']);
			$modified = $event->getInvoker()->getModified();
			if ( ! isset($modified[$createdbyName])) {
				$event->getInvoker()->$createdbyName = $this->getUserId();
			}
		}

		if ( ! $this->_options['updatedby']['disabled'] && $this->_options['updatedby']['onInsert']) {
			$updatedbyName = $event->getInvoker()->getTable()->getFieldName($this->_options['updatedby']['name']);
			$modified = $event->getInvoker()->getModified();
			if ( ! isset($modified[$updatedbyName])) {
				$event->getInvoker()->$updatedbyName = $this->getUserId();
			}
		}
	}

	public function preUpdate(Doctrine_Event $event)
	{
		if ( ! $this->_options['updatedby']['disabled']) {
			$updatedbyName = $event->getInvoker()->getTable()->getFieldName($this->_options['updatedby']['name']);
			$modified = $event->getInvoker()->getModified();
			if ( ! isset($modified[$updatedbyName])) {
				$event->getInvoker()->$updatedbyName = $this->getUserId();
			}
		}
	}

	public function preDqlUpdate(Doctrine_Event $event)
	{
		if ( ! $this->_options['updatedby']['disabled']) {
			$params = $event->getParams();
			$updatedbyName = $event->getInvoker()->getTable()->getFieldName($this->_options['updatedby']['name']);
			$field = $params['alias'] . '.' . $updatedbyName;
			$query = $event->getQuery();

			if ( ! $query->contains($field)) {
				$query->set($field, '?', $this->getUserId());
			}
		}
	}

	public function getUserId()
	{
		try
		{
			if($user = sfContext::getInstance()->getUser()->getGuardUser())
			{
				return $user->getId();
			} else {
				return null;
			}
		} catch(Exception $e) {
			return null;
		}
	}
}