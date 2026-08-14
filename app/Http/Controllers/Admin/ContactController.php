<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreContactRequest;
use App\Http\Requests\UpdateContactRequest;
use App\Models\Contact;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests; 

class ContactController extends Controller
{
    use AuthorizesRequests; 
    
    public function index(Request $request)
    {
        $this->authorize('viewAny', Contact::class);
        
        $query = Contact::with('customer');

        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $contacts = $query->latest()->paginate(10)->withQueryString();
        $customers = Customer::orderBy('company_name')->get();

        return view('admin.contacts.index', compact('contacts', 'customers'));
    }

    public function create(Request $request)
    {
        $this->authorize('create', Contact::class);
        
        $customers = Customer::orderBy('company_name')->get();
        $selectedCustomerId = $request->query('customer_id');

        return view('admin.contacts.create', compact('customers', 'selectedCustomerId'));
    }

    public function store(StoreContactRequest $request)
    {
        $this->authorize('create', Contact::class);
        
        $data = $request->validated();
        $data['is_primary'] = $request->boolean('is_primary');

        if ($data['is_primary']) {
            Contact::where('customer_id', $data['customer_id'])
                   ->update(['is_primary' => false]);
        }

        Contact::create($data);

        return redirect()->route('admin.contacts.index')
            ->with('success', 'Contact berhasil ditambahkan.');
    }

    public function show(Contact $contact)
    {
        $this->authorize('view', $contact);
        
        $contact->load('customer');
        return view('admin.contacts.show', compact('contact'));
    }

    public function edit(Contact $contact)
    {
        $this->authorize('update', $contact);
        
        $customers = Customer::orderBy('company_name')->get();
        return view('admin.contacts.edit', compact('contact', 'customers'));
    }

    public function update(UpdateContactRequest $request, Contact $contact)
    {
        $this->authorize('update', $contact);
        
        $data = $request->validated();
        $data['is_primary'] = $request->boolean('is_primary');

        if ($data['is_primary']) {
            Contact::where('customer_id', $contact->customer_id)
                   ->where('id', '!=', $contact->id)
                   ->update(['is_primary' => false]);
        }

        $contact->update($data);

        return redirect()->route('admin.contacts.index')
            ->with('success', 'Contact berhasil diperbarui.');
    }

    public function destroy(Contact $contact)
    {
        $this->authorize('delete', $contact);
        
        $contact->delete();

        return redirect()->route('admin.contacts.index')
            ->with('success', 'Contact berhasil dihapus.');
    }
}