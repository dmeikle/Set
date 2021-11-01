<?php
/*
 *  This file is part of the Quantum Unit Solutions development package.
 *
 *  (c) Quantum Unit Solutions <http://github.com/dmeikle/>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Gossamer\Set\Utils;

use Gossamer\Set\Exceptions\ObjectNotFoundException;

class Container
{
    private $directory = array();

    protected $pathDirectory = array();

    protected $bindings = array();

    protected $instantiatedBindings = array();

    /**
     * remove all items from memory
     */
    public function __destruct() {
        while (count($this->directory) > 0) {
            try {
                $item = array_pop($this->directory);
                unset($item);
            } catch (\Exception $e) {

            }
        }
    }

    /**
     * @param $key
     * @return mixed
     * @throws ObjectNotFoundException
     */
    public function get($key) {
        if(array_key_exists($key, $this->pathDirectory)) {
            return $this->getByKey($this->pathDirectory[$key]);
        }

        return $this->getByKey($key);
    }

    private function getByKey(string $key) {
        if (!array_key_exists($key, $this->directory)) {
            //
            //            echo debug_backtrace()[0]['function']."<br>\r\n";
            //            echo debug_backtrace()[1]['function']."<br>\r\n";
            //            echo debug_backtrace()[2]['function']."<br>\r\n";
            //            echo debug_backtrace()[3]['function']."<br>\r\n";
            //            echo debug_backtrace()[4]['function']."<br>\r\n";
            throw new ObjectNotFoundException($key . ' does not exist in container');
        }

        return $this->directory[$key];
    }

    /**
     * @param $key
     * @param $object
     */
    public function set(string $key, &$object, string $fullpath = null) {
        $this->directory[$key] = $object;
        if(!is_null($fullpath)) {
            $this->pathDirectory[$fullpath] = $key;
        }
    }

    public function getKeys() {
        return array_keys($this->directory);
    }

    public function bind($abstract, $concrete = null) {
        if(is_null($concrete)) {
            $concrete = $abstract;
        }

        $this->bindings[$abstract] = $concrete;
    }

    public function getBinding(string $key) {
        if(array_key_exists($key, $this->instantiatedBindings)) {
            return $this->instantiatedBindings[$key];
        }

        return new $this->bindings[$key];
    }
}