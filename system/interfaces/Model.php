<?php

namespace system\interfaces;

interface Model
{
    public function __construct();

    public function getFields(): ?array;

    /**
     * Get all entries for model
     *
     * @return mixed
     */
    public function get();

    /**
     * Find a specific entry on the Model
     * @param int $id
     * @return mixed
     */
    public function find(int $id);

}
