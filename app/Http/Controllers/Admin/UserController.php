<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddUser;
use App\Interfaces\UserRepositoryInterface;
use App\Mail\TempPassChange;
use App\Models\User;
use App\Models\UserTypes;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Throwable;

class UserController extends Controller
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function index(Request $request): View
    {
        //Gate::authorize('admin-level');

        $phrase = $request->get('phrase');
        $email = $request->get('email');
        $phone = $request->get('phone');
        $page = $request->get('page');
        $limit = $request->get('limit', UserRepositoryInterface::LIMIT_DEFAULT);

        $resultPaginator = $this->userRepository->filterBy($phrase, $email, $phone, $limit);
        $resultPaginator->appends([
            'phrase' => $phrase,
            'email' => $email,
            'phone' => $phone,
        ]);

        $counter = 1;
        if ($page >= 1) {
            $counter = (($page - 1) * $limit) + 1;
        }

        return view('admin.users.list', [
            'users' => $resultPaginator,
            'phrase' => $phrase,
            'email' => $email,
            'phone' => $phone,
            'counter' => $counter
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //Gate::authorize('admin-level');

        $user = User::with('type')->findOrFail($id);
        $types = UserTypes::get();
        $commission_servies = $this->userRepository::COMMISSION_SERVIES;
        $commission_medicals = $this->userRepository::COMMISSION_MEDICALS;

        return view('admin.users.edit', [
            'user' => $user,
            'types' => $types,
            'commission_servies' => $commission_servies,
            'commission_medicals' => $commission_medicals
        ]);
    }

    public function users_list(Request $request): View
    {
        //Gate::authorize('admin-level');

        $phrase = $request->get('phrase');
        $email = $request->get('email');
        $phone = $request->get('phone');
        $limit = $request->get('limit', UserRepositoryInterface::LIMIT_DEFAULT);

        $resultPaginator = $this->userRepository->filterBy($phrase, $email, $phone, $limit);
        $resultPaginator->appends([
            'phrase' => $phrase,
            'email' => $email,
            'phone' => $phone,
        ]);

        return view('admin.users.list', [
            'users' => $resultPaginator,
            'phrase' => $phrase,
            'email' => $email,
            'phone' => $phone
        ]);
    }

    public function create()
    {
        //Gate::authorize('admin-level');

        $types = UserTypes::get();
        $commission_servies = $this->userRepository::COMMISSION_SERVIES;
        $commission_medicals = $this->userRepository::COMMISSION_MEDICALS;

        return view('admin.users.create', [
            'types' => $types,
            'commission_servies' => $commission_servies,
            'commission_medicals' => $commission_medicals
        ]);
    }

    public function store(AddUser $request)
    {
        //Gate::authorize('admin-level');

        $user = Auth::user();

        $data = $request->validated();

        // Add random password
        $random_password = Str::random(8);
        $hashed_random_password = Hash::make($random_password);
        $data['password'] = $hashed_random_password;
        $data['parent_id'] = $user->id;
        //$email = $data['email'];
        $email = 'szelkaandrzej@gmail.com';
        $smtp_username = config('mail.mailers.smtp.username');
        $title = 'Pro??ba o zmian?? tymczasowego has??a do Twojego konta';
        $url = route('login');

        $details = ['random_password' => $random_password, 'smtp_username' => $smtp_username, 'email' => $email, 'title' => $title, 'url' => $url];

        $sendmail = Mail::to($email)->send(new TempPassChange($details));

        /* Mail::send('emails.User.TempPassChange', $details, function ($m) use ($smtp_username, $email) {
            $m->from($smtp_username, 'Clinic Vet App');
            $m->to($email, 'Andrzej Szelka')->subject('Tymczasowe has??o!');
            //$m->subject('Tymczasowe has??o!');
            // Attach a file from a raw $data string...
            //$m->attachData($data, $name, array $options = []);
        }); */

        $user = $this->userRepository->create($data);

        //return redirect()->route('users.edit', ['id' => $user->id])->with('success', 'U??ytkownik zosta?? dodany!');
        return redirect()->route('users.list')->with('success', 'U??ytkownik zosta?? dodany! Wys??ali??my do niego email z has??em tymczasowym i linkiem do logowania.');
    }

    public function update(AddUser $request, int $userId)
    {
        //Gate::authorize('admin-level');

        $data = $request->validated();

        $this->userRepository->update($data, $userId);

        return redirect()->route('users.edit', ['id' => $userId])->with('success', 'U??ytkownik zosta?? zaktualizowany!');
    }

    public function changeStatus(int $id)
    {
        //Gate::authorize('admin-level');

        if ($id == Auth::id()) {
            return redirect()->back()->with('warning', 'Nie uda??o si??, poniewa?? swojego konta nie mo??esz wy????czy??!');
        }

        $this->userRepository->change_status($id);
        return redirect()->back()->with('success', 'Status zosta?? zmieniony!');
    }

    public function show(Request $request, int $userId): View
    {
        //Gate::authorize('admin-level');

        $loggedUser = Auth::user();
        $user = $this->userRepository->get($userId);

        // domy??lnie, ??e user, kt??rego ogl??damy to inny ni?? ten zalogowany
        $profile_logged = false;

        // sprawdzenie, czy zalogowany u??ytkownik to jest ten sam profil
        if ($loggedUser->id == $user->id) $profile_logged = true;

        return view('admin.users.show', [
            'user' => $user,
            'profile_logged' => $profile_logged
        ]);
    }

    public function delete($userId)
    {
        //Gate::authorize('admin-level');

        $this->userRepository->delete($userId);

        return redirect()->route('users.list')->with('success', 'U??ytkownik zosta?? usuni??ty!');
    }
}
