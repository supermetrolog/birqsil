<?php

declare(strict_types=1);

namespace common\forms\base;

use elisdn\compositeForm\CompositeForm as CompositeFormCompositeForm;
use yii\base\Model;

abstract class CompositeForm extends CompositeFormCompositeForm
{
    public function __construct(array $config = [])
    {
        foreach ($this->internalForms() as $key => $name) {
            if (is_numeric($key)) {
                $this->_forms[''] = $this->$name;
            } else {
                $this->_forms[$key] = $this->$name;
            }
        }

        parent::__construct($config);
    }

    public function getErrors($attribute = null): array
    {
        $result = Model::getErrors($attribute);

        foreach ($this->_forms as $name => $form) {

            if (is_array($form)) {
                /** @var Model[] $form */
                foreach ($form as $i => $item) {
                    foreach ($item->getErrors() as $attr => $errors) {
                        /** @var array $errors */
                        $errorAttr = $name . '.' . $i . '.' . $attr;
                        if ($attribute === null) {
                            foreach ($errors as $error) {
                                $result[$errorAttr][] = $error;
                            }
                        } elseif ($errorAttr === $attribute) {
                            foreach ($errors as $error) {
                                $result[] = $error;
                            }
                        }
                    }
                }
            } else {
                foreach ($form->getErrors() as $attr => $errors) {
                    /** @var array $errors */
                    if ($name === '') {
                        $errorAttr = $attr;
                    } else {
                        $errorAttr = $name . '.' . $attr;
                    }
                    if ($attribute === null) {
                        foreach ($errors as $error) {
                            $result[$errorAttr][] = $error;
                        }
                    } elseif ($errorAttr === $attribute) {
                        foreach ($errors as $error) {
                            $result[] = $error;
                        }
                    }
                }
            }
        }
        return $result;
    }
}
