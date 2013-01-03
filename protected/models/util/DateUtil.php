<?php

class DateUtil {

    public static function getAge($dateString) {
        if(is_null($dateString) || $dateString == "") return "";
        $now = strtotime('now');
        $dob = strtotime($dateString);

        $diff = abs($now - $dob);
        return floor($diff / (365 * 60 * 60 * 24));
    }

    public static function toSQLDate($originalDate,$format='d/m/Y') {
        $date = DateTime::createFromFormat($format, $originalDate);
        return $date->format('Y-m-d');
    }

    public static function fromSQLDate($sqlDate, $format = 'd/m/Y') {
        if (is_null($sqlDate))
            return null;
        return date($format, strtotime($sqlDate));
    }

}

?>
