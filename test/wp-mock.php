<?php

declare(strict_types=1);

if (!class_exists('WP_Query')) {
    class WP_Query
    {
        /** @var array<string, mixed> */
        public $queryVars;

        /**
         * @param array<string, mixed> $queryVars
         */
        public function __construct(array $queryVars)
        {
            $this->queryVars = $queryVars;
        }

        /**
         * @param mixed $default
         * @return mixed
         */
        public function get(string $queryVar, $default = '')
        {
            return $this->queryVars[$queryVar] ?? $default;
        }

        public function have_posts(): bool
        {
            return false;
        }
    }
}

if (!class_exists('WP_REST_Response')) {
    class WP_REST_Response
    {
        private $data;
        private $status;
        private $headers;

        /**
         * @param array<string, mixed>|null $data
         * @param int $status
         * @param array<string, mixed>|null $headers
         */
        public function __construct(?array $data = null, int $status = 200, ?array $headers = null)
        {
            $this->data = $data ?? [];
            $this->status = $status;
            $this->headers = $headers ?? [];
        }

        /**
         * @return array<string, mixed>
         */
        public function get_data(): array
        {
            return $this->data;
        }

        public function get_status(): int
        {
            return $this->status;
        }

        /**
         * @return array<string, mixed>
         */
        public function get_headers(): array
        {
            return $this->headers;
        }
    }
}
