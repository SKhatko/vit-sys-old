<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Tree
{

    //items must be indexed with item id
    private static $items;

    //items must be an indexed array
    public static function createFromArray($items)
    {
        if (!is_array($items)) {
            throw new Exception('Could not generate tree. Invalid argument - expecting array');
        }
        self::$items = $items;
        self::prepareArray();
        $res = self::arrayToTree();
        return $res;
    }

    private static function prepareArray()
    {
        foreach (self::$items as $key => $item) {
            self::$items[$key] = [
                'object' => $item,
                'children' => array()
            ];
        }
    }

    private static function arrayToTree()
    {
        $result = array();
        foreach (self::$items as $id => $itemArr) {
            if (!$itemArr['object']->parent_id) {
                $result[$id] = $itemArr;
                $result[$id]['children'] = self::getChildren($id);
            }
        }
        return $result;
    }

    private static function getChildrenOf($id)
    {
        $result = array();
        foreach (self::$items as $thisId => $itemArr) {
            if ($itemArr['object']->parent_id == $id) {
                $result[$thisId] = $itemArr;
            }
        }
        return $result;
    }

    private static function getChildren($id)
    {
        $result = array();
        $children = self::getChildrenOf($id);
        foreach ($children as $id => $itemArr) {
            $result[$id] = $itemArr;
            $result[$id]['children'] = self::getChildren($id);
        }
        return $result;
    }


}
