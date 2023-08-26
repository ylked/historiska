<?php

namespace App\Custom;

class SendResponse
{
    public static function success($message, $content = [])
    {
        return response()->json([
            'success' => true,
            'status' => 200,
            'error' => null,
            'message' => $message,
            'content' => $content
        ], 200);
    }

    public static function bad_request($message)
    {
        return response()->json([
            'success' => false,
            'status' => 400,
            'error' => 'BAD REQUEST',
            'message' => $message
        ], 400);
    }

    public static function unauthorized($message)
    {
        return response()->json(
            [
                'success' => false,
                'status' => 401,
                'error' => 'UNAUTHORIZED',
                'message' => $message
            ], 401
        );
    }

    public static function forbidden($message)
    {
        return response()->json(
            [
                'success' => false,
                'status' => 403,
                'error' => 'FORBIDDEN',
                'message' => $message
            ], 403
        );
    }

    public static function not_found($message)
    {
        return response()->json(
            [
                'success' => false,
                'status' => 404,
                'error' => 'NOT FOUND',
                'message' => $message
            ], 404
        );
    }

    public static function too_many_requests($message)
    {
        return response()->json(
            [
                'success' => false,
                'status' => 429,
                'error' => 'TOO MANY REQUESTS',
                'message' => $message
            ], 429
        );
    }
}
