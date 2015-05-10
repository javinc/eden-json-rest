<?php //-->

namespace Modules;

use Exception;

class Service
{
    /* Constants
    --------------------------------------------*/
    const CREATED_FIELD = 'created_at';
    const UPDATED_FIELD = 'updated_at';
    const DELETED_FIELD = 'deleted_at';

    /* Public Properties
    --------------------------------------------*/
    /* Protected Properties
    --------------------------------------------*/
    protected static $resource = null;
    protected static $methodsAvailable = [
        'db',
        'search',
        'find',
        'get',
        'create',
        'update',
        'remove'];

    protected static $optionsAvailable = [
        'fields',
        'limits',
        'sorts',
        'filters'];

    /* Public Methods
    --------------------------------------------*/
    // self calling instance
    public static function __callStatic($name, $args)
    {   
        // properties
        $method = strtolower(current($args));
        $params = end($args);

        // normalize name
        // camel-cased name will be underscore separated on db name
        self::$resource = strtolower(preg_replace('/([a-zA-Z])(?=[A-Z])/', '$1_', $name));

        // check args is empty, then create
        // a new instance of class 
        if($method == '_') {
            // single instatnce
            return self::i();
        }

        // check methods avalable
        if(!in_array($method, self::$methodsAvailable)) {
            Helper::panic(
                $name . '::' . $method . '()', 
                'not available');
        }

        return call_user_func_array(array(self, $method), $params);
    }

    // singleton
    // this will return instance of service
    // with Campaign resource and statically
    // and able to call methods
    public static function i()
    {
        static $instatnce = null;
        if ($instatnce === null) {
            $instatnce = new Service();
        }

        return $instatnce;
    }

    public static function db()
    {
        return control()->database();
    }

    public static function search()
    {
        return self::db()->search(self::$resource);
    }

    public static function find($options = array())
    {   
        // check options avalable
        foreach ($options as $name => $option) {
            if(!in_array($name, self::$optionsAvailable)) {
                Helper::panic(
                    self::$resource . '::' . __FUNCTION__ . '()',
                    'option',
                    $name,
                    'not available');
            }
        }

        $search = self::search();

        // fields
        if($property = self::isPropertyExists($options, 'fields')) {
            $search->setColumns(implode(', ', $property));
        }

        // limits
        if($property = self::isPropertyExists($options, 'limits')) {
            if(count($property) != 2) {
                return false;
            }

            $search->setRange($property[1])->setStart($property[0]);
        }

        // sorts
        if($property = self::isPropertyExists($options, 'sorts')) {
            foreach ($property as $field => $type) {
                $search->addSort($field, strtoupper($type));
            }
        }

        // filters
        if($property = self::isPropertyExists($options, 'filters')) {
            foreach ($property as $key => $value) {
                // if array means manual
                // manual adding of filter
                if(is_array($value)) {
                    call_user_func_array(array(
                        $search, 'addFilter'), $value);
                    
                    continue;
                }

                $filterMethod = 'filterBy' . ucfirst(strtolower($key));
                $search->$filterMethod($value);
            }
        }

        // except soft deleted
        $search->addFilter(self::DELETED_FIELD . ' IS NULL');

        try {
            $data = $search->getRows();

            // except soft deleted
            foreach ($data as $key => $value) {
                unset($data[$key][self::DELETED_FIELD]);
            }

            return $data; 
        } catch (Exception $e) {
            Helper::panic($e->getMessage());
        }
    }

    public static function get($options = array())
    {   
        // check empty options
        if(empty($options)) {
            Helper::panic(
                self::$resource . '::' . __FUNCTION__ . '()', 
                'options required,',
                'empty given');

            return;
        }

        // if filter not array it means its an Id
        // special cases of table column naming
        // convention, In this case column id is `id`
        if(!is_array($options)) {
            $options = array('filters' => array('id' => $options));    
        }

        // single row
        $options['limits'] = array(0, 1);

        return current(self::find($options));
    }

    public static function create($fields)
    {   
        // cast array
        $fields = (array) $fields;

        // check empty fields
        if(empty($fields)) {
            Helper::panic(
                self::$resource . '::' . __FUNCTION__ . '()', 
                'fields required,',
                'empty given');

            return;
        }

        // add meta
        $fields[self::CREATED_FIELD] = date("Y-m-d H:i:s");
        $fields[self::UPDATED_FIELD] = date("Y-m-d H:i:s");

        try {
            $id = self::db()
                ->insertRow(self::$resource, $fields)
                ->getLastInsertedId();

            return self::get($id);
        } catch (Exception $e) {
            Helper::panic($e->getMessage());
        }
    }
    
    public static function update($fields, $filters)
    {   
        // check empty fields || filters
        if(empty($fields) || empty($filters)) {
            Helper::panic(
                self::$resource . '::' . __FUNCTION__ . '()', 
                'fields & filters are required,',
                'empty given');

            return;
        }

        // check if exists
        $data = self::get($filters);

        if(empty($data)) {
            return;
        }

        // parse filters
        if(is_array($filters)) {
            foreach ($filters as $key => $filter) {
                $filters[] = array($key . '=%s', $filter);
                unset($filters[$key]);
            }
        } else {
            // if filter not array it means its an Id
            // special cases of table column naming
            // convention, In this case column id is `id`
            $filters = [array('id=%s', $filters)];
        }

        // update meta
        $fields[self::UPDATED_FIELD] = date("Y-m-d H:i:s");

        // new data
        $data = array_merge($data, $fields);

        try {
            self::db()->updateRows(
                self::$resource, 
                $fields,
                $filters);
            
            return $data;
        } catch (Exception $e) {
            Helper::panic($e->getMessage());
        }
    }

    public static function remove($filters)
    {   
        // check empty filters
        if(empty($filters)) {
            Helper::panic(
                self::$resource . '::' . __FUNCTION__ . '()', 
                'filters required');

            return;
        }

        // soft remove only
        if(self::update(array(
            self::DELETED_FIELD => date("Y-m-d H:i:s")),
            $filters)) {
            return true;
        }
    }

    /* Protected Methods
    --------------------------------------------*/
    /* Private Methods
    --------------------------------------------*/
    // Private ctor so nobody else can instance it
    private function __construct()
    {
    }

    private static function isPropertyExists($object, $property)
    {
        if(isset($object[$property]) && is_array($object[$property])) {
            return $object[$property];
        }

        return false;
    }
}