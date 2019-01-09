<?php

class C_NextGen_Serializable
{
    /**
     * Serializes the data
     *
     * @param mixed $value
     * @return string
     */
    public static function serialize($value)
    {
        // Try encoding using JSON. It's usually Unicode safe but still, sometimes trips over things
        $serialized = @json_encode($value);

        if (!$serialized)
        {
            $serialized = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F-\x9F]/u', '', $value);
            $serialized = @json_encode($serialized);
        }

        // Using json_encode here because PHP's serialize is not Unicode safe
        return base64_encode($serialized);
    }

    /**
     * Unserializes data using our proprietary format
     *
     * @throws Exception This method will not unserialize any objects
     * @param string $value
     * @return mixed
     */
    public static function unserialize($value)
    {
        $retval = NULL;
        if (is_string($value))
        {
            $retval = stripcslashes($value);

            if (strlen($value) > 1)
            {
                // We can't always rely on base64_decode() or json_decode() to return FALSE as their documentation
                // claims so check if $retval begins with a: as that indicates we have a serialized PHP object.
                if (strpos($retval, 'a:') === 0)
                {
                    if (self::check_for_serialized_objects($value))
                        throw new Exception(__("NextGen Gallery will not unserialize data with objects", 'nextgen_gallery'));

                    // Record this for later
                    $er = error_reporting(0);

                    // The second parameter was added by PHP 7.0
                    if (version_compare(phpversion(), '7.0', '>=')) {
                        $retval = unserialize($value, array('allowed_classes' => FALSE));
                    }
                    else {
                        $retval = unserialize($value);
                    }

                    // Restore error reporting level
                    error_reporting($er);
                }
                else {
                    // We use json_decode() here because PHP's unserialize() is not Unicode safe.
                    $retval = json_decode(base64_decode($retval), TRUE);
                }
            }
        }

        return $retval;
    }

    /**
     * Determines if a string may hold a serialized PHP object
     *
     * @param $string
     * @return bool
     */
    public static function check_for_serialized_objects($string)
    {
        if (!is_string($string))
            return false;
        $string = trim($string);
        return (bool)preg_match("/O:[0-9]+:/is", $string);
    }
}