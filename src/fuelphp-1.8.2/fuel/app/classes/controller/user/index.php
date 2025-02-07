<?php

use Auth\Auth;
use Fuel\Core\Controller_Template;
use Fuel\Core\Debug;
use Fuel\Core\Response;
use Fuel\Core\View;
use Fuel\Core\DB;

class Controller_User_Index extends Controller_Template
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
        $data['prefectures'] = Model_Prefecture::find('all');
        $data['hotels'] = Model_Hotel::query()
            ->related('prefecture')
            ->where('status', 1)
            ->order_by(DB::expr('RAND()'))
            ->limit(18)
            ->get();
        $this->template->set_global('content', View::forge('user/hotel/index', $data));
    }

    public function action_prefecture($prefecture_id)
    {
        $data = [];
        $data['prefectures'] = Model_Prefecture::find('all');
        $data['prefecture'] = Model_Prefecture::find($prefecture_id);
        $data['hotels'] = Model_Hotel::query()
            ->related('prefecture')
            ->where('status', 1)
            ->where('prefecture_id', $prefecture_id)
            ->get();

        $this->template->set_global('content', View::forge('user/hotel/list', $data));
    }
    public function action_hotel($hotel_id)
    {
        $data = [];
        $data['prefectures'] = Model_Prefecture::find('all');
        $data['hotel'] = Model_Hotel::find($hotel_id);
        $data['hotels'] = Model_Hotel::query()
            ->order_by(DB::expr('RAND()'))
            ->limit(4)
            ->get();
        $this->template->set_global('content', View::forge('user/hotel/detail', $data));
    }
    public function action_login()
    {
        $this->template->set_global('content', View::forge('user/login'));
    }
}
