<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;
use Illuminate\Support\Facades\Log;

trait HandlesExceptions
{
    /**
     * Handle model not found exceptions.
     *
     * @param int $id
     * @param string $message
     * @throws ModelNotFoundException
     */
    public function handleModelNotFound($id, $message = 'Record not found')
    {
        Log::error($message . 'with ID: ' . $id);
        throw new ModelNotFoundException($message);
    }

    /**
     * Handle generic exceptions.
     *
     * @param string $message
     * @throws Exception
     */
    public function handleGeneralError($message = 'An error occurred')
    {
        Log::error($message);
        throw new Exception($message);
    }

    /**
     * Handle unauthorized action exceptions.
     *
     * @param string $message
     * @throws Exception
     */
    public function handleUnauthorized($message = 'You do not have permission to perform this action')
    {
        Log::warning($message);
        throw new Exception($message);
    }
}
