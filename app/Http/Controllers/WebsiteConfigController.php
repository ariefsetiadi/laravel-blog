<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\WebsiteConfig;

use App\Services\WebsiteConfigService;

use App\Http\Requests\WebsiteConfigRequest;

class WebsiteConfigController extends Controller
{
    public function __construct(
        protected WebsiteConfigService $websiteConfigService,
    ) { }

    public function index()
    {
        $data['title']  =   'Pengaturan Website';
        $data['config'] =   WebsiteConfig::where('id', 1)->first();

        return view('WebsiteConfig.form', $data);
    }

    public function save(WebsiteConfigRequest $request)
    {
        $result =   $this->websiteConfigService->saveConfig($request);

        return response()->json([
            'success'   =>  $result['success'],
            'messages'  =>  $result['message'],
        ]);
    }
}
