<?php

    /**
     * Simple Serial generator and validator class
     *
     */
    class simpleSerial
    {

        private $_secret = "";
        private $_pool = "0123456789abcdefghijklmnopqrstuvwxyz";
        private $_serialLength = 6;
        private $_rounds = "10";
        private $_delimiter = "-";

        /**
         * Set secret hash for encryption
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
            $this->_delimiter = $delimiter;
            return true;
        }

        /**
         * Set the number of rounds for hash to run.
         *
         * @param $round    int Rounds.
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
            if(is_int($serialLength) && $serialLength < 37) {
                $this->_serialLength = $serialLength;
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
                $random = $this->generateRandom($this->_pool, $this->_serialLength);

                $hash   = $this->generateHash($random);
                $serial = $random . $this->_delimiter . $hash;

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

            $mySerial = substr($serial, 0, $this->_serialLength);
            $myHash   = substr($serial, $this->_serialLength * -1);

            $hash = $this->generateHash($mySerial);

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
        private function generateHash($data)
        {

            $fullSerial = $this->_secret . $data . $this->_secret . $data;
            $hash       = "";

            for ($i = 0; $i < $this->_rounds; $i++) {
                $hash .= $fullSerial;
                $hash = hash('sha256', $hash);
            }

            $hash = base_convert($hash, 16, 36);

            $hash = substr($hash, 0, $this->_serialLength);

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
        private function generateRandom($pool, $length)
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