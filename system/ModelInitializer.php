<?php

namespace system;

class ModelInitializer
{
    private $model;

    public function __construct(&$model)
    {
        $this->setFields($model);
    }

    /**
     * Get the fields of the model and set it as attributes for the model
     *
     * @param $model
     */
    private function setFields(&$model): void
    {
        $fields = $model->getFields();
        $model->attributes = (new \stdClass());

        if (!empty($fields)) {
            foreach ($fields as $field => $type) {
                $model->attributes->$field = null;
            }
        }
    }
}
