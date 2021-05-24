<?php

namespace system;

use system\interfaces\Model;

class BaseModel implements Model
{
    public $table;
    public $fields;
    public $attributes;
    public $where = [];

    public function __construct()
    {
        new ModelInitializer($this);
    }

    /**
     * Return the fields array
     *
     * @return array|null
     */
    public function getFields(): ?array
    {
        return $this->fields;
    }

    /**
     * Get all entries. If where is set then return matching ones
     *
     * @return array|mixed|null
     */
    public function get()
    {
        if (!empty($this->where)) {
            $entries = $this->findEntriesMatchingQuery();
        } else {
            $entries = $this->readTable();
        }

        return $entries;
    }

    /**
     * Return first or all the entries that match the where query
     *
     * @param false $first
     * @return array|mixed
     */
    private function findEntriesMatchingQuery($first = false)
    {
        $entries = $this->readTable();
        $output = [];
        foreach ($entries as $entry) {
            if ($this->isAMatch($entry)) {
                if ($first) {
                    return (new $this())->find($entry->id);
                }
                $output[] = $entry;
            }
        }
        return $output;
    }

    /**
     * Read json file and decode it to an object so it can be used on the system
     *
     * @return mixed|null
     */
    private function readTable()
    {
        try {
            return json_decode(file_get_contents(db . $this->table . '.json'));
        } catch (\Throwable $exception) {
            (new Log())->error($exception, true);
            return null;
        }
    }

    /**
     * Is it a match against the current query?
     *
     * @param $entry
     * @return bool
     */
    private function isAMatch($entry): bool
    {
        $whereArray = $this->where;
        foreach ($whereArray as $index => $query) {
            $operator = $query['operator'];
            $value = $query['match'];
            $field = $query['field'];
            if (!in_array($operator, ['=', '!=', '>', '<', '<=', '>=', 'LIKE', 'NOT LIKE'])) {
                $value = $operator;
                $operator = '=';
            }
            switch ($operator) {
                case 'LIKE':
                case '=':
                    if ($entry->$field !== $value) {
                        return false;
                    }
                    break;
                case 'NOT LIKE':
                case '!=':
                    if ($entry->$field === $value) {
                        return false;
                    }
                    break;
                case '>':
                case '>=':
                    if ($entry->$field < $value) {
                        return false;
                    }
                    break;
                case '<':
                case '<=':
                    if ($entry->$field > $value) {
                        return false;
                    }
                    break;
            }
        }
        return true;
    }

    /**
     * Return specific entry from the DB
     * @param int $id
     * @return ?null
     */
    public function find(int $id)
    {
        $entries = $this->readTable();
        if (is_iterable($entries)) {
            foreach ($entries as $entry) {
                if ($entry->id === $id) {
                    $this->populateWithEntry($entry);
                    return $this;
                }
            }
        }
        return null;
    }

    /**
     * Populate the attributes field
     *
     * @param $entry
     */
    private function populateWithEntry($entry)
    {
        foreach ($entry as $property => $value) {
            $this->attributes->$property = $value;
        }
    }

    /**
     * Get first entry on the DB. If where is set matching the required criteria
     *
     * @return array|mixed
     */
    public function first()
    {
        return $this->findEntriesMatchingQuery(true);
    }

    /**
     * Save any modifications to Model
     *
     * @return $this|false
     */
    public function save()
    {
        if (!empty($this->attributes)) {
            $newEntry = $this->toArray();
            if ($this->attributes->id === null) {
                $newEntry['id'] = $this->total() + 1;
                $this->attributes->id = $newEntry['id'];
            }

            $this->updateDb((object)$newEntry);
            return $this;
        }

        return false;
    }

    /**
     * Convert current model to an array
     *
     * @return array
     */
    private function toArray(): array
    {
        return (array)$this->attributes;
    }

    /**
     * Get total rows in the DB
     * @return int
     */
    public function total(): int
    {
        return count($this->readTable());
    }

    /**
     * Update teh DB file
     *
     * @param $newEntry
     */
    private function updateDb($newEntry)
    {
        $allEntries = $this->readTable();
        $updated = false;
        foreach ($allEntries as $index => $entry) {
            if ($entry->id === $newEntry->id) {
                $allEntries[$index] = $newEntry;
                $updated = true;
            }
        }
        if (!$updated) {
            $allEntries[] = $newEntry;
        }

        file_put_contents(db . $this->table . '.json', json_encode($allEntries));
    }

    /**
     * Add a where query for matching values
     *
     * @param string $field
     * @param $operator
     * @param null $match
     * @return $this
     */
    public function where(string $field, $operator, $match = null)
    {
        $this->where[] = ['field' => $field, 'operator' => $operator, 'match' => $match];
        return $this;
    }

    /**
     * Catch all the attributes calls
     *
     * @param $method
     * @return null
     */
    public function __get($method)
    {
        if (property_exists($this->attributes, $method)) {
            return $this->attributes->$method;
        }
        return null;
    }

    /**
     * Catch all the attributes calls
     *
     * @param $method
     */
    public function __set($method, $value): void
    {
        $this->attributes->$method = $value;
    }

    public function __isset($name)
    {
        if (method_exists($this, ($method = 'isset_' . $name))) {
            return $this->$method();
        }
        return null;
    }

    public function __unset($name)
    {
        if (method_exists($this, ($method = 'unset_' . $name))) {
            $this->$method();
        }
    }

    /**
     * Reset a specific model or all of them to the original set of data
     */
    public function reset(): void
    {
        if (!empty($this->table)) {
            $contents = file_get_contents(dbbackup . $this->table . '.json');
            file_put_contents(db . $this->table . '.json', $contents);
        } else {
            if ($handle = opendir(dbbackup)) {
                while (false !== ($entry = readdir($handle))) {
                    if ($entry != "." && $entry != "..") {
                        $contents = file_get_contents(dbbackup . $entry);
                        $fileName = basename($entry);
                        file_put_contents(db . $fileName, $contents);
                    }
                }
                closedir($handle);
            }
        }
    }
}
