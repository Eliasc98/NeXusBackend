<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{
    public function index()
    {
        $contacts = Auth::user()->contacts()->latest()->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Contacts fetched successfully.',
            'data' => $contacts,
        ]);
    }

    public function search(Request $request)
    {
        $user = auth()->user();
        $query = $request->query('q');

        $contacts = $user->contacts()
            ->when($query, function ($q) use ($query) {
                $q->where('name', 'like', "%$query%")
                ->orWhere('email', 'like', "%$query%")
                ->orWhere('phone', 'like', "%$query%");
            })
            ->latest()
            ->take($query ? 100 : 3)
            ->get();

        if ($contacts->isEmpty()) {
            return response()->json([
                'status' => 'success',
                'message' => $query
                    ? 'No matching contacts found.'
                    : 'No recent contacts found.'
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => $query ? 'Search results' : 'Default recent contacts',
            'data' => $contacts,
        ]);
    }

    public function searchApi(Request $request)
{
    $user = auth()->user();
    $query = $request->input('q');

    if (!$query) {
        return response()->json([
            'status' => 'error',
            'message' => 'Please enter a contact name, email, or phone number to search.'
            
        ], 400);
    }

    $contacts = $user->contacts()
        ->where(function ($q1) use ($query) {
            $q1->where('name', 'like', "%$query%")
                ->orWhere('email', 'like', "%$query%")
                ->orWhere('phone', 'like', "%$query%");
        })
        ->get();

    if ($contacts->isEmpty()) {
        return response()->json([
            'status' => 'success',
            'message' => 'No matching contact found.'
        ]);
    }

    return response()->json([
        'status' => 'success',
        'message' => 'Contact Fetched Successfully',
        'data' => $contacts
    ]);
}

    // 
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'  => 'nullable|string',
            'email' => 'nullable|email',
            'phone' => 'required|string',
        ]);

        $contact = Auth::user()->contacts()->create($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Contact created successfully.',
            'data' => $contact,
        ], 201);
    }

    // View single contact
    public function show($id)
    {
        $contact = Auth::user()->contacts()->find($id);

        if (!$contact) {
            return response()->json([
                'status' => 'error',
                'message' => 'Contact not found.'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $contact,
        ]);
    }

    // Update contact
    public function update(Request $request, $id)
    {
        $contact = Auth::user()->contacts()->find($id);

        if (!$contact) {
            return response()->json([
                'status' => 'error',
                'message' => 'Contact not found.'
            ], 404);
        }

        $data = $request->validate([
            'name'  => 'nullable|string',
            'email' => 'nullable|email',
            'phone' => 'required|string',
        ]);

        $contact->update($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Contact updated successfully.',
            'data' => $contact,
        ]);
    }

    // Delete contact
    public function destroy($id)
    {
        $contact = Auth::user()->contacts()->find($id);

        if (!$contact) {
            return response()->json([
                'status' => 'error',
                'message' => 'Contact not found.'
            ], 404);
        }

        $contact->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Contact deleted successfully.',
        ]);
    }
}
