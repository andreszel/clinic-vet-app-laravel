<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddUser;
use App\Interfaces\UserRepositoryInterface;
use App\Mail\TempPassChange;
use App\Models\UserTypes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\View\View;

class UserController extends Controller
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function index(Request $request): View
    {
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

        return view('admin.doctors.list', [
            'users' => $resultPaginator,
            'phrase' => $phrase,
            'email' => $email,
            'phone' => $phone
        ]);
    }

    public function create()
    {
        $types = UserTypes::get();
        $commission_servies = $this->userRepository::COMMISSION_SERVIES;
        $commission_medicals = $this->userRepository::COMMISSION_MEDICALS;

        return view('admin.doctors.create', [
            'types' => $types,
            'commission_servies' => $commission_servies,
            'commission_medicals' => $commission_medicals
        ]);
    }

    public function store(AddUser $request)
    {
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
        $title = 'Prośba o zmianę tymczasowego hasła do Twojego konta';
        $url = 'https://wp.pl';

        dd($data);

        //return redirect(url('send-mail/' . $title . '/' . $random_password . '/' . $email));

        $details = ['random_password' => $random_password, 'smtp_username' => $smtp_username, 'email' => $email, 'title' => $title, 'url' => $url];

        $sendmail = Mail::to($email)->send(new TempPassChange($details));

        dump($sendmail);
        dd($details);

        /* Mail::send('emails.User.TempPassChange', $details, function ($m) use ($smtp_username, $email) {
            $m->from($smtp_username, 'Clinic Vet App');
            $m->to($email, 'Andrzej Szelka')->subject('Tymczasowe hasło!');
            //$m->subject('Tymczasowe hasło!');
            // Attach a file from a raw $data string...
            //$m->attachData($data, $name, array $options = []);
        }); */

        dd($data);

        $user = $this->userRepository->create($data);

        return redirect()->route('admin.doctors.edit', ['id' => $user->id])->with('success', 'Lekarz został dodany!');
    }

    public function show(int $userId, Request $request): View
    {
        $loggedUser = Auth::user();
        $user = $this->userRepository->get($userId);

        // domyślnie, że user, którego oglądamy to inny niż ten zalogowany
        $profile_logged = false;

        // sprawdzenie, czy zalogowany użytkownik to jest ten sam profil
        if ($loggedUser->id == $user->id) $profile_logged = true;

        return view('admin.user.show', [
            'user' => $user,
            'profile_logged' => $profile_logged
        ]);
    }
}
