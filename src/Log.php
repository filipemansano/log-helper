<?php

/**
 * Log - helper to display and manipulate messages in console with php
 *
 * @class    Log
 * @author   Filipe Mansano <filipemansano@gmail.com>
 * @url      <https://github.com/filipemansano>
 * @license  The MIT License (MIT) - <http://opensource.org/licenses/MIT>
 */

namespace FilipeMansano;

use Exception;
use ReflectionClass;

class Log
{
    const BACKGROUND_BLACK = 40;
    const BACKGROUND_RED = 41;
    const BACKGROUND_GREEN = 42;
    const BACKGROUND_YELLOW = 43;
    const BACKGROUND_BLUE = 44;
    const BACKGROUND_MAGENTA = 45;
    const BACKGROUND_CYAN = 46;
    const BACKGROUND_LIGHT_GREY = 47;

    const FOREGROUND_BLACK = "0;30";
    const FOREGROUND_RED = "0;31";
    const FOREGROUND_GREEN = "0;32";
    const FOREGROUND_YELLOW = "1;33";
    const FOREGROUND_BLUE = "0;34";
    const FOREGROUND_MAGENTA = "0;35";
    const FOREGROUND_CYAN = "0;36";
    const FOREGROUND_LIGHT_GREY = "0;37";
    const FOREGROUND_WHITE = "1;37";
    const FOREGROUND_BROWN = "0;33";
    const FOREGROUND_DARK_GREY = "1;30";
    const FOREGROUND_LIGHT_RED = "1;31";
    const FOREGROUND_LIGHT_BLUE = "1;34";
    const FOREGROUND_LIGHT_CYAN = "1;36";
    const FOREGROUND_LIGHT_GREEN = "1;32";
    const FOREGROUND_LIGHT_MAGENTA = "1;35";

    const REPLACE_LAST_MESSAGE = -1;

    private static $instance = NULL;
    private $colorsAvaliables = [];
    private $lastMessagePrinted = "";

    public static function getInstance(){

        if (is_null(self::$instance)) {
            self::$instance = new Log();
        }
        
        return self::$instance;
    }
    
    public function __construct(){

        $reflect = new ReflectionClass(get_class($this));
        $constantDefined = $reflect->getConstants();

        $colorsAvaliables = ["FOREGROUND" => [], "BACKGROUND" => []];

        foreach ($constantDefined as $name => $value) {
            if (strpos($name, "FOREGROUND") !== false) {
                $colorsAvaliables["FOREGROUND"][] = $value;
            } elseif (strpos($name, "BACKGROUND") !== false) {
                $colorsAvaliables["BACKGROUND"][] = $value;
            }
        }

        $this->colorsAvaliables = $colorsAvaliables;
    }
    
    public function printError(string $msg, Exception $exception = null){

        $tab = "    ";
        $msgSize = strlen($msg);
        $blankLine = $tab.str_repeat(" ", $msgSize).$tab;

        $this->printMessage(PHP_EOL."{$blankLine}", self::FOREGROUND_WHITE, self::BACKGROUND_RED);
        $this->printMessage($tab.$msg.$tab, self::FOREGROUND_WHITE, self::BACKGROUND_RED);

        if (!is_null($exception)) {

            $detail = $exception->getCode() . " - " . $exception->getMessage();

            $detailSize = strlen($detail);

            // exception message is more than msg size
            if ($detailSize > $msgSize) {

                $textChunked = str_split($detail, $msgSize);
                $linesNumber = count($textChunked);

                foreach ($textChunked as $key => $msgChunked) {
                    if (($key+1) == $linesNumber && strlen($msgChunked) < $msgSize) {
                        $msgChunked .= str_repeat(" ", $msgSize - strlen($msgChunked));
                    }

                    $this->printMessage($tab.$msgChunked.$tab, self::FOREGROUND_WHITE, self::BACKGROUND_RED);
                }

            } 
            
            // exception message is less than msg size
            else if($detailSize < $msgSize) {
                $appendSpaces = str_repeat(" ", $msgSize - $detailSize);
                $this->printMessage($tab.$detail.$appendSpaces.$tab, self::FOREGROUND_WHITE, self::BACKGROUND_RED);
            }
            
            // equals size
            else{
                $this->printMessage($tab.$detail.$tab, self::FOREGROUND_WHITE, self::BACKGROUND_RED);
            }
        }

        $this->printMessage("{$blankLine}", self::FOREGROUND_WHITE, self::BACKGROUND_RED);
    }

    public function printMessage(string $msg, string $foregroundColor = self::FOREGROUND_WHITE, string $backgroundColor = self::BACKGROUND_BLACK, int $breakLines = 1){
        if (!in_array($foregroundColor, $this->colorsAvaliables["FOREGROUND"])) {
            $foregroundColor = self::FOREGROUND_WHITE;
        }

        if (!in_array($backgroundColor, $this->colorsAvaliables["BACKGROUND"])) {
            $backgroundColor = self::BACKGROUND_BLACK;
        }

        if($breakLines === self::REPLACE_LAST_MESSAGE && $this->lastMessagePrinted != ""){
            $size = strlen($this->lastMessagePrinted);
            echo "\033[{$size}D";
        }
        
        $this->lastMessagePrinted = "\e[{$foregroundColor};{$backgroundColor}m{$msg}\e[0m";
        echo $this->lastMessagePrinted;

        if (is_numeric($breakLines) && $breakLines > 0) {
            for ($i = 1; $i <= $breakLines; $i++) {
                echo PHP_EOL;
                $this->lastMessagePrinted = "";
            }
        }
    }
}
