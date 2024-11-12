<?php

namespace App\Http\Controllers;

use App\Services\PetService;
use Exception;
use Illuminate\Http\Request;

class PetController extends Controller
{
    public function __construct(protected PetService $petService)
    {
    }

    public function index()
    {
        $pets = $this->petService->findPetsByStatus('available');
        return view('pets.index', compact('pets'));
    }

    public function create()
    {
        return view('pets.create');
    }

    public function store(Request $request)
    {
        $data = $this->validateAndMapData($request);

        try {
            $response = $this->petService->createPet($data);

            if ($response['status'] === 'success') {
                return redirect()->route('pets.index')->with('message', 'Pet added successfully!');
            }

            return back()->withErrors('Error adding pet: ' . $response['message']);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error adding pet: ' . $e->getMessage()], 500);
        }
    }


    public function show($id)
    {
        try {
            $pet = $this->petService->getPet($id);

            if (!$pet || empty($pet['category'])) {
                return redirect()->route('pets.index')->withErrors('Pet not found or missing category');
            }

            return view('pets.show', compact('pet'));
        } catch (Exception $e) {
            return back()->withErrors('Error fetching pet: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $pet = $this->petService->getPet($id);
        return view('pets.edit', compact('pet'));
    }

    public function update(Request $request, $id)
    {
        $data = $this->validateAndMapData($request);
        $data['id'] = $id;

        try {
            $response = $this->petService->updatePet($id, $data);

            if ($response['status'] === 'success') {
                return redirect()->route('pets.show', $id)->with('message', 'Pet updated successfully!');
            }

            return back()->withErrors('Error updating pet: ' . $response['message']);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error updating pet: ' . $e->getMessage()], 500);
        }
    }


    public function destroy($id)
    {
        try {
            $this->petService->deletePet($id);
            return redirect()->route('pets.index')->with('message', 'Pet deleted successfully');
        } catch (Exception $e) {
            return back()->withErrors('Error deleting pet: ' . $e->getMessage());
        }
    }

    protected function validateAndMapData(Request $request): array
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category.name' => 'required|string|max:255',
            'photoUrls' => 'required|array|min:1',
            'tags' => 'array',
            'status' => 'required|string|in:available,pending,sold',
        ]);

        return [
            'name' => $validated['name'],
            'category' => ['id' => 0, 'name' => $validated['category']['name']],
            'photoUrls' => $validated['photoUrls'],
            'tags' => $this->mapTags($validated['tags'] ?? []),
            'status' => $validated['status'],
        ];
    }

    protected function mapTags(array $tags): array
    {
        return array_map(fn($tag) => ['name' => $tag], $tags);
    }
}
