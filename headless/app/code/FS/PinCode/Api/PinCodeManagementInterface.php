<?php

namespace FS\PinCode\Api;

interface PinCodeManagementInterface
{
  /**
     * GET for get api
     * @param string $pin_code
     * @return string
     */
    public function getApiId($pin_code);
}