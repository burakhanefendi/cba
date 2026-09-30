<?php

namespace App\Http\Controllers;

use App\Models\Setting;

class ContactController extends Controller
{
    public function index()
    {
        $email   = Setting::get('contact_email', 'info@cba-ist.com') ?: 'info@cba-ist.com';
        $phone   = Setting::get('contact_phone', '02122657591') ?: '02122657591';
        $address = Setting::get('contact_address')
            ?: "Arnavutköy, Arnavutköy Dere Sok. No:44, 34345 Beşiktaş/İstanbul";
        $mapUrl  = Setting::get('contact_map_url')
            ?: 'https://www.google.com/maps/place/CBA/@41.069054,29.0435878,17z/data=!3m1!4b1!4m6!3m5!1s0x14cab61f875e579b:0xd5a86a5695ad26b6!8m2!3d41.069054!4d29.0435878!16s%2Fg%2F11g6xwv3vq';

        return view('contact.index', [
            'email'         => $email,
            'phone'         => $phone,
            'phoneDisplay'  => $this->formatPhone($phone),
            'phoneTel'      => $this->telHref($phone),
            'address'       => $address,
            'mapUrl'        => $mapUrl,
        ]);
    }

    private function formatPhone(string $phone): string
    {
        $digits = preg_replace('/\D+/', '', $phone) ?? '';

        if (strlen($digits) === 11 && str_starts_with($digits, '0')) {
            return substr($digits, 0, 4) . ' ' . substr($digits, 4, 3) . ' ' . substr($digits, 7, 2) . ' ' . substr($digits, 9, 2);
        }

        return $phone;
    }

    private function telHref(string $phone): string
    {
        $digits = preg_replace('/\D+/', '', $phone) ?? '';

        if (str_starts_with($digits, '0')) {
            $digits = '90' . substr($digits, 1);
        } elseif (! str_starts_with($digits, '90')) {
            $digits = '90' . $digits;
        }

        return 'tel:+' . $digits;
    }
}
