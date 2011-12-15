<?php

    /**
     * Simple Serial generator class
     *
     */
    class simpleSerial
    {

        private $_secret = "";
        private $_pool = "0123456789abcdefghijklmnopqrstuvwxyz";
        private $_serialLength = 6;

        public $delimiter = "-";

        /**
         * Set secret hash for encryption
         *
         * @param $secret   string  Secret key for the serial
         *
         * @return bool
         */
        public function setSecret($secret)
        {
            if (is_string($secret) && strlen($secret) > 6) {
                $this->_secret = $secret;
                return true;
            }
            return false;
        }

        /**
         * Generate some serials
         *
         * @param $number   int     Number of serials
         *
         * @return array            An array of serials
         */
        public function generateSerials($number)
        {
            $serials = array();

            for ($i = 1; $i <= $number; $i++) {
                $random     = $this->generateRandom($this->_pool, $this->_serialLength);
                $fullSerial = $random . $this->_secret;
                $md5        = md5($fullSerial);
                $serial     = $random . $this->delimiter . substr($md5, 0, $this->_serialLength);

                array_push($serials, $serial);
            }
            return $serials;
        }

        /**
         * Validate serial
         *
         * @param $key      string  Serial to validate
         *
         * @return bool             True if serial is valid, else false
         */
        public function validateSerial($key)
        {

            $myKey = substr($key, 0, $this->_serialLength) . $this->_secret;
            $myMd5 = substr($key, $this->_serialLength * -1);
            $md5   = substr(md5($myKey), 0, $this->_serialLength);

            if ($md5 == $myMd5) {
                return true;
            }
            return false;

        }

        /**
         * Generate some random junk
         *
         * @param $pool     string  Character pool
         * @param $length   int     Length of the random string
         *
         * @return string
         */
        private function generateRandom($pool, $length)
        {

            $random = '';

            for ($i = 1; $i <= $length; $i++) {

                $index = mt_rand(0, (strlen($pool) -1));
                $char  = $pool[$index];
                $random .= $char;
            }

            return $random;

        }
    }

?>