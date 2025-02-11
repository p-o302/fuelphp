<?php

use Fuel\Core\Controller_Template;
use Fuel\Core\View;
use Fuel\Core\Input;
use Fuel\Core\Validation;
use Fuel\Core\Session;
use Fuel\Core\Response;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Controller_Admin_Role extends Controller_Template
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
        $data['roles'] = Model_Role::query()
            ->order_by('id', $order)
            ->get();

        $this->template->content = View::forge('admin/role/index', 
            [
                'roles' => $data['roles'],
                'searchQuery' => [
                    'order' => $order
                ]
            ]
        );
    }

    public function action_create()
    {
        $this->template->content = View::forge('admin/role/create');
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

            Session::set_flash('success', '新規ホテルが追加されました！');
            return Response::redirect('/admin/role');
        } else {
            $val_errors = $val->error();
            $this->template->set_global('errors', $val_errors);
            $this->template->content = View::forge('admin/role/create');
            Session::set_flash('error', 'Form validation error!');
        }
    }

    public function action_update($role_id)
    {
        if (empty($role_id)) {
            return Response::redirect('/admin/role');
        }
        // validate
        $val = Validation::forge();
        $val->add('name', 'Role Name')
            ->add_rule('required')
            ->add_rule('max_length', 255);

        if ($val->run()) {
            $role = Model_Role::find($role_id);
            $role->name = Input::post('name');
            $role->status = Input::post('status');
            $role->save();

            return Response::redirect('/admin/role');
        } else {
            $val_errors = $val->error();
            $this->template->set_global('errors', $val_errors);

            $data = [];
            $data['role'] = Model_Role::find($role_id);

            $this->template->content = View::forge('admin/role/edit', $data);
            Session::set_flash('error', 'Form validation error!');
        }
    }


    public function action_status($role_id)
    {
        if (empty($role_id)) {
            return Response::redirect('/admin/role');
        }
        $role = Model_Role::find($role_id);
        $role->status = 0;
        $role->save();
        Session::set_flash('success', 'Deleted successfully!');

        return Response::redirect('/admin/role');
    }

    public function action_search()
    {
        $role_name = Input::get('role_name', '');
        $roles = Model_Role::query();
        if (!empty($role_name)) {
            $roles->where('name', 'like', "%$role_name%");
        }
        $roles = $roles->get();

        $this->template->content = View::forge('admin/role/index', [
            'roles' => $roles,
            'searchQuery' => [
                'role_name' => $role_name
            ]
        ]);
    }

    public function action_export_excel()
    {
        $roles = Model_Role::find('all');

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'Username');
        $sheet->setCellValue('C1', 'Status');

        $row = 2;
        foreach ($roles as $role) {
            $sheet->setCellValue('A' . $row, $role->id);
            $sheet->setCellValue('B' . $row, $role->name);
            $sheet->setCellValue('C' . $row, ($role->status == 1) ? 'Active' : 'Inactive');
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'roles_export.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }
}
