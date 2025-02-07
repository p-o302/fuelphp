<?php

use Fuel\Core\Controller_Template;
use Fuel\Core\View;
use Fuel\Core\Input;
use Fuel\Core\Validation;
use Fuel\Core\Session;
use Fuel\Core\Response;

class Controller_Admin_User extends Controller_Template
{
    public $template;

    public function before()
    {
        parent::before();
        $this->template = View::forge('user/template');
    }

    public function action_index()
    {
        // print_r(Auth::hash_password('password')); die;

        $data = [];
        $data['roles'] = Model_Role::find('all');
        $data['users'] = Model_User::query()
            ->where('status', 1)
            ->related('role')
            ->order_by('id', 'desc')
            ->limit(19)
            ->get();

        $this->template->content = View::forge('admin/user/index', $data);
    }

    public function action_create()
    {
        $data = [];
        $data['roles'] = Model_Role::query()
            ->where('status', 1)->get();
        $this->template->content = View::forge('admin/user/create', $data);
    }
    public function action_edit($role_id)
    {
        if (empty($role_id)) {
            return Response::redirect('/admin/role');
        }
        $data = [];
        $data['role'] = Model_Role::find($role_id);
        $this->template->content = View::forge('admin/role/edit', $data);
    }

    public function action_store()
    {
        // validate
        $val = Validation::forge();
        $val->add('name', 'Role Name')
            ->add_rule('required')
            ->add_rule('max_length', 255);

        if ($val->run()) {
            $role = Model_Role::forge();
            $role->name = Input::post('name');
            $role->status = Input::post('status');
            $role->created_at = \Fuel\Core\Date::forge()->format('%Y-%m-%d %H:%M:%S');
            $role->updated_at = \Fuel\Core\Date::forge()->format('%Y-%m-%d %H:%M:%S');

            $role->save();
        }
    }

}