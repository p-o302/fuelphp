<?php

use Fuel\Core\Controller_Template;
use Fuel\Core\View;
use Fuel\Core\Input;
use Fuel\Core\Validation;
use Fuel\Core\Session;
use Fuel\Core\Response;

class Controller_Admin_Prefecture extends Controller_Template
{
    public $template;

    public function before()
    {
        parent::before();
        $this->template = View::forge('user/template');
    }

    public function action_index()
    {
        $data = [];
        $data['prefectures'] = Model_Prefecture::query()
            ->where('status', 1)
            ->order_by('id', 'desc')
            ->limit(19)
            ->get();

        $this->template->content = View::forge('admin/prefecture/index', $data);
    }

    public function action_create()
    {
        $this->template->content = View::forge('admin/prefecture/create');
    }
    public function action_edit($prefecture_id)
    {
        if (empty($prefecture_id)) {
            return Response::redirect('/admin/prefecture');
        }
        $data = [];
        $data['prefecture'] = Model_Prefecture::find($prefecture_id);
        $this->template->content = View::forge('admin/prefecture/edit', $data);
    }

    public function action_store()
    {
        // validate
        $val = Validation::forge();
        $val->add('name_en', 'Prefecture Name EN')
            ->add_rule('required')
            ->add_rule('max_length', 255);

        $val->add('name_jp', 'Prefecture Name JP')
            ->add_rule('required')
            ->add_rule('max_length', 255);

        if ($val->run()) {
            $file_path = '';
            if (Input::file('file_path')) {
                $uploaded_file = Input::file('file_path');
                $upload_dir = DOCROOT . 'assets/img/hotel/';
                $filename = uniqid() . '_' . $uploaded_file['name'];
                if (move_uploaded_file($uploaded_file['tmp_name'], $upload_dir . $filename)) {
                    $file_path = 'hotel/' . $filename;
                }
            }

            $prefecture = Model_Prefecture::forge();
            $prefecture->name_en = Input::post('name_en');
            $prefecture->name_jp = Input::post('name_jp');
            $prefecture->file_path = $file_path;
            $prefecture->status = Input::post('status');
            $prefecture->created_at = \Fuel\Core\Date::forge()->format('%Y-%m-%d %H:%M:%S');
            $prefecture->updated_at = \Fuel\Core\Date::forge()->format('%Y-%m-%d %H:%M:%S');

            $prefecture->save();

            Session::set_flash('success', '新規ホテルが追加されました！');
            return Response::redirect('/admin/prefecture');
        } else {
            $val_errors = $val->error();
            $this->template->set_global('errors', $val_errors);

            $data = [];
            $data['prefectures'] = Model_Prefecture::find('all');
            $this->template->content = View::forge('admin/prefecture/create', $data);
            Session::set_flash('error', 'Form validation error!');
        }
    }

    public function action_update($prefecture_id)
    {
        if (empty($prefecture_id)) {
            return Response::redirect('/admin/prefecture');
        }
        // validate
        $val = Validation::forge();
        $val->add('name_en', 'Prefecture Name EN')
            ->add_rule('required')
            ->add_rule('max_length', 255);

        $val->add('name_jp', 'Prefecture Name JP')
            ->add_rule('required')
            ->add_rule('max_length', 255);

        if ($val->run()) {
            $file_path = '';
            if (Input::file('file_path')) {
                $uploaded_file = Input::file('file_path');
                $upload_dir = DOCROOT . 'assets/img/hotel/';
                $filename = uniqid() . '_' . $uploaded_file['name'];
                if (move_uploaded_file($uploaded_file['tmp_name'], $upload_dir . $filename)) {
                    $file_path = 'hotel/' . $filename;
                }
            }

            $prefecture = Model_Prefecture::find($prefecture_id);
            $prefecture->name_en = Input::post('name_en');
            $prefecture->name_jp = Input::post('name_jp');
            $prefecture->file_path = $file_path;
            $prefecture->status = Input::post('status');
            $prefecture->updated_at = \Fuel\Core\Date::forge()->format('%Y-%m-%d %H:%M:%S');

            $prefecture->save();

            Session::set_flash('success', 'ホテルが更新されました！');
            return Response::redirect('/admin/prefecture');
        } else {
            $val_errors = $val->error();
            $this->template->set_global('errors', $val_errors);

            $data = [];
            $data['prefecture'] = Model_Prefecture::find($prefecture_id);

            $this->template->content = View::forge('admin/prefecture/edit', $data);
            Session::set_flash('error', 'Form validation error!');
        }
    }
    public function action_status($prefecture_id)
    {
        if (empty($prefecture_id)) {
            return Response::redirect('/admin/prefecture');
        }
        $prefecture = Model_Prefecture::find($prefecture_id);
        $prefecture->status = 0;
        $prefecture->save();
        Session::set_flash('success', 'Deleted successfully!');

        return Response::redirect('/admin/prefecture');
    }
    public function action_search()
    {
        $prefecture_name = Input::get('prefecture_name', '');
        $prefectures = Model_Prefecture::query()
            ->where('status', 1);

        if (!empty($prefecture_name)) {
            $prefectures->where_open()
                ->where('name_jp', 'like', "%$prefecture_name%")
                ->or_where('name_en', 'like', "%$prefecture_name%")
                ->where_close();
        }
        $prefectures = $prefectures->get();

        $this->template->content = View::forge('admin/prefecture/index', [
            'prefectures' => $prefectures,
            'searchQuery' => [
                'prefecture_name' => $prefecture_name
            ]
        ]);
    }
}
