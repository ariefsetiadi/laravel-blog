<?php

namespace App\Services;

use Auth;
use File;
use Str;

use App\Models\User;
use App\Models\WebsiteConfig;

class WebsiteConfigService {
  public function saveConfig($data) {
    try {
      $sosmed = $data->only(['facebook', 'instagram', 'linkedin', 'twitter']);

      $config = WebsiteConfig::where('id', 1)->first();
      if ($config) {
        $icon_name  = $config->icon;

        if (isset($data['icon']) && is_file($data['icon'])) {
          $path = '/uploads/website';
          $icon = $data->icon;

          if (!File::exists($path)) {
            File::makeDirectory($path, $mode = 0775, true, true);
          }

          if ($config->icon != NULL) {
            File::delete('uploads/website/' . $config->icon);
          }

          $icon_name  = Str::slug($data->name) . '.' . $icon->getClientOriginalExtension();
          $icon->move(public_path($path), $icon_name);
        }

          $config->update([
            'name'          =>  $data->name,
            'icon'          =>  $icon_name,
            'description'   =>  $data->description,
            'phone'         =>  json_encode($data->phone),
            'email'         =>  strtolower($data->email),
            'social_media'  =>  json_encode($sosmed),
            'address'       =>  $data->address,
            'updated_by'    =>  Auth::user()->id,
          ]);
      } else {
        if (isset($data['icon']) && is_file($data['icon'])) {
          $path = '/uploads/website';
          $icon = $data->icon;

          if (!File::exists($path)) {
            File::makeDirectory($path, $mode = 0775, true, true);
          }

          $icon_name  = Str::slug($data->name) . '.' . $icon->getClientOriginalExtension();
          $icon->move(public_path($path), $icon_name);
        }

        WebsiteConfig::create([
          'name'          =>  $data->name,
          'icon'          =>  $file['data'],
          'description'   =>  $icon_name,
          'phone'         =>  json_encode($data->phone),
          'email'         =>  strtolower($data->email),
          'social_media'  =>  json_encode($sosmed),
          'address'       =>  $data->address,
          'created_by'    =>  Auth::user()->id,
          'updated_by'    =>  Auth::user()->id,
        ]);
      }

      return responseSuccess(null, 200, 'Pengaturan Website berhasil disimpan');
    } catch (\Throwable $th) {
      return responseFailed(null, 500, 'Pengaturan Website gagal disimpan');
    }
  }
}