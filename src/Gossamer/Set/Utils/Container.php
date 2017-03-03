<?php
/*
 *  This file is part of the Quantum Unit Solutions development package.
 *
 *  (c) Quantum Unit Solutions <http://github.com/dmeikle/>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

/**
 * Created by PhpStorm.
 * User: user
 * Date: 3/1/2017
 * Time: 10:36 PM
 */

namespace Gossamer\Set\Utils;

use Gossamer\Set\Exceptions\ObjectNotFoundException;

class Container 
{

    private $directory = null;

    public function __destruct() {
        while (count($this->directory) > 0) {
            try {
                $item = array_pop($this->directory);
                unset($item);
            } catch (\Exception $e) {

            }
        }
    }

    public function get($key) {
        $directory = $this->getDirectory();
        if (!array_key_exists($key, $directory)) {
            throw new ObjectNotFoundException($key . ' does not exist in container');
        }

        $item = $directory[$key];

        if (is_null($item['object'])) {
            $item['object'] = new $item['objectPath']();
        }

        $directory[$key] = $item;
        $this->directory = $directory;

        return $item['object'];
    }

    private function getDirectory() {
        if (is_null($this->directory)) {
            $this->directory = array();
        }

        return $this->directory;
    }

    public function set($key, $objectPath, $object = null) {
        $directory = $this->getDirectory();
        $directory[$key] = array('objectPath' => $objectPath, 'object' => $object);
        $this->directory = $directory;
    }
}