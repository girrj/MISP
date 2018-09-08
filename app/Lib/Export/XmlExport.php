<?php

class XmlExport
{
	private $__converter = false;
	public $non_restrictive_export = true;

    public function handler($data, $options = array())
    {
		if ($options['scope'] === 'Attribute') {
			return $this->__attributeHandler($data, $options);
		} else {
			return $this->__eventHandler($data, $options);
		}
    }

	private function __eventHandler($event, $options = array()) {
		if ($this->__converter === false) {
			App::uses('XMLConverterTool', 'Tools');
			$this->__converter = new XMLConverterTool();
		}
		return $this->__converter->convert($event, false);
	}

	private function __attributeHandler($attribute, $options = array())
	{
		$attribute = array_merge($attribute['Attribute'], $attribute);
		unset($attribute['Event']);
		unset($attribute['Attribute']);
		if (isset($attribute['Object']) && empty($attribute['Object']['id'])) {
			unset($attribute['Object']);
		}
		if (isset($attribute['AttributeTag'])) {
			$attributeTags = array();
			foreach ($attribute['AttributeTag'] as $tk => $tag) {
				$attribute['Tag'][$tk] = $attribute['AttributeTag'][$tk]['Tag'];
			}
			unset($attribute['AttributeTag']);
			unset($attribute['value1']);
			unset($attribute['value2']);
		}
		$xmlObject = Xml::fromArray(array('Attribute' => $attribute), array('format' => 'tags'));
		$xmlString = $xmlObject->asXML();
		return substr($xmlString, strpos($xmlString, "\n") + 1);
	}

    public function header($options = array())
    {
		return '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL . '<response>';
    }

    public function footer()
    {
		return '</response>' . PHP_EOL;
    }

    public function separator()
    {
        return '';
    }
}
