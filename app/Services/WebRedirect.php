<?php

namespace App\Services;

class WebRedirect
{
    public static function success($route, $message, $code = 200)
    {
        return redirect()->route($route)
                         ->with([
                            'success' => $message,
                            'code' => $code,
                        ]);
    }

    public static function successBack($message = 'Berhasil Menambah Data', $code = 200)
    {
        return redirect()->back()
                         ->with([
                            'success' => $message,
                            'code' => $code,
                        ]);
    }

    public static function successReturn($route, $parameter, $parameter_id, $message = 'Berhasil Menambah Data', $code = 200)
    {
        return redirect()->route($route, [$parameter => $parameter_id])
                 ->with([
                    'success' => $message,
                    'code' => $code,
                ]);
    }

    public static function updateBack($message = 'Berhasil Mengupdate Data', $code = 200)
    {
        return redirect()->back()
                         ->with([
                            'success' => $message,
                            'code' => $code,
                        ]);
    }

    public static function updateReturn($route, $parameter, $parameter_id, $message = 'Berhasil Mengupdate Data', $code = 200)
    {
        return redirect()->route($route, [$parameter => $parameter_id])
                 ->with([
                    'success' => $message,
                    'code' => $code,
                ]);
    }

    public static function destroyBack($message = 'Berhasil Menghapus Data', $code = 200)
    {
        return redirect()->back()
                         ->with([
                            'success' => $message,
                            'code' => $code,
                        ]);
    }

    public static function error($message, $code = 404)
    {
        return redirect()->back()
                         ->with([
                            'error' => $message,
                            'code' => $code,
                        ])->withInput();
    }

    public static function error_validation($e)
    {
        $errors = $e->validator->errors()->toArray();
        return redirect()->back()->withErrors($errors)->withInput();
    }

    public static function store($route, $message = 'Berhasil Menambah Data')
    {
        return static::success($route, $message);
    }

    public static function update($route, $message = 'Berhasil Mengupdate Data')
    {
        return static::success($route, $message);
    }

    public static function destroy($route, $message = 'Berhasil Menghapus Data')
    {
        return static::success($route, $message);
    }
}
