<?php

    /**
     * Simple Serial generator and validator class.
     *
     * @author  Attila Kerekes
     * @link    http://www.attilakerekes.com/
     * @version 1.0
     */
    class simpleSerial
    {

        private $_secret = "";
        private $_pool = "0123456789abcdefghijklmnopqrstuvwxyz";
        private $_serialLength = 20;
        private $_rounds = 10;
        private $_count = 1;
        private $_delimiter = "-";

        /**
         * Set secret key for encryption
         *
         * @param $secret   string  Secret serial for the serial
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
         * Set the delimiter in the serial.
         *
         * @param $delimiter    string  The delimiter.
         *
         * @return bool
         */
        public function setDelimiter($delimiter)
        {
            if (in_array($delimiter, array('-', '_', ' ', ':'))) {
                $this->_delimiter = $delimiter;
                return true;
            }
            return false;
        }

        /**
         * Set the number of rounds for hash to run.
         *
         * @param $round    int     Rounds.
         *
         * @return bool
         */
        public function setRounds($round)
        {
            if (is_int($round)) {
                $this->_rounds = $round;
                return true;
            }
            return false;
        }

        /**
         * Set character pool for random generator (first part of serial).
         *
         * @param $pool     string  Pool for random generator.
         *
         * @return bool
         */
        public function setPool($pool)
        {
            if (is_string($pool)) {
                $this->_pool = $pool;
                return true;
            }
            return false;
        }

        /**
         * Set the length of one serial block.
         *
         * @param $serialLength int Length of the serial
         *
         * @return bool
         */
        public function setSerialLength($serialLength)
        {
            if (is_int($serialLength) && $serialLength < 37 && ($serialLength & 1)) {
                $this->_serialLength = $serialLength;
                return true;
            }
            return false;
        }

        /**
         * Set how many serials you want to generate.
         *
         * @param $count    int     Number of serials to generate.
         *
         * @return bool
         */
        public function setCount($count)
        {
            if (is_int($count) && $count <= 64) {
                $this->_count = $count;
                return true;
            }
            return false;
        }

        /**
         * Generate some serials
         *
         * @return array            An array of serials
         */
        public function generateSerials()
        {
            $serials = array();

            for ($i = 1; $i <= $this->_count; $i++) {
                $random = $this->_generateRandom($this->_pool, ($this->_serialLength / 2));

                $hash   = $this->_generateHash($random);
                $serial = $random . $hash;

                $serial = implode($this->_delimiter, str_split($serial, 5));

                array_push($serials, $serial);
            }
            return $serials;
        }

        /**
         * Validate serial
         *
         * @param $serial   string  Serial to validate
         *
         * @return bool             True if serial is valid, else false
         */
        public function validateSerial($serial)
        {
            $serial   = str_replace($this->_delimiter, '', $serial);
            $mySerial = substr($serial, 0, ($this->_serialLength / 2));
            $myHash   = substr($serial, ($this->_serialLength / 2) * -1);

            $hash = $this->_generateHash($mySerial);

            if ($hash == $myHash) {
                return true;
            }
            return false;

        }

        /**
         * Hash generator.
         *
         * @param $data     string  Data to hash.
         *
         * @return string           Hash converted to base 36.
         */
        private function _generateHash($data)
        {

            $fullSerial = $this->_secret . $data . $this->_secret . $data;
            $hash       = "";

            for ($i = 0; $i < $this->_rounds; $i++) {
                $hash .= $fullSerial;
                $hash = hash('sha256', $hash);
            }

            $hash = base_convert($hash, 16, 36);

            $hash = substr($hash, 0, ($this->_serialLength / 2));

            return $hash;
        }

        /**
         * Generate some random junk
         *
         * @param $pool     string  Character pool
         * @param $length   int     Length of the random string
         *
         * @return string
         */
        private function _generateRandom($pool, $length)
        {

            $random = '';

            for ($i = 1; $i <= $length; $i++) {

                $index = mt_rand(0, (strlen($pool) - 1));
                $char  = $pool[$index];
                $random .= $char;
            }

            return $random;

        }
    }

?>