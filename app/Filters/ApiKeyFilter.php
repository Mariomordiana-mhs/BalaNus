<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\API\ResponseTrait;

class ApiKeyFilter implements FilterInterface
{
    use ResponseTrait;

    public function before(RequestInterface $request, $arguments = null)
    {
        $validKey = 'BalaNusSecretKey2026'; // API Key Rahasia Anda

        // 1. Cek apakah ada API Key di Header (untuk aplikasi mobile/server)
        $headerKey = $request->getHeaderLine('X-API-KEY');
        
        // 2. Cek apakah ada API Key di URL Parameter (untuk akses via Browser)
        $urlKey = $request->getGet('key');

        // Jika KEDUANYA tidak cocok dengan kunci rahasia, maka tolak!
        if ($headerKey !== $validKey && $urlKey !== $validKey) {
            $response = service('response');
            return $response->setJSON([
                'status' => false, 
                'message' => 'Akses ditolak! API Key tidak valid atau tidak disertakan.'
            ])->setStatusCode(401);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {}
}