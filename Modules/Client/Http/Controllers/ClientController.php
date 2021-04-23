<?php

namespace Modules\Client\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use GroceryCrud\Core\GroceryCrud;
use Modules\Client\Entities\Client;
use Modules\Client\Entities\ClientImage;

class ClientController extends Controller
{
    private function _getDatabaseConnection() {
        $databaseConnection = config('database.default');
        $databaseConfig = config('database.connections.' . $databaseConnection);


        return [
            'adapter' => [
                'driver' => 'Pdo_Mysql',
                'database' => $databaseConfig['database'],
                'username' => $databaseConfig['username'],
                'password' => $databaseConfig['password'],
                'charset' => 'utf8'
            ]
        ];
    }

    private function _getGroceryCrudEnterprise() {
        $database = $this->_getDatabaseConnection();
        $config = config('grocerycrud');

        $crud = new GroceryCrud($config, $database);

        return $crud;
    }

    private function _show_output($output) {
        if ($output->isJSONResponse) {
            return response($output->output, 200)
                  ->header('Content-Type', 'application/json')
                  ->header('charset', 'utf-8');
        }

        $css_files = $output->css_files;
        $js_files = $output->js_files;
        $output = $output->output;

        return view('grocery', [
            'output' => $output,
            'css_files' => $css_files,
            'js_files' => $js_files
        ]);
    }

    /**
     * Show the datagrid for customers
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $crud = $this->_getGroceryCrudEnterprise();

        $crud->setTable('clients');
        $crud->setSubject('Client Job', 'Client Jobs');
        $crud->columns(['client_name_id', 'job', 'deal_date', 'deadline_date', 'fee', 'dp', 'paid']);
        $crud->unsetFields(['created_at', 'updated_at']);
        $crud->setTexteditor(['note']);
        $crud->setSkin('bootstrap-v4');
        $crud->fieldType('paid', 'checkbox_boolean');
        $crud->fieldType('finished', 'checkbox_boolean');
        $crud->setRelation('client_name_id', 'client_names', 'name');
        $crud->displayAs([
            'client_name_id' => 'Client Name',
            'paid' => 'Paid',
            'dp' => 'DP'
        ]);
        $crud->callbackColumn('dp', function ($value, $row) {
            return "Rp " . number_format($value, 0, ',', '.');
        });
        $crud->callbackColumn('fee', function ($value, $row) {
            return "Rp " . number_format($value, 0, ',', '.');
        });
        $crud->setActionButton('Files', 'fa fa-file', function ($row) {
            return route('client.files', $row->id);
        }, true);
        $crud->where(['finished'=>'0']);

        $output = $crud->render();

        return $this->_show_output($output);
    }

    public function files($id)
    {
        $crud = $this->_getGroceryCrudEnterprise();

        $crud->setTable('client_images');
        $crud->setSubject('Client File', 'Client Files');
        $crud->unsetColumns(['client_id', 'created_at', 'updated_at']);
        $crud->unsetFields(['client_id', 'created_at', 'updated_at']);
        $crud->setTexteditor(['note']);
        $crud->setSkin('bootstrap-v4');
        $crud->setFieldUpload('image', 'storage', '../../storage');
        $crud->where(['client_id' => $id]);
        $crud->callbackBeforeInsert(function ($s) use($id) {
            $s->data['client_id'] = $id;

            return $s;
        });
        $crud->setActionButton('Kembali', 'fa fa-arrow-left', function ($row) {
            return route('client.index');
        });
        $output = $crud->render();

        return $this->_show_output($output);
    }

    public function name()
    {
        $crud = $this->_getGroceryCrudEnterprise();

        $crud->setTable('client_names');
        $crud->setSubject('Client Name', 'Client Names');
        $crud->unsetColumns(['created_at', 'updated_at']);
        $crud->unsetFields(['created_at', 'updated_at']);
        $crud->setTexteditor(['note']);
        $crud->setSkin('bootstrap-v4');
        $output = $crud->render();

        return $this->_show_output($output);
    }
}
