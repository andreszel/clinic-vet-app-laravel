<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddCustomer;
use App\Interfaces\CustomerRepositoryInterface;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CustomerController extends Controller
{
    private CustomerRepositoryInterface $customerRepository;

    public function __construct(CustomerRepositoryInterface $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request): View
    {
        $name = $request->get('name');
        $surname = $request->get('surname');
        $page = $request->get('page');
        $limit = $request->get('limit', CustomerRepositoryInterface::LIMIT_DEFAULT);

        $resultPaginator = $this->customerRepository->filterBy($name, $surname, $limit);
        $resultPaginator->appends([
            'name' => $name,
            'surname' => $surname
        ]);
        //dd($resultPaginator);

        $url = $request->url();
        $uri = $request->getRequestUri();

        $counter = 1;
        if ($page >= 1) {
            $counter = (($page - 1) * $limit) + 1;
        }

        return view('admin.customers.list', [
            'customers' => $resultPaginator,
            'name' => $name,
            'surname' => $surname,
            'counter' => $counter
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.customers.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddCustomer $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();

        $customer = $this->customerRepository->create($data);

        return redirect()->route('customers.list')->with('success', 'Klient został dodany!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $customer = Customer::findOrFail($id);

        return view('admin.customers.edit', [
            'customer' => $customer
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AddCustomer $request, $id)
    {
        $data = $request->validated();
        $data['address'] = $request->address ?? '';
        $data['phone'] = $request->phone ?? '';
        $data['email'] = $request->email ?? '';

        $this->customerRepository->update($data,  $id);

        return redirect()->route('customers.list')->with('success', 'Klient został zaktualizowany!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->customerRepository->delete($id);
        return redirect()->back()->with('success', 'Klient został usunięty!');
    }
}
