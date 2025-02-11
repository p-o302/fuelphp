<?php

use Fuel\Core\Controller_Template;
use Fuel\Core\View;
use Fuel\Core\Input;
use Fuel\Core\Validation;
use Fuel\Core\Session;
use Fuel\Core\Response;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Controller_Admin_User extends Controller_Template
{
    public $template;

    public function before()
    {
        parent::before();
        Controller_Auth::checkAdmin();
        $this->template = View::forge('user/template');
    }

    public function action_index()
    {
        $data = [];
        $order = Input::get('order', 'desc');
        $users = Model_User::query()->related('role');
        $users->order_by('id', $order);

        $data['users'] = $users->limit(20)->get();

        $this->template->content = View::forge('admin/user/index', [
            'users' => $data['users'],
            'searchQuery' => [
                'order' => $order
            ]
        ]);
    }

    public function action_create()
    {
        $data = [];
        $data['roles'] = Model_Role::query()
            ->where('status', 1)->get();
        $this->template->content = View::forge('admin/user/create', $data);
    }

    public function action_edit($user_id)
    {
        if (empty($user_id)) {
            return Response::redirect('/admin/user');
        }
        $data = [];
        $data['user'] = Model_User::find($user_id);
        $data['roles'] = Model_Role::query()
            ->where('status', 1)->get();
        $this->template->content = View::forge('admin/user/edit', $data);
    }

    public function action_store()
    {
        $email = Input::post('email');
        $password = Input::post('password');

        // validate
        $val = Validation::forge();
        $val->add_field('username', 'Username', 'required|min_length[2]|max_length[255]');
        $val->add_field('email', 'Email', 'required|valid_email');
        $val->add_field('password', 'Password', 'required|min_length[6]');

        $errors = [];
        if ($val->run()) {
            $existing_user = Model_User::query()->where('email', $email)->get_one();
            if ($existing_user) {
                $errors['email'] = 'Email is already in use.';
                $val_errors = $val->error();
            } else {
                $hashed_password = Auth::hash_password($password);
                $user = Model_User::forge();
                $user->username = Input::post('username');
                $user->email = $email;
                $user->password = $hashed_password;
                $user->group_id = 1;
                $user->role_id = Input::post('role_id');
                $user->status = Input::post('status');
                $user->created_at = \Fuel\Core\Date::forge()->get_timestamp();
                $user->updated_at = \Fuel\Core\Date::forge()->get_timestamp();

                $user->save();

                Session::set_flash('success', '新規ホテルが追加されました！');
                return Response::redirect('/admin/user');
            }
        }

        $val_errors = array_merge($val->error(), $errors);
        $this->template->set_global('errors', $val_errors);
        $data = [];
        $data['roles'] = Model_Role::query()
            ->where('status', 1)->get();

        $this->template->content = View::forge('admin/user/create', $data);
        Session::set_flash('error', 'Form validation error!');
    }

    public function action_update($user_id)
    {
        if (empty($user_id)) {
            return Response::redirect('/admin/user');
        }

        $user = Model_User::find($user_id);
        if (!$user) {
            Session::set_flash('error', 'User not found.');
            return Response::redirect('/admin/user');
        }

        $email = Input::post('email');

        // validate
        $val = Validation::forge();
        $val->add_field('username', 'Username', 'required|min_length[2]|max_length[255]');
        $val->add_field('email', 'Email', 'required|valid_email');

        $errors = [];
        $existing_user = Model_User::query()
            ->where('email', $email)
            ->where('id', '!=', $user_id)
            ->get_one();

        if ($val->run()) {
            if ($existing_user) {
                $errors['email'] = 'Email is already in use.';
            } else {
                $user->username = Input::post('username');
                $user->email = $email;
                $user->role_id = Input::post('role_id');
                $user->status = Input::post('status');
                $user->updated_at = \Fuel\Core\Date::forge()->get_timestamp();

                $user->save();

                Session::set_flash('success', 'User updated successfully!');
                return Response::redirect('/admin/user');
            }
        }

        $val_errors = array_merge($val->error(), $errors);
        $this->template->set_global('errors', $val_errors);

        $data = [];
        $data['user'] = $user;
        $data['roles'] = Model_Role::query()->where('status', 1)->get();

        $this->template->content = View::forge('admin/user/edit', $data);
        Session::set_flash('error', 'Form validation error!');
    }

    public function action_changepassword()
    {
        if (Input::method() == 'POST') {
            $val = Validation::forge();
            $val->add_field('password', 'Password', 'required|min_length[6]');

            $user_id = Input::post('user_id');
            $password = Input::post('password');

            $user = Model_User::find($user_id);
            if (!$user) {
                Session::set_flash('error', 'User not found.');
                return Response::redirect('/admin/user');
            }
            if ($val->run()) {
                $hashed_password = Auth::hash_password($password);
                $user->username = $user->username;
                $user->email = $user->email;
                $user->group_id = $user->group_id;
                $user->status = $user->status;
                $user->created_at = $user->created_at;
                $user->role_id = $user->role_id;
                $user->password = $hashed_password;
                $user->updated_at =
                    \Fuel\Core\Date::forge()->get_timestamp();

                $user->save();

                Session::set_flash('success', 'Password changed successfully.');
                return Response::redirect('/admin/user');
            }
        }
    }

    public function action_search()
    {
        $user_name = Input::get('user_name', '');
        $users = Model_User::query();
        if (!empty($user_name)) {
            $users->where_open()
                ->where('username', 'like', "%$user_name%")
                ->or_where('email', 'like', "%$user_name%")
                ->where_close();
        }
        $users = $users->get();

        $this->template->content = View::forge('admin/user/index', [
            'users' => $users,
            'searchQuery' => [
                'user_name' => $user_name
            ]
        ]);
    }

    public function action_export_excel()
    {
        $users = Model_User::find('all');

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'Username');
        $sheet->setCellValue('C1', 'Email');
        $sheet->setCellValue('D1', 'Role');
        $sheet->setCellValue('E1', 'Status');

        $row = 2;
        foreach ($users as $user) {
            $sheet->setCellValue('A' . $row, $user->id);
            $sheet->setCellValue('B' . $row, $user->username);
            $sheet->setCellValue('C' . $row, $user->email);
            $sheet->setCellValue('D' . $row, isset($user->role->name) ? $user->role->name : 'N/A');
            $sheet->setCellValue('E' . $row, ($user->status == 1) ? 'Active' : 'Inactive');
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'users_export.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }
}
