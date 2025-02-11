<?php

use Fuel\Core\Debug;
use Service\Email;
use Fuel\Core\Controller_Template;
use Fuel\Core\View;
use Fuel\Core\Input;
use Fuel\Core\Validation;
use Fuel\Core\Response;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// require_once APPPATH . 'vendor/autoload.php';
// require_once '/var/www/html/fuel/app/classes/service/email.php';

class Controller_Auth extends Controller_Template
{
    public $template = 'user/template';

    static public function checkAdmin()
    {
        if (!Auth::check()) {
            Response::redirect('/auth/login');
        }

        $user_id = Auth::instance()->get_user_id()[1];
        $user = Model_User::find($user_id);

        if (!$user || $user->role_id != 1) {
            Response::redirect('/');
        }
    }

    static public function checkClient()
    {
        if (!Auth::check()) {
            Response::redirect('/auth/login');
        }
    }

    public function action_login()
    {
        if (Auth::check()) {
            Response::redirect('/');
        }

        $params = ['errors' => [], 'email' => ''];

        if (Input::method() == 'POST') {
            $val = Validation::forge();
            $val->add('login_email', 'Email')->add_rule('required')->add_rule('valid_email');
            $val->add('login_password', 'Password')->add_rule('required');

            if ($val->run()) {
                $email = Input::post('login_email');
                $password = Input::post('login_password');

                $user = Model_User::query()->where('email', $email)->get_one();
                if (!$user) {
                    $params['errors']['login_email'] = 'Email does not exist.';
                } else {
                    $auth = Auth::instance();
                    if ($auth->login($email, $password)) {
                        $user_id = $auth->get_user_id()[1];
                        $user = Model_User::find($user_id);

                        if ($user && $user->role_id == 1) {
                            Response::redirect('/admin');
                        }
                        Response::redirect('/');
                    } else {
                        $params['errors']['login_password'] = 'Invalid Password.';
                    }
                }
            } else {
                $params['errors'] = $val->error();
            }
            $params['email'] = Input::post('login_email');
        }

        $this->template->content = View::forge('auth/login', $params);
    }


    public function action_register()
    {
        $params = ['errors' => [], 'email' => ''];

        if (Input::method() == 'POST') {
            $val = Validation::forge();
            $val->add('register_username', 'Username')->add_rule('required')->add_rule('min_length', 3);
            $val->add('register_email', 'Email')->add_rule('required')->add_rule('valid_email')->add_rule('unique', 'users.email');
            $val->add('register_password', 'Password')->add_rule('required')->add_rule('min_length', 6);

            $email = Input::post('register_email');
            $existing_user = Model_User::query()->where('email', $email)->get_one();
            if ($existing_user) {
                $params['errors']['register_email'] = 'Email is already in use.';
            }

            if ($val->run() && empty($params['errors'])) {
                $user = Model_User::forge();
                $hashed_password = Auth::hash_password(Input::post('register_password'));
                $user->username = Input::post('register_username');
                $user->email = $email;
                $user->password = $hashed_password;
                $user->group_id = 1;
                $user->role_id = 2;
                $user->status = 1;
                $user->created_at = \Fuel\Core\Date::forge()->get_timestamp();
                $user->updated_at = \Fuel\Core\Date::forge()->get_timestamp();

                if ($user->save()) {
                    Session::set_flash('success', 'Registration successful! Please log in.');
                    Response::redirect('/auth/login');
                } else {
                    $params['errors']['register'] = 'Registration failed. Try again.';
                }
            } else {
                $params['errors'] = array_merge($params['errors'], $val->error());
            }
        }

        $this->template->content = View::forge('auth/login', $params);
    }


    public function action_forgot_password()
    {
        $params = ['errors' => []];

        if (Input::method() == 'POST') {
            $val = Validation::forge();
            $val->add('forgot_email', 'Email')->add_rule('required')->add_rule('valid_email');

            if ($val->run()) {
                $email = Input::post('forgot_email');
                $user = Model_User::query()->where('email', $email)->get_one();

                if ($user) {
                    $reset_token = bin2hex(random_bytes(32));
                    $expire_time = date("Y-m-d H:i:s", time() + 3600); //1h
                    $user->reset_token = $reset_token;
                    $user->reset_token_expires_at = $expire_time;

                    $user->save();

                    $reset_link = Uri::create("auth/reset_password?token=$reset_token");

                    $mail = new PHPMailer(true);

                    try {
                        $mail->isSMTP();
                        $mail->Host = 'smtp.gmail.com';
                        $mail->SMTPAuth = true;
                        $mail->Username = 'n_phuongthao@thk-hd.vn';
                        $mail->Password = 'qhhrtgzmfsfrsziu';
                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                        $mail->Port = 587;

                        $mail->setFrom('your-email@gmail.com', 'Your App Name');
                        $mail->addAddress($email);
                        $mail->Subject = 'Password Reset';
                        $mail->Body = "Click here to reset your password: $reset_link";

                        $mail->send();

                        Session::set_flash('success', 'Password reset link has been sent to your email.');
                        $this->template->content = View::forge('auth/sent_link', $params);
                    } catch (Exception $e) {
                        $params['errors']['mail'] = 'Mail error: ' . $mail->ErrorInfo;
                    }
                } else {
                    $params['errors']['forgot_email'] = 'Email not found.';
                }
            } else {
                $params['errors'] = $val->error();
            }
        }

        $this->template->content = View::forge('auth/sent_link', $params);
    }

    public function action_reset_password()
    {
        $params = ['errors' => []];

        $token = Input::method() == 'POST' ? Input::post('token') : Input::get('token');
        $params['token'] = $token;
        $user = Model_User::query()->where('reset_token', $token)->get_one();

        if (!$user || (time() - strtotime($user->reset_token_expires_at) > 3600)) {
            Session::set_flash('error', 'Invalid token or reset link has expired. Please request a new one.');
            Response::redirect('/auth/login');
        }

        if (Input::method() == 'POST') {
            $val = Validation::forge();
            $val->add('new_password', 'New Password')->add_rule('required')->add_rule('min_length', 6);
            $val->add('confirm_password', 'Confirm Password')->add_rule('required')->add_rule('match_field', 'new_password');

            if ($val->run()) {
                $user->password = Auth::hash_password(Input::post('new_password'));
                $user->reset_token = null;
                $user->save();

                Session::set_flash('success', 'Password reset successful. Please log in.');
                Response::redirect('/auth/login');
            } else {
                $params['errors'] = $val->error();
                $params['old_data'] = Input::post();
            }
        }

        $this->template->content = View::forge('auth/reset_password', $params);
    }

    public function action_logout()
    {
        Auth::dont_remember_me();
        Auth::logout();

        $referrer = Input::server('HTTP_REFERER') ? Input::server('HTTP_REFERER') : '/';

        Response::redirect($referrer);
    }
}
