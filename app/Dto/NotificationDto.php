<?php

namespace App\Dto;

class NotificationDto
{
    public function __construct(
        public string $title,
        public string $body,
        public array $data = [],
        public bool $debug = false
    ) {
        //    
    }
}