<?php

namespace App\Lib;

// use Symfony\Component\Console\Exception\InvalidArgumentException;
// use function Symfony\Component\String\u;

class Validator
{
        public function checkDateTime(string $strDate): ?\DateTime
        {
                if (preg_match("/^(\d{2})-(\d{2})-(\d{4})$/", $strDate)) { //Date
                        if ($usrDate = \DateTime::createFromFormat('d-m-Y', $strDate)) {
                                return $usrDate;
                        }
                }
                else if (preg_match("/^(\d{2})-(\d{2})-(\d{4}) (\d{2}):(\d{2})$/", $strDate)) {//Date+Time
                        if ($usrDate = \DateTime::createFromFormat('d-m-Y H:i', $strDate)) {
                                return $usrDate;
                        }
                }
                else {
                        throw new \Exception('Bad DateTime Format... 00-00-0000 00:00');
                }
        }

        public function validateUsername(?string $username): string
        {
                if (empty($username)) {
                        throw new InvalidArgumentException('The username can not be empty.');
                }

                if (1 !== preg_match('/^[a-z_]+$/', $username)) {
                        throw new InvalidArgumentException('The username must contain only lowercase latin characters and underscores.');
                }

                return $username;
        }

        public function validatePassword(?string $plainPassword): string
        {
                if (empty($plainPassword)) {
                        throw new InvalidArgumentException('The password can not be empty.');
                }

                if (u($plainPassword)->trim()->length() < 6) {
                        throw new InvalidArgumentException('The password must be at least 6 characters long.');
                }

                return $plainPassword;
        }

        public function validateEmail(?string $email): string
        {
                if (empty($email)) {
                        throw new InvalidArgumentException('The email can not be empty.');
                }

                if (null === u($email)->indexOf('@')) {
                        throw new InvalidArgumentException('The email should look like a real email.');
                }

                return $email;
        }

        public function validateFullName(?string $fullName): string
        {
                if (empty($fullName)) {
                        throw new InvalidArgumentException('The full name can not be empty.');
                }

                return $fullName;
        }

}
