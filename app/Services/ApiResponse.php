<?php

namespace App\Services;

use Exception;
use \Illuminate\Support\Facades\Response;

class ApiResponse
{

    public static function success($data, $message = 'success', $code = 200)
    {
        return Response::json([
            'message' => $message,
            'code' => $code,
            'data' => $data,
        ], $code);
    }

    function list($data, $columns, $message = 'success', $code = 200)
    {
        return Response::json([
            'message' => $message,
            'code' => $code,
            'data' => $data,
            'columns' => $columns->columns(),
        ], $code);
    }

    function listings($data, $columns = [], $message = 'success', $code = 200)
    {
        $columnsData = [];

        // if (!empty($columns)) {
        //     $columnsData = array_map(function ($item) {
        //         return $item->columns();
        //     }, $columns);
        // }
        if (!empty($columns)) {
            foreach ($columns as $item) {
                $columnsData = $item->columns();
            }
        }

        return Response::json([
            'message' => $message,
            'code' => $code,
            'data' => $data,
            'columns' => $columnsData,
        ], $code);
    }

    public function listAlt($data, $columns, $message = 'success', $code = 200)
    {
        return Response::json([
            'message' => $message,
            'code' => $code,
            'data' => $data,
            'columns' => $columns,
        ], $code);
    }

    public function lists($data, $columns, $parameter, $message = 'success', $code = 200)
    {
        return Response::json([
            'message' => $message,
            'code' => $code,
            'data' => $data,
            'columns' => $columns->columns($parameter),
        ], $code);
    }

    public static function success_direct($data, $message = 'Sukses', $code = 200)
    {
        return Response::json($data, $code, );
    }

    public static function error($message = 'Data tidak ditemukan', $code = 404, $data = [])
    {
        if ($code === 0) {
            $code = 500;
        } elseif ($code > 999) {
            $code = 500;
        }

        return Response::json([
            'message' => $message,
            'code' => $code,
            'data' => $data,
        ], $code);
    }

    public static function error_code_log($message = 'Tidak Ditemukan', $log = 'error', $data = null)
    {
        return Response::json([
            'message' => $message,
            'log' => $log,
            'code' => 404,
            'data' => $data,
        ], 500);
    }

    public static function error_code($message = 'Tidak Ditemukan', $code = 404)
    {
        return Response::json([
            'message' => $message,
            'code' => $code,
        ], 500);
    }

    public static function error_validation($validator, $message = 'Validasi Gagal!')
    {
        return Response::json([
            'message' => $message,
            'code' => 400,
            'errors' => $validator->errors(),
        ], 400);
    }

    public static function error_relation($message = 'Modul ini memiliki relasi dengan modul lain!')
    {
        return Response::json([
            'code' => 400,
            'message' => $message,
            'errors' => null,
        ], 400);
    }

    public static function direct_response($data, $code = 400)
    {
        if ($code > 1000 || $code <= 0) {
            $code = 500;
        }

        return Response::json($data, $code);
    }

    public static function error_repositories($message = 'Data tidak ditemukan', $code = 404, $data = [])
    {
        return throw new Exception($message, $code);
    }

    public static function store($data, $message = 'Success')
    {
        return static::success($data, $message, 201);
    }

    public static function update($data, $message = 'Success')
    {
        return static::success($data, $message);
    }

    public static function delete($data, $message = 'Berhasil Menghapus Data')
    {
        return static::success($data, $message);
    }

    public static function delete_alt()
    {
        return Response::json(null, 204);
    }
}
