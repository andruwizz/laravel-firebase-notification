<?php

namespace App\Notifications\Messages;

class CloudMessagingMessage
{
    public function __construct(
        public ?string $title = '',
        public ?string $body = '',
        public ?array $data = []
    ) {
        // 
    }

    /**
     * Set notification title
     * 
     * @param string $title
     * 
     * @return self
     */
    public function title(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Set notification body
     * 
     * @param string $body
     * 
     * @return self
     */
    public function body(?string $body): self
    {
        $this->body = $body;

        return $this;
    }

    /**
     * Set notification data
     * 
     * @param array $data
     * 
     * @return self
     */
    public function data(?array $data): self
    {
        $this->data = $data;

        return $this;
    }
}