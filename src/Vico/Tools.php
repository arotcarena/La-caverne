<?php

namespace App\Vico;


class Tools
{
   

    public static function hydrate(Object $object, array $data):Object
    {
        foreach($data as $key => $value)
        {
            if(!empty($value))
            {
                $method = 'set'.ucfirst($key);
                if(method_exists($object, $method))
                {
                    $object->$method($value);
                }
                if(str_contains($key, '_'))
                {
                    $parts = explode('_', $key);
                    $parts = array_map(function ($part) {
                        return ucfirst($part);
                    }, $parts);
                    $method = implode('', $parts);
                    if(method_exists($object, $method))
                    {
                        $object->$method($value);
                    }
                }
            }
        }
        return $object;
    }

   
}