<?php

class mod_smartlink_generator extends testing_module_generator {

    /**
     * Default values for new smartlink instances.
     *
     * @var array
     */
    protected $defaults = [
        'name' => 'Smart link',
        'intro' => '',
        'introformat' => FORMAT_HTML,
        'url' => '',
    ];

    /**
     * Create a new smartlink activity instance in a course.
     *
     * @param array|stdClass|null $record
     * @param array|null $options
     * @return stdClass the created instance record (with cmid).
     */
    public function create_instance($record = null, ?array $options = null) {
        $record = (object) (array) $record;

        foreach ($this->defaults as $field => $value) {
            if (!isset($record->$field)) {
                $record->$field = $value;
            }
        }

        return parent::create_instance($record, (array) $options);
    }
}
