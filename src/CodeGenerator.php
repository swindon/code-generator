<?php

namespace Swindon\CodeGenerator;

use Swindon\CodeGenerator\Exceptions\CodeGeneratorException;

class CodeGenerator {

    /**
     * Setup character sets
     */
    const CHAR_UPPER = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    const CHAR_LOWER = 'abcdefghijklmnopqrstuvwxyz';
    const CHAR_NUMERIC = '0123456789';
    const CHAR_SYMBOLS = '!"#$%&\'()*+,-./:;<=>?@[\]^_`{|}~';

    const CHAR_ALPHA = self::CHAR_UPPER . self::CHAR_LOWER;
    const CHAR_ALPHANUMERIC = self::CHAR_ALPHA . self::CHAR_NUMERIC;
    const CHAR_UPPER_ALPHANUMERIC = self::CHAR_UPPER . self::CHAR_NUMERIC;
    const CHAR_LOWER_ALPHANUMERIC = self::CHAR_LOWER . self::CHAR_NUMERIC;
    const CHAR_ALL = self::CHAR_ALPHANUMERIC . self::CHAR_SYMBOLS;

    private $ambiguous_chars = 'B8G6I1l|0OQDS5Z2()[]{}:;,.\'"`!$-~';

    /**
     * The different characters, by flag
     * @var array
     */
    protected $charArrays = array(
        self::CHAR_ALPHANUMERIC,
        self::CHAR_ALPHA,
        self::CHAR_UPPER,
        self::CHAR_LOWER,
        self::CHAR_NUMERIC,
        self::CHAR_SYMBOLS,
        self::CHAR_UPPER_ALPHANUMERIC,
        self::CHAR_LOWER_ALPHANUMERIC,
        self::CHAR_ALL,
    );

    /**
     * The characters to be used
     */
    private $chars = 0;

    /**
     * Flag removal of ambiguous characters
     */
    private $removeAmbiguous = false;

    /**
     * Construct
     */
    public function __construct()
    {
        $this->setCharacters($this->chars,$this->removeAmbiguous);
    }

    /**
     * Generates a random code
     *
     * @param   $length             int
     *
     * @return string
     */
    public function generate(int $length = 8) : string
    {
        // Check min length
        if( !$length || $length < 1 ) throw new CodeGeneratorException('Min. length of 1 or above.', 1);

        // return random string
        return substr( str_shuffle( implode( '', array_fill( 0, $length, $this->chars ) ) ), 0, $length );
    }

    /**
     * Generates an array of random codes
     *
     * @param   $maxNum             int
     * @param   $length             int
     *
     * @return  array
     */
    public function bulk(int $maxNum, int $length = 8) : array
    {
        // Check codes can be created
        if( $maxNum > pow(strlen($this->chars),$length) ) {
            if( $this->removeAmbiguous ) {
                throw new CodeGeneratorException('Cannot generate more than '.$this->humanReadableNumber(pow(strlen($this->chars),$length)).' possible unique codes. Try increasing the code length and/or setting "Remove Ambiguous Characters" to false.', 1);
            } else {
                throw new CodeGeneratorException('Cannot generate more than '.$this->humanReadableNumber(pow(strlen($this->chars),$length)).' possible unique codes. Try increasing the code length.', 1);
            }
        }

        // Setup array
        $codes = [];
        // Chunk size
        $maxBatch = 10000;
        // Loop
        while (count($codes) < $maxNum) {
            foreach( array_unique(str_split($this->generate(min($maxNum-count($codes),$maxBatch)*$length,$this->chars),$length)) as $code ) {
                $codes[$code] = $code;
            }
        }
        // Return codes
        return array_values($codes);
    }

    /**
     * Set character set
     *
     * @param   $chars              string|int
     * @param   $removeAmbiguous    boolean
     *
     * @return  CodeGenerator
     */
    public function setCharacters($chars = 0, bool $removeAmbiguous = false) : CodeGenerator
    {
        $this->chars = $this->chars($chars,$removeAmbiguous);
        return $this;
    }

    /**
     * Set removal of ambiguous characters
     *
     * @param   $chars              boolean
     * @param   $removeAmbiguous    boolean
     *
     * @return  CodeGenerator
     */
    public function setAmbiguous(bool $removeAmbiguous = true, ?string $ambiguous_chars) : CodeGenerator
    {
        if( $ambiguous_chars ) $this->ambiguous_chars = $ambiguous_chars;
        $this->removeAmbiguous = $removeAmbiguous;
        return $this;
    }

    /**
     * Get character set
     *
     * @param   $chars              string|int
     * @param   $removeAmbiguous    boolean
     *
     * @return  string
     */
    private function chars($chars = 0, bool $removeAmbiguous = false) : string
    {
        // Setup characters
        if( is_string($chars) ) {
            $chars = $chars;
        } else if( is_null($chars) || is_int($chars) ) {
            $chars = $this->charArrays[$chars]??$this->charArrays[0];
        } else {
            throw new CodeGeneratorException('Invalid character set.',1);
        }
        // Remove ambiguous characters
        if( $removeAmbiguous ) {
            $chars = str_replace(str_split($this->ambiguous_chars), '', $chars);
        }
        // Ensure characters in string
        if( !strlen($chars) ) {
            throw new CodeGeneratorException('Invalid character set.',1);
        }
        return $chars;
    }

    /**
     * Human readable numbers
     *
     * @param   $numbers            int
     *
     * @return string
     */
    private function humanReadableNumber($numbers)
    {
       $readable = array(
            '',
            'million',
            'billion',
            'quadrillion',
            'quintillion',
            'sextillion',
            'septillion',
            'octillion',
            'nonillion',
            'decillion',
            'undecillion',
            'duodecillion',
            'tredecillion',
            'quatttuor-decillion',
            'quindecillion',
            'sexdecillion',
            'septen-decillion',
            'octodecillion',
            'novemdecillion',
            'vigintillion',
            'centillion',
       );
       $index=0;
       while($numbers >= 1000000){
            $numbers /= 1000000;
            $index++;
       }
       return (preg_match('/\.00?$/',number_format(round($numbers, 2),2))?number_format(round($numbers, 2)):number_format(round($numbers, 2),2)).($readable[$index]?" ".$readable[$index]:'');
    }

}
