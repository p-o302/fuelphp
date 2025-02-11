<?php

use Fuel\Core\Controller_Template;
use Fuel\Core\View;
use Fuel\Core\Input;
use Fuel\Core\Validation;
use Fuel\Core\Session;
use Fuel\Core\Response;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Controller_Admin_Hotel extends Controller_Template
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
        $data['prefectures'] = Model_Prefecture::find('all');
        $data['hotels'] = Model_Hotel::query()
            ->related('prefecture')
            ->where('status', 1)
            ->order_by('id', $order)
            ->limit(19)
            ->get();

        $this->template->content = View::forge('admin/hotel/index', [
            'hotels' => $data['hotels'],
            'prefectures' => $data['prefectures'],
            'searchQuery' => [
                'order' => $order
            ]
        ]);
    }

    public function action_hotel()
    {
        $data = [];
        $data['prefectures'] = Model_Prefecture::find('all');
        $data['hotels'] = Model_Hotel::query()
            ->related('prefecture')
            ->where('status', 1)
            ->order_by('id', 'desc')
            ->limit(19)
            ->get();

        $this->template->content = View::forge('admin/hotel/index', $data);
    }

    public function action_create()
    {
        $data = [];
        $data['prefectures'] = Model_Prefecture::find('all');
        $this->template->content = View::forge('admin/hotel/create', $data);
    }
    public function action_edit($hotel_id)
    {
        $data = [];
        $data['hotel'] = Model_Hotel::find($hotel_id);
        $data['prefectures'] = Model_Prefecture::find('all');
        $this->template->content = View::forge('admin/hotel/edit', $data);
    }

    public function action_store()
    {
        // validate
        $val = Validation::forge();
        $val->add('name', 'Hotel Name')
            ->add_rule('required')
            ->add_rule('max_length', 255);

        $val->add('prefecture_id', 'Prefecture')
            ->add_rule('required');

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

            $hotel = Model_Hotel::forge();
            $hotel->name = Input::post('name');
            $hotel->prefecture_id = Input::post('prefecture_id');
            $hotel->file_path = $file_path;
            $hotel->status = Input::post('status');
            $hotel->created_at = \Fuel\Core\Date::forge()->format('%Y-%m-%d %H:%M:%S');
            $hotel->updated_at = \Fuel\Core\Date::forge()->format('%Y-%m-%d %H:%M:%S');

            $hotel->save();

            Session::set_flash('success', '新規ホテルが追加されました！');
            return Response::redirect('/admin/hotel');
        } else {
            $val_errors = $val->error();
            $this->template->set_global('errors', $val_errors);

            $data = [];
            $data['prefectures'] = Model_Prefecture::find('all');
            $this->template->content = View::forge('admin/hotel/create', $data);
            Session::set_flash('error', 'Form validation error!');
        }
    }

    public function action_update($hotel_id)
    {
        if (empty($hotel_id)) {
            return Response::redirect('/admin/hotel');
        }
        // validate
        $val = Validation::forge();
        $val->add('name', 'Hotel Name')
            ->add_rule('required')
            ->add_rule('max_length', 255);

        $val->add('prefecture_id', 'Prefecture')
            ->add_rule('required');

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

            $hotel = Model_Hotel::find($hotel_id);
            $hotel->name = Input::post('name');
            $hotel->prefecture_id = Input::post('prefecture_id');
            $hotel->file_path = $file_path;
            $hotel->status = Input::post('status');
            $hotel->updated_at = \Fuel\Core\Date::forge()->format('%Y-%m-%d %H:%M:%S');

            $hotel->save();

            Session::set_flash('success', 'ホテルが更新されました！');
            return Response::redirect('/admin/hotel');
        } else {
            $val_errors = $val->error();
            $this->template->set_global('errors', $val_errors);

            $data = [];
            $data['hotel'] = Model_Hotel::find($hotel_id);
            $data['prefectures'] = Model_Prefecture::find('all');
            $this->template->content = View::forge('admin/hotel/edit', $data);
            Session::set_flash('error', 'Form validation error!');
        }
    }
    public function action_status($hotel_id)
    {
        if (empty($hotel_id)) {
            return Response::redirect('/admin/hotel');
        }
        $hotel = Model_Hotel::find($hotel_id);
        $hotel->status = 0;
        $hotel->save();
        Session::set_flash('success', 'Deleted successfully!');

        return Response::redirect('/admin/hotel');
    }
    public function action_search()
    {
        $hotel_name = Input::get('hotel_name', '');
        $prefecture_name = Input::get('prefecture_name', '');
        $hotels = Model_Hotel::query()
            ->related('prefecture')
            ->where('status', 1);

        if (!empty($hotel_name)) {
            $hotels->where('name', 'like', "%$hotel_name%");
        }

        if (!empty($prefecture_name)) {
            $hotels->where('prefecture.name_jp', 'like', "%$prefecture_name%");
        }
        $hotels = $hotels->get();

        $this->template->content = View::forge('admin/hotel/index', [
            'hotels' => $hotels,
            'searchQuery' => [
                'hotel_name' => $hotel_name,
                'prefecture_name' => $prefecture_name
            ]
        ]);
    }
    public function action_export_excel()
    {
        $hotels = Model_Hotel::find('all');

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'ホテル名');
        $sheet->setCellValue('C1', '場所');
        $sheet->setCellValue('D1', 'Status');

        $row = 2;
        foreach ($hotels as $hotel) {
            $sheet->setCellValue('A' . $row, $hotel->id);
            $sheet->setCellValue('B' . $row, $hotel->name);
            $sheet->setCellValue('C' . $row, isset($hotel->prefecture->name_jp) ? $hotel->prefecture->name_jp : 'N/A');
            $sheet->setCellValue('D' . $row, ($hotel->status == 1) ? 'Active' : 'Inactive');
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'hotels_export.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }
}
