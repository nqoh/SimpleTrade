<?php

namespace App;

trait HttpRequest
{
     public function success($data=[], $message , $statusCode=200)
     {
       return response()->json([
              'Status' => 'Successfuly',
              'Data' => $data,
              'Message' => $message
       ], $statusCode);
     }

     public function error($message,$statusCode=400)
     {
          return response()->json([
             'Status' => 'Error',
             'Message' => $message,
          ],$statusCode);
     }
}
