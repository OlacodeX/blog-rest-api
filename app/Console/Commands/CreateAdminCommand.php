<?php

namespace App\Console\Commands;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class CreateAdminCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will create a new admin account for you.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $user['name'] = $this->ask('Name of new user');
        $user['email'] = $this->ask('Email of new user');
        $user['password'] = $this->secret('Password of new user'); 
        
        $validator = Validator::make($user, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'max:255', 'email', 'unique:'.User::class],
            'password' => ['required', 'string', Password::min(8)->mixedCase()->numbers()->symbols()]
        ]);
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }
            return -1;
        } 
        
        DB::transaction(function () use ($user) {
            // $user['password'] = Hash::make($user['password']);

            $profile = Admin::create($user);
            $newUser = new User();
            $newUser['name'] = $user['name'];
            $newUser['email'] = $user['email'];
            $newUser['email_verified_at'] = now();
            $newUser['password'] = $user['password'];
            $newUser['profile_id'] = $profile->id;
            $newUser['profile_type'] = $profile::class;
    
            $newUser->save();
        });

        $this->info('User '.$user['email'].' created successfully');

        // return error message at index 0
        return 0;
    }
}
