<?php

namespace Social;

class Error
{
    /**
     * @see http://tools.ietf.org/html/draft-ietf-oauth-v2-31#section-4.2.2.1
     */
    const INVALID_REQUEST = 'invalid_request';
    const UNAUTHORIZED_CLIENT = 'unauthorized_client';
    const ACCESS_DENIED = 'access_denied';
    const UNSUPPORTED_RESPONSE_TYPE = 'unsupported_response_type';
    const INVALID_SCOPE = 'invalid_scope';
    const SERVER_ERROR = 'server_error';
    const TEMPORARILY_UNAVAILABLE = 'temporarily_unavailable';

    const INVALID_CODE = 'invalid_code';
    const INVALID_TOKEN = 'invalid_token';

    // OAUTH 1.0 errors
    const INVALID_VERIFIER = 'invalid_verifier';
    const INVALID_RESPONSE = 'invalid_response';

    private $error;
    private $description;

    public function __construct($error, $description = null)
    {
        $this->error = $error;
        $this->description = $description;
    }

    public static function createFromRequest($request, $default = null)
    {
        $error = $default;
        $description = null;

        if (is_string($request)) {
            $error = $request;
        } else {
            if (isset($request['error'])) {
                $error = $request['error'];
            }

            if (isset($request['error_description'])) {
                $description = $request['error_description'];
            }
        }

        return new self($error, $description);
    }

    public function __toString()
    {
        $msg = (string)$this->error;

        if ($this->description) {
            $msg .= ':' . $this->description;
        }

        return $msg;
    }
}