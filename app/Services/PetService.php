<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;

class PetService
{
    protected string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = env('PETSTORE_API_BASE_URL');
    }

    public function createPet(array $data): array
    {
        $payload = $this->createPetPayload($data);
        return $this->sendRequest('post', '/pet', $payload);
    }

    public function getPet(int $id): array
    {
        return $this->sendRequest('get', "/pet/{$id}");
    }

    public function updatePet(int $id, array $data): array
    {
        $payload = $this->createPetPayload(array_merge(['id' => $id], $data));
        return $this->sendRequest('put', '/pet', $payload);
    }

    public function deletePet(int $id): array
    {
        return $this->sendRequest('delete', "/pet/{$id}");
    }

    public function findPetsByStatus(string $status): array
    {
        return $this->sendRequest('get', '/pet/findByStatus', ['status' => $status]);
    }

    protected function sendRequest(string $method, string $endpoint, array $payload = []): array
    {
        $response = Http::$method("{$this->baseUrl}{$endpoint}", $payload);

        if ($response->failed()) {
            throw new Exception("Error during {$method} request to {$endpoint}");
        }

        return $response->json();
    }

    protected function createPetPayload(array $data): array
    {
        return [
            'name' => $data['name'],
            'category' => [
                'id' => $data['category']['id'],
                'name' => $data['category']['name'],
            ],
            'photoUrls' => $data['photoUrls'],
            'tags' => $this->mapTags($data['tags']),
            'status' => $data['status'],
        ];
    }

    protected function mapTags(array $tags): array
    {
        return array_map(fn($tag) => ['name' => $tag['name']], $tags);
    }
}
