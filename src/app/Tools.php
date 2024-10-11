<?php


namespace PixellWeb\Paybox\app;



use Spatie\ArrayToXml\ArrayToXml;

class Tools
{

    /**
     * Get parameters like querystring.
     *
     * @param array $parameters
     * @param bool $with_signature_parameter
     * @return string
     */
    static public function getSignedData(array $parameters, bool $with_signature_parameter = false): string
    {
        if (!$with_signature_parameter) {
            unset($parameters[PaymentRequest::PBX_RETOUR_SIGNATURE]);
        }

        return collect($parameters)->map(function ($value, $key) {
            return $key . '=' . $value;
        })->implode('&');
    }


    /**
     * @param string $string
     * @return string
     * @desc https://github.com/codeinchq/strip-accents/blob/master/src/StripAccents.php
     */
    public static function stripAccents(string $string): string
    {
        // converting accents in HTML entities
        $string = htmlentities($string, ENT_NOQUOTES, 'utf-8');

        // replacing the HTML entities to extract the first letter
        // examples: "&ecute;" => "e", "&Ecute;" => "E", "à" => "a" ...
        $string = preg_replace(
            '#&([A-za-z])(?:acute|grave|cedil|circ|orn|ring|slash|th|tilde|uml);#',
            '\1',
            $string
        );

        // replacing ligatures
        // Exemple "œ" => "oe", "Æ" => "AE"
        $string = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $string);

        // removing the remaining bits
        return preg_replace('#&[^;]+;#', '', $string);
    }

    static public function arrayToXml(array $array, string $rootElement): string
    {
        return str_replace("\n", "", ArrayToXml::convert($array, $rootElement));
    }

}
