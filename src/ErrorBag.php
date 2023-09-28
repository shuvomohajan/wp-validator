<?php

namespace BitApps\WPValidator;

class ErrorBag
{
    protected $errors = [];

    public function addError($role, $customMessages)
    {
        $attributeKey = $role->getInputDataContainer()->getAttributeKey();
        $roleName = $role->getRuleName();
        $params = $role->getParamKeys();

        $defaultPlaceholders = [
            'attribute' => $role->getInputDataContainer()->getAttributeLabel(),
            'value' => $role->getInputDataContainer()->getAttributeValue(),
        ];

        $placeholders = array_merge($params, $defaultPlaceholders);

        if (isset($customMessages[$attributeKey][$roleName])) {
            $message = $this->replacePlaceholders($placeholders, $customMessages[$attributeKey][$roleName]);
        } else {
            $message = $this->replacePlaceholders($placeholders, $role->message());
        }

        $this->errors[$attributeKey][] = $message;

    }

    private function replacePlaceholders($placeholders, $message)
    {
        foreach ($placeholders as $key => $placeholder) {
            if (isset($placeholders[$key])) {
                $message = str_replace(":" . $key, $placeholder, $message);
            }
        }
        return $message;
    }

    public function getErrors($field = null)
    {
        return $this->errors;
    }

    public function hasErrors($field = null)
    {
        if ($field === null) {
            return !empty($this->errors);
        }

        return isset($this->errors[$field]) && !empty($this->errors[$field]);
    }
}
