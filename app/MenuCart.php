<?php namespace App;

class MenuCart
{

    private static function lookupItemKey($cart, $itemId)
    {

        foreach ($cart['items'] as $key => $value) {
            if ($value['id'] == $itemId) {
                return $key;
            }
        }

        return NULL;
    }

    private static function lookupGroupKey($cart, $groupId, $items)
    {

        foreach ($cart['groups'] as $key => $value) {
            if ($value['id'] == $groupId && !count($items->diff($value['items']))) {
                return $key;
            }
        }

        return NULL;
    }

    private static function generateGroupKey()
    {
        return uniqid();
    }

    public static function addItem(MenuItem $item, $quantity = 1)
    {

        $cart = self::getCart();

        if (isset($cart['items'])) {

            $itemKey = self::lookupItemKey($cart, $item->id);

            if (!is_null($itemKey)) {
                if ($cart['items'][$itemKey]['quantity'] + $quantity <= 0) {
                    return self::removeItem($item);
                } else {
                    $cart['items'][$itemKey]['quantity'] += $quantity;
                }
            } else {
                $cart['items'][] = [
                    'id' => $item->id,
                    'item' => $item,
                    'quantity' => $quantity
                ];
            }
        } else {
            $cart['items'][] = [
                'id' => $item->id,
                'item' => $item,
                'quantity' => $quantity
            ];
        }

        session()->put('menu.cart', $cart);
    }

    public static function addGroup(MenuGroup $group, $items, $quantity = 1)
    {

        $cart = self::getCart();

        $groupKey = isset($cart['groups']) ? self::lookupGroupKey($cart, $group->id, $items) : null;

        if ($groupKey) {
            if ($cart['groups'][$groupKey]['quantity'] + $quantity <= 0) {
                return self::removeGroup($groupKey);
            } else {
                $cart['groups'][$groupKey]['quantity'] += $quantity;
            }
        } else {
            $itemIds = [];
            foreach ($items as $item) {
                $itemIds[] = $item->id;
            }

            $groupKey = self::generateGroupKey();
            $cart['groups'][$groupKey] = [
                'id' => $group->id,
                'group' => $group,
                'items' => $items,
                'item_ids' => $itemIds,
                'quantity' => $quantity
            ];
        }

        session()->put('menu.cart', $cart);
    }

    public static function incrementGroup($groupKey, $quantity = 1)
    {

        $cart = self::getCart();

        foreach ($cart['groups'] as $key => $group) {
            if ($key == $groupKey) {
                $cart['groups'][$key]['quantity'] += $quantity;
                if ($cart['groups'][$key]['quantity'] < 1) unset($cart['groups'][$key]);
            }
        }
        session()->put('menu.cart', $cart);
    }

    public static function removeItem(MenuItem $item)
    {

        $cart = self::getCart();

        if (isset($cart['items'])) {
            $itemKey = self::lookupItemKey($cart, $item->id);
            if (!is_null($itemKey)) {
                unset($cart['items'][$itemKey]);
                session()->put('menu.cart', $cart);
            }
        }

    }

    public static function removeGroup($groupKey)
    {

        $cart = self::getCart();
        foreach ($cart['groups'] as $key => $group) {
            if ($key == $groupKey) {
                unset($cart['groups'][$key]);
                session()->put('menu.cart', $cart);
            }
        }
    }


    public static function getCart()
    {
        if (!session()->has('menu.cart')) {
            session()->put('menu.cart', []);
            return [];
        } else {
            return session()->get('menu.cart');
        }
    }

    public static function loadCart($data)
    {

        self::clearCart();

        foreach ($data['items'] as $id => $arr) {
            self::addItem($arr['item'], $arr['quantity']);
        }

        foreach ($data['groups'] as $id => $arr) {
            self::addGroup($arr['group'], $arr['items'], $arr['quantity']);
        }
    }

    public static function clearCart()
    {
        session()->put('menu.cart', []);
    }
}